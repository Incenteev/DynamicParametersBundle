<?php

namespace Incenteev\DynamicParametersBundle;

use Incenteev\DynamicParametersBundle\DependencyInjection\Compiler\ParameterReplacementPass;
use Incenteev\DynamicParametersBundle\ExpressionLanguage\FunctionProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class IncenteevDynamicParametersBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ParameterReplacementPass());

        $container->addExpressionLanguageProvider(new FunctionProvider());
    }
}
