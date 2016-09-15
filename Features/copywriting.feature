Feature: Translation management
    As a translation manager
    I should be able to create, finish and complete a copywriting job.

  Scenario: Generate EN copywriting for products
    Given I have "1" products
    And I have the following translations for product "1":
        | title       | description                 | locale |
        | Hello Paris | Paris is the city of lights | en     |
     Then I should have "0" translatables with job for class "Worldia\Bundle\ProductTestBundle\Entity\Product"
     When I generate a translation batch with the following parameters:
        | finder  | filter | name      | languageFrom | category | briefing | options                       | activity    |
        | product | {}     | PROJECT-1 | en           | C054     | Nothing  | {"language_level": "premium"} | copywriting |
     Then I should have "1" translatables with job for class "Worldia\Bundle\ProductTestBundle\Entity\Product"
     When I receive the request '{ "id": "PROJECT-1", "name": "PROJECT-1", "status": "in_progress"}'
     Then I should have the following jobs:
        | id | translatable | project   | document         | status  |
        | 1  | 1            | PROJECT-1 | en-copywriting-1 | started |
     When I receive the request '{ "id": "en-copywriting-1", "title": "en-copywriting-1", "status": "in_review", "project_id": "PROJECT-1", "author_work": { "title": "New title", "description": "New description"}}'
     Then the job "1" should have status "finished"
     When I translate job "1"
     Then the job "1" should have status "validated"
      And I should have the following translations for product "1":
        | title     | description     | locale |
        | New title | New description | en     |
