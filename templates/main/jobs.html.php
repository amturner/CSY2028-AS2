<section class="left">
    <?php require 'jobnav.html.php'; ?>
</section>

<section class="right">
    <?php if (isset($categoryName) && isset($categoryName)): ?>
        <?php if (isset($jobs) && count($jobs) > 0): ?>
            <h1><?=$categoryName;?> Jobs</h1>
            <ul class="listing">
                <?php foreach ($jobs as $job): ?>
                    <li>
                        <div class="details">
                            <h2><?=$job->title;?></h2>
                            <h3><?=$job->salary;?></h3>
                            <p><?=nl2br($job->description);?></p>

                            <a class="more" href="/apply.php?id=<?=$job->id;?>">Apply for this job</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php elseif (isset($jobs) && count($jobs) == 0): ?>
            <h1><?=$categoryName;?> Jobs</h1>
            <p>There are currently no jobs listed under the category '<?=$categoryName;?>'. Please check back again later!</p>
        <?php else: ?>
        <p>rl</p>
        <?php endif; ?>
    <?php elseif (isset($_GET['category']) && !isset($categoryName)): ?>
        <h1>Category Not Found</h1>
        <p>The category that you have specified does not exist. Please ensure the category name is correct before trying again.</p>
    <?php else: ?>
        <h2>Select the type of job you are looking for:</h2>

        <?php require 'jobnav.html.php'; ?>
    <?php endif; ?>
</section>