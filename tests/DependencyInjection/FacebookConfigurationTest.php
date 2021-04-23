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

class FacebookConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }

    /** @test */
    public function it_pass_with_valid_configuration(): void
    {
        $this->assertConfigurationIsValid([
            [
                'drivers' => [
                    'facebook' => [
                        'parameters' => [
                            // mandatory
                            'token' => 'my-token',
                            'app_secret' => 'my-app-secret',
                            'verification' => 'my-verification',
                            // optional
                            'start_button_payload' => 'start-button-pressed-event',
                            'whitelisted_domains' => ['host-a.domain.com', 'host-b.domain.com'],
                            'greeting' => [
                                ['locale' => 'default', 'text' => 'Hi'],
                                ['locale' => 'es', 'text' => 'Hola'],
                                ['locale' => 'pt', 'text' => 'Oi'],
                            ],
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
                        'facebook' => [
                            'class' => NullDriver::class,
                            'parameters' => [
                                'token' => 'my-token',
                                'app_secret' => 'my-app-secret',
                                'verification' => 'my-verification',
                            ],
                        ],
                    ],
                ],
            ],
            'must be a valid Facebook BotMan driver'
        );
    }

    /** @test */
    public function it_requires_parameters(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                [
                    'drivers' => [
                        'facebook' => [
                        ],
                    ],
                ],
            ],
            'The child config "parameters" under "botman.drivers.facebook" must be configured.'
        );
    }

    /** @test */
    public function it_requires_token_parameter(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                [
                    'drivers' => [
                        'facebook' => [
                            'parameters' => [
                                'app_secret' => 'my-app-secret',
                                'verification' => 'my-verification',
                            ],
                        ],
                    ],
                ],
            ],
            'The child config "token" under "botman.drivers.facebook.parameters" must be configured.'
        );
    }

    /** @test */
    public function it_requires_app_secret_parameter(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                [
                    'drivers' => [
                        'facebook' => [
                            'parameters' => [
                                'token' => 'my-token',
                                'verification' => 'my-verification',
                            ],
                        ],
                    ],
                ],
            ],
            'The child config "app_secret" under "botman.drivers.facebook.parameters" must be configured.'
        );
    }

    /** @test */
    public function it_requires_verification_parameter(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                [
                    'drivers' => [
                        'facebook' => [
                            'parameters' => [
                                'token' => 'my-token',
                                'app_secret' => 'my-app-secret',
                            ],
                        ],
                    ],
                ],
            ],
            'The child config "verification" under "botman.drivers.facebook.parameters" must be configured.'
        );
    }

    /** @test */
    public function its_empty_parameters_must_be_set(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'drivers' => [
                        'facebook' => [
                            'parameters' => [
                                'token' => 'my-token',
                                'app_secret' => 'my-app-secret',
                                'verification' => 'my-verification',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'drivers' => [
                    'facebook' => [
                        'parameters' => [
                            'token' => 'my-token',
                            'app_secret' => 'my-app-secret',
                            'verification' => 'my-verification',
                            'start_button_payload' => null,
                            'greeting' => [],
                            'whitelisted_domains' => [],
                        ],
                    ],
                ],
            ],
            'drivers.facebook.parameters'
        );
    }

    /** test */
    public function it_requires_at_least_default_greeting(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                [
                    'drivers' => [
                        'facebook' => [
                            'parameters' => [
                                'token' => 'my-token',
                                'app_secret' => 'my-app-secret',
                                'verification' => 'my-verification',
                                'greeting' => [
                                    ['locale' => 'es', 'text' => 'Hola'],
                                    ['locale' => 'pt', 'text' => 'Oi'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'Default locale must be defined'
        );
    }
}
