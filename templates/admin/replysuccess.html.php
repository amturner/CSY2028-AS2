<?php require 'userpanel.html.php'; ?>
<h2>Replying to <?=htmlspecialchars(strip_tags($enquiry->getFullname('firstname')), ENT_QUOTES, 'UTF-8');?> (<?=htmlspecialchars(strip_tags($enquiry->email), ENT_QUOTES, 'UTF-8');?>)</h2>

<p>Your reply has successfully been sent!</p>
<p><a href="/admin/enquiries">View enquiries</a></p>