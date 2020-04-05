<?php require 'userpanel.html.php'; ?>
<h2><?=(isset($_GET['id'])) ? 'User updated' : 'User added';?></h2>
<p>The user <b><?=$username;?></b> was <?=(isset($_GET['id'])) ? 'updated' : 'added';?> successfully!</p>
<?=(!isset($_GET['id'])) ? '<p><a href="/admin/users/edit">Create another user</a></p>' : '' ;?>
<p><a href="/admin/users">View all users</a></p>