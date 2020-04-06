<?php require 'userpanel.html.php'; ?>
<h2><?=(isset($_GET['id'])) ? 'Job updated' : 'Job added';?></h2>
<p>The job <b><?=$title;?></b> was <?=(isset($_GET['id'])) ? 'updated' : 'added';?> successfully!</p>
<?php if (!isset($_GET['id'])): ?>
    <p><a href="/admin/jobs/edit">Add another job</a></p>
<?php endif; ?>
<p><a href="/admin/jobs/active">View all jobs</a></p>
<p><a href="/admin/jobs/archive">View all archived jobs</a></p>