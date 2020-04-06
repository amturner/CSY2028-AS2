<?php
namespace JobSite\Controllers;
class CategoryController {
    private $categoriesTable;
    private $categories;
    private $get;
    private $post;

    public function __construct(\CSY2028\DatabaseTable $categoriesTable, $get, $post) {
        $this->categoriesTable = $categoriesTable;
        $this->categories = $this->categoriesTable->retrieveAllRecords();
        $this->get = $get;
        $this->post = $post;
    }

    public function listCategories() {
        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'admin/categories.html.php',
            'variables' => [
                'categories' => $this->categories
            ],
            'title' => 'Admin Panel - Categories'
        ];
    }

    public function editCategorySubmit() {
        if (isset($this->post['submit'])) {
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

                $variables = [
                    'categories' => $this->categories,
                    'name' => htmlspecialchars(strip_tags($this->post['category']['name']), ENT_QUOTES, 'UTF-8')
                ];
            }
            // Display the edit form with any generated errors.
            else {
                if (isset($this->get['id']))
                    $pageName = 'Edit Category';
                else
                    $pageName = 'Add Category';

                $variables = [
                    'categories' => $this->categories,
                    'errors' => $errors,
                    'category' => $category
                ];
            }
        }

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => 'admin/editcategory.html.php',
            'variables' => $variables,
            'title' => 'Admin Panel - ' . $pageName
        ];
    }

    public function editCategoryForm() {
        if (isset($this->get['id'])) {
            $category = $this->categoriesTable->retrieveRecord('id', $this->get['id'])[0];

            return [
                'layout' => 'sidebarlayout.html.php',
                'template' => 'admin/editcategory.html.php',
                'variables' => [
                    'categories' => $this->categories,
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
                    'categories' => $this->categories
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