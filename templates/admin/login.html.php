<h2>Log in</h2>
<?=(isset($error)) ? '<p>' . $error . '</p>' : '';?>
<form action="" method="post">
    <label for="username">Username:</label> <input type="text" name="login[username]" id="username">
    <label for="password">Password:</label> <input type="password" name="login[password]" id="passsword">
    <input type="submit" name="submit" value="Submit">
</form>