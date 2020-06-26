<?php require 'userpanel.html.php'; ?>
<h2><?=(isset($_GET['id'])) ? 'Edit Category' : 'Add Category' ;?></h2>
<?php if (isset($errors) && count($errors) > 0): ?>
    <div class="errors">
        <p>The category could not be <?=(isset($_GET['id'])) ? 'updated' : 'added';?>:</p>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?=$error;?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form action="" method="POST">
    <p class="required">Required: </p>
    <input type="hidden" name="category[id]" value="<?=(isset($_GET['id'])) ? $category->id : '';?>" />
    <label class="required">Name</label>
    <input type="text" name="category[name]" value="<?=(isset($_GET['id'])) ? htmlspecialchars(strip_tags($category->name), ENT_QUOTES, 'UTF-8') : '';?>" />
    <input type="submit" name="submit" value="Save Category" />
</form>