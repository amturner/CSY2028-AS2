<?php
require 'JobSite/Controllers/AdminController.php';
class AdminControllerTest extends \PHPUnit\Framework\TestCase { 
    /* Admin Controller Tests */
    // Home Page Test
    public function testHome() {
        $adminController = new \JobSite\Controllers\AdminController();

        $this->assertNotEmpty($adminController->home());
    }

    // Access Restricted Page Test
    public function testAccessRestricted() {
        $adminController = new \JobSite\Controllers\AdminController();

        $this->assertNotEmpty($adminController->accessRestricted());
    }
}
?>