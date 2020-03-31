<?php
namespace JobSite;
class Routes implements \CSY2028\Routes {
    public function getRoutes() {
        require '../dbConnection.php';
        
        // Create a new DatabaseTable object in $categoriesTable.
        $categoriesTable = new \CSY2028\DatabaseTable($pdo, 'category', 'id', '\JobSite\Entities\Category');
        // Overwrite the object in $categoriesTable with a new DatabaseTable object using the previous object as a parameter.
        $categoriesTable = new \CSY2028\DatabaseTable($pdo, 'category', 'id', '\JobSite\Entities\Category', [$categoriesTable]);
        $applicantsTable = new \CSY2028\DatabaseTable($pdo, 'applicants', 'id', '\JobSite\Entities\Applicant');
        $jobsTable = new \CSY2028\DatabaseTable($pdo, 'job', 'id', '\JobSite\Entities\Job', [$applicantsTable]);
        $usersTable = new \CSY2028\DatabaseTable($pdo, 'users', 'id', '\JobSite\Entities\User');

        $jobSiteController = new \JobSite\Controllers\JobSiteController($jobsTable, $categoriesTable);
        $adminController = new \JobSite\Controllers\AdminController($usersTable, $categoriesTable, $jobsTable, $applicantsTable);
        $userController = new \JobSite\Controllers\UserController($usersTable, $categoriesTable);

        $routes = [
            '' => [
                'GET' => [
                    'controller' => $jobSiteController,
                    'function' => 'home'
                ]
            ],
            'jobs' => [
                'GET' => [
                    'controller' => $jobSiteController,
                    'function' => 'jobs'
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
            'admin/jobs' => [
                'GET' => [
                    'controller' => $adminController,
                    'function' => 'jobs'
                ],
                'login' => true
            ],
            'admin/categories' => [
                'GET' => [
                    'controller' => $adminController,
                    'function' => 'categories'
                ],
                'login' => true
            ],
            'admin/users' => [
                'GET' => [
                    'controller' => $adminController,
                    'function' => 'users'
                ],
                'login' => true
            ],
            'admin/users/adduser' => [
                'GET' => [
                    'controller' => $userController,
                    'function' => 'addUserForm'
                ],
                'POST' => [
                    'controller' => $userController,
                    'function' => 'addUserSubmit'
                ],
                'login' => true
            ]
        ];

        return $routes;
    }

	public function checkLogin() {
		session_start();
		if (!isset($_SESSION['loggedIn'])) {
			header('Location: /admin/login');
		}
	}
}