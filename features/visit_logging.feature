@visit_logging
Feature: Visit Logging

  Background:
    Given the store operates on a single channel in "United States"
    And the store has a product "Awesome T-Shirt"
    And the store has a category "T-Shirts"


  Scenario: User visits homepage
    When I visit the store homepage
    Then this request should be logged

  Scenario: User visits product page
    When I visit the product "Awesome T-Shirt" page
    Then this request should be logged

  Scenario: User visits category page
    When I visit the "T-Shirts" category page
    Then this request should be logged

  Scenario: User visits the cart page
    When I visit the cart page
    Then this request should be logged
    

