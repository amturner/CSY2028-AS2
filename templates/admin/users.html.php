<main class="sidebar">
    <?php require 'adminnav.html.php'; ?>

    <section class="right">
        <h2>Users</h2>

        <a class="new" href="/admin/users/adduser">Add new user</a>

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
                    <td><?=$user->username;?></td>
                    <td><?=$user->email;?></td>
                    <td><?=($user->administrator) == 1 ? 'true' : 'false' ;?></td>
                    <td><?=($user->active) == 1 ? 'true' : 'false' ;?></td>
                    <td><a style="float: right" href="/admin/users/edit?id=<?=$user->id;?>">Edit</a></td>
                    <td>
                        <form method="post" action="/admin/categories/delete">
                            <input type="hidden" name="id" value="<?=$user->id;?>" />
                            <input type="submit" name="submit" value="Delete" />
                        </form>
                    </td>
                </tr>
        <?php endforeach; ?>
            </thead>
        </table>    
    </section>
</main>
