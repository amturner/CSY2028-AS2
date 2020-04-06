<?php
namespace JobSite\Controllers;
class JobSiteController {
    private $jobsTable;

    public function __construct(\CSY2028\DatabaseTable $jobsTable) {
        $this->jobsTable = $jobsTable;
    }

    public function home($parameters) {
        $jobs = $this->jobsTable->retrieveAllRecords('closingDate', 'ASC');

        $filteredJobs = [];

        for ($i=0; $i<10; $i++) {
            if (!isset($jobs[$i]))
                break;
            
            $filteredJobs[] = $jobs[$i];
        }

        return [
            'layout' => 'mainlayout.html.php',
            'template' => 'main/home.html.php',
            'variables' => [
                'categories' => $parameters[0],
                'jobs' => $filteredJobs
            ],
            'title' => 'Home'
        ];
    }

    public function about($parameters) {
        return [
            'layout' => 'mainlayout.html.php',
            'template' => 'main/about.html.php',
            'variables' => [
                'categories' => $parameters[0]
            ],
            'title' => 'About Us'
        ];
    }

    public function faq() {
        return [
            'layout' => 'mainlayout.html.php',
            'template' => 'main/faq.html.php',
            'variables' => [],
            'title' => 'FAQs'
        ];
    }
}
?>