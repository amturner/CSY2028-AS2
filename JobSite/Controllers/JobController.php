<?php
namespace JobSite\Controllers;
class JobController {
    private $jobsTable;
    private $applicantsTable;
    private $locationsTable;
    private $categoriesTable;
    private $get;
    private $post;

    public function __construct(\CSY2028\DatabaseTable $jobsTable, \CSY2028\DatabaseTable $applicantsTable, \CSY2028\DatabaseTable $locationsTable, \CSY2028\DatabaseTable $categoriesTable, $get, $post) {
        $this->jobsTable = $jobsTable;
        $this->applicantsTable = $applicantsTable;
        $this->locationsTable = $locationsTable;
        $this->categoriesTable = $categoriesTable;
        $this->get = $get;
        $this->post = $post;
    }

    public function applySubmit() {
        $categories = $this->categoriesTable->retrieveAllRecords();

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

            if ($_FILES['cv']['error'] != 4) {
                $parts = explode('.', $_FILES['cv']['name']);
                $extension = end($parts);
                $fileName = uniqid() . '.' . $extension;
                move_uploaded_file($_FILES['cv']['tmp_name'], 'cvs/' . $fileName);
            
                if ($_FILES['cv']['error'] == 1)
                    $errors[] = 'There was an error uploading your CV.';
            }
            else {
                $errors[] = 'You have not attached a CV to your application.';
            }

            if (count($errors) == 0) {
                $values = [
                    'name' => htmlspecialchars(strip_tags($this->post['apply']['name']), ENT_QUOTES, 'UTF-8'),
                    'email' => $this->post['apply']['email'],
                    'details' => htmlspecialchars(strip_tags($this->post['apply']['details']), ENT_QUOTES, 'UTF-8'),
                    'jobId' => $jobId,
                    'cv' => $fileName

                ];

                $this->applicantsTable->save($values);

                return [
                    'layout' => 'sidebarlayout.html.php',
                    'template' => 'main/applysuccess.html.php',
                    'variables' => [
                        'categories' => $categories,
                        'title' => $jobTitle
                    ],
                    'title' => 'Jobs - Apply'
                ];
            }
            else {
                return [
                    'layout' => 'sidebarlayout.html.php',
                    'template' => 'main/apply.html.php',
                    'variables' => [
                        'categories' => $categories,
                        'title' => $jobTitle,
                        'jobId' => $jobId,
                        'errors' => $errors
                    ],
                    'title' => 'Jobs - Apply'
                ]; 
            }
        }
    }

    public function applyForm() {
        $categories = $this->categoriesTable->retrieveAllRecords();

        if (isset($this->get['id'])) {
            if (!empty($this->jobsTable->retrieveRecord('id', $this->get['id'])[0])) {
                $job = $this->jobsTable->retrieveRecord('id', $this->get['id'])[0];

                $title = 'Apply';

                $variables = [
                    'categories' => $categories,
                    'title' => htmlspecialchars(strip_tags($job->title), ENT_QUOTES, 'UTF-8'),
                    'jobId' => $job->id
                ];
            }
            else {
                $title = 'Apply - Job Not Found';

                $variables = [
                    'categories' => $categories
                ];
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

    public function editJobSubmit() {
        if (isset($this->post['submit'])) {
            $categories = $this->categoriesTable->retrieveAllRecords();
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

                return [
                    'layout' => 'sidebarlayout.html.php',
                    'template' => 'admin/editjobsuccess.html.php',
                    'variables' => [
                        'categories' => $categories,
                        'locations' => $locations,
                        'title' => htmlspecialchars(strip_tags($this->post['job']['title']), ENT_QUOTES, 'UTF-8')
                    ],
                    'title' => 'Admin Panel - ' . $pageName
                ];
            }
            // Display the edit form with any generated errors.
            else {
                if (isset($this->get['id']))
                    $pageName = 'Edit Job';
                else
                    $pageName = 'Add Job';

                return [
                    'layout' => 'sidebarlayout.html.php',
                    'template' => 'admin/editjob.html.php',
                    'variables' => [
                        'categories' => $categories,
                        'locations' => $locations,
                        'errors' => $errors,
                        'job' => $job
                    ],
                    'title' => 'Admin Panel - ' . $pageName
                ];
            }
        }
    }

    public function editJobForm() {
        $categories = $this->categoriesTable->retrieveAllRecords();
        $locations = $this->locationsTable->retrieveAllRecords('town', 'ASC');

        if (isset($this->get['id'])) {
            $job = $this->jobsTable->retrieveRecord('id', $this->get['id'])[0];
            if (isset($_SESSION['isOwner']) || isset($_SESSION['isAdmin']) || isset($_SESSION['isEmployee'])) {
                
                $variables = [
                    'categories' => $categories,
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
                'categories' => $categories,
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

    public function deleteJob() {
        $this->jobsTable->deleteRecordById($this->post['id']);
        $this->applicantsTable->deleteRecord('jobId', $this->post['id']);

        header('Location: /admin/jobs');
    }

    public function showJob() {
        $categories = $this->categoriesTable->retrieveAllRecords();
        $job = $this->jobsTable->retrieveRecord('id', $this->get['id'])[0];

        if (empty($job))
            header('Location: /jobs');

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'main/job.html.php',
            'variables' => [
                'categories' => $categories,
                'job' => $job
            ],
            'title' => 'Jobs - Job Details'
        ];
    }

    public function listJobs() {
        $categories = $this->categoriesTable->retrieveAllRecords();
        $locations = $this->locationsTable->retrieveAllRecords('town', 'ASC');
        $allJobs = $this->jobsTable->retrieveAllRecords();

        foreach ($allJobs as $job) {
            if (date('Y-m-d') > $job->closingDate) {
                $values = [
                    'id' => $job->id,
                    'active' => 0
                ];

                $this->jobsTable->save($values);
            }
        }

        if (isset($this->get['category']) && $this->get['category'] != '') {
            $categoriesByFilter = $this->categoriesTable->retrieveRecord('name', ucwords(urldecode($this->get['category'])));

            if (!empty($categoriesByFilter)) {
                $category = $categoriesByFilter[0];
                
                if (isset($this->get['location']) && $this->get['location'] != 'All') {
                    if (!empty($this->locationsTable->retrieveRecord('town', ucwords(urldecode($this->get['location'])))[0])) {
                        $locationByFilter = $this->locationsTable->retrieveRecord('town', ucwords(urldecode($this->get['location'])))[0];
                        $jobs = $this->jobsTable->retrieveRecord('categoryId', $category->id);                

                        $filteredLocations = [];
                        $filteredJobs = [];

                        foreach ($jobs as $job) {
                            foreach ($locations as $location) {
                                if ($job->locationId == $location->id && $job->active == 1) {
                                    $filteredLocations[] = $location->town;
                                }
                            }
                        }

                        foreach ($jobs as $job)
                            if ($job->locationId == $locationByFilter->id && $job->active == 1)
                                $filteredJobs[] = $job;

                        $locationsNoId = [];

                        foreach ($filteredLocations as $filteredLocation)
                            $locationsNoId[] = $filteredLocation;

                        $locations = array_unique($locationsNoId);
                        $jobs = $filteredJobs;

                        $categoryName = $category->name;
                        $locationTown = $locationByFilter->town;

                        $variables = [
                            'categoryName' => $categoryName,
                            'locationTown' => $locationTown,
                            'jobs' => $jobs,
                            'categories' => $categories,
                            'locations' => $locations
                        ];
                        
                        $title = '';
                    }
                    else {
                        $categoryName = $category->name;

                        $variables = [
                            'categoryName' => $categoryName,
                            'categories' => $categories,
                            'locations' => $locations
                        ];

                        $title = ' - Location Not Found';
                    }
                }
                else {
                    $jobs = $this->jobsTable->retrieveRecord('categoryId', $category->id);
                    $categoryName = $category->name;

                    $filteredLocations = [];
                    $filteredJobs = [];

                    foreach ($jobs as $job) {
                        foreach ($locations as $location) {
                            if ($job->locationId == $location->id && $job->active == 1) {
                                $filteredLocations[] = $location->town;
                            }
                        }
                    }

                    foreach ($jobs as $job) {
                        foreach ($locations as $location) {
                            if ($job->locationId == $location->id && $job->active == 1) {
                                $filteredJobs[] = $job;
                            }
                        }
                    }

                    $locations = array_unique($filteredLocations);
                    $jobs = $filteredJobs;

                    $variables = [
                        'categoryName' => $categoryName,
                        'jobs' => $jobs,
                        'categories' => $categories,
                        'locations' => $locations
                    ];

                    $title = '';
                }
            }
            else {
                $variables = [
                    'categories' => $categories,
                    'locations' => $locations
                ];

                $title = ' - Category Not Found';
            }
        }
        else {
            $variables = [
                'categories' => $categories,
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

    public function listJobsAdmin($parameters) {
        $categories = $this->categoriesTable->retrieveAllRecords();
        $allJobs = $this->jobsTable->retrieveAllRecords();

        if (empty($parameters))
            header('Location: /admin/jobs/active');

        foreach ($allJobs as $job) {
            if (date('Y-m-d') > $job->closingDate) {
                $values = [
                    'id' => $job->id,
                    'active' => 0
                ];

                $this->jobsTable->save($values);
            }
        }

        if (isset($this->get['category']) && $this->get['category'] != 'All') {
            if (!empty($this->categoriesTable->retrieveRecord('name', ucwords(urldecode($this->get['category']))))) {
                $categoriesByFilter = $this->categoriesTable->retrieveRecord('name', ucwords(urldecode($this->get['category'])));
                $categoryByFilter = $categoriesByFilter[0];

                if (isset($_SESSION['isOwner']) || isset($_SESSION['isAdmin']) || isset($_SESSION['isEmployee'])) {
                    $jobs = $this->jobsTable->retrieveAllRecords();
                }
                else {
                    $jobs = $this->jobsTable->retrieveRecord('userId', $_SESSION['id']);
                }

                $filteredJobs = [];
                $filteredCategories = [];

                if ($parameters[0] == 'active') {
                    $title = 'Jobs';

                    foreach ($jobs as $job)
                    if ($job->categoryId == $categoryByFilter->id && $job->active == 1)
                        $filteredJobs[] = $job;

                    foreach ($jobs as $job) {
                        foreach ($categories as $category) {
                            if ($job->categoryId == $category->id && $job->active == 1) {
                                $filteredCategories[] = $category->name;
                            }
                        }
                    }
                }
                elseif ($parameters[0] == 'archived') {
                    $title = 'Archived Jobs';

                    foreach ($jobs as $job)
                    if ($job->categoryId == $categoryByFilter->id && $job->active == 0)
                        $filteredJobs[] = $job;

                    foreach ($jobs as $job) {
                        foreach ($categories as $category) {
                            if ($job->categoryId == $category->id && $job->active == 0) {
                                $filteredCategories[] = $category->name;
                            }
                        }
                    }                    
                }

                $categoryChoices = array_unique($filteredCategories);
                $categoryName = $categoryByFilter->name;

                $variables = [
                    'categories' => $categories,
                    'title' => $title,
                    'categoryChoices' => $categoryChoices,
                    'categoryName' => htmlspecialchars(strip_tags($categoryName), ENT_QUOTES, 'UTF-8'),
                    'jobs' => $filteredJobs,
                    'parameters' => $parameters
                ];
            }
            else {
                $filteredCategories = [];

                if ($parameters[0] == 'active') {
                    $title = 'Jobs';

                    foreach ($allJobs as $job) {
                        foreach ($categories as $category) {
                            if ($job->categoryId == $category->id && $job->active == 1) {
                                $filteredCategories[] = $category->name;
                            }
                        }
                    }
                }
                elseif ($parameters[0] == 'archived') {
                    $title = 'Archived Jobs';

                    foreach ($allJobs as $job) {
                        foreach ($categories as $category) {
                            if ($job->categoryId == $category->id && $job->active == 0) {
                                $filteredCategories[] = $category->name;
                            }
                        }
                    }
                }

                $categoryChoices = array_unique($filteredCategories);

                $variables = [
                    'categories' => $categories,
                    'title' => $title,
                    'categoryChoices' => $categoryChoices,
                    'parameters' => $parameters
                ];
            }
        }
        else {
            if (isset($_SESSION['isOwner']) || isset($_SESSION['isAdmin']) || isset($_SESSION['isEmployee']))
                $jobs = $this->jobsTable->retrieveAllRecords();
            else
                $jobs = $this->jobsTable->retrieveRecord('userId', $_SESSION['id']);

            $filteredJobs = [];
            $filteredCategories = [];

            if ($parameters[0] == 'active') {
                $title = 'Jobs';

                foreach ($jobs as $job)
                if ($job->active == 1)
                    $filteredJobs[] = $job;

                foreach ($jobs as $job) {
                    foreach ($categories as $category) {
                        if ($job->categoryId == $category->id && $job->active == 1) {
                            $filteredCategories[] = $category->name;
                        }
                    }
                }
            }
            elseif ($parameters[0] == 'archived') {
                $title = 'Archived Jobs';

                foreach ($jobs as $job)
                if ($job->active == 0)
                    $filteredJobs[] = $job;

                foreach ($jobs as $job) {
                    foreach ($categories as $category) {
                        if ($job->categoryId == $category->id && $job->active == 0) {
                            $filteredCategories[] = $category->name;
                        }
                    }
                }
            }

            $categoryChoices = array_unique($filteredCategories);

            $variables = [
                'categories' => $categories,
                'title' => $title,
                'categoryChoices' => $categoryChoices,
                'jobs' => $filteredJobs,
                'parameters' => $parameters
            ];
        }

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'admin/jobs.html.php',
            'variables' => $variables,
            'title' => 'Admin Panel - Jobs - ' . $title
        ];
    }

    public function listApplicants() {
        $categories = $this->categoriesTable->retrieveAllRecords();
        $jobs = $this->jobsTable->retrieveRecord('id', $this->get['id']);

        if (!empty($jobs)) {
            $job = $jobs[0];

            if ($job->userId == $_SESSION['id']) {
                $variables = [
                    'categories' => $categories,
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