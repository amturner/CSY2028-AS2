<?php
namespace JobSite\Entities;
class Category {
    private $categoriesTable;
    private $jobsTable;

    public $id;
    public $name;

    public function __construct(\CSY2028\DatabaseTable $categoriesTable, \CSY2028\DatabaseTable $jobsTable) {
        $this->categoriesTable = $categoriesTable;
        $this->jobsTable = $jobsTable;
    }

    public function getCategoryName() {  
        return $this->categoriesTable->retrieveRecord('id', $this->id)[0]->name;
    }

    public function getJobsCount() {
        return count($this->jobsTable->retrieveRecord('categoryId', $this->id));
    }
}
?>