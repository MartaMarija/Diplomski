<?php

namespace App\Repository;

use Pimcore\Model\DataObject\Member;
use Pimcore\Model\DataObject\Service;

class MemberRepository
{
    public const PATH = '/Korisnici';

    public function createMember(Member $member)
    {
        $path = Service::createFolderByPath(self::PATH);

        $member->setParent($path);
        $member->setKey($member->getEmail());
        $member->setPublished(true);

        return $member->save();
    }
}
