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
 * Unit-tests for Yuyat_ParallelHttp_Response
 *
 * @author Yuya Takeyama
 */
class Yuyat_Tests_ParallelHttp_ResponseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getStatusCode_should_be_its_status_code()
    {
        $res = $this->createResponse("HTTP/1.1 200 OK\r\nX-Powered-By: PHP\r\n\r\nHello, World!\n");

        $this->assertEquals(200, $res->getStatusCode());
    }

    /**
     * @test
     */
    public function getHeaders_should_be_its_headers()
    {
        $res = $this->createResponse("HTTP/1.1 200 OK\r\nX-Powered-By: PHP\r\nX-Foo: Bar\r\n\r\nHello, World!\n");

        $this->assertEquals(array('x-powered-by' => 'PHP', 'x-foo' => 'Bar'), $res->getHeaders());
    }

    /**
     * @test
     */
    public function getBody_should_be_its_body()
    {
        $res = $this->createResponse("HTTP/1.1 200 OK\r\nX-Powered-By: PHP\r\n\r\nHello, World!\n");

        $this->assertEquals("Hello, World!\n", $res->getBody());
    }

    private function createResponse($content)
    {
        return new Yuyat_ParallelHttp_Response($content);
    }
}
