<?php

namespace Spatricius\SlackUnfurlBundle\DependencyInjection;

use Symfony\Component\Config\Loader\GlobFileLoader;
use Symfony\Component\Config\Resource\GlobResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

class SpatriciusSlackUnfurlExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $childDefinition = $container->registerForAutoconfiguration(
            '\Spatricius\SlackUnfurlBundle\Service\SlackRequestParser\SlackRequestParserInterface'
        );
        $childDefinition->addTag('spatricius.slack_unfurl.request_parser');

        $childDefinition = $container->registerForAutoconfiguration(
            '\Spatricius\SlackUnfurlBundle\Service\GitlabTextResolver\SlackResponseRendererInterface'
        );
        $childDefinition->addTag('spatricius.slack_unfurl.response_renderer');
    }

    public function prepend(ContainerBuilder $container)
    {
        $this->prependZeichen32($container);
        $this->prependMessanger($container);
    }

    protected function prependZeichen32(ContainerBuilder $container)
    {
        $config = Yaml::parseFile(
            dirname(__DIR__).
            DIRECTORY_SEPARATOR.'Resources'.
            DIRECTORY_SEPARATOR.'config'.
            DIRECTORY_SEPARATOR.'prepend'.
            DIRECTORY_SEPARATOR.'zeichen32_git_lab_api.yaml'
        );
        $container->prependExtensionConfig('zeichen32_git_lab_api', $config['zeichen32_git_lab_api']);
    }

    protected function prependMessanger(ContainerBuilder $container)
    {
        $config = Yaml::parseFile(
            dirname(__DIR__).
            DIRECTORY_SEPARATOR.'Resources'.
            DIRECTORY_SEPARATOR.'config'.
            DIRECTORY_SEPARATOR.'prepend'.
            DIRECTORY_SEPARATOR.'messenger.yaml'
        );
        $container->prependExtensionConfig('framework', $config['framework']);
    }

}