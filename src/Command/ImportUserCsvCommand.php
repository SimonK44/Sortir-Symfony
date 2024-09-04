<?php
//lancer la commande :
//php bin/console app:import-user-csv

namespace App\Command;

use App\Entity\User;
use App\Repository\SitesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\CssSelector\Parser\Reader;

class ImportUserCsvCommand extends Command
{
    protected static $defaultName = 'app:import-user-csv';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, SitesRepository $sitesRepository)
    {
        $this->entityManager = $entityManager;
        $this->sitesRepository = $sitesRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Importe les utilisateurs depuis un fichier CSV')
            ->addArgument('file', InputArgument::OPTIONAL, 'Le chemin du fichier CSV', 'app/Ressources/userListe.csv');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument('file');

        if(!file_exists($filePath)){
            $output->writeln('File not found');
            return Command::FAILURE;
        }

        $csv = array_map('str_getcsv', file($filePath));
        $header = array_shift($csv);

        foreach ($csv as $row) {
            if (count($row) < count($header)) {
                $output->writeln("Ligne malformée détectée, saut de la ligne.");
                continue;
            }

            $data = array_combine($header, $row);

          $user = new User();
          $user->setNom($data['nom'] ?? '');
          $user->setPrenom($data['prenom'] ?? '');
          $user->setEmail($data['email'] ?? '');
          $user->setPassword($data['password'] ?? '');
          $user->setPseudo($data['pseudo'] ?? '');
          $user->setTelephone($data['telephone'] ?? '');
          $user->setActif($data['actif'] ?? '');
          //var_dump($data['site_id']);
          $site=$this->sitesRepository->find($data['site_id'] ?? '');
          $user->setSite($site);
          $user->setRoles(['ROLE_USER']);
          $user->setVerified('1');
          $this->entityManager->persist($user);

        }
        $this->entityManager->flush();
        $output->writeln('Users imported successfully');
        return Command::SUCCESS;
    }



}