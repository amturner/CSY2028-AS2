<section class="left">
    <?php require 'jobnav.html.php'; ?>
</section>

<section class="right">
    <h1>Job Details</h1>
    <ul class="listing">
        <li>
            <div class="details">
                <h2><?=$job->title;?></h2>
                <h3><?='Location: ' . $job->getFullLocation();?></h3>
                <h3><?='Salary: ' . $job->salary;?></h3>
                <p><?=strip_tags(nl2br(htmlspecialchars($job->description, ENT_QUOTES, 'UTF-8')), '<br>');?></p>

                <a class="more" href="/jobs/apply?id=<?=$job->id;?>">Apply for this job</a>
                <h4>Closing Date: <?=$job->getClosingDate();?></h4>
            </div>
        </li>
    </ul>
</section>