'use strict';
window.hljs = require('highlight.js');
require('highlightjs-line-numbers.js');
import Granim from "granim";
//import highlight from 'highlight.js';


window.popper = require('./vendor/popper/popper.min.js');

!function ($, highlight) {

    $('#button-skill-diagram').on('click', function (e) {
        $('#skill-diagram').removeClass('hidden').addClass('visible');
        $('#skill-table').removeClass('visible').addClass('hidden');
    });

    $('#button-skill-table').on('click', function (e) {
        $('#skill-diagram').removeClass('visible').addClass('hidden');
        $('#skill-table').removeClass('hidden').addClass('visible');
    });

    /* Add copy button to pre code blocks (with copy to clipboard functionality) */
    $('pre code').each(function (i, block) {
        let code = block.textContent;

        let blockJquery = $(block);

        blockJquery.parent().data('code', code);

        let button = $('<button>', {title: 'Copy to clipboard'});

        button.text('📋');
        button.on('click', function (e) {
            let preJquery = $(e.target).parent();

            let copyText = preJquery.data('code');

            navigator.clipboard.writeText(copyText);

            alert('Copied code to clipboard');
        });

        blockJquery.parent().append(button);
    });

    /* Add code highlighting and line numbers */
    highlight.highlightAll();
    highlight.initLineNumbersOnLoad();

    /* Close alerts after 5 seconds */
    $('.alert').fadeTo(5000, 500).slideUp(500, function(){
        $('.alert').slideUp(500);
    });

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

    /* Create popper popups */
    $('.popper').each(function () {
        let popper = $(this);

        let section = popper.find('.popper-section');
        let popup = popper.find('.popper-popup');

        if (section.length <= 0 || popup.length <= 0) return;

        popper.data('popper-instance', window.popper.createPopper(section[0], popup[0], {placement: 'top', modifiers: [
            {
                name: 'offset',
                options: {
                    offset: [0, 10],
                },
            },
        ]}));

        let appear = function (e) {
            let popper = $(this);
            let popup = popper.find('.popper-popup');

            popup.attr('data-show', '');
            popper.data('popper-instance').update();
        };

        let disappear = function (e) {
            let popper = $(this);
            let popup = popper.find('.popper-popup');

            popup.removeAttr('data-show');
        };

        popper.on('mouseover', appear);
        popper.on('click', appear);
        popper.on('mouseout', disappear);
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
}(jQuery, window.hljs);
