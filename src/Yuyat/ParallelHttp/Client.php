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

    public function request(array $options, $callback)
    {
        $request = new Yuyat_ParallelHttp_Request($options);

        $request->on('response', $callback);

        $this->loop->addRequest($request);

        return $request;
    }

    public function get($url, $callback)
    {
        $parsedUrl = parse_url($url);

        $path = $parsedUrl['path'];

        if (isset($parsedUrl['query']) && $parsedUrl['query'] !== '') {
            $path .= "?{$parsedUrl['query']}";
        }

        $options = array(
            'host' => $parsedUrl['host'],
            'path' => $parsedUrl['path'],
        );

        if (isset($parsedUrl['port'])) {
            $options['port'] = $parsedUrl['port'];
        }

        return $this->request($options, $callback);
    }
}
