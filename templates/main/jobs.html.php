<section class="left">
    <?php require 'jobnav.html.php'; ?>
</section>

<section class="right">
    <?php if (isset($categoryName) && isset($jobs) || isset($categoryName) && isset($locationTown) && isset($jobs)): ?>
        <form class="filter" action="" method="get">
            <input type="hidden" name="category" value="<?=(isset($categoryName)) ? $categoryName : '';?>" />
            <label>Location</label>
            <select name="location">
                <?php if (isset($_GET['location']) && isset($locationTown) && count($locations) > 1): ?>
                    <option selected="selected" value="<?=ucwords(urlencode($locationTown));?>"><?=ucwords(urldecode($locationTown));?></option>
                <?php endif; ?>
                <option value="All">All</option>
                <?php foreach ($locations as $location): ?>
                    <?php if (htmlspecialchars(strip_tags($location), ENT_QUOTES, 'UTF-8') != ucwords(urldecode($_GET['location'])) && count($locations) > 1): ?>
                        <option value="<?=ucwords(urlencode(htmlspecialchars(strip_tags($location), ENT_QUOTES, 'UTF-8')));?>"><?=htmlspecialchars(strip_tags($location), ENT_QUOTES, 'UTF-8');?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>

            <button>Apply</button>
        </form>
        <?php if (isset($jobs) && count($jobs) > 0): ?>
            <h1><?=$categoryName;?> Jobs</h1>
            <ul class="listing">
                <?php foreach ($jobs as $job): ?>
                    <li>
                        <div class="details">
                            <h2><?=$job->title;?></h2>
                            <h3><?='Location: ' . $job->getFullLocation();?></h3>
                            <h3><?='Salary: ' . $job->salary;?></h3>
                            <p><?=nl2br($job->description);?></p>

                            <a class="more" href="/jobs/apply?id=<?=$job->id;?>">Apply for this job</a>
                            <h4>Closing Date: <?=$job->getClosingDate();?></h4>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php elseif (isset($jobs) && count($jobs) == 0): ?>
            <h1><?=$categoryName;?> Jobs</h1>
            <p>There are currently no jobs listed under the category <b><?=$categoryName;?></b><?=(isset($locationTown)) ? ' in <b>' . $locationTown . '</b>' : '';?>. Please check back again later!</p>
        <?php endif; ?>
    <?php elseif (isset($_GET['category']) && !isset($categoryName)): ?>
        <h1>Category Not Found</h1>
        <p>The category that you have specified does not exist. Please ensure the category name is correct before trying again.</p> 
    <?php elseif (isset($_GET['location']) && !isset($locationTown)): ?>
        <h1>Location Not Found</h1>
        <p>The location that you have specified does not exist. Please ensure the location name is correct before trying again.</p> 
    <?php else: ?>
        <h2>Select the type of job you are looking for:</h2>

        <?php require 'jobnav.html.php'; ?>
    <?php endif; ?>
</section>