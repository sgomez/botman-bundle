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
                        ->append($this->addDriverDefinition('telegram', TelegramDriver::class, ['token' => 'scalar']))
                    ->end()
                ->end()
                ->append($this->addHttpNode())
            ->end()
        ;

        return $treeBuilder;
    }

    private function addDriverDefinition(string $name, string $driver, array $params): NodeDefinition
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root($name);

        $node
            ->children()
                ->scalarNode('class')
                    ->defaultValue($driver)
                    ->validate()
                        ->ifTrue(function ($v) use ($driver) {
                            return !is_subclass_of($v, $driver)
                                ;
                        })
                        ->thenInvalid('Class \'%s\' must be a valid ' . $name . ' botman driver')
                    ->end()
                ->end()
                ->append($this->addDriverOptions($params))
            ->end()
        ;

        return $node;
    }

    private function addDriverOptions(array $params): NodeDefinition
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('parameters');
        $node->isRequired()->ignoreExtraKeys();

        $children = $node->children();

        foreach ($params as $name => $type) {
            $children->node($name, $type)->isRequired()->end();
        }
        $node = $children->end();

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
