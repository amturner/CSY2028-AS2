<?php
namespace JobSite\Controllers;
class JobController {
    private $jobsTable;
    private $categoriesTable;

    public function __construct(\CSY2028\DatabaseTable $jobsTable, \CSY2028\DatabaseTable $categoriesTable) {
        $this->jobsTable = $jobsTable;
        $this->categoriesTable = $categoriesTable;
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

            if ($_POST['job']['location'] == '')
                $errors[] = 'The location cannot be blank.';

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

        if (isset($_GET['id'])) {
            $job = $this->jobsTable->retrieveRecord('id', $_GET['id'])[0];

            return [
                'layout' => 'sidebarlayout.html.php',
                'template' => 'admin/editjob.html.php',
                'variables' => [
                    'categories' => $categories,
                    'job' => $job
                ],
                'title' => 'Admin Panel - Edit Job'
            ];
        }
        else {
            return [
                'layout' => 'sidebarlayout.html.php',
                'template' => 'admin/editjob.html.php',
                'variables' => [
                    'categories' => $categories
                ],
                'title' => 'Admin Panel - Add Job'
            ];
        }
    }

    public function listApplicants() {
        $categories = $this->categoriesTable->retrieveAllRecords();
        $job = $this->jobsTable->retrieveRecord('id', $_GET['id'])[0];

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'admin/applicants.html.php',
            'variables' => [
                'categories' => $categories,
                'title' => $job->title,
                'applicants' => $job->listApplicants()
            ],
            'title' => 'Admin Panel - Applicants'
        ];
    }
}
?>