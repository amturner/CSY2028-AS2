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

    public function jobs() {
        $categories = $this->categoriesTable->retrieveAllRecords();
        $allJobs = $this->jobsTable->retrieveAllRecords();

        if (isset($_GET['category']) && $_GET['category'] != 'All') {
            if (!empty($this->categoriesTable->retrieveRecord('name', ucwords(urldecode($_GET['category']))))) {
                $categoriesByFilter = $this->categoriesTable->retrieveRecord('name', ucwords(urldecode($_GET['category'])));
                $categoryByFilter = $categoriesByFilter[0];

                if (isset($_SESSION['isAdmin'])) {
                    $jobs = $this->jobsTable->retrieveAllRecords();
                }
                else {
                    $jobs = $this->jobsTable->retrieveRecord('userId', $_SESSION['id']);
                }

                $filteredJobs = [];
                $filteredCategories = [];

                foreach ($jobs as $job)
                    if ($job->categoryId == $categoryByFilter->id)
                        $filteredJobs[] = $job;

                foreach ($jobs as $job) {
                    foreach ($categories as $category) {
                        if ($job->categoryId == $category->id) {
                            $filteredCategories[] = $category->name;
                        }
                    }
                }

                $categoryChoices = array_unique($filteredCategories);
                $categoryName = $categoryByFilter->name;

                return [
                    'layout' => 'sidebarlayout.html.php',
                    'template' => 'admin/jobs.html.php',
                    'variables' => [
                        'categories' => $categories,
                        'categoryChoices' => $categoryChoices,
                        'categoryName' => htmlspecialchars(strip_tags($categoryName), ENT_QUOTES, 'UTF-8'),
                        'jobs' => $filteredJobs
                    ],
                    'title' => 'Admin Panel - Jobs'
                ];
            }
            else {
                $filteredCategories = [];

                foreach ($allJobs as $job) {
                    foreach ($categories as $category) {
                        if ($job->categoryId == $category->id) {
                            $filteredCategories[] = $category->name;
                        }
                    }
                }

                $categoryChoices = array_unique($filteredCategories);

                return [
                    'layout' => 'sidebarlayout.html.php',
                    'template' => 'admin/jobs.html.php',
                    'variables' => [
                        'categories' => $categories,
                        'categoryChoices' => $categoryChoices
                    ],
                    'title' => 'Admin Panel - Jobs'
                ];
            }
        }
        else {
            if (isset($_SESSION['isAdmin']))
                $jobs = $this->jobsTable->retrieveAllRecords();
            else
                $jobs = $this->jobsTable->retrieveRecord('userId', $_SESSION['id']);

            $filteredCategories = [];

            foreach ($jobs as $job) {
                foreach ($categories as $category) {
                    if ($job->categoryId == $category->id) {
                        $filteredCategories[] = $category->name;
                    }
                }
            }

            $categoryChoices = array_unique($filteredCategories);

            return [
                'layout' => 'sidebarlayout.html.php',
                'template' => 'admin/jobs.html.php',
                'variables' => [
                    'categories' => $categories,
                    'categoryChoices' => $categoryChoices,
                    'jobs' => $jobs
                ],
                'title' => 'Admin Panel - Jobs'
            ];
        }
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
}
?>