<?php
require 'JobSite/Controllers/JobSiteController.php';
class JobSiteControllerTest extends \PHPUnit\Framework\TestCase {
    private $jobsTable;
    private $categoriesTable;

    public function setUp() {
        require 'dbConnection.php';
        $this->pdo = $pdo;
        $this->jobsTable = new \CSY2028\DatabaseTable($this->pdo, 'job', 'id');
        $this->categoriesTable = new \CSY2028\DatabaseTable($this->pdo, 'category', 'id');
    }

    /* Job Site Controller Tests */
    // Home Page Test
    public function testHome() {
        $jobSiteController = new \JobSite\Controllers\JobSiteController($this->jobsTable);
        
        $this->assertNotEmpty($jobSiteController->home([$this->categoriesTable->retrieveAllRecords()]));
    }

    // About Page Test
    public function testAbout() {
        $jobSiteController = new \JobSite\Controllers\JobSiteController($this->jobsTable);

        $this->assertNotEmpty($jobSiteController->about([$this->categoriesTable->retrieveAllRecords()]));
    }

    // Home Page Test
    public function testFAQ() {
        $jobSiteController = new \JobSite\Controllers\JobSiteController($this->jobsTable);

        $this->assertNotEmpty($jobSiteController->faq());
    }
}
?>