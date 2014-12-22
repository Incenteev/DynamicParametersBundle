<?php

namespace Incenteev\DynamicParametersBundle\Tests\DependencyInjection\Compiler;

use Incenteev\DynamicParametersBundle\DependencyInjection\Compiler\ParameterReplacementPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Parameter;

class ParameterReplacementPassTest extends \PHPUnit_Framework_TestCase
{
    public function testReplaceParameters()
    {
        $container = new ContainerBuilder();

        $container->setParameter('foo', 'bar');
        $container->setParameter('bar', 'baz');
        $container->setParameter('incenteev_dynamic_parameters.parameters', array('foo' => array('variable' => 'SYMFONY_FOO', 'yaml' => false)));

        $container->register('srv.foo', 'stClass')
            ->setProperty('test', '%foo%')
            ->setProperty('test2', '%bar%');

        $container->register('srv.bar', 'ArrayObject')
            ->addMethodCall('append', array('%foo%'))
            ->addMethodCall('append', array(new Parameter('foo')));

        $pass = new ParameterReplacementPass();
        $pass->process($container);

        $def = $container->getDefinition('srv.foo');
        $props = $def->getProperties();

        $this->assertInstanceOf('Symfony\Component\ExpressionLanguage\Expression', $props['test'], 'Parameters are replaced in properties');
        $this->assertEquals('dynamic_parameter(\'foo\', \'SYMFONY_FOO\')', (string) $props['test']);
        $this->assertEquals('%bar%', $props['test2'], 'Other parameters are not replaced');

        $def = $container->getDefinition('srv.bar');
        $calls = $def->getMethodCalls();

        $this->assertInstanceOf('Symfony\Component\ExpressionLanguage\Expression', $calls[0][1][0], 'Parameters are replaced in arguments');
        $this->assertEquals('dynamic_parameter(\'foo\', \'SYMFONY_FOO\')', (string) $calls[0][1][0]);

        $this->assertInstanceOf('Symfony\Component\ExpressionLanguage\Expression', $calls[1][1][0], 'Parameter instances are replaced in arguments');
        $this->assertEquals('dynamic_parameter(\'foo\', \'SYMFONY_FOO\')', (string) $calls[1][1][0]);
    }

    public function testReplaceYamlParameter()
    {
        $container = new ContainerBuilder();

        $container->setParameter('foo', 'bar');
        $container->setParameter('incenteev_dynamic_parameters.parameters', array('foo' => array('variable' => 'SYMFONY_FOO', 'yaml' => true)));

        $container->register('srv.foo', 'stClass')
            ->setProperty('test', '%foo%');

        $pass = new ParameterReplacementPass();
        $pass->process($container);

        $def = $container->getDefinition('srv.foo');
        $props = $def->getProperties();

        $this->assertInstanceOf('Symfony\Component\ExpressionLanguage\Expression', $props['test'], 'Parameters are replaced in properties');
        $this->assertEquals('dynamic_yaml_parameter(\'foo\', \'SYMFONY_FOO\')', (string) $props['test']);
    }
}
