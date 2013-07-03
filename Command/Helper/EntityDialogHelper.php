<?php
/*
 * This file is part of the WSGeneratorBundle package.
 *
 * (c) Benjamin Georgeault <https://github.com/WedgeSama/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\GeneratorBundle\Command\Helper;

use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use WS\GeneratorBundle\Tools\Tools;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EntityDialogHelper extends FieldDialogHelper {

	protected $container;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
	}

	/**
	 * Demande un nom d'entity
	 */
	public function askEntity($output, $entity = null) {
		$validator = function ($entity) {
			$entity = Validators::validateEntityName($entity);
		};
		
		return $this->askVar($output, 'Veuillez entrer le nom d\'une entity', 
				$validator, $entity);
	}

	/**
	 * Demande un nom d'entity existante
	 */
	public function askEntityExist($output, $entity = null) {
		$validator = function ($entity) {
			$entity = Validators::validateEntityName($entity);
			
			try {
				if (Tools::entityExist($this->container, $entity))
					return $entity;
				else
					throw new \InvalidArgumentException(
							'L\'entity n\'existe pas.');
			} catch (\Exception $e) {
				throw new \InvalidArgumentException('Bundle non existant.');
			}
		};
		
		return $this->askVar($output, 
				'Veuillez entrer le nom d\'une entity existante', $validator, 
				$entity);
	}

	/**
	 * Demande un nom d'entity non existante
	 */
	public function askEntityNotExist($output, $entity = null) {
		$validator = function ($entity) {
			$entity = Validators::validateEntityName($entity);
			
			try {
				if (! Tools::entityExist($this->container, $entity))
					return $entity;
			} catch (\Exception $e) {
				throw new \InvalidArgumentException('Bundle non existant.');
			}
			
			throw new \InvalidArgumentException('L\'entity existe deja.');
		};
		
		return $this->askVar($output, 
				'Veuillez entrer le nom d\'une entity non existante', $validator, 
				$entity);
	}

	public function askFields($output, $fields = array()) {
		$output->writeln(
				array(
						'', 
						'		---------------------------------', 
						'		         Ajout de champs', 
						'		Entrer un champ vide pour arreter', 
						'', 
						'		Types de champ autorises : ', 
						' ' . implode(', ', self::$ALLOWED_TYPES) 
				));
		
		// Demarre la boucle d'ajout de champ
		while (true) {
			$output->writeln(
					array(
							'		---------------------------------', 
							'' 
					));
			// demande le nom du nouveau champ
			$field = $this->askFieldName($output, $fields);
			if (! $field) {
				$output->writeln('		---------------------------------');
				break;
			}
			
			// Type
			$type = $this->askFieldType($output, $field);
			$type_opts = $this->askFieldTypeOptions($output, $type);
			
			// Validators
			$valids = $this->askFieldValidators($output, $type);
			
			// Ajout le champ a la liste
			$fields[$field] = $this->makeFieldAsArray($field, $type, 
					$type_opts, $valids);
		}
		
		$output->writeln(
				array(
						'		---------------------------------', 
						'' 
				));
		
		return $fields;
	}

	public function askEntities($output, $fields = array()) {
		$allowed_links = array();
		foreach (self::$ALLOWED_LINKS as $key => $value)
			$allowed_links[] = $key;
		
		$output->writeln(
				array(
						'', 
						'		---------------------------------', 
						'		         Lier des entities', 
						'		Entrer un champ vide pour arreter', 
						'', 
						'		Types de liens autorises : ', 
						' ' . implode(', ', $allowed_links) 
				));
		
		$entities = array();
		
		// Demarre la boucle d'ajout de champ
		while (true) {
			$output->writeln(
					array(
							'		---------------------------------', 
							'' 
					)); ################
			// demande le nom du nouveau champ
			$field = $this->askFieldName($output, $fields);
			if (! $field) {
				$output->writeln('		---------------------------------');
				break;
			}
			
			// Type
			$type = $this->askLinkType($output, $field);
			
			$type_entity = $this->askEntity($output);
			
			$type_opts = $this->askLinkTypeOptions($output, $type);
			
			// Ajout le champ a la liste, pour le check
			$fields[$field] = array(
					'columnName' => $field 
			);
			
			$entities[$field] = array(
					'name' => $field, 
					'type' => $type, 
					'entity' => $type_entity, 
					'opts' => $type_opts 
			);
		}
		
		$output->writeln(
				array(
						'		---------------------------------', 
						'' 
				));
		
		return $entities;
	}

	/**
     * Nom du helper
     */
	public function getName() {
		return 'ws_entity_dialog_helper';
	}

}
