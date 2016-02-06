<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 6/02/16
 * Time: 13:05
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Foundation\Infrastructure\Model\Repository\FileSystem;

use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Fields;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Filter;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Identity;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Page;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Pageable;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\PageRepository;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\ReadRepository;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Sort;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\WriteRepository;
use NilPortugues\Foundation\Infrastructure\Model\Repository\FileSystem\Contracts\FileSystem;
use NilPortugues\Foundation\Infrastructure\Model\Repository\InMemory\Filter as InMemoryFilter;
use NilPortugues\Foundation\Infrastructure\Model\Repository\InMemory\Sorter as InMemorySorter;

/**
 * Class FileSystemRepository
 */
class FileSystemRepository implements ReadRepository, WriteRepository, PageRepository
{
    /**
     * @var FileSystem
     */
    protected $fileSystem;

    /**
     * FileSystemRepository constructor.
     *
     * @param FileSystem $fileSystem
     */
    public function __construct(FileSystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * Retrieves an entity by its id.
     *
     * @param Identity    $id
     * @param Fields|null $fields
     *
     * @return mixed
     */
    public function find(Identity $id, Fields $fields = null)
    {
        $fileContent = $this->fileSystem->read($id->id());

        return unserialize($fileContent);
    }

    /**
     * Returns all instances of the type.
     *
     * @param Filter|null $filter
     * @param Sort|null   $sort
     * @param Fields|null $fields
     *
     * @return array
     */
    public function findBy(Filter $filter = null, Sort $sort = null, Fields $fields = null)
    {
        $allFiles = $this->fileSystem->files();

        foreach($allFiles as &$fileContent) {
            $fileContent = unserialize($fileContent);
        }

        $allFiles = InMemoryFilter::filter($allFiles, $filter);
        $allFiles = InMemorySorter::sort($allFiles, $sort);

        return $allFiles;
    }

    /**
     * Returns the total amount of elements in the repository given the restrictions provided by the Filter object.
     *
     * @param Filter|null $filter
     *
     * @return int
     */
    public function count(Filter $filter = null)
    {
        $allFiles = $this->fileSystem->files();

        if($filter) {
            $allFiles = InMemoryFilter::filter($allFiles, $filter);
        }

        return count($allFiles);
    }

    /**
     * Returns whether an entity with the given id exists.
     *
     * @param $id
     *
     * @return bool
     */
    public function exists(Identity $id)
    {

    }

    /**
     * Adds a new entity to the storage.
     *
     * @param Identity $value
     *
     * @return mixed
     */
    public function add(Identity $value)
    {

    }

    /**
     * Adds a collections of entities to the storage.
     *
     * @param array $values
     *
     * @return mixed
     */
    public function addAll(array $values)
    {

    }

    /**
     * Removes the entity with the given id.
     *
     * @param $id
     */
    public function remove(Identity $id)
    {

    }

    /**
     * Removes all elements in the repository given the restrictions provided by the Filter object.
     * If $filter is null, all the repository data will be deleted.
     *
     * @param Filter $filter
     *
     * @return bool
     */
    public function removeAll(Filter $filter = null)
    {

    }

    /**
     * Returns a Page of entities meeting the paging restriction provided in the Pageable object.
     *
     * @param Pageable $pageable
     *
     * @return Page
     */
    public function findAll(Pageable $pageable = null)
    {

    }
}