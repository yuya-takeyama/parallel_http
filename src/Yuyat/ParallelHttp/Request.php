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
    private $host;

    private $port;

    private $path;

    private $method;

    private $headers;

    private $auth;

    public function __construct(array $options)
    {
        if (! array_key_exists('host', $options)) {
            throw new InvalidArgumentException('"host" is required');
        }

        $this->host = $options['host'];

        $this->method = isset($options['method']) ? strtoupper($options['method']) : 'GET';
        $this->port   = isset($options['port']) ? (int)$options['port'] : 80;
        $this->path   = isset($options['path']) ? (string)$options['path'] : '/';
        $this->auth   = isset($options['auth']) ? (string)$options['auth'] : null;
    }

    public function getMethod()
    {
        return $this->method;
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
}
