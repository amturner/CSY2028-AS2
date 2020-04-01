<?php
namespace JobSite\Controllers;
class CategoryController {
    private $categoriesTable;

    public function __construct(\CSY2028\DatabaseTable $categoriesTable) {
        $this->categoriesTable = $categoriesTable;
    }

    public function editCategorySubmit() {
        if (isset($_POST['submit'])) {
            $categories = $this->categoriesTable->retrieveAllRecords();

            if (isset($_GET['id']))
                $category = $this->categoriesTable->retrieveRecord('id', $_GET['id'])[0];
            else
                $category = '';

            $errors = [];

            if ($_POST['category']['name'] == '')
                $errors[] = 'The name cannot be blank.';

            if (count($errors) == 0) {
                if (isset($_GET['id']))
                    $pageName = 'Category Updated';
                else
                    $pageName = 'Category Added';

                $_POST['category']['name'] = htmlspecialchars(strip_tags($_POST['category']['name']), ENT_QUOTES, 'UTF-8');

                $this->categoriesTable->save($_POST['category']);

                return [
                    'layout' => 'sidebarlayout.html.php',
                    'template' => 'admin/editcategorysuccess.html.php',
                    'variables' => [
                        'categories' => $categories,
                        'name' => htmlspecialchars(strip_tags($_POST['category']['name']), ENT_QUOTES, 'UTF-8')
                    ],
                    'title' => 'Admin Panel - ' . $pageName
                ];
            }
            // Display the edit form with any generated errors.
            else {
                if (isset($_GET['id']))
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

        if (isset($_GET['id'])) {
            $category = $this->categoriesTable->retrieveRecord('id', $_GET['id'])[0];

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
        $this->categoriesTable->deleteRecord($_POST['category']['id']);

        header('Location: /admin/categories');
    }
}
?>