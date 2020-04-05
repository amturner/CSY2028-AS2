<?php require 'userpanel.html.php'; ?>
<?php if (count($applicants) > 0): ?>
    <h2>Applicants for <?=$title;?></h2>

    <table>
        <thead>
            <tr>
                <th style="width: 10%">Name</th>
                <th style="width: 10">Email</th>
                <th style="width: 65%">Details</th>
                <th style="width: 15%">CV</th>
            </tr>

    <?php foreach ($applicants as $applicant): ?>
            <tr>
                <td><?=$applicant->name;?></td>
                <td><?=$applicant->email;?></td>
                <td><?=$applicant->details;?></td>
                <td><a href="/cvs/<?=$applicant->cv;?>">Download CV</a></td>
            </tr>
    <?php endforeach; ?>
        </thead>
    </table>    
<?php else: ?>
    <h2>Applicants for <?=$title;?></h2>
    <p>This job currently has no applicants.</p>    
<?php endif; ?>