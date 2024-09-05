<?php

namespace App\Command;

use App\Constant\GenerateCommandConstant;
use Faker\Factory as FakerFactory;
use Pimcore\Model\DataObject\HikingAssociation;
use Pimcore\Model\DataObject\Service;
use Pimcore\Model\DataObject\Trip;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateTripsCommand extends Command
{
    protected static $defaultName = 'app:generate:trips';
    private $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $hikingAssociations = $this->getHikingAssociations();

        foreach ($hikingAssociations as $hikingAssociation) {
            for ($i = 0; $i < GenerateCommandConstant::TRIPS; $i++) {
                $this->create($hikingAssociation);
            }
        }

        $output->writeln('Trips generated successfully!');

        return Command::SUCCESS;
    }

    public function getHikingAssociations(): array
    {
        $listing = new HikingAssociation\Listing();
        $listing->setOrderKey('creationDate');
        $listing->setOrder('DESC');

        $listing->setLimit(GenerateCommandConstant::HIKING_ASSOCIATIONS);

        return $listing->getObjects();
    }

    public function create(HikingAssociation $hikingAssociation)
    {
        $path = Service::createFolderByPath(sprintf('/Planinarska društva/%s/Izleti', $hikingAssociation->getKey()));

        $trip = new Trip();
        $trip->setParent($path);
        $trip->setPublished(true);

        $name = $this->faker->city();

        $trip->setHikingAssociation($hikingAssociation);
        $trip->setName($name);
        $trip->setAvailableCapacity(1000);
        $trip->setKey($name . ' - ' . $this->faker->dateTimeBetween('now', '+30 days')->format('m.d.Y'));

        return $trip->save();
    }
}

