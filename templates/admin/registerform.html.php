<main class="sidebar">
    <?php require 'adminnav.html.php'; ?>

    <section class="right">
        <h2>Create new account</h2>
        <?php if (isset($errors) && count($errors) > 0): ?>
            <div class="errors">
                <p>The account could not be created:</p>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?=$error;?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form action="" method="post">
            <p class="required">Required: </p>
            <label class="required" for="username">Username:</label> <input type="text" name="register[username]" id="username" <?=(isset($_POST['register'])) ? 'value="' . $_POST['register']['username'] . '"': '';?>>
            <label class="required" for="email">Email Address:</label> <input type="text" name="register[email]" id="email" <?=(isset($_POST['register'])) ? 'value="' . $_POST['register']['email'] . '"': '';?>>
            <label class="required" for="password">Password:</label> <input type="password" name="register[password]" id="passsword">
            <input type="submit" name="submit" value="Submit">
        </form>
    </section>
</main>