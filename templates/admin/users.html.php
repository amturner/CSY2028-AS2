<?php require 'userpanel.html.php'; ?>
<h2>Users</h2>

<a class="new" href="/admin/users/edit">Add new user</a>

<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Name</th>
            <th>Email Address</th>
            <th style="width: 10%">Role</th>
            <th style="width: 5%">Active</th>
            <th style="width: 5%">&nbsp;</th>
            <th style="width: 5%">&nbsp;</th>
        </tr>

<?php foreach ($users as $user): ?>
    <tr>
        <td><?=htmlspecialchars(strip_tags($user->username), ENT_QUOTES, 'UTF-8');?></td>
        <td><?=htmlspecialchars(strip_tags($user->getFullName('firstname')), ENT_QUOTES, 'UTF-8');?></td>
        <td><?=htmlspecialchars(strip_tags($user->email), ENT_QUOTES, 'UTF-8');?></td>
        <td><?php if ($user->role == 3) { echo 'Owner'; } elseif ($user->role == 2) { echo 'Administrator'; } elseif ($user->role == 1) { echo 'Employee'; } else { echo 'Client'; } ?></td>
        <td><?=($user->active) == 1 ? 'true' : 'false' ;?></td>
        <?php if (isset($_SESSION['isOwner']) || isset($_SESSION['isAdmin']) && ($user->role == 2 && $user->id == $_SESSION['id'] || $user->role == 1) || isset($_SESSION['isEmployee']) && $user->role == 1 && $user->id == $_SESSION['id'] || $user->role == 0): ?>
            <td><a style="float: right" href="/admin/users/edit?id=<?=$user->id;?>">Edit</a></td>
        <?php else: ?>
            <td></td>
        <?php endif; ?>
        
            <td>
                <form method="post" action="/admin/users/delete">
                    <input type="hidden" name="user[id]" value="<?=$user->id;?>" />
                    <input type="submit" name="submit" value="Delete" <?=($user->role == 3 || isset($_SESSION['isAdmin']) && $user->role == 2 || isset($_SESSION['isEmployee']) && ($user->role == 2 || $user->role == 1)) ? 'disabled' : '';?>/>
                </form>
            </td>
    </tr>
<?php endforeach; ?>
    </thead>
</table>    