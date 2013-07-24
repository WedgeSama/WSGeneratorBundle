<?php
/*
 * This file is part of the WSGeneratorBundle package.
 *
 * (c) Benjamin Georgeault <https://github.com/WedgeSama/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\GeneratorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WS\GeneratorBundle\Command\Helper\EntityDialogHelper;
use WS\GeneratorBundle\Generator\EntityGenerator;

class GenerateEntityCommand extends ContainerAwareCommand {

	protected function configure() {
		$this->setName('ws:generate:entity')
			->setDescription('Commande de generation d\'entity.')
			->addOption('entity', null, InputOption::VALUE_REQUIRED, 
				'Le nom de l\'entity.')
			->addOption('fields', null, InputOption::VALUE_REQUIRED, 
				'Les champs de l\'entity.')
			->addOption('entities', null, InputOption::VALUE_REQUIRED, 
				'Les liens avec les autres entities.')
			->addOption('license', null, InputOption::VALUE_OPTIONAL, 
				'Genere les informations de license.', 'yes');
		/*
		$this->setHelp(
				<<<EOT
La commande <info>ws:generator:entity</info> vous permet de generer
facilement une entity et ses options.
	
	<comment>--entity</comment> : Obligatoire, nom de l'entity.
		Doit respecter la notation <info>AcmeBlogBundle:Entity</info>.
EOT
		);//*/
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$generator = new EntityGenerator($this->getContainer());
		
		$output->writeln(
				array(
						'', 
						' 1/2 : Generation de l\'entity' 
				));
		
		$generator->generate($input->getOption('entity'), 
				$input->getOption('fields'), $input->getOption('entities'), $input->getOption('license'));
		
		$output->writeln(
				array(
						'', 
						' 2/2 : Generation du repository' 
				));
		
		$output->writeln(
				array(
						'', 
						'    ENTITY GENEREE AVEC SUCCES', 
						'' 
				));
	}

	protected function initialize(InputInterface $input, OutputInterface $output) {
		$this->getHelperSet()
			->set(new EntityDialogHelper($this->getContainer()));
	}

	protected function interact(InputInterface $input, OutputInterface $output) {
		$dialog = $this->getHelperSet()
			->get('ws_entity_dialog_helper');
		
		$output->writeln(
				array(
						'', 
						'    Bienvenue sur le generateur d\'entity pour Symfony2 par WedgeSama', 
						'' 
				));
		
		// recupère le namespace
		$entity = $dialog->askEntityNotExist($output, 
				$input->getOption('entity'));
		
		// Champ id
		$fields = array();
		$fields['id'] = $dialog->makeIdField();
		
		// recupere les champs
		$fields = $dialog->askFields($output, $fields);
		
		// recupere les liens avec les entities
		//$entities = $dialog->askEntities($output, $fields);
		
		// licence
		$license = $dialog->askConfirmation($output,
				'Generer les informations de license ? [yes]',
				true);
		
		// Confirmation
		if (! $dialog->askConfirmation($output, 
				'Confirmer la generation ? [yes]', true)) {
			$output->writeln('<error>Generation annulee</error>');
			
			return 1;
		}
		
		// sauvegarde les variables
		$input->setOption('entity', $entity);
		$input->setOption('fields', $fields);
		//$input->setOption('entities', $entities);
		$input->setOption('entities', array());
		$input->setOption('license', $license);
	}

}