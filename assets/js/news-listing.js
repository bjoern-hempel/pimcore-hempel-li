'use strict';
$(document).ready(function () {
    $(document).on('change', '.news-sort-by, .news-per-page', function () {
        if ($(this).hasClass('news-sort-by')) {
            $('.news-sort-by-hidden').val($(this).val());
        }

        if ($(this).hasClass('news-per-page')) {
            $('.news-per-page-hidden').val($(this).val());
        }

        $('.news-search-form').submit();
    })
})