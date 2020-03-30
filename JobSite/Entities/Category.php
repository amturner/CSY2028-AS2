<?php
namespace JobSite\Entities;
class Category {
    private $categoriesTable;

    public $id;
    public $name;

    public function __construct(\CSY2028\DatabaseTable $categoriesTable) {
        $this->categoriesTable = $categoriesTable;
    }

    public function getCategoryName($id) {  
        return $this->categoriesTable->retrieveRecord('id', $id)[0]->name;
    }
}
?>