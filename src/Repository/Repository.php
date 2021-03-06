<?php

namespace App\Repository;

use App\Repository\IRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Repository extends ServiceEntityRepository implements IRepository
{
    public function __contruct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    public function getAll()
    {
        return $this->findAll();
    }

    public function getById($key)
    {
        return $this->find($key);
    }

    public function insert($entity)
    {
        if(!is_null($entity)){
            $this->_em->persist($entity);
        }
    }

    public function delete($entity)
    {
        if(!is_null($entity)){
            $this->_em->remove($entity);
        }
    }

    public function save()
    {
        return $this->_em->flush();
    }
}
