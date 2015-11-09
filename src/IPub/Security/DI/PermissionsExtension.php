<?php
/**
 * PermissionsExtension.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Permissions!
 * @subpackage	DI
 * @since		5.0
 *
 * @date		10.10.14
 */

namespace IPub\Security\DI;

use Nette;
use Nette\DI;
use Nette\PhpGenerator as Code;

class PermissionsExtension extends DI\CompilerExtension
{
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		// Application permissions
		$builder->addDefinition($this->prefix('permissions'))
			->setClass('IPub\Security\Permission');

		// Annotation access checkers
		$builder->addDefinition($this->prefix('checkers.annotation'))
			->setClass('IPub\Security\Access\AnnotationChecker');

		// Latte access checker
		$builder->addDefinition($this->prefix('checkers.latte'))
			->setClass('IPub\Security\Access\LatteChecker');

		// Link access checker
		$builder->addDefinition($this->prefix('checkers.link'))
			->setClass('IPub\Security\Access\LinkChecker');
	}

	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();

		// Get acl permissions service
		$service = $builder->getDefinition($this->prefix('permissions'));

		// Check all extensions and search for permissions provider
		foreach ($this->compiler->getExtensions() as $extension) {
			if (!$extension instanceof IPermissionsProvider) {
				continue;
			}

			// Get permissions & details
			foreach($extension->getPermissions() as $permission => $details) {
				// Assign permission to service
				$service->addSetup('addPermission', array($permission, $details));
			}
		}
		
		// Install extension latte macros
		$latteFactory = $builder->getDefinition($builder->getByType('\Nette\Bridges\ApplicationLatte\ILatteFactory') ?: 'nette.latteFactory');
		
		$latteFactory
			->addSetup('IPub\Security\Latte\Macros::install(?->getCompiler())', array('@self'));
	}

	/**
	 * @param Nette\Configurator $config
	 * @param string $extensionName
	 */
	public static function register(Nette\Configurator $config, $extensionName = 'permissions')
	{
		$config->onCompile[] = function (Nette\Configurator $config, Nette\DI\Compiler $compiler) use ($extensionName) {
			$compiler->addExtension($extensionName, new PermissionsExtension());
		};
	}
}
