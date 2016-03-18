<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 6/02/16
 * Time: 13:05.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Foundation\Infrastructure\Model\Repository\FileSystem;

use NilPortugues\Assert\Assert;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Fields;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Filter;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Identity;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Page;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Pageable;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\PageRepository;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\ReadRepository;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Sort;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\WriteRepository;
use NilPortugues\Foundation\Domain\Model\Repository\Page as ResultPage;
use NilPortugues\Foundation\Infrastructure\Model\Repository\FileSystem\Contracts\FileSystem;
use NilPortugues\Foundation\Infrastructure\Model\Repository\InMemory\Filter as InMemoryFilter;
use NilPortugues\Foundation\Infrastructure\Model\Repository\InMemory\PropertyValue;
use NilPortugues\Foundation\Infrastructure\Model\Repository\InMemory\Sorter as InMemorySorter;

/**
 * Class FileSystemRepository.
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
        return $this->fileSystem->read($id->id());
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

        if ($filter) {
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
        return $this->fileSystem->exists($id);
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
        foreach ($values as $value) {
            Assert::isInstanceOf($value, Identity::class);
            $this->add($value);
        }
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
        $this->fileSystem->write($value->id(), $value);
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
        if (null === $filter) {
            $this->fileSystem->deleteAll();

            return true;
        }

        $elements = (array) $this->findBy($filter);
        foreach ($elements as $element) {
            $this->remove($element);
        }

        return true;
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

        if ($filter) {
            $allFiles = InMemoryFilter::filter($allFiles, $filter);
        }

        if ($sort) {
            $allFiles = InMemorySorter::sort($allFiles, $sort);
        }

        return $allFiles;
    }

    /**
     * Removes the entity with the given id.
     *
     * @param $id
     */
    public function remove(Identity $id)
    {
        $this->fileSystem->delete($id);
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
        if (null === $pageable) {
            $files = $this->findBy();

            return new ResultPage($files, count($files), 1, 1);
        }

        $results = $this->findBy($pageable->filters(), $pageable->sortings());

        if (0 !== count($pageable->distinctFields()->get())) {
            $results = $this->resultsWithDistinctFieldsOnly($pageable->distinctFields(), $results);
        }

        return new ResultPage(
            array_slice($results, $pageable->offset() - $pageable->pageSize(), $pageable->pageSize()),
            count($results),
            $pageable->pageNumber(),
            ceil(count($results) / $pageable->pageSize())
        );
    }

    /**
     * Repository data is added or removed as a whole block.
     * Must work or fail and rollback any persisted/erased data.
     *
     * @param callable $transaction
     *
     * @throws \Exception
     */
    public function transactional(callable $transaction)
    {
        $allFiles = $this->fileSystem->files();
        try {
            $transaction();
        } catch (\Exception $e) {
            foreach (array_diff($this->fileSystem->files(), $allFiles) as $item) {
                /* @var Identity $item */
                $this->remove($item);
            }
            throw $e;
        }
    }

    /**
     * Returns all instances of the type meeting $distinctFields values.
     *
     * @param Fields      $distinctFields
     * @param Filter|null $filter
     * @param Sort|null   $sort
     *
     * @return array
     */
    public function findByDistinct(Fields $distinctFields, Filter $filter = null, Sort $sort = null) {
        $results = $this->findBy($filter, $sort, $distinctFields);

        return $this->resultsWithDistinctFieldsOnly($distinctFields, $results);
    }

    /**
     * @param Fields $distinctFields
     * @param        $results
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function resultsWithDistinctFieldsOnly(Fields $distinctFields, $results)
    {
        $newResults = [];
        $valueHash = [];
        foreach ($results as $result) {
            $distinctValues = [];
            foreach ($distinctFields->get() as $field) {
                $distinctValues[$field] = PropertyValue::get($result, $field);
            }

            $hash = md5(serialize($distinctValues));
            if (false === in_array($hash, $valueHash)) {
                $valueHash[] = $hash;
                $newResults[] = $result;
            }
        }

        return $newResults;
    }
}
