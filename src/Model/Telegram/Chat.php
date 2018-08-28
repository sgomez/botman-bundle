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

final class Chat
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $type;
    /**
     * @var ?string
     */
    private $title;
    /**
     * @var ?string
     */
    private $username;
    /**
     * @var ?string
     */
    private $firstName;
    /**
     * @var ?string
     */
    private $lastName;
    /**
     * @var bool
     */
    private $allMembersAreAdministrators = false;
    /**
     * @var object
     */
    private $photo;
    /**
     * @var ?string
     */
    private $description;
    /**
     * @var ?string
     */
    private $inviteLink;
    /**
     * @var object
     */
    private $pinnedMessage;
    /**
     * @var ?string
     */
    private $stickerSetName;
    /**
     * @var bool
     */
    private $canSetStickerSet;

    private function __construct()
    {
    }

    public static function fromPayload(array $payload): self
    {
        $chat = new self();

        $chat->id = $payload['id'];
        $chat->type = $payload['type'];
        $chat->title = $payload['title'] ?? null;
        $chat->username = $payload['username'] ?? null;
        $chat->firstName = $payload['first_name'] ?? null;
        $chat->lastName = $payload['last_name'] ?? null;
        $chat->allMembersAreAdministrators = $payload['all_members_are_administrators'] ?? null;
        $chat->description = $payload['description'] ?? null;
        $chat->inviteLink = $payload['invite_link'] ?? null;
        $chat->pinnedMessage = $payload['pinned_message'] ?? null;
        $chat->stickerSetName = $payload['sticker_set_name'] ?? null;
        $chat->canSetStickerSet = $payload['can_set_sticker_set'] ?? null;

        return $chat;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return bool
     */
    public function isAllMembersAreAdministrators(): bool
    {
        return $this->allMembersAreAdministrators;
    }

    /**
     * @return object
     */
    public function getPhoto(): object
    {
        return $this->photo;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getInviteLink()
    {
        return $this->inviteLink;
    }

    /**
     * @return object
     */
    public function getPinnedMessage(): object
    {
        return $this->pinnedMessage;
    }

    /**
     * @return mixed
     */
    public function getStickerSetName()
    {
        return $this->stickerSetName;
    }

    /**
     * @return bool
     */
    public function isCanSetStickerSet(): bool
    {
        return $this->canSetStickerSet;
    }
}
