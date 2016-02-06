<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 6/02/16
 * Time: 14:03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Foundation\Infrastructure\Model\Repository\FileSystem\Drivers;

use NilPortugues\Foundation\Infrastructure\Model\Repository\FileSystem\Contracts\FileSystem;

/**
 * Class NativeFileSystem
 * @package NilPortugues\Foundation\Infrastructure\Model\Repository\FileSystem\Drivers
 */
class NativeFileSystem implements FileSystem
{
    const EXTENSION = '.dbdata.php';

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
        return file_get_contents($filePath);
    }

    /**
     * @param $id
     *
     * @return string
     */
    private function calculateFilePath($id)
    {
        return $this->baseDir()
        . DIRECTORY_SEPARATOR
        . $this->getDirectoryHash($id)
        . DIRECTORY_SEPARATOR
        . $id
        . self::EXTENSION;
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
        $key           = \md5($key);
        $level1        = \substr($key, 0, 1);
        $level2        = \substr($key, 1, 1);
        $level3        = \substr($key, 2, 1);
        $directoryHash = $level1 . DIRECTORY_SEPARATOR . $level2 . DIRECTORY_SEPARATOR . $level3;

        return $directoryHash;
    }

    /**
     * Writes a file to the file system.
     *
     * @param string $filePath
     *
     * @return mixed
     */
    public function write($filePath)
    {

    }

    /**
     * Given an existing file path, move the file to the new file path.
     *
     * @param string $filePath
     * @param string $newFilePath
     *
     * @return void
     */
    public function move($filePath, $newFilePath)
    {

    }

    /**
     * Counts files in the directory recursively.
     *
     * @return mixed
     */
    public function count()
    {

    }

    /**
     * Returns a flat array containing a list of files in a directory.
     * Files are searched recursively.
     *
     * @return array
     */
    public function files()
    {

    }

    /**
     * Deletes a file from the file system.
     *
     * @param string $filePath
     */
    public function delete($filePath)
    {

    }

    /**
     * Deletes all file from the base directory given the current file system.
     *
     * @return void
     */
    public function deleteAll()
    {

    }
}