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
use Symfony\Component\DependencyInjection\Container;

/**
 * Generates a bundle.
 *
 * @author Benjamin Georgeault <https://github.com/WedgeSama/>
 */
class BundleGenerator extends Generator
{
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function generate($namespace, $bundle, $dir, $format, $structure)
    {
        $dir .= '/'.strtr($namespace, '\\', '/');
        if (file_exists($dir)) {
            if (!is_dir($dir)) {
                throw new \RuntimeException(sprintf('Unable to generate the bundle as the target directory "%s" exists but is a file.', realpath($dir)));
            }
            $files = scandir($dir);
            if ($files != array('.', '..')) {
                throw new \RuntimeException(sprintf('Unable to generate the bundle as the target directory "%s" is not empty.', realpath($dir)));
            }
            if (!is_writable($dir)) {
                throw new \RuntimeException(sprintf('Unable to generate the bundle as the target directory "%s" is not writable.', realpath($dir)));
            }
        }

        $basename = substr($bundle, 0, -6);
        $parameters = array(
            'namespace' => $namespace,
            'bundle'    => $bundle,
            'format'    => $format,
            'bundle_basename' => $basename,
            'extension_alias' => Container::underscore($basename),
            'structure' => $structure
        );

        $this->renderFile('bundle/Bundle.php.twig', $dir.'/'.$bundle.'.php', $parameters);
        $this->renderFile('bundle/Events.php.twig', $dir.'/'.$basename.'Events.php', $parameters);
        $this->renderFile('bundle/Extension.php.twig', $dir.'/DependencyInjection/'.$basename.'Extension.php', $parameters);
        $this->renderFile('bundle/Configuration.php.twig', $dir.'/DependencyInjection/Configuration.php', $parameters);

        $this->renderFile('bundle/services.yml.twig', $dir.'/Resources/config/services.yml', $parameters);
        $this->renderFile('bundle/routing.yml.twig', $dir.'/Resources/config/routing.yml', $parameters);
        $this->renderFile('bundle/services.yml.twig', $dir.'/Resources/config/events.yml', $parameters);
        $this->filesystem->mkdir($dir.'/Resources/config/routing');

        $this->filesystem->mkdir($dir.'/Resources/translations');
        $this->filesystem->touch($dir.'/Resources/translations/' . $bundle . '.fr.yml');
        $this->filesystem->touch($dir.'/Resources/translations/' . $bundle . '.en.yml');
        $this->filesystem->mkdir($dir.'/Resources/doc');
        $this->filesystem->touch($dir.'/Resources/doc/index.md');
        $this->filesystem->mkdir($dir.'/Resources/views');

        if ($structure) {
            $this->renderFile('bundle/FormPass.php.twig', $dir.'/DependencyInjection/Compiler/FormPass.php', $parameters);
            $this->filesystem->mkdir($dir.'/Resources/views/Form');
            $this->filesystem->touch($dir.'/Resources/views/Form/div_layout.html.twig');
            $this->filesystem->touch($dir.'/Resources/views/Form/js_layout.html.twig');
            $this->renderFile('bundle/services.yml.twig', $dir.'/Resources/config/forms.yml', $parameters);
            $this->filesystem->mkdir($dir.'/Form');
        }
    }
}
