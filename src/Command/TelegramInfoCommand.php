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

use Sgomez\Bundle\BotmanBundle\Services\Http\TelegramClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TelegramInfoCommand extends Command
{
    /**
     * @var TelegramClient
     */
    private $telegramClient;

    public function __construct(TelegramClient $telegramClient)
    {
        parent::__construct();

        $this->telegramClient = $telegramClient;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('botman:telegram:info')
            ->setDescription('Retrieve the current values of Telegram bot and its webhook status')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        $me = $this->telegramClient->getMe();
        $webhookInfo = $this->telegramClient->getWebhookInfo();

        $io->title('Bot basic information');

        $io->writeln('<info>Id.</info>: ' . $me->getId());
        $io->writeln('<info>Is a bot</info>: ' . ($me->isBot() ? 'true' : 'false'));
        $io->writeln('<info>First name</info>: ' . $me->getFirstName());
        $io->writeln('<info>Last name</info>: ' . $me->getLastName());
        $io->writeln('<info>Username</info>: ' . $me->getUsername());
        $io->writeln('<info>Language code</info>: ' . $me->getLanguageCode());


        $io->title('Current webhook status');
        foreach ($webhookInfo['result'] as $key => $value) {
            $io->writeln(sprintf('<info>%s</info>: %s', $key, $value));
        }
    }
}
