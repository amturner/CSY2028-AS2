<section class="left">
    <?php require 'adminnav.html.php'; ?>
</section>

<section class="right">
    <?php require 'userpanel.html.php'; ?>
    <h2>Jobs</h2>

    <a class="new" href="/admin/jobs/edit">Add new job</a>

        <form class="filter" action="" method="get">
            <label>Filter: </label>
            <select name="filterBy">
                <?php if (isset($_GET['filterBy']) && isset($categoryName)): ?>
                    <option selected="selected" value="<?=ucwords(urlencode($categoryName));?>"><?=ucwords(urldecode($categoryName));?></option>
                <?php endif; ?>
            <option value="All">All</option>
            <?php foreach ($categories as $category): ?>
                <?php if (htmlspecialchars(strip_tags($category->name), ENT_QUOTES, 'UTF-8') != ucwords(urldecode($_GET['filterBy']))): ?>
                    <option value="<?=ucwords(urlencode(htmlspecialchars(strip_tags($category->name), ENT_QUOTES, 'UTF-8')));?>"><?=htmlspecialchars(strip_tags($category->name), ENT_QUOTES, 'UTF-8');?></option>
                <?php endif; ?>
            <?php endforeach; ?>
            </select>

            <button>Apply</button>
        </form>

    <?php if (isset($categoryName)): ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th style="width: 15%">Salary</th>
                    <th style="width: 15%">Category</th>
                    <th style="width: 5%">&nbsp;</th>
                    <th style="width: 15%">&nbsp;</th>
                    <th style="width: 5%">&nbsp;</th>
                </tr>

        <?php foreach ($jobs as $job): ?>
                <tr>
                    <td><?=htmlspecialchars(strip_tags($job->title), ENT_QUOTES, 'UTF-8');?></td>
                    <td><?=htmlspecialchars(strip_tags($job->salary), ENT_QUOTES, 'UTF-8');?></td>
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
    <?php else: ?>
        <p>The category <b><?=ucwords(urldecode(htmlspecialchars(strip_tags($_GET['filterBy']), ENT_QUOTES, 'UTF-8')));?></b> does not exist. Please try applying a different filter.</p>
    <?php endif; ?>
</section>