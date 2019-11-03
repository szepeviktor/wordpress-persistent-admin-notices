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
```
