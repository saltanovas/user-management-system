<?php

namespace App\Repository;

use App\Entity\Group;
use App\Entity\User;
use App\Repository\Repository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function removeGroupFromUser(User $user, Group $group)
    {
        if (!is_null($group) && !is_null($user)) {
            $user->removeGroup($group);
            $group->removeUser($user);

            $this->insert($group);
            $this->save();
        }
    }
}
