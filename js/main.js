//Navbar and Menus

jQuery(document).ready(function ($) {
    $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
});

function toggleMenu(header) {
    header.classList.toggle('active');
    const subMenu = header.nextElementSibling;
    subMenu.classList.toggle('open');
}

jQuery(document).ready(function($) {
    $('.copy-url-btn').on('click', function(e) {
        e.preventDefault();
        
        var link = $(this).data('link');
        var $btn = $(this);
        
        // Use modern clipboard API if available
        if (navigator.clipboard) {
            navigator.clipboard.writeText(link).then(function() {
                // Success
                $btn.find('.normal-text').hide();
                $btn.find('.copied-text').show();
                
                setTimeout(function() {
                    $btn.find('.normal-text').show();
                    $btn.find('.copied-text').hide();
                }, 2000);
            }).catch(function() {
                fallbackCopy(link, $btn);
            });
        } else {
            fallbackCopy(link, $btn);
        }
    });
    
    function fallbackCopy(text, $btn) {
        var $temp = $('<input>');
        $('body').append($temp);
        $temp.val(text).select();
        
        try {
            document.execCommand('copy');
            $btn.find('.normal-text').hide();
            $btn.find('.copied-text').show();
            
            setTimeout(function() {
                $btn.find('.normal-text').show();
                $btn.find('.copied-text').hide();
            }, 2000);
        } catch (err) {
            alert('Unable to copy. Please copy manually.');
        }
        
        $temp.remove();
    }
});