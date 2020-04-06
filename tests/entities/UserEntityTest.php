<?php
require 'JobSite/Entities/User.php';
class UserEntityTest extends \PHPUnit\Framework\TestCase { 
    private $usersTable;

    public function setUp() {
        require 'dbConnection.php';
        $this->pdo = $pdo;
        $this->usersTable = new \CSY2028\DatabaseTable($this->pdo, 'users', 'id', '\JobSite\Entities\User');
    }
    
    /* User Entity Tests */
    /* Get Full Name Tests */
    // No Order
    public function testGetFullNameNoOrder() {
        $user = $this->usersTable->retrieveRecord('id', 1)[0];

        $this->assertEmpty($user->getFullName(''));
    }

    // Firstname
    public function testGetFullNameOrderedFirstname() {
        $user = $this->usersTable->retrieveRecord('id', 1)[0];

        $this->assertEquals($user->getFullName('firstname'), 'Owner User');
    }

    // Surname
    public function testGetFullNameOrderedSurname() {
        $user = $this->usersTable->retrieveRecord('id', 1)[0];

        $this->assertEquals($user->getFullName('surname'), 'User, Owner');
    }
}
?>