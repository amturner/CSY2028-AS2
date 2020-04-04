<p>Welcome to Jo's Jobs, we're a recruitment agency based in Northampton. We offer a range of different office jobs. Get in touch if you'd like to list a job with us.</a></p>

<h2>Select the type of job you are looking for:</h2>
<?php require 'jobnav.html.php'; ?>

<h2>Hurry! These jobs are closing soon:</h2>
<?php if (count($jobs) > 0): ?>
    <div class="closing-soon">
        <?php foreach ($jobs as $job): ?>
            <article class="job">
                <h3><?=$job->title;?></h3>
                <p>
                    <b>Category: <?=htmlspecialchars(strip_tags($job->getCategoryName()), ENT_QUOTES, 'UTF-8');?></b><br>
                    <b>Salary: <?=htmlspecialchars(strip_tags($job->salary), ENT_QUOTES, 'UTF-8');?></b>
                </p>
                <p>
                    <a href="/jobs/job?id=<?=$job->id;?>">View details</a> | <a href="/jobs/apply?id=<?=$job->id;?>">Apply for this job</a><br>
                    <b>Closing Date: <?=$job->getClosingDate();?></b>
                </p>
            </article>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>There are currently no jobs.</p>
<?php endif; ?>