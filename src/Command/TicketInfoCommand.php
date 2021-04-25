<?php

namespace App\Command;

use App\Entity\Transition as EntityTransition;
use App\Repository\TicketRepository;
use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Workflow\StateMachine;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow;

class TicketInfoCommand extends Command
{
    protected static $defaultName = 'app:ticket:info';
    protected static $defaultDescription = 'Get ticket desctription';

    public function __construct(
    private TicketRepository $ticketRepository,
    private StateMachine $stateMachine
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('ticketLabel', InputArgument::REQUIRED, 'Ticket label');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $ticketLabel = $input->getArgument('ticketLabel');

        $ticket = $this->ticketRepository->findOneBy(
            [
                'label' => $ticketLabel
            ]
        );

        if (is_null($ticket)) {
            $io->error('Ticket not found');
            return Command::FAILURE;
        }

        $io->title("Ticket " . $ticket->getLabel());

        $infos = [
            'id' => $ticket->getId(),
            'label' => $ticket->getLabel(),
            'state' => $ticket->getCurrentPlace(),
            'possible actions' => implode(',', array_map(function (Transition $transition) {
                return $transition->getName();
            }, $this->stateMachine->getEnabledTransitions($ticket))),
            'content' => $ticket->getContent()
        ];

        foreach ($infos as $key => $info) {
            $io->writeln('<fg=green>' . $key . ':</>' . $info);
        }

        $io->title("Transitions");

        $table = new Table($output);
        $table
            ->setHeaders(['Date','From','To'])
            ->setRows(array_map(function (EntityTransition $transition) {
                return [
                    $transition->getTimeStamp()->format(DateTime::RFC850),
                    $transition->getStateForm(),
                    $transition->getStateTo()
                ];
            }, $ticket->getTransitions()->toArray()));
        ;
        $table->render();

        return Command::SUCCESS;
    }
}
