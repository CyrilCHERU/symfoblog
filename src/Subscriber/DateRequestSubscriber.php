<?php

namespace App\Subscriber;

use DateTime;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class DateRequestSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => ['addDateToAttributes', 1]
        ];
    }

    public function addDateToAttributes(ControllerEvent $event)
    {
        $request = $event->getRequest();
        $date = (new DateTime())->format("d/m/Y");

        $request->attributes->set('date', $date);
    }
}
