<?php
namespace JobSite\Controllers;
class JobSiteController {
    private $jobsTable;
    private $categoriesTable;

    public function __construct(\CSY2028\DatabaseTable $jobsTable, \CSY2028\DatabaseTable $categoriesTable) {
        $this->jobsTable = $jobsTable;
        $this->categoriesTable = $categoriesTable;
    }

    public function home() {
        $categories = $this->categoriesTable->retrieveAllRecords();

        return [
            'layout' => 'mainlayout.html.php',
            'template' => 'main/home.html.php',
            'variables' => [
                'categories' => $categories
            ],
            'title' => 'Home'
        ];
    }

    public function about() {
        $categories = $this->categoriesTable->retrieveAllRecords();

        return [
            'layout' => 'mainlayout.html.php',
            'template' => 'main/home.html.php',
            'variables' => [
                'categories' => $categories
            ],
            'title' => 'About'
        ];
    }

    public function faq() {
        $categories = $this->categoriesTable->retrieveAllRecords();

        return [
            'layout' => 'mainlayout.html.php',
            'template' => 'main/faq.html.php',
            'variables' => [
                'categories' => $categories
            ],
            'title' => 'FAQs'
        ];
    }
}
?>