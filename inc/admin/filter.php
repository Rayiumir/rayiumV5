<?php $author = isset($_GET['author']) ? intval($_GET['author']) : 0 ?>
<lable for="student">
    <select name="author" id="student">
        <option <?php selected(! $author); ?>>همه دانشجویان ...</otion>
        <?php foreach(rayium_get_students() as $row) :?>
        <option <?php selected($author == $row->ID); ?> value="<?php echo $row->ID; ?>"><?php echo $row->display_name; ?></otion>
        <?php endforeach; ?>
    </select>
</lable>

