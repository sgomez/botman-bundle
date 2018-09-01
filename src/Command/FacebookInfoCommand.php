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

use Sgomez\Bundle\BotmanBundle\Services\Http\FacebookClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

class FacebookInfoCommand extends Command
{
    /**
     * @var FacebookClient
     */
    private $client;

    public function __construct(FacebookClient $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    protected function configure(): void
    {
        $this
            ->setName('botman:facebook:info')
            ->setDescription('Retrieve the current values of Messenger Profile Properties')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        $response = $this->client->getProperties();
        $yaml = Yaml::dump($response, 3, 4);

        $io->title('Messenger Profile Properties');
        $io->writeln($yaml);
    }
}
