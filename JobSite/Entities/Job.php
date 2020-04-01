<?php
namespace JobSite\Entities;
class Job {
    private $applicantsTable;
    private $categoriesTable;

    public $id;
    public $title;
    public $description;
    public $salary;
    public $closingDate;
    public $categoryId;
    public $location;

    public function __construct(\CSY2028\DatabaseTable $applicantsTable, \CSY2028\DatabaseTable $categoriesTable) {
        $this->applicantsTable = $applicantsTable;
        $this->categoriesTable = $categoriesTable;
    }

    public function getCategoryName() {
        return $this->categoriesTable->retrieveRecord('id', $this->categoryId)[0]->name;
    }

    public function listApplicants() {
        return $this->applicantsTable->retrieveRecord('jobId', $this->id);
    }

    public function getApplicantsCount() {
        return count($this->listApplicants());
    }
}
?>