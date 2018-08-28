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

namespace Sgomez\Bundle\BotmanBundle\Services\Http;

use Http\Client\Common\HttpMethodsClient;
use Sgomez\Bundle\BotmanBundle\Exception\TelegramClientException;
use Sgomez\Bundle\BotmanBundle\Model\Telegram\User;

final class TelegramClient
{
    private const BASE_URI = 'https://api.telegram.org/bot%s/%s';

    /**
     * @var HttpMethodsClient
     */
    private $client;
    /**
     * @var string
     */
    private $token;

    public function __construct(HttpMethodsClient $client, string $token)
    {
        $this->client = $client;
        $this->token = $token;
    }

    public function getMe(): User
    {
        $responseData = $this->sendPayload('getMe');

        return User::fromPayload($responseData['result']);
    }

    public function getWebhookInfo(): array
    {
        return $this->sendPayload('getWebhookInfo');
    }

    public function setWebhook(string $url): array
    {
        $body = \json_encode(['url' => $url]) ?: null;

        return $this->sendPayload('setWebhook', $body);
    }

    public function removeWebhook(): array
    {
        return $this->sendPayload('deleteWebhook');
    }

    private function sendPayload(string $endpoint, ?string $body = null): array
    {
        $response = $this->client->post($this->buildUrl($endpoint), ['content-type' => 'application/json'], $body);
        $responseData = \json_decode($response->getBody()->getContents(), true);

        if (200 !== $response->getStatusCode()) {
            throw TelegramClientException::fromPayload($endpoint, $responseData);
        }

        return $responseData;
    }

    private function buildUrl(string $endpoint): string
    {
        return sprintf(self::BASE_URI, $this->token, $endpoint);
    }
}
