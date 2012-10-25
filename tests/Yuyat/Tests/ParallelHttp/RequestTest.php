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
 * Unit-tests for Yuyat_ParallelHttp_Request
 *
 * @author Yuya Takeyama
 */
class Yuyat_Tests_ParallelHttp_RequestTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getUrl_should_be_its_url()
    {
        $request = $this->createRequest(array(
            'host' => 'example.net',
            'path' => '/path/to/document',
        ));

        $this->assertEquals('http://example.net/path/to/document', $request->getUrl());
    }

    /**
     * @test
     */
    public function getUrl_should_contain_port_number_if_it_is_not_80()
    {
        $request = $this->createRequest(array(
            'host' => 'example.net',
            'path' => '/path/to/document',
            'port' => 1234,
        ));

        $this->assertEquals('http://example.net:1234/path/to/document', $request->getUrl());
    }

    /**
     * @test
     */
    public function getUrl_should_contain_auth_param_if_it_is_set()
    {
        $request = $this->createRequest(array(
            'host' => 'example.net',
            'path' => '/path/to/document',
            'auth' => 'foo:bar',
        ));

        $this->assertEquals('http://foo:bar@example.net/path/to/document', $request->getUrl());
    }

    /**
     * @test
     */
    public function getHeaders_should_be_empty_array_by_default()
    {
        $request = $this->createRequest(array(
            'host' => 'example.net',
            'path' => '/path/to/document',
        ));

        $this->assertEquals(array(), $request->getHeaders());
    }

    /**
     * @test
     */
    public function getHeaders_should_be_array_specified_as_headers()
    {
        $headers = array(
            'Host'  => 'example.net',
            'X-Foo' => 'Bar',
        );

        $request = $this->createRequest(array(
            'host'    => 'example.net',
            'path'    => '/path/to/document',
            'headers' => $headers,
        ));

        $this->assertEquals($headers, $request->getHeaders());

    }

    /**
     * @test
     */
    public function getMethod_should_be_GET_by_deafult()
    {
        $request = $this->createRequest(array(
            'host' => 'example.net',
            'path' => '/path/to/document',
        ));

        $this->assertEquals('GET', $request->getMethod());
    }

    /**
     * @test
     */
    public function getMethod_should_be_string_specified_as_method()
    {
        $request = $this->createRequest(array(
            'host'   => 'example.net',
            'path'   => '/path/to/document',
            'method' => 'POST',
        ));

        $this->assertEquals('POST', $request->getMethod());
    }

    private function createRequest(array $options)
    {
        return new Yuyat_ParallelHttp_Request($options);
    }
}
