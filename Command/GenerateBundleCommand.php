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

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WS\GeneratorBundle\Command\Helper\BundleDialogHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Sensio\Bundle\GeneratorBundle\Manipulator\RoutingManipulator;
use Sensio\Bundle\GeneratorBundle\Manipulator\KernelManipulator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use WS\GeneratorBundle\Generator\BundleGenerator;

class GenerateBundleCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('ws:generate:bundle')
                ->setDescription('Commande de generation de Bundle.')
                ->addOption('namespace', null, InputOption::VALUE_REQUIRED,
                        'Le namespace du Bundle.')
                ->addOption('bundle', null, InputOption::VALUE_REQUIRED,
                        'Nom du bundle')
                ->addOption('dir', null, InputOption::VALUE_REQUIRED,
                        'Le dossier ou generer le bundle.', 'src')
                ->addOption('form', null, InputOption::VALUE_OPTIONAL,
                        'Generer les fichiers de template pour les formulaires.',
                        'yes')
                ->addOption('license', null, InputOption::VALUE_OPTIONAL,
                        'Genere les informations de license.', 'yes')
		        ->addOption('route_prefix', null, InputOption::VALUE_OPTIONAL, 
						'Prefix des routes du bundle.', '/');

        $this
                ->setHelp(
                        <<<EOT
La commande <info>ws:generator:bundle</info> vous permet de generer
facilement la structure d'un Bundle.

	<comment>--namespace</comment> : Obligatoire, namespace du bundle.
		Doit respecter la notation <info>Acme/BlogBundle</info>.
	
	<comment>--dir</comment> : Dossier où sera genere le Bundle.
		Par defaut <info>src</info>.

	<comment>--form</comment> : Generer les fichiers de template pour les formulaires.
		Active par defaut.

	<comment>--license</comment> : Generer les informations de license.
		Active par defaut.

	<comment>--route_prefix</comment> : Prefix des routes du bundle.
		Racine par defaut.
EOT
                );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $generator = new BundleGenerator($this->getContainer());

        $output
                ->writeln(
                        array(
                            '', ' 1/3 : Generation des fichiers du Bundle'
                        ));

        $generator
                ->generate($input->getOption('namespace'),
                        $input->getOption('bundle'), $input->getOption('dir'),
                        $input->getOption('form'),
                        $input->getOption('license'));

        $output
                ->writeln(
                        array(
                                '       Generation OK', '',
                                ' 2/3 : Import des routes'
                        ));

        $routing = new RoutingManipulator(
                $this->getContainer()->getParameter('kernel.root_dir')
                        . '/config/routing.yml');

        try {
            $routing->addResource($input->getOption('bundle'), 'yml', $input->getOption('route_prefix'));
            $output->writeln('       Import OK');
        } catch (\RuntimeException $e) {
            $output->writeln('       Import FAIL, routes deja existantes');
        }

        $output
                ->writeln(
                        array(
                            '', ' 3/3 : Import du bundle'
                        ));

        $kernel = new KernelManipulator($this->getContainer()->get('kernel'));

        try {
            $kernel
                    ->addBundle(
                            $input->getOption('namespace') . '\\'
                                    . $input->getOption('bundle'));
            $output->writeln('       Import OK');
        } catch (\RuntimeException $e) {
            $output->writeln('Bundle deja existant dans le Kernel');
            $output->writeln('       Import FAIL, bundle deja importe');
        }

        $output
                ->writeln(
                        array(
                                '',
                                '    BUNDLE GENERE AVEC SUCCES',
                                ''
                        ));

    }

    protected function initialize(InputInterface $input,
            OutputInterface $output) {

        $this->getHelperSet()->set(new BundleDialogHelper());
    }

    protected function interact(InputInterface $input, OutputInterface $output) {
        $dialog = $this->getHelperSet()->get('ws_bundle_dialog_helper');

        $output
                ->writeln(
                        array(
                                '',
                                '    Bienvenue sur le generateur de bundle pour Symfony2 par WedgeSama',
                                ''
                        ));

        // recupère le namespace
        $namespace = $dialog
                ->askNamespace($output, $input->getOption('namespace'));

        // recupère le dossier du bundle
        $dir = $dialog->askDir($output, $input->getOption('dir'));
        
        // route
        $route = $dialog->askRoutePerfix($output, $input->getOption('route_prefix'));

        // formulaire ?
        $form = $dialog
                ->askConfirmation($output,
                        'Generer les fichiers de template pour les formulaires ? [yes]',
                        true);

        // licence
        $license = $dialog
                ->askConfirmation($output,
                        'Generer les informations de license ? [yes]', true);

        // Confirmation
        if (!$dialog
                ->askConfirmation($output, 'Confirmer la generation ? [yes]',
                        true)) {
            $output->writeln('<error>Generation annulee</error>');

            return 1;
        }
        
        // genère le nom du bundle en fonction du namespace
        $bundle = strtr($namespace,
        		array(
        				'\\Bundle\\' => '', '\\' => ''
        		));

        // sauvegarde les variables
        $input->setOption('namespace', $namespace);
        $input->setOption('dir', $dir);
        $input->setOption('form', $form);
        $input->setOption('bundle', $bundle);
        $input->setOption('license', $license);
        $input->setOption('route_prefix', $route);
    }
}
