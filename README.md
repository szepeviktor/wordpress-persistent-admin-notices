# Persistent admin notices for WordPress

```php
use WordPress\Admin\PersistentNotices;

// Fire it up!
new PersistentNotices();

// New notice.
PersistentNotices::add('slug', 'Something needs your attention!');

// Delete notice.
PersistentNotices::add('slug');
```
