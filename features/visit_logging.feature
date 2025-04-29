Feature: Logging frontend visits

  Scenario: User visits the homepage
    Given I am on the homepage
    Then a visit log should exist with route "sylius_shop_homepage"

  Scenario: User visits a product detail page
    Given I am on the product page "Cool T-Shirt"
    Then a visit log should exist with route "sylius_shop_product_show"

  Scenario: User visits a category page
    Given I am on the taxon page "T-Shirts"
    Then a visit log should exist with route "sylius_shop_product_index"

  Scenario: User visits the cart page
    Given I am on the cart summary page
    Then a visit log should exist with route "sylius_shop_cart_summary"

  Scenario: Admin sees all visit logs listed
    Given I am logged in as an administrator
    When I go to the request logs admin page
    Then I should see a visit log for route "sylius_shop_homepage"
