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

class BundleGenerator extends Generator {

	public function generate($namespace, $bundle, $dir, $form, $license) {
		// si système de fichier absolu
		if (! $this->filesystem->isAbsolutePath($dir))
			$dir = getcwd() . '/' . $dir;
			
			// check le dossier
		$dir .= '/' . strtr($namespace, '\\', '/');
		if (file_exists($dir)) {
			if (! is_dir($dir)) {
				throw new \RuntimeException(
						'Impossible de generer le dossier du bundle. "' . realpath(
								$dir) . '" est un fichier.');
			}
			$files = scandir($dir);
			if ($files != array (
					'.','..' 
			)) {
				throw new \RuntimeException(
						'Impossible de generer le dossier du bundle. "' . realpath(
								$dir) . '" n\'est pas vide.');
			}
			if (! is_writable($dir)) {
				throw new \RuntimeException(
						'Impossible de generer le dossier du bundle. "' . realpath(
								$dir) . '" n\'est pas accessible en ecriture.');
			}
		}
		
		$form = $form == 'yes' ? true : false;
		
		$basename = substr($bundle, 0, - 6);
		$parameters = array (
				'namespace' => $namespace,'bundle' => $bundle,
				'bundle_basename' => $basename,'form' => $form,
				'license' => $license,
				'extension_alias' => Container::underscore($basename) 
		);
		
		$this->renderFile('Bundle/Bundle.php.twig', 
				$dir . '/' . $bundle . '.php', $parameters);
		$this->renderFile('Bundle/Extension.php.twig', 
				$dir . '/DependencyInjection/' . $basename . 'Extension.php', 
				$parameters);
		$this->renderFile('Bundle/Configuration.php.twig', 
				$dir . '/DependencyInjection/Configuration.php', $parameters);
		$this->renderFile('bundle/services.yml.twig', 
				$dir . '/Resources/config/services.yml', $parameters);
		if ($form)
			$this->renderFile('bundle/services.yml.twig', 
					$dir . '/Resources/config/form.yml', $parameters);
		
		$this->filesystem->mkdir($dir . '/Resources/config/routing');
		
		$this->filesystem->mkdir($dir . '/Resources/doc');
		$this->filesystem->touch($dir . '/Resources/doc/index.md');
		$this->filesystem->mkdir($dir . '/Resources/translations');
		$this->filesystem->mkdir($dir . '/Resources/views');
		
		if ($license) {
			$this->renderFile('License/LICENSE.twig', 
					$dir . '/Resources/meta/LICENSE', $parameters);
			$this->renderFile('License/license.yml.twig', 
					$dir . '/Resources/config/routing.yml', $parameters);
			$this->renderFile('License/license.yml.twig', 
					$dir . '/Resources/translations/' . $bundle . '.fr.yml', 
					$parameters);
			$this->renderFile('License/license.yml.twig', 
					$dir . '/Resources/translations/' . $bundle . '.en.yml', 
					$parameters);
		} else {
			$this->filesystem->touch($dir . '/Resources/config/routing.yml');
			$this->filesystem->touch(
					$dir . '/Resources/translations/' . $bundle . '.fr.yml');
			$this->filesystem->touch(
					$dir . '/Resources/translations/' . $bundle . '.en.yml');
		}
		
		if ($form) {
			$this->renderFile('Bundle/FormPass.php.twig', 
					$dir . '/DependencyInjection/Compiler/FormPass.php', 
					$parameters);
			
			$this->filesystem->mkdir($dir . '/Resources/views/Form');
			if ($license) {
				$this->renderFile('License/license.twig.twig', 
						$dir . '/Resources/views/Form/div_layout.html.twig', 
						$parameters);
				$this->renderFile('License/license.twig.twig', 
						$dir . '/Resources/views/Form/js_layout.html.twig', 
						$parameters);
			} else {
				$this->filesystem->touch(
						$dir . '/Resources/views/Form/div_layout.html.twig');
				$this->filesystem->touch(
						$dir . '/Resources/views/Form/js_layout.html.twig');
			}
		}
	}

}
