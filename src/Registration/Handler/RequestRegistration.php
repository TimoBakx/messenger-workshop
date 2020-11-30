<?php
declare(strict_types=1);

namespace App\Registration\Handler;

use App\Registration\Message\RegistrationRequest;
use App\Registration\TokenStore;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RequestRegistration implements MessageHandlerInterface
{
    /**
     * @var TokenStore
     */
    private $tokenStore;

    public function __construct(TokenStore $tokenStore)
    {
        $this->tokenStore = $tokenStore;
    }

    public function __invoke(RegistrationRequest $request): void
    {
        $this->tokenStore->createRequest($request);
    }
}
