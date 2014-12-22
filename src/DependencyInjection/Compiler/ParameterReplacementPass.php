<?php

namespace Incenteev\DynamicParametersBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\ExpressionLanguage\Expression;

class ParameterReplacementPass implements CompilerPassInterface
{
    /**
     * @var \SplObjectStorage
     */
    private $visitedDefinitions;
    private $parameterExpressions = array();

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('incenteev_dynamic_parameters.parameters')) {
            return;
        }

        foreach ($container->getParameter('incenteev_dynamic_parameters.parameters') as $name => $paramConfig) {
            $function = $paramConfig['yaml'] ? 'dynamic_yaml_parameter' : 'dynamic_parameter';
            $this->parameterExpressions[$name] = sprintf('%s(%s, %s)', $function, var_export($name, true), var_export($paramConfig['variable'], true));
        }

        // TODO handle parameters concatenating another parameter with something else

        $this->visitedDefinitions = new \SplObjectStorage();

        foreach ($container->getDefinitions() as $definition) {
            $this->updateDefinitionArguments($definition);
        }

        // Release memory
        $this->visitedDefinitions = null;
        $this->parameterExpressions = array();
    }

    private function updateDefinitionArguments(Definition $definition)
    {
        if ($this->visitedDefinitions->contains($definition)) {
            return;
        }

        $this->visitedDefinitions->attach($definition);

        $definition->setProperties($this->updateArguments($definition->getProperties()));
        $definition->setArguments($this->updateArguments($definition->getArguments()));

        $methodsCalls = array();

        foreach ($definition->getMethodCalls() as $index => $call) {
            $methodsCalls[$index] = array($call[0], $this->updateArguments($call[1]));
        }

        $definition->setMethodCalls($methodsCalls);
    }

    private function updateArguments(array $values)
    {
        foreach ($values as $key => $value) {
            if ($value instanceof Definition) {
                $this->updateDefinitionArguments($value);

                continue;
            }

            if ($value instanceof Parameter && isset($this->parameterExpressions[(string) $value])) {
                $values[$key] = new Expression($this->parameterExpressions[(string) $value]);

                continue;
            }

            if (is_array($value)) {
                $values[$key] = $this->updateArguments($value);

                continue;
            }

            if (!is_string($value)) {
                continue;
            }

            // Parameter-only argument
            if (preg_match('/^%([^%\s]++)%$/', $value, $match)) {
                $parameter = strtolower($match[1]);

                if (isset($this->parameterExpressions[$parameter])) {
                    $values[$key] = new Expression($this->parameterExpressions[$parameter]);
                }

                continue;
            }

            // TODO handle arguments concatenating a parameter with something else
        }

        return $values;
    }
}
