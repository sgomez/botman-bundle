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

namespace App\BotMan\Drivers\Generic;

use BotMan\BotMan\Interfaces\DriverInterface;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class GenericDriver implements DriverInterface
{
    /**
     * {@inheritdoc}
     */
    public function matchesRequest(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigured(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(IncomingMessage $matchingMessage): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getConversationAnswer(IncomingMessage $message): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildServicePayload($message, $matchingMessage, $additionalParameters = []): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function sendPayload($payload): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function hasMatchingEvent(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function types(IncomingMessage $matchingMessage): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function serializesCallbacks(): void
    {
    }
}
