<?php
namespace JobSite\Entities;
class Job {
    private $applicantsTable;

    public $id;
    public $title;
    public $description;
    public $salary;
    public $closingDate;
    public $categoryId;
    public $location;

    public function __construct(\CSY2028\DatabaseTable $applicantsTable) {
        $this->applicantsTable = $applicantsTable;
    }

    public function getApplicantsCount() {
        return count($this->applicantsTable->retrieveRecord('jobId', $this->id));
    }
}
?>