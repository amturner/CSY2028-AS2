<?php
namespace JobSite\Controllers;
class JobController {
    private $jobsTable;
    private $applicantsTable;
    private $locationsTable;
    private $categoriesTable;

    public function __construct(\CSY2028\DatabaseTable $jobsTable, \CSY2028\DatabaseTable $applicantsTable, \CSY2028\DatabaseTable $locationsTable, \CSY2028\DatabaseTable $categoriesTable) {
        $this->jobsTable = $jobsTable;
        $this->applicantsTable = $applicantsTable;
        $this->locationsTable = $locationsTable;
        $this->categoriesTable = $categoriesTable;
    }

    public function applySubmit() {
        $categories = $this->categoriesTable->retrieveAllRecords();

        if (isset($_POST['submit'])) {
            $job = $this->jobsTable->retrieveRecord('id', $_GET['id'])[0];
            $jobId = $job->id;
            $jobTitle = htmlspecialchars(strip_tags($job->title), ENT_QUOTES, 'UTF-8');

            $errors = [];

            if ($_POST['apply']['name'] == '')
                $errors[] = 'Your name cannot be blank.';

            if ($_POST['apply']['email'] != '') {
                if (!filter_var($_POST['apply']['email'], FILTER_VALIDATE_EMAIL))
                    $errors[] = 'Your email address is invalid.';
            }
            else
                $errors[] = 'Your email address cannot be blank.';

            if ($_POST['apply']['details'] == '')
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
                    'name' => htmlspecialchars(strip_tags($_POST['apply']['name']), ENT_QUOTES, 'UTF-8'),
                    'email' => $_POST['apply']['email'],
                    'details' => htmlspecialchars(strip_tags($_POST['apply']['details']), ENT_QUOTES, 'UTF-8'),
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

        if (isset($_GET['id'])) {
            $job = $this->jobsTable->retrieveRecord('id', $_GET['id'])[0];

            if (isset($job))
                $jobTitle = htmlspecialchars(strip_tags($job->title), ENT_QUOTES, 'UTF-8');
            else
                $jobTitle = '';    
        }
        else {
            $jobTitle = '';
        }

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'main/apply.html.php',
            'variables' => [
                'categories' => $categories,
                'title' => $jobTitle,
                'jobId' => $job->id
            ],
            'title' => 'Jobs - Apply'
        ];
    }

    public function editJobSubmit() {
        if (isset($_POST['submit'])) {
            $categories = $this->categoriesTable->retrieveAllRecords();

            if (isset($_GET['id']))
                $job = $this->jobsTable->retrieveRecord('id', $_GET['id'])[0];
            else
                $job = '';

            $errors = [];

            // Validate user input
            if ($_POST['job']['title'] == '')
                $errors[] = 'The title cannot be blank.';
            
            if ($_POST['job']['description'] == '')
                $errors[] = 'The description cannot be blank.';

            if ($_POST['job']['salary'] == '')
                $errors[] = 'The salary cannot be blank.';

            if ($_POST['job']['closingDate'] != null) {
                if ($_POST['job']['closingDate'] < date('Y-m-d'))
                    $errors[] = 'The closing date cannnot be before the current date.';
            }
            else
                $errors[] = 'The closing date cannot be blank.';

            if (count($errors) == 0) {
                if (isset($_GET['id']))
                    $pageName = 'Job Updated';
                else
                    $pageName = 'Job Added';

                $this->jobsTable->save($_POST['job']);

                return [
                    'layout' => 'sidebarlayout.html.php',
                    'template' => 'admin/editjobsuccess.html.php',
                    'variables' => [
                        'categories' => $categories,
                        'title' => htmlspecialchars(strip_tags($_POST['job']['title']), ENT_QUOTES, 'UTF-8')
                    ],
                    'title' => 'Admin Panel - ' . $pageName
                ];
            }
            // Display the edit form with any generated errors.
            else {
                if (isset($_GET['id']))
                    $pageName = 'Edit Job';
                else
                    $pageName = 'Add Job';

                return [
                    'layout' => 'sidebarlayout.html.php',
                    'template' => 'admin/editjob.html.php',
                    'variables' => [
                        'categories' => $categories,
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

        if (isset($_GET['id'])) {
            $job = $this->jobsTable->retrieveRecord('id', $_GET['id'])[0];
            if ($job->userId == $_SESSION['id']) {
                return [
                    'layout' => 'sidebarlayout.html.php',
                    'template' => 'admin/editjob.html.php',
                    'variables' => [
                        'categories' => $categories,
                        'locations' => $locations,
                        'job' => $job
                    ],
                    'title' => 'Admin Panel - Edit Job'
                ];
            } 
            else
                header('Location: /admin/jobs');  
        }
        else {
            return [
                'layout' => 'sidebarlayout.html.php',
                'template' => 'admin/editjob.html.php',
                'variables' => [
                    'categories' => $categories,
                    'locations' => $locations,
                    'towns' => $towns
                ],
                'title' => 'Admin Panel - Add Job'
            ];
        }
    }

    public function listJobs() {
        $categories = $this->categoriesTable->retrieveAllRecords();
        $locations = $this->locationsTable->retrieveAllRecords('town', 'ASC');
        
        if (isset($_GET['category']) && $_GET['category'] != '') {
            $categoriesByFilter = $this->categoriesTable->retrieveRecord('name', ucwords(urldecode($_GET['category'])));

            if (!empty($categoriesByFilter)) {
                $category = $categoriesByFilter[0];
                
                if (isset($_GET['location']) && $_GET['location'] != 'All') {
                    if (!empty($this->locationsTable->retrieveRecord('town', $_GET['location'])[0])) {
                        $locationByFilter = $this->locationsTable->retrieveRecord('town', $_GET['location'])[0];
                        $jobs = $this->jobsTable->retrieveRecord('categoryId', $category->id);                

                        $filteredLocations = [];
                        $filteredJobs = [];

                        foreach ($jobs as $job) {
                            foreach ($locations as $location) {
                                if ($job->locationId == $location->id) {
                                    $filteredLocations[] = $location->town;
                                }
                            }
                        }

                        foreach ($jobs as $job)
                            if ($job->locationId == $locationByFilter->id)
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

                    foreach ($jobs as $job) {
                        foreach ($locations as $location) {
                            if ($job->locationId == $location->id) {
                                $filteredLocations[] = $location->town;
                            }
                        }
                    }

                    $locations = array_unique($filteredLocations);

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

    public function listApplicants() {
        $categories = $this->categoriesTable->retrieveAllRecords();
        $jobs = $this->jobsTable->retrieveRecord('id', $_GET['id']);

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