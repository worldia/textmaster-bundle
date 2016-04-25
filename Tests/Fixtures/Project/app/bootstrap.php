<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

if (!file_exists($file = __DIR__.'/../../../../vendor/autoload.php')) {
    throw new \RuntimeException('Install the dependencies to run the test suite.');
}

$loader = require $file;

$loader->add('Worldia\\',  array(realpath(dirname(__FILE__).'/../src')));
AnnotationRegistry::registerLoader('class_exists');
