<?php
namespace Stakeholders\ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ClientCreateCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('sh:oauth:client:create')
			->setDescription('Create new oAuth client')
			->addOption('redirect-uri', null, InputOption::VALUE_REQUIRED|InputOption::VALUE_IS_ARRAY, 'Set the redirect uri(s).', null)
			->addOption('grant-type', null, InputOption::VALUE_REQUIRED|InputOption::VALUE_IS_ARRAY, 'Set allowed grant type(s).', null);
	}
	
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');
		$client = $clientManager->createClient();
		$client->setRedirectUris(array('http://sh.4m-team.pro'));
		$client->setAllowedGrantTypes(array('password'));
		$clientManager->updateClient($client);
		$output->writeln('<info>Added a new client:</info>');
		$output->writeln(sprintf('Public ID: <comment>%s</comment>', $client->getPublicId()));
		$output->writeln(sprintf('Secret: <comment>%s</comment>', $client->getSecret()));
	}
}