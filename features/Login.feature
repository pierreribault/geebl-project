Feature: Login
    Background:
        Given a redactor called "Alan" exists

    Scenario: Log in as Alan
        Given I am logged in as "Alan"
        And I visit the path "/"
        Then I should see the text "Laravel"
        * run the test

    Scenario: Acces to nova as Alan
        Given I am logged in as "Alan"
        When I visit the path "/nova"
        Then I should see the text "Alan"
        * I should see the text "Events" in dashboard sidebar
        * I should see the text "Sellers" in dashboard sidebar
        * I should see the text "Users" in dashboard sidebar
        But I should not see the text "Companies"
        Then I take a screenshot "login-as-alan"
        * run the test

    Scenario: Can create a new event
        Given I am logged in as "Alan"
        And I visit the path "/nova/resources/events/new"
        When I type "Mon event" in the field "Name"
        * I type "mon-event" in the field "Slug"
        * I select "20221002" in the datepicker "Date"
        * I type "Paris" in the field "Location"
        * I type "Mon super event de fou" in the field "Description"
        * I type "89,99" in the field "Price"
        * I type "2000" in the field "Seats"
        * I select "Alan" in the field "Author"
        Then I press on the button "Create Event"
        * I take a screenshot "create-event"
        * run the test
