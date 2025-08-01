@admin_menu_integration
Feature: Admin menu contains analytics section
  In order to easily access analytics
  As an Administrator
  I want to see analytics menu item in admin panel

  Background:
    Given the store operates on a single channel in "United States"
    And I am logged in as an administrator

  @ui
  Scenario: Administrator sees request logs menu item
    When I go to the admin dashboard
    Then I should see "Request Logs" in the admin menu
    And the request logs menu item should be accessible

  @ui
  Scenario: Administrator can navigate to request logs from menu
    When I go to the admin dashboard
    And I click on "Request Logs" in the admin menu
    Then I should be redirected to the request logs page