<section class="left">
    <?php require 'adminnav.html.php'; ?>
</section>

<section class="right">
    <h2><?=(isset($_GET['id'])) ? 'Category updated' : 'Category added';?></h2>
    <p>The category <b><?=$name;?></b> was <?=(isset($_GET['id'])) ? 'updated' : 'added';?> successfully!</p>
    <p><a href="/admin/categories">View all categories</a></p>
</section>