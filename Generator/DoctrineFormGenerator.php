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

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\Yaml\Yaml;

/**
 * @see Sensio\Bundle\GeneratorBundle\Generator\DoctrineFormGenerator
 */
class DoctrineFormGenerator extends Generator
{
    private $filesystem;
    private $className;
    private $classPath;

    /**
     * Constructor.
     *
     * @param Filesystem $filesystem A Filesystem instance
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getClassPath()
    {
        return $this->classPath;
    }

    /**
     * Generates the entity form class if it does not exist.
     *
     * @param BundleInterface   $bundle   The bundle in which to create the class
     * @param string            $entity   The entity relative class name
     * @param ClassMetadataInfo $metadata The entity metadata class
     */
    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata)
    {
        $parts       = explode('\\', $entity);
        $entityClass = array_pop($parts);

        $this->className = $entityClass.'Type';
        $dirPath         = $bundle->getPath().'/Form';
        $this->classPath = $dirPath.'/'.str_replace('\\', '/', $entity).'Type.php';
        $formName = strtolower(substr(str_replace('\\', '_', $bundle->getNamespace()), 0, -6).($parts ? '_' : '').implode('_', $parts).'_'.substr($this->className, 0, -4));
        $key = 'form.type.'.$formName;

        if (file_exists($this->classPath)) {
            throw new \RuntimeException(sprintf('Unable to generate the %s form class as it already exists under the %s file', $this->className, $this->classPath));
        }

        if (count($metadata->identifier) > 1) {
            throw new \RuntimeException('The form generator does not support entity classes with multiple primary keys.');
        }

        $parts = explode('\\', $entity);
        array_pop($parts);
        $entityNamespace = implode('\\', $parts);

        $this->renderFile('form/FormType.php.twig', $this->classPath, array(
            'fields'           => $this->getFieldsFromMetadata($metadata),
            'namespace'        => $bundle->getNamespace(),
            'entity_namespace' => $entityNamespace,
            'entity_class'     => $entityClass,
            'bundle'           => $bundle->getName(),
            'form_class'       => $this->className,
            'form_type_name'   => $formName,
        ));

        // Make form type as a service.

        $file = $bundle->getPath().'/Resources/config/forms.yml';

        if (! $this->filesystem->exists($file)) {
            $this->filesystem->touch($file);
        }

        $array = Yaml::parse(file_get_contents($file));

        if (! is_array($array)) {
            $array = array();
        }

        if (! array_key_exists('services', $array)) {
            $array['services'] = array();
        }

        if (! is_array($array['services'])) {
            $array['services'] = array();
        }

        if (array_key_exists($key, $array['services'])) {
            throw new \RuntimeException(sprintf('Unable to add type form to form\'s services file, the key "%s" already exist', $key));
        }

        $fullNamespace = $bundle->getNamespace().'\\Form'.($entityNamespace?'\\'.$entityNamespace:'').'\\'.$this->className;

        $array['services'][$key] = array(
            'class' => $fullNamespace,
            'tags' => array(
                array(
                    'name' => 'form.type',
                    'alias' => $formName
                )
            )
        );


        file_put_contents($file, Yaml::dump($array, 4));
    }

    /**
     * Returns an array of fields. Fields can be both column fields and
     * association fields.
     *
     * @param  ClassMetadataInfo $metadata
     * @return array             $fields
     */
    private function getFieldsFromMetadata(ClassMetadataInfo $metadata)
    {
        $fields = (array) $metadata->fieldNames;

        // Remove the primary key field if it's not managed manually
        if (!$metadata->isIdentifierNatural()) {
            $fields = array_diff($fields, $metadata->identifier);
        }

        foreach ($metadata->associationMappings as $fieldName => $relation) {
            if ($relation['type'] !== ClassMetadataInfo::ONE_TO_MANY) {
                $fields[] = $fieldName;
            }
        }

        return $fields;
    }
}

