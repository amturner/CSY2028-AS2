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
        $jobs = $this->jobsTable->retrieveAllRecords('closingDate', 'ASC');

        $filteredJobs = [];

        if (count($jobs) < 10)
            $count = count($job);
        else
            $count = 10;

        for ($i=0; $i<$count; $i++)
            $filteredJobs[] = $jobs[$i];

        return [
            'layout' => 'mainlayout.html.php',
            'template' => 'main/home.html.php',
            'variables' => [
                'categories' => $categories,
                'jobs' => $filteredJobs
            ],
            'title' => 'Home'
        ];
    }

    public function about() {
        $categories = $this->categoriesTable->retrieveAllRecords();

        return [
            'layout' => 'mainlayout.html.php',
            'template' => 'main/about.html.php',
            'variables' => [
                'categories' => $categories
            ],
            'title' => 'About Us'
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