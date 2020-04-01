<section class="left">
    <?php require 'adminnav.html.php'; ?>
</section>

<section class="right">
    <h2><?=(isset($_GET['id'])) ? 'Job updated' : 'Job added';?></h2>
    <p>The job <b><?=$title;?></b> was <?=(isset($_GET['id'])) ? 'updated' : 'added';?> successfully!</p>
    <p><a href="/admin/jobs">View all jobs</a></p>
</section>