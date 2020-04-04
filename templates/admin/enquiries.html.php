<section class="left">
    <?php require 'adminnav.html.php'; ?>
</section>

<section class="right">
    <?php require 'userpanel.html.php'; ?>
    <h2><?=$title;?></h2>

    <?php if ($parameters[0] == 'active'): ?>
        <p><a class="new" href="/admin/enquiries/archive">View previous enquiries</a></p>
    <?php elseif ($parameters[0] == 'archived'): ?>
        <p><a class="new" href="/admin/enquiries/active">View enquiries</a></p>      
    <?php endif; ?>

    <?php if (count($enquiries) > 0): ?>
        <?php if ($parameters[0] == 'active'): ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th style="width: 10%">Phone</th>
                        <th style="width: 40%">Message</th>
                        <th style="width: 5%">&nbsp;</th>
                        <th style="width: 5%">&nbsp;</th>
                    </tr>

            <?php foreach ($enquiries as $enquiry): ?>
                <tr>
                    <td><?=htmlspecialchars(strip_tags($enquiry->getFullName('firstname')), ENT_QUOTES, 'UTF-8');?></td>
                    <td><?=htmlspecialchars(strip_tags($enquiry->email), ENT_QUOTES, 'UTF-8');?></td>
                    <td><?=$enquiry->phone;?></td>
                    <td><?=htmlspecialchars(strip_tags($enquiry->message), ENT_QUOTES, 'UTF-8');?></td>
                    <td><a style="float: right" href="/admin/enquiries/reply?id=<?=$enquiry->id;?>">Reply</a></td>
                    
                    <td>
                        <form method="post" action="/admin/enquiries/delete">
                            <input type="hidden" name="enquiry[id]" value="<?=$enquiry->id;?>" />
                            <input type="submit" name="submit" value="Delete" />
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
                </thead>
            </table>
        <?php elseif ($parameters[0] == 'archived'): ?>
            <?php foreach ($enquiries as $enquiry): ?>
                <article class="enquiry-reply">
                    <div class="reply">
                        <h3>Original Enquiry</h3>
                        <p>
                            <b>Name</b>: <?=$enquiry->getFullName('surname');?><br>
                            <b>Email</b>: <?=$enquiry->email;?><br>
                            <b>Phone</b>: <?=$enquiry->phone;?><br><br>
                            <b>Message</b>:<br>
                            <?=strip_tags(nl2br(htmlspecialchars($enquiry->message, ENT_QUOTES, 'UTF-8')), '<br>');?>
                        </p>
                    </div>

                    <?php foreach ($enquiryReplies as $reply): ?>
                        <?php if ($reply->enquiry_id == $enquiry->id): ?>
                            <?php foreach ($users as $user): ?>
                                <?php if ($user->id == $reply->user_id): ?>
                                    <div class="reply">
                                        <h3>Staff Reply</h3>
                                        <p>
                                            <b>Name</b>: <?=$user->getFullName('surname');?><br>
                                            <b>Email</b>: <?=$user->email;?><br><br>
                                            <b>Message</b>:<br>
                                            <?=strip_tags(nl2br(htmlspecialchars($reply->message, ENT_QUOTES, 'UTF-8')), '<br>');?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </article>
                <hr>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php else: ?>
        <?php if ($parameters[0] == 'active'): ?>
            <p>There are currently no outstanding enquiries.</p>
        <?php elseif ($parameters[0] == 'archived'): ?>
            <p>There are currently no replies to any enquiries.</p>
        <?php endif; ?>
    <?php endif; ?>
</section>