<?php

namespace App\Repository;

use App\Entity\User;
use App\Repository\Repository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function removeGroupFromUser($userId, $groupId)
    {
        // if I use Group or User, symfony cannot find such classes
        $user = $this->_em->find('App\Entity\User', $userId);
        $group = $this->_em->find('App\Entity\Group', $groupId);

        $user->removeGroup($group);
        $group->removeUser($user);

        $this->insert($group);
        $this->save();

        //TODO: need some return to check whether remove was successful?
    }
}
