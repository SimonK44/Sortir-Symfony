<?php

// lancer la commande avec :
//                            php bin/console app:dump

namespace App\Command;


use App\Entity\Villes;
use App\Repository\EtatsRepository;
use App\Repository\LieuxRepository;
use App\Repository\SitesRepository;
use App\Repository\SortiesRepository;
use App\Repository\UserRepository;
use App\Repository\VillesRepository;
use Doctrine\ORM\EntityManagerInterface;
use mysqli;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:dump',
    description: 'dump de la base',
    hidden: false,
    aliases: ['app:add-dump']
)]
class DumpCommand extends Command
{
    protected static $defaultName = 'app:dump';
    private $entityManager;
    private $VillesRepository;



    public function __construct(EntityManagerInterface $entityManager,
                                VillesRepository $villesRepository,
                                LieuxRepository $lieuxRepository,
                                EtatsRepository $etatsRepository,
                                SitesRepository $sitesRepository,
                                UserRepository $userRepository,
                                SortiesRepository $sortiesRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->VillesRepository = $villesRepository;
        $this->lieuxRepository = $lieuxRepository;
        $this->etatsRepository = $etatsRepository;
        $this->sitesRepository = $sitesRepository;
        $this->userRepository = $userRepository;
        $this->sortiesRepository = $sortiesRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName(self::$defaultName);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = 'app/Ressources/Dump.txt';
        $insert = '';

// ouverture du ficiher
        $fichier = fopen($filePath, 'c+b');

////////////////// traitement des villes
        $insert ='DELETE FROM villes'
                  .";\n"
                  ."\n";
        fwrite($fichier, $insert);

//        on recupere tous les villes
        $villes = $this->VillesRepository->findAll();
// on balaye tous les enregs

        foreach ($villes as $ville) {
            $insert= 'INSERT INTO villes (nom_ville,code_postal) VALUES (\''
                . $ville->getNomVille()
                . '\',\''
                . $ville->getCodePostal()
                . '\');'
                . "\n";

            fwrite($fichier, $insert);
        }

////////////////// traitement des lieux
        $insert = "\n"
                 .'DELETE FROM lieux '
                 .";\n"
                 ."\n";
        fwrite($fichier, $insert);

//        on recupere tous les Lieux
        $lieuxs = $this->lieuxRepository->findAll();
// on balaye tous les enregs

        foreach ($lieuxs as $lieux) {
            $insert= 'INSERT INTO lieux (`ville_id`, `nom_lieu`, `rue`, `latitude`, `longitude`) VALUES (\''
                     . $lieux->getVille()->getId()
                     . '\',\''
                     . $lieux->getNomLieu()
                     . '\',\''
                     . $lieux->getRue()
                     . '\',\''
                     . $lieux->getLatitude()
                     . '\',\''
                     . $lieux->getLongitude()
                     . '\');'
                     . "\n";

            fwrite($fichier, $insert);
        }


////////////////// traitement des etats
        $insert = "\n"
            .'DELETE FROM etats '
            .";\n"
            ."\n";
        fwrite($fichier, $insert);

//        on recupere tous les etats
        $etats = $this->etatsRepository->findAll();
// on balaye tous les enregs

        foreach ($etats as $etat) {
            $insert= 'INSERT INTO etats ( id, libelle) VALUES (\''
                . $etat->getid()
                . '\',\''
                . $etat->getLibelle()
                . '\');'
                . "\n";

            fwrite($fichier, $insert);
        }

////////////////// traitement des sites
        $insert = "\n"
            .'DELETE FROM sites '
            .";\n"
            ."\n";
        fwrite($fichier, $insert);

//        on recupere tous les sites
        $sites = $this->sitesRepository->findAll();
// on balaye tous les enregs

        foreach ($sites as $site) {
            $insert= 'INSERT INTO sites (`id`, `nom_site`) VALUES (\''
                . $site->getid()
                . '\',\''
                . $site->getNomSite()
                . '\');'
                . "\n";

            fwrite($fichier, $insert);
        }

////////////////// traitement des users
        $insert = "\n"
            .'DELETE FROM users '
            .";\n"
            ."\n";
        fwrite($fichier, $insert);

//        on recupere tous les users
        $users = $this->userRepository->findAll();
// on balaye tous les enregs

        foreach ($users as $user) {

            $insert= 'INSERT INTO sites (`id`, `email`, `roles`, `password`, `pseudo`, `nom`, `prenom`, `telephone`, `actif`,`is_verified`, `site_id`) VALUES (\''
                . $user->getid()
                . '\',\''
                . $user->getEmail()
                . '\',\''
                . '[ROLE_USER]'
                . '\',\''
                . $user->getPassword()
                . '\',\''
                . $user->getPseudo()
                . '\',\''
                . $user->getNom()
                . '\',\''
                . $user->getPrenom()
                . '\',\''
                . $user->getTelephone()
                . '\',\''
                . $user->isActif()
                . '\',\''
                . $user->isVerified()
                . '\',\''
                . $user->getSite()->getId()
                . '\');'
                . "\n";

            fwrite($fichier, $insert);
        }

        ////////////////// traitement des sorties
        $insert = "\n"
            .'DELETE FROM sorties '
            .";\n"
            ."\n";
        fwrite($fichier, $insert);

//        on recupere tous les sorties
        $sorties = $this->sortiesRepository->findAll();
// on balaye tous les enregs

        foreach ($sorties as $sortie) {
            $insert= 'INSERT INTO sorties (`id`, `etat_id`, `lieux_id`, `user_id`, `site_id`, `nom`, `date_debut`, `duree`, `date_cloture`, `nb_inscriptions_max`, `description_infos`, `etat_sortie`, `url_photo`, `is_published`) VALUES (\''
                . $sortie->getid()
                . '\',\''
                . $sortie->getEtat()->getId()
                . '\',\''
                . $sortie->getLieux()->getId()
                . '\',\''
                . $sortie->getUser()->getId()
                . '\',\''
                . $sortie->getSite()->getId()
                . '\',\''
                . $sortie->getNom()
                . '\',\''
                . $sortie->getDateDebut()->format('Y-m-d H:i:s')
                . '\',\''
                . $sortie->getDuree()
                . '\',\''
                . $sortie->getDateCloture()->format('Y-m-d H:i:s')
                . '\',\''
                . $sortie->getNbInscriptionsMax()
                . '\',\''
                . $sortie->getDescriptionInfos()
                . '\',\''
                . $sortie->getEtatSortie()
                . '\',\''
                . $sortie->getUrlPhoto()
                . '\',\''
                . $sortie->getIsPublished()
                . '\');'
                . "\n";

            fwrite($fichier, $insert);
        }

        ////////////////// traitement des user_sorties
        $insert = "\n"
            .'DELETE FROM user_sorties '
            .";\n"
            ."\n";
        fwrite($fichier, $insert);

        foreach ($sorties as $sortie) {
              $participants = $sortie->getUsers();
              foreach ($participants as $participant) {
                  $insert = 'INSERT INTO user_sorties (`user_id`, `sorties_id`) VALUES (\''
                      . $sortie->getUser()->getId()
                      . '\',\''
                      . $participant->getId()
                      . '\');'
                      . "\n";

                  fwrite($fichier, $insert);
              }
        }


        return Command::SUCCESS;
    }





}