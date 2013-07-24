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

use Sensio\Bundle\GeneratorBundle\Generator\Generator as BaseGenerator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Generator extends BaseGenerator {

    protected $filesystem;

    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->filesystem = $this->container->get('filesystem');
        
        $skeletonDirs = array();
        if (is_dir(
                $dir = $this->container->get('kernel')
                    ->getRootdir() . '/Resources/WSGeneratorBundle/skeleton')) {
            $skeletonDirs[] = $dir;
        }
        
        $skeletonDirs[] = __DIR__ . '/../Resources/skeleton';
        $skeletonDirs[] = __DIR__ . '/../Resources';
        
        $this->setSkeletonDirs($skeletonDirs);
    }

    protected function appendToFile($template, $target, $parameters) {
        if (! is_dir(dirname($target))) {
            mkdir(dirname($target), 0777, true);
        }
        
        return file_put_contents($target, $this->render($template, $parameters), 
                FILE_APPEND);
    }

    protected function appendCodeToFile($target, $code) {
        if (! is_dir(dirname($target))) {
            mkdir(dirname($target), 0777, true);
        }
        
        return file_put_contents($target, $code, FILE_APPEND);
    }

}
