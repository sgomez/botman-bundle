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

use App\BotMan\Drivers\Generic\MyTelegramDriver;
use BotMan\BotMan\Interfaces\DriverInterface;
use BotMan\Drivers\Telegram\TelegramDriver;
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

    /**
     * @test
     */
    public function it_pass_with_valid_configuration(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'drivers' => [
                        'telegram' => [
                            'parameters' => [
                                'token' => 'my-bot-secret',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'drivers' => [
                    'telegram' => [
                        'class' => TelegramDriver::class,
                        'parameters' => [
                            'token' => 'my-bot-secret',
                        ],
                    ],
                ],
            ],
        'drivers'
        );
    }

    /**
     * @test
     */
    public function it_requires_token_parameter(): void
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
            'The child node "parameters" at path "botman.drivers.telegram" must be configured.'
        );

        $this->assertConfigurationIsInvalid(
            [
                [
                    'drivers' => [
                        'telegram' => [
                            'parameters' => [
                                'secret' => 'my-bot-secret',
                            ],
                        ],
                    ],
                ],
            ],
            'The child node "token" at path "botman.drivers.telegram.parameters" must be configured.'
        );
    }

    /**
     * @test
     */
    public function it_requires_valid_telegram_driver(): void
    {
        $driver = $this->getMockBuilder(DriverInterface::class)->getMock();

        $this->assertConfigurationIsInvalid(
            [
                [
                    'drivers' => [
                        'telegram' => [
                            'class' => \get_class($driver),
                            'parameters' => [
                                'token' => 'my-bot-secret',
                            ],
                        ],
                    ],
                ],
            ],
            'must be a valid telegram botman driver'
        );
    }

    /**
     * @test
     */
    public function it_can_use_extended_telegram_driver(): void
    {
        $this->assertConfigurationIsValid([
            [
                'drivers' => [
                    'telegram' => [
                        'class' => MyTelegramDriver::class,
                        'parameters' => [
                            'token' => 'my-bot-secret',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
