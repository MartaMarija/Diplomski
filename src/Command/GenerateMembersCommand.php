<?php

namespace App\Command;

use App\Constant\GenerateCommandConstant;
use App\Repository\MemberRepository;
use Pimcore\Model\DataObject\Member;
use Pimcore\Model\DataObject\Service;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Faker\Factory as FakerFactory;

class GenerateMembersCommand extends Command
{
    protected static $defaultName = 'app:generate:members';

    private $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        for ($i = 0; $i < GenerateCommandConstant::MEMBERS; $i++) {
            $this->create();
        }

        $output->writeln('Members generated successfully!');

        return Command::SUCCESS;
    }

    public function create()
    {
        $path = Service::createFolderByPath(MemberRepository::PATH);

        $member = new Member();
        $member->setParent($path);
        $member->setPublished(true);

        $member->setEmail(uniqid() . $this->faker->unique()->safeEmail);
        $member->setPassword($this->faker->password);
        $member->setKey($member->getEmail());

        $member->setFirstName($this->faker->firstName);
        $member->setLastName($this->faker->lastName);
        $member->setOIB(11111111111);

        return $member->save();
    }
}
