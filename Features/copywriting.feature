Feature: Copywriting management
    As a translation manager
    I should be able to create, finish and complete a copywriting job.

  Scenario: Generate EN copywriting for products
    Given I have "2" products
      And I have the following translations for product "1":
        | title       | description                 | locale |
        | Hello Paris | Paris is the city of lights | en     |
      And I have the following translations for product "2":
        | title     | description          | locale |
        | Hello NYC | NYC is the big apple | en     |
     Then I should have "0" translatables with job for class "Worldia\Bundle\ProductTestBundle\Entity\Product" and locale en
     When I generate a translation batch with the following parameters:
        | finder  | filter | name      | languageFrom | category | briefing | options                       | activity    | useMyTextmasters |
        | product | {}     | PROJECT-1 | en           | C054     | Nothing  | {"language_level": "premium"} | copywriting | false            |
     Then I should have "2" translatables with job for class "Worldia\Bundle\ProductTestBundle\Entity\Product" and locale en
     When I receive the request '{ "id": "PROJECT-1", "name": "PROJECT-1", "status": "in_progress"}'
     Then I should have the following jobs:
        | id | translatable | project   | document         | status  | locale |
        | 1  | 1            | PROJECT-1 | en-copywriting-1 | started | en     |
        | 2  | 2            | PROJECT-1 | en-copywriting-2 | started | en     |
     When I receive the request '{ "id": "en-copywriting-1", "title": "en-copywriting-1", "status": "in_review", "project_id": "PROJECT-1", "author_work": { "title": "New title", "description": "New description"}}'
     Then the job "1" should have status "finished"
     When I translate job "1"
     Then the job "1" should have status "validated"
      And I should have the following translations for product "1":
        | title     | description     | locale |
        | New title | New description | en     |
     When I receive the request '{ "id": "en-copywriting-2", "title": "en-copywriting-2", "status": "completed", "project_id": "PROJECT-1", "author_work": { "title": "Super title", "description": "Super description"}, "custom_data": {"adapter": {"class": "Worldia\\Bundle\\ProductTestBundle\\Entity\\Product", "id": "2"}}}'
     Then the job "2" should have status "validated"
      And I should have the following translations for product "2":
        | title       | description       | locale |
        | Super title | Super description | en     |
     When I generate a translation batch with the following parameters:
        | finder  | filter | name      | languageFrom | category | briefing | options                       | activity    | useMyTextmasters |
        | product | {}     | PROJECT-1 | en           | C054     | Nothing  | {"language_level": "premium"} | copywriting | false            |
     Then I should have "2" translatables with job for class "Worldia\Bundle\ProductTestBundle\Entity\Product" and locale en
     Then I should have the following jobs:
        | id | translatable | project   | document         | status    | locale |
        | 1  | 1            | PROJECT-1 | en-copywriting-1 | validated | en     |
        | 2  | 2            | PROJECT-1 | en-copywriting-2 | validated | en     |
     When I generate a translation batch with the following parameters:
        | finder  | filter | name      | languageFrom | category | briefing | options                       | activity    | useMyTextmasters |
        | product | {}     | PROJECT-1 | de           | C054     | Nothing  | {"language_level": "premium"} | copywriting | false            |
     Then I should have "2" translatables with job for class "Worldia\Bundle\ProductTestBundle\Entity\Product" and locale de
