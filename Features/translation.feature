Feature: Translation management
    As a translation manager
    I should be able to create, finish and translate a job.

  Scenario: Generate FR translations for products
    Given I have "2" products
      And I have the following translations for product "1":
        | title       | description                 | locale |
        | Hello Paris | Paris is the city of lights | en     |
      And I have the following translations for product "2":
        | title     | description          | locale |
        | Hello NYC | NYC is the big apple | en     |
     Then I should have "0" translatables with job for class "Worldia\Bundle\ProductTestBundle\Entity\Product"
     When I generate a translation batch with the following parameters:
        | finder  | filter | name      | languageFrom | languageTo | category | briefing | options                       | activity    | textmasters                  |
        | product | {}     | PROJECT-1 | en           | fr         | C054     | Nothing  | {"language_level": "premium"} | translation | {"55c3763e656462000b000027"} |
     Then I should have "2" translatables with job for class "Worldia\Bundle\ProductTestBundle\Entity\Product"
     When I receive the request '{ "id": "PROJECT-1", "name": "PROJECT-1", "status": "in_progress"}'
     Then I should have the following jobs:
        | id | translatable | project   | document | status  |
        | 1  | 1            | PROJECT-1 | en-fr-1  | started |
        | 2  | 2            | PROJECT-1 | en-fr-2  | started |
     When I receive the request '{ "id": "en-fr-1", "title": "en-fr-1", "status": "in_review", "project_id": "PROJECT-1", "original_content": { "title": { "original_phrase": "Hello Paris", "completed_phrase": "Bonjour Paris"}, "description": { "original_phrase": "Paris is the city of lights", "completed_phrase": "Paris est la ville lumière"}}}'
     Then the job "1" should have status "finished"
     When I translate job "1"
     Then the job "1" should have status "validated"
      And I should have the following translations for product "1":
        | title         | description                 | locale |
        | Hello Paris   | Paris is the city of lights | en     |
        | Bonjour Paris | Paris est la ville lumière  | fr     |
     When I receive the request '{ "id": "en-fr-2", "title": "en-fr-2", "status": "completed", "project_id": "PROJECT-1", "original_content": { "title": { "original_phrase": "Hello NYC", "completed_phrase": "Bonjour NYC"}, "description": { "original_phrase": "NYC is the big apple", "completed_phrase": "NYC est la grosse pomme"}}, "custom_data": {"adapter": {"class": "Worldia\\Bundle\\ProductTestBundle\\Entity\\Product", "id": "2"}}}'
     Then the job "2" should have status "validated"
      And I should have the following translations for product "2":
        | title       | description             | locale |
        | Hello NYC   | NYC is the big apple    | en     |
        | Bonjour NYC | NYC est la grosse pomme | fr     |
