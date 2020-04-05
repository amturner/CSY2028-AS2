<?php require 'userpanel.html.php'; ?>
<h2>Categories</h2>

<a class="new" href="/admin/categories/edit">Add new category</a>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th style="width: 5%">&nbsp;</th>
            <th style="width: 10%">&nbsp;</th>
            <th style="width: 5%">&nbsp;</th>
        </tr>

<?php foreach ($categories as $category): ?>
        <tr>
            <td><?=htmlspecialchars(strip_tags($category->name), ENT_QUOTES, 'UTF-8');?></td>
            <td><a style="float: right" href="/admin/categories/edit?id=<?=$category->id;?>">Edit</a></td>
            <td><a style="float: right" href="/admin/jobs?category=<?=ucwords(urlencode($category->name));?>">View jobs (<?=$category->getJobsCount();?>)</a></td>
            <td>
                <form method="post" action="/admin/categories/delete">
                    <input type="hidden" name="category[id]" value="<?=$category->id;?>" />
                    <input type="submit" name="submit" value="Delete" <?=($category->getJobsCount() > 0) ? 'disabled' : '';?>/>
                </form>
            </td>
        </tr>
<?php endforeach; ?>
    </thead>
</table>    