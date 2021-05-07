<?php

namespace App\Repository;

use App\Entity\Group;
use App\Repository\Repository;
use Doctrine\Persistence\ManagerRegistry;

class GroupRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__contruct($registry, Group::class);
    }

    public function removeUser($groupId, $userId)
    {
        // if I use Group or User, symfony cannot find such classes
        $group = $this->_em->find('App\Entity\Group', $groupId);
        $user = $this->_em->find('App\Entity\User', $userId);

        if (!is_null($group) && !is_null($user)) {
            $group->removeUser($user);
            $user->removeGroup($group);

            $this->insert($user);
            $this->save();
        }
    }
}
