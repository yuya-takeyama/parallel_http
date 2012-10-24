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
    public function __construct()
    {
        $this->poller = new Yuyat_ParallelHttp_Poller;
    }

    public function addRequeset(Yuyat_ParallelHttp_Requeset $request)
    {
        $this->poller->addRequest($request);
    }

    public function removeRequeset(Yuyat_ParallelHttp_Requeset $request)
    {
        $this->poller->removeRequest($request);
    }

    public function run()
    {
        while ($this->poller->isUnfinished()) {
            if ($this->poller->poll()) {
                $requests = $this->poller->getFinishedRequests();

                foreach ($requests as $request) {
                    $request->handleResponse();
                }
            }
        }
    }
}
