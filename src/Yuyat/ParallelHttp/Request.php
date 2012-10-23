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
 * HTTP Request
 *
 * @author Yuya Takeyama
 */
class Yuyat_ParallelHttp_Request extends Edps_EventEmitter
{
    /**
     * @var Yuyat_ParallelHttp_EventLoop
     */
    private $loop;

    /**
     * @var array
     */
    private $options;

    public function __construct(Yuyat_ParallelHttp_EventLoop $loop, $options)
    {
        $this->loop    = $loop;
        $this->options = $options;
    }
}
