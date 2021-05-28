Feature:

  Scenario: Task successfully created
    When I go to "/tasks/create"
    Then I should see "Title"
    And I should see "Content"
    And I should see "Ajouter"
    And the "task_title" field should contain ""
    And the "task_content" field should contain ""
    And I fill in "task1" for "task_title"
    And I fill in "La premiere task" for "task_content"
    And the "task_title" field should contain "task1"
    And the "task_content" field should contain "La premiere task"
    When I press "Ajouter"
    Then I should be on "/tasks"
    And I should see "Superbe ! La tâche a été bien été ajoutée."

  Scenario: Task failed to created (content field is empty)
    When I go to "/tasks/create"
    Then I should see "Title"
    And I should see "Content"
    And I should see "Ajouter"
    And I fill in "test" for "task_title"
    And I fill in "" for "task_content"
    When I press "Ajouter"
    And I should see "Vous devez saisir du contenu"

  Scenario: Task failed to created (title field is empty)
    When I go to "/tasks/create"
    Then I should see "Title"
    And I should see "Content"
    And I should see "Ajouter"
    And I fill in "" for "task_title"
    And I fill in "test task" for "task_content"
    When I press "Ajouter"
    And I should see "Vous devez saisir un titre"


