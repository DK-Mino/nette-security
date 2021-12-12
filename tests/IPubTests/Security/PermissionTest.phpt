<?php

namespace IPubTests\Security;

use Mockery\Mock;
use Nette;
use Nette\Security\IAuthorizator;
use Tester;
use Tester\Assert;

use IPub;
use IPub\Security;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../lib/PermissionsProvider.php';
require __DIR__ . '/../lib/RolesProvider.php';


class PrivilegesTest extends Tester\TestCase
{
    /**
     * @var Security\Permission
     */
    private $permission;


    /**
     * @return Nette\DI\Container
     */
    protected function createContainer()
    {
        $config = new Nette\Configurator();
        $config->enableDebugger();
        error_reporting(~E_USER_DEPRECATED);
        $config->setTempDirectory(TEMP_DIR);
        $config->addConfig(__DIR__ . '/../config/application.neon', $config::NONE);
        $config->addConfig(__DIR__ . '/../config/providers.neon', $config::NONE);

        Security\DI\SecurityExtension::register($config);

        return $config->createContainer();
    }


    public function setUp()
    {
        parent::setUp();

        $dic = $this->createContainer();
        $this->permission = $dic->getService('ipubSecurity.permission');
    }


    public function testPermissionComments()
    {
        //Create new permission
        $permission = new Security\Entities\Permission();

        //Assertion
        Assert::null($permission->getComment());

        //Add a comment and test it
        $permission->setComment('New comment');
        Assert::true($permission->getComment() === 'New comment');

    }

    public function testSetNewPrivilegeException()
    {
        //Create new permission
        $permission = new Security\Entities\Permission();

        Assert::exception(function () use ($permission){
            $permission->setNewPrivilege('Privilege');
        },IPub\Security\Exceptions\InvalidArgumentException::class, 'Privilege must be of type Privilege');
    }
}


\run(new PrivilegesTest());
