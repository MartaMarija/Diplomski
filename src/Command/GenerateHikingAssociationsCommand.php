<?php

namespace App\Command;

use App\Constant\GenerateCommandConstant;
use Faker\Factory as FakerFactory;
use Pimcore\Db;
use Pimcore\Model\Asset;
use Pimcore\Model\DataObject\City;
use Pimcore\Model\DataObject\HikingAssociation;
use Pimcore\Model\DataObject\Service;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateHikingAssociationsCommand extends Command
{
    protected static $defaultName = 'app:generate:hiking-associations';

    private $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        for ($i = 0; $i < GenerateCommandConstant::HIKING_ASSOCIATIONS; $i++) {
            $this->create();
        }

        $output->writeln('Hiking Associations generated successfully!');

        return Command::SUCCESS;
    }

    public function create()
    {
        $path = Service::createFolderByPath('/Planinarska društva');

        $hikingAssociation = new HikingAssociation();
        $hikingAssociation->setParent($path);
        $hikingAssociation->setPublished(true);

        $hikingAssociation->setCity($this->getCity());
        $hikingAssociation->setName($this->faker->unique()->city());
        $hikingAssociation->setLogo(Asset::getByPath('/TestLogo.png'));
        $hikingAssociation->setKey($hikingAssociation->getName() . uniqid());

        return $hikingAssociation->save();
    }

    public function getCity(): City
    {
        $query = Db::getConnection()->prepare(
            'SELECT oo_id FROM object_City ORDER BY RAND() LIMIT 1'
        );

        $cityId = $query->executeQuery()->fetchOne();

        return City::getById($cityId);
    }
}
