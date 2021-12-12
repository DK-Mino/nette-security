<?php

namespace IPubTests\Security;

use Mockery\Mock;
use Nette;
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


    public function testPrivilegePermissionConnection()
    {
        //Setup permission mock
        $mock = \Mockery::mock(Security\Entities\Permission::class);
        $mock->shouldReceive('setNewPrivilege')->andReturnSelf();
        $mock->shouldReceive('getNewPrivilege')->andReturn(new Security\Entities\Privilege('fake.value', $mock));

        //Get privilege from mock
        $privilege = $mock->getNewPrivilege();

        //Assertions
        Assert::true($privilege->getPermission() instanceof  Security\Entities\Permission);
        Assert::true($privilege->getValue() === 'fake.value');
        Assert::true($privilege->getPermission() === $mock);

    }

    public function testUpdatePrivilege()
    {
        //Edit privilege
        $privilege = new Security\Entities\Privilege('fake.value', new Security\Entities\Permission());
        $privilege->setValue('updated.value');
        $privilege->setName('Test privilege');

        //Assertions
        Assert::true($privilege->getName() === 'Test privilege');
        Assert::true($privilege->getValue() === 'updated.value');
        Assert::true($privilege->__toString() === 'Test privilege');
    }

    public function testPrivilegeExceptions()
    {
        Assert::exception(function (){
            new Security\Entities\Privilege(1, 2);
        },IPub\Security\Exceptions\InvalidArgumentException::class, 'Privilege value must be either string or Nette\Security\IAuthorizator::ALL');
        Assert::exception(function (){
            new Security\Entities\Privilege('exception test', 2);
        },IPub\Security\Exceptions\InvalidArgumentException::class, 'Permission must be of type Permission');
    }

    public function testGetNewPrivilege()
    {
        $permission = new Security\Entities\Permission(null,'privilege');
        $privilege = $permission->getNewPrivilege();

        Assert::true($privilege instanceof Security\Entities\Privilege);
        Assert::true($privilege->getValue() === 'privilege');
    }
}


\run(new PrivilegesTest());
