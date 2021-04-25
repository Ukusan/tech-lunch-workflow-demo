<?php

namespace App\EventSubscriber;

use App\Entity\Transition;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\CompletedEvent;
use Symfony\Component\Workflow\Event\EnteredEvent;
use Symfony\Component\Workflow\Event\GuardEvent;

class TransitionStateSubscriber implements EventSubscriberInterface
{
    public function onWorkflowPullRequestTransition(CompletedEvent $event)
    {
        $ticket = $event->getSubject();
        $transition = $event->getTransition();

        $transition = (new Transition())
        ->setStateForm($transition->getFroms()[0])
        ->setStateTo($transition->getTos()[0])
        ->setTimeStamp(new DateTime());

        $ticket->addTransition($transition);
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.pull_request.completed' => 'onWorkflowPullRequestTransition',
        ];
    }
}
