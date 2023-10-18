# piyo2/path

Path utilities for PHP.

## Installation

```bash
composer require piyo2/path
```

## Usage

### Join paths

```php
use piyo2\util\path\Path;

Path::join('foo', 'bar'); // => 'foo/bar'
Path::join('foo', 'bar', 'baz'); // => 'foo/bar/baz'
Path::join('/foo', 'bar'); // => '/foo/bar'
Path::join('./foo', 'bar'); // => './foo/bar'
Path::join('/foo/bar/baz', '../qux'); // => '/foo/bar/qux'
```

### Sanitize file name

```php
use piyo2\util\path\Path;

Path::sanitizeFileName('foo'); // => 'foo'
Path::sanitizeFileName('f/o\\o<b>a|r'); // => 'f_o_o_b_a_r'
Path::sanitizeFileName('.foo'); // => 'foo'
Path::sanitizeFileName('foo..bar'); // => 'foo__bar'

// Allow beginning dot
Path::sanitizeFileName('.foo', true); // => '.foo'
```
