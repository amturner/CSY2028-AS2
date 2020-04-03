<section class="left">
    <?php require 'adminnav.html.php'; ?>
</section>

<section class="right">
    <?php require 'userpanel.html.php'; ?>
    <h2><?=$title;?></h2>

    <?php if ($parameters[0] == 'active'): ?>
        <a class="new" href="/admin/jobs/edit">Add new job</a> |
        <a class="new" href="/admin/jobs/archive">View archived jobs</a>
    <?php elseif ($parameters[0] == 'archived'): ?>
        <a class="new" href="/admin/jobs/active">View jobs</a>
    <?php endif; ?>

    <form class="filter" action="" method="get">
        <label>Category</label>
        <select name="category">
            <?php if (isset($_GET['category']) && isset($categoryName) && count($categoryChoices) > 1): ?>
                <option selected="selected" value="<?=ucwords(urlencode($categoryName));?>"><?=ucwords(urldecode($categoryName));?></option>
            <?php endif; ?>
            <option value="All">All</option>
            <?php foreach ($categoryChoices as $categoryChoice): ?>
                <?php if (htmlspecialchars(strip_tags($categoryChoice), ENT_QUOTES, 'UTF-8') != ucwords(urldecode($_GET['category'])) && count($categoryChoices) > 1): ?>
                    <option value="<?=ucwords(urlencode(htmlspecialchars(strip_tags($categoryChoice), ENT_QUOTES, 'UTF-8')));?>"><?=htmlspecialchars(strip_tags($categoryChoice), ENT_QUOTES, 'UTF-8');?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>

        <button>Apply</button>
    </form>

    <?php if ((isset($categoryName) || isset($_GET['category']) && $_GET['category'] == 'All' || !isset($_GET['category'])) && count($jobs) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th style="width: 15%">Salary</th>
                    <th style="width: 10%">Closing Date</th>
                    <th style="width: 15%">Category</th>
                    <th style="width: 5%">&nbsp;</th>
                    <th style="width: 15%">&nbsp;</th>
                    <th style="width: 5%">&nbsp;</th>
                </tr>

        <?php foreach ($jobs as $job): ?>
                <tr>
                    <td><?=htmlspecialchars(strip_tags($job->title), ENT_QUOTES, 'UTF-8');?></td>
                    <td><?=htmlspecialchars(strip_tags($job->salary), ENT_QUOTES, 'UTF-8');?></td>
                    <td><?=htmlspecialchars(strip_tags($job->getClosingDate()), ENT_QUOTES, 'UTF-8');?></td>
                    <td><?=htmlspecialchars(strip_tags($job->getCategoryName()), ENT_QUOTES, 'UTF-8');?></td>
                    <td><a style="float: right" href="/admin/jobs/edit?id=<?=$job->id;?>">Edit</a></td>
                    <td><a style="float: right" href="/admin/jobs/applicants?id=<?=$job->id;?>">View applicants (<?=$job->getApplicantsCount();?>)</a></td>
                    <td>
                        <form method="post" action="/admin/jobs/delete">
                            <input type="hidden" name="id" value="<?=$job->id;?>" />
                            <input type="submit" name="submit" value="Delete" />
                        </form>
                    </td>
                </tr>
        <?php endforeach; ?>
            </thead>
        </table>
    <?php elseif (isset($jobs) && count($jobs) == 0): ?>
        <?php if (isset($categoryName)): ?>
            <p>The category <b><?=$categoryName;?></b> currently has no jobs.</p>    
        <?php else: ?>
            <?php if ($parameters[0] == 'active'): ?>
                <p>You have not yet posted any job listings. Click <a href="/admin/jobs/edit">here</a> to post your first one! </p>
            <?php elseif ($parameters[0] == 'archived'): ?>
                <p>No jobs have yet been archived.</p>
            <?php endif; ?>
        <?php endif; ?>
    <?php else: ?>
        <p>The category <b><?=ucwords(urldecode(htmlspecialchars(strip_tags($_GET['category']), ENT_QUOTES, 'UTF-8')));?></b> does not exist. Please try applying a different filter.</p>
    <?php endif; ?>
</section>