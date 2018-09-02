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

use Sgomez\Bundle\BotmanBundle\Command\Helpers\TelegramTrait;
use Sgomez\Bundle\BotmanBundle\Services\Http\TelegramClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\RouterInterface;

class TelegramWebhookCommand extends Command
{
    use TelegramTrait;

    /**
     * @var TelegramClient
     */
    private $telegramClient;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(TelegramClient $telegramClient, RouterInterface $router)
    {
        parent::__construct();

        $this->telegramClient = $telegramClient;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('botman:telegram:webhook')
            ->setDescription('Configure the system webhook to be used by Telegram bot')
            ->addOption('unset', null, InputOption::VALUE_NONE, 'Remove webhook')
            ->addArgument('url', InputArgument::OPTIONAL, 'Custom webhook url')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);
        $unset = $input->getOption('unset');
        $webhookRoute = $input->getArgument('url');

        if ($unset) {
            $response = $this->telegramClient->removeWebhook();
            $this->printWebhookResponse($io, $response);

            return;
        }

        if (null === $webhookRoute) {
            $webhookRoute = $this->router->generate('botman_webhook', [], RouterInterface::ABSOLUTE_URL);
        }

        $response = $this->telegramClient->setWebhook($webhookRoute);
        $this->printWebhookResponse($io, $response);
    }
}
