# FileSystem Repository
![PHP7 Tested](http://php-eye.com/badge/nilportugues/filesystem-repository/php70.svg)
[![Build Status](https://travis-ci.org/PHPRepository/filesystem-repository.svg)](https://travis-ci.org/PHPRepository/filesystem-repository) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nilportugues/php-filesystem-repository/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nilportugues/php-filesystem-repository/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/76361296-1319-4b83-a79a-63827f0d75fd/mini.png?)](https://insight.sensiolabs.com/projects/76361296-1319-4b83-a79a-63827f0d75fd) [![Latest Stable Version](https://poser.pugx.org/nilportugues/filesystem-repository/v/stable)](https://packagist.org/packages/nilportugues/filesystem-repository) [![Total Downloads](https://poser.pugx.org/nilportugues/filesystem-repository/downloads)](https://packagist.org/packages/nilportugues/filesystem-repository) [![License](https://poser.pugx.org/nilportugues/filesystem-repository/license)](https://packagist.org/packages/nilportugues/filesystem-repository)
[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif)](https://paypal.me/nilportugues)

FileSystem Repository using *[nilportugues/repository](https://github.com/nilportugues/php-repository)* as foundation.

FileSystem Repository allows you to fetch, paginate and operate with data easily without adding overhead and following good practices.

Table of Contents
=================

  * [Features](#features)
  * [Installation](#installation)
  * [Usage](#usage)
  * [Repository](#repository)
  * [Data Operations](#data-operations)
    * [Fields](#fields)
    * [Filtering](#filtering)
    * [Pagination](#pagination)
      * [Pageable](#pageable)
      * [Page object](#page-object)
    * [Sorting](#sorting)
      * [Ordering](#ordering)


## Features

- **Repository pattern right from the start.**
- **All operations available from the beginning:**
  - Search the repository using PHP objects
  - Filtering is available using the Filter object.
  - Fetching certaing fields is available using the Fields Object.
  - Pagination is solved available using the Page and Pageable objects.
- **Want to change persistence layer? Provided repository alternatives are:**
  - *[InMemoryRepository](https://github.com/PHPRepository/repository)*: for testing purposes
  - *[SQL Repository](https://github.com/PHPRepository/sql-repository)*: migration to SQL databases is possible.
  - *[MongoDBRepository](https://github.com/PHPRepository/mongodb-repository)*: because your schema keeps changing
- **Caching layer required? Easily to add!**
  - Require the *[Repository Cache](https://github.com/PHPRepository/repository-cache)* package from Composer to add consistent caching to all operations.

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

# Repository 

The repository class implements all the methods required to interact and filter your data. 

- `public function add($value)`
- `public function addAll(array $values)`
- `public function remove(Identity $id)`
- `public function removeAll(Filter $filter = null)`
- `public function transactional(callable $transaction)`
- `public function count(Filter $filter = null)`
- `public function find(Identity $id, Fields $fields = null)`
- `public function findBy(Filter $filter = null, Sort $sort = null, Fields $fields = null)`
- `public function findByDistinct(Fields $distinctFields, Filter $filter = null, Sort $sort = null, Fields $fields = null)`
- `public function findAll(Pageable $pageable = null)`
- `public function count(Filter $filter = null)`
- `public function exists(Identity $id)`

---

# Data Operations

All data can be extracted by fields name, using filters, applying ordering and pages, capable of applying fields, filters and ordering criteria.

## Fields

Selecting by field will make hydratation fail. Currently partial object hydratation is not supported.

**Class:** `NilPortugues\Foundation\Domain\Model\Repository\Fields`

**Methods:**
- `public function __construct(array $fields = [])`
- `public function add($field)`
- `public function get()`

## Filtering

**Class:** `NilPortugues\Foundation\Domain\Model\Repository\Filter`

**Methods:**
- `public function filters()`
- `public function must()`
- `public function mustNot()`
- `public function should()`
- `public function clear()`
    
For **must()**, **mustNot()** and **should()**, the methods available are:

- `public function notStartsWith($filterName, $value)`
- `public function notEndsWith($filterName, $value)`
- `public function notEmpty($filterName)`
- `public function empty($filterName)`
- `public function startsWith($filterName, $value)`
- `public function endsWith($filterName, $value)`
- `public function equal($filterName, $value)`
- `public function notEqual($filterName, $value)`
- `public function includeGroup($filterName, array $value)`
- `public function notIncludeGroup($filterName, array $value)`
- `public function range($filterName, $firstValue, $secondValue)`
- `public function notRange($filterName, $firstValue, $secondValue)`
- `public function notContain($filterName, $value)`
- `public function contain($filterName, $value)`
- `public function beGreaterThanOrEqual($filterName, $value)`
- `public function beGreaterThan($filterName, $value)`
- `public function beLessThanOrEqual($filterName, $value)`
- `public function beLessThan($filterName, $value)`
- `public function clear()`
- `public function get()`
- `public function hasEmpty($filterName)` 

## Pagination 

Pagination is handled by two objects, `Pageable` that has the requirements to paginate, and `Page` that it's actually the page with the page data, such as page number, total number, and the data.

### Pageable

**Class:** `NilPortugues\Foundation\Domain\Model\Repository\Pageable`

**Methods:**
- `public function __construct($pageNumber, $pageSize, Sort $sort = null, Filter $filter = null, Fieldse $fields = null)`
- `public function offset()`
- `public function pageNumber()`
- `public function sortings()`
- `public function next()`
- `public function pageSize()`
- `public function previousOrFirst()`
- `public function hasPrevious()`
- `public function first()`
- `public function filters()`
- `public function fields()`

### Page object

**Class:** `NilPortugues\Foundation\Domain\Model\Repository\Page`

**Methods:**
- `public function __construct(array $elements, $totalElements, $pageNumber, $totalPages, Sort $sort = null, Filter $filter = null, Fields $fields = null)`
- `public function content()`
- `public function hasPrevious()`
- `public function isFirst()`
- `public function isLast()`
- `public function hasNext()`
- `public function pageSize()`
- `public function pageNumber()`
- `public function totalPages()`
- `public function nextPageable()`
- `public function sortings()`
- `public function filters()`
- `public function fields()`
- `public function previousPageable()`
- `public function totalElements()`
- `public function map(callable $converter)`

## Sorting

**Class:** `NilPortugues\Foundation\Domain\Model\Repository\Sort`

**Methods:**
- `public function __construct(array $properties = [], Order $order = null)`
- `public function andSort(SortInterface $sort)`
- `public function orders()`
- `public function equals(SortInterface $sort)`
- `public function orderFor($propertyName)`
- `public function setOrderFor($propertyName, Order $order)`
- `public function property($propertyName)`

### Ordering

Sometimes you want to sort by multiple fields, this is where Order comes in play.

**Class**: `NilPortugues\Foundation\Domain\Model\Repository\Order`

**Methods:**
- `public function __construct($direction)`
- `public function isDescending()`
- `public function isAscending()`
- `public function __toString()`
- `public function equals($object)`
- `public function direction()`

---

# Quality

To run the PHPUnit tests at the command line, go to the tests directory and issue phpunit.

This library attempts to comply with [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/), [PSR-4](http://www.php-fig.org/psr/psr-4/).

If you notice compliance oversights, please send a patch via [Pull Request](https://github.com/nilportugues/php-filesystem-repository/pulls).


# Contribute

Contributions to the package are always welcome!

* Report any bugs or issues you find on the [issue tracker](https://github.com/nilportugues/php-filesystem-repository/issues/new).
* You can grab the source code at the package's [Git repository](https://github.com/nilportugues/php-filesystem-repository).


# Support

Get in touch with me using one of the following means:

 - Emailing me at <contact@nilportugues.com>
 - Opening an [Issue](https://github.com/nilportugues/php-filesystem-repository/issues/new)


# Authors

* [Nil Portugués Calderó](http://nilportugues.com)
* [The Community Contributors](https://github.com/nilportugues/php-filesystem-repository/graphs/contributors)


# License
The code base is licensed under the [MIT license](LICENSE).
