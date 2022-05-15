Feature: Owner access in the admin panel
    Background:
        Given a owner called "Jamy" exists

    Scenario: Log in as Jamy
        Given I am logged in as "Jamy"
        And I visit the path "/"
        Then I should see the text "Laravel"
        * run the test

    Scenario: Acces to nova as Jamy
        Given I am logged in as "Jamy"
        When I visit the path "/nova"
        Then I should see the text "Jamy"
        * I take a screenshot "login-as-jamy"
        * I should see the text "Events" in dashboard sidebar
        * I should see the text "Users" in dashboard sidebar
        * I should see the text "Companies" in dashboard sidebar
        * run the test
