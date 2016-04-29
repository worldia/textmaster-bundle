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
      And I create a translation project for products with the following parameters:
        | name      | languageFrom | languageTo | category | projectBriefing | options                       |
        | PROJECT-1 | en           | fr         | C054     | Nothing         | {"language_level": "premium"} |
     Then I should have the following jobs:
        | id | translatable | project   | document | status  |
        | 1  | 1            | PROJECT-1 | en-fr-1  | started |
        | 2  | 2            | PROJECT-1 | en-fr-2  | started |
     When I receive the request '{ "id": "en-fr-1", "title": "en-fr-1", "status": "in_review", "project_id": "PROJECT-1", "original_content": { "title": { "original_phrase": "Hello Paris"}, "description": { "original_phrase": "Paris is the city of lights"}}, "translated_content": { "title": { "translated_phrase": "Bonjour Paris"}, "description": { "translated_phrase": "Paris est la ville lumière"}}}'
     Then the job "1" should have status "finished"
     When I translate job "1"
     Then the job "1" should have status "validated"
      And I should have the following translations for product "1":
        | title         | description                 | locale |
        | Hello Paris   | Paris is the city of lights | en     |
        | Bonjour Paris | Paris est la ville lumière  | fr     |
     When I receive the request '{ "id": "en-fr-2", "title": "en-fr-2", "status": "in_review", "project_id": "PROJECT-1", "original_content": { "title": { "original_phrase": "Hello NYC"}, "description": { "original_phrase": "NYC is the big apple"}}, "translated_content": { "title": { "translated_phrase": "Bonjour NYC"}, "description": { "translated_phrase": "NYC est la grosse pomme"}}}'
     Then the job "2" should have status "finished"
     When I translate job "2"
     Then the job "2" should have status "validated"
      And I should have the following translations for product "2":
        | title       | description             | locale |
        | Hello NYC   | NYC is the big apple    | en     |
        | Bonjour NYC | NYC est la grosse pomme | fr     |