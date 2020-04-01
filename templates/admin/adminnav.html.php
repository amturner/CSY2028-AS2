<section class="left">
    <ul>
        <li><a href="/admin/jobs">Jobs</a></li>
        <?php if (isset($_SESSION['isAdmin'])): ?>
            <li><a href="/admin/categories">Categories</a></li>
            <li><a href="/admin/users">Users</a></li>
        <?php endif; ?>
    </ul>
</section>