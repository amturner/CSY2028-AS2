<ul>
    <?php 
        if (!empty(explode('/', $_SERVER['REQUEST_URI'])[2]))
            $page = explode('/', $_SERVER['REQUEST_URI'])[2];
        else
            $page = '';
    ?>
    <li <?=($page == 'jobs') ? 'class="current"' : '';?>><a href="/admin/jobs">Jobs</a></li>
    <?php if (isset($_SESSION['isOwner']) || isset($_SESSION['isAdmin']) || isset($_SESSION['isEmployee'])): ?>
        <li <?=($page == 'categories') ? 'class="current"' : '';?>><a href="/admin/categories">Categories</a></li>
        <li <?=($page == 'users') ? 'class="current"' : '';?>><a href="/admin/users">Users</a></li>
        <li <?=($page == 'enquiries') ? 'class="current"' : '';?>><a href="/admin/enquiries">Enquiries</a></li>
    <?php endif; ?>
</ul>