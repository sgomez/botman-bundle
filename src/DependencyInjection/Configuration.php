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

namespace Sgomez\Bundle\BotmanBundle\DependencyInjection;

use BotMan\Drivers\Facebook\FacebookDriver;
use BotMan\Drivers\Telegram\TelegramDriver;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('botman');
        $root = $treeBuilder->getRootNode();

        $root
            ->children()
                ->scalarNode('controller')
                    ->defaultValue('App\Controller\WebhookController')
                    ->validate()
                        ->ifTrue(function ($v) {
                            return !\class_exists($v);
                        })
                        ->thenInvalid('Webhook controller class \'%s\' does not exist')
                    ->end()
                ->end()

                ->scalarNode('path')
                    ->defaultValue('/botman')
                ->end()

                ->arrayNode('drivers')
                    ->children()
                        ->append($this->addTelegramConfiguration())
                        ->append($this->addFacebookConfiguration())
                    ->end()
                ->end()
                ->append($this->addHttpNode())
            ->end()
        ;

        return $treeBuilder;
    }

    private function addFacebookConfiguration(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('facebook');
        $node = $treeBuilder->getRootNode();

        $node
            ->children()
                ->scalarNode('class')
                    ->defaultValue(FacebookDriver::class)
                    ->validate()
                        ->ifTrue(function ($v) {
                            return !\is_subclass_of($v, FacebookDriver::class);
                        })
                        ->thenInvalid('Class \'%s\' must be a valid Facebook BotMan driver.')
                    ->end()
                ->end()

                ->arrayNode('parameters')
                    ->isRequired()
                    ->children()
                        ->scalarNode('token')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('app_secret')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('verification')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('start_button_payload')->defaultNull()->end()
                        ->arrayNode('greeting')
                            ->validate()
                                ->always(function ($v) {
                                    foreach ($v as ['locale' => $locale]) {
                                        if (isset($locales[$locale])) {
                                            throw new InvalidConfigurationException(sprintf(
                                                'Duplicated `botman.drivers.facebook.greeting.locale`: `%s`.',
                                                $locale
                                            ));
                                        }

                                        $locales[$locale] = 1;
                                    }

                                    if (!isset($locales['default'])) {
                                        throw new InvalidConfigurationException('Default locale must be defined in `botman.drivers.facebook.greeting.locale`.');
                                    }

                                    return $v;
                                })
                            ->end()
                            ->requiresAtLeastOneElement()
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('locale')->isRequired()->cannotBeEmpty()->end()
                                    ->scalarNode('text')->isRequired()->cannotBeEmpty()->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('whitelisted_domains')
                            ->scalarPrototype()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    private function addTelegramConfiguration(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('telegram');
        $node = $treeBuilder->getRootNode();

        $node
            ->children()
                ->scalarNode('class')
                    ->defaultValue(TelegramDriver::class)
                    ->validate()
                        ->ifTrue(function ($v) {
                            return !\is_subclass_of($v, TelegramDriver::class);
                        })
                        ->thenInvalid('Class \'%s\' must be a valid Telegram BotMan driver.')
                    ->end()
                ->end()

                ->arrayNode('parameters')
                    ->isRequired()
                    ->children()
                        ->scalarNode('token')->isRequired()->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    private function addHttpNode(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('http');
        $node = $treeBuilder->getRootNode();

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('client')
                    ->defaultValue('httplug.client.default')
                ->end()

                ->scalarNode('message_factory')
                    ->defaultValue('httplug.message_factory.default')
                ->end()
            ->end()
        ;

        return $node;
    }
}
