<section class="left">
    <?php require 'adminnav.html.php'; ?>
</section>

<section class="right">
    <?php require 'userpanel.html.php'; ?>
    <h2><?=(isset($_GET['id'])) ? 'Edit Job' : 'Add Job';?></h2>
    <?php if (isset($errors) && count($errors) > 0): ?>
        <div class="errors">
            <p>The job could not be <?=(isset($_GET['id'])) ? 'updated' : 'added';?>:</p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?=$error;?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form action="" method="POST">
        <p class="required">Required: </p>
        <input type="hidden" name="job[id]" value="<?=(isset($_GET['id'])) ? $job->id : '';?>" />
        <label class="required">Title</label>
        <input type="text" name="job[title]" value="<?=(isset($_GET['id'])) ? htmlspecialchars(strip_tags($job->title), ENT_QUOTES, 'UTF-8') : '';?>" />

        <label class="required">Description</label>
        <textarea name="job[description]"><?=(isset($_GET['id'])) ? htmlspecialchars(strip_tags($job->description), ENT_QUOTES, 'UTF-8') : '';?></textarea>

        <label class="required">Location</label>
        <input type="text" name="job[location]" value="<?=(isset($_GET['id'])) ? htmlspecialchars(strip_tags($job->location), ENT_QUOTES, 'UTF-8') : '';?>" />

        <label class="required">Salary</label>
        <input type="text" name="job[salary]" value="<?=(isset($_GET['id'])) ? htmlspecialchars(strip_tags($job->salary), ENT_QUOTES, 'UTF-8') : '';?>" />

        <label>Category</label>
        <select name="job[categoryId]">
        <?php foreach ($categories as $category): ?>
            <?php if (isset($_GET['id'])): ?>
                <?php if ($job->categoryId == $category->id): ?>
                    <option selected="selected" value="<?=$category->id;?>"><?=htmlspecialchars(strip_tags($category->name), ENT_QUOTES, 'UTF-8');?></option>
                <?php else: ?>
                    <option value="<?=$category->id;?>"><?=htmlspecialchars(strip_tags($category->name), ENT_QUOTES, 'UTF-8');?></option>
                <?php endif; ?>
            <?php else: ?>    
                <option value="<?=$category->id;?>"><?=htmlspecialchars(strip_tags($category->name), ENT_QUOTES, 'UTF-8');?></option>
            <?php endif; ?>
        <?php endforeach; ?>

        </select>

        <label class="required">Closing Date</label>
        <input type="date" name="job[closingDate]" value="<?=(isset($_GET['id'])) ? $job->closingDate : '';?>"  />

        <input type="submit" name="submit" value="Save" />
    </form>
</section>