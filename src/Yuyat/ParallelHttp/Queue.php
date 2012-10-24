<?php
/**
 * This file is part of ParallelHttp.
 *
 * (c) Yuya Takeyama
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Queue data structure
 *
 * @author Yuya Takeyama
 */
class Yuyat_ParallelHttp_Queue
{
    private $data;

    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    public function enqueue($value)
    {
        $this->data[] = $value;
    }

    public function dequeue()
    {
        return array_shift($this->data);
    }

    public function toArray()
    {
        return $this->data;
    }
}
