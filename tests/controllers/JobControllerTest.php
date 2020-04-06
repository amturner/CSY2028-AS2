<?php
require 'JobSite/Controllers/JobController.php';
class JobControllerTest extends \PHPUnit\Framework\TestCase { 
    private $jobsTable;
    private $applicantsTable;
    private $locationsTable;
    private $categoriesTable;

    public function setUp() {
        require 'dbConnection.php';
        $this->pdo = $pdo;
        //$this->jobsTable = new \CSY2028\DatabaseTable($this->pdo, 'job', 'id');
        $this->applicantsTable = new \CSY2028\DatabaseTable($this->pdo, 'applicants', 'id');
        $this->locationsTable = new \CSY2028\DatabaseTable($this->pdo, 'locations', 'id');
        $this->categoriesTable = new \CSY2028\DatabaseTable($this->pdo, 'category', 'id');
        $this->jobsTable = new \CSY2028\DatabaseTable($pdo, 'job', 'id', '\JobSite\Entities\Job', [$this->locationsTable, $this->applicantsTable, $this->categoriesTable]);
    }

    /* Job Controller Tests */
    /* List Jobs Tests */
    // List Jobs 
    public function testListJobsWithoutCategoryFilter() {
        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], []);
        $jobs = $jobController->listJobs([$this->categoriesTable->retrieveAllRecords()]);

        $this->assertNotEmpty($jobs['variables']['categories']);
    }

    public function testListJobsWithInvalidCategory() {
        $testGetData = [
            'category' => 'Plumbing'
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, []);
        $jobs = $jobController->listJobs([]);

        $this->assertFalse(isset($jobs['variables']['categoryName']));
    }

    public function testListJobsWithValidCategory() {
        $testGetData = [
            'category' => 'Sales'
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, []);
        $jobs = $jobController->listJobs([]);

        $this->assertNotEmpty($jobs['variables']['categoryName']);
    }

    public function testListJobsWithCategoryFilterAndNoLocation() {
        $testGetData = [
            'category' => 'Sales',
            'location' => ''
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, []);
        $jobs = $jobController->listJobs([]);

        $this->assertNotEmpty($jobs['variables']['categoryName']);
    }

    public function testListJobsWithCategoryFilterAndLocation() {
        $testGetData = [
            'category' => 'Sales',
            'location' => 'Northampton'
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, []);
        $jobs = $jobController->listJobs([]);

        $this->assertNotEmpty($jobs['variables']['categoryName']);
    }

    public function testListJobsWithCategoryFilterAndAllLocations() {
        $testGetData = [
            'category' => 'Sales',
            'location' => 'All'
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, []);
        $jobs = $jobController->listJobs([]);

        $this->assertNotEmpty($jobs['variables']['categoryName']);
    }
    
    // Admin List Jobs
    public function testAdminListActiveJobsWithoutCategoryFilter() {
        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], []);

        $_SESSION['id'] = 1;

        $jobs = $jobController->listJobsAdmin(['active']);

        $this->assertEquals($jobs['variables']['title'], 'Jobs');

        unset($_SESSION['id']);
    } 

    public function testAdminListArchivedJobsWithoutCategoryFilter() {
        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], []);

        $_SESSION['id'] = 1;

        $jobs = $jobController->listJobsAdmin(['archived']);

        $this->assertEquals($jobs['variables']['title'], 'Archived Jobs');

        unset($_SESSION['id']);
    } 

    public function testAdminListJobsAsOwnerWithoutCategoryFilter() {
        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], []);

        $_SESSION['isOwner'] = true;

        $jobs = $jobController->listJobsAdmin(['active']);

        $this->assertEquals($jobs['variables']['title'], 'Jobs');

        unset($_SESSION['isOwner']);
    } 

    // List Applicants Tests
    public function testListApplicantsNonExistantJob() {
        $testGetData = [
            'id' => 99
        ];

        $_SESSION['isOwner'] = true;

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, []);
        $job = @$jobController->listApplicants();

        $this->assertEmpty($job['variables']);

        unset($_SESSION['isOwner']);
    }

    public function testListApplicants() {
        $testGetData = [
            'id' => 1
        ];

        $_SESSION['id'] = 1;

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, []);
        $job = @$jobController->listApplicants();

        $this->assertNotEmpty($job['variables']);

        unset($_SESSION['id']);
    }

    /* Form Tests */
    // Apply Form Tests
    public function testShowApplyFormWithoutId() {
        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], []);
        $form = @$jobController->applyForm();

        $this->assertEmpty($form['variables']['title']);
    }

    public function testShowApplyFormWithInvalidId() {
        $testGetData = [
            'id' => 99
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, []);
        $form = $jobController->applyForm();

        $this->assertEmpty($form['variables']);
    }

    public function testShowApplyFormWithValidId() {
        $testGetData = [
            'id' => 1
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, []);
        $form = $jobController->applyForm();

        $this->assertEquals($form['variables']['title'], 'First level tech support');
    }

    // Edit Job Form Tests
    public function testShowEditJobFormWithoutId() {
        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], []);
        $form = @$jobController->editJobForm();

        $this->assertFalse(isset($form['variables']['job']));
    }

    public function testShowEditJobFormWithIdAsClient() {
        $testGetData = [
            'id' => 1
        ];

        $_SESSION['isClient'] = true;

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, []);
        $form = @$jobController->editJobForm();

        $this->assertFalse(isset($form['variables']['job']));

        unset($_SESSION['isClient']);
    }

    public function testShowEditJobFormWithIdAsOwner() {
        $testGetData = [
            'id' => 1
        ];

        $_SESSION['isOwner'] = true;

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, []);
        $form = @$jobController->editJobForm();

        $this->assertTrue(isset($form['variables']['job']));

        unset($_SESSION['isOwner']);
    }
}
?>