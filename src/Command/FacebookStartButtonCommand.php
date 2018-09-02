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

class FacebookStartButtonCommand extends Command
{
    /**
     * @var FacebookClient
     */
    private $client;
    /**
     * @var null|string
     */
    private $payload;

    public function __construct(FacebookClient $client, ?string $payload)
    {
        parent::__construct();

        $this->client = $client;
        $this->payload = $payload;
    }

    protected function configure(): void
    {
        $this
            ->setName('botman:facebook:start-button')
            ->setDescription('Configure Messenger `Get started` buttom from driver configuration')
            ->addOption('unset', null, InputOption::VALUE_NONE, 'Remove property')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $unset = $input->getOption('unset');
            if ($unset) {
                $this->client->unsetProperty(FacebookClient::PROPERTY_GET_STARTED);

                $io->success('`Get started` button payload removed.');

                return;
            }

            if (null === $this->payload) {
                $io->error('Parameter `start_button_payload` must be configured in `botman.driver.facebook.start_button_payload` config path.');

                return;
            }

            $this->client->setGetStarted($this->payload);

            $io->success('`Get started` button payload configured.');
        } catch (FacebookClientException $e) {
            $io->error($e->getMessage());
        }
    }
}
