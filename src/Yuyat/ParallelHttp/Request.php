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
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $auth;

    /**
     * @var resource (curl)
     */
    private $curl;

    public function __construct(array $options)
    {
        if (! array_key_exists('host', $options)) {
            throw new InvalidArgumentException('"host" is required');
        }

        $this->host = $options['host'];

        $this->method  = isset($options['method']) ? strtoupper($options['method']) : 'GET';
        $this->headers = isset($options['headers']) ? $options['headers'] : array();
        $this->port    = isset($options['port']) ? (int)$options['port'] : 80;
        $this->path    = isset($options['path']) ? (string)$options['path'] : '/';
        $this->auth    = isset($options['auth']) ? (string)$options['auth'] : null;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getUrl()
    {
        $url = 'http://';

        if (isset($this->auth)) {
            $url .= "{$this->auth}@";
        }

        $url .= $this->host;

        if ($this->port !== 80) {
            $url .= ":{$this->port}";
        }

        $url .= $this->path;

        return $url;
    }

    public function getCurlResource()
    {
        if (is_null($this->curl)) {
            $this->curl = curl_init();

            curl_setopt_array($this->curl, array(
                CURLOPT_URL            => $this->getUrl(),
                CURLOPT_CUSTOMREQUEST  => $this->getMethod(),
                CURLOPT_HTTPHEADER     => $this->getHeaders(),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER         => true,
                CURLINFO_HEADER_OUT    => true,
                CURLOPT_TIMEOUT        => 10,
                CURLOPT_CONNECTTIMEOUT => 10,
            ));
        }

        return $this->curl;
    }
}
