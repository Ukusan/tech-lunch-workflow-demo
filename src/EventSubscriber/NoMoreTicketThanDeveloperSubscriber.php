<?php

namespace App\EventSubscriber;

use App\Constant\PRPlace;
use App\Repository\DeveloperRepository;
use App\Repository\TicketRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\GuardEvent;

class NoMoreTicketThanDeveloperSubscriber implements EventSubscriberInterface
{

    public function __construct(
    private DeveloperRepository $developerRepository,
    private TicketRepository $ticketRepository
    ) {
    }

    public function onWorkflowPullRequestGuardSubmit(GuardEvent $event)
    {
        $nbDev = count($this->developerRepository->findAll());
        $nbTicketInCoding = count($this->ticketRepository->findBy(
            [
                'currentPlace' => PRPlace::CODING
            ]
        ));
        if ($nbDev <= $nbTicketInCoding) {
            $event->setBlocked(true, 'You cannot start more ticket than the number of developer');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.pull_request.guard.submit' => 'onWorkflowPullRequestGuardSubmit',
        ];
    }
}
