jQuery(document).ready(function($){

    $('#rayium_has_discount').change(function(e){
        if ($(this).is(':checked')) {
            $(this).closest('table').addClass('rayium_discount_base');
        } else {
            $(this).closest('table').removeClass('rayium_discount_base');
        }
    });

    $('#rayium_video_uploader').click(function(event){

        event.preventDefault();

        if( typeof demo_uploader !== 'undefined' ){
            demo_uploader.open();
            return;
        }

        demo_uploader = wp.media({
            title : rayium.i18n.video_uploader_title,
            library : {
                type : ['video'],
            }
        });

        demo_uploader.on('select', function(){
            let selected = demo_uploader.state().get('selection');
            $('#rayium_demo').val( selected.first().toJSON().url ).change();
        });

        demo_uploader.open();
        

    });

    $('.select2').select2();
    
    jalaliDatepicker.startWatch({
        time: true
    });

    $('.rayium_expire_jalali').change(function(){
        let datetime = $(this).val();
        let gregoriran = '';
        if(datetime.length > 5){
            let slices = datetime.split( ' ' );
            gregoriran = moment(slices[0], 'jYYYY/jMM/jDD').format('YYYY/MM/DD') + ' ' + slices[1];
        }
        $('#rayium_expire').val(gregoriran);
        
    });

    if($('#rayium_expire').length){
        let datetime = $('#rayium_expire').val();

        if(datetime.length > 5){
            let slices = datetime.split( ' ' );
            let jalali = moment(slices[0], 'YYYY/MM/DD').format('jYYYY/jMM/jDD') + ' ' + slices[1];
            $('.rayium_expire_jalali').val(jalali);
        }
        
    }

    $('.rm_playlist_table tbody').sortable({
        handle : '.dashicons-move',
        stop : reindex_playlist
    });

    function reindex_playlist(){
        $('.rm_playlist_table tbody tr td:nth-child(2)').each(function(index){
            $(this).text( index + 1);
        });
    }

    $(document).on('click', '.rm_playlist_table .dashicons-trash', function(){
        if( confirm(rayium.i18n.sure_delete) ){
            $(this).closest('tr').addClass('deleting').slideUp(1500, function(){
                $(this).remove();
                check_no_items();
                reindex_playlist();
            });
        }
    });

    function check_no_items(){
        if($('.rm_playlist_table tbody tr:not(.rm_no_item)').length ){
            $('.rm_no_item').remove();
        }else{
            $('.rm_playlist_table tbody').html( $('.rm_not_item').html() );
        }
    }

    $('.rm_add_playlist_item').click(function (){
        let html = $('#tp_item').html();
        $(html).appendTo('.rm_playlist_table tbody');
        check_no_items();
        reindex_playlist();
    });

    function calculate_video_data(video_url, on_calculate){

        let video = $('<video/>', {
            id : 'rm_video',
            src : video_url,
            type : 'video/mp4',
            controls : true,
            preload : true
        });

        $(video).get(0).load();

        $(video).on('loadedmetadata', function(){

            let duration = parseInt(this.duration);
            let width = parseInt(this.videoWidth);
            let height = parseInt(this.videoHeight);

            $(this).remove();

            on_calculate(width, height, duration);

        });

        $(video).on('error', function(){
            $(this).remove();
            on_error();
        });
    }

    function formatTime(seconds){

        return [
            parseInt(seconds / 60 / 60),
            parseInt(seconds / 60 % 60),
            parseInt(seconds % 60)
        ]
        .join(":")
        .replace(/\b(\d)\b/g, "0$1")
    }

    $(document).on('change', '.rm_playlist_table input[type="url"]', function(){

        let el_duration = $(this).closest('tr').find('.rayium_duration');
        let tr = $(this).closest('tr');

        let url = $(this).val().trim();
        let ext = url.split('.').pop();

        if(['mp4', 'm4v', 'webm'].indexOf(ext) >= 0){

            $(tr).addClass('calculating');

            calculate_video_data(url, function(width, height, duration){

                $(tr).find('.rm_item_width').val(width);
                $(tr).find('.rm_item_height').val(height);
                $(tr).find('.rm_item_duration').val(duration);
                $(el_duration).text(formatTime(duration));

                $(tr).removeClass('calculating');

            });
        }else{
            $(el_duration).text('--:--');
        }
    });

});
