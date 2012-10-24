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
 * Unit-tests for Yuyat_ParallelHttp_FixedQueue
 *
 * @author Yuya Takeyama
 */
class Yuyat_Tests_ParallelHttp_FixedQueueTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_behave_like_queue()
    {
        $queue = $this->createFixedQueue(3);

        $queue->enqueue('foo');
        $queue->enqueue('bar');
        $queue->enqueue('baz');

        $this->assertEquals(array('foo', 'bar', 'baz'), $queue->toArray());
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function it_should_throw_RuntimeException_when_overflow_its_size()
    {
        $queue = $this->createFixedQueue(3);

        $queue->enqueue('foo');
        $queue->enqueue('bar');
        $queue->enqueue('baz');
        $queue->enqueue('overflow');
    }

    /**
     * @test
     */
    public function isFilled_should_be_false_if_it_is_not_filled()
    {
        $queue = $this->createFixedQueue(3);

        $queue->enqueue('foo');
        $queue->enqueue('bar');

        $this->assertFalse($queue->isFilled());
    }

    /**
     * @test
     */
    public function isFilled_should_be_true_if_it_is_filled()
    {
        $queue = $this->createFixedQueue(3);

        $queue->enqueue('foo');
        $queue->enqueue('bar');
        $queue->enqueue('baz');

        $this->assertTrue($queue->isFilled());
    }

    private function createFixedQueue($size, array $data = array())
    {
        return new Yuyat_ParallelHttp_FixedQueue($size, $data);
    }
}
