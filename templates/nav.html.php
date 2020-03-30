<nav>
    <ul>
        <li><a href="/">Home</a></li>
        <li>Jobs
            <ul>
                <?php foreach ($categories as $category): ?>
                    <li><a href="/jobs?category=<?=urlencode($category->name);?>"><?=$category->name;?></a></li>
                <?php endforeach; ?>
            </ul>
        </li>
        <li><a href="/about">About Us</a></li>
        <li><a href="/faq">FAQs</a></li>
    </ul>
</nav>