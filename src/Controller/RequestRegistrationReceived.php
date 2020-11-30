<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class RequestRegistrationReceived
{
    /**
     * @var Environment
     */
    private $renderer;

    public function __construct(Environment $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @Route("/confirm", name="registration_confirmation")
     *
     * @throws
     */
    public function __invoke(): Response
    {
        return new Response($this->renderer->render('registration/confirm.html.twig'));
    }
}
