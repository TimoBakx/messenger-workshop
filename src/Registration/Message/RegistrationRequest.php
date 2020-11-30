<?php declare(strict_types = 1);

namespace App\Registration\Message;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

final class RegistrationRequest
{
    /**
     * @Assert\NotNull()
     * @Assert\Length(max=100)
     *
     * @var string
     */
    public $name;

    /**
     * @Assert\NotNull()
     * @Assert\Email()
     *
     * @var string
     */
    public $email;

    /**
     * @Assert\Type(type="DateTimeImmutable")
     *
     * @var DateTimeImmutable
     */
    public $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
