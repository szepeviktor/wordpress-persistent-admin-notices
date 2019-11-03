# Persistent admin notices for WordPress

```php
// Use Composer instead!
// require __DIR__ . '/vendor/szepeviktor/persistent-admin-notices/src/PersistentNotices.php';

use WordPress\Admin\PersistentNotices;

// Fire it up!
new PersistentNotices();

// New notice.
PersistentNotices::add('slug', 'Something needs your attention!');

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
