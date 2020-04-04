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

    public function accessRestricted() {
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