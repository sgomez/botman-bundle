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

namespace Sgomez\Bundle\BotmanBundle\Command\Helpers;

use Symfony\Component\Console\Style\OutputStyle;

trait TelegramTrait
{
    public function printWebhookResponse(OutputStyle $outputStyle, array $response): void
    {
        if (!array_key_exists('ok', $response)) {
            throw new \RuntimeException('Response not supported');
        }

        if ($response['ok']) {
            $outputStyle->success($response['description']);
        } else {
            $outputStyle->error($response['description']);
        }
    }
}
