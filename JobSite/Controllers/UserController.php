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
                    'template' => 'admin/addusersuccess.html.php',
                    'variables' => [
                        'username' => $username
                    ],
                    'title' => 'User Created'
                ];
            }
            // Display the registration from with any generated errors.
            else {
                return [
                    'template' => 'admin/adduser.html.php',
                    'variables' => [
                        'errors' => $errors
                    ],
                    'title' => 'Create User'
                ];
            }
        }
    }

    public function addUserForm() {
        return [
			'template' => 'admin/adduser.html.php',
			'variables' => [],
			'title' => 'Create Account'
		];
    }

    public function loginSubmit() {
        if (isset($_POST['submit'])) {
            $user = $this->usersTable->retrieveRecord('username', $_POST['login']['username']);

            $username = $_POST['login']['username'];
            $password = $_POST['login']['password'];
            $passwordWithUsername = $username . $password;

            $error = '';

            if ($username != '' && $password != '')
                if (!empty($user)) {
                    if (password_verify($passwordWithUsername, $user[0]->password) == true) {
                        if ($user[0]->active == 0)
                            $error = 'Your account has not been activated. Please contact an administrator.';
                    }
                    else
                        $error = 'The password provided is incorrect.';
                }
                else
                    $error = 'A user with the username provided does not exist.';
            else
                $error = 'You have not provided a username and/or password.';

            if ($error == '') {
                session_start();

                if ($user[0]->administrator == 1)
                    $_SESSION['isAdmin'] = true;
                else
                    $_SESSION['isAdmin'] = false;

                $_SESSION['id'] = $user[0]->id;

                $_SESSION['loggedIn'] = true;
                header('Location: /admin');
            }
            else {
                return [
                    'template' => 'admin/login.html.php',
                    'variables' => [
                        'error' => $error
                    ],
                    'title' => 'Log in'
                ];
            }
        }
    }

    public function loginForm() {
        session_start();
        if (!isset($_SESSION['loggedIn'])) {
            return [
                'template' => 'admin/login.html.php',
                'variables' => [],
                'title' => 'Log in'
            ];
        }
        else
            header('Location: /admin');
    }

    public function logout() {
        //session_start();
        unset($_SESSION['loggedIn']);
        unset($_SESSION['isAdmin']);
        unset($_SESSION['id']);
        //header('Location: /admin/logout');

        return [
            'template' => 'admin/logout.html.php',
            'variables' => [],
            'title' => 'Log out'
        ];
    }
}
?>