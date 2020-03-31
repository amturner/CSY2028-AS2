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
            'template' => 'main/home.html.php',
            'variables' => [
                'categories' => $categories
            ],
            'title' => 'Home'
        ];
    }

    public function jobs() {
        if (isset($_GET['category']) && $_GET['category'] != '') {
            $categoryId = $this->categoriesTable->retrieveRecord('name', ucwords(urldecode($_GET['category'])));
            $categoryName = $this->categoriesTable->retrieveRecord('name', ucwords(urldecode($_GET['category'])));
        }

        $categories = $this->categoriesTable->retrieveAllRecords();

        if (isset($categoryId[0]) && isset($categoryName[0])) {
            $jobs = $this->jobsTable->retrieveRecord('categoryId', $categoryId[0]->id);
    
            return [
                'template' => 'main/jobs.html.php',
                'variables' => [
                    'categoryName' => $categoryName[0]->name,
                    'jobs' => $jobs,
                    'categories' => $categories
                ],
                'title' => 'Jobs'
            ];
        }
        else {
            return [
                'template' => 'main/jobs.html.php',
                'variables' => [
                    'categories' => $categories
                ],
                'title' => 'Jobs'
            ];
        }
    }

    public function about() {
        $categories = $this->categoriesTable->retrieveAllRecords();

        return [
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
            'template' => 'main/faq.html.php',
            'variables' => [
                'categories' => $categories
            ],
            'title' => 'FAQs'
        ];
    }
}
?>