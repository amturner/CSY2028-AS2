<?php
namespace JobSite\Controllers;
class JobSiteController {
    private $jobsTable;

    public function __construct(\CSY2028\DatabaseTable $jobsTable) {
        $this->jobsTable = $jobsTable;
    }

    // Function for displaying the home page.
    public function home($parameters) {
        // Retrieve all jobs ordered by closing date ascending from the job table.
        $jobs = $this->jobsTable->retrieveAllRecords('closingDate', 'ASC');

        // Loop through the jobs array and make any jobs where the 
        // current date is past the closing date inactive.
        foreach ($jobs as $job) {
            if (date('Y-m-d') > $job->closingDate) {
                $values = [
                    'id' => $job->id,
                    'active' => 0
                ];

                $this->jobsTable->save($values);
            }
        }

        $filteredJobs = [];

        // Loop through the jobs array and only store jobs with a 
        // closing date after the current date in the $filteredJobs array.
        for ($i=0; $i<=10; $i++) {
            if (!isset($jobs[$i]))
                break;
            
            if (date('Y-m-d') < $jobs[$i]->closingDate)
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

    // Function to display the about page.
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

    // Function to display the FAQs page.
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