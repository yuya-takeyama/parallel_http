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
 * Fixed queue data structure
 *
 * @author Yuya Takeyama
 */
class Yuyat_ParallelHttp_FixedQueue extends Yuyat_ParallelHttp_Queue
{
    /**
     * @var int
     */
    private $maxSize;

    public function __construct($maxSize, array $data = array())
    {
        $this->maxSize = $maxSize;

        parent::__construct($data);
    }

    public function enqueue($value)
    {
        $count = $this->count();

        if ($this->isFilled()) {
            throw new RuntimeException(sprintf('FixedQueue is filled with %d elements', $count));
        }

        return parent::enqueue($value);
    }

    public function isFilled()
    {
        $count = $this->count();

        return $count >= $this->maxSize;
    }
}
