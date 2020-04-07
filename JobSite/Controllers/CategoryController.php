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

    // Function for displaying a page listing out all 
    // the categories currently in the category table
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

    // Function for submitting the category edit form
    public function editCategorySubmit() {
        // Check if the user has actually submitted the form.
        if (isset($this->post['submit'])) {
            // Check if $_GET['id'] is set. If so, retrieve the category with the specified ID.
            if (isset($this->get['id']))
                $category = $this->categoriesTable->retrieveRecord('id', $this->get['id'])[0];
            else
                $category = '';

            $errors = [];

            if ($this->post['category']['name'] == '')
                $errors[] = 'The name cannot be blank.';

            // Check if no errors have been generated from input validation.
            if (count($errors) == 0) {
                // Update page name accoding to whether $_GET['id'] is set.
                if (isset($this->get['id']))
                    $pageName = 'Category Updated';
                else
                    $pageName = 'Category Added';

                $this->post['category']['name'] = htmlspecialchars(strip_tags($this->post['category']['name']), ENT_QUOTES, 'UTF-8');

                $this->categoriesTable->save($this->post['category']);

                $template = 'admin/editcategorysuccess.html.php';

                $variables = [
                    'categories' => $this->categories,
                    'name' => htmlspecialchars(strip_tags($this->post['category']['name']), ENT_QUOTES, 'UTF-8')
                ];
            }
            // Display the edit form with any generated errors.
            else {
                // Update page name accoding to whether $_GET['id'] is set.
                if (isset($this->get['id']))
                    $pageName = 'Edit Category';
                else
                    $pageName = 'Add Category';

                $template = 'admin/editcategory.html.php';

                $variables = [
                    'categories' => $this->categories,
                    'errors' => $errors,
                    'category' => $category
                ];
            }
        }

        return [
            'layout' => 'sidebarlayout.html.php',
            'template' => $template,
            'variables' => $variables,
            'title' => 'Admin Panel - ' . $pageName
        ];
    }

    // Function for displaying the category edit form
    public function editCategoryForm() {
        // Check if $_GET['id'] has been set. If so, display a pre-filled form.
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
        // Display an form.
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

    // Function for deleting a category from the category table.
    public function deleteCategory() {
        $this->categoriesTable->deleteRecordById($this->post['category']['id']);

        header('Location: /admin/categories');
    }
}
?>