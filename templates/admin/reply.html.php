<section class="left">
    <?php require 'adminnav.html.php'; ?>
</section>

<section class="right">
    <?php require 'userpanel.html.php'; ?>
    <h2>Replying to <?=htmlspecialchars(strip_tags($enquiry->getFullname('firstname')), ENT_QUOTES, 'UTF-8');?> (<?=htmlspecialchars(strip_tags($enquiry->email), ENT_QUOTES, 'UTF-8');?>)</h2>

    <?php if (isset($errors) && count($errors) > 0): ?>
        <div class="errors">
            <p>Your reply could not be sent:</p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?=$error;?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form action="" method="post">      
        <input type="hidden" name="reply[user_id]" value="<?=$_SESSION['id'];?>" />
        <input type="hidden" name="reply[enquiry_id]" value="<?=$enquiry->id;?>" />
        <label for="enquiry-message">Enquiry Message</label>
        <textarea id="enquiry-message" disabled><?=htmlspecialchars(strip_tags($enquiry->message), ENT_QUOTES, 'UTF-8');?></textarea>

        <label for="message">Message</label>
        <textarea name="reply[message]" id="message" placeholder="Type your reply here."></textarea>

        <input type="submit" name="submit" value="Send">
    </form>
</section>