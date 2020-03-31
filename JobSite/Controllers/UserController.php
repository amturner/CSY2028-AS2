<?php
namespace JobSite\Controllers;
class UserController {
    private $usersTable;

    public function __construct(\CSY2028\DatabaseTable $usersTable) {
        $this->usersTable = $usersTable;
    }

    public function addUserSubmit() {
        if (isset($_POST['submit'])) {
            // Assign user input to variables.
            $username = $_POST['register']['username'];
            $email = $_POST['register']['email'];
            $password = $_POST['register']['password'];

            $errors = [];

            // Validate user input
            if ($username != '') {
                $existingUsername = $this->usersTable->retrieveRecord('username', $username);

                if (!empty($existingUsername))
                    $errors[] = 'The specified username already is already in use.';
            }
            else
                $errors[] = 'The username cannot be blank.';

            if ($email != '') {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $existingEmail = $this->usersTable->retrieveRecord('email', $email);

                    if (!empty($existingEmail))
                        $errors[] = 'The specified email address is already in use.';
                }
                else
                    $errors[] = 'The email address is invalid.';
            }
            else
                $errors[] = 'The email address cannot be blank.';
            
            if ($password == '')
                $errors[] = 'The password cannot be blank.';

            // Create new user account if there are no errors.
            if (count($errors) == 0) {
                $values = [
                    'username' => $username,
                    'email' => $email,
                    'password' => password_hash($username . $password, PASSWORD_DEFAULT)
                ];
    
                $this->usersTable->save($values);
    
                return [
                    'template' => 'admin/registersuccess.html.php',
                    'variables' => [
                        'username' => $username
                    ],
                    'title' => 'Account Created'
                ];
            }
            // Display the registration from with any generated errors.
            else {
                return [
                    'template' => 'admin/registerform.html.php',
                    'variables' => [
                        'errors' => $errors
                    ],
                    'title' => 'Create Account'
                ];
            }
        }
    }

    public function addUserForm() {
        return [
			'template' => 'admin/registerform.html.php',
			'variables' => [],
			'title' => 'Create Account'
		];
    }

    public function loginSubmit() {
        if (isset($_POST['submit'])) {
            $user = $this->usersTable->retrieveRecord('username', $_POST['login']['username']);

            $username = $_POST['login']['username'];
            $password = $username . $_POST['login']['password'];

            if (password_verify($password, $user[0]->password)) {
                session_start();
                $_SESSION['loggedIn'] = true;
                $_SESSION['user_id'] = $user[0]->user_id;
                header('Location: /admin');
            }
        }
    }

    public function loginForm() {
        return [
            'template' => 'admin/loginform.html.php',
            'variables' => [],
            'title' => 'Log in'
        ];
    }

    public function logout() {
        //session_start();
        unset($_SESSION['loggedIn']);
        unset($_SESSION['user_id']);
        //header('Location: /admin/logout');

        return [
            'template' => 'admin/logout.html.php',
            'variables' => [],
            'title' => 'Log out'
        ];
    }
}
?>