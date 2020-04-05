<?php
require 'CSY2028/DatabaseTable.php';
require 'JobSite/Controllers/JobSiteController.php';
require 'JobSite/Controllers/AdminController.php';
require 'JobSite/Controllers/JobController.php';
require 'JobSite/Controllers/UserController.php';
require 'JobSite/Controllers/CategoryController.php';
require 'JobSite/Controllers/EnquiryController.php';

class ControllersTest extends \PHPUnit\Framework\TestCase {    
    /* User Controller Tests */
    /*
    public function testRegister() {
        require 'dbConnection.php';
        $testData = [
            'user' => [
                ''
            ],
            'submit' => true
        ]; 
    }
    */

    public function testLoginNoDetails() {
        require 'dbConnection.php';
        $testData = [
            'login' => [
                'username' => '',
                'password' => '',
            ],
            'submit' => true
        ];

        $categoriesTable = new \CSY2028\DatabaseTable($pdo, 'category', 'id');
        $usersTable = new \CSY2028\DatabaseTable($pdo, 'users', 'id');

        $userController = new \JobSite\Controllers\UserController($usersTable, $categoriesTable, [], $testData);
    
        @$userController->loginSubmit();

        $this->assertFalse(isset($_SESSION['loggedIn']));
    }

    public function testLoginNoPassword() {
        require 'dbConnection.php';
        $testData = [
            'login' => [
                'username' => 'testuser',
                'password' => '',
            ],
            'submit' => true
        ];

        $categoriesTable = new \CSY2028\DatabaseTable($pdo, 'category', 'id');
        $usersTable = new \CSY2028\DatabaseTable($pdo, 'users', 'id');

        $userController = new \JobSite\Controllers\UserController($usersTable, $categoriesTable, [], $testData);
    
        @$userController->loginSubmit();

        $this->assertFalse(isset($_SESSION['loggedIn']));
    }

    public function testLoginNoUsername() {
        require 'dbConnection.php';
        $testData = [
            'login' => [
                'username' => '',
                'password' => 'testing123',
            ],
            'submit' => true
        ];

        $categoriesTable = new \CSY2028\DatabaseTable($pdo, 'category', 'id');
        $usersTable = new \CSY2028\DatabaseTable($pdo, 'users', 'id');

        $userController = new \JobSite\Controllers\UserController($usersTable, $categoriesTable, [], $testData);
    
        @$userController->loginSubmit();

        $this->assertFalse(isset($_SESSION['loggedIn']));
    }

    public function testLoginSuccessful() {
        require 'dbConnection.php';
        $testData = [
            'login' => [
                'username' => 'testuser',
                'password' => 'testing123',
            ],
            'submit' => true
        ];

        $categoriesTable = new \CSY2028\DatabaseTable($pdo, 'category', 'id');
        $usersTable = new \CSY2028\DatabaseTable($pdo, 'users', 'id');

        $userController = new \JobSite\Controllers\UserController($usersTable, $categoriesTable, [], $testData);
    
        @$userController->loginSubmit();

        $this->assertTrue(isset($_SESSION['loggedIn']));
    }
    
    public function testLogout() {
        require 'dbConnection.php';
        $usersTable = new \CSY2028\DatabaseTable($pdo, 'users', 'id');
        $categoriesTable = new \CSY2028\DatabaseTable($pdo, 'category', 'id');

        $userController = new \JobSite\Controllers\UserController($usersTable, $categoriesTable, [], []);

        $userController->logout();

        $this->assertFalse(isset($_SESSION['loggedIn']));
    }
}
?>