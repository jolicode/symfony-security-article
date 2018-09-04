<?php

namespace App\Controller;

use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $authenticationUtils;
    private $adminRepository;

    public function __construct(AuthenticationUtils $authenticationUtils, AdminRepository $adminRepository)
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->adminRepository = $adminRepository;
    }

    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        $error = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();
        $admins = $this->adminRepository->findAll();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
            'admins' => $admins,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new \BadMethodCallException('The security component should handle this action.');
    }
}
