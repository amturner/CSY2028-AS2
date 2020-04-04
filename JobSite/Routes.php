<?php
namespace JobSite;
class Routes implements \CSY2028\Routes {
    public function getRoutes() {
        require '../dbConnection.php';
        
        // Create new DatabaseTable objects.
        $categoriesTable = new \CSY2028\DatabaseTable($pdo, 'category', 'id');
        $jobsTable = new \CSY2028\DatabaseTable($pdo, 'job', 'id');
        $applicantsTable = new \CSY2028\DatabaseTable($pdo, 'applicants', 'id');
        $enquiriesTable = new \CSY2028\DatabaseTable($pdo, 'enquiries', 'id', '\JobSite\Entities\Enquiry');
        $enquiryRepliesTable = new \CSY2028\DatabaseTable($pdo, 'enquiry_replies', 'id');
        $usersTable = new \CSY2028\DatabaseTable($pdo, 'users', 'id', '\JobSite\Entities\User');
        $locationsTable = new \CSY2028\DatabaseTable($pdo, 'locations', 'id');

        // Redefine DatabaseTable objects with entity class and parameters.
        $categoriesTable = new \CSY2028\DatabaseTable($pdo, 'category', 'id', '\JobSite\Entities\Category', [$categoriesTable, $jobsTable]);
        $jobsTable = new \CSY2028\DatabaseTable($pdo, 'job', 'id', '\JobSite\Entities\Job', [$locationsTable, $applicantsTable, $categoriesTable]);

        // Create new controller objects.
        $jobSiteController = new \JobSite\Controllers\JobSiteController($jobsTable, $enquiriesTable, $categoriesTable);
        $adminController = new \JobSite\Controllers\AdminController($usersTable, $categoriesTable, $jobsTable, $applicantsTable);
        $categoryController = new \JobSite\Controllers\CategoryController($categoriesTable);
        $jobController = new \JobSite\Controllers\JobController($jobsTable, $applicantsTable, $locationsTable, $categoriesTable);
        $userController = new \JobSite\Controllers\UserController($usersTable, $categoriesTable);
        $enquiryController = new \JobSite\Controllers\EnquiryController($usersTable, $enquiriesTable, $enquiryRepliesTable, $categoriesTable);

        // Define routes.
        $routes = [
            '' => [
                'GET' => [
                    'controller' => $jobSiteController,
                    'function' => 'home'
                ]
            ],
            'jobs' => [
                'GET' => [
                    'controller' => $jobController,
                    'function' => 'listJobs'
                ]
            ],
            'jobs/job' => [
                'GET' => [
                    'controller' => $jobController,
                    'function' => 'showJob'
                ]
            ],
            'jobs/apply' => [
                'GET' => [
                    'controller' => $jobController,
                    'function' => 'applyForm'
                ],
                'POST' => [
                    'controller' => $jobController,
                    'function' => 'applySubmit'
                ]
            ],
            'about' => [
                'GET' => [
                    'controller' => $jobSiteController,
                    'function' => 'about'
                ]
            ],
            'faq' => [
                'GET' => [
                    'controller' => $jobSiteController,
                    'function' => 'faq'
                ]
            ],
            'contact' => [
                'GET' => [
                    'controller' => $enquiryController,
                    'function' => 'contactForm'
                ],
                'POST' => [
                    'controller' => $enquiryController,
                    'function' => 'contactSubmit'
                ]
            ],
            'admin' => [
                'GET' => [
                    'controller' => $adminController,
                    'function' => 'home'
                ],
                'login' => true
            ],
            'admin/login' => [
                'GET' => [
                    'controller' => $userController,
                    'function' => 'loginForm'
                ],
                'POST' => [
                    'controller' => $userController,
                    'function' => 'loginSubmit'
                ]
            ],
            'admin/logout' => [
                'GET' => [
                    'controller' => $userController,
                    'function' => 'logout'
                ],
                'login' => true
            ],
            'admin/access-restricted' => [
                'GET' => [
                    'controller' => $adminController,
                    'function' => 'accessRestricted'
                ],
                'login' => true
            ],
            'admin/jobs' => [
                'GET' => [
                    'controller' => $jobController,
                    'function' => 'listJobsAdmin'
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
                    'function' => 'listApplicants'
                ],
                'login' => true
            ],
            'admin/jobs/edit' => [
                'GET' => [
                    'controller' => $jobController,
                    'function' => 'editJobForm'
                ],
                'POST' => [
                    'controller' => $jobController,
                    'function' => 'editJobSubmit'
                ],
                'login' => true
            ],
            'admin/jobs/delete' => [
                'POST' => [
                    'controller' => $jobController,
                    'function' => 'deleteJob'
                ]
            ],
            'admin/categories' => [
                'GET' => [
                    'controller' => $categoryController,
                    'function' => 'listCategories'
                ],
                'login' => true,
                'restricted' => true
            ],
            'admin/categories/edit' => [
                'GET' => [
                    'controller' => $categoryController,
                    'function' => 'editCategoryForm'
                ],
                'POST' => [
                    'controller' => $categoryController,
                    'function' => 'editCategorySubmit'
                ],
                'login' => true,
                'restricted' => true
            ],
            'admin/categories/delete' => [
                'POST' => [
                    'controller' => $categoryController,
                    'function' => 'deleteCategory'
                ],
                'login' => true,
                'restricted' => true
            ],
            'admin/users' => [
                'GET' => [
                    'controller' => $userController,
                    'function' => 'listUsers'
                ],
                'login' => true,
                'restricted' => true
            ],
            'admin/users/edit' => [
                'GET' => [
                    'controller' => $userController,
                    'function' => 'editUserForm'
                ],
                'POST' => [
                    'controller' => $userController,
                    'function' => 'editUserSubmit'
                ],
                'login' => true
            ],
            'admin/users/delete' => [
                'POST' => [
                    'controller' => $userController,
                    'function' => 'deleteUser'
                ],
                'login' => true,
                'restricted' => true
            ],
            'admin/enquiries' => [
                'GET' => [
                    'controller' => $enquiryController,
                    'function' => 'listEnquiries'
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
                    'function' => 'replyEnquiryForm'
                ],
                'POST' => [
                    'controller' => $enquiryController,
                    'function' => 'replyEnquirySubmit'
                ],
                'login' => true,
                'restricted' => true
            ],
            'admin/enquiries/delete' => [
                'POST' => [
                    'controller' => $enquiryController,
                    'function' => 'deleteEnquiry'
                ],
                'login' => true,
                'restricted' => true
            ]
        ];

        return $routes;
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