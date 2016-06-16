<?php

namespace APIRestBundle\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ClientCreateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('vp:oauth-server:client-create')
            ->setDescription('Create a new client')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $clientManager = $this->getApplication()->getKernel()->getContainer()->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris(array("RedirectUris" =>"book-box.local"));
        $client->setAllowedGrantTypes(["ROLE_USER"]);
        $clientManager->updateClient($client);
        $output->writeln(sprintf('Added a new client with name <info>%s</info> and public id <info>%s</info>.',"kiwi", $client->getPublicId()));
    }
}