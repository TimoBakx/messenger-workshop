<?php declare(strict_types = 1);

namespace App\Registration;

use App\Registration\Message\RegistrationRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use function hash;
use const DIRECTORY_SEPARATOR;

final class Registration
{
    private $requestStorageDir;
    private $confirmationStorageDir;
    private $entityManager;

    public function __construct(string $requestStorageDir, string $confirmationStorageDir, EntityManagerInterface $entityManager)
    {
        $this->requestStorageDir = rtrim($requestStorageDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->confirmationStorageDir = rtrim($confirmationStorageDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->entityManager = $entityManager;
    }

    public function request(RegistrationRequest $request): void
    {
    }

    public function confirm(string $requestFilename): string
    {
        $filesystem = new Filesystem();
        $filename = hash('crc32', $requestFilename) . '.json';

        $filesystem->copy($requestFilename, $this->confirmationStorageDir . $filename);
        $filesystem->remove($requestFilename);

        return substr($filename, 0, -5);
    }
}
