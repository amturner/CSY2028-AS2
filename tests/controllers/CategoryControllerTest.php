<?php
require 'JobSite/Controllers/CategoryController.php';
class CategoryControllerTest extends \PHPUnit\Framework\TestCase { 
    private $categoriesTable;

    public function setUp() {
        require 'dbConnection.php';
        $this->pdo = $pdo;
        $this->categoriesTable = new \CSY2028\DatabaseTable($this->pdo, 'category', 'id');
    }

    /* Category Controller Tests */
    // List Categories Test
    public function testListCategories() {
        $categoryController = new \JobSite\Controllers\CategoryController($this->categoriesTable, [], []);

        $this->assertNotEmpty($categoryController->listCategories());
    }

    // Edit Category Form Tests
    public function testShowEditCategoryForm() {
        $testGetData = [
            'id' => 1
        ];

        $categoryController = new \JobSite\Controllers\CategoryController($this->categoriesTable, $testGetData, []);  

        $this->assertNotEmpty($categoryController->editCategoryForm());
    }

    public function testShowEditCategoryFormNoID() {
        $categoryController = new \JobSite\Controllers\CategoryController($this->categoriesTable, [], []);  

        $this->assertNotEmpty($categoryController->editCategoryForm());
    }

    // Create Category Tests
    public function testCreateCategoryNoName() {
        $testPostData = [
            'category' => [
                'name' => ''
            ],
            'submit' => true
        ];

        $categoryController = new \JobSite\Controllers\CategoryController($this->categoriesTable, [], $testPostData);
        
        $this->assertCount(1, $categoryController->editCategorySubmit()['variables']['errors']);
    }

    public function testCreateCategory() {
        $testPostData = [
            'category' => [
                'name' => 'Test Category'
            ],
            'submit' => true
        ];

        $categoryController = new \JobSite\Controllers\CategoryController($this->categoriesTable, [], $testPostData);
        $categoryController->editCategorySubmit();
        
        $category = $this->pdo->query('SELECT name FROM category WHERE name = "Test Category";')->fetch();
    
        $this->assertEquals($category['name'], 'Test Category');
    }

    // Edit Category Tests
    public function testEditCategoryNoName() {
        $testGetData = [
            'id' => 4
        ];

        $testPostData = [
            'category' => [
                'id' => 4,
                'name' => ''
            ],
            'submit' => true
        ];

        $categoryController = new \JobSite\Controllers\CategoryController($this->categoriesTable, $testGetData, $testPostData);
        
        $this->assertCount(1, $categoryController->editCategorySubmit()['variables']['errors']);
    }

    public function testEditCategory() {
        $testGetData = [
            'id' => 4
        ];

        $testPostData = [
            'category' => [
                'id' => 4,
                'name' => 'Testing Category'
            ],
            'submit' => true
        ];

        $categoryController = new \JobSite\Controllers\CategoryController($this->categoriesTable, $testGetData, $testPostData);
        $categoryController->editCategorySubmit();

        $category = $this->pdo->query('SELECT id, name FROM category WHERE id = 4;')->fetch();

        $this->assertEquals($category['name'], 'Testing Category');   
    }

    // Delete Category Test
    public function testDeleteCategory() {
        $testPostData = [
            'category' => [
                'id' => 4
            ]
        ];

        $categoryController = new \JobSite\Controllers\CategoryController($this->categoriesTable, [], $testPostData);
        @$categoryController->deleteCategory();

        $category = $this->pdo->query('SELECT name FROM category WHERE name = "Test Category";')->fetch();
        
        $this->assertNull($category['name']);

        $this->pdo->query('ALTER TABLE category AUTO_INCREMENT = 4;');
    }
}
?>