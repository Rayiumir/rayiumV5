<?php

defined('ABSPATH') || exit;

global $post;

$label = 'ناموفق';
$selected = $post->post_status == 'failed' ? 'selected' : '';
?>

<script>
    jQuery(document).ready(function($){
        $('select#post_status').append(
            `<option value="failed"><?php echo $label; ?></option>`
        );

        <?php if($post->post_status == 'failed') : ?>
            $('#post-status-display').text(`<?php echo $label; ?>`);
        <?php endif; ?>

        $('a.save-post-status').click(function(e){
            if($('select#post_status').val() == 'failed'){
                setTimeout(() => {
                    $('input#save-post').val('ناموفق ذخیره شد.');
                }, 5);
            }
        });
    });
</script>