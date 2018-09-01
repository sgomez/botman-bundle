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
use Sgomez\Bundle\BotmanBundle\Exception\FacebookClientException;
use function GuzzleHttp\{json_decode, json_encode};

final class FacebookClient
{
    public const PROPERTY_GREETING = 'greeting';
    public const PROPERTY_GET_STARTED = 'get_started';
    public const PROPERTY_PERSISTENT_MENU = 'persistent_menu';
    public const PROPERTY_WHITELISTED_DOMAINS = 'whitelisted_domains';

    /**
     * @var HttpMethodsClient
     */
    private $client;
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var array|string[]
     */
    private static $properties = [self::PROPERTY_GREETING, self::PROPERTY_GET_STARTED, self::PROPERTY_PERSISTENT_MENU, self::PROPERTY_WHITELISTED_DOMAINS];

    public function __construct(HttpMethodsClient $client, string $accessToken)
    {
        $this->client = $client;
        $this->accessToken = $accessToken;
    }

    public function setGreetingText(array $greeting): array
    {
        return $this->setProperty('greeting', ['greeting' => $greeting]);
    }

    public function setGetStarted(string $payload): array
    {
        return $this->setProperty('payload', ['get_started' => ['payload' => $payload]]);
    }

    public function unsetProperty(string $property): array
    {
        if (!\in_array($property, self::$properties, true)) {
            throw new \InvalidArgumentException(sprintf('Property `%s` is not supported', $property));
        }

        $response = $this->client->delete(
            sprintf('https://graph.facebook.com/v3.1/me/messenger_profile?access_token=%s', $this->accessToken),
            ['content-type' => 'application/json'],
            json_encode(['fields' => [$property]])
        );
        $responseData = json_decode($response->getBody()->getContents(), true);

        if (200 !== $response->getStatusCode()) {
            throw FacebookClientException::fromPayload($property, $responseData);
        }

        return $responseData;
    }

    public function getProperties(): array
    {
        $response = $this->client->get(
            sprintf('https://graph.facebook.com/v3.1/me/messenger_profile?fields=%s&access_token=%s', implode(',', self::$properties), $this->accessToken)
        );
        $responseData = json_decode($response->getBody()->getContents(), true);

        if (200 !== $response->getStatusCode()) {
            throw FacebookClientException::fromPayload('greeting', $responseData);
        }

        return $responseData['data'];
    }

    private function setProperty(string $property, array $payload): array
    {
        $response = $this->client->post(
            sprintf('https://graph.facebook.com/v3.1/me/messenger_profile?access_token=%s', $this->accessToken),
            ['content-type' => 'application/json'],
            json_encode($payload)
        );
        $responseData = json_decode($response->getBody()->getContents(), true);

        if (200 !== $response->getStatusCode()) {
            throw FacebookClientException::fromPayload($property, $responseData);
        }

        return $responseData;
    }
}
