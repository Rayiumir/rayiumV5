<?php defined('ABSPATH') || exit; $counter = 1; ?>

<table class="rm_playlist_table">
    <thead>
        <tr>
            <th></th>
            <th>#</th>
            <th>عنوان</th>
            <th>پیوند</th>
            <th>نوع دسترسی</th>
            <th>اطلاعات فایل</th>
            <th>عملیات</th>
        </tr>
    </thead>
    <tbody>
        <?php if($playlists->have_posts()) : ?>
        <?php while ($playlists->have_posts()) :
            $playlists->the_post(); global $post; ?>
            <?php
                $width = get_post_meta( get_the_ID(), '_width', true);
                $height = get_post_meta( get_the_ID(), '_height', true);
                $duration = get_post_meta( get_the_ID(), '_duration', true);
            ?>
                <tr>
                    <td><span class="dashicons dashicons-move"></span></td>
                    <td><?php echo $counter++; ?></td>
                    <td class="rayium_playlist_input">
                        <input type="hidden" name="rayium_playlist[ids][]" value="<?php the_ID() ?>">
                        <input type="hidden" class="rm_item_width" name="rayium_playlist[widths][]" value="<?php echo esc_attr($width) ?>">
                        <input type="hidden" class="rm_item_height" name="rayium_playlist[heights][]" value="<?php echo esc_attr($height) ?>">
                        <input type="hidden" class="rm_item_duration" name="rayium_playlist[durations][]" value="<?php echo esc_attr($duration) ?>">
                        <input type="text" name="rayium_playlist[titles][]" value="<?php echo esc_attr($post->post_title); ?>">
                    </td>
                    <td class="rayium_playlist_input"><input type="url" name="rayium_playlist[urls][]" value="<?php echo esc_attr($post->guid); ?>"></td>
                    <td>
                        <select name="rayium_playlist[statuses][]">
                            <option value="free" <?php selected($post->post_status, 'free'); ?>>رایگان</option>
                            <option value="premium" <?php selected($post->post_status, 'premium'); ?>>نقدی</option>
                        </select>
                    </td>
                    <td><p class="rayium_duration"><?php echo $duration ? rayium_second_to_time($duration) : '--:--' ?></p>
                    <img src="<?php echo admin_url('images/spinner.gif'); ?>" alt="spinner" width="24" height="24" class="rm_spinner" srcset="">
                    </td>
                    <td><span class="dashicons dashicons-trash"></span></td>
                </tr>
        <?php endwhile; ?>
        <?php else: ?>
        <tr class="rm_no_item">
            <td colspan="7">
                <p>لیست پخشی وجود ندارد</p>
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<button type="button" class="button button-primary rm_add_playlist_item" style="margin-top: 10px;">افزودن فیلد جدید</button>

<script type="text/template" id="rm_no_item">
    <tr class="rm_no_item">
        <td colspan="7">
            <p>لیست پخشی وجود ندارد</p>
        </td>
    </tr>
</script>
<script type="text/template" id="tp_item">
    <tr>
        <td><span class="dashicons dashicons-move"></span></td>
        <td><?php echo $counter++; ?></td>
        <td class="rayium_playlist_input">
            <input type="hidden" name="rayium_playlist[ids][]" value="0">
            <input type="hidden" class="rm_item_width" name="rayium_playlist[widths][]" value="0">
            <input type="hidden" class="rm_item_height" name="rayium_playlist[heights][]" value="0">
            <input type="hidden" class="rm_item_duration" name="rayium_playlist[durations][]" value="0">
            <input type="text" name="rayium_playlist[titles][]" value="">
        </td>
        <td class="rayium_playlist_input"><input type="url" name="rayium_playlist[urls][]" value=""></td>
        <td>
            <select name="rayium_playlist[statuses][]">
                <option value="free">رایگان</option>
                <option value="premium">نقدی</option>
            </select>
        </td>
        <td>
            <p class="rayium_duration">--:--</p>
            <img src="<?php echo admin_url('images/spinner.gif'); ?>" alt="spinner" width="24" height="24" class="rm_spinner" srcset="">
        </td>
        <td><span class="dashicons dashicons-trash"></span></td>
    </tr>
</script>



