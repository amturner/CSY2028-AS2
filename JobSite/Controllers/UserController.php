<?php
namespace JobSite\Controllers;
class UserController {
    private $usersTable;
    private $get;
    private $post;

    public function __construct(\CSY2028\DatabaseTable $usersTable, $get, $post) {
        $this->usersTable = $usersTable;
        $this->get = $get;
        $this->post = $post;
    }

    // Function for display a page that lists all users
    // in the users table.
    public function listUsers() {
        $users = $this->usersTable->retrieveAllRecords();

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'admin/users.html.php',
            'variables' => [
                'users' => $users
            ],
            'title' => 'Admin Panel - Users'
        ];
    }

    // Function for submitting the edit user form.
    public function editUserSubmit() {
        if (isset($this->post['submit'])) {
            if (isset($this->get['id']))
                $user = $this->usersTable->retrieveRecord('id', $this->get['id'])[0];
            else
                $user = '';

            $errors = [];

            // Validate user input
            if ($this->post['user']['username'] != '') {
                $existingUsername = $this->usersTable->retrieveRecord('username', htmlspecialchars(strip_tags($this->post['user']['username']), ENT_QUOTES, 'UTF-8'));
                
                if (isset($this->get['id'])) {
                    $currentUsername = $this->usersTable->retrieveRecord('id', $this->get['id'])[0]->username;

                    if (!empty($existingUsername) && htmlspecialchars(strip_tags($this->post['user']['username']), ENT_QUOTES, 'UTF-8') != $currentUsername)
                        $errors[] = 'The specified username already is already in use.';
                }
            }
            else
                $errors[] = 'The username cannot be blank.';

            if ($this->post['user']['firstname'] == '')
                $errors[] = 'The first name cannot be blank.';

            if ($this->post['user']['surname'] == '')
                $errors[] = 'The surname cannot be blank.';

            if ($this->post['user']['email'] != '') {
                if (filter_var($this->post['user']['email'], FILTER_VALIDATE_EMAIL)) {
                    $existingEmail = $this->usersTable->retrieveRecord('email', $this->post['user']['email']);

                    if (isset($this->get['id'])) {
                        $currentEmail = $this->usersTable->retrieveRecord('id', $this->get['id'])[0]->email;
    
                        if (!empty($existingEmail) && $this->post['user']['email'] != $currentEmail)
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
            
            if (!isset($this->get['id']) && $this->post['user']['password'] == '')
                $errors[] = 'The password cannot be blank.';

            // Create new user account if there are no errors.
            if (count($errors) == 0) {
                if (isset($this->get['id']))
                    $pageName = 'User Updated';
                else
                    $pageName = 'User Added';

                $this->post['user']['username'] = strtolower(htmlspecialchars(strip_tags($this->post['user']['username']), ENT_QUOTES, 'UTF-8'));
                $this->post['user']['firstname'] = htmlspecialchars(strip_tags($this->post['user']['firstname']), ENT_QUOTES, 'UTF-8');
                $this->post['user']['surname'] = htmlspecialchars(strip_tags($this->post['user']['surname']), ENT_QUOTES, 'UTF-8');

                if (isset($this->get['id']) && $this->post['user']['password'] == '')
                    unset($this->post['user']['password']);
                else
                    $this->post['user']['password'] = password_hash($this->post['user']['username'] . $this->post['user']['password'], PASSWORD_DEFAULT);
    
                $this->usersTable->save($this->post['user']);
    
                $template = 'admin/editusersuccess.html.php';

                $variables = [
                    'username' => htmlspecialchars(strip_tags($this->post['user']['username']), ENT_QUOTES, 'UTF-8')
                ];
            }
            // Display the registration form with any generated errors.
            else {
                if (isset($this->get['id']))
                    $pageName = 'Edit User';
                else
                    $pageName = 'Add User';

                $template = 'admin/edituser.html.php';
                
                $variables = [
                    'errors' => $errors,
                    'user' => $user
                ];
            }
        }

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => $template,
            'variables' => $variables,
            'title' => 'Admin Panel ' . $pageName
        ];
    }

    // Function for displaying the edit user form. 
    public function editUserForm() {
        // Check if $_GET['id'] has been set. If so, display
        // a pre-filled edit user (Edit User) form.
        if (isset($this->get['id'])) {
            $user = $this->usersTable->retrieveRecord('id', $this->get['id'])[0];

            // Check if the user has permission to access the details of another user.
            // Redirect the user back to /admin/users if not.
            if (!empty($user) && (isset($_SESSION['isOwner']) || $this->get['id'] == $_SESSION['id'] || isset($_SESSION['isAdmin']) && ($user->role == 1 || $user->role == 0) || isset($_SESSION['isEmployee']) && $user->role == 0)) {
                return [
                    'layout' => 'sidebarlayout.html.php',
                    'template' => 'admin/edituser.html.php',
                    'variables' => [
                        'user' => $user
                    ],
                    'title' => 'Admin Panel - Edit User'
                ];    
            }
            else
                header('Location: /admin/users');
        }
        // Display an empty edit user (Add User) form.
        else {
            return [
                'layout' => 'sidebarlayout.html.php',
                'template' => 'admin/edituser.html.php',
                'variables' => [],
                'title' => 'Admin Panel - Add User'
            ];         
        }
    }

    // Function for deleting a user from the database.
    public function deleteUser() {
        $this->usersTable->deleteRecordById($this->post['user']['id']);

        header('Location: /admin/users');
    }

    // Function for submitting the login form.
    public function loginSubmit() {
        if (isset($this->post['submit'])) {
            $user = $this->usersTable->retrieveRecord('username', $this->post['login']['username']);

            $username = strtolower($this->post['login']['username']);
            $password = $this->post['login']['password'];
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

            // Check if the $error variable has no value. If so,
            // log the user into the system and set roles accordingly.
            if ($error == '') {
                session_start();

                if ($user[0]->role == 3)
                    $_SESSION['isOwner'] = true;
                elseif ($user[0]->role == 2)
                    $_SESSION['isAdmin'] = true;
                elseif ($user[0]->role == 1)
                    $_SESSION['isEmployee'] = true;
                else
                    $_SESSION['isClient'] = true;

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

    // Function for displaying the login form.
    public function loginForm() {
        session_start();
        // Check if is not already logged in. If so,
        // display the form.
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

    // Function for logging the user out from the system.
    public function logout() {   
        // Unset all $_SESSION variables.
        unset($_SESSION['loggedIn']);
        unset($_SESSION['isOwner']);
        unset($_SESSION['isAdmin']);
        unset($_SESSION['isEmployee']);
        unset($_SESSION['isClient']);
        unset($_SESSION['username']);
        unset($_SESSION['id']);

        return [
            'layout' => 'mainlayout.html.php',
            'template' => 'admin/logout.html.php',
            'variables' => [],
            'title' => 'Log out'
        ];
    }
}
?>