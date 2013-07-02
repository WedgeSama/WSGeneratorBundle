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

use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Symfony\Component\Console\Helper\HelperSet;

class BundleDialogHelper extends DialogHelper {

	/**
     * Demande une information à l'utilisateur et la valide
     */
	protected function askVar($output, $question, $validator, $default, 
		$var = null) {
		if ($default === null)
			$default = $var;
		
		try {
			$var = $var ? $validator($var) : null;
		} catch (\Exception $error) {
			$output->writeln(
					$this->getHelperSet()
						->get('formatter')
						->formatBlock($error->getMessage(), 'error'));
			$var = null;
		}
		
		if ($default !== null)
			$question .= ' [<comment>' . $default . '</comment>]';
		
		$question .= ' : ';
		
		$var = $this->askAndValidate($output, $question, 
				function ($var) use($validator) {
					return $validator($var);
				}, false, $default);
		
		return $var;
	}

	/**
     * Demande un namespace
     */
	public function askNamespace($output, $namespace = null) {
		$validator = function ($namespace) {
			return Validators::validateBundleNamespace($namespace);
		};
		
		return $this->askVar($output, 'Veuillez entrer le nom du bundle', 
				$validator, null, $namespace);
	}

	/**
     * Demande un dossier
     */
	public function askDir($output, $dir = null) {
		$validator = function ($dir) {
			return Validators::validateTargetDir($dir, null, null);
		};
		
		return $this->askVar($output, 'Veuillez entrer le nom du dossier', 
				$validator, null, $dir);
	}

	/**
     * Demande un dossier
     */
	public function askRoutePerfix($output, $prefix = null) {
		$validator = function ($prefix) {
			if(!preg_match('#/[/a-zA-Z0-9\-_]*#', $prefix))
				throw new \InvalidArgumentException('Prefix invalide.');
			
			return $prefix;
		};
		
		return $this->askVar($output, 
				'Veuillez entrer le prefix pour les routes du bundle', 
				$validator, null, $prefix);
	}

	/**
     * Nom du helper
     */
	public function getName() {
		return 'ws_bundle_dialog_helper';
	}

}
