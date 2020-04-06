<?php
require 'JobSite/Entities/Category.php';
class CategoryEntityTest extends \PHPUnit\Framework\TestCase { 
    private $jobsTable;
    private $categoriesTable;

    public function setUp() {
        require 'dbConnection.php';
        $this->pdo = $pdo;
        $this->jobsTable = new \CSY2028\DatabaseTable($this->pdo, 'job', 'id');
        $this->categoriesTable = new \CSY2028\DatabaseTable($this->pdo, 'category', 'id');

        $this->categoriesTable  = new \CSY2028\DatabaseTable($pdo, 'category', 'id', '\JobSite\Entities\Category', [$this->categoriesTable , $this->jobsTable]);
       // $this->jobsTable = new \CSY2028\DatabaseTable($pdo, 'job', 'id', '\JobSite\Entities\Job', [$this->locationsTable, $this->applicantsTable, $this->categoriesTable]);
    }
    
    /* Category Entity Tests */
    // Get Category Name Test
    public function testGetCategoryName() {
        $category = $this->categoriesTable->retrieveRecord('id', 1)[0];

        $this->assertEquals($category->getCategoryName(), 'IT');
    }

    // Get Jobs Count Test
    public function testGetJobsCount() {
        $category = $this->categoriesTable->retrieveRecord('id', 1)[0];

        $this->assertEquals($category->getJobsCount(), 2);
    }
}
?>