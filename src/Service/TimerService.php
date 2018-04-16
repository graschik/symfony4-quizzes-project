<?php

namespace App\Service;


use App\Entity\Timer;

class TimerService
{
    private $timer;

    public function __construct()
    {
        $this->timer = new Timer();
    }

    public function startTimer()
    {
        $this->timer->setStart(time());
    }

    public function stopTimer()
    {
        $this->timer->setEnd(time());

        $this
            ->timer
            ->setDifference($this->timer->getEnd() - $this->timer->getStart());

        $this
            ->timer
            ->setDuration($this->timer->getDuration() + $this->timer->getDifference());
    }

    public function getTimerDuration(){
        return $this->timer->getDuration();
    }
}