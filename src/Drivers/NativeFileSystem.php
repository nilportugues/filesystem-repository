<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 6/02/16
 * Time: 14:03.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Foundation\Infrastructure\Model\Repository\FileSystem\Drivers;

use NilPortugues\Foundation\Infrastructure\Model\Repository\FileSystem\Contracts\FileSystem;
use RuntimeException;

/**
 * Class NativeFileSystem.
 */
class NativeFileSystem implements FileSystem
{
    const EXTENSION = '.dbdata';

    /**
     * @var string
     */
    private $baseDir;

    /**
     * NativeFileSystem constructor.
     *
     * @param $baseDir
     */
    public function __construct($baseDir)
    {
        $this->baseDir = realpath($baseDir);

        if (false === file_exists($this->baseDir)) {
            throw new RuntimeException(
               sprintf('Provided base directory \'%s\' does not exist', $baseDir)
           );
        }
    }

    /**
     * Reads a file from the file system.
     *
     * @param string $filePath
     *
     * @return mixed
     */
    public function read($filePath)
    {
        $filePath = $this->calculateFilePath($filePath);

        if (false === file_exists($filePath)) {
            return;
        }

        return unserialize(file_get_contents($filePath));
    }

    /**
     * @param $id
     *
     * @return string
     */
    private function calculateFilePath($id)
    {
        return $this->baseDir()
        .DIRECTORY_SEPARATOR
        .$this->getDirectoryHash($id)
        .DIRECTORY_SEPARATOR
        .$id
        .self::EXTENSION;
    }

    /**
     * Returns the base directory.
     *
     * @return string
     */
    public function baseDir()
    {
        return $this->baseDir;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function getDirectoryHash($key)
    {
        $key = \md5($key);
        $level1 = \substr($key, 0, 1);
        $level2 = \substr($key, 1, 1);
        $level3 = \substr($key, 2, 1);
        $directoryHash = $level1.DIRECTORY_SEPARATOR.$level2.DIRECTORY_SEPARATOR.$level3;

        return $directoryHash;
    }

    /**
     * Writes a file to the file system.
     *
     * @param string $filePath
     * @param string $contents
     *
     * @return bool
     */
    public function write($filePath, $contents)
    {
        $this->createDirectory($this->getDirectoryHash($filePath));
        $filePath = $this->calculateFilePath($filePath);

        return false !== file_put_contents($filePath, serialize($contents), FILE_APPEND | LOCK_EX);
    }

    /**
     * @param $filePath
     */
    private function createDirectory($filePath)
    {
        $filePath = $this->baseDir().DIRECTORY_SEPARATOR.$filePath;

        if (false === file_exists($filePath)) {
            mkdir($filePath, 0755, true);
        }
    }

    /**
     * Returns a flat array containing a list of files in a directory.
     * Files are searched recursively.
     *
     * @return array
     */
    public function files()
    {
        $files = [];
        $directory = $this->baseDir();
        $this->filesRecursively($directory, $files);

        return $files;
    }

    /**
     * @param string $directory
     * @param array  $files
     */
    private function filesRecursively($directory, array &$files)
    {
        foreach (glob("{$directory}/*") as $file) {
            if (\is_dir($file)) {
                $this->filesRecursively($file, $files);
            } else {
                $files[] = unserialize(file_get_contents($file));
            }
        }
    }

    /**
     * Deletes a file from the file system.
     *
     * @param string $filePath
     */
    public function delete($filePath)
    {
        if ($this->exists($filePath)) {
            $filePath = $this->calculateFilePath($filePath);
            unlink($filePath);
        }
    }

    /**
     * @param string $filePath
     *
     * @return bool
     */
    public function exists($filePath)
    {
        return file_exists($this->calculateFilePath($filePath));
    }

    /**
     * Deletes all file from the base directory given the current file system.
     */
    public function deleteAll()
    {
        $directory = $this->baseDir();
        $this->deleteAllRecursively($directory);
    }

    /**
     * @param string $directory
     */
    private function deleteAllRecursively($directory)
    {
        foreach (glob("{$directory}/*") as $file) {
            if (\is_dir($file)) {
                $this->deleteAllRecursively($file);
            } else {
                unlink($file);
            }
        }

        if ($this->baseDir() !== $directory) {
            rmdir($directory);
        }
    }
}
