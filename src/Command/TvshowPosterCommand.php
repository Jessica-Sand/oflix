<?php

namespace App\Command;

use App\Repository\TvShowRepository;
use App\Service\OmdbApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TvshowPosterCommand extends Command
{
    // php bin/console tvshow:poster
    protected static $defaultName = 'tvshow:poster';

    // Description qui apparait quand on fait --help
    // php bin/console tvshow:poster --help
    protected static $defaultDescription = 'Mise à jour des posters de toutes les séries';

    private $tvShowRepository;
    private $omdbApi;
    private $entityManager;
    public function __construct(TvShowRepository $tvShowRepository, OmdbApi $omdbApi, EntityManagerInterface $entityManager)
    {
        // Symfony a besoin de faire des vérifications avant execution de la commande
        parent::__construct();
        $this->tvShowRepository = $tvShowRepository;
        $this->omdbApi = $omdbApi;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        // php bin/console tvshow:poster 2 
        // ICI on ne veut mettre à jour que la série dont l'id est égal à 2
        // Le chiffre 2 est un argument (tvshowId)
        $this
            ->addArgument('tvshowId', InputArgument::OPTIONAL, 'Identifiant de la série')
            ->addOption('titi', null, InputOption::VALUE_NONE, 'Option description');
    }

    /**
     * Dans cette méthode qu'on viendra coder l'algorithme
     * permettant de mettre à jour nos séries (Logique métier)
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $tvshowId = $input->getArgument('tvshowId');

        if ($tvshowId) {
            // On ne met à jour que la série dont l'ID est égal $tvshowId
            $io->note(sprintf('Série numéro %s', $tvshowId));
        }

        $option1 = $input->getOption('titi');


        // 1) On récupère la ou les séries à mettre à jour
        $tvShowList = $this->tvShowRepository->findAll();

        // 2) Pour chaque série, on récupère les informations de omdbAPI (Poster)
        // en fonction du title
        foreach ($tvShowList as $tvShow) {
            // $title = 'Breaking bad' ==> breaking-bad
            $title = $tvShow->getTitle();
            $tvshowData = $this->omdbApi->fetch($title);
            $tvShow->setPoster($tvshowData['Poster']);
        }

        // 3) On met à jour la Base de données
        $this->entityManager->flush();

        $io->success('Séries mises à jour en BDD');

        return Command::SUCCESS;
    }
}
