<?php
declare(strict_types=1);

namespace App\Registration\Handler;

use App\Registration\Message\Registration;
use App\Registration\TokenStore;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Register implements MessageHandlerInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var TokenStore
     */
    private $tokenStore;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, TokenStore $tokenStore)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->tokenStore = $tokenStore;
    }

    public function __invoke(Registration $registration)
    {
        $user = $registration->getUser();
        $token = $registration->getToken();

        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->tokenStore->removeConfirmation($token);
    }
}
