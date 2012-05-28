Microdata
============

Microdata is a syntax for embedding machine-readable metadata in HTML.

Microdata is a PHP library for extracting microdata from HTML documents. It
is inspired by MicrodataJS, which is inspired by the native Microdata DOM API.

Example use:
```php
namespace Demos;

require __DIR__ . '/../src/Microdata/Reader/Document.php';
require __DIR__ . '/../src/Microdata/Reader/Element.php';
require __DIR__ . '/../src/Microdata/Reader.php';

use Microdata;

$r = new Microdata\Reader("http://www.imdb.com/title/tt0903747/");

$data = $r->read();

print_r($data);
```
Requirements
============

PHP 5.3 or higher