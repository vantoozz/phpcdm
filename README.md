# PHP Code Density Meter (PHPCDM)

`phpcdm` is a Code Density Meter for PHP


[![Build Status](https://travis-ci.org/vantoozz/phpcdm.svg?branch=master)](https://travis-ci.org/vantoozz/phpcdm)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/4b3e0816e98d486e9f0eff445a6310c6)](https://www.codacy.com/app/vantoozz/phpcdm?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=vantoozz/phpcdm&amp;utm_campaign=Badge_Grade)
[![Packagist](https://img.shields.io/packagist/v/vantoozz/phpcdm.svg)](https://packagist.org/packages/vantoozz/phpcdm)
[![License](https://poser.pugx.org/vantoozz/phpcdm/license)](https://packagist.org/packages/vantoozz/phpcdm)

## What is  code density?

Look at this code:
```php
<?php

final class SomethingGreat
{
    public function doSomethingGreat($entity, $property, $type, $refProperties, $update, $delete)
    {
        $time = $this->startAction(" $type (" . implode(',', (array) $type) . ')');
        $this->createAction()->addExtraKey($entity, $entity, $property, $refProperties, $delete, $update)->execute();
        $this->finishAction($time);
        $this->createAction()->updateProperty($entity, $property, $type)->execute();
        if ($type instanceof InvalidArgumentException && $type->getMessage() !== null) {
            $this->createAction()->addDescriptionOnProperty($entity, $property, $type->description)->execute();
        }
        $this->createAction()->renameEntity($entity, $property)->execute();
        $this->finishAction($time);
        $time = $this->startAction(" $type (" . implode(',', (array) $type) . ')');
        $this->createAction()->addExtraKey($entity, $entity, $property, $refProperties, $delete, $update)->execute();
        if ($type instanceof InvalidArgumentException && $type->getMessage() !== $delete) {
            $this->finishAction($time);
            $this->createAction()->addDescriptionOnProperty($entity, $property, $type->description)->execute();
            $this->createAction()->renameEntity($entity, $property)->execute();
        }
        $this->createAction()->updateProperty($entity, $property, $type)->execute();
        $this->finishAction($time);
    }
}
```

It looks like a wall of characters. It actually _is_ a wall of characters. No one can easily understand what the author of this code meant and definitely, no one will be happy trying to read it. Moreover, often such walls of characters are the result of bad application design.

_Code density_ is a measure of how many characters are displayed on a single page.

PHP Code Density Meter aims to help a developer to prevent code density issues and eventually keep an application in a good shape.

The example code has density of 0.381 which is far above the default threshold (0.2)


## Installation

You can add this tool as a local, per-project, development-time dependency to your project using [Composer](https://getcomposer.org/):

```bash
composer require --dev vantoozz/phpcdm
```

You can then invoke it using the `vendor/bin/phpcdm` executable.
