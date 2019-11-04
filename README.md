# Persistent admin notices for WordPress

This package is for displaying admin notices for site-wide events not for individual user transactions.
Here are some examples.

- _[WP-Cron] Authentication token for XYZ API has expired._
- _All Editors! Please finish your articles by 8pm._
- _We have a new release v3.2.1. See new features at http://example.com/_
- _CSV import failed. We are out of sync!_
- _Incomplete ACF options: x, y, z_
- _All products are out of stock!_
- _Maximum X limit is reached. Please reduce X!_
- _John Doe is fired! Please do not contact him anymore._

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
- Showing notices to a Specific User would make this package a messaging system.

### Alternatives

More wordpressy and user transaction oriented solution: https://github.com/WPTRT/admin-notices
