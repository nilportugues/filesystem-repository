<?php

use NilPortugues\Example\Repository\Identity;
use NilPortugues\Example\Repository\Color;
use NilPortugues\Foundation\Domain\Model\Repository\Filter;
use NilPortugues\Foundation\Domain\Model\Repository\Order;
use NilPortugues\Foundation\Domain\Model\Repository\Sort;
use NilPortugues\Foundation\Infrastructure\Model\Repository\FileSystem\Drivers\NativeFileSystem;
use NilPortugues\Foundation\Infrastructure\Model\Repository\FileSystem\FileSystemRepository;

include '../vendor/autoload.php';

$fileRepository = new FileSystemRepository(new NativeFileSystem(__DIR__.'/db/colors'));
$fileRepository->addAll([new Color('Red', 1), new Color('Blue', 2)]);

$filter = new Filter();
$filter->must()->equal('id', 1);

$sort = new Sort();
$sort->setOrderFor('name', new Order('DESC'));
print_r($fileRepository->findBy($filter, $sort));

$id = new Identity(2);
if ($fileRepository->exists($id)) {
    echo 'Color already exists'.PHP_EOL;
    print_r($fileRepository->find($id));
}

$fileRepository->removeAll();
