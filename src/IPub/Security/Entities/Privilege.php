<?php

namespace IPub\Security\Entities;

use Nette;
use Nette\Security\IAuthorizator;
use IPub\Security\Exceptions;
use Nette\SmartObject;

class Privilege implements IPrivilege
{
    use SmartObject;

    /** @var string */
    private $name;

    /** @var string|IAuthorizator::ALL */
    private $value;

    /** @var Permission */
    private $permission;



    /**
     * @param string|IAuthorizator $value
     * @param string|NULL $name
     * @param Permission $permission
     */
    public function __construct($value, $permission, $name = null)
    {
        if (!is_string($value) && ($value !== IAuthorizator::ALL)) {
           throw new Exceptions\InvalidArgumentException('Privilege value must be either string or Nette\Security\IAuthorizator::ALL');
        }
        if (!$permission instanceof Permission) {
            throw new Exceptions\InvalidArgumentException('Permission must be of type Permission');
        }

        $this->value = (string) $value;
        $this->name = $name;
        $this->permission = $permission;
        $this->permission->setNewPrivilege($this);
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return string|IAuthorizator::ALL
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Permission
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
