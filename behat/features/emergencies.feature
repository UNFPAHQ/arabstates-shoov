Feature: Emergencies page
  In order to be able to view the article text
  As an anonymous user
  We need to be able to click article Read more and Collapse link

  @api
  Scenario: Visit emergencies page and check the read more link.
    Given I am an anonymous user
    When  I visit the "topics/emergencies" page
    And   I click on "Read More"
    Then  I should see "UNFPA works closely with governments"


  @javascript
  Scenario: Visit emergencies page and check the collapse link.
    Given I am an anonymous user
    When  I visit the "topics/emergencies" page
    And   I click on "Collapse"
    Then  I should not see "UNFPA works closely with governments"