jQuery(document).ready(function($){

    const player = $('.rayium-player video')[0];

    // Only proceed if player exists
    if (player) {
        player.addEventListener( 'play', on_play );
        player.addEventListener( 'pause', on_pause );

        function on_play(){
            player.controls = 'controls';
            let url = player.src;
            $(`.rayium-item[data-url="${url}"]`).addClass('playing');
        }

        function on_pause(){
            player.controls = 'controls';
            let url = player.src;
            $(`.rayium-item[data-url="${url}"]`).removeClass('playing');
        }

        $(".rayium-play").click(function( e ){
            e.preventDefault();
            player.play();
        });

        $('.rayium-item[data-url]').click(function(e){
            e.preventDefault();
            let url = $(this).data('url');
            if( ['mp4', 'm4v', 'webm'].indexOf( url.split('.').pop() ) >= 0 ){
                player.pause();
                player.src = url;
                player.load();
                player.play();
                $('.rayium-item.playing').removeClass('playing');
                $(this).addClass('playing');
            }else{
                window.open(url, '_blank');
            }
        });
    }
});