<?php
namespace JobSite\Entities;
class Enquiry {
    public $id;
    public $firstname;
    public $surname;
    public $email;
    public $phone;
    public $message;
    public $answered;

    public function getFullName($order) {
        if ($order == 'firstname')
            return $this->firstname . ' ' . $this->surname;
        elseif ($order == 'surname')
            return $this->surname . ', ' . $this->firstname;
    }
}
?>