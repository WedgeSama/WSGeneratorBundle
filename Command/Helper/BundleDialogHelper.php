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

use Sensio\Bundle\GeneratorBundle\Command\Validators;

class BundleDialogHelper extends DialogHelper {

	/**
     * Demande un namespace
     */
	public function askNamespace($output, $namespace = null) {
		$validator = function ($namespace) {
			return Validators::validateBundleNamespace($namespace);
		};
		
		return $this->askVar($output, 'Veuillez entrer le nom du bundle', 
				$validator, $namespace);
	}

	/**
     * Demande un dossier
     */
	public function askDir($output, $dir = null) {
		$validator = function ($dir) {
			return Validators::validateTargetDir($dir, null, null);
		};
		
		return $this->askVar($output, 'Veuillez entrer le nom du dossier', 
				$validator, $dir);
	}

	/**
     * Demande un dossier
     */
	public function askRoutePerfix($output, $prefix = null) {
		$validator = function ($prefix) {
			if(!preg_match('#^/[/a-zA-Z0-9\-_]*$#', $prefix))
				throw new \InvalidArgumentException('Prefix invalide.');
			
			return $prefix;
		};
		
		return $this->askVar($output, 
				'Veuillez entrer le prefix pour les routes du bundle', 
				$validator, $prefix);
	}

	/**
     * Nom du helper
     */
	public function getName() {
		return 'ws_bundle_dialog_helper';
	}

}
