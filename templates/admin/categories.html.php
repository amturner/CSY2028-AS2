<main class="sidebar">
    <?php require 'adminnav.html.php'; ?>

    <section class="right">
        <h2>Categories</h2>

        <a class="new" href="/admin/categories/addcategory">Add new category</a>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th style="width: 5%">&nbsp;</th>
                    <th style="width: 5%">&nbsp;</th>
                </tr>

        <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?=$category->name;?></td>
                    <td><a style="float: right" href="/admin/categories/edit?id=<?=$category->id;?>">Edit</a></td>
                    <td>
                        <form method="post" action="/admin/categories/delete">
                            <input type="hidden" name="id" value="<?=$category->id;?>" />
                            <input type="submit" name="submit" value="Delete" />
                        </form>
                    </td>
                </tr>
        <?php endforeach; ?>
            </thead>
        </table>    
    </section>
</main>
