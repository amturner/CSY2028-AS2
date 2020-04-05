<?php
require 'CSY2028/DatabaseTable.php';
require 'JobSite/Controllers/UserController.php';
class UserControllerTest extends \PHPUnit\Framework\TestCase {  
    private $pdo;
    private $usersTable;

    public function setUp() {
        require 'dbConnection.php';
        $this->pdo = $pdo;
        $this->usersTable = new \CSY2028\DatabaseTable($this->pdo, 'users', 'id');
    }

    /* User Controller Tests */
    // List Users Test
    public function testListUsers() {
        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], []);
        $users = $userController->listUsers();

        $this->assertNotEmpty($users['variables']['users']);
    }

    // Add User Tests
    public function testAddUserNoDetails() {
        $testPostData = [
            'user' => [
                'username' => '',
                'firstname' => '',
                'surname' => '',
                'email' => '',
                'password' => ''
            ],
            'submit' => true
        ];      

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        $user = $userController->editUserSubmit();

        $this->assertEquals(count($user['variables']['errors']), 5);
    }

    public function testAddUserOnlyUsername() {
        $testPostData = [
            'user' => [
                'username' => 'phpunit',
                'firstname' => '',
                'surname' => '',
                'email' => '',
                'password' => ''
            ],
            'submit' => true
        ];      

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        $user = $userController->editUserSubmit();

        $this->assertEquals(count($user['variables']['errors']), 4);
    }

    public function testAddUserOnlyFirstname() {
        $testPostData = [
            'user' => [
                'username' => '',
                'firstname' => 'PHPUnit',
                'surname' => '',
                'email' => '',
                'password' => ''
            ],
            'submit' => true
        ];      

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        $user = $userController->editUserSubmit();

        $this->assertEquals(count($user['variables']['errors']), 4);
    }

    public function testAddUserOnlySurname() {
        $testPostData = [
            'user' => [
                'username' => '',
                'firstname' => '',
                'surname' => 'User',
                'email' => '',
                'password' => ''
            ],
            'submit' => true
        ];      

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        $user = $userController->editUserSubmit();

        $this->assertEquals(count($user['variables']['errors']), 4);
    }

    public function testAddUserOnlyEmail() {
        $testPostData = [
            'user' => [
                'username' => '',
                'firstname' => '',
                'surname' => '',
                'email' => 'phpunit@jobs.v.je',
                'password' => ''
            ],
            'submit' => true
        ];      

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        $user = $userController->editUserSubmit();

        $this->assertEquals(count($user['variables']['errors']), 4);
    }

    public function testAddUserOnlyPassword() {
        $testPostData = [
            'user' => [
                'username' => '',
                'firstname' => '',
                'surname' => '',
                'email' => '',
                'password' => 'phpunit_128'
            ],
            'submit' => true
        ];      

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        $user = $userController->editUserSubmit();

        $this->assertEquals(count($user['variables']['errors']), 4);
    }

    public function testAddUserUsernameAndFirstname() {
        $testPostData = [
            'user' => [
                'username' => 'phpunit',
                'firstname' => 'PHPUnit',
                'surname' => '',
                'email' => '',
                'password' => ''
            ],
            'submit' => true
        ];      

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        $user = $userController->editUserSubmit();

        $this->assertEquals(count($user['variables']['errors']), 3);
    }

    public function testAddUserUsernameFirstnameSurname() {
        $testPostData = [
            'user' => [
                'username' => 'phpunit',
                'firstname' => 'PHPUnit',
                'surname' => 'User',
                'email' => '',
                'password' => ''
            ],
            'submit' => true
        ];      

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        $user = $userController->editUserSubmit();

        $this->assertEquals(count($user['variables']['errors']), 2);
    }

    public function testAddUserUsernameFirstnameSurnamePassword() {
        $testPostData = [
            'user' => [
                'username' => 'phpunit',
                'firstname' => 'PHPUnit',
                'surname' => 'User',
                'email' => '',
                'password' => 'phpunit_128!'
            ],
            'submit' => true
        ];      

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        $user = $userController->editUserSubmit();

        $this->assertEquals(count($user['variables']['errors']), 1);
    }

    public function testAddUserInvalidEmail() {
        $testPostData = [
            'user' => [
                'username' => 'phpunit',
                'firstname' => 'PHPUnit',
                'surname' => 'User',
                'email' => 'jobs.v.je',
                'password' => 'phpunit_128!'
            ],
            'submit' => true
        ];      

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        $user = $userController->editUserSubmit();

        $this->assertEquals(count($user['variables']['errors']), 1);
    }

    public function testAddUserEmailAlreadyTaken() {
        $testPostData = [
            'user' => [
                'username' => 'phpunit',
                'firstname' => 'PHPUnit',
                'surname' => 'User',
                'email' => 'admin@jobs.v.je',
                'password' => 'phpunit_128!'
            ],
            'submit' => true
        ];      

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        $user = $userController->editUserSubmit();

        $this->assertEquals(count($user['variables']['errors']), 1);
    }


    public function testAddUserSuccessful() {
        $testPostData = [
            'user' => [
                'username' => 'phpunit',
                'firstname' => 'PHPUnit',
                'surname' => 'User',
                'email' => 'phpunit@jobs.v.je',
                'password' => 'phpunit_128!'
            ],
            'submit' => true
        ]; 

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        $userController->editUserSubmit();
        
        $user = $this->pdo->query('SELECT username FROM users WHERE username = "phpunit";')->fetch();

        $this->assertNotNull($user['username']);
    }

    // Edit User Tests
    public function testEditUserError() {
        $testGetData = [
            'id' => 6
        ];

        $testPostData = [
            'user' => [
                'id' => 6,
                'username' => 'phpunit',
                'firstname' => '',
                'surname' => 'Account',
                'email' => 'phpunit@jobs.v.je',
                'password' => ''
            ],
            'submit' => true
        ]; 
       
        $userController = new \JobSite\Controllers\UserController($this->usersTable, $testGetData, $testPostData);
    
        $userController->editUserSubmit();

        $user = $userController->editUserSubmit();

        $this->assertEquals(count($user['variables']['errors']), 1);
    }

    public function testEditUser() {
        $testGetData = [
            'id' => 6
        ];

        $testPostData = [
            'user' => [
                'id' => 6,
                'username' => 'phpunit',
                'firstname' => 'PHPUnit',
                'surname' => 'Account',
                'email' => 'phpunit@jobs.v.je',
                'password' => ''
            ],
            'submit' => true
        ]; 
       
        $userController = new \JobSite\Controllers\UserController($this->usersTable, $testGetData, $testPostData);
    
        $userController->editUserSubmit();

        $user = $this->pdo->query('SELECT id, surname FROM users WHERE id = 6;')->fetch();

        $this->assertNotEquals($user['surname'], 'User');
    }

    public function testEditUserChangePassword() {
        $testGetData = [
            'id' => 6
        ];

        $testPostData = [
            'user' => [
                'id' => 6,
                'username' => 'phpunit',
                'firstname' => 'PHPUnit',
                'surname' => 'Account',
                'email' => 'phpunit@jobs.v.je',
                'password' => 'testingcode_256!'
            ],
            'submit' => true
        ]; 
       
        $userController = new \JobSite\Controllers\UserController($this->usersTable, $testGetData, $testPostData);
    
        $userController->editUserSubmit();

        $user = $this->pdo->query('SELECT id, password FROM users WHERE id = 6;')->fetch();

        $this->assertNotEquals($user['password'], password_hash('phpunit' . 'phpunit_128!', PASSWORD_DEFAULT));
    }

    // Delete User Test
    public function testDeleteUser() {
        $userResult = $this->pdo->query('SELECT id, username FROM users WHERE username = "phpunit";');
        $user = $userResult->fetch();

        $testPostData = [
            'user' => [
                'id' => $user['id']
            ],
            'submit' => true
        ]; 

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);

        @$userController->deleteUser();

        $user = $this->pdo->query('SELECT id, username FROM users WHERE username = "phpunit";')->fetch();

        $this->assertNull($user['username']);
        $this->pdo->query('ALTER TABLE users AUTO_INCREMENT = 6');
    }

    // Log in Tests
    public function testLoginNoDetails() {
        $testPostData = [
            'login' => [
                'username' => '',
                'password' => '',
            ],
            'submit' => true
        ];

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        $user = @$userController->loginSubmit();

        $this->assertEquals($user['variables']['error'], 'You have not provided a username and/or password.');
    }

    public function testLoginNoPassword() {
        $testPostData = [
            'login' => [
                'username' => 'owner',
                'password' => '',
            ],
            'submit' => true
        ];

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        $user = @$userController->loginSubmit();

        $this->assertEquals($user['variables']['error'], 'You have not provided a username and/or password.');
    }

    public function testLoginNoUsername() {
        $testPostData = [
            'login' => [
                'username' => '',
                'password' => 'testing123',
            ],
            'submit' => true
        ];

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        $user = @$userController->loginSubmit();

        $this->assertEquals($user['variables']['error'], 'You have not provided a username and/or password.');
    }

    public function testLoginUserDoesNotExist() {
        $testPostData = [
            'login' => [
                'username' => 'phpunit',
                'password' => 'testing123',
            ],
            'submit' => true
        ];

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        $user = @$userController->loginSubmit();

        $this->assertEquals($user['variables']['error'], 'A user with the username provided does not exist.');
    }

    public function testLoginIncorrectPassword() {
        $testPostData = [
            'login' => [
                'username' => 'owner',
                'password' => 'unittesting456',
            ],
            'submit' => true
        ];

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        $user = @$userController->loginSubmit();

        $this->assertEquals($user['variables']['error'], 'The password provided is incorrect.');
    }

    public function testLoginInactive() {
        $testPostData = [
            'login' => [
                'username' => 'inactive',
                'password' => 'testing123',
            ],
            'submit' => true
        ];

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        $user = @$userController->loginSubmit();

        $this->assertEquals($user['variables']['error'], 'Your account has not been activated. Please contact an administrator.');
    }

    
    public function testLoginOwner() {
        $testPostData = [
            'login' => [
                'username' => 'owner',
                'password' => 'testing123',
            ],
            'submit' => true
        ];

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        @$userController->loginSubmit();

        $this->assertTrue(isset($_SESSION['loggedIn']) && isset($_SESSION['isOwner']));
    }

    public function testLoginAdmin() {
        $testPostData = [
            'login' => [
                'username' => 'admin',
                'password' => 'testing123',
            ],
            'submit' => true
        ];

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        @$userController->loginSubmit();

        $this->assertTrue(isset($_SESSION['loggedIn']) && isset($_SESSION['isAdmin']));
    }

    
    public function testLoginEmployee() {
        $testPostData = [
            'login' => [
                'username' => 'employee',
                'password' => 'testing123',
            ],
            'submit' => true
        ];

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        @$userController->loginSubmit();

        $this->assertTrue(isset($_SESSION['loggedIn']) && isset($_SESSION['isEmployee']));
    }

    public function testLoginClient() {
        $testPostData = [
            'login' => [
                'username' => 'client',
                'password' => 'testing123',
            ],
            'submit' => true
        ];

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
    
        @$userController->loginSubmit();

        $this->assertTrue(isset($_SESSION['loggedIn']) && isset($_SESSION['isClient']));
    }

    // Log out Test
    public function testLogout() {

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], []);
        $userController->logout();

        $this->assertFalse(isset($_SESSION['loggedIn']));
    }

    // Log in Form Tests
    public function testShowLoginForm() {
        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], []);
        $form = @$userController->loginForm();

        $this->assertNotEmpty($form); 
    }

    public function testShowLoginFormWhileLoggedIn() {
        $testPostData = [
            'login' => [
                'username' => 'owner',
                'password' => 'testing123',
            ],
            'submit' => true
        ];

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
        @$userController->loginSubmit();

        $form = @$userController->loginForm();

        $this->assertEmpty($form); 

        $userController->logout();
    }

    // Edit Form Tests
    public function testShowEditUserFormAsOwner() {
        $testGetData = [
            'id' => 1
        ];      

        $testPostData = [
            'login' => [
                'username' => 'owner',
                'password' => 'testing123',
            ],
            'submit' => true
        ];

        $userController = new \JobSite\Controllers\UserController($this->usersTable, $testGetData, $testPostData);
        @$userController->loginSubmit();

        $form = $userController->editUserForm();

        $this->assertNotEmpty($form); 
        
        $userController->logout();
    }

    public function testShowEditUserFormAsClient() {
        $testGetData = [
            'id' => 1
        ];      

        $testPostData = [
            'login' => [
                'username' => 'client',
                'password' => 'testing123',
            ],
            'submit' => true
        ];

        $userController = new \JobSite\Controllers\UserController($this->usersTable, $testGetData, $testPostData);
        @$userController->loginSubmit();

        $form = @$userController->editUserForm();

        $this->assertEmpty($form);        

        $userController->logout();
    }

    public function testShowEditUserWithoutGet() {
        $testPostData = [
            'login' => [
                'username' => 'owner',
                'password' => 'testing123',
            ],
            'submit' => true
        ];

        $userController = new \JobSite\Controllers\UserController($this->usersTable, [], $testPostData);
        @$userController->loginSubmit();

        $form = $userController->editUserForm();

        $this->assertNotEmpty($form); 
        
        $userController->logout();
    }
}
?>