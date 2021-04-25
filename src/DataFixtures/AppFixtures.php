<?php

namespace App\DataFixtures;

use App\Constant\PRPlace;
use App\Entity\Developer;
use App\Entity\Ticket;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Badcow\LoremIpsum\Generator;

class AppFixtures extends Fixture
{
    private const NB_DEV = 4;
    private const NB_TICKET = 10;

    public function load(ObjectManager $manager)
    {
        $generator = new Generator();
        $paragraphs = $generator->getParagraphs(5);
        foreach (range(0, static::NB_DEV) as $indice) {
            $dev = $this->addDevelop(uniqid(), (uniqid() . '@kosmos.fr'));
            $manager->persist($dev);
        }

        foreach (range(0, static::NB_TICKET) as $indice) {
            $ticket = $this->addTicket('PGIN3-' . ($indice + 1), implode('\n', $generator->getSentences(3)));
            $manager->persist($ticket);
        }

        $manager->flush();
    }

    private function addDevelop(string $name, string $mail): Developer
    {
        return (new Developer())
        ->setMail($mail)
        ->setName($name);
    }

    private function addTicket(string $label, string $content): Ticket
    {
        return (new Ticket())
            ->setContent($content)
            ->setLabel($label)
            ->setCurrentPlace(PRPlace::ALL[array_rand(PRPlace::ALL)]);
    }
}
