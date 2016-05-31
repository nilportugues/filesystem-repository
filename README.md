# FileSystem Repository
![PHP7 Tested](http://php-eye.com/badge/nilportugues/filesystem-repository/php70.svg)
[![Build Status](https://travis-ci.org/PHPRepository/php-filesystem-repository.svg)](https://travis-ci.org/PHPRepository/php-filesystem-repository) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nilportugues/php-filesystem-repository/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nilportugues/php-filesystem-repository/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/76361296-1319-4b83-a79a-63827f0d75fd/mini.png?)](https://insight.sensiolabs.com/projects/76361296-1319-4b83-a79a-63827f0d75fd) [![Latest Stable Version](https://poser.pugx.org/nilportugues/filesystem-repository/v/stable)](https://packagist.org/packages/nilportugues/filesystem-repository) [![Total Downloads](https://poser.pugx.org/nilportugues/filesystem-repository/downloads)](https://packagist.org/packages/nilportugues/filesystem-repository) [![License](https://poser.pugx.org/nilportugues/filesystem-repository/license)](https://packagist.org/packages/nilportugues/filesystem-repository)
[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif)](https://paypal.me/nilportugues)

FileSystem Repository using *[nilportugues/repository](https://github.com/nilportugues/php-repository)* as foundation.


## Installation

Use [Composer](https://getcomposer.org) to install the package:

```json
$ composer require nilportugues/filesystem-repository
```

## Usage

```php
<?php
use NilPortugues\Foundation\Infrastructure\Model\Repository\FileSystem\Drivers\NativeFileSystem;
use NilPortugues\Foundation\Infrastructure\Model\Repository\FileSystem\FileSystemRepository;

//-------------------------------------------------------------------
// Setting up the repository directory and how it will be access:
//-------------------------------------------------------------------
$baseDir = __DIR__.'/data/colors';
$fileSystem = new NativeFileSystem($baseDir);
$fileRepository = new FileSystemRepository($fileSystem);

//-------------------------------------------------------------------
// Create sample data
//-------------------------------------------------------------------
$red = new Color('Red', 1);
$blue = new Color('Blue', 2)
$fileRepository->addAll([$red, $blue]);

//-------------------------------------------------------------------
// Now let's try filtering by id
//-------------------------------------------------------------------
$filter = new Filter();
$filter->must()->equal('id', 1); //id is a Color property.

print_r($fileRepository->findBy($filter));

//-------------------------------------------------------------------
// Now let's try filtering by contaning 'e' in the name and sort them.
//-------------------------------------------------------------------
$filter = new Filter();
$filter->must()->contain('name', 'e'); //name is a Color property.

$sort = new Sort();
$sort->setOrderFor('name', new Order('DESC'));

print_r($fileRepository->findBy($filter, $sort)); // This will return both values.

//-------------------------------------------------------------------
//Lets remove all colors from the repository
//-------------------------------------------------------------------
$fileRepository->removeAll();
```


## Quality

To run the PHPUnit tests at the command line, go to the tests directory and issue phpunit.

This library attempts to comply with [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/), [PSR-4](http://www.php-fig.org/psr/psr-4/).

If you notice compliance oversights, please send a patch via [Pull Request](https://github.com/nilportugues/php-filesystem-repository/pulls).


## Contribute

Contributions to the package are always welcome!

* Report any bugs or issues you find on the [issue tracker](https://github.com/nilportugues/php-filesystem-repository/issues/new).
* You can grab the source code at the package's [Git repository](https://github.com/nilportugues/php-filesystem-repository).


## Support

Get in touch with me using one of the following means:

 - Emailing me at <contact@nilportugues.com>
 - Opening an [Issue](https://github.com/nilportugues/php-filesystem-repository/issues/new)


## Authors

* [Nil Portugués Calderó](http://nilportugues.com)
* [The Community Contributors](https://github.com/nilportugues/php-filesystem-repository/graphs/contributors)


## License
The code base is licensed under the [MIT license](LICENSE).
