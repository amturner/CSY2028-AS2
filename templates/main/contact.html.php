<h2>Contact Us</h2>

<?php if (isset($errors) && count($errors) > 0): ?>
    <div class="errors">
        <p>Your message could not be sent:</p>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?=$error;?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form class="clearfix" action="" method="post">
    <p class="required">Required: </p>
    <label class="required" for="firstname">First Name</label>
    <input type="text" name="contact[firstname]" id="name" value="<?=(isset($_POST['contact'])) ? $_POST['contact']['firstname'] : '';?>">

    <label class="required" for="surname">Surname</label>
    <input type="text" name="contact[surname]" id="name" value="<?=(isset($_POST['contact'])) ? $_POST['contact']['surname'] : '';?>">
    
    <label class="required" for="email">Email</label>
    <input type="text" name="contact[email]" id="email" value="<?=(isset($_POST['contact'])) ? $_POST['contact']['email'] : '';?>">    
    
    <label for="phone">Phone</label>
    <input type="text" name="contact[phone]" id="phone" value="<?=(isset($_POST['contact'])) ? $_POST['contact']['phone'] : '';?>">

    <label class="required" for="message">Message</label>
    <textarea name="contact[message]" id="message" placeholder="Type your message here."><?=(isset($_POST['contact'])) ? $_POST['contact']['message'] : '';?></textarea>

    <input type="submit" name="submit" value="Send">
</form>