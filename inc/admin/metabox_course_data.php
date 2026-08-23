<?php defined('ABSPATH') || exit; ?>

<table class="widefat <?php echo $has_discount ? 'rayium_has_discount' : ''; ?>" id="rayium_field">
    <tr>
        <td style="width:150px;">
            <lable for="rayium_demo">پیش نمایش دوره</lable>
        </td>
        <td>
            <input type="url" id="rayium_demo" name="rayium_course[demo]" value="<?php echo esc_url( $demo ); ?>" placeholder="دمو دوره">
            <button class="button button-secondary" id="rayium_video_uploader">انتخاب ویدئو</button>
        </td>
    </tr>
    <tr>
        <td style="width:150px;">
            <lable for="rayium_teacher">انتخاب مدرس</lable>
        </td>
        <td>
            <select class="select2" id="rayium_teacher" name="rayium_course[teacher]">
                <option>انتخاب کنید ...</option>
                <?php foreach(get_users(['fields' => ['id', 'display_name']]) as $row) : ?>
                    <option value="<?php echo $row->ID; ?>" <?php selected( $row->ID, $teacher_id ); ?>><?php echo $row->display_name; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
        <td style="width:150px;">
            <lable for="rayium_price">قیمت دوره</lable>
        </td>
        <td>
            <input type="number" min="0" id="rayium_price" name="rayium_course[price]" value="<?php echo esc_attr( $price ); ?>" placeholder="قیمت دوره">
        </td>
    </tr>
    <tr class="rayium_has_discount">
        <td style="width:150px;">
            <lable for="rayium_has_discount">تخفیف دارد ؟</lable>
        </td>
        <td>
            <input type="checkbox" <?php checked( $has_discount ); ?> id="rayium_has_discount" name="rayium_course[has_discount]" value="1">
        </td>
    </tr>
    <tr class="rayium_discount_base">
        <td style="width:150px;">
            <lable for="rayium_sale_price">تخفیف دوره</lable>
        </td>
        <td>
            <input type="number" min="0" id="rayium_sale_price" name="rayium_course[sale_price]" value="<?php echo esc_attr( $sale_price ); ?>" placeholder="تخفیف دوره">
        </td>
    </tr>
    <tr class="rayium_discount_base">
        <td style="width:150px;">
            <lable for="rayium_expire">تاریخ تخفیف دوره</lable>
        </td>
        <td>
            <input type="text" class="ltr rayium_expire_jalali" data-jdp>
            <input type="hidden" id="rayium_expire" name="rayium_course[expire]" value="<?php echo esc_attr( $expire ); ?>">
        </td>
    </tr>
</table>