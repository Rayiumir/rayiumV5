//Navbar and Menus

jQuery(document).ready(function ($) {

    $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });

    var darkMode;

    if (localStorage.getItem('dark-mode')) {
        // if dark mode is in storage, set variable with that value
        darkMode = localStorage.getItem('dark-mode');
    } else {
        // if dark mode is not in storage, set variable to 'light'
        darkMode = 'light';
    }

    // set new localStorage value
    localStorage.setItem('dark-mode', darkMode);


    if (localStorage.getItem('dark-mode') == 'dark') {
        // if the above is 'dark' then apply .dark to the body
        $('body').addClass('dark');
        // hide the 'dark' button
        $('#dark-button').hide();
        // show the 'light' button
        $('#light-button').show();
    }


    // Toggle dark UI

    $('#dark-button').on('click', function () {
        $('#dark-button').hide();
        $('#light-button').show();
        $('body').addClass('dark');
        // set stored value to 'dark'
        localStorage.setItem('dark-mode', 'dark');
    });

    $('#light-button').on('click', function () {
        $('#light-button').hide();
        $('#dark-button').show();
        $('body').removeClass('dark');
        // set stored value to 'light'
        localStorage.setItem('dark-mode', 'light');
    });
});

function toggleMenu(header) {
    header.classList.toggle('active');
    const subMenu = header.nextElementSibling;
    subMenu.classList.toggle('open');
}

jQuery(document).ready(function ($) {
    $('.copy-url-btn').on('click', function (e) {
        e.preventDefault();

        var link = $(this).data('link');
        var $btn = $(this);

        // Use modern clipboard API if available
        if (navigator.clipboard) {
            navigator.clipboard.writeText(link).then(function () {
                // Success
                $btn.find('.normal-text').hide();
                $btn.find('.copied-text').show();

                setTimeout(function () {
                    $btn.find('.normal-text').show();
                    $btn.find('.copied-text').hide();
                }, 2000);
            }).catch(function () {
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

            setTimeout(function () {
                $btn.find('.normal-text').show();
                $btn.find('.copied-text').hide();
            }, 2000);
        } catch (err) {
            alert('Unable to copy. Please copy manually.');
        }

        $temp.remove();
    }
});

// Dark Mode
