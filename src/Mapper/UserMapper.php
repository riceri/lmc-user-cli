<?php

namespace LmcUserCli\Mapper;

use LmcUser\Entity\UserInterface as UserEntityInterface;
use Laminas\Hydrator\HydratorInterface;
use LmcUser\Mapper\User as LmcUser;
use LmcUser\Mapper\UserInterface;

class UserMapper extends LmcUser implements UserInterface
{
    protected $tableColumns  = [];

    public function fetchAll()
    {
        $select = $this->getSelect()->columns($this->getTableColumns());
        $entities = $this->select($select);

        $this->getEventManager()->trigger('fetchAll', $this, ['entities' => $entities]);

        return $entities;
    }

    public function findByEmail($email)
    {
        $select = $this->getSelect()
            ->columns($this->getTableColumns())
            ->where([$this->tableColumns['email'] => $email]);
        $entity = $this->select($select)->current();

        $this->getEventManager()->trigger('find', $this, ['entity' => $entity]);

        return $entity;
    }

    public function findByUsername($username)
    {
        $select = $this->getSelect()
            ->columns($this->getTableColumns())
            ->where([$this->tableColumns['username'] => $username]);
        $entity = $this->select($select)->current();

        $this->getEventManager()->trigger('find', $this, ['entity' => $entity]);

        return $entity;
    }

    public function findById($id)
    {
        $select = $this->getSelect()
            ->columns($this->getTableColumns())
            ->where([$this->tableColumns['user_id'] => $id]);
        $entity = $this->select($select)->current();

        $this->getEventManager()->trigger('find', $this, ['entity' => $entity]);

        return $entity;
    }

    public function getTableColumns()
    {
        return $this->tableColumns;
    }

    public function setTableColumns($tableColumns)
    {
        $this->tableColumns = $tableColumns;
    }

    public function insert(UserEntityInterface $entity, $tableName = null, HydratorInterface $hydrator = null)
    {
        $result = parent::insert($entity, $tableName, $hydrator);

        $entity->setId($result->getGeneratedValue());

        return $result;
    }

    public function update(UserEntityInterface $entity, $where = null, $tableName = null, HydratorInterface $hydrator = null)
    {
        if (! $where) {
            $where = [$this->tableColumns['user_id'] => $entity->getId()];
        }

        return parent::update($entity, $where, $tableName, $hydrator);
    }

    public function delete($where, $tableName = null)
    {
        parent::delete($where, $tableName);
    }
}
