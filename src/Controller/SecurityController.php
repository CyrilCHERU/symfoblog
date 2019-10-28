<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function login()
    {
        // $form = $this->createForm(LoginType::class);
        $this->addFlash(
            'success',
            "Bonjour {$this->getUser()}</strong>, vous êtes connecté :)"
        );

        return $this->render('security/login.html.twig');

        // [
        //     'form' => $form->createView()
        // ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     *
     * @return void
     */
    public function logOut()
    {
        $this->addFlash(
            'danger',
            "Au revoir {$this->getUser()}</strong>, vous êtes déconnecté :)"
        );
    }

    /**
     * @Route("/register", name="security_register")
     *
     * @return void
     */
    public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $user = new User;

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $user->getPassword();
            $password = $encoder->encodePassword($user, $plainPassword);

            $user->setPassword($password);

            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                "Bonjour {$this->getUser()}</strong>, vous êtes inscrit :)"
            );

            return $this->redirectToRoute('security_login');
        }
        return $this->render('/security/register.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}
