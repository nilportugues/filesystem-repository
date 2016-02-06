<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 6/02/16
 * Time: 13:32
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Foundation\Infrastructure\Model\Repository\FileSystem\Contracts;

interface FileSystem
{
    /**
     * Returns the base directory.
     *
     * @return string
     */
    public function baseDir();

    /**
     * Reads a file from the file system.
     * 
     * @param string $filePath
     *
     * @return mixed
     */
    public function read($filePath);

    /**
     * Writes a file to the file system.
     *
     * @param string $filePath
     *
     * @return mixed
     */
    public function write($filePath);

    /**
     * Given an existing file path, move the file to the new file path.
     *
     * @param string $filePath
     * @param string $newFilePath
     *
     * @return void
     */
    public function move($filePath, $newFilePath);

    /**
     * Counts files in the directory recursively.
     *
     * @return mixed
     */
    public function count();

    /**
     * Returns a flat array containing a list of files in a directory.
     * Files are searched recursively.
     *
     * @return array
     */
    public function files();

    /**
     * Deletes a file from the file system.
     *
     * @param string $filePath
     */
    public function delete($filePath);

    /**
     * Deletes all file from the base directory given the current file system.
     *
     * @return void
     */
    public function deleteAll();
}