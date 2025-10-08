@admin_request_filtering
Feature: Admin can filter request logs
  In order to analyze specific user activity
  As an Administrator
  I want to filter request logs by various criteria

  Background:
    Given the store has currency "USD", "EUR"
    And the store operates on a channel named "United States" in "USD" currency
    And the store operates on another channel named "Germany" in "EUR" currency
    And the store has a product "T-Shirt"
    And the store classifies its products as "Clothing"
    And I am logged in as an administrator

  @ui
  Scenario: Administrator filters requests by channel
    Given there are requests from "United States" channel
    And there are requests from "Germany" channel
    When I go to the request logs page
    And I filter requests by channel "United States"
    Then I should see only requests from "United States" channel
    And I should not see requests from "Germany" channel

  @ui
  Scenario: Administrator filters requests by route name
    Given there are requests to different routes for "Germany" channel
    When I go to the request logs page
    And I filter requests by route name "sylius_shop_cart_summary"
    Then I should see only cart summary requests

  @ui
  Scenario: Administrator filters requests by date
    Given there are requests from different dates for "Germany" channel
    When I go to the request logs page
    And I filter requests by today's date
    Then I should see only requests from today
