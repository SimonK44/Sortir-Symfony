<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\FileUploader;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{

    public function __construct(
        private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        Security $security,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        FileUploader $fileUploader
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        //dd($form);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setRoles(["ROLE_USER"]);
            $userPhoto = $form->get('userPhoto')->getData();

            if ($userPhoto) {
                $newFilename = $fileUploader->upload($userPhoto);
                $user->setUserPhoto($newFilename);
                $userPhoto->move($this->getParameter('photosDirectory'), $newFilename);
                $user->setUserPhoto($newFilename);
            }


        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );

        $entityManager->persist($user);
        $entityManager->flush();


        // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('mail@sortir.com', 'Eni Sortir Bot'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );

        // do anything else you need here, like send an email

            $this->addFlash('success', 'Veuillez valider votre adresse e-mail en cliquant sur le lien que vous avez reçu par e-mail !');

            return $this->redirectToRoute('app_login');


        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }


    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_login');
    }

    #[Route('/profil', name : 'app_profil')]
    public function userProfil(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        return $this->render('user/profil.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profil/{id}', name : 'app_profil_participant', methods: ['GET'])]
    public function participantProfil(Request $request, UserRepository $userRepository, User $user): Response
    {
        $userId = $userRepository->findByID($user->getId());

        return $this->render('user/profil.html.twig', [
            'userId' => $userId,
            'user' => $user,
        ]);
    }

    #[Route('/profil-edit', name : 'app_profil_modification')]
    public function modificationProfil(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        //dd($user);
        //dd($form);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($user);
            $entityManager->persist($user);
            $entityManager->flush();

            // envoie message flash
            $this->addFlash('succes','Profil modifié avec succes 😊');

            return $this->redirectToRoute('app_profil_retour', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/profil_modification.html.twig', [
            'user' => $user,
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/profil-retour', name : 'app_profil_retour')]
    public function retourProfil(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        return $this->render('user/profil_retour.html.twig', [
            'user' => $user,
        ]);
    }






}
