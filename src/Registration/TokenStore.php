<?php
declare(strict_types=1);

namespace App\Registration;

use App\Registration\Message\RegistrationRequest;
use function json_encode;
use const JSON_PRETTY_PRINT;
use Symfony\Component\Filesystem\Filesystem;

class TokenStore
{
    /**
     * @var string
     */
    private $requestStorageDir;
    /**
     * @var string
     */
    private $confirmationStorageDir;

    public function __construct(string $requestStorageDir, string $confirmationStorageDir)
    {
        $this->requestStorageDir = $requestStorageDir;
        $this->confirmationStorageDir = $confirmationStorageDir;
    }

    public function removeConfirmation(string $token): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->confirmationStorageDir . $token . '.json');
    }

    public function createRequest(RegistrationRequest $request): void
    {
        $filesystem = new Filesystem();
        $filename = $request->getCreatedAt()->format('U') . '.json';

        $filesystem->dumpFile($this->requestStorageDir . $filename, json_encode($request, JSON_PRETTY_PRINT));
    }

    public function move(string $token): void
    {

    }
}
