Feature: Reviewer access in the admin panel
    Background:
        Given a reviewer called "Julien" exists

    Scenario: Log in as Julien
        Given I am logged in as "Julien"
        And I visit the path "/"
        Then I should see the text "Laravel"
        * run the test

    Scenario: Acces to nova as Julien
        Given I am logged in as "Julien"
        When I visit the path "/nova"
        Then I should see the text "Julien"
        * I should see the text "Events" in dashboard sidebar
        * I should see the text "Users" in dashboard sidebar
        But I should not see the text "Companies" in dashboard sidebar
        Then I take a screenshot "login-as-julien"
        * run the test

    Scenario: Can edit an event
        Given I am logged in as "Julien"
        * I have an event called "Mon event"
        * I visit the path "/nova/resources/events/1/edit"
        * I'm waiting to see the text "Mon event" in the page
        When I type "Mon event - edit" in the field "Name"
        * I type "79.99" in the field "Price"
        Then I press on the button "Update Event"
        * I'm waiting to see the text "Mon event - edit" in the page
        * I take a screenshot "edit-event-as-julien"
        * I should see the text "79.99"
        * run the test
