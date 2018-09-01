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

use BotMan\Drivers\Telegram\TelegramDriver;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('botman');

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
                    ->end()
                ->end()
                ->append($this->addHttpNode())
            ->end()
        ;

        return $treeBuilder;
    }

    private function addTelegramConfiguration(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('telegram');

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
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('http');

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('client')->defaultValue('httplug.client.default')->end()
                ->scalarNode('message_factory')->defaultValue('httplug.message_factory.default')->end()
            ->end()
        ;

        return $node;
    }
}
