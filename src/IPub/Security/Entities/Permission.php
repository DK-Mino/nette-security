<?php

namespace IPub\Security\Entities;

use Nette;
use Nette\Security\IAuthorizator;
use IPub\Security\Entities;
use IPub\Security\Exceptions;
use Nette\SmartObject;


class Permission implements IPermission
{
    use SmartObject;

    /** @var Entities\IResource|NULL */
	protected $resource;

	/** @var string|NULL */
	protected $privilege;

	/** @var callable|NULL */
	protected $assertion;

	/** @var string */
	private $comment;


	/**
	 * @param IResource|NULL $resource
	 * @param string|NULL $privilege
	 * @param callable|NULL $assertion
	 */
	public function __construct(Entities\IResource $resource = NULL, string $privilege = NULL, callable $assertion = NULL)
	{

		$this->resource = $resource;
		$this->privilege = $privilege;
		$this->assertion = $assertion;
	}


	/**
	 * @return IResource|NULL
	 */
	public function getResource()
	{
		return $this->resource;
	}


	/**
	 * @return string|NULL
	 */
	public function getPrivilege()
	{
		return $this->privilege;
	}


	/**
	 * @return callable|NULL
	 */
	public function getAssertion()
	{
		return $this->assertion;
	}


	/**
	 * @param string $comment
	 * @return $this
	 */
	public function setComment($comment)
	{
		$this->comment = $comment;

		return $this;
	}


	/**
	 * @return string|NULL
	 */
	public function getComment()
	{
		return $this->comment;
	}


    /**
     * @param Privilege $privilege
     * @return $this
     */
    public function setNewPrivilege($privilege){

        if (!($privilege instanceof Privilege)) {
            throw new Exceptions\InvalidArgumentException('Privilege must be of type Privilege');
        }
        $this->privilege = $privilege->getValue();

        return $this;
    }

    /**
     * @return Privilege
     */
    public function getNewPrivilege()
    {
        return new Privilege($this->privilege, $this);
    }


	/**
	 * @return string
	 */
	public function __toString()
	{
		$ky = (string) $this->resource . IPermission::DELIMITER . (string) $this->privilege;

		return $ky;
	}

}
