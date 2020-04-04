<?php
namespace JobSite\Controllers;
class EnquiryController {
    private $usersTable;
    private $enquiriesTable;
    private $enquiryRepliesTable;
    private $categoriesTable;

    public function __construct(\CSY2028\DatabaseTable $usersTable, \CSY2028\DatabaseTable $enquiriesTable, \CSY2028\DatabaseTable $enquiryRepliesTable, \CSY2028\DatabaseTable $categoriesTable) {
        $this->usersTables = $usersTable;
        $this->enquiriesTable = $enquiriesTable;
        $this->enquiryRepliesTable = $enquiryRepliesTable;
        $this->categoriesTable = $categoriesTable;
    }

    public function listEnquiries($parameters) {
        $categories = $this->categoriesTable->retrieveAllRecords();
        $users = $this->usersTables->retrieveAllRecords();
        $enquiries = $this->enquiriesTable->retrieveAllRecords();
        $enquiryReplies = $this->enquiryRepliesTable->retrieveAllRecords();

        if (empty($parameters))
            header('Location: /admin/enquiries/active');

        $filteredEnquiries = [];

        if ($parameters[0] == 'active') {
            $title = 'Enquiries';

            foreach ($enquiries as $enquiry)
                if ($enquiry->answered == 0)
                    $filteredEnquiries[] = $enquiry;
        }
        elseif ($parameters[0] == 'archived') {
            $title = 'Previous Enquiries';

            foreach ($enquiries as $enquiry)
                if ($enquiry->answered == 1) {
                    $filteredEnquiries[] = $enquiry;
                }
        }

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'admin/enquiries.html.php',
            'variables' => [
                'categories' => $categories,
                'parameters' => $parameters,
                'title' => $title,
                'users' => $users,
                'enquiries' => $filteredEnquiries,
                'enquiryReplies' => $enquiryReplies
            ],
            'title' => 'Admin Panel - Enquiries - ' . $title
        ];
    }

    public function contactSubmit() {
        if (isset($_POST['submit'])) {
            $categories = $this->categoriesTable->retrieveAllRecords();
    
            $errors = [];

            if ($_POST['contact']['firstname'] == '')
                $errors[] = 'Your name cannot be blank.';

            if ($_POST['contact']['surname'] == '')
                $errors[] = 'Your name cannot be blank.';

            if ($_POST['contact']['email'] != '') {
                if (!filter_var($_POST['contact']['email'], FILTER_VALIDATE_EMAIL))
                    $errors[] = 'Your email address is invalid.';
            }
            else
                $errors[] = 'Your email address cannot be blank.';

            if ($_POST['contact']['message'] == '')
                $errors[] = 'Your message cannot be blank.';

            if (count($errors) == 0) {
                $_POST['contact']['firstname'] = htmlspecialchars(strip_tags($_POST['contact']['firstname']), ENT_QUOTES, 'UTF-8');
                $_POST['contact']['surname'] = htmlspecialchars(strip_tags($_POST['contact']['surname']), ENT_QUOTES, 'UTF-8');
                $_POST['contact']['message'] = htmlspecialchars(strip_tags($_POST['contact']['message']), ENT_QUOTES, 'UTF-8');

                $this->enquiriesTable->save($_POST['contact']);

                $template = 'main/contactsuccess.html.php';

                $variables = [
                    'categories' => $categories
                ];
            }
            else {
                $template = 'main/contact.html.php';

                $variables = [
                    'categories' => $categories,
                    'errors' => $errors
                ];
            }
        }

        return [
            'layout' => 'mainlayout.html.php',
            'template' => $template,
            'variables' => $variables,
            'title' => 'Contact Us'
        ];
    }

    public function contactForm() {
        $categories = $this->categoriesTable->retrieveAllRecords();
        
        return [
            'layout' => 'mainlayout.html.php',
            'template' => 'main/contact.html.php',
            'variables' => [
                'categories' => $categories
            ],
            'title' => 'Contact Us'
        ];
    }

    public function replyEnquirySubmit() {
        if (isset($_POST['submit'])) {
            $categories = $this->categoriesTable->retrieveAllRecords();
            $enquiry = $this->enquiriesTable->retrieveRecord('id', $_GET['id'])[0];

            $errors = [];

            if ($_POST['reply']['message'] == '')
                $errors[] = 'Your message cannot be blank.';
        
            if (count($errors) == 0) {
                $_POST['reply']['message'] = htmlspecialchars(strip_tags($_POST['reply']['message']), ENT_QUOTES, 'UTF-8');
                
                $this->enquiryRepliesTable->save($_POST['reply']);
                
                $enquiryValues = [
                    'id' => $enquiry->id,
                    'answered' => 1
                ];

                $this->enquiriesTable->save($enquiryValues);
                
                $template = 'admin/replysuccess.html.php';

                $variables = [
                    'categories' => $categories
                ];
            }
            else
                $template = 'admin/reply.html.php';

                $variables = [
                    'categories' => $categories,
                    'errors' => $errors,
                    'enquiry' => $enquiry
                ];
                
            return [
                'layout' => 'sidebarlayout.html.php',
                'template' => $template,
                'variables' => $variables,
                'title' => 'Admin Panel - Enquriries - Reply'
            ];
        }
    }

    public function replyEnquiryForm() {
        $categories = $this->categoriesTable->retrieveAllRecords();
        $enquiry = $this->enquiriesTable->retrieveRecord('id', $_GET['id'])[0];

        if (empty($enquiry) || $enquiry->answered == 1)
            header('Location: /admin/enquiries');

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'admin/reply.html.php',
            'variables' => [
                'enquiry' => $enquiry
            ],
            'title' => 'Admin Panel - Enquiries - Reply'
        ]; 
    }

    public function deleteEnquiry() {
        $this->enquiriesTable->deleteRecordById($_POST['enquiry']['id']);

        header('Location: /admin/enquiries');
    }
}
?>