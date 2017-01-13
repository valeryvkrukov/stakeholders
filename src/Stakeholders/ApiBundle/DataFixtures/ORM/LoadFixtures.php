<?php 
namespace Stakeholders\ApiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

use Stakeholders\ApiBundle\Entity\Account;
use Stakeholders\ApiBundle\Entity\Field;
use Stakeholders\ApiBundle\Entity\Campaign;

use Faker\Factory as Faker;
use Nelmio\Alice\Fixtures;

class LoadFixtures implements ContainerAwareInterface, FixtureInterface
{
	protected $container;
	protected $faker;
	
	public function load(ObjectManager $manager)
	{
		$kernel = $this->container->get('kernel');
		$output = new ConsoleOutput;
		$objects = Fixtures::load(__DIR__.'/users.yml', $manager, array(
			'logger' => function($message) use ($output) {
				$output->writeln(sprintf('<comment>%s</comment>', $message));
			}
		));
		$this->faker = Faker::create();
		
		$users = $manager->getRepository('StakeholdersApiBundle:User')->findAll();
		foreach ($users as $user) {
			$photo = $this->faker->image($kernel->getRootDir().'/../web/uploads/profiles');
			$user->setPhoto($this->container->get('router')->getContext()->getBaseUrl().'/uploads/profiles/'.basename($photo));
			$this->createAccount($manager, $user);
			$manager->persist($user);
			$manager->flush();
			$output->writeln(sprintf('>> <comment>User %s updated</comment>', $user->getEmail()));
			if (in_array('ROLE_INFLUENCER', $user->getRoles())) {
				for ($c = 0; $c < rand(2, 10); $c++) {
					$campaign = $this->createCampaigns($manager, $user, $users);
					if ($campaign) {
						$output->writeln(sprintf('>>> <comment>Campaign #%s: "%s" created</comment>', $c, $campaign->getTitle()));
					}
				}
			}
		}
	}
	
	protected function createAccount($manager, $user)
	{
		$account = new Account();
		$account->setUser($user);
		$provider = $this->faker->randomElement(array(
				'internal',
				'facebook',
				'google',
			));
		$account->setApiProvider($provider);
		if ($provider != 'internal') {
			$account->setApiKey($this->faker->uuid());
			$account->setApiSecret($this->faker->uuid());
		}
		$type = in_array('ROLE_INFLUENCER', $user->getRoles())?'influencer':'customer';
		$this->createFields($manager, $account, $type);
		$manager->persist($account);
	}
	
	protected function createFields($manager, $account, $type)
	{
		if ($type == 'influencer') {
			$audience = array();
			for ($i = 0; $i < rand(2, 5); $i++) {
				$audience[] = $this->faker->word();
			}
			$prices = array();
			foreach ($this->faker->randomElements(array('event', 'video', 'photoshoot', 'social_media'), rand(1, 4)) as $price) {
				$prices[$price] = $this->faker->randomFloat();
			}
			$required = array(
				'website' => array(
					'type' => 'text',
					'value' => $this->faker->url()
				),
				'audience' => array(
					'type' => 'array',
					'value' => json_encode($audience)
				),
				'prices' => array(
					'type' => 'array',
					'value' => json_encode($prices)
				),
				'frequency' => array(
					'type' => 'text',
					'value' => $this->faker->randomDigit()
				),
				'contact_number' => array(
					'type' => 'text',
					'value' => $this->faker->tollFreePhoneNumber()
				)
			);
			foreach ($required as $_field => $val) {
				$field = new Field();
				$field->setName($_field);
				$field->setValueType($val['type']);
				$field->setValue($val['value']);
				$field->setAccount($account);
				$manager->persist($field);
				$account->addField($field);
			}
		} else {
			$names = $this->faker->words(rand(5, 10));
			foreach ($names as $name) {
				$type = $this->faker->randomElement(array('json', 'text', 'html'));
				$field = new Field();
				$field->setName($name);
				$field->setValueType($type);
				if ($type == 'json') {
					$field->setValue(json_encode($this->faker->words()));
				} elseif ($type == 'text') {
					$field->setValue($this->faker->sentence());
				} else {
					$field->setValue($this->faker->text());
				}
				$field->setAccount($account);
				$manager->persist($field);
				$account->addField($field);
			}
		}
		$socials = $this->faker->randomElements(array(
			'facebook',
			'google-plus',
			'twitter',
			'instagram'
		), rand(1, 4));
		foreach ($socials as $network) {
			$field = new Field();
			$field->setName($network);
			$field->setValueType('account');
			$field->setValue(json_encode(array(
				'userID' => $this->faker->randomNumber(),
				'username' => $this->faker->userName(),
				'link' => $this->faker->url(),
			)));
			$field->setAccount($account);
			$manager->persist($field);
			$account->addField($field);
		}
	}
	
	protected function createCampaigns($manager, $user, $users)
	{
		$customer = null;
		shuffle($users);
		foreach ($users as $client) {
			if (in_array('ROLE_CUSTOMER', $client->getRoles())) {
				$customer = $client;
				break;
			}
		}
		if ($customer) {
			$campaign = new Campaign();
			$campaign->setCustomer($customer);
			$campaign->setInfluencer($user);
			$campaign->setTitle($this->faker->sentence());
			$campaign->setBrief($this->faker->text());
			$campaign->setPaymentStatus($this->faker->randomElement(array('unpaid', 'paid', 'pending')));
			$campaign->setStatus($this->faker->randomElement(array('new', 'active', 'review', 'completed')));
			$manager->persist($campaign);
			$manager->flush();
			return $campaign;
		}
		return false;
	}
	
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}
}