<?php

namespace App\Command;


use App\Entity\Villes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-ville',
    description: 'insertion nouvelle ville',
    hidden: false,
    aliases: ['app:add-ville']
)]
class VilleCommand extends Command
{
    protected static $defaultName = 'app:import-user-csv';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName(self::$defaultName);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = 'app/Ressources/Ville.csv';

        if(!file_exists($filePath)){
            $output->writeln('File not found');
            return Command::FAILURE;
        }

        $csv = array_map('str_getcsv', file($filePath));
        $header = array_shift($csv);

        foreach ($csv as $row) {
            $data = array_combine($header, $row);
            var_dump($data);
            $ville= new Villes();
            $ville->setNomVille($data['nom']);
            $ville->setCodePostal($data['codePostal']);


            $this->entityManager->persist($ville);
        }
        $this->entityManager->flush();
        $output->writeln('Users imported successfully');
        return Command::SUCCESS;
    }



}