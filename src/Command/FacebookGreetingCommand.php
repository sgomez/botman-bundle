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

namespace Sgomez\Bundle\BotmanBundle\Command;

use Sgomez\Bundle\BotmanBundle\Exception\FacebookClientException;
use Sgomez\Bundle\BotmanBundle\Services\Http\FacebookClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FacebookGreetingCommand extends Command
{
    /**
     * @var FacebookClient
     */
    private $client;
    /**
     * @var array
     */
    private $greeting;

    public function __construct(FacebookClient $client, array $greeting)
    {
        parent::__construct();

        $this->client = $client;
        $this->greeting = $greeting;
    }

    protected function configure(): void
    {
        $this
            ->setName('botman:facebook:greeting')
            ->setDescription('Configure greeting message from driver configuration')
            ->addOption('unset', null, InputOption::VALUE_NONE, 'Remove property')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $unset = $input->getOption('unset');
            if ($unset) {
                $this->client->unsetProperty(FacebookClient::PROPERTY_GREETING);

                $io->success('`Greeting` message removed.');

                return;
            }

            if (empty($this->greeting)) {
                $io->error('Parameter `greeting` must be configured in `botman.drivers.facebook.parameters` config path.');

                return;
            }

            $this->client->setGreetingText($this->greeting);

            $io->success('`Greeting` message configured.');
        } catch (FacebookClientException $e) {
            $io->error($e->getMessage());
        }
    }
}
