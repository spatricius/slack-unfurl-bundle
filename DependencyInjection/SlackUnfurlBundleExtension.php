<?php

namespace Spatricius\SlackUnfurlBundle\DependencyInjection;

use Symfony\Component\Config\Resource\GlobResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class SlackUnfurlBundleExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configDir = dirname(__DIR__).'/config';
        $container->addResource(new GlobResource($configDir, '/*', false));

        $childDefinition = $container->registerForAutoconfiguration(
            '\App\Service\SlackRequestParser\SlackRequestParserInterface'
        );
        $childDefinition->addTag('spatricius.slack.unfurl.request.parser');

        $childDefinition = $container->registerForAutoconfiguration(
            '\App\Service\GitlabTextResolver\SlackResponseRendererInterface'
        );
        $childDefinition->addTag('spatricius.slack.unfurl.response.renderer');

    }

    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        $test = $bundles['zeichen32_git_lab_api'];

//        if (!isset($bundles['AcmeGoodbyeBundle'])) {
//// disable AcmeGoodbyeBundle in bundles
//            $config = ['use_acme_goodbye' => false];
//            foreach ($container->getExtensions() as $name => $extension) {
//                switch ($name) {
//                    case 'acme_something':
//                    case 'acme_other':
//// set use_acme_goodbye to false in the config of
//// acme_something and acme_other
////
//// note that if the user manually configured
//// use_acme_goodbye to true in config/services.yaml
//// then the setting would in the end be true and not false
//                        $container->prependExtensionConfig($name, $config);
//                        break;
//                }
//            }
//        }
//
//// get the configuration of AcmeHelloExtension (it's a list of configuration)
//        $configs = $container->getExtensionConfig($this->getAlias());
//
//// iterate in reverse to preserve the original order after prepending the config
//        foreach (array_reverse($configs) as $config) {
//// check if entity_manager_name is set in the "acme_hello" configuration
//            if (isset($config['entity_manager_name'])) {
//// prepend the acme_something settings with the entity_manager_name
//                $container->prependExtensionConfig('acme_something', [
//                    'entity_manager_name' => $config['entity_manager_name'],
//                ]);
//            }
//        }
    }

}