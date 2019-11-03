# Persistent admin notices for WordPress

```php
// Use Composer instead:  composer require szepeviktor/persistent-admin-notices
// require __DIR__ . '/vendor/szepeviktor/persistent-admin-notices/src/PersistentNotices.php';

use WordPress\Admin\PersistentNotices;

// Fire it up!
new PersistentNotices();

// New notice.
PersistentNotices::add('slug', 'Something needs your attention!');

// New error notice.
PersistentNotices::error('slug', 'An error needs to be resolved!');

// Delete notice.
PersistentNotices::remove('slug');

// Customize notice.
PersistentNotices::add(
    'slug',
    'Something very special needs your attention!',
    [
        'expiration' => PersistentNotices::PERSISTENT, // Expiration in seconds or PersistentNotices::ONCE
        'type'       => 'info',           // Notice type: info, succes, warning, error.
        'capability' => 'manage_options', // WordPress capability to receive notifications.
        'priority'   => 10,               // Used to order notices.
        'classes'    => '',               // Override CSS classes.
        'noticeHtml' => '',               // Override full notice HTML.
    ]
);
```

### What is the goal here??

When your plugin or theme handles admin notices you have to
make a calculation or decision and display the notice **every time**.

If you use this package you have to make your calculation only once and

1. Leave the notice there
1. Or let the notice expire
1. Or remove it at a later event

Additional benefit is to display notices in WordPress cron jobs and AJAX actions or REST calls.

### Why not ... ?

- Dismissable notices cloud be very easily... dismissed.
- Notices on specific Admin Pages may be overlooked.
- Show notices to only one type of user as this package is not a message broker.

### Alternatives

More wordpressy solution: https://github.com/WPTRT/admin-notices
