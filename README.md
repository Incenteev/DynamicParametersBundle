# DynamicParametersBundle

This bundle provides you a few CLI commands to check your translations.

[![Build Status](https://travis-ci.org/Incenteev/DynamicParametersBundle.svg?branch=master)](https://travis-ci.org/Incenteev/DynamicParametersBundle)
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

If you are using the [env-map feature of the Incenteev ParameterHandler](https://github.com/Incenteev/ParameterHandler/#using-environment-variables-to-set-the-parameters),
you can import the whole env-map very easily:


```yaml
# app/config/config.yml
incenteev_dynamic_parameters:
    import_parameter_handler_map: true
    parameters:
        something_else: NOT_IN_THE_COMPOSER_JSON
```
