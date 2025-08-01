@console_command
Feature: Console command removes old request logs
  In order to manage storage space
  As a System Administrator
  I want to remove old analytics logs via console command

  Background:
    Given the store operates on a single channel in "United States"
    And there are request logs from 100 days ago
    And there are request logs from 50 days ago
    And there are request logs from 10 days ago

  @cli
  Scenario: Remove logs older than default 90 days
    When I run the command "threebrs:analytics:remove-old"
    Then the command should succeed
    And logs older than 90 days should be removed
    And recent logs should be preserved

  @cli
  Scenario: Remove logs older than custom number of days
    When I run the command "threebrs:analytics:remove-old 60"
    Then the command should succeed
    And logs older than 60 days should be removed
    And logs newer than 60 days should be preserved

  @cli
  Scenario: Command validates numeric input
    When I run the command "threebrs:analytics:remove-old invalid"
    Then the command should fail
    And I should see an error about numeric value

  @cli
  Scenario: Command validates positive number
    When I run the command "threebrs:analytics:remove-old -10"
    Then the command should fail
    And I should see an error about positive integer
