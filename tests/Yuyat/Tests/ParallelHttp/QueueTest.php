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
 * Unit-tests for Yuyat_ParallelHttp_Queue
 *
 * @author Yuya Takeyama
 */
class Yuyat_Tests_ParallelHttp_QueueTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function toArray_should_be_empty_array_by_default()
    {
        $queue = $this->createQueue();

        $this->assertEquals(array(), $queue->toArray());
    }

    /**
     * @test
     */
    public function toArray_should_has_enqueued_elements()
    {
        $queue = $this->createQueue();

        $queue->enqueue('foo');
        $queue->enqueue('bar');
        $queue->enqueue('baz');

        $this->assertEquals(array('foo', 'bar', 'baz'), $queue->toArray());
    }

    /**
     * @test
     */
    public function dequeue_should_be_first_element()
    {
        $queue = $this->createQueue();

        $queue->enqueue('foo');
        $queue->enqueue('bar');
        $queue->enqueue('baz');

        $this->assertEquals('foo', $queue->dequeue());

        return $queue;
    }

    /**
     * @test
     * @depends dequeue_should_be_first_element
     */
    public function dequeue_should_remove_first_element($queue)
    {
        $this->assertEquals(array('bar', 'baz'), $queue->toArray());
    }

    /**
     * @test
     */
    public function count_should_be_size_of_queue()
    {
        $queue = $this->createQueue();

        $queue->enqueue('foo');
        $queue->enqueue('bar');
        $queue->enqueue('baz');

        $this->assertEquals(3, count($queue));
    }

    private function createQueue($arr = array())
    {
        return new Yuyat_ParallelHttp_Queue($arr);
    }
}
