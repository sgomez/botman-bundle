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

namespace Sgomez\Bundle\BotmanBundle\Tests\DependencyInjection;

use BotMan\BotMan\Drivers\NullDriver;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Sgomez\Bundle\BotmanBundle\DependencyInjection\Configuration;

class TelegramConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }

    /** @test */
    public function it_pass_with_valid_configuration(): void
    {
        $this->assertConfigurationIsValid([
            [
                'drivers' => [
                    'telegram' => [
                        'parameters' => [
                            'token' => 'my-token',
                        ],
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function it_requires_a_valid_driver(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                [
                    'drivers' => [
                        'telegram' => [
                            'class' => NullDriver::class,
                            'parameters' => [
                                'token' => 'my-token',
                            ],
                        ],
                    ],
                ],
            ],
            'must be a valid Telegram BotMan driver'
        );
    }

    /** @test */
    public function it_requires_parameters(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                [
                    'drivers' => [
                        'telegram' => [
                        ],
                    ],
                ],
            ],
            'The child config "parameters" under "botman.drivers.telegram" must be configured.'
        );
    }

    /** @test */
    public function it_requires_token_parameter(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                [
                    'drivers' => [
                        'telegram' => [
                            'parameters' => [
                            ],
                        ],
                    ],
                ],
            ],
            'The child config "token" under "botman.drivers.telegram.parameters" must be configured.'
        );
    }
}
