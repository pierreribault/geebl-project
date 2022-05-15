Feature: Redactor access in the admin panel
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
        * I should see the text "Users" in dashboard sidebar
        But I should not see the text "Companies" in dashboard sidebar
        Then I take a screenshot "login-as-alan"
        * run the test

    Scenario: Can create a new event
        Given I am logged in as "Alan"
        And I visit the path "/nova/resources/events/new"
        When I type "Mon event" in the field "Name"
        * I type "mon-event" in the field "Slug"
        * I type "Paris" in the field "Location"
        * I select "2020-05-10" in the datepicker "Date"
        * I type "Mon super event de fou" in the field "Description"
        * I type "89.99" in the field "Price"
        * I type "2000" in the field "Seats"
        * I select "Alan" in the field "Author"
        Then I press on the button "Create Event"
        * I'm waiting to see the text "Mon event" in the page
        * I take a screenshot "create-event-as-alan"
        * I should see the text "Paris"
        * I should see the text "2020-05-10"
        * I should see the text "Mon super event de fou"
        * I should see the text "89.99"
        * I should see the text "2000"
        * I should see the text "Alan"
        * run the test

    Scenario: Can edit an event
        Given I am logged in as "Alan"
        * I have an event called "Mon event"
        * I visit the path "/nova/resources/events/1/edit"
        * I'm waiting to see the text "Mon event" in the page
        When I type "Mon event - edit" in the field "Name"
        * I type "79.99" in the field "Price"
        Then I press on the button "Update Event"
        * I'm waiting to see the text "Mon event - edit" in the page
        * I take a screenshot "edit-event-as-alan"
        * I should see the text "79.99"
        * run the test
