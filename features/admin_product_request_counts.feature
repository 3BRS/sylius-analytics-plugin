@admin_product_request_counts
Feature: Admin product grid shows request counts
  In order to see product popularity
  As an Administrator
  I want to see request counts in the product grid

  Background:
    Given the store operates on a single channel in "United States"
    And the store has a product "Popular T-Shirt"
    And the store has a product "Unpopular T-Shirt"
    And I am logged in as an administrator

  @ui
  Scenario: Administrator sees request counts in product grid
    Given the product "Popular T-Shirt" has been visited 10 times
    And the product "Unpopular T-Shirt" has been visited 2 times
    When I go to the admin products page
    Then I should see the product "Popular T-Shirt" with 10 requests
    And I should see the product "Unpopular T-Shirt" with 2 requests

  @ui
  Scenario: Administrator sees zero requests for unvisited products
    Given the product "Popular T-Shirt" has never been visited
    When I go to the admin products page
    Then I should see the product "Popular T-Shirt" with 0 requests