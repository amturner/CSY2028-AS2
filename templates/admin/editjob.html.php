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
    <input type="text" name="job[title]" value="<?php if (isset($_GET['id'])) { echo htmlspecialchars(strip_tags($job->title), ENT_QUOTES, 'UTF-8'); } elseif (isset($_POST['job'])) { echo $_POST['job']['title']; } ?>" />

    <label class="required">Description</label>
    <textarea name="job[description]"><?php if (isset($_GET['id'])) { echo htmlspecialchars(strip_tags($job->description), ENT_QUOTES, 'UTF-8'); } elseif (isset($_POST['job'])) { echo $_POST['job']['description']; } ?></textarea>

    <label>Location</label>
    <select name="job[locationId]">
        <?php foreach ($locations as $location): ?>
            <?php if (isset($_GET['id'])): ?>
                <?php if ($job->getTown() == $location->town): ?>
                    <option selected="selected" value="<?=$location->id;?>"><?=htmlspecialchars(strip_tags($location->town), ENT_QUOTES, 'UTF-8');?></option>
                <?php else: ?>
                    <option value="<?=$location->id;?>"><?=htmlspecialchars(strip_tags($location->town), ENT_QUOTES, 'UTF-8');?></option>
                <?php endif; ?>
            <?php else: ?>    
                <option value="<?=$location->id;?>"><?=htmlspecialchars(strip_tags($location->town), ENT_QUOTES, 'UTF-8');?></option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>

    <label class="required">Salary</label>
    <input type="text" name="job[salary]" value="<?php if (isset($_GET['id'])) { echo htmlspecialchars(strip_tags($job->salary), ENT_QUOTES, 'UTF-8'); } elseif (isset($_POST['job'])) { echo $_POST['job']['salary']; } ?>" />

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
    <input type="date" name="job[closingDate]" value="<?php if (isset($_GET['id'])) { echo htmlspecialchars(strip_tags($job->closingDate), ENT_QUOTES, 'UTF-8'); } elseif (isset($_POST['job'])) { echo $_POST['job']['closingDate']; } ?>"  />
    <input type="hidden" name="job[userId]" value="<?=$_SESSION['id'];?>" />
    
    <input type="submit" name="submit" value="Save" />
</form>