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
 * Event Loop which passes finished request to its handler
 *
 * @author Yuya Takeyama
 */
class Yuyat_ParallelHttp_EventLoop
{
    /**
     * curl_multi resource
     *
     * @var resource
     */
    private $curlMulti;

    private $parallels;

    /**
     * Interval time (in micro seconds)
     *
     * @var int
     */
    private $interval;

    /**
     * @var Yuyat_ParallelHttp_Queue
     */
    private $requestQueue;

    /**
     * @var int
     */
    private $currentSize = 0;

    /**
     * @var int
     */
    private $running;

    /**
     * Map of curl resource ID and its parent request
     *
     * @var array
     */
    private $requests = array();

    public function __construct(array $options = array())
    {
        $this->timeout   = isset($options['timeout']) ? (float)$options['timeout'] : 0.1;
        $this->parallels = isset($options['parallels']) ? $options['parallels'] : 10;
        $this->interval  = isset($options['interval']) ? $options['interval'] * 1000000 : null;

        $this->curlMulti = curl_multi_init();
        $this->requestQueue = new Yuyat_ParallelHttp_Queue;
    }

    public function addRequest(Yuyat_ParallelHttp_Request $request)
    {
        $this->requestQueue->enqueue($request);
    }

    public function run()
    {
        while ($this->isUnfinished()) {
            $this->runCurlMulti();
        }
    }

    public function runCurlMulti()
    {
        while ($this->currentSize < $this->parallels && count($this->requestQueue) > 0) {
            $request = $this->requestQueue->dequeue();
            $ch      = $request->getCurlResource();

            curl_multi_add_handle($this->curlMulti, $ch);

            $this->requests[(int)$ch] = $request;

            $this->currentSize++;
        }

        if (is_null($this->running)) {
            curl_multi_exec($this->curlMulti, $this->running);
        }

        if (isset($this->interval)) {
            $result = 1;

            usleep($this->interval);
        } else {
            $result = curl_multi_select($this->curlMulti, $this->timeout);
        }

        switch ($result) {
        case -1:
        case 0:
            continue;

        default:
            $stat = curl_multi_exec($this->curlMulti, $this->running);

            do {
                $raised = curl_multi_info_read($this->curlMulti, $remains);

                if ($raised) {
                    $curl    = $raised['handle'];
                    $request = $this->requests[(int)$curl];

                    $info = curl_getinfo($curl);

                    $status = $info['http_code'];
                    $content = curl_multi_getcontent($curl);

                    if ($status === 0) {
                        $error = new stdClass;
                        $error->curl = $curl;
                        $error->info = $info;

                        $request->emit('error', array($error));
                    } else {
                        $request->emit('response', array(new Yuyat_ParallelHttp_Response($content)));
                    }

                    curl_multi_remove_handle($this->curlMulti, $curl);

                    unset($this->requests[(int)$curl]);

                    $this->currentSize--;
                }
            } while ($remains);
        }

        return $this->running;
    }

    public function isUnfinished()
    {
        return count($this->requests) > 0 || count($this->requestQueue) > 0;
    }
}
