'use strict';
import Granim from "granim";

!function ($) {

    /**
     * @return {undefined}
     */
    function initiateLayout() {
        $('.hero.index.auto-height').css('height', $(window).height() + 'px');
    }

    $(window).on("load", function () {
        $(".loader-inner").fadeOut();
        $(".loader").delay(200).fadeOut("slow");
    });

    let mainColor1 = '#097965';
    let mainColor2 = '#095779';

    new Granim({
        element: '.gradient-hero',
        direction: 'diagonal',
        opacity: [.7, .7, .7],
        isPausedWhenNotInView: true,
        states: {
            'default-state': {
                gradients: [[mainColor1, mainColor2], [mainColor2, mainColor1]],
                transitionSpeed: 2e3
            }
        }
    });

    new Granim({
        element: '.gradient-footer',
        direction: 'diagonal',
        opacity: [.7, .7, .7],
        isPausedWhenNotInView: true,
        states: {
            'default-state': {
                gradients: [[mainColor1, mainColor2], [mainColor2, mainColor1]],
                transitionSpeed: 2e3
            }
        }
    });

    $(function () {
        initiateLayout();
    });

    $(window).resize(function () {
        initiateLayout();
    });

    $('.percentage').each(function () {
        let meterPos = $(this).text();

        $(this).css('height', meterPos).parent().find('h4.outer').css('bottom', meterPos);
    });

    $('a.scroll').smoothScroll({
        speed: 800,
        offset: 0
    });

    /* Category filter */
    $('.filter').on('click', 'li a', function () {
        $(this).addClass('active');
        $(this).parent().siblings().find('a').removeClass('active');
        $(this).closest('.works').find('.box.work').removeClass('disable');

        let selected = $(this).attr('data-filter');

        if (selected !== 'all') {
            let $appointment = $(this).closest('.works').find('.box.work');
            let i = 0;
            for (; i < $appointment.length; i++) {
                if (!$appointment.eq(i).hasClass(selected)) {
                    $appointment.eq(i).addClass('disable');
                }
            }
        }

        return false;
    });
}(jQuery);
