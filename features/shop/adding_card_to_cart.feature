@buying_gift_card
Feature: Adding a gift card to the cart
  In order to choose how much I want to spend on a gift card
  As a Customer
  I want to be able to buy a gift card with custom amount

  Background:
    Given the store operates on a single channel in "United States"
    And the store has a product "Standard gift card"
    And this product's price is "$20.00"
    And this product is a gift card
    And the store has a product "Gift card"
    And this product is a configurable gift card

  @ui
  Scenario: Adding a configurable gift card to the cart
    Given I am a logged in customer
    When I add this product to the cart with amount "$125.00" and custom message "Hey buddy"
    Then I should be on my cart summary page
    And I should be notified that the product has been successfully added
    And there should be one item in my cart
    And this item should have name "Gift card"
    And total price of "Gift card" item should be "$125.00"

  @api
  Scenario: Adding a configurable gift card to the cart
    Given I am a logged in customer
    When I add this product to the cart with amount "$125.00" and custom message "Hey buddy"
    Then I should see "Gift card" with unit price "$125.00" in my cart
    When I add this product to the cart with amount "$225.00" and custom message "Hey man"
    Then I should see "Gift card" with unit price "$225.00" in my cart

  @api @bug
  Scenario: Adding a gift card product twice to the cart
    When I add product "Standard gift card" to the cart
    Then I should see "Standard gift card" with unit price "$20.00" in my cart
    When I add product "Standard gift card" to the cart
    Then I should see "Standard gift card" with unit price "$20.00" in my cart


#  @api
#  Scenario: Adding a standard gift card to the cart
#    Given I am a logged in customer
#    When I add product "Standard gift card" to the cart
#    Then I should see "Standard gift card" with unit price "$20.00" in my cart
