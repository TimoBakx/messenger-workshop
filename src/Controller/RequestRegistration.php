<?php
declare(strict_types=1);

namespace App\Controller;


use App\Registration\Form\RegistrationRequestType;
use App\Registration\Registration;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class RequestRegistration
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var Environment
     */
    private $renderer;

    public function __construct(FormFactoryInterface $formFactory, MessageBusInterface $messageBus, RouterInterface $router, Environment $renderer)
    {
        $this->formFactory = $formFactory;
        $this->messageBus = $messageBus;
        $this->router = $router;
        $this->renderer = $renderer;
    }

    /**
     * @Route("/", name="registration_request")
     *
     * @throws
     */
    public function __invoke(Request $request, Registration $registration): Response
    {
        $form = $this->formFactory->create(RegistrationRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->messageBus->dispatch($form->getData());

//            $registration->request($form->getData());

            return new RedirectResponse($this->router->generate('registration_confirmation'));
        }

        return new Response($this->renderer->render(
            'registration/request.html.twig',
            [
                'form' => $form->createView(),
            ]
        ));
    }
}
