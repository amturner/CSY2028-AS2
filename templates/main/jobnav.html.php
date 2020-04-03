<ul>
    <?php foreach ($categories as $category): ?>
        <li <?=(isset($_GET['category']) && ucwords(urldecode($_GET['category'])) == $category->name) ? 'class="current"' : '';?>><a href="/jobs?category=<?=urlencode($category->name);?>"><?=$category->name;?></a></li>
    <?php endforeach; ?>
</ul>