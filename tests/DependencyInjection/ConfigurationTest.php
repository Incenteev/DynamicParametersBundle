<?php

namespace Incenteev\DynamicParametersBundle\Tests\DependencyInjection;

use Incenteev\DynamicParametersBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testValidConfiguration()
    {
        $configs = array(
            array(
                'parameters' => array(
                    'foo' => 'FOO',
                    'bar' => 'SYMFONY__BAR',
                ),
            ),
            array(
                'parameters' => array(
                    'baz' => 'SYMFONY__BAZ',
                    'foo' => 'OVERWRITE_FOO',
                ),
            ),
        );

        $expected = array(
            'parameters' => array(
                'foo' => 'OVERWRITE_FOO',
                'bar' => 'SYMFONY__BAR',
                'baz' => 'SYMFONY__BAZ',
            ),
        );

        $this->assertEquals($expected, $this->processConfiguration($configs));
    }

    private function processConfiguration(array $configs)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        return $processor->processConfiguration($configuration, $configs);
    }
}
