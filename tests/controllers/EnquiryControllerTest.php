<?php
require 'JobSite/Controllers/EnquiryController.php';
class EnquiryControllerTest extends \PHPUnit\Framework\TestCase { 
    private $usersTable;
    private $enquiriesTable;
    private $enquiryRepliesTable;

    public function setUp() {
        require 'dbConnection.php';
        $this->pdo = $pdo;
        $this->usersTable = new \CSY2028\DatabaseTable($this->pdo, 'users', 'id');
        $this->enquiriesTable = new \CSY2028\DatabaseTable($this->pdo, 'enquiries', 'id');
        $this->enquiryRepliesTable = new \CSY2028\DatabaseTable($this->pdo, 'enquiry_replies', 'id');
    }

    /* Enquiry Controller Tests */
    // List Enquiries Tests
    public function testListEnquiriesNoParams() {
        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, [], []);
        $enquiries = @$enquiryController->listEnquiries([]);

        $this->assertEmpty($enquiries['variables']['title']);
    }
    
    public function testListActiveEnquiries() {
        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, [], []);
        $enquiries = $enquiryController->listEnquiries(['active']);

        $this->assertTrue(!empty($enquiries) && $enquiries['variables']['title'] == 'Enquiries');
    }

    public function testListPreviousEnquiries() {
        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, [], []);
        $enquiries = $enquiryController->listEnquiries(['archived']);

        $this->assertTrue(!empty($enquiries) && $enquiries['variables']['title'] == 'Previous Enquiries');
    }

    // Create Enquiry Tests
    public function testCreateEnquiryNoDetails() {
        $testPostData = [
            'contact' => [
                'firstname' => '',
                'surname' => '',
                'email' => '',
                'phone' => '',
                'message' => ''
            ],
            'submit' => true
        ];
    
        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, [], $testPostData);
        $enquiry = $enquiryController->contactSubmit();

        $this->assertCount(4, $enquiry['variables']['errors']);
    }

    public function testCreateEnquiryOnlyFirstname() {
        $testPostData = [
            'contact' => [
                'firstname' => 'Tony',
                'surname' => '',
                'email' => '',
                'phone' => '',
                'message' => ''
            ],
            'submit' => true
        ];
    
        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, [], $testPostData);
        $enquiry = $enquiryController->contactSubmit();

        $this->assertCount(3, $enquiry['variables']['errors']);
    }

    public function testCreateEnquiryOnlySurname() {
        $testPostData = [
            'contact' => [
                'firstname' => '',
                'surname' => 'Stark',
                'email' => '',
                'phone' => '',
                'message' => ''
            ],
            'submit' => true
        ];
    
        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, [], $testPostData);
        $enquiry = $enquiryController->contactSubmit();

        $this->assertCount(3, $enquiry['variables']['errors']);
    }

    public function testCreateEnquiryOnlyEmail() {
        $testPostData = [
            'contact' => [
                'firstname' => '',
                'surname' => '',
                'email' => 'tony@starkindustries.com',
                'phone' => '',
                'message' => ''
            ],
            'submit' => true
        ];
    
        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, [], $testPostData);
        $enquiry = $enquiryController->contactSubmit();

        $this->assertCount(3, $enquiry['variables']['errors']);
    }

    public function testCreateEnquiryOnlyPhone() {
        $testPostData = [
            'contact' => [
                'firstname' => '',
                'surname' => '',
                'email' => '',
                'phone' => '01234567891',
                'message' => ''
            ],
            'submit' => true
        ];
    
        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, [], $testPostData);
        $enquiry = $enquiryController->contactSubmit();

        $this->assertCount(4, $enquiry['variables']['errors']);
    }

    public function testCreateEnquiryOnlyMessage() {
        $testPostData = [
            'contact' => [
                'firstname' => '',
                'surname' => '',
                'email' => '',
                'phone' => '',
                'message' => 'Hello World!'
            ],
            'submit' => true
        ];
    
        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, [], $testPostData);
        $enquiry = $enquiryController->contactSubmit();

        $this->assertCount(3, $enquiry['variables']['errors']);
    }

    public function testCreateEnquiryFirstnameSurname() {
        $testPostData = [
            'contact' => [
                'firstname' => 'Tony',
                'surname' => 'Stark',
                'email' => '',
                'phone' => '',
                'message' => ''
            ],
            'submit' => true
        ];
    
        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, [], $testPostData);
        $enquiry = $enquiryController->contactSubmit();

        $this->assertCount(2, $enquiry['variables']['errors']);
    }

    public function testCreateEnquiryFirstnameSurnamePhone() {
        $testPostData = [
            'contact' => [
                'firstname' => 'Tony',
                'surname' => 'Stark',
                'email' => '',
                'phone' => '01234567891',
                'message' => ''
            ],
            'submit' => true
        ];
    
        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, [], $testPostData);
        $enquiry = $enquiryController->contactSubmit();

        $this->assertCount(2, $enquiry['variables']['errors']);
    }

    public function testCreateEnquiryFirstnameSurnameMessage() {
        $testPostData = [
            'contact' => [
                'firstname' => 'Tony',
                'surname' => 'Stark',
                'email' => '',
                'phone' => '01234567891',
                'message' => 'Hello World!'
            ],
            'submit' => true
        ];
    
        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, [], $testPostData);
        $enquiry = $enquiryController->contactSubmit();

        $this->assertCount(1, $enquiry['variables']['errors']);
    }

    public function testCreateEnquiryInvalidEmail() {
        $testPostData = [
            'contact' => [
                'firstname' => 'Tony',
                'surname' => 'Stark',
                'email' => 'starkindustries.com',
                'phone' => '01234567891',
                'message' => 'Hello World!'
            ],
            'submit' => true
        ];
    
        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, [], $testPostData);
        $enquiry = $enquiryController->contactSubmit();

        $this->assertCount(1, $enquiry['variables']['errors']);
    }

    public function testCreateEnquirySuccessful() {
        $testPostData = [
            'contact' => [
                'firstname' => 'Tony',
                'surname' => 'Stark',
                'email' => 'tony@starkindustries.com',
                'phone' => '01234567891',
                'message' => 'Hello World!'
            ],
            'submit' => true
        ];
    
        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, [], $testPostData);
        $enquiryController->contactSubmit();

        $enquiry = $this->pdo->query('SELECT email FROM enquiries WHERE email = "tony@starkindustries.com";')->fetch();

        $this->assertEquals($enquiry['email'], 'tony@starkindustries.com');
    }

    // Enquiry Reply Tests
    public function testEnquiryReplyNoMessage() {
        $testGetData = [
            'id' => 3
        ];

        $testPostData = [
            'reply' => [
                'user_id' => 1,
                'enquiry_id' => 3,
                'message' => ''
            ],
            'submit' => true
        ];

        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, $testGetData, $testPostData);

        $reply = $enquiryController->replyEnquirySubmit();

        $this->assertCount(1, $reply['variables']['errors']);
    }

    public function testEnquiryReply() {
        $testGetData = [
            'id' => 3
        ];

        $testPostData = [
            'reply' => [
                'user_id' => 1,
                'enquiry_id' => 3,
                'message' => 'Hi there!'
            ],
            'submit' => true
        ];

        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, $testGetData, $testPostData);
        $enquiryController->replyEnquirySubmit();

        $reply = $this->pdo->query('SELECT enquiry_id, message FROM enquiry_replies WHERE enquiry_id = 3;')->fetch();
        
        $this->assertEquals($reply['message'], 'Hi there!');
    }

    // Delete Enquiry Test
    public function testDeleteEnquiry() {
        $testPostData = [
            'enquiry' => [
                'id' => 3
            ]
        ];

        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, [], $testPostData);
        @$enquiryController->deleteEnquiry();

        $enquiry = $this->pdo->query('SELECT id FROM enquiries WHERE id = 3;')->fetch();
        $reply = $this->pdo->query('SELECT enquiry_id FROM enquiry_replies WHERE enquiry_id = 3;')->fetch();

        $this->assertTrue(empty($enquiry) && empty($reply));

        $this->pdo->query('ALTER TABLE enquiries AUTO_INCREMENT = 3;');
    }

    /* Form Tests */
    public function testShowContactForm() {
        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, [], []);

        $this->assertNotEmpty($enquiryController->contactForm());
    }

    public function testShowReplyEnquiryForm() {
        $testGetData = [
            'id' => 1
        ];

        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, $testGetData, []);

        $this->assertNotEmpty($enquiryController->replyEnquiryForm());
    } 

    public function testShowReplyEnquiryFormReplied() {
        $testGetData = [
            'id' => 2
        ];

        $enquiryController = new \JobSite\Controllers\EnquiryController($this->usersTable, $this->enquiriesTable, $this->enquiryRepliesTable, $testGetData, []);

        $this->assertNotEmpty(@$enquiryController->replyEnquiryForm());
    } 
}
?>