<?php

declare(strict_types=1);

namespace WordPress\Admin;

class PersistentNotices
{
    /**
     * No expiration constant.
     *
     * @var int
     */
    public const PERSISTENT = 0;

    /**
     * Transient prefix.
     *
     * @var string
     */
    protected const PREFIX = 'persistent_admin_notice_';

    /**
     * Transient list key.
     *
     * @var string
     */
    protected const LIST_KEY = 'list__of__notices';

    public function __construct()
    {
        if ((\defined('WP_INSTALLING') && true === WP_INSTALLING) || !\is_admin() || \wp_doing_ajax()) {
            return;
        }

        \add_action('admin_notices', [$this, 'show']);
    }

    /**
     * Public API to add an admin notice.
     *
     * For expiration time use PersistentNotices::PERSISTENT, MINUTE_IN_SECONDS, HOUR_IN_SECONDS,
     * DAY_IN_SECONDS, WEEK_IN_SECONDS, MONTH_IN_SECONDS and YEAR_IN_SECONDS
     */
    public static function add(string $name, string $message, array $args = []): void
    {
        $defaultArgs = [
            'expiration' => self::PERSISTENT,
            'type' => 'info',
            'capability' => 'manage_options',
            'priority' => 10,
            'classes' => '',
            // TODO 'iconUrl' => '', in <p> - 32×32 - vertical-align: middle; margin-right: 1em;
            'noticeHtml' => '',
        ];

        $name = \sanitize_key($name);
        if ('' === $name || '' === \trim($message)) {
            // TODO Signal error
            return;
        }
        $args = \array_merge($defaultArgs, $args);

        // Do not show for users without this capability.
        if (!\current_user_can($args['capability'])) {
            return;
        }

        if ('' === $args['classes']) {
            $args['classes'] = self::getClassesfromType($args['type']);
        }
        if ('' === $args['noticeHtml']) {
            $args['noticeHtml'] = \sprintf(
                '<div class="%s"><p>%s</p></div>',
                \esc_attr($args['classes']),
                \esc_html($message)
            );
        }

        self::addToNoticeList($name, $args['noticeHtml'], $args['expiration'], $args['priority']);
    }

    /**
     * Public API to remove an admin notice.
     */
    public static function remove(string $name): void
    {
        self::removeFromNoticeList($name);
    }

    /**
     * Display admin notices in admin_notices hook.
     */
    public function show(): void
    {
        $list = \get_transient(self::PREFIX . self::LIST_KEY);
        if (false === $list) {
            return;
        }
        $priorities = \array_column($list, 'priority');
        $names = \array_column($list, 'name');
        // Sort by priority.
        \array_multisort($priorities, SORT_ASC, SORT_NUMERIC, $names);

        \array_walk($names, function ($name) {
            print \get_transient(self::PREFIX . $name);
        });
    }

    protected static function getClassesfromType(string $type): string
    {
        switch ($type) {
            case 'error':
                return 'notice notice-error';
            case 'warning':
                return 'notice notice-warning';
            case 'success':
                return 'notice notice-success';
            case 'info':
            default:
                return 'notice notice-info';
        }
    }

    protected static function addToNoticeList(string $name, string $html, int $expiration, int $priority): void
    {
        $list = \get_transient(self::PREFIX . self::LIST_KEY);
        if (false === $list) {
            $list = [];
        }
        $list[] = ['name' => $name, 'priority' => $priority];

        // Save the notice.
        \set_transient(self::PREFIX . $name, $html, $expiration);

        // Save the notice list.
        \set_transient(self::PREFIX . self::LIST_KEY, \array_unique($list), self::PERSISTENT);
    }

    protected static function removeFromNoticeList(string $name): void
    {
        $list = \get_transient(self::PREFIX . self::LIST_KEY);
        if (false === $list) {
            return;
        }

        $index = \array_search($name, \array_column($list, 'name'));
        // Not found.
        if (false === $index) {
            return;
        }

        unset($list[$index]);

        // Save the notice list.
        \set_transient(self::PREFIX . self::LIST_KEY, $list, self::PERSISTENT);

        // Delete the notice.
        \delete_transient(self::PREFIX . $name);
    }
}
