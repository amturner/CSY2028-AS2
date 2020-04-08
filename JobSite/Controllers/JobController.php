<?php
namespace JobSite\Controllers;
class JobController {
    private $jobsTable;
    private $applicantsTable;
    private $locationsTable;
    private $categoriesTable;
    private $categories;
    private $get;
    private $post;
    private $files;

    public function __construct(\CSY2028\DatabaseTable $jobsTable, \CSY2028\DatabaseTable $applicantsTable, \CSY2028\DatabaseTable $locationsTable, \CSY2028\DatabaseTable $categoriesTable, $get, $post, $files) {
        $this->jobsTable = $jobsTable;
        $this->applicantsTable = $applicantsTable;
        $this->locationsTable = $locationsTable;
        $this->categoriesTable = $categoriesTable;
        $this->categories = $this->categoriesTable->retrieveAllRecords();
        $this->get = $get;
        $this->post = $post;
        $this->files = $files;
    }

    // Function for submitting the job application form.
    public function applySubmit() {
        // Check if the user has actually submitted the form.
        if (isset($this->post['submit'])) {
            $job = $this->jobsTable->retrieveRecord('id', $this->get['id'])[0];
            $jobId = $job->id;
            $jobTitle = htmlspecialchars(strip_tags($job->title), ENT_QUOTES, 'UTF-8');

            $errors = [];

            if ($this->post['apply']['name'] == '')
                $errors[] = 'Your name cannot be blank.';

            if ($this->post['apply']['email'] != '') {
                if (!filter_var($this->post['apply']['email'], FILTER_VALIDATE_EMAIL))
                    $errors[] = 'Your email address is invalid.';
            }
            else
                $errors[] = 'Your email address cannot be blank.';

            if ($this->post['apply']['details'] == '')
                $errors[] = 'Your cover letter cannot be blank.';

            if ($this->files['cv']['error'] != 4) {
                $parts = explode('.', $this->files['cv']['name']);
                $extension = end($parts);
                $fileName = uniqid() . '.' . $extension;
                move_uploaded_file($this->files['cv']['tmp_name'], 'cvs/' . $fileName);
            
                if ($this->files['cv']['error'] == 1)
                    $errors[] = 'There was an error uploading your CV.';
            }
            else {
                $errors[] = 'You have not attached a CV to your application.';
            }

            // Check if no errors have been generated. If so, create a new job application.
            if (count($errors) == 0) {
                $values = [
                    'name' => htmlspecialchars(strip_tags($this->post['apply']['name']), ENT_QUOTES, 'UTF-8'),
                    'email' => $this->post['apply']['email'],
                    'details' => htmlspecialchars(strip_tags($this->post['apply']['details']), ENT_QUOTES, 'UTF-8'),
                    'jobId' => $jobId,
                    'cv' => $fileName
                ];

                $this->applicantsTable->save($values);

                $template = 'main/applysuccess.html.php';

                $variables = [
                    'title' => $jobTitle
                ];
                
                $title = 'Jobs - Apply';
            }
            // Display the job application form with any generated errors.
            else {
                $template = 'main/apply.html.php';

                $variables = [
                    'title' => $jobTitle,
                    'jobId' => $jobId,
                    'errors' => $errors
                ];

                $title = 'Jobs - Apply';
            }
        }

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => $template,
            'variables' => $variables,
            'title' => $title
        ]; 
    }

    // Function for displaying the job application form.
    public function applyForm() {
        // Check if $_GET['id'] has been set. If so,
        // display an application form for the specified
        // job.
        if (isset($this->get['id'])) {
            // Check if a job exists with the ID specified. If so,
            // continue with displaying the form.
            if (!empty($this->jobsTable->retrieveRecord('id', $this->get['id'])[0])) {
                $job = $this->jobsTable->retrieveRecord('id', $this->get['id'])[0];

                // Check if the specified job is currntly active. If so,
                // display the form.
                if ($job->active == 1) {
                    $title = 'Apply';

                    $variables = [
                        'title' => htmlspecialchars(strip_tags($job->title), ENT_QUOTES, 'UTF-8'),
                        'jobId' => $job->id
                    ];
                }
                else {
                    $title = 'Apply - Job Not Found';
    
                    $variables = [];
                }          
            }
            else {
                $title = 'Apply - Job Not Found';

                $variables = [];
            }          
        }
        else {
            header('Location: /jobs');
        }

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'main/apply.html.php',
            'variables' => $variables,
            'title' => 'Jobs - ' . $title
        ];
    }

    // Function for submitting the edit job form.
    public function editJobSubmit() {
        if (isset($this->post['submit'])) {
            $locations = $this->locationsTable->retrieveAllRecords('town', 'ASC');

            if (isset($this->get['id']))
                $job = $this->jobsTable->retrieveRecord('id', $this->get['id'])[0];
            else
                $job = '';

            $errors = [];

            // Validate user input
            if ($this->post['job']['title'] == '')
                $errors[] = 'The title cannot be blank.';
            
            if ($this->post['job']['description'] == '')
                $errors[] = 'The description cannot be blank.';

            if ($this->post['job']['salary'] == '')
                $errors[] = 'The salary cannot be blank.';

            if ($this->post['job']['closingDate'] != null) {
                if ($this->post['job']['closingDate'] < date('Y-m-d'))
                    $errors[] = 'The closing date cannnot be before the current date.';
            }
            else
                $errors[] = 'The closing date cannot be blank.';

            if (count($errors) == 0) {
                if (isset($this->get['id']))
                    $pageName = 'Job Updated';
                else
                    $pageName = 'Job Added';

                if ($job != '')
                    if ($job->active == 0)
                            $this->post['job']['active'] = 1;

                $this->jobsTable->save($this->post['job']);

                $template = 'admin/editjobsuccess.html.php';

                $variables = [
                    'title' => htmlspecialchars(strip_tags($this->post['job']['title']), ENT_QUOTES, 'UTF-8')
                ];
            }
            // Display the edit form with any generated errors.
            else {
                if (isset($this->get['id']))
                    $pageName = 'Edit Job';
                else
                    $pageName = 'Add Job';

                $template = 'admin/editjob.html.php';

                $variables = [
                    'categories' => $this->categories,
                    'locations' => $locations,
                    'errors' => $errors,
                    'job' => $job
                ];
            }
        }

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => $template,
            'variables' => $variables,
            'title' => 'Admin Panel - ' . $pageName
        ];
    }

    // Function for displaying the edit job form.
    public function editJobForm() {
        $locations = $this->locationsTable->retrieveAllRecords('town', 'ASC');

        if (isset($this->get['id'])) {
            $job = $this->jobsTable->retrieveRecord('id', $this->get['id'])[0];
            if (isset($_SESSION['isOwner']) || isset($_SESSION['isAdmin']) || isset($_SESSION['isEmployee']) || $job->userId == $_SESSION['id']) {
                
                $variables = [
                    'categories' => $this->categories,
                    'locations' => $locations,
                    'job' => $job
                ];
                
                $title = 'Edit Job';
            } 
            else
                header('Location: /admin/jobs');  
        }
        else {
            $variables = [
                'categories' => $this->categories,
                'locations' => $locations
            ];

            $title = 'Add Job';
        }

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'admin/editjob.html.php',
            'variables' => $variables,
            'title' => 'Admin Panel - ' . $title
        ];
    }

    // Function for deleting a job and any associated applicants from the database.
    public function deleteJob() {
        $this->jobsTable->deleteRecordById($this->post['id']);
        $this->applicantsTable->deleteRecord('jobId', $this->post['id']);

        header('Location: /admin/jobs');
    }

    // Function for showing the details of an individual job listing.
    public function showJob() {
        $job = $this->jobsTable->retrieveRecord('id', $this->get['id'])[0];

        // Check if $job is empty or if $job as an active value equal to 0.
        // If so, redirect the user back to /jobs.
        if (empty($job) || $job->active == 0)
            header('Location: /jobs');

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'main/job.html.php',
            'variables' => [
                'job' => $job
            ],
            'title' => 'Jobs - Job Details'
        ];
    }

    // Function for displaying a list of jobs.
    public function listJobs($parameters) {
        $locations = $this->locationsTable->retrieveAllRecords('town', 'ASC');
        $allJobs = $this->jobsTable->retrieveAllRecords();

        // Loop through the jobs array and make any jobs where the 
        // current date is past the closing date inactive.
        foreach ($allJobs as $job) {
            if (date('Y-m-d') > $job->closingDate) {
                $values = [
                    'id' => $job->id,
                    'active' => 0
                ];

                $this->jobsTable->save($values);
            }
        }

        // Check if $_GET['category'] has been set and is not equal to nothing.
        // If so, proceed with listing users.
        if (isset($this->get['category']) && $this->get['category'] != '') {
            $categoriesByFilter = $this->categoriesTable->retrieveRecord('name', ucwords(urldecode($this->get['category'])));

            // Check if $categoriesByFilter is not empty to confirm if the specified category exists.
            if (!empty($categoriesByFilter)) {
                $category = $categoriesByFilter[0];
                
                // Check if $_GET['location'] has been set and is not equal to All.
                // If so, proceed with listing ALL users.
                if (isset($this->get['location']) && $this->get['location'] != 'All') {
                    // Check if the a town exists with the town specified. If so, continue with listing out jobs.
                    if (!empty($this->locationsTable->retrieveRecord('town', ucwords(urldecode($this->get['location'])))[0])) {
                        $locationByFilter = $this->locationsTable->retrieveRecord('town', ucwords(urldecode($this->get['location'])))[0];
                        $jobs = $this->jobsTable->retrieveRecord('categoryId', $category->id);                

                        $filteredLocations = [];
                        $filteredJobs = [];

                        // Filter out all towns that have no jobs and 
                        // store them in the $filteredLocations array.
                        foreach ($jobs as $job) {
                            foreach ($locations as $location) {
                                if ($job->locationId == $location->id && $job->active == 1) {
                                    $filteredLocations[] = $location->town;
                                }
                            }
                        }

                        // Filter out all jobs that aren't in the specified
                        // category and are inactive and store them in the
                        // $filteredJobs array.
                        foreach ($jobs as $job)
                            if ($job->locationId == $locationByFilter->id && $job->active == 1)
                                $filteredJobs[] = $job;

                        $locationsNoId = [];

                        // Store all locations without their IDs in the
                        // $locationsNoId array.
                        foreach ($filteredLocations as $filteredLocation)
                            $locationsNoId[] = $filteredLocation;

                        // Store all unique locations in $locations and
                        // filtered jobs in $jobs.
                        $locations = array_unique($locationsNoId);
                        $jobs = $filteredJobs;

                        $categoryName = $category->name;
                        $locationTown = $locationByFilter->town;

                        $variables = [
                            'categoryName' => $categoryName,
                            'locationTown' => $locationTown,
                            'jobs' => $jobs,
                            'locations' => $locations
                        ];
                        
                        $title = '';
                    }
                    else {
                        $categoryName = $category->name;

                        $variables = [
                            'categoryName' => $categoryName,
                            'locations' => $locations
                        ];

                        $title = ' - Location Not Found';
                    }
                }
                // List jobs by the specified category.
                else {
                    $jobs = $this->jobsTable->retrieveRecord('categoryId', $category->id);
                    $categoryName = $category->name;

                    $filteredLocations = [];
                    $filteredJobs = [];

                    // Filter out all towns that have no jobs and 
                    // store them in the $filteredLocations array.
                    foreach ($jobs as $job) {
                        foreach ($locations as $location) {
                            if ($job->locationId == $location->id && $job->active == 1) {
                                $filteredLocations[] = $location->town;
                            }
                        }
                    }

                    // Filter out all jobs that aren't in the specified
                    // category and are inactive and store them in the
                    // $filteredJobs array.
                    foreach ($jobs as $job) {
                        foreach ($locations as $location) {
                            if ($job->locationId == $location->id && $job->active == 1) {
                                $filteredJobs[] = $job;
                            }
                        }
                    }

                    // Store all unique locations in $locations and
                    // filtered jobs in $jobs.
                    $locations = array_unique($filteredLocations);
                    $jobs = $filteredJobs;

                    $variables = [
                        'categoryName' => $categoryName,
                        'jobs' => $jobs,
                        'locations' => $locations
                    ];

                    $title = '';
                }
            }
            else {
                $variables = [
                    'locations' => $locations
                ];

                $title = ' - Category Not Found';
            }
        }
        else {
            $variables = [
                'categories' => $parameters[0],
                'locations' => $locations
            ];
            
            $title = '';
        }

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'main/jobs.html.php',
            'variables' => $variables,
            'title' => 'Jobs' . $title
        ];
    }

    // Function for listing jobs in the admin panel.
    public function listJobsAdmin($parameters) {
        $allJobs = $this->jobsTable->retrieveAllRecords();

        // Loop through the jobs array and make any jobs where the 
        // current date is past the closing date inactive.
        foreach ($allJobs as $job) {
            if (date('Y-m-d') > $job->closingDate) {
                $values = [
                    'id' => $job->id,
                    'active' => 0
                ];

                $this->jobsTable->save($values);
            }
        }

        // Check if $parameters isn't empty. If so, continue with listing out jobs.
        if (!empty($parameters)) {
            // Check if $_GET['category'] has been set and is not equal to nothing.
            // If so, proceed with listing users.
            if (isset($this->get['category']) && $this->get['category'] != 'All') {
                // Check if the a category exists with the name specified. If so, continue with listing out jobs.
                if (!empty($this->categoriesTable->retrieveRecord('name', ucwords(urldecode($this->get['category']))))) {
                    $categoriesByFilter = $this->categoriesTable->retrieveRecord('name', ucwords(urldecode($this->get['category'])));
                    $categoryByFilter = $categoriesByFilter[0];

                    // Check if the logged in user is an Owner, Admin or Employee. If so, retrieve all jobs
                    // from the database.
                    if (isset($_SESSION['isOwner']) || isset($_SESSION['isAdmin']) || isset($_SESSION['isEmployee'])) {
                        $jobs = $this->jobsTable->retrieveAllRecords();
                    }
                    // Retrieve all jobs created by the user ID specified.
                    else {
                        $jobs = $this->jobsTable->retrieveRecord('userId', $_SESSION['id']);
                    }

                    $filteredJobs = [];
                    $filteredCategories = [];

                    // If the parameter at index 0 is equal to 'active', display all active jobs.
                    if ($parameters[0] == 'active') {
                        $title = 'Jobs';

                        // Filter out all jobs which aren't active.
                        foreach ($jobs as $job)
                        if ($job->categoryId == $categoryByFilter->id && $job->active == 1)
                            $filteredJobs[] = $job;

                        // Filter out all categories that aren't associated with a job in the specified
                        // category.
                        foreach ($jobs as $job) {
                            foreach ($this->categories as $category) {
                                if ($job->categoryId == $category->id && $job->active == 1) {
                                    $filteredCategories[] = $category->name;
                                }
                            }
                        }
                    }
                    // If the parameter at index 0 is equal to 'archived', display all archived jobs.
                    elseif ($parameters[0] == 'archived') {
                        $title = 'Archived Jobs';

                        // Filter out all jobs which aren't active.
                        foreach ($jobs as $job)
                            if ($job->categoryId == $categoryByFilter->id && $job->active == 0)
                                $filteredJobs[] = $job;

                        // Filter out all categories that aren't associated with a job in the specified
                        // category.
                        foreach ($jobs as $job) {
                            foreach ($this->categories as $category) {
                                if ($job->categoryId == $category->id && $job->active == 0) {
                                    $filteredCategories[] = $category->name;
                                }
                            }
                        }                    
                    }

                    // Store all unique categories from $filteredCategories in $categoryChoices.
                    $categoryChoices = array_unique($filteredCategories);
                    $categoryName = $categoryByFilter->name;

                    $variables = [
                        'title' => $title,
                        'categoryChoices' => $categoryChoices,
                        'categoryName' => htmlspecialchars(strip_tags($categoryName), ENT_QUOTES, 'UTF-8'),
                        'jobs' => $filteredJobs,
                        'parameters' => $parameters
                    ];
                }
                else {
                    $filteredCategories = [];

                    // If the parameter at index 0 is equal to 'active', display all active jobs.
                    if ($parameters[0] == 'active') {
                        $title = 'Jobs';

                        // Filter out all categories that aren't associated with a job in the specified
                        // category.
                        foreach ($allJobs as $job) {
                            foreach ($this->categories as $category) {
                                if ($job->categoryId == $category->id && $job->active == 1) {
                                    $filteredCategories[] = $category->name;
                                }
                            }
                        }
                    }
                    // If the parameter at index 0 is equal to 'archived', display all archived jobs.
                    elseif ($parameters[0] == 'archived') {
                        $title = 'Archived Jobs';

                        // Filter out all categories that aren't associated with a job in the specified
                        // category.
                        foreach ($allJobs as $job) {
                            foreach ($this->categories as $category) {
                                if ($job->categoryId == $category->id && $job->active == 0) {
                                    $filteredCategories[] = $category->name;
                                }
                            }
                        }
                    }

                    // Store all unique categories from $filteredCategories in $categoryChoices.
                    $categoryChoices = array_unique($filteredCategories);

                    $variables = [
                        'title' => $title,
                        'categoryChoices' => $categoryChoices,
                        'parameters' => $parameters
                    ];
                }
            }
            else {
                // Check if the logged in user is an Owner, Admin or Employee. If so, retrieve all jobs
                // from the database.
                if (isset($_SESSION['isOwner']) || isset($_SESSION['isAdmin']) || isset($_SESSION['isEmployee']))
                    $jobs = $this->jobsTable->retrieveAllRecords();
                // Retrieve all jobs created by the user ID specified.
                else
                    $jobs = $this->jobsTable->retrieveRecord('userId', $_SESSION['id']);

                $filteredJobs = [];
                $filteredCategories = [];

                // If the parameter at index 0 is equal to 'active', display all active jobs.
                if ($parameters[0] == 'active') {
                    $title = 'Jobs';

                    // Filter out all jobs that aren't active.
                    foreach ($jobs as $job)
                    if ($job->active == 1)
                        $filteredJobs[] = $job;

                    // Filter out all categories that aren't associated with a job in the specified
                    // category.
                    foreach ($jobs as $job) {
                        foreach ($this->categories as $category) {
                            if ($job->categoryId == $category->id && $job->active == 1) {
                                $filteredCategories[] = $category->name;
                            }
                        }
                    }
                }
                // If the parameter at index 0 is equal to 'archived', display all archived jobs.
                elseif ($parameters[0] == 'archived') {
                    $title = 'Archived Jobs';

                    // Filter out all jobs that are active.
                    foreach ($jobs as $job)
                    if ($job->active == 0)
                        $filteredJobs[] = $job;

                    // Filter out all categories that aren't associated with a job in the specified
                    // category.
                    foreach ($jobs as $job) {
                        foreach ($this->categories as $category) {
                            if ($job->categoryId == $category->id && $job->active == 0) {
                                $filteredCategories[] = $category->name;
                            }
                        }
                    }
                }

                // Store all unique categories from $filteredCategories in $categoryChoices.               
                $categoryChoices = array_unique($filteredCategories);

                $variables = [
                    'title' => $title,
                    'categoryChoices' => $categoryChoices,
                    'jobs' => $filteredJobs,
                    'parameters' => $parameters
                ];
            }
        }
        else {
            header('Location: /admin/jobs/active');
        }

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'admin/jobs.html.php',
            'variables' => $variables,
            'title' => 'Admin Panel - Jobs - ' . $title
        ];
    }

    // Function for listing out all applicants for a specified job.
    public function listApplicants() {
        $jobs = $this->jobsTable->retrieveRecord('id', $this->get['id']);

        // Check if any jobs have been returned. If so, display the applicants.
        if (!empty($jobs)) {
            $job = $jobs[0];

            // Check if the current user can view the associated job listing. If so, display the applicants. 
            if (isset($_SESSION['isOwner']) || isset($_SESSION['isAdmin']) || isset($_SESSION['isEmployee']) || $job->userId == $_SESSION['id']) {
                $variables = [
                    'title' => $job->title,
                    'applicants' => $job->listApplicants()                
                ];
            }
            else
                header('Location: /admin/jobs');
        }
        else
            header('Location: /admin/jobs');

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'admin/applicants.html.php',
            'variables' => $variables,
            'title' => 'Admin Panel - Applicants'
        ];
    }
}
?>