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
    <label class="required" for="username">Username</label> 
    <input type="text" name="user[username]" id="username" value="<?php if (isset($_GET['id'])) { echo htmlspecialchars(strip_tags($user->username), ENT_QUOTES, 'UTF-8'); } elseif (isset($_POST['user'])) { echo $_POST['user']['username']; } ?>" <?=(isset($_GET['id'])) ? 'readonly' : '';?>>
    
    <label class="required" for="firstname">First Name</label> 
    <input type="text" name="user[firstname]" id="firstname" value="<?php if (isset($_GET['id'])) { echo htmlspecialchars(strip_tags($user->firstname), ENT_QUOTES, 'UTF-8'); } elseif (isset($_POST['user'])) { echo $_POST['user']['firstname']; } ?>">
    
    <label class="required" for="surname">Surname</label> 
    <input type="text" name="user[surname]" id="surname" value="<?php if (isset($_GET['id'])) { echo htmlspecialchars(strip_tags($user->surname), ENT_QUOTES, 'UTF-8'); } elseif (isset($_POST['user'])) { echo $_POST['user']['surname']; } ?>">
    
    <label class="required" for="email">Email Address</label> 
    <input type="text" name="user[email]" id="email" value="<?php if (isset($_GET['id'])) { echo htmlspecialchars(strip_tags($user->email), ENT_QUOTES, 'UTF-8'); } elseif (isset($_POST['user'])) { echo $_POST['user']['email']; } ?>">
    
    <label <?=(!isset($_GET['id'])) ? 'class="required"' : '';?> for="password">Password</label> 
    <input type="password" name="user[password]" id="passsword">

    <label>Role</label>
    <select name="user[role]" <?php if ($_GET['id'] != $_SESSION['id'] && ($user->role == 2 || $user->role == 1 || $user->role == 0)) { echo ''; } else { echo 'disabled'; } ?>>
        <?php if ($user->role == 3): ?>
            <option <?=(isset($_GET['id']) && $user->role == 3) ? 'selected="selected"' : '';?> value="3">Owner</option>               
        <?php endif; ?>
        
        <?php if (!isset($_GET['id']) && isset($_SESSION['isOwner']) || isset($_SESSION['isOwner']) || $user->role == 3 || $user->role == 2): ?>
            <option <?=(isset($_GET['id']) && $user->role == 2) ? 'selected="selected"' : '';?> value="2">Administrator</option>
        <?php endif; ?>

        <?php if (isset($_SESSION['isOwner']) || isset($_SESSION['isAdmin']) || $user->role != 0): ?>
            <option <?=(isset($_GET['id']) && $user->role == 1) ? 'selected="selected"' : '';?> value="1">Employee</option>
        <?php endif; ?>

        <option <?=(isset($_GET['id']) && $user->role == 0) ? 'selected="selected"' : '';?> value="0">Client</option>
    </select> 

    <label>Active</label>
    <select name="user[active]" <?php if ($_GET['id'] != $_SESSION['id'] && ($user->role == 2 || $user->role == 1 || $user->role == 0)) { echo ''; } else { echo 'disabled'; } ?>>
        <option <?=(isset($_GET['id']) && $user->active == 1) ? 'selected="selected"' : '';?> value="1">True</option>
        <option <?=(isset($_GET['id']) && $user->active == 0) ? 'selected="selected"' : '';?> value="0">False</option>
    </select> 
    
    <input type="submit" name="submit" value="Save">
</form>