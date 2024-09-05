<?php

namespace App\Command;

use App\Constant\GenerateCommandConstant;
use Faker\Factory as FakerFactory;
use Pimcore\Db;
use Pimcore\Model\DataObject\City;
use Pimcore\Model\DataObject\Fieldcollection;
use Pimcore\Model\DataObject\Fieldcollection\Data\ContactFC;
use Pimcore\Model\DataObject\Guide;
use Pimcore\Model\DataObject\HikingAssociation;
use Pimcore\Model\DataObject\Service;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateGuidesCommand extends Command
{
    protected static $defaultName = 'app:generate:guides';
    private $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fc = new Fieldcollection();
        $contactFC = new ContactFC();
        $contactFC->setContactType('Broj mobitela');
        $contactFC->setContactDetails('091 111 1111');
        $fc->setItems([$contactFC]);

        $hikingAssociations = $this->getHikingAssociations();

        foreach ($hikingAssociations as $hikingAssociation) {
            for ($i = 0; $i < GenerateCommandConstant::GUIDES; $i++) {
                $this->create($hikingAssociation, $fc);
            }
            $output->writeln('Guides generated successfully for ' . $hikingAssociation->getId());
        }

        $output->writeln('Guides generated successfully!');

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

    public function create(HikingAssociation $hikingAssociation, Fieldcollection $fc)
    {
        $path = Service::createFolderByPath(sprintf('/Planinarska društva/%s/Vodiči', $hikingAssociation->getKey()));

        $guide = new Guide();
        $guide->setParent($path);
        $guide->setPublished(true);

        $guide->setHikingAssociation($hikingAssociation);
        $guide->setContactInformation($fc);
        $guide->setFirstName($this->faker->firstName);
        $guide->setLastName($this->faker->lastName);
        $guide->setKey($this->faker->unique()->email());

        return $guide->save();
    }
}
