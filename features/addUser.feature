Feature:

  Scenario: User successfully connected
    When I go to "/login"
    Then I should see "Nom d'utilisateur"
    And I should see "Mot de passe"
    And I should see "Se connecter"
    And I fill in "username1" for "username"
    And I fill in "password1" for "password"
    When I press "Se connecter"
    Then I should be on "/login"
    And I should see "Vous êtes connecté en tant que username1"

  Scenario: User successfully registred
    When I go to "/login"
    And I fill in "username1" for "username"
    And I fill in "password1" for "password"
    When I press "Se connecter"
    And I should see "Vous êtes connecté en tant que username1"
    When I go to "/users/create"
    Then I should see "Nom d'utilisateur"
    And I should see "Mot de passe"
    And I should see "Tapez le mot de passe à nouveau"
    And I should see "Adresse email"
    And I should see "Rôle"
    And I should see "Ajouter"
    And I fill in "user1" for "user_username"
    And I fill in "pass1" for "user_password_first"
    And I fill in "pass1" for "user_password_second"
    And I select "Utilisateur" from "user_roles"
    And I fill in "emailtest@test.test" for "user_email"
    When I press "Ajouter"
    Then I should be on "/users"
    And I should see "Superbe ! L'utilisateur a bien été ajouté"

  Scenario: User failed to created (username field is empty)
    When I go to "/login"
    And I fill in "username1" for "username"
    And I fill in "password1" for "password"
    When I press "Se connecter"
    Given I am on "/login"
    And I should see "Vous êtes connecté en tant que username1"
    When I go to "/users/create"
    Then I should see "Nom d'utilisateur"
    And I should see "Mot de passe"
    And I should see "Tapez le mot de passe à nouveau"
    And I should see "Adresse email"
    And I should see "Ajouter"
    And I fill in "" for "user_username"
    And I fill in "pass2" for "user_password_first"
    And I fill in "pass2" for "user_password_second"
    And I select "Utilisateur" from "user_roles"
    And I fill in "emailtest@test.test" for "user_email"
    When I press "Ajouter"
    And I should see "Vous devez saisir un nom d'utilisateur"

  Scenario: User failed to created (password and confirm password are different)
    When I go to "/login"
    And I fill in "username1" for "username"
    And I fill in "password1" for "password"
    When I press "Se connecter"
    Then I should be on "/login"
    And I should see "Vous êtes connecté en tant que username1"
    When I go to "/users/create"
    Then I should see "Nom d'utilisateur"
    And I should see "Mot de passe"
    And I should see "Tapez le mot de passe à nouveau"
    And I should see "Adresse email"
    And I should see "Ajouter"
    And I fill in "user3" for "user_username"
    And I fill in "pass3" for "user_password_first"
    And I fill in "password3" for "user_password_second"
    And I select "Utilisateur" from "user_roles"
    And I fill in "emailtest@test.test" for "user_email"
    When I press "Ajouter"
    Then I should see "Les deux mots de passe doivent correspondre"
