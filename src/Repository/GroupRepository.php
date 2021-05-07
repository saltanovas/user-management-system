<?php

namespace App\Repository;

use App\Entity\Group;
use App\Entity\User; 
use App\Repository\Repository;
use Doctrine\Persistence\ManagerRegistry;

class GroupRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__contruct($registry, Group::class);
    }

    public function removeUser(Group $group, User $user)
    {
        if (!is_null($group) && !is_null($user)) {
            $group->removeUser($user);
            $user->removeGroup($group);

            $this->insert($user);
            $this->save();
        }
    }
}
