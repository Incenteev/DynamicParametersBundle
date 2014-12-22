<?php

namespace Incenteev\DynamicParametersBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class IncenteevDynamicParametersExtension extends ConfigurableExtension
{
    protected function loadInternal(array $config, ContainerBuilder $container)
    {
        $parameters = $config['parameters'];

        $container->setParameter('incenteev_dynamic_parameters.parameters', $parameters);
    }
}
