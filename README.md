# DynamicParametersBundle

This bundle provides a way to read parameters from environment variables at runtime.
The value defined in the container parameter is used as fallback when the environment variable is not available.

[![Build Status](https://travis-ci.org/Incenteev/DynamicParametersBundle.svg?branch=master)](https://travis-ci.org/Incenteev/DynamicParametersBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Incenteev/DynamicParametersBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Incenteev/DynamicParametersBundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Incenteev/DynamicParametersBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Incenteev/DynamicParametersBundle/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/2e97bd6b-7ae8-41d1-b0a7-a3106f21c50d/mini.png)](https://insight.sensiolabs.com/projects/2e97bd6b-7ae8-41d1-b0a7-a3106f21c50d)
[![Latest Stable Version](https://poser.pugx.org/incenteev/dynamic-parameters-bundle/v/stable.svg)](https://packagist.org/packages/incenteev/dynamic-parameters-bundle)
[![Latest Unstable Version](https://poser.pugx.org/incenteev/dynamic-parameters-bundle/v/unstable.svg)](https://packagist.org/packages/incenteev/dynamic-parameters-bundle)
[![License](https://poser.pugx.org/incenteev/dynamic-parameters-bundle/license.svg)](https://packagist.org/packages/incenteev/dynamic-parameters-bundle)

## Installation

Installation is a quick (I promise!) 2 step process:

1. Download IncenteevDynamicParametersBundle
2. Enable the bundle

### Step 1: Install IncenteevDynamicParametersBundle with composer

Run the following composer require command:

```bash
$ composer require incenteev/dynamic-parameters-bundle
```

### Step 2: Enable the bundle

Finally, enable the bundle in the kernel:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Incenteev\DynamicParametersBundle\IncenteevDynamicParametersBundle(),
    );
}
```

## Usage

Define the map of parameter names with the environment variable used to configure them.

```yaml
# app/config/config.yml
incenteev_dynamic_parameters:
    parameters:
        database_host: DATABASE_HOST
        "database.name": DATABASE_NAME
```

Environment variables are always strings. To be able to set parameters of other types, the bundle supports parsing the environment variable as inline Yaml:

```yaml
# app/config/config.yml
incenteev_dynamic_parameters:
    parameters:
        use_ssl:
            variable: HAS_SSL
            yaml: true
```

### ParameterHandler integration

If you are using the [env-map feature of the Incenteev ParameterHandler](https://github.com/Incenteev/ParameterHandler/#using-environment-variables-to-set-the-parameters),
you can import the whole env-map very easily:

```yaml
# app/config/config.yml
incenteev_dynamic_parameters:
    import_parameter_handler_map: true
    parameters:
        something_else: NOT_IN_THE_COMPOSER_JSON
```

The ParameterHandler parses the environment variables as inline Yaml, so the Yaml parsing is automatically enabled for these variables when importing the map.

> Note: Any parameter defined explicitly will win over the imported map.

By default, the bundle will look for the composer.json file in ``%kernel.root_dir%/../composer.json``. If you use a non-standard location for your kernel, you can change the path to your composer.json file to read the env-map:

```yaml
# app/config/config.yml
incenteev_dynamic_parameters:
    import_parameter_handler_map: true
    composer_file: path/to/composer.json
```

### Retrieving parameters at runtime

The bundle taks care of service arguments, but changing the behavior of ``$container->getParameter()`` is not possible. However, it exposes a service to get parameters taking the environment variables into account.

```php
$this->get('incenteev_dynamic_parameters.retriever')->get('use_ssl');
```

## Limitations

- Getting a parameter from the container directly at runtime will not use the environment variable
- Parameters or arguments built by concatenating other parameters together will not rely on the environment variables (yet)
