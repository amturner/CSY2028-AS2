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
        $this->jobsTable = new \CSY2028\DatabaseTable($this->pdo, 'job', 'id', '\JobSite\Entities\Job', [$this->locationsTable, $this->applicantsTable, $this->categoriesTable]);
    }

    /* Job Controller Tests */
    /* Edit Job Submit Tests */
    // Create Job Tests
    public function testCreateJobNoValues() {
        $testPostData = [
            'job' => [
                'id' => '',
                'title' => '',
                'description' => '',
                'locationId' => '',
                'salary' => '',
                'categoryId' => '',
                'closingDate' => '',
                'userId' => 1
            ],
            'submit' => true
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], $testPostData, []);
        $job = $jobController->editJobSubmit();

        $this->assertCount(4, $job['variables']['errors']);
    }

    public function testCreateJobOnlyTitle() {
        $testPostData = [
            'job' => [
                'id' => '',
                'title' => 'Sous Chef',
                'description' => '',
                'locationId' => '',
                'salary' => '',
                'categoryId' => '',
                'closingDate' => '',
                'userId' => 1
            ],
            'submit' => true
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], $testPostData, []);
        $job = $jobController->editJobSubmit();

        $this->assertCount(3, $job['variables']['errors']);
    }

    public function testCreateJobOnlyDescription() {
        $testPostData = [
            'job' => [
                'id' => '',
                'title' => '',
                'description' => 'This is a job opening for an experienced Sous Chef.',
                'locationId' => '',
                'salary' => '',
                'categoryId' => '',
                'closingDate' => '',
                'userId' => 1
            ],
            'submit' => true
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], $testPostData, []);
        $job = $jobController->editJobSubmit();

        $this->assertCount(3, $job['variables']['errors']);
    }

    public function testCreateJobOnlySalary() {
        $testPostData = [
            'job' => [
                'id' => '',
                'title' => '',
                'description' => '',
                'locationId' => '',
                'salary' => '£4000',
                'categoryId' => '',
                'closingDate' => '',
                'userId' => 1
            ],
            'submit' => true
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], $testPostData, []);
        $job = $jobController->editJobSubmit();

        $this->assertCount(3, $job['variables']['errors']);
    }

    public function testCreateJobOnlyClosingDate() {
        $testPostData = [
            'job' => [
                'id' => '',
                'title' => '',
                'description' => '',
                'locationId' => '',
                'salary' => '',
                'categoryId' => '',
                'closingDate' => '2020-06-20',
                'userId' => 1
            ],
            'submit' => true
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], $testPostData, []);
        $job = $jobController->editJobSubmit();

        $this->assertCount(3, $job['variables']['errors']);
    }

    public function testCreateJobTitleDescription() {
        $testPostData = [
            'job' => [
                'id' => '',
                'title' => 'Sous Chef',
                'description' => 'This is a job opening for an experienced Sous Chef.',
                'locationId' => '',
                'salary' => '',
                'categoryId' => '',
                'closingDate' => '',
                'userId' => 1
            ],
            'submit' => true
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], $testPostData, []);
        $job = $jobController->editJobSubmit();

        $this->assertCount(2, $job['variables']['errors']);
    }

    public function testCreateJobTitleDescriptionSalary() {
        $testPostData = [
            'job' => [
                'id' => '',
                'title' => 'Sous Chef',
                'description' => 'This is a job opening for an experienced Sous Chef.',
                'locationId' => '',
                'salary' => '£4000',
                'categoryId' => '',
                'closingDate' => '',
                'userId' => 1
            ],
            'submit' => true
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], $testPostData, []);
        $job = $jobController->editJobSubmit();

        $this->assertCount(1, $job['variables']['errors']);
    }

    public function testCreateJobInvalidClosingDate() {
        $testPostData = [
            'job' => [
                'id' => '',
                'title' => 'Sous Chef',
                'description' => 'This is a job opening for an experienced Sous Chef.',
                'locationId' => '850',
                'salary' => '£4000',
                'categoryId' => '3',
                'closingDate' => '2020-02-20',
                'userId' => 1
            ],
            'submit' => true
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], $testPostData, []);
        $job = $jobController->editJobSubmit();

        $this->assertCount(1, $job['variables']['errors']);
    }

    public function testCreateJobSuccessful() {
        $testPostData = [
            'job' => [
                'id' => '',
                'title' => 'Sous Chef',
                'description' => 'This is a job opening for an experienced Sous Chef.',
                'locationId' => '850',
                'salary' => '£4000',
                'categoryId' => '3',
                'closingDate' => '2020-06-20',
                'userId' => 1,
                'active' => 0
            ],
            'submit' => true
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], $testPostData, []);
        $jobController->editJobSubmit();

        $job = $this->pdo->query('SELECT title FROM job WHERE title = "Sous Chef"')->fetch();

        $this->assertNotEmpty($job);
    }

    // Edit Job Test
    public function testEditJobError() {
        $testGetData = [
            'id' => 11
        ];

        $testPostData = [
            'job' => [
                'id' => '11',
                'title' => '',
                'description' => 'This is a job opening for an experienced Sous Chef for a company based in Northampton.',
                'locationId' => '850',
                'salary' => '£4000',
                'categoryId' => '3',
                'closingDate' => '2020-06-20',
                'userId' => 1
            ],
            'submit' => true
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, $testPostData, []);
        $job = $jobController->editJobSubmit();

        $this->assertCount(1, $job['variables']['errors']);
    }

    public function testEditJob() {
        $testGetData = [
            'id' => 11
        ];

        $testPostData = [
            'job' => [
                'id' => '11',
                'title' => 'Sous Chef',
                'description' => 'This is a job opening for an experienced Sous Chef for a company based in Northampton.',
                'locationId' => '850',
                'salary' => '£4000',
                'categoryId' => '3',
                'closingDate' => '2020-06-20',
                'userId' => 1
            ],
            'submit' => true
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, $testPostData, []);
        $jobController->editJobSubmit();

        $job = $this->pdo->query('SELECT id, description FROM job WHERE id = 11')->fetch();

        $this->assertEquals($job['description'], 'This is a job opening for an experienced Sous Chef for a company based in Northampton.');
    }

    // Apply To Job Tests
    public function testApplyNoDetails() {
        $testGetData = [
            'id' => 11
        ];

        $testPostData = [
            'apply' => [
                'name' => '',
                'email' => '',
                'details' => '',
                'jobId' => 11
            ],
            'submit' => true
        ];

        $testFilesData = [
            'cv' => [
                'error' => 4
            ]
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, $testPostData, $testFilesData);
        $application = $jobController->applySubmit();

        $this->assertCount(4, $application['variables']['errors']);
    }

    public function testApplyOnlyName() {
        $testGetData = [
            'id' => 11
        ];

        $testPostData = [
            'apply' => [
                'name' => 'Jim Bob',
                'email' => '',
                'details' => '',
                'jobId' => 11
            ],
            'submit' => true
        ];

        $testFilesData = [
            'cv' => [
                'name' => '',
                'tmp_name' => '',
                'error' => 4
            ]
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, $testPostData, $testFilesData);
        $application = $jobController->applySubmit();

        $this->assertCount(3, $application['variables']['errors']);
    }

    public function testApplyOnlyEmail() {
        $testGetData = [
            'id' => 11
        ];

        $testPostData = [
            'apply' => [
                'name' => '',
                'email' => 'jim@bob.com',
                'details' => '',
                'jobId' => 11
            ],
            'submit' => true
        ];

        $testFilesData = [
            'cv' => [
                'name' => '',
                'tmp_name' => '',
                'error' => 4
            ]
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, $testPostData, $testFilesData);
        $application = $jobController->applySubmit();

        $this->assertCount(3, $application['variables']['errors']);
    }

    public function testApplyOnlyCoverLetter() {
        $testGetData = [
            'id' => 11
        ];

        $testPostData = [
            'apply' => [
                'name' => '',
                'email' => '',
                'details' => 'This is my cover letter!',
                'jobId' => 11
            ],
            'submit' => true
        ];

        $testFilesData = [
            'cv' => [
                'name' => '',
                'tmp_name' => '',
                'error' => 4
            ]
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, $testPostData, $testFilesData);
        $application = $jobController->applySubmit();

        $this->assertCount(3, $application['variables']['errors']);
    }

    public function testApplyOnlyCV() {
        $testGetData = [
            'id' => 11
        ];

        $testPostData = [
            'apply' => [
                'name' => '',
                'email' => '',
                'details' => '',
                'jobId' => 11
            ],
            'submit' => true
        ];

        $testFilesData = [
            'cv' => [
                'name' => 'cv.php',
                'tmp_name' => 'cv128.php',
                'error' => 2
            ]
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, $testPostData, $testFilesData);
        $application = $jobController->applySubmit();

        $this->assertCount(3, $application['variables']['errors']);
    }

    public function testApplyOnlyCVError() {
        $testGetData = [
            'id' => 11
        ];

        $testPostData = [
            'apply' => [
                'name' => '',
                'email' => '',
                'details' => '',
                'jobId' => 11
            ],
            'submit' => true
        ];

        $testFilesData = [
            'cv' => [
                'name' => 'cv.php',
                'tmp_name' => 'cv128.php',
                'error' => 1
            ]
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, $testPostData, $testFilesData);
        $application = $jobController->applySubmit();

        $this->assertCount(4, $application['variables']['errors']);
    }

    public function testApplyAllDetails() {
        $testGetData = [
            'id' => 11
        ];

        $testPostData = [
            'apply' => [
                'name' => 'Jim Bob',
                'email' => 'jim@bob.com',
                'details' => 'This is my cover letter!',
                'jobId' => 11
            ],
            'submit' => true
        ];

        $testFilesData = [
            'cv' => [
                'name' => 'cv.php',
                'tmp_name' => 'cv128.php',
                'error' => 2
            ]
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, $testPostData, $testFilesData);
        $jobController->applySubmit();

        $application = $this->pdo->query('SELECT name FROM applicants WHERE name = "Jim Bob" AND jobId = 11;')->fetch();

        $this->assertEquals($application['name'], 'Jim Bob');
    }

    /* List Jobs Tests */
    // List Jobs 
    public function testListJobsWithoutCategoryFilter() {
        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], [], []);
        $jobs = $jobController->listJobs([$this->categoriesTable->retrieveAllRecords()]);

        $this->assertNotEmpty($jobs['variables']['categories']);
    }

    public function testListJobsWithInvalidCategory() {
        $testGetData = [
            'category' => 'Plumbing'
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, [], []);
        $jobs = $jobController->listJobs([]);

        $this->assertFalse(isset($jobs['variables']['categoryName']));
    }

    public function testListJobsWithValidCategory() {
        $testGetData = [
            'category' => 'Sales'
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, [], []);
        $jobs = $jobController->listJobs([]);

        $this->assertNotEmpty($jobs['variables']['categoryName']);
    }

    public function testListJobsWithCategoryFilterAndNoLocation() {
        $testGetData = [
            'category' => 'Sales',
            'location' => ''
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, [], []);
        $jobs = $jobController->listJobs([]);

        $this->assertNotEmpty($jobs['variables']['categoryName']);
    }

    public function testListJobsWithCategoryFilterAndLocation() {
        $testGetData = [
            'category' => 'Sales',
            'location' => 'Northampton'
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, [], []);
        $jobs = $jobController->listJobs([]);

        $this->assertNotEmpty($jobs['variables']['categoryName']);
    }

    public function testListJobsWithCategoryFilterAndAllLocations() {
        $testGetData = [
            'category' => 'Sales',
            'location' => 'All'
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, [], []);
        $jobs = $jobController->listJobs([]);

        $this->assertNotEmpty($jobs['variables']['categoryName']);
    }
    
    // Admin List Jobs
    public function testAdminListJobsWithoutParams() {
        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], [], []);

        $jobs = @$jobController->listJobsAdmin([]);

        $this->assertEmpty($jobs['variables']);
    } 


    public function testAdminListActiveJobsWithoutCategoryFilter() {
        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], [], []);

        $_SESSION['id'] = 1;

        $jobs = @$jobController->listJobsAdmin(['active']);

        $this->assertEquals($jobs['variables']['title'], 'Jobs');

        unset($_SESSION['id']);
    } 

    public function testAdminListJobsAsOwnerWithoutCategoryFilter() {
        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], [], []);

        $_SESSION['isOwner'] = true;

        $jobs = @$jobController->listJobsAdmin(['active']);

        $this->assertEquals($jobs['variables']['title'], 'Jobs');

        unset($_SESSION['isOwner']);
    } 

    public function testAdminListArchivedJobsWithoutCategoryFilter() {
        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], [], []);

        $_SESSION['id'] = 1;

        $jobs = @$jobController->listJobsAdmin(['archived']);

        $this->assertEquals($jobs['variables']['title'], 'Archived Jobs');

        unset($_SESSION['id']);
    } 

    public function testAdminListActiveJobsWithInvalidCategoryFilter() {
        $testGetData = [
            'category' => 'Cooking'
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, [], []);

        $jobs = @$jobController->listJobsAdmin(['active']);

        $this->assertEquals($jobs['variables']['title'], 'Jobs');
    } 

    public function testAdminListArchivedJobsWithInvalidCategoryFilter() {
        $testGetData = [
            'category' => 'Cooking'
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, [], []);

        $jobs = @$jobController->listJobsAdmin(['archived']);

        $this->assertEquals($jobs['variables']['title'], 'Archived Jobs');
    } 

    public function testAdminListActiveJobsWithCategoryFilter() {
        $testGetData = [
            'category' => 'Sales'
        ];

        $_SESSION['id'] = 1;

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, [], []);

        $jobs = @$jobController->listJobsAdmin(['active']);

        $this->assertEquals($jobs['variables']['title'], 'Jobs');

        unset($_SESSION['id']);
    } 

    public function testAdminListArchivedJobsWithCategoryFilter() {
        $testGetData = [
            'category' => 'Sales'
        ];

        $_SESSION['id'] = 1;

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, [], []);

        $jobs = @$jobController->listJobsAdmin(['archived']);

        $this->assertEquals($jobs['variables']['title'], 'Archived Jobs');

        unset($_SESSION['id']);
    } 

    
    public function testAdminListActiveJobsWithCategoryFilterAsOwner() {
        $testGetData = [
            'category' => 'Sales'
        ];

        $_SESSION['isOwner'] = true;

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, [], []);

        $jobs = @$jobController->listJobsAdmin(['active']);

        $this->assertEquals($jobs['variables']['title'], 'Jobs');

        unset($_SESSION['isOwner']);
    } 

    // Delete Job Test
    public function testDeleteJob () {
        $testPostData = [
            'id' => 11
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], $testPostData, []);
        @$jobController->deleteJob();

        $job = $this->pdo->query('SELECT id FROM job WHERE id = 11;')->fetch();
        $application = $this->pdo->query('SELECT jobId FROM applicants WHERE jobId = 4;')->fetch();

        $this->assertTrue(empty($job) && empty($application));

        $this->pdo->query('ALTER TABLE job AUTO_INCREMENT = 11;');
        $this->pdo->query('ALTER TABLE applicants AUTO_INCREMENT = 3;');
    }

    // Show Job Test
    public function testShowJobInvalid() {
        $testGetData = [
            'id' => 99
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, [], []);
        $job = @$jobController->showJob();

        $this->assertEmpty($job['variables']['job']);
    }

    public function testShowJobValid() {
        $testGetData = [
            'id' => 1
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, [], []);
        $job = @$jobController->showJob();

        $this->assertNotEmpty($job['variables']['job']);
    }

    // List Applicants Tests
    public function testListApplicantsNonExistantJob() {
        $testGetData = [
            'id' => 99
        ];

        $_SESSION['isOwner'] = true;

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, [], []);
        $job = @$jobController->listApplicants();

        $this->assertEmpty($job['variables']);

        unset($_SESSION['isOwner']);
    }

    public function testListApplicants() {
        $testGetData = [
            'id' => 1
        ];

        $_SESSION['id'] = 1;

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, [], []);
        $job = @$jobController->listApplicants();

        $this->assertNotEmpty($job['variables']);

        unset($_SESSION['id']);
    }

    /* Form Tests */
    // Apply Form Tests
    public function testShowApplyFormWithoutId() {
        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], [], []);
        $form = @$jobController->applyForm();

        $this->assertEmpty($form['variables']['title']);
    }

    public function testShowApplyFormWithInvalidId() {
        $testGetData = [
            'id' => 99
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, [], []);
        $form = $jobController->applyForm();

        $this->assertEmpty($form['variables']);
    }

    public function testShowApplyFormWithInactiveJob() {
        $testGetData = [
            'id' => 4
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, [], []);
        $form = $jobController->applyForm();

        $this->assertEmpty($form['variables']);
    }

    public function testShowApplyFormWithValidId() {
        $testGetData = [
            'id' => 1
        ];

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, [], []);
        $form = $jobController->applyForm();

        $this->assertEquals($form['variables']['title'], 'First level tech support');
    }

    // Edit Job Form Tests
    public function testShowEditJobFormWithoutId() {
        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, [], [], []);
        $form = @$jobController->editJobForm();

        $this->assertFalse(isset($form['variables']['job']));
    }

    public function testShowEditJobFormWithIdAsClient() {
        $testGetData = [
            'id' => 1
        ];

        $_SESSION['isClient'] = true;

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, [], []);
        $form = @$jobController->editJobForm();

        $this->assertFalse(isset($form['variables']['job']));

        unset($_SESSION['isClient']);
    }

    public function testShowEditJobFormWithIdAsOwner() {
        $testGetData = [
            'id' => 1
        ];

        $_SESSION['isOwner'] = true;

        $jobController = new \JobSite\Controllers\JobController($this->jobsTable, $this->applicantsTable, $this->locationsTable, $this->categoriesTable, $testGetData, [], []);
        $form = @$jobController->editJobForm();

        $this->assertTrue(isset($form['variables']['job']));

        unset($_SESSION['isOwner']);
    }
}
?>