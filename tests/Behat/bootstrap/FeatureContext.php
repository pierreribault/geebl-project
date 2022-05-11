<?php

use App\Models\Company;
use App\Models\Seller;
use App\Models\User;
use Behat\Behat\Context\Context;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Tests\BrowserBuilder;
use Tests\DuskTestCase;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends DuskTestCase implements Context
{
    use DatabaseMigrations;

    protected BrowserBuilder $builder;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {

        parent::setUp();

        $this->builder = new BrowserBuilder();
    }

    /** @BeforeScenario */
    public function before(BeforeScenarioScope $scope)
    {
    }

    /** @AfterScenario */
    public function after(AfterScenarioScope $scope)
    {
        parent::tearDown();
    }

    /**
     * @Given I visit the path :path
     */
    public function iVisitThePath($path)
    {
        $this->builder->visit($path);
    }

    /**
     * @Then I should see the text :text
     */
    public function iShouldSeeTheText($text)
    {
        $this->builder->assertSee($text);
    }

    /**
     * @Then I should see the text :text in dashboard sidebar
     */
    public function iShouldSeeTheTextInDashboardSidebar($text)
    {
        $this->builder->assertSeeIn('ul.list-reset', $text);
    }

    /**
     * @Then I should not see the text :text
     */
    public function iShouldNotSeeTheText($text)
    {
        $this->builder->assertDontSee($text);
    }

    /**
     * @Then I take a screenshot :name
     */
    public function iTakeScreenshot($filename)
    {
        $this->builder->screenshot($filename);
    }

    /**
     * @Then run the test
     */
    public function runScenario()
    {
        $this->browse($this->builder->execute());
    }

    /**
     * @Given a user called :user exists
     */
    public function aUserCalledExists($user)
    {
        $user = User::factory()->create([
            'name' => $user,
        ]);
    }

    /**
     * @Given a redactor called :user exists
     */
    public function aRedactorCalledExists($user)
    {
        Company::factory()
            ->has(Seller::factory()->for(User::factory()->set('name', $user))->redactor())
            ->create();
    }

    /**
     * @Given I am logged in as :user
     */
    public function iAmLoggedInAs($user)
    {
        $user = User::where('name', $user)->first();

        $this->builder->loginAs($user);
    }

    /**
     * @Given I press on the button :text
     */
    public function iPressOnTheButton($text)
    {
        $this->builder->pressAndWaitFor($text);
    }

    /**
     * @Then I type :text in the field :field
     */
    public function iTypeInField($text, $field)
    {
        $this->builder->type("@" . str()->lower($field), $text);
    }

    /**
     * @Then I select :text in the datepicker :field
     */
    public function iSelectInDatePicker($date, $field)
    {
        $this->builder->keys("@" . str()->lower($field), $date);
    }

    /**
     * @Then I select :text in the field :field
     */
    public function iSelectInField($text, $field)
    {
        $this->builder->select("@" . str()->lower($field));
    }
}
