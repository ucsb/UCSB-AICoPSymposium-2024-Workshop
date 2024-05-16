/**
 * @file
 * Overrride UI behaviors
 */

(function ($, Drupal) {

    'use strict';
    $(function () {
        $('body').equalHeight();
    })

    $(document).ready(function () {
        $('body').equalHeight();
    })

    $(window).on('load', function () {
        $('body').equalHeight();
    })

    $(window).on('resize', function () {
        $('body').equalHeight();
    })




    $.fn.equalHeight = function () {

        const parents = new Set(
            [...document.querySelectorAll('.equal-height-gradslam')]
                .map(div => div.parentElement)
        );

        parents.forEach((parent) => {

            let children = parent.getElementsByClassName('equal-height-gradslam');
            var maxHeight = 0;

            // Get the max height
            Array.from(children).forEach(function (element) {
                element.style.height = null;

                if (window.innerWidth >= 992) {
                    maxHeight = element.offsetHeight > maxHeight ? element.offsetHeight : maxHeight;
                }
            });

            // Apply the height for each of the elements
            Array.from(children).forEach(function (element) {
                if (window.innerWidth >= 992) {
                    element.style.height = maxHeight + "px";
                }
            });
        });
    }


})(jQuery, Drupal);