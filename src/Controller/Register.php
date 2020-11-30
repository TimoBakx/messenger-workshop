<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Registration\Form\RegistrationAttemptType;
use App\Registration\Message\Registration;
use const DIRECTORY_SEPARATOR;
use function file_get_contents;
use function json_decode;
use function rtrim;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class Register
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var Environment
     */
    private $renderer;
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router, Environment $renderer, MessageBusInterface $messageBus)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->renderer = $renderer;
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/register/{token}", name="registration_register")
     *
     * @throws
     */
    public function register(
        string $token,
        Request $request,
        string $confirmationStorageDir
    ): Response
    {
        $filesystem = new Filesystem();
        $confirmationStorageDir = rtrim($confirmationStorageDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $filename = $confirmationStorageDir . $token . '.json';

        if (!$filesystem->exists($filename)) {
            throw new NotFoundHttpException();
        }
        $user = $this->createUserFromConfirmation($filename);

        $form = $this->formFactory->create(RegistrationAttemptType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->messageBus->dispatch(new Registration($token, $user));

            return new RedirectResponse($this->router->generate('registration_success'));
        }

        return new Response($this->renderer->render(
            'registration/register.html.twig',
            [
                'form' => $form->createView(),
            ]
        ));
    }

    private function createUserFromConfirmation(string $filename): User
    {
        $data = json_decode(file_get_contents($filename), true);

        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);

        return $user;
    }
}
