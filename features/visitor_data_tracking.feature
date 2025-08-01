@visitor_data_tracking
Feature: Visitor data is properly tracked and stored
  In order to have detailed analytics
  As an Administrator
  I want to see all visitor data tracked correctly

  Background:
    Given the store operates on a single channel in "United States"
    And the store has a product "T-Shirt"
    And there is a customer account "john@example.com"

  @ui
  Scenario: Anonymous visitor data is tracked
    When an anonymous user visits the store homepage
    And the request should be logged with IP address
    And the request should be logged with user agent
    And the request should be logged with no customer information

  @ui
  Scenario: Logged in customer data is tracked
    Given I am logged in as "john@example.com"
    When I visit the store homepage
    Then the request should be logged with customer "john@example.com"
    And the request should be logged with IP address
    And the request should be logged with user agent

  @ui
  Scenario: Full URL and route name are tracked
    When I visit the product "T-Shirt" page
    Then the request should be logged with full URL containing product slug
    And the request should be logged with route name "sylius_shop_product_show"
    And the request should be logged with channel information
