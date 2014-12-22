<?php

namespace Incenteev\DynamicParametersBundle\Tests\DependencyInjection;

use Incenteev\DynamicParametersBundle\DependencyInjection\IncenteevDynamicParametersExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class IncenteevDynamicParametersExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadEmptyConfig()
    {
        $container = new ContainerBuilder();
        $extension = new IncenteevDynamicParametersExtension();

        $extension->load(array(), $container);

        $this->assertTrue($container->hasParameter('incenteev_dynamic_parameters.parameters'));
        $this->assertSame(array(), $container->getParameter('incenteev_dynamic_parameters.parameters'));
    }

    public function testLoadParameters()
    {
        $container = new ContainerBuilder();
        $extension = new IncenteevDynamicParametersExtension();

        $config = array(
            'parameters' => array(
                'foo' => 'FOO',
                'bar' => 'SYMFONY__BAR',
            ),
        );

        $extension->load(array($config), $container);

        $expected = array(
            'foo' => array('variable' => 'FOO', 'yaml' => false),
            'bar' => array('variable' => 'SYMFONY__BAR', 'yaml' => false),
        );

        $this->assertTrue($container->hasParameter('incenteev_dynamic_parameters.parameters'));
        $this->assertSame($expected, $container->getParameter('incenteev_dynamic_parameters.parameters'));
    }
}
