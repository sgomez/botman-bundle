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
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FacebookStartButtonCommand extends Command
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;
    /**
     * @var FacebookClient
     */
    private $client;

    public function __construct(ParameterBagInterface $parameterBag, FacebookClient $client)
    {
        parent::__construct();

        $this->parameterBag = $parameterBag;
        $this->client = $client;
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

            $config = $this->parameterBag->get('botman.drivers');
            if (!isset($config['facebook']['parameters']['start_button_payload']) || empty($config['facebook']['parameters']['start_button_payload'])) {
                $io->error('Parameter `greeting` must be configured in `botman.drivers.facebook.start_button_payload` config path.');

                return;
            }

            $this->client->setGetStarted($config['facebook']['parameters']['start_button_payload']);
        } catch (FacebookClientException $e) {
            $io->error($e->getMessage());

            return;
        }

        $io->success('`Get started` button payload configured.');
    }
}
