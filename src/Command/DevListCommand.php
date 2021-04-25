<?php

namespace App\Command;

use App\Entity\Developer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use App\Repository\DeveloperRepository;

class DevListCommand extends Command
{
    protected static $defaultName = 'app:dev:list';
    protected static $defaultDescription = 'List of developer';

    public function __construct(private DeveloperRepository $developerRepository)
    {
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
        $developers = $this->developerRepository->findAll();

        $table = new Table($output);
        $table
            ->setHeaders(['name','mail'])
            ->setRows(array_map(function (Developer $developer) {
                return [
                    $developer->getName(),
                    $developer->getMail()
                ];
            }, $developers));
        ;
        $table->render();

        return Command::SUCCESS;
    }
}
