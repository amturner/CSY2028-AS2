<?php
namespace JobSite;
class Routes implements \CSY2028\Routes {
    private $categoriesTable;

    public function getRoutes() {
        require '../dbConnection.php';

        // Create new DatabaseTable objects.
        $this->categoriesTable = new \CSY2028\DatabaseTable($pdo, 'category', 'id');
        $jobsTable = new \CSY2028\DatabaseTable($pdo, 'job', 'id');
        $applicantsTable = new \CSY2028\DatabaseTable($pdo, 'applicants', 'id');
        $enquiriesTable = new \CSY2028\DatabaseTable($pdo, 'enquiries', 'id', '\JobSite\Entities\Enquiry');
        $enquiryRepliesTable = new \CSY2028\DatabaseTable($pdo, 'enquiry_replies', 'id');
        $usersTable = new \CSY2028\DatabaseTable($pdo, 'users', 'id', '\JobSite\Entities\User');
        $locationsTable = new \CSY2028\DatabaseTable($pdo, 'locations', 'id');

        // Redefine DatabaseTable objects with entity class and parameters.
        $this->categoriesTable  = new \CSY2028\DatabaseTable($pdo, 'category', 'id', '\JobSite\Entities\Category', [$this->categoriesTable , $jobsTable]);
        $jobsTable = new \CSY2028\DatabaseTable($pdo, 'job', 'id', '\JobSite\Entities\Job', [$locationsTable, $applicantsTable, $this->categoriesTable]);

        // Create new controller objects.
        $jobSiteController = new \JobSite\Controllers\JobSiteController($jobsTable);
        $adminController = new \JobSite\Controllers\AdminController();
        $categoryController = new \JobSite\Controllers\CategoryController($this->categoriesTable, $_GET, $_POST);
        $jobController = new \JobSite\Controllers\JobController($jobsTable, $applicantsTable, $locationsTable, $this->categoriesTable, $_GET, $_POST);
        $userController = new \JobSite\Controllers\UserController($usersTable, $_GET, $_POST);
        $enquiryController = new \JobSite\Controllers\EnquiryController($usersTable, $enquiriesTable, $enquiryRepliesTable, $_GET, $_POST);

        // Define routes.
        $routes = [
            '' => [
                'GET' => [
                    'controller' => $jobSiteController,
                    'function' => 'home',
                    'parameters' => [$this->categoriesTable->retrieveAllRecords()]
                ]
            ],
            'jobs' => [
                'GET' => [
                    'controller' => $jobController,
                    'function' => 'listJobs',
                    'parameters' => [$this->categoriesTable->retrieveAllRecords()]
                ]
            ],
            'jobs/job' => [
                'GET' => [
                    'controller' => $jobController,
                    'function' => 'showJob',
                    'parameters' => []
                ]
            ],
            'jobs/apply' => [
                'GET' => [
                    'controller' => $jobController,
                    'function' => 'applyForm',
                    'parameters' => []
                ],
                'POST' => [
                    'controller' => $jobController,
                    'function' => 'applySubmit',
                    'parameters' => []
                ]
            ],
            'about' => [
                'GET' => [
                    'controller' => $jobSiteController,
                    'function' => 'about',
                    'parameters' => [$this->categoriesTable->retrieveAllRecords()]
                ]
            ],
            'faq' => [
                'GET' => [
                    'controller' => $jobSiteController,
                    'function' => 'faq',
                    'parameters' => []
                ]
            ],
            'contact' => [
                'GET' => [
                    'controller' => $enquiryController,
                    'function' => 'contactForm',
                    'parameters' => []
                ],
                'POST' => [
                    'controller' => $enquiryController,
                    'function' => 'contactSubmit',
                    'parameters' => []
                ]
            ],
            'admin' => [
                'GET' => [
                    'controller' => $adminController,
                    'function' => 'home',
                    'parameters' => []
                ],
                'login' => true
            ],
            'admin/login' => [
                'GET' => [
                    'controller' => $userController,
                    'function' => 'loginForm',
                    'parameters' => []
                ],
                'POST' => [
                    'controller' => $userController,
                    'function' => 'loginSubmit',
                    'parameters' => []
                ]
            ],
            'admin/logout' => [
                'GET' => [
                    'controller' => $userController,
                    'function' => 'logout',
                    'parameters' => []
                ],
                'login' => true
            ],
            'admin/access-restricted' => [
                'GET' => [
                    'controller' => $adminController,
                    'function' => 'accessRestricted',
                    'parameters' => []
                ],
                'login' => true
            ],
            'admin/jobs' => [
                'GET' => [
                    'controller' => $jobController,
                    'function' => 'listJobsAdmin',
                    'parameters' => []
                ],
                'login' => true
            ],
            'admin/jobs/active' => [
                'GET' => [
                    'controller' => $jobController,
                    'function' => 'listJobsAdmin',
                    'parameters' => ['active']
                ],
                'login' => true
            ],
            'admin/jobs/archive' => [
                'GET' => [
                    'controller' => $jobController,
                    'function' => 'listJobsAdmin',
                    'parameters' => ['archived']
                ],
                'login' => true
            ],
            'admin/jobs/applicants' => [
                'GET' => [
                    'controller' => $jobController,
                    'function' => 'listApplicants',
                    'parameters' => []
                ],
                'login' => true
            ],
            'admin/jobs/edit' => [
                'GET' => [
                    'controller' => $jobController,
                    'function' => 'editJobForm',
                    'parameters' => []
                ],
                'POST' => [
                    'controller' => $jobController,
                    'function' => 'editJobSubmit',
                    'parameters' => []
                ],
                'login' => true
            ],
            'admin/jobs/delete' => [
                'POST' => [
                    'controller' => $jobController,
                    'function' => 'deleteJob',
                    'parameters' => []
                ]
            ],
            'admin/categories' => [
                'GET' => [
                    'controller' => $categoryController,
                    'function' => 'listCategories',
                    'parameters' => []
                ],
                'login' => true,
                'restricted' => true
            ],
            'admin/categories/edit' => [
                'GET' => [
                    'controller' => $categoryController,
                    'function' => 'editCategoryForm',
                    'parameters' => []
                ],
                'POST' => [
                    'controller' => $categoryController,
                    'function' => 'editCategorySubmit',
                    'parameters' => []
                ],
                'login' => true,
                'restricted' => true
            ],
            'admin/categories/delete' => [
                'POST' => [
                    'controller' => $categoryController,
                    'function' => 'deleteCategory',
                    'parameters' => []
                ],
                'login' => true,
                'restricted' => true
            ],
            'admin/users' => [
                'GET' => [
                    'controller' => $userController,
                    'function' => 'listUsers',
                    'parameters' => []
                ],
                'login' => true,
                'restricted' => true
            ],
            'admin/users/edit' => [
                'GET' => [
                    'controller' => $userController,
                    'function' => 'editUserForm',
                    'parameters' => []
                ],
                'POST' => [
                    'controller' => $userController,
                    'function' => 'editUserSubmit',
                    'parameters' => []
                ],
                'login' => true
            ],
            'admin/users/delete' => [
                'POST' => [
                    'controller' => $userController,
                    'function' => 'deleteUser',
                    'parameters' => []
                ],
                'login' => true,
                'restricted' => true
            ],
            'admin/enquiries' => [
                'GET' => [
                    'controller' => $enquiryController,
                    'function' => 'listEnquiries',
                    'parameters' => []
                ],
                'login' => true,
                'restricted' => true
            ],
            'admin/enquiries/active' => [
                'GET' => [
                    'controller' => $enquiryController,
                    'function' => 'listEnquiries',
                    'parameters' => ['active']
                ],
                'login' => true,
                'restricted' => true                
            ],
            'admin/enquiries/archive' => [
                'GET' => [
                    'controller' => $enquiryController,
                    'function' => 'listEnquiries',
                    'parameters' => ['archived']
                ],
                'login' => true,
                'restricted' => true   
            ],
            'admin/enquiries/reply' => [
                'GET' => [
                    'controller' => $enquiryController,
                    'function' => 'replyEnquiryForm',
                    'parameters' => []
                ],
                'POST' => [
                    'controller' => $enquiryController,
                    'function' => 'replyEnquirySubmit',
                    'parameters' => []
                ],
                'login' => true,
                'restricted' => true
            ],
            'admin/enquiries/delete' => [
                'POST' => [
                    'controller' => $enquiryController,
                    'function' => 'deleteEnquiry',
                    'parameters' => []
                ],
                'login' => true,
                'restricted' => true
            ]
        ];

        return $routes;
    }

    public function getTemplateVariables() {
        return [
            'categories' => $this->categoriesTable->retrieveAllRecords()
        ];
    }

	public function checkLogin() {
		session_start();
		if (!isset($_SESSION['loggedIn']))
			header('Location: /admin/login');
    }
    
    public function updateRole() {
        if (isset($_SESSION['id'])) {
            require '../dbConnection.php';
            $usersTable = new \CSY2028\DatabaseTable($pdo, 'users', 'id', '\JobSite\Entities\User');
            $user = $usersTable->retrieveRecord('id', $_SESSION['id'])[0];
    
            if ($user->role == 3 && !isset($_SESSION['isOwner'])) {
                $_SESSION['isOwner'] = true;
                unset($_SESSION['isAdmin']);
                unset($_SESSION['isEmployee']);
                unset($_SESSION['isClient']);
            }
            elseif ($user->role == 2 && !isset($_SESSION['isAdmin'])) {
                unset($_SESSION['isOwner']);
                $_SESSION['isAdmin'] = true;
                unset($_SESSION['isEmployee']);
                unset($_SESSION['isClient']);
            }
            elseif ($user->role == 1 && !isset($_SESSION['isEmployee'])) {
                unset($_SESSION['isOwner']);
                unset($_SESSION['isAdmin']);
                $_SESSION['isEmployee'] = true;
                unset($_SESSION['isClient']);
            }
            elseif ($user->role == 0 && !isset($_SESSION['isClient'])) {
                unset($_SESSION['isOwner']);
                unset($_SESSION['isAdmin']);
                unset($_SESSION['isEmployee']);
                $_SESSION['isClient'] = true;
            }
        }
    }

    public function checkAccess() {
        if (isset($_SESSION['isOwner']) || isset($_SESSION['isAdmin']) || isset($_SESSION['isEmployee']))
            return true;
    }
}