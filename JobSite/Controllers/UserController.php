<?php
namespace JobSite\Controllers;
class UserController {
    private $usersTable;
    private $categoriesTable;

    public function __construct(\CSY2028\DatabaseTable $usersTable, \CSY2028\DatabaseTable $categoriesTable) {
        $this->usersTable = $usersTable;
        $this->categoriesTable = $categoriesTable;
    }

    public function editUserSubmit() {
        if (isset($_POST['submit'])) {
            $categories = $this->categoriesTable->retrieveAllRecords();

            if (isset($_GET['id']))
                $user = $this->usersTable->retrieveRecord('id', $_GET['id'])[0];
            else
                $user = '';

            $errors = [];

            // Validate user input
            if ($_POST['user']['username'] != '') {
                $existingUsername = $this->usersTable->retrieveRecord('username', htmlspecialchars(strip_tags($_POST['user']['username']), ENT_QUOTES, 'UTF-8'));
                
                if (isset($_GET['id'])) {
                    $currentUsername = $this->usersTable->retrieveRecord('id', $_GET['id'])[0]->username;

                    if (!empty($existingUsername) && htmlspecialchars(strip_tags($_POST['user']['username']), ENT_QUOTES, 'UTF-8') != $currentUsername)
                        $errors[] = 'The specified username already is already in use.';
                }
            }
            else
                $errors[] = 'The username cannot be blank.';

            if ($_POST['user']['email'] != '') {
                if (filter_var($_POST['user']['email'], FILTER_VALIDATE_EMAIL)) {
                    $existingEmail = $this->usersTable->retrieveRecord('email', $_POST['user']['email']);

                    if (isset($_GET['id'])) {
                        $currentEmail = $this->usersTable->retrieveRecord('id', $_GET['id'])[0]->email;
    
                        if (!empty($existingEmail) && $_POST['user']['email'] != $currentEmail)
                            $errors[] = 'The specified email address is already in use.';
                    }
                    else {
                        if (!empty($existingEmail))
                            $errors[] = 'The specified email address is already in use.';
                    }
                }
                else
                    $errors[] = 'The email address is invalid.';
            }
            else
                $errors[] = 'The email address cannot be blank.';
            
            if (!isset($_GET['id']) && $_POST['user']['password'] == '')
                $errors[] = 'The password cannot be blank.';

            // Create new user account if there are no errors.
            if (count($errors) == 0) {
                if (isset($_GET['id']))
                    $pageName = 'User Updated';
                else
                    $pageName = 'User Added';

                $_POST['user']['username'] = htmlspecialchars(strip_tags($_POST['user']['username']), ENT_QUOTES, 'UTF-8');

                if (isset($_GET['id']) && $_POST['user']['password'] == '')
                    unset($_POST['user']['password']);
                else
                    $_POST['user']['password'] = password_hash($_POST['user']['username'] . $_POST['user']['password'], PASSWORD_DEFAULT);
    
                $this->usersTable->save($_POST['user']);
    
                return [
                    'layout' => 'sidebarlayout.html.php',
                    'template' => 'admin/editusersuccess.html.php',
                    'variables' => [
                        'categories' => $categories,
                        'username' => htmlspecialchars(strip_tags($_POST['user']['username']), ENT_QUOTES, 'UTF-8')
                    ],
                    'title' => 'Admin Panel - ' . $pageName
                ];
            }
            // Display the registration form with any generated errors.
            else {
                if (isset($_GET['id']))
                    $pageName = 'Edit User';
                else
                    $pageName = 'Add User';

                return [
                    'layout' => 'sidebarlayout.html.php',
                    'template' => 'admin/edituser.html.php',
                    'variables' => [
                        'categories' => $categories,
                        'errors' => $errors,
                        'user' => $user
                    ],
                    'title' => 'Admin Panel ' . $pageName
                ];
            }
        }
    }

    public function editUserForm() {
        $categories = $this->categoriesTable->retrieveAllRecords();

        if (isset($_GET['id'])) {
            $user = $this->usersTable->retrieveRecord('id', $_GET['id'])[0];

            return [
                'layout' => 'sidebarlayout.html.php',
                'template' => 'admin/edituser.html.php',
                'variables' => [
                    'categories' => $categories,
                    'user' => $user
                ],
                'title' => 'Admin Panel - Edit User'
            ];
        }
        else {
            return [
                'layout' => 'sidebarlayout.html.php',
                'template' => 'admin/edituser.html.php',
                'variables' => [
                    'categories' => $categories
                ],
                'title' => 'Admin Panel - Add User'
            ];
        }
    }

    public function deleteUser() {
        $this->usersTable->deleteRecord($_POST['user']['id']);

        header('Location: /admin/users');
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

                $_SESSION['username'] = $user[0]->username;
                $_SESSION['id'] = $user[0]->id;

                $_SESSION['loggedIn'] = true;
                header('Location: /admin');
            }
            else {
                return [
                    'layout' => 'mainlayout.html.php',
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
                'layout' => 'mainlayout.html.php',
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
        unset($_SESSION['username']);
        unset($_SESSION['id']);
        //header('Location: /admin/logout');

        return [
            'layout' => 'mainlayout.html.php',
            'template' => 'admin/logout.html.php',
            'variables' => [],
            'title' => 'Log out'
        ];
    }
}
?>