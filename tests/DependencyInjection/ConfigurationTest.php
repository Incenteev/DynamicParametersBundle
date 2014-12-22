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
                'import_parameter_handler_map' => true,
                'parameters' => array(
                    'foo' => 'FOO',
                    'bar' => array('variable' => 'SYMFONY__BAR', 'yaml' => true),
                ),
            ),
            array(
                'parameters' => array(
                    'baz' => 'SYMFONY__BAZ',
                    'foo' => array('variable'=> 'OVERWRITE_FOO'),
                ),
            ),
        );

        $expected = array(
            'import_parameter_handler_map' => true,
            'composer_file' => '%kernel.root_dir%/../composer.json',
            'parameters' => array(
                'foo' => array('variable' => 'OVERWRITE_FOO', 'yaml' => false),
                'bar' => array('variable' => 'SYMFONY__BAR', 'yaml' => true),
                'baz' => array('variable' => 'SYMFONY__BAZ', 'yaml' => false),
            ),
        );

        $this->assertEquals($expected, $this->processConfiguration($configs));
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testInvalidComposerFile()
    {
        $this->processConfiguration(array(array('composer_file' => null)));
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testInvalidVariableName()
    {
        $this->processConfiguration(array(array('parameters' => array('foo' => ''))));
    }

    private function processConfiguration(array $configs)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        return $processor->processConfiguration($configuration, $configs);
    }
}
