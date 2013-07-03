<?php
/*
 * This file is part of the WSGeneratorBundle package.
*
* (c) Benjamin Georgeault <https://github.com/WedgeSama/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace WS\GeneratorBundle\Tools;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Tools {

	static public function parseShortcutNotation($shortcut) {
		$entity = str_replace('/', '\\', $shortcut);
		
		$pos = strpos($entity, ':');
		
		if (false == $pos) {
			throw new \InvalidArgumentException('Nom d\'entity invalide.');
		}
		
		return array (
				substr($entity, 0, $pos),substr($entity, $pos + 1) 
		);
	}

	static public function entityExist(ContainerInterface $container, $shortcut) {
		list ($bundle, $entity) = self::parseShortcutNotation($shortcut);
		
		$bundle = $container->get('kernel')
			->getBundle($bundle);
		if (file_exists(
						$bundle->getPath() . '/Entity/' .
								 str_replace('\\', '/', $entity) . '.php'))
			return true;
		
		return false;
	}

}