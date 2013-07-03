<?php
/*
 * This file is part of the WSGeneratorBundle package.
 *
 * (c) Benjamin Georgeault <https://github.com/WedgeSama/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\GeneratorBundle\Generator;

use Symfony\Component\DependencyInjection\Container;
use WS\GeneratorBundle\Tools\Tools;

class EntityGenerator extends Generator {

	public function generate($shortcut, array $fields, array $entities, $license) {
		list ($bundle, $entity) = Tools::parseShortcutNotation($shortcut);
		
		$bundleInterface = $this->container->get('kernel')
			->getBundle($bundle);
		
		$entity_path = $bundleInterface->getPath() . '/Entity/' .
				 str_replace('\\', '/', $entity) . '.php';
		
		$bundle_basename = substr($bundle, 0, - 6);
		$parameters = array(
				'namespace' => $bundleInterface->getNamespace(), 
				'bundle' => $bundle, 
				'bundle_basename' => $bundle_basename, 
				'entity' => $entity, 
				'license' => $license, 
				'bundle_extension_alias' => Container::underscore(
						$bundle_basename), 
				'entity_extension_alias' => Container::underscore($entity), 
				'full_extension_alias' => Container::underscore(
						$bundle_basename . $entity), 
				'valid' => false, 
				'uniq' => false, 
				'fields' => $fields, 
				'entities' => $entities 
		);
		
		foreach ($fields as $field) {
			if (isset($field['validate_rules']))
				$parameters['valid'] = true;
			if (isset($field['type_opts']))
				$parameters['uniq'] = true;
			if ($parameters['valid'] && $parameters['uniq'])
				break;
		}
		
		$this->renderFile('Entity/Entity.php.twig', $entity_path, $parameters);
	}

}