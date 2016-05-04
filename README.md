# TextmasterBundle

[![Build Status](https://travis-ci.org/worldia/textmaster-bundle.svg?branch=master)](https://travis-ci.org/worldia/textmaster-bundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/worldia/textmaster-bundle/badges/quality-score.png?b=master&s=9eb65ec3ad399ec652d0f8deab4968d1201608cc)](https://scrutinizer-ci.com/g/worldia/textmaster-bundle/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/worldia/textmaster-bundle/badges/coverage.png?b=master&s=27adaf2cde1d45ab9ffabe86d24ada544e51207f)](https://scrutinizer-ci.com/g/worldia/textmaster-bundle/?branch=master)

A Symfony2 bundle integrating [php-textmaster-api](https://github.com/worldia/php-textmaster-api).

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
            new Worldia\TextmasterBundle\WordliaTextmasterBundle(),
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

You can optionally add routing files.  
If you do add project.yml or document.yml you will need [white-october/pagerfanta-bundle](https://github.com/whiteoctober/WhiteOctoberPagerfantaBundle#installation) to display pager on list page.

```yml
// in app/config/routing.yml
worldia_textmaster_project:
    resource: @WorldiaTextmasterBundle/Resources/config/routing/project.yml
  
worldia_textmaster_document:
    resource: @WorldiaTextmasterBundle/Resources/config/routing/document.yml
```

## Usage examples

Create a project with documents:
```php
<?php
// src/AppBundle/Controller/MyController.php
namespace AppBundle\Controller;
  
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Textmaster\Model\DocumentInterface;
use Textmaster\Model\ProjectInterface;
  
class MyController extends Controller
{
    public function createProjectAction()
    {
        // ...
  
        $manager = $this->get('worldia.textmaster.api.manager');
  
        // generate an empty Project
        $project = $manager->getProject();
  
        // set values
        $project
            ->setName('Project name')
            ->setLanguageFrom('en')
            ->setLanguageTo('fr')
            ->setActivity(ProjectInterface::ACTIVITY_TRANSLATION)
            ->setBriefing('My poject briefing')
            ->setCategory('CO21')
            ->setOptions(array('language_level' => 'premium'))
        ;
  
        // save project on textmaster
        $project->save();
  
        // prepare callback url
        $callback = array(
            DocumentInterface::STATUS_IN_REVIEW => array(
                'url' => $this->generateUrl('worldia_textmaster_document_update', ['projectId' => $project->getId()]),
            ),
        );
  
        // add documents
        $document1 = $project->createDocument();
        $document1->setOriginalContent('My first content to translate');
        $document1->setCallback($callback);
        $document1->save();
  
        $document2 = $project->createDocument();
        $document2->setOriginalContent('My other content to translate');
        $document2->setCallback($callback);
        $document2->save();
  
        $document3 = $project->createDocument();
        $document3->setOriginalContent('My last content to translate');
        $document3->setCallback($callback);
        $document3->save();
  
        // start project on textmaster
        $project->launch();
  
        // ...
    }
}
```

## API callback

The bundle provide a controller with a route to catch API callback.  
This will simply call php-textmaster-api Handler to raise en event corresponding to the right object depending on its status.  
You can simply add a listener to any of these events.

```php
<?php
// src/AppBundle/EventListener/DocumentInReviewListener.php
namespace AppBundle\EventListener;
  
use Textmaster\Event\DocumentEvent;
  
class DocumentInReviewListener
{
    public function onTextmasterDocumentInReview(DocumentEvent $event)
    {
        $document = $event->getDocument();
        // do your stuff here
    }
}
```

Service definition in yml:
```yml
# app/config/services.yml
services:
    app.document_in_review_listener:
        class: AppBundle\EventListener\DocumentInReviewListener
        tags:
            - { name: kernel.event_listener, event: textmaster.document.in_review }
```

Service definition in xml:
```xml
<!-- app/config/services.xml -->
<?xml version="1.0" encoding="UTF-8" ?>
<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service id="app.document_in_review_listener"
            class="AppBundle\EventListener\DocumentInReviewListener">

            <tag name="kernel.event_listener" event="textmaster.document.in_review" />
        </service>
    </services>
</container>
```
