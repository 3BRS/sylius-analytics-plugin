@view_logged_requests
Feature: View logged requests in the admin panel
  In order to monitor user activity
  As an Administrator
  I want to see request logs from shop users

  Background:
    Given the store operates on a single channel in "United States"
    And the store has a product "Awesome T-Shirt"
    And the store has a category "T-Shirts"
    And I am logged in as an administrator

  @ui
  Scenario: Administrator views the visit logs
    Given I visit the store homepage
    And I visit the product "Awesome T-Shirt" page
    And I visit the "T-Shirts" category page
    And I visit the cart page
    When I go to the request logs page
    Then I should see visit logs for all pages
