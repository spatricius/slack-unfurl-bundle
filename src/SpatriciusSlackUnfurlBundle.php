<?php

namespace Spatricius\SlackUnfurlBundle;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SpatriciusSlackUnfurlBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(
                __DIR__.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'config'
            )
        );
        $loader->load('services.yaml');
    }
}