<?php

namespace App\EventListener;

use DateTime;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class TimeRequestListener
{
    public function addTimeToAttributes(ControllerEvent $event)
    {
        $request = $event->getRequest();
        $time = (new DateTime())->format("H:i");

        $request->attributes->set('time', $time);
    }
}
