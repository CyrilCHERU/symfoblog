<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function login()
    {
        // $form = $this->createForm(LoginType::class);
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
    { }
}
