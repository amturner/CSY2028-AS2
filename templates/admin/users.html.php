<section class="left">
    <?php require 'adminnav.html.php'; ?>
</section>

<section class="right">
    <h2>Users</h2>

    <a class="new" href="/admin/users/edit">Add new user</a>

    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Email Address</th>
                <th style="width: 10%">Administrator</th>
                <th style="width: 5%">Active</th>
                <th style="width: 5%">&nbsp;</th>
                <th style="width: 5%">&nbsp;</th>
            </tr>

    <?php foreach ($users as $user): ?>
            <tr>
                <td><?=htmlspecialchars(strip_tags($user->username), ENT_QUOTES, 'UTF-8');?></td>
                <td><?=htmlspecialchars(strip_tags($user->email), ENT_QUOTES, 'UTF-8');?></td>
                <td><?=($user->administrator) == 1 ? 'true' : 'false' ;?></td>
                <td><?=($user->active) == 1 ? 'true' : 'false' ;?></td>
                <td><a style="float: right" href="/admin/users/edit?id=<?=$user->id;?>">Edit</a></td>
                <td>
                    <form method="post" action="/admin/users/delete">
                        <input type="hidden" name="user[id]" value="<?=$user->id;?>" />
                        <input type="submit" name="submit" value="Delete" <?=($user->id == $_SESSION['id']) ? 'disabled' : '';?>/>
                    </form>
                </td>
            </tr>
    <?php endforeach; ?>
        </thead>
    </table>    
</section>