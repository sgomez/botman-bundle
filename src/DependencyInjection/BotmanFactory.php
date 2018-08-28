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

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory as BaseBotManFactory;
use BotMan\BotMan\Cache\SymfonyCache;
use BotMan\BotMan\Drivers\DriverManager;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class BotmanFactory
{
    public static function create(ContainerInterface $container, AdapterInterface $filesystemAdapter, RequestStack $requestStack, array $drivers): BotMan
    {
        $config = [];
        foreach ($drivers as $driver => $options) {
            DriverManager::loadDriver($options['class']);
            $config[$driver] = $options['parameters'];
        }

        $cache = new SymfonyCache($filesystemAdapter);
        $request = $requestStack->getCurrentRequest();

        $botman = BaseBotManFactory::create($config, $cache, $request);
        $botman->setContainer($container);

        return $botman;
    }
}
