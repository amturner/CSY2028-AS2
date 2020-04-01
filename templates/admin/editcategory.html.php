<section class="left">
    <?php require 'adminnav.html.php'; ?>
</section>

<section class="right">
    <h2><?=(isset($_GET['id'])) ? 'Edit Category' : 'Add Category' ;?></h2>

    <form action="" method="POST">
        <p class="required">Required: </p>
        <input type="hidden" name="category[id]" value="<?=(isset($_GET['id'])) ? $category->id : '';?>" />
        <label class="required">Name</label>
        <input type="text" name="category[name]" value="<?=(isset($_GET['id'])) ? htmlspecialchars(strip_tags($category->name), ENT_QUOTES, 'UTF-8') : '';?>" />
        <input type="submit" name="submit" value="Save Category" />
    </form>
</section>