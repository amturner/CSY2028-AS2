<section class="left">
    <?php require 'jobnav.html.php'; ?>
</section>

<section class="right">
    <?php if ($title != ''): ?>
        <h2>Apply for <?=$title;?></h2>
        <?php if (isset($errors) && count($errors) > 0): ?>
            <div class="errors">
                <p>Your application could not be submitted:</p>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?=$error;?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <label>Your name</label>
            <input type="text" name="apply[name]" />

            <label>E-mail address</label>
            <input type="text" name="apply[email]" />

            <label>Cover letter</label>
            <textarea name="apply[details]"></textarea>

            <label>CV</label>
            <input type="file" name="cv" />

            <input type="hidden" name="apply[jobId]" value="<?=$jobId;?>" />
            <input type="submit" name="submit" value="Apply" />
        </form>
    <?php else: ?>
        <h2>Job Not Found</h2>
        <p>The job you're applying for does not exist.</p>
    <?php endif; ?>
</section>