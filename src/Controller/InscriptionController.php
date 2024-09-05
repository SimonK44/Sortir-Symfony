<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Sorties;
use App\Repository\SortiesRepository;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/inscri')]
class InscriptionController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/inscription/{idSortie}/{idUser}', name: '_inscription',requirements: ['idSortie' => '\d+', 'idUser' => '\d+'])]
    public function inscription(Request $request, int $idSortie,int $idUser,
                                EntityManagerInterface $entityManager,
                                SortiesRepository $sortiesRepository,
                                UserRepository $UserRepository,
                                MailerInterface $mailer ): Response {

        //insertion du user à la sortie
        $sortiesRepository->InsertUserSortie($idSortie,$idUser);
        // envoie message flash
        $this->addFlash('succes','Inscription effectuée avec succes 😊');


        // generate a signed url and email it to the user

        $user =$UserRepository->find($idUser);
        $sortie = $sortiesRepository->find($idSortie);

//envois mail
       $email= (new TemplatedEmail())
           ->from(new Address('mail@sortir.com', 'Eni Sortir Bot'))
           ->to($user->getEmail())
           ->subject('Inscription sortie')
           ->htmlTemplate('registration/inscription_email.html.twig')
           ->context([
            'sortie' => $sortie,
            'participant' => $user,
        ]);


         $mailer->send($email);


        return $this->redirectToRoute('app_sorties_show', array(
            'id' => $idSortie,
        ));
    }

    #[Route('/desinscription/{idSortie}/{idUser}', name: '_desincription',requirements: ['idSortie' => '\d+', 'idUser' => '\d+'])]
    public function desinscription(Request $request, int $idSortie,int $idUser,
                                   EntityManagerInterface $entityManager,
                                   SortiesRepository $sortiesRepository,
                                   UserRepository $UserRepository,
                                   MailerInterface $mailer ): Response {


        // suppression du user de la sortie
        $sortiesRepository->DeleteUserSortie($idSortie,$idUser);
        // envoie message flash
        $this->addFlash('succes','Désinscription effectuée avec succes 😊');

        $user =$UserRepository->find($idUser);
        $sortie = $sortiesRepository->find($idSortie);

 //envois mail
        $email= (new TemplatedEmail())
            ->from(new Address('mail@sortir.com', 'Eni Sortir Bot'))
            ->to($user->getEmail())
            ->subject('Inscription sortie')
            ->htmlTemplate('registration/desinscription_email.html.twig')
            ->context([
                'sortie' => $sortie,
                'participant' => $user,
            ]);


        $mailer->send($email);

        return $this->redirectToRoute('app_sorties_show', array(
            'id' => $idSortie,
        ));

    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/app_sorties_delete/{idSortie}', name: '_suppressionSortie',requirements: ['idSortie' => '\d+', ])]
    public function supprimerSortie(Request $request, int $idSortie,
                                   EntityManagerInterface $entityManager,
                                   SortiesRepository $sortiesRepository,
                                   UserRepository $UserRepository,
                                   MailerInterface $mailer ): Response {

        $sortie = $sortiesRepository->find($idSortie);


        foreach ($sortie->getUsers() as $participant) {
            $texteFlash = $participant->getPseudo().'a reçu un mail d annulation de la sortie';
      //      $this->addFlash('notice','les participants ont recu un Email');
            $this->addFlash('notice',$texteFlash);

//envois mail
            $email= (new TemplatedEmail())
                ->from(new Address('mail@sortir.com', 'Eni Sortir Bot'))
                ->to($participant->getEmail())
                ->subject('Annulation sortie ️☠️ ☠️ ☠️ ☠️ ☠️ ☠️  ')
                ->htmlTemplate('registration/annulation_email.html.twig')
                ->context([
                    'sortie' => $sortie,
                    'participant' => $participant,
                ]);

            $mailer->send($email);

        }

        $this->addFlash('succes','La sortie a été supprimée avec succes');


        $entityManager->remove($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('app_sorties_index', [], Response::HTTP_SEE_OTHER);

    }


}
