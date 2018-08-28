<?php

declare(strict_types=1);

/*
 * This file is part of the `botman-bundle` project.
 *
 * (c) Sergio Góemz <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sgomez\Bundle\BotmanBundle\Model\Telegram;

class User
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var bool
     */
    private $isBot;
    /**
     * @var string
     */
    private $firstName;
    /**
     * @var null|string
     */
    private $lastName;
    /**
     * @var null|string
     */
    private $username;
    /**
     * @var null|string
     */
    private $languageCode;

    private function __construct()
    {
    }

    public static function fromPayload(array $payload): self
    {
        $user = new self();

        $user->id = $payload['id'];
        $user->isBot = $payload['is_bot'];
        $user->firstName = $payload['first_name'];
        $user->lastName = $payload['last_name'] ?? null;
        $user->username = $payload['username'] ?? null;
        $user->languageCode = $payload['language_code'] ?? null;

        return $user;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isBot(): bool
    {
        return $this->isBot;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return null|string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return null|string
     */
    public function getLanguageCode(): ?string
    {
        return $this->languageCode;
    }
}
