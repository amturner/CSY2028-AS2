<?php
namespace JobSite\Entities;
class Job {
    private $locationsTable;
    private $applicantsTable;
    private $categoriesTable;

    public $id;
    public $title;
    public $description;
    public $salary;
    public $closingDate;
    public $categoryId;
    public $locationId;
    public $userId;

    public function __construct(\CSY2028\DatabaseTable $locationsTable, \CSY2028\DatabaseTable $applicantsTable, \CSY2028\DatabaseTable $categoriesTable) {
        $this->locationsTable = $locationsTable;
        $this->applicantsTable = $applicantsTable;
        $this->categoriesTable = $categoriesTable;
    }

    public function getCategoryName() {
        return $this->categoriesTable->retrieveRecord('id', $this->categoryId)[0]->name;
    }

    public function getTown() {
        return $this->locationsTable->retrieveRecord('id', $this->locationId)[0]->town;
    }

    public function getClosingDate() {
        return (new \DateTime($this->closingDate))->format('d/m/Y');
    }

    public function listApplicants() {
        return $this->applicantsTable->retrieveRecord('jobId', $this->id);
    }

    public function getApplicantsCount() {
        return count($this->listApplicants());
    }
}
?>