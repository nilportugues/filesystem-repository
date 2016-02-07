<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/02/16
 * Time: 12:18.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Example\Repository;

class Identity implements \NilPortugues\Foundation\Domain\Model\Repository\Contracts\Identity
{
    /**
     * @var string
     */
    private $id;

    /**
     * Identity constructor.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = (string) $id;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->id;
    }
}
