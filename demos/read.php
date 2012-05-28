<?php

namespace Demos;

require __DIR__ . '/../src/Microdata/Reader/Document.php';
require __DIR__ . '/../src/Microdata/Reader/Element.php';
require __DIR__ . '/../src/Microdata/Reader.php';

use Microdata;

$r = new Microdata\Reader("http://www.imdb.com/title/tt0903747/");

$data = $r->read();

print_r($data);