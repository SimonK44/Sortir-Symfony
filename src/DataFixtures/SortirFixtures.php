<?php
//-------------------------------------------------------------------------------
// a passer avec la commande : symfony console doctrine:fixture:load --append
//-------------------------------------------------------------------------------
namespace App\DataFixtures;

use App\Entity\Etats;
use App\Entity\Lieux;
use App\Entity\Sites;
use App\Entity\Sorties;
use App\Entity\User;
use App\Repository\EtatsRepository;
use App\Repository\LieuxRepository;
use App\Repository\SitesRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SortirFixtures extends Fixture
{

    private EtatsRepository $etatsRepository;
    private lieuxRepository $lieuxRepository;
    private UserRepository $userRepository;

    private SitesRepository $sitesRepository;
    private EntityManagerInterface $entityManager;


    public function __construct(EtatsRepository $etatsRepository,
                                LieuxRepository $lieuxRepository,
                                UserRepository $userRepository,
                                SitesRepository $sitesRepository,
                                EntityManagerInterface $entityManager,
    )
    {
        $this->etatsRepository = $etatsRepository;
        $this->lieuxRepository = $lieuxRepository;
        $this->sitesRepository = $sitesRepository;
        $this->userRepository = $userRepository;
        $this->entityManager  = $entityManager;


    }



    public function load(ObjectManager $manager,): void
    {
        $faker = Factory::create('fr-FR');

        for ($i = 0; $i < 500; $i++) {
            $sortie = new Sorties();

            $etatr = $this->etatsRepository->find(mt_rand(1,7));
            $lieuxr = $this->lieuxRepository->find((mt_rand(1,3)));
            $userr = $this->userRepository->find(mt_rand(1,3));
            $siter = $this->sitesRepository->find(mt_rand(1,3));



            $sortie->setNom($faker->words(1, true))
                ->setDateDebut(new \dateTime())
                ->setDuree(mt_rand(1, 10))
                ->setIsPublished($faker->randomDigitNotNull())
                ->setDateCloture($faker->dateTimeBetween($sortie->getDateDebut(),new \datetime()))
                ->setnbInscriptionsMax(mt_rand(2, 20))
                ->setDescriptionInfos($faker->words(8, true))
                ->setEtatSortie(2)
                ->setEtat($etatr)
                ->setlieux($lieuxr)
                ->SetUser($userr)
                ->setSite($siter)
            ;

            $manager->persist($sortie);
        }
        $manager->flush();
        Var_dump('************************** 500 enreg dans la table sortie on eté crées');
    }
}
