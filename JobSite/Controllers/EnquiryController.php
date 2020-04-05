<?php
namespace JobSite\Controllers;
class EnquiryController {
    private $usersTable;
    private $enquiriesTable;
    private $enquiryRepliesTable;
    private $categoriesTable;
    private $get;
    private $post;

    public function __construct(\CSY2028\DatabaseTable $usersTable, \CSY2028\DatabaseTable $enquiriesTable, \CSY2028\DatabaseTable $enquiryRepliesTable, \CSY2028\DatabaseTable $categoriesTable, $get, $post) {
        $this->usersTables = $usersTable;
        $this->enquiriesTable = $enquiriesTable;
        $this->enquiryRepliesTable = $enquiryRepliesTable;
        $this->categoriesTable = $categoriesTable;
        $this->get = $get;
        $this->post = $post;
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
        if (isset($this->post['submit'])) {
            $categories = $this->categoriesTable->retrieveAllRecords();
    
            $errors = [];

            if ($this->post['contact']['firstname'] == '')
                $errors[] = 'Your name cannot be blank.';

            if ($this->post['contact']['surname'] == '')
                $errors[] = 'Your name cannot be blank.';

            if ($this->post['contact']['email'] != '') {
                if (!filter_var($this->post['contact']['email'], FILTER_VALIDATE_EMAIL))
                    $errors[] = 'Your email address is invalid.';
            }
            else
                $errors[] = 'Your email address cannot be blank.';

            if ($this->post['contact']['message'] == '')
                $errors[] = 'Your message cannot be blank.';

            if (count($errors) == 0) {
                $this->post['contact']['firstname'] = htmlspecialchars(strip_tags($this->post['contact']['firstname']), ENT_QUOTES, 'UTF-8');
                $this->post['contact']['surname'] = htmlspecialchars(strip_tags($this->post['contact']['surname']), ENT_QUOTES, 'UTF-8');
                $this->post['contact']['message'] = htmlspecialchars(strip_tags($this->post['contact']['message']), ENT_QUOTES, 'UTF-8');

                $this->enquiriesTable->save($this->post['contact']);

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
        if (isset($this->post['submit'])) {
            $categories = $this->categoriesTable->retrieveAllRecords();
            $enquiry = $this->enquiriesTable->retrieveRecord('id', $this->get['id'])[0];

            $errors = [];

            if ($this->post['reply']['message'] == '')
                $errors[] = 'Your message cannot be blank.';
        
            if (count($errors) == 0) {
                $this->post['reply']['message'] = htmlspecialchars(strip_tags($this->post['reply']['message']), ENT_QUOTES, 'UTF-8');
                
                $this->enquiryRepliesTable->save($this->post['reply']);
                
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
        $enquiry = $this->enquiriesTable->retrieveRecord('id', $this->get['id'])[0];

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
        $this->enquiriesTable->deleteRecordById($this->post['enquiry']['id']);

        header('Location: /admin/enquiries');
    }
}
?>