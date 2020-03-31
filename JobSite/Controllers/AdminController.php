<?php
namespace JobSite\Controllers;
class AdminController {
    private $usersTable;

    public function home() {
        return [
            'template' => 'admin/home.html.php',
            'variables' => [],
            'title' => 'Admin Home'
        ];
    }

    public function jobs() {

    }

    public function categories() {
        
    }
}
?>