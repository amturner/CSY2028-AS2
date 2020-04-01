<section class="left">
    <?php require 'adminnav.html.php'; ?>
</section>

<section class="right">
    <?php require 'userpanel.html.php'; ?>
    <h2><?=(isset($_GET['id'])) ? 'Edit user' : 'Add user';?></h2>
    <?php if (isset($errors) && count($errors) > 0): ?>
        <div class="errors">
            <p>The user could not be <?=(isset($_GET['id'])) ? 'updated' : 'added';?>:</p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?=$error;?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form action="" method="post">
        <p class="required">Required: </p>
        <input type="hidden" name="user[id]" value="<?=(isset($_GET['id'])) ? $user->id : '';?>" />
        <label class="required" for="username">Username</label> <input type="text" name="user[username]" id="username" value="<?=(isset($_GET['id'])) ? htmlspecialchars(strip_tags($user->username), ENT_QUOTES, 'UTF-8') : '';?>" <?=(isset($_GET['id'])) ? 'readonly' : '';?>>
        <label class="required" for="email">Email Address</label> <input type="text" name="user[email]" id="email" value="<?=(isset($_GET['id'])) ? htmlspecialchars(strip_tags($user->email), ENT_QUOTES, 'UTF-8') : '';?>">
        <label <?=(!isset($_GET['id'])) ? 'class="required"' : '';?> for="password">Password</label> <input type="password" name="user[password]" id="passsword">
        <input type="submit" name="submit" value="Save">
    </form>
</section>