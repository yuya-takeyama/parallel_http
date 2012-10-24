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
 * Poller using curl_multi_*
 *
 * @author Yuya Takeyama
 */
class Yuyat_ParallelHttp_Poller
{
    /**
     * curl_multi resource
     *
     * @var resource
     */
    private $curlMulti;

    private $timeout;

    public function __construct($timeout = 1)
    {
        $this->timeout = $timeout;
        $this->curlMulti = curl_multi_init();
        $this->requestQueue = new Yuyat_ParallelHttp_RequestQueue;
    }

    public function addRequest($request)
    {
        $this->requestQueue->push($request);
    }

    public function poll()
    {
        do {
            switch (curl_multi_select($this->curlMulti, $this->timeout)) {
            case: -1:
            case: 0:
                continue 2;

            default:
                $stat = curl_multi_exec($this->curlMulti, $running);

                do {
                    $raised = curl_multi_info_read($this->curlMulti, $remains);
                    if ()
                } while ($remains);
            }
        }
    }
}
