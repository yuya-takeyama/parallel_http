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

    private $requestQueue;

    private $currentSize = 0;

    private $running;

    private $requests = array();

    public function __construct($timeout = 0.1, $parallels = 10)
    {
        $this->timeout = $timeout;
        $this->parallels = $parallels;
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

            $ch = curl_init();

            curl_setopt_array($ch, array(
                CURLOPT_URL            => $request->getUrl(),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER         => true,
                CURLINFO_HEADER_OUT    => true,
                CURLOPT_TIMEOUT        => 100,
                CURLOPT_CONNECTTIMEOUT => 10,
            ));

            curl_multi_add_handle($this->curlMulti, $ch);

            $this->requests[(int)$ch] = $request;

            $this->currentSize++;
        }

        if (is_null($this->running)) {
            curl_multi_exec($this->curlMulti, $this->running);
        }

        $result = curl_multi_select($this->curlMulti, $this->timeout);

            var_dump($result);
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
                    $body   = curl_multi_getcontent($curl);

                    if ($body === false) {
                        $request->emit('error', array($request, $info, $status, array(), $body));
                    } else {
                        $request->emit('response', array($request, $info, $status, array(), $body));
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
