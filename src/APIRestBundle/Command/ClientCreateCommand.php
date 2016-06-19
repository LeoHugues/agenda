<?php

namespace APIRestBundle\Command;


use FOS\OAuthServerBundle\Entity\ClientManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ClientCreateCommand extends Command
{

    const OPTION_REDIRECT_URI = 'redirect-uri';
    const OPTION_GRANT_TYPE = 'grant-type';

    /**
     * @var ClientManager
     */
    private $clientManager;

    public function __construct(ClientManager $clientManager)
    {
        parent::__construct();

        $this->clientManager = $clientManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('oauth:client:create')
            ->setDescription('Create a new OAuth client')
            ->addOption(
                self::OPTION_REDIRECT_URI,
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'If set add a redirect uri'
            )
            ->addOption(
                self::OPTION_GRANT_TYPE,
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'If set add a grant type'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = $this->clientManager->createClient();
        $client->setRedirectUris($input->getOption(self::OPTION_REDIRECT_URI));
        $client->setAllowedGrantTypes($input->getOption(self::OPTION_GRANT_TYPE));
        $this->clientManager->updateClient($client);

        $output->writeln('Client created');
        $output->writeln('client_id= ' . '<info>' . $client->getPublicId() . '</info>');
        $output->writeln('client_secret= ' . '<info>' . $client->getSecret() . '</info>');
    }
}