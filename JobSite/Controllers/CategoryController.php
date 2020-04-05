<?php
namespace JobSite\Controllers;
class CategoryController {
    private $categoriesTable;
    private $get;
    private $post;

    public function __construct(\CSY2028\DatabaseTable $categoriesTable, $get, $post) {
        $this->categoriesTable = $categoriesTable;
        $this->get = $get;
        $this->post = $post;
    }

    public function listCategories() {
        $categories = $this->categoriesTable->retrieveAllRecords();

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'admin/categories.html.php',
            'variables' => [
                'categories' => $categories
            ],
            'title' => 'Admin Panel - Categories'
        ];
    }

    public function editCategorySubmit() {
        if (isset($this->post['submit'])) {
            $categories = $this->categoriesTable->retrieveAllRecords();

            if (isset($this->get['id']))
                $category = $this->categoriesTable->retrieveRecord('id', $this->get['id'])[0];
            else
                $category = '';

            $errors = [];

            if ($this->post['category']['name'] == '')
                $errors[] = 'The name cannot be blank.';

            if (count($errors) == 0) {
                if (isset($this->get['id']))
                    $pageName = 'Category Updated';
                else
                    $pageName = 'Category Added';

                $this->post['category']['name'] = htmlspecialchars(strip_tags($this->post['category']['name']), ENT_QUOTES, 'UTF-8');

                $this->categoriesTable->save($this->post['category']);

                return [
                    'layout' => 'sidebarlayout.html.php',
                    'template' => 'admin/editcategorysuccess.html.php',
                    'variables' => [
                        'categories' => $categories,
                        'name' => htmlspecialchars(strip_tags($this->post['category']['name']), ENT_QUOTES, 'UTF-8')
                    ],
                    'title' => 'Admin Panel - ' . $pageName
                ];
            }
            // Display the edit form with any generated errors.
            else {
                if (isset($this->get['id']))
                    $pageName = 'Edit Category';
                else
                    $pageName = 'Add Category';

                return [
                    'layout' => 'sidebarlayout.html.php',
                    'template' => 'admin/editcategory.html.php',
                    'variables' => [
                        'categories' => $categories,
                        'errors' => $errors,
                        'category' => $category
                    ],
                    'title' => 'Admin Panel - ' . $pageName
                ];
            }
        }
    }

    public function editCategoryForm() {
        $categories = $this->categoriesTable->retrieveAllRecords();

        if (isset($this->get['id'])) {
            $category = $this->categoriesTable->retrieveRecord('id', $this->get['id'])[0];

            return [
                'layout' => 'sidebarlayout.html.php',
                'template' => 'admin/editcategory.html.php',
                'variables' => [
                    'categories' => $categories,
                    'category' => $category
                ],
                'title' => 'Admin Panel - Edit Category'
            ];
        }
        else {
            return [
                'layout' => 'sidebarlayout.html.php',
                'template' => 'admin/editcategory.html.php',
                'variables' => [
                    'categories' => $categories
                ],
                'title' => 'Admin Panel - Add Category'
            ];
        }
    }

    public function deleteCategory() {
        $this->categoriesTable->deleteRecordById($this->post['category']['id']);

        header('Location: /admin/categories');
    }
}
?>