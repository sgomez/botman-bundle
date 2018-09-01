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

namespace Sgomez\Bundle\BotmanBundle\Exception;

class FacebookClientException extends \RuntimeException
{
    public static function fromPayload(string $method, array $payload): self
    {
        return new self(sprintf('Error retrieving \'%s\' request: %s', $method, $payload['error']['message']));
    }
}
