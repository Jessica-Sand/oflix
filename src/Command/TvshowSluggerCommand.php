<?php

namespace App\Command;

use App\Repository\TvShowRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\String\Slugger\SluggerInterface;

class TvshowSluggerCommand extends Command
{
    protected static $defaultName = 'tvshow:slugger';
    protected static $defaultDescription = 'Création de slugs pour une ou plusieurs séries';

    private $tvShowRepository;
    private $entityManager;
    private $slugger;
    public function __construct(TvShowRepository $tvShowRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $this->tvShowRepository = $tvShowRepository;
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('tvshowId', InputArgument::OPTIONAL, 'Identifiant de la série')
            ->addOption('updatedAt', null, InputOption::VALUE_NONE, 'Option de mise à jour de la propriété updatedAt');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // php bin/console tvshow:slugger 2
        $tvshowId = $input->getArgument('tvshowId');

        // php bin/console tvshow:slugger --updatedAt
        // $optionUpdatedAt sera égal à TRUE (si précisée dans la commande) et FALSE sinon
        $optionUpdatedAt = $input->getOption('updatedAt');

        // 1) On va récupérer la ou les séries à mettre à jour
        // $tvShowList = $this->tvShowRepository->findBy(['slug' => null]);
        if ($tvshowId) {
            // On met à jour uniquement la série dont l'ID est égal à tvshowId
            // On récupère la série dont l'ID précisée en argumant de la commande
            $tvShow = $this->tvShowRepository->find($tvshowId);
            $this->saveTvShow($tvShow, $optionUpdatedAt);
        } else {
            // On met à jour toutes les séries
            $tvShowList = $this->tvShowRepository->findAll();
            foreach ($tvShowList as $tvShow) {
                $this->saveTvShow($tvShow, $optionUpdatedAt);
            }
        }

        // 5) On flush avec le manager
        $this->entityManager->flush();

        // 6) Message de success
        $io->success('Mise à jour de la base de données effectuée');

        return Command::SUCCESS;
    }

    private function saveTvShow($tvShow, $optionUpdatedAt)
    {
        // 2) Pour chaque série, on récupère le title
        $title = $tvShow->getTitle();

        // 3) On génère le slug avec le service Slugger
        $slug = $this->slugger->slug($title);

        // 4) On met à jour la propriété slug
        // la fonction php strtolower permet de mettre une chaine de caractère
        // en minuscule
        // Ex : Breaking-Bad deviendrait breaking-bad
        $tvShow->setSlug(strtolower($slug));

        if ($optionUpdatedAt) {
            // Si on a précisé l'option updatedAt
            // on met à jour également la propriété updatedAt
            $tvShow->setUpdatedAt(new DateTime());
        }
    }
}