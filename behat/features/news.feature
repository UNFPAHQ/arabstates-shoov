Feature: News search
  In order to be able to view the news article page
  As an anonymous user
  We need to be able to filter article in news page

  @javascript
  Scenario: Visit News page, search and check news article.
    Given I am an anonymous user
    When  I visit the "news" page
    And   I fill "State of the World's Midwifery" in the title filter and "2014" in the year filter
    Then  I should see "With newly collected data on midwifery services"
