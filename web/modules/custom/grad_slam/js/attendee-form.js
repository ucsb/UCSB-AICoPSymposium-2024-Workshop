/**
 * @file
 * Overrride UI behaviors
 */

(function ($, Drupal) {

    // 'use strict';
    // $(function () {
    //     $('body').attachFullNameEventListeners();
    // })

    $(document).ajaxStop(function () {
        $('body').attachFullNameEventListeners();
    });

    // $(document).ready(function () {
    //     $('body').attachFullNameEventListeners();
    // })

    

    $.fn.attachFullNameEventListeners = function() {
        var $attendee = $('.grad_slam_attendee_form');

        $('.grad_slam_attendee_form').each(function (){
            var full_name = $(this).find('.grad_slam_attendee_full_name input')[0];
            var firstName = $(this).find('.grad_slam_attendee_first_name input')[0];
            var middleName = $(this).find('.grad_slam_attendee_middle_name input')[0];
            var lastName = $(this).find('.grad_slam_attendee_last_name input')[0];


            firstName.addEventListener("input", function () {
                full_name.value = `${firstName.value ? `${firstName.value}` : ''}${middleName.value ? ` ${middleName.value}` : ''}${lastName.value ? ` ${lastName.value}` : ''}`;
            }, false);

            middleName.addEventListener("input", function () {
                full_name.value = `${firstName.value ? `${firstName.value}` : ''}${middleName.value ? ` ${middleName.value}` : ''}${lastName.value ? ` ${lastName.value}` : ''}`;
            }, false);

            lastName.addEventListener("input", function () {
                full_name.value = `${firstName.value ? `${firstName.value}` : ''}${middleName.value ? ` ${middleName.value}` : ''}${lastName.value ? ` ${lastName.value}` : ''}`;
            }, false);

        });

        var $events = $('.grad_slam_event_form');
        $('.grad_slam_event_form').each(function () {

            var event_title = $(this).find('.grad_slam_event_title input')[0];
            var topic = $(this).find('.grad_slam_event_topic select')[0];

            topic.addEventListener("click", function () {
                event_title.value = topic.options[topic.selectedIndex].text;
            }, false);
            topic.addEventListener("change", function () {
                event_title.value = topic.options[topic.selectedIndex].text;
            }, false);
        });


    }

})(jQuery, Drupal);


