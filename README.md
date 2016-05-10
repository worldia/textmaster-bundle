# TextmasterBundle

[![Build Status](https://travis-ci.org/worldia/textmaster-bundle.svg?branch=master)](https://travis-ci.org/worldia/textmaster-bundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/worldia/textmaster-bundle/badges/quality-score.png?b=master&s=9eb65ec3ad399ec652d0f8deab4968d1201608cc)](https://scrutinizer-ci.com/g/worldia/textmaster-bundle/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/worldia/textmaster-bundle/badges/coverage.png?b=master&s=27adaf2cde1d45ab9ffabe86d24ada544e51207f)](https://scrutinizer-ci.com/g/worldia/textmaster-bundle/?branch=master)

A Symfony2 bundle integrating [textmaster-api](https://github.com/worldia/textmaster-api).

## Installation

### Step 1: Download TextmasterBundle using composer

Require the bundle with composer:

```bash
$ composer require worldia/textmaster-bundle
```

### Step 2: Enable the bundle

Enable the bundle in the kernel:

```php
<?php
    // app/AppKernel.php
  
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Worldia\Bundle\TextmasterBundle\WorldiaTextmasterBundle(),
            // ...
        );
    }
```

### Step 3: Configure the bundle

```yml
// in app/config/config.yml
  
worldia_textmaster:
    credentials:
        api_key: your_api_key
        api_secret: your_api_secret
```

### Step 4: Add route

The translation manager is using the callback routes.

```yml
// in app/config/routing.yml
  
worldia_textmaster_callback:
    resource: @WorldiaTextmasterBundle/Resources/config/routing/callback.yml
```

## Additional installation

### Configuration

By default, the translator provider used is the ArrayBasedMappingProvider. You can easily configure it as followed:

```yml
// in app/config/config.yml
  
worldia_textmaster:
    mapping_properties:
        AppBundle\Entity\FirstEntity: ['property1', 'property2', 'property3', ...]
        AppBundle\Entity\SecondEntity: ['propertyA', 'propertyB', 'propertyC', ...]
        ...
```

There is a template provided for each route. You can override it easily with configuration:

```yml
// in app/config/config.yml
  
worldia_textmaster:
    templates:
        project:
            show: 'MyTemplate:Project:show.html.twig'
            list: 'MyTemplate:Project:list.html.twig'
```

### Route

You can optionally add object routing files.  
If you do you will need [white-october/pagerfanta-bundle](https://github.com/whiteoctober/WhiteOctoberPagerfantaBundle#installation) to display pager on list page.

```yml
// in app/config/routing.yml
worldia_textmaster_project:
    resource: @WorldiaTextmasterBundle/Resources/config/routing/project.yml
  
worldia_textmaster_document:
    resource: @WorldiaTextmasterBundle/Resources/config/routing/document.yml
  
worldia_textmaster_job:
    resource: @WorldiaTextmasterBundle/Resources/config/routing/job.yml
```

## Usage example

Create a project with documents:
```php
<?php
// src/AppBundle/MyService/MyService.php
  
namespace AppBundle\MyService;
  
use Worldia\Bundle\TextmasterBundle\Translation\TranslationManager;
  
class MyService
{
    /**
     * @var TranslationManager
     */
    protected $translationManager;
  
    public function createProject()
    {
        // retrieve all entities to translate in array $translatable.
  
        $project = $this->translationManager->create(
            $translatable,
            'Project name',
            'en',
            'fr',
            'CO21',
            'My poject briefing',
            array('language_level' => 'premium')
        );
  
        // the project is sent to TextMaster and launched.
    }
}
```

## API callback

The bundle provide a controller with a route to catch API callback.  
This will call textmaster-api Handler to raise en event corresponding to the right object depending on its status.  
Then a listener (DocumentListener or ProjectListener) will catch this event and act accordingly.  
For example, an event for 'textmaster.document.in_review' will get the job related to the document and finish it.
