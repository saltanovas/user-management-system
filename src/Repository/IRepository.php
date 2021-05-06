<?php

namespace App\Repository;

interface IRepository
{
    public function getAll();
    public function getById($key);
    public function searchFor(...$criteria);
    public function insert($entity);
    public function delete($entity);
    public function save();
}
