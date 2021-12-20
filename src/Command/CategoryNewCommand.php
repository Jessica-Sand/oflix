<?php

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CategoryNewCommand extends Command
{
    protected static $defaultName = 'category:new';
    protected static $defaultDescription = 'Création d\'une catégorie en ligne de commande';

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $categoryName = $input->getArgument('name');

        if ($categoryName) {
            // Le nom de la catégorie a bien été saisi dans le terminal
            // php bin/console category:new Drame

            // 1) On créé une nouvelle instance de l'entité Category
            $category = new Category();

            // 2) Je renseigne le nom de la catégorie
            $category->setName($categoryName);

            // 3) On sauvegarde la catégorie en BDD
            dump('La catégorie est ', $category);
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            // @copyright 2021 Chris Tucker
            $io->success('La catégorie ' . $category->getName() . ' à été ajoutée !');
        } else {
            $io->error('Merci de saisir un nom de catégorie');
            // return Command::FAILURE;
            return 1;
        }

        // return Command::SUCCESS;
        return 0;
    }
}
