<?php

namespace Incenteev\DynamicParametersBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Inline;

class ParameterRetriever
{
    private $container;
    private $parameterMap;

    public function __construct(ContainerInterface $container, array $parameterMap)
    {
        $this->container = $container;
        $this->parameterMap = $parameterMap;
    }

    /**
     * @param string $name
     *
     * @return array|string|bool|int|float|null
     */
    public function getParameter($name)
    {
        if (!isset($this->parameterMap[$name])) {
            return $this->container->getParameter($name);
        }

        $varName = $this->parameterMap[$name]['variable'];

        $var = getenv($varName);

        if (false === $var) {
            return $this->container->getParameter($name);
        }

        if ($this->parameterMap[$name]['yaml']) {
            return Inline::parse($var);
        }

        return $var;
    }
}
