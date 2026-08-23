<?php
$price = get_post_meta( $post->ID, '_price', true );
$percent = get_post_meta( $post->ID, '_discount_price', true );
$gateway = get_post_meta( $post->ID, '_gateway', true );
$payment_id = get_post_meta( $post->ID, '_bitpay_payment_id', true );
$trans_id = get_post_meta( $post->ID, '_bitpay_trans_id', true );
$error_message = get_post_meta( $post->ID, '_error_message', true );
$key = $post->post_excerpt;
$sale_price = $post->menu_order;
$stadent = get_user_by( 'ID', $post->post_author );
?>

<table class="striped" id="course_register_table" style="width:100%; padding:5px;">
    <tr>
        <td style="width:150px;">قیمت دوره : </td>
        <td><?php echo number_format($price / 10); ?> تومان</td>
    </tr>
    <tr>
        <td style="width:150px">قیمت فروش : </td>
        <td><?php echo number_format($sale_price / 10); ?> تومان</td>
    </tr>
    <tr>
        <td style="width:150px">تخفیف دوره : </td>
        <td><?php echo $percent; ?> % تخفیف</td>
    </tr>
    <tr>
        <td style="width:150px">دانشجو : </td>
        <td><a href="<?php echo admin_url('edit.php?post_type=course_register&author=' . $stadent->ID)?>"><?php echo $stadent->display_name; ?></a></td>
    </tr>
    <tr>
        <td style="width:150px">نوع درگاه : </td>
        <td><?php echo $gateway; ?></td>
    </tr>
    <tr>
        <td style="width:150px">شناسه پرداخت : </td>
        <td><?php echo $payment_id; ?></td>
    </tr>
    <tr>
        <td style="width:150px">شناسه تراکنش : </td>
        <td><?php echo $trans_id; ?></td>
    </tr>
    <tr>
        <td style="width:150px">کد یکتا : </td>
        <td><?php echo $key; ?></td>
    </tr>
    <?php if($error_message) : ?>
        <tr>
            <td style="width:150px">خطا : </td>
            <td><?php echo $error_message; ?></td>
        </tr>
    <?php endif;?>
</table>