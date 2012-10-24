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
 * HTTP Client
 *
 * @author Yuya Takeyama
 */
class Yuyat_ParallelHttp_Client
{
    /**
     * @var Yuyat_ParallelHttp_EventLoop
     */
    private $loop;

    public function __construct(Yuyat_ParallelHttp_EventLoop $loop)
    {
        $this->loop = $loop;
    }

    public function request($url, $callback)
    {
        $request = new Yuyat_ParallelHttp_Request($url);

        $request->on('response', $callback);

        $this->loop->addRequest($request);

        return $request;
    }
}
