<?php

namespace App\Command;

use App\Constant\GenerateCommandConstant;
use App\Model\Member;
use Faker\Factory as FakerFactory;
use Pimcore\Db;
use Pimcore\Model\DataObject\HikingAssociation;
use Pimcore\Model\DataObject\Member\Listing;
use Pimcore\Model\DataObject\Payment;
use Pimcore\Model\DataObject\Service;
use Pimcore\Model\DataObject\Trip;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GeneratePaymentObjectsCommand extends Command
{
    protected static $defaultName = 'app:generate:payments';
    private $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $members = $this->getMembers();

        foreach ($members as $member) {

            // Create trip payments
//            $trips = $this->getTrips();

            $trips = [
                6323,6324,6325,6326,6327,6328,6329,6330,6331,6332,6333,6334,6335,6336,6337,6338,6339,6340,6341,6342,6343,6344,6345,6346,6347,6348,6349,6350,6351,6352,6353,6354,6355,6356,6357,6358,6359,6360,6361,6362,6363,6364,6365,6366,6367,6368,6369,6370,6371
            ];
            foreach ($trips as $trip) {
                try{
                    $this->createTripPayment($member, Trip::getById($trip));
                }catch (\Exception) {

                }
            }

            // Create memberships
//            $hikingAssociations = $this->getHikingAssociations();
//            foreach ($hikingAssociations as $hikingAssociation) {
//                $this->createMembershipPayment($member, $hikingAssociation);
//            }
        }

        $output->writeln('Payments generated successfully!');

        return Command::SUCCESS;
    }

    public function getMembers(): array
    {
        $query = Db::getConnection()->prepare(
            'SELECT oo_id FROM object_Member ORDER BY RAND() LIMIT ' . GenerateCommandConstant::MEMBERS
        );

        $results = $query->executeQuery()->fetchAllAssociative();

        return array_map(function ($result) {
            return Member::getById($result['oo_id']);
        }, $results);
//        $listing = new Listing();
//        $listing->setOrderKey('creationDate');
//        $listing->setOrder('DESC');
//
//        $listing->setLimit(GenerateCommandConstant::MEMBERS);
//
//        return $listing->getObjects();
    }

    public function getTrips(): array
    {
        $query = Db::getConnection()->prepare(
            'SELECT oo_id FROM object_Trip ORDER BY RAND() LIMIT ' . GenerateCommandConstant::TRIP_PAYMENTS
        );

        $results = $query->executeQuery()->fetchAllAssociative();

        return array_map(function ($result) {
            return Trip::getById($result['oo_id']);
        }, $results);
    }

    public function getHikingAssociations(): array
    {
        $query = Db::getConnection()->prepare(
            'SELECT oo_id FROM object_HikingAssociation ORDER BY RAND() LIMIT ' . GenerateCommandConstant::MEMBERSHIP_PAYMENTS
        );

        $results = $query->executeQuery()->fetchAllAssociative();

        return array_map(function ($result) {
            return HikingAssociation::getById($result['oo_id']);
        }, $results);
    }

    public function createTripPayment(Member $member, Trip $trip)
    {
        $year = rand(2020, 2024);
        $hikingAssociation = $trip->getHikingAssociation();

        $path = Service::createFolderByPath(sprintf(
            '/Planinarska društva/%s/Izleti/%s/Uplate', $hikingAssociation->getKey(), $trip->getKey())
        );

        $payment = new Payment();
        $payment->setParent($path);
        $payment->setPublished(true);
        $payment->setKey($member->getEmail());

        $payment->setPaymentObject($trip);
        $payment->setMember($member);
        $payment->setPaymentType('Uživo');
        $payment->setDescription('Izlet');
        $payment->setYear($year);

        return $payment->save();
    }

    public function createMembershipPayment(Member $member, HikingAssociation $hikingAssociation)
    {
        $year = rand(2020, 2024);

        $path = Service::createFolderByPath(sprintf('/Planinarska društva/%s/Članarine/%s', $hikingAssociation->getKey(), $year));

        $payment = new Payment();
        $payment->setParent($path);
        $payment->setPublished(true);
        $payment->setKey($member->getEmail());

        $payment->setPaymentObject($hikingAssociation);
        $payment->setMember($member);
        $payment->setPaymentType('Uživo');
        $payment->setDescription('Članarina');
        $payment->setYear($year);

        return $payment->save();
    }
}
