<section class="left">
    <?php require 'adminnav.html.php'; ?>
</section>

<section class="right">
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
</section>