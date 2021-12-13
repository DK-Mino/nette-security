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

    public function testMetrics()
    {

        for ($i = 0; $i < 10000000 ; $i++){
            $permission = new Security\Entities\Permission(new Security\Entities\Resource('name'), 'privilege' );
        }
        Assert::true($permission instanceof Security\Entities\Permission);
    }
}


\run(new PrivilegesTest());
