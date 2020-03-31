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
            'template' => 'admin/home.html.php',
            'variables' => [
                'categories' => $categories
            ],
            'title' => 'Admin Panel - Home'
        ];
    }

    public function jobs() {
        $categories = $this->categoriesTable->retrieveAllRecords();
        $jobs = $this->jobsTable->retrieveAllRecords();

        return [
            'template' => 'admin/jobs.html.php',
            'variables' => [
                'categories' => $categories,
                'jobs' => $jobs
            ],
            'title' => 'Admin Panel - Jobs'
        ];
    }

    public function categories() {
        $categories = $this->categoriesTable->retrieveAllRecords();

        return [
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