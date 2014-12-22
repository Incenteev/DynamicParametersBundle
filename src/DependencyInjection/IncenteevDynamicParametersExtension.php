<?php

namespace Incenteev\DynamicParametersBundle\DependencyInjection;

use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class IncenteevDynamicParametersExtension extends ConfigurableExtension
{
    protected function loadInternal(array $config, ContainerBuilder $container)
    {
        $parameters = $config['parameters'];

        if ($config['import_parameter_handler_map']) {
            $composerFile = $container->getParameterBag()->resolveValue($config['composer_file']);

            $container->addResource(new FileResource($composerFile));

            $parameters = array_replace($this->loadHandlerEnvMap($composerFile), $parameters);
        }

        $container->setParameter('incenteev_dynamic_parameters.parameters', $parameters);
    }

    private function loadHandlerEnvMap($composerFile)
    {
        $settings = json_decode(file_get_contents($composerFile), true);

        if (empty($settings['extra']['incenteev-parameters'])) {
            return array();
        }

        $handlerConfigs = $settings['extra']['incenteev-parameters'];

        // Normalize to the multiple-file syntax
        if (array_keys($handlerConfigs) !== range(0, count($handlerConfigs) - 1)) {
            $handlerConfigs = array($handlerConfigs);
        }

        $parameters = array();
        foreach ($handlerConfigs as $config) {
            if (!empty($config['env-map'])) {
                $envMap = array_map(function ($var) {
                    return array('variable' => $var, 'yaml' => true);
                }, $config['env-map']);

                $parameters = array_replace($parameters, $envMap);
            }
        }

        return $parameters;
    }
}
