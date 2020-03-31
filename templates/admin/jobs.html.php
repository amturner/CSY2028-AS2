<main class="sidebar">
    <?php require 'adminnav.html.php'; ?>

    <section class="right">
        <h2>Jobs</h2>

        <a class="new" href="/admin/jobs/addjob">Add new job</a>

        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th style="width: 15%">Salary</th>
                    <th style="width: 5%">&nbsp;</th>
                    <th style="width: 15%">&nbsp;</th>
                    <th style="width: 5%">&nbsp;</th>
                    <th style="width: 5%">&nbsp;</th>
                </tr>

        <?php foreach ($jobs as $job): ?>
                <tr>
                    <td><?=$job->title;?></td>
                    <td><?=$job->salary;?></td>
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
    </section>
</main>
