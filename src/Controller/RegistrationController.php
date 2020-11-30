<?php declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="registration_")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/success", name="success")
     */
    public function confirmRegistration(): Response
    {
        return $this->render('registration/success.html.twig');
    }

}
