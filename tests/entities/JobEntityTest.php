<?php
require 'JobSite/Entities/Job.php';
class JobEntityTest extends \PHPUnit\Framework\TestCase { 
    private $locationsTable;
    private $applicantsTable;
    private $jobsTable;
    private $categoriesTable;

    public function setUp() {
        require 'dbConnection.php';
        $this->pdo = $pdo;
        $this->locationsTable = new \CSY2028\DatabaseTable($this->pdo, 'locations', 'id');
        $this->applicantsTable = new \CSY2028\DatabaseTable($this->pdo, 'applicants', 'id');
        $this->jobsTable = new \CSY2028\DatabaseTable($this->pdo, 'job', 'id');
        $this->categoriesTable = new \CSY2028\DatabaseTable($this->pdo, 'category', 'id');

        $this->categoriesTable  = new \CSY2028\DatabaseTable($pdo, 'category', 'id', '\JobSite\Entities\Category', [$this->categoriesTable , $this->jobsTable]);
        $this->jobsTable = new \CSY2028\DatabaseTable($pdo, 'job', 'id', '\JobSite\Entities\Job', [$this->locationsTable, $this->applicantsTable, $this->categoriesTable]);
    }
    
    /* Job Entity Tests */
    // Get Category Name Test
    public function testGetCategoryName() {
        $job = $this->jobsTable->retrieveRecord('id', 1)[0];

        $this->assertEquals($job->getCategoryName(), 'IT');
    }

    public function testGetFullLocation() {
        $job = $this->jobsTable->retrieveRecord('id', 1)[0];

        $this->assertEquals($job->getFullLocation(), 'Northampton, Northamptonshire, England');
    }

    public function testGetTown() {
        $job = $this->jobsTable->retrieveRecord('id', 1)[0];

        $this->assertEquals($job->getTown(), 'Northampton');
    }

    public function testGetClosingDate() {
        $job = $this->jobsTable->retrieveRecord('id', 1)[0];

        $this->assertEquals($job->getClosingDate(), '31/07/2020');
    }

    public function testListApplicants() {
        $job = $this->jobsTable->retrieveRecord('id', 1)[0];

        $this->assertNotEmpty($job->listApplicants());
    }

    public function testGetApplicantsCount() {
        $job = $this->jobsTable->retrieveRecord('id', 1)[0];

        $this->assertEquals($job->getApplicantsCount(), 2);
    }
}
?>