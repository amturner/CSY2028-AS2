<?php
require 'JobSite/Entities/Enquiry.php';
class EnquiryEntityTest extends \PHPUnit\Framework\TestCase { 
    private $enquiriesTable;

    public function setUp() {
        require 'dbConnection.php';
        $this->pdo = $pdo;
        $this->enquiriesTable = new \CSY2028\DatabaseTable($this->pdo, 'enquiries', 'id', '\JobSite\Entities\Enquiry');
    }
    
    /* Enquiry Entity Tests */
    /* Get Full Name Tests */
    // No Order
    public function testGetFullNameNoOrder() {
        $enquiry = $this->enquiriesTable->retrieveRecord('id', 1)[0];

        $this->assertEmpty($enquiry->getFullName(''));
    }

    // Firstname
    public function testGetFullNameOrderedFirstname() {
        $enquiry = $this->enquiriesTable->retrieveRecord('id', 1)[0];

        $this->assertEquals($enquiry->getFullName('firstname'), 'John Doe');
    }

    // Surname
    public function testGetFullNameOrderedSurname() {
        $enquiry = $this->enquiriesTable->retrieveRecord('id', 1)[0];

        $this->assertEquals($enquiry->getFullName('surname'), 'Doe, John');
    }
}
?>