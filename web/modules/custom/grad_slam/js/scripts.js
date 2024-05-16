(function ($, Drupal) {
    $(document).ready(function () {
        $('body').setActiveGradSlamMenuItem();
    });

    // Set active main nav item
    $.fn.setActiveGradSlamMenuItem = function () {
        $.each($('#body:not(.localist-body) nav#block-gradslammainnavigation').find('li'), function () {
            $(this).toggleClass('active',
                window.location.pathname.indexOf($(this).find('a').attr('href')) > -1);
        });
    }
})(jQuery, Drupal);