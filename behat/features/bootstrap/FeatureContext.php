<?php


use Drupal\DrupalExtension\Context\DrupalContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;


class FeatureContext extends DrupalContext implements SnippetAcceptingContext {

  /**
   * @When I visit the homepage
   */
  public function iVisitTheHomepage() {
    $this->getSession()->visit($this->locatePath('/'));
  }

  /**
   * @Then I should have access to the page
   */
  public function iShouldHaveAccessToThePage() {
    $this->assertSession()->statusCodeEquals('200');
  }

  /**
   * @Then I should not have access to the page
   */
  public function iShouldNotHaveAccessToThePage() {
    $this->assertSession()->statusCodeEquals('403');
  }

  /**
   * @When I search :arg1
   */
  public function iSearch($search_value) {
    $this->getSession()->resizeWindow(1440, 900, 'current');
    $page = $this->getSession()->getPage();
    // Add a value to the search form
    $input_box = $page->find('css', '.top-search .search .top-search-text');
    $input_box->setValue($search_value);
    // Submit the form.
    $submit_button = $page->find('css', '.top-search .search .top-search-submit');
    $submit_button->click();
  }

  /**
   * @Then I should see the title :arg1
   */
  public function iShouldSeeTheTitle($title) {
    $this->checkingContentPage($title);
  }


  /**
   * @param $comparison_text
   * @throws Exception
   */
  public function checkingContentPage($comparison_text) {
    if(strpos($this->getSession()->getPage()->getText(), $comparison_text) === FALSE) {
      throw new Exception(format_string("The sentence result @sentence not found", array('@sentence' =>  $comparison_text)));
    }
    return TRUE;
  }

  /**
   * @When I visit :arg1 node of type :arg2
   */
  public function iVisitNodeOfType($title, $type) {
    $query = new \entityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', strtolower($type))
      ->propertyCondition('title', $title)
      ->propertyCondition('status', NODE_PUBLISHED)
      ->range(0, 1)
      ->execute();
    if (empty($result['node'])) {
      $params = array(
        '@title' => $title,
        '@type' => $type,
      );
      throw new \Exception(format_string("Node @title of @type not found.", $params));
    }
    $nid = key($result['node']);
    $params['@nid'] = $nid;
    $this->getSession()->visit($this->locatePath('node/' . $nid));
  }

  /**
   * @Then I should see the text :arg1
   */
  public function iShouldSeeTheText($text) {
    // Use the Mink Extension step definition.
    $this->assertSession()->pageTextContains($text);
  }

  /**
   * @When I visit the :arg1 page
   */
  public function iVisitThePage($page) {
    $this->getSession()->visit($this->locatePath('/' . $page));
  }

  /**
   * @When I fill :arg1 in the title filter and :arg2 in the year filter
   */
  public function iFillTheInTheFilterTitleAndInTheFilterYear($title, $year) {
    $page = $this->getSession()->getPage();
    // Add a title to the search form
    $input_search = $page->findById('#edit-title');
    $input_search->setValue($title);

    // Select the year value in the year select
    $year_select = $page->findById('#edit-field-news-date-value-value-year');
    $year_select->selectOption($year);

    // Submit the form
    $submit_button = $page->findById('#edit-submit-vw-news');
    $submit_button->click();

    // Get in the article page
    $article = $page->find('css', '.views-more-link');
    $article->click();
  }

  /**
   * @When I click on :arg1
   */
  public function iClickOn($text) {
    $page_url = $this->getSession()->getCurrentUrl();
    $page = $this->getSession()->getPage();

    // Find read more link.
    $link = $page->find('css', '.views-field-view-node .active');
    if (!$link) {
      throw new LogicException(format_string("Could not find the '@link' link at '@url'.", array('@link' => $text, '@url' => $page_url)));
    }
    // Click on the read more link.
    $link->click();

    // Click to close the Collapse.
    if($text === "Collapse") {
      $link->click();
    }
  }
}
