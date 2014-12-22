<?php

namespace Incenteev\DynamicParametersBundle\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Component\Yaml\Inline;

class FunctionProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions()
    {
        return array(
            new ExpressionFunction('dynamic_parameter', function ($paramName, $envVar) {
                return sprintf('(false === getenv(%s) ? $this->getParameter(%s) : getenv(%s))', $envVar, $paramName, $envVar);
            }, function (array $variables, $paramName, $envVar) {
                $envParam = getenv($envVar);

                if (false !== $envParam) {
                    return $envParam;
                }

                return $variables['container']->getParameter($paramName);
            }),
            new ExpressionFunction('dynamic_yaml_parameter', function ($paramName, $envVar) {
                return sprintf('(false === getenv(%s) ? $this->getParameter(%s) : \Symfony\Component\Yaml\Inline::parse(getenv(%s)))', $envVar, $paramName, $envVar);
            }, function (array $variables, $paramName, $envVar) {
                $envParam = getenv($envVar);

                if (false !== $envParam) {
                    return Inline::parse($envParam);
                }

                return $variables['container']->getParameter($paramName);
            }),
        );
    }

}
