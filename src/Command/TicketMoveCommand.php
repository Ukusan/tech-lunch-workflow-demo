<?php

namespace App\Command;

use App\Constant\PRPlace;
use App\Repository\TicketRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Workflow\StateMachine;

class TicketMoveCommand extends Command
{
    protected static $defaultName = 'app:ticket:move';
    protected static $defaultDescription = 'Add a short description for your command';

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
            ->addArgument('ticketLabel', InputArgument::REQUIRED, 'Ticket label')
            ->addArgument('action', InputArgument::REQUIRED, 'action')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $ticketLabel = $input->getArgument('ticketLabel');
        $action = $input->getArgument('action');

        $ticket = $this->ticketRepository->findOneBy(
            [
                'label' => $ticketLabel
            ]
        );

        if (is_null($ticket)) {
            $io->error('Ticket not found');
            return Command::FAILURE;
        }

        $this->stateMachine->apply($ticket, $action);

        $this->ticketRepository->save($ticket);

        return Command::SUCCESS;
    }
}
