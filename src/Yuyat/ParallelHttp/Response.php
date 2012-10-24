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
 * HTTP Response
 *
 * @author Yuya Takeyama
 */
class Yuyat_ParallelHttp_Response
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $body;

    /**
     * @var bool
     */
    private $parsed = false;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function getStatusCode()
    {
        $this->parse();

        return $this->statusCode;
    }

    public function getHeaders()
    {
        $this->parse();

        return $this->headers;
    }

    public function getBody()
    {
        $this->parse();

        return $this->body;
    }

    /**
     * This function is almost taken from below.
     *
     * @see http://snipplr.com/view/17242/parse-http-response/
     */
    private function parse() 
    {
        if ($this->parsed) {
            return;
        }

        $statusLine = strtok($this->content, "\n");

        if (preg_match('#^HTTP/\d+(?:\.\d+)*\s+(\d{3})#', $statusLine, $matches)) {
            $this->statusCode = (int)$matches[1];
        }

        $this->headers = array();
        $this->body = '';

        $str = strtok($this->content, "\n");
        $h = null;

        while ($str !== false) {
            if ($h and trim($str) === '') {                
                $h = false;
                continue;
            }

            if ($h !== false and false !== strpos($str, ':')) {
                $h = true;
                list($headername, $headervalue) = explode(':', trim($str), 2);
                $headername = strtolower($headername);
                $headervalue = ltrim($headervalue);
                if (isset($headers[$headername])) 
                    $this->headers[$headername] .= ',' . $headervalue;
                else 
                    $this->headers[$headername] = $headervalue;
            }

            if ($h === false) {
                $this->body .= $str."\n";
            }

            $str = strtok("\n");
        }

        $this->body = ltrim($this->body);
    }
}
