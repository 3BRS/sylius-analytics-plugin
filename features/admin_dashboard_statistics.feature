@admin_dashboard_statistics
Feature: Admin dashboard shows analytics statistics
  In order to monitor popular content
  As an Administrator
  I want to see most requested pages statistics on the dashboard

  Background:
    Given the store operates on a single channel in "United States"
    And the store has a product "Popular T-Shirt"
    And the store has a product "Unpopular T-Shirt"
    And the store classifies its products as "T-Shirts"
    And I am logged in as an administrator

  @ui
  Scenario: Administrator sees most requested pages widget on dashboard
    Given there are multiple visits to different pages
    And I visit the product "Popular T-Shirt" page 5 times
    And I visit the product "Unpopular T-Shirt" page 2 times
    And I visit the store homepage 3 times
    When I go to the admin dashboard
    Then I should see the most requested pages widget
    And I should see "Popular T-Shirt" page with more visits than "Unpopular T-Shirt"

  @ui
  Scenario: Administrator sees configurable time period for statistics
    Given there are visits from different time periods
    When I go to the admin dashboard
    Then I should see statistics for the configured number of days
    And the widget should show the request log days parameter