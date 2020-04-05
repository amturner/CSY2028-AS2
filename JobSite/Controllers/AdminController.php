<?php
namespace JobSite\Controllers;
class AdminController {
    public function home() {
        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'admin/home.html.php',
            'variables' => [],
            'title' => 'Admin Panel - Home'
        ];
    }

    public function accessRestricted() {
        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'admin/restricted.html.php',
            'variables' => [],
            'title' => 'Admin Panel - Access Restricted'
        ];  
    }
}
?>