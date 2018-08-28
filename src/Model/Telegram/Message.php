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

use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Illuminate\Support\Collection;

class Message
{
    /**
     * @var int
     */
    private $message_id;
    /**
     * @var ?User
     */
    private $from;
    /**
     * @var int
     */
    private $date;
    /**
     * @var Chat
     */
    private $chat;
    /**
     * @var ?string
     */
    private $text;
    /**
     * @var array|User[]|null
     */
    private $new_chat_members;
    /**
     * @var ?User
     */
    private $left_chat_member;
    /**
     * @var ?string
     */
    private $new_chat_title;

    private function __construct()
    {
    }

    public static function fromIncomingMessage(IncomingMessage $incomingMessage): self
    {
        $payload = $incomingMessage->getPayload();

        return self::fromPayload($payload instanceof Collection ? $payload->toArray() : $payload);
    }

    public static function fromPayload(array $payload): self
    {
        $message = new self();

        $message->message_id = $payload['message_id'];
        $message->from = array_key_exists('from', $payload) ? User::fromPayload($payload['from']) : null;
        $message->date = $payload['date'];
        $message->chat = Chat::fromPayload($payload['chat']);
        $message->text = $payload['text'] ?? null;

        $message->new_chat_members = null;
        if (array_key_exists('new_chat_members', $payload)) {
            $message->new_chat_members = [];
            foreach ($payload['new_chat_members'] as $new_chat_member) {
                $message->new_chat_members[] = User::fromPayload($new_chat_member);
            }
        }

        $message->left_chat_member = array_key_exists('left_chat_member', $payload) ? User::fromPayload($payload['left_chat_member']) : null;
        $message->new_chat_title = $payload['new_chat_title'] ?? null;

        return $message;
    }

    /**
     * @return int
     */
    public function getMessageId(): int
    {
        return $this->message_id;
    }

    /**
     * @return ?User
     */
    public function getFrom(): ?User
    {
        return $this->from;
    }

    /**
     * @return int
     */
    public function getDate(): int
    {
        return $this->date;
    }

    /**
     * @return Chat
     */
    public function getChat(): Chat
    {
        return $this->chat;
    }

    /**
     * @return ?string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @return array|User[]|null
     */
    public function getNewChatMembers(): ?array
    {
        return $this->new_chat_members;
    }

    /**
     * @return ?User
     */
    public function getLeftChatMember(): ?User
    {
        return $this->left_chat_member;
    }

    /**
     * @return ?string
     */
    public function getNewChatTitle(): ?string
    {
        return $this->new_chat_title;
    }
}
