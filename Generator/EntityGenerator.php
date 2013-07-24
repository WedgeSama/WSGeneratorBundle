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
                'uniq' => false 
        );
        
        foreach ($fields as $field_key => $field_value) {
            if (isset($field_value['validate_rules']))
                $parameters['valid'] = true;
            if (isset($field_value['type_opts']))
                $parameters['uniq'] = true;
        }
        
        $this->parseFields($fields);
        
        $parameters['fields'] = $fields;
        $parameters['entities'] = $entities;
        
        $this->renderFile('Entity/Entity.php.twig', $entity_path, $parameters);
    }

    protected function parseFields(array &$fields) {
        foreach ($fields as $field_key => &$field_value) {
            if (isset($field_value['type_opts'])) {
                $this->parseForTwig($field_value['type_opts']);
            }
        }
    }

    protected function parseForTwig(&$field_type_opts) {
        foreach ($field_type_opts as $key => &$value) {
            if (is_bool($value)) {
                if ($value)
                    $value = 'true';
                else
                    $value = 'false';
            }
            
            if ($key == "unique") {
                if ($value == 'false')
                    unset($field_type_opts[$key]);
            } else if ($key == "nullable") {
                if ($value == 'false')
                    unset($field_type_opts[$key]);
            }
        }
    }

}