<?php

namespace App\Command;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Workflow\StateMachine;

class TicketListCommand extends Command
{
    protected static $defaultName = 'app:ticket:list';
    protected static $defaultDescription = 'List of ticket';

    public function __construct(
    private StateMachine $stateMachine,
    private TicketRepository $ticketRepository
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription);
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tickets = $this->ticketRepository->findAll();
        $headers = $this->stateMachine->getDefinition()->getPlaces();

        $table = new Table($output);
        $table
            ->setHeaders($headers)
            ->setRows(array_map(function (Ticket $ticket) use ($headers) {
                return $this->createRow($ticket, $headers);
            }, $tickets));
        ;
        $table->render();

        return Command::SUCCESS;
    }

    private function createRow(Ticket $ticket, array $heards): array
    {
        $index = array_search($ticket->getCurrentPlace(), array_values($heards));
        $row = array_fill(0, count($heards), '');
        $row[$index] = $ticket->getLabel();
        return $row;
    }
}
