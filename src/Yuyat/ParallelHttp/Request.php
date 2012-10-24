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
    public function __construct($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }
}
