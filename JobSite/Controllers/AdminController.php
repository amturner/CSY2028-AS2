<?php
namespace JobSite\Controllers;
class AdminController {
    private $usersTable;
    private $categoriesTable;
    private $jobsTable;
    private $applicantsTable;

    public function __construct(\CSY2028\DatabaseTable $usersTable, \CSY2028\DatabaseTable $categoriesTable, \CSY2028\DatabaseTable $jobsTable, \CSY2028\DatabaseTable $applicantsTable) {
        $this->usersTable = $usersTable;
        $this->categoriesTable = $categoriesTable;
        $this->jobsTable = $jobsTable;
        $this->applicantsTable = $applicantsTable;
    }

    public function home() {
        $categories = $this->categoriesTable->retrieveAllRecords();

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'admin/home.html.php',
            'variables' => [
                'categories' => $categories
            ],
            'title' => 'Admin Panel - Home'
        ];
    }

    public function jobs($parameters) {
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

        if (isset($_GET['category']) && $_GET['category'] != 'All') {
            if (!empty($this->categoriesTable->retrieveRecord('name', ucwords(urldecode($_GET['category']))))) {
                $categoriesByFilter = $this->categoriesTable->retrieveRecord('name', ucwords(urldecode($_GET['category'])));
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

    public function categories() {
        $categories = $this->categoriesTable->retrieveAllRecords();

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'admin/categories.html.php',
            'variables' => [
                'categories' => $categories
            ],
            'title' => 'Admin Panel - Categories'
        ];
    }

    public function users() {
        $categories = $this->categoriesTable->retrieveAllRecords();
        $users = $this->usersTable->retrieveAllRecords();

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'admin/users.html.php',
            'variables' => [
                'categories' => $categories,
                'users' => $users
            ],
            'title' => 'Admin Panel - Users'
        ];
    }

    public function accessRestricted() {
        session_start();
        $categories = $this->categoriesTable->retrieveAllRecords();

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'admin/restricted.html.php',
            'variables' => [
                'categories' => $categories
            ],
            'title' => 'Admin Panel - Access Restricted'
        ];  
    }
}
?>