jQuery(function ($) {
    $('#st-update-glocation').on('click', function (event) {
        $('.update-glocation-wrapper').toggleClass('open');
        return !1
    });
    $('.update-glocation-close').on('click', function (event) {
        close_update_popup();
        return !1
    });
    $(document).on('keyup', function (event) {
        if (event.which == 27) {
            close_update_popup()
        }
    });
    $('.update-glocation-button').on('click', function (event) {
        var t = $(this);
        if (!t.hasClass('running')) {
            t.addClass('running').text('Running');
            var update_table_post_type = $('.update-item-form input#update_table_post_type:checked').val();
            var update_location_nested = $('.update-item-form input#update_location_nested:checked').val();
            var update_location_relationships = $('.update-item-form input#update_location_relationships:checked').val();
            var reset_table = $('input[name="reset_table"]:checked').val();
            var step = '';
            if (typeof update_table_post_type != 'undefined' && update_table_post_type != '') {
                step = 'update_table_post_type'
            } else {
                if (typeof update_location_nested != 'undefined' && update_location_nested != '') {
                    step = 'update_location_nested'
                } else {
                    if (typeof update_location_relationships != 'undefined' && update_location_relationships != '') {
                        step = 'update_location_relationships'
                    }
                }
            }

            get_date_glocation(1, '', step, update_table_post_type, update_location_nested, update_location_relationships, reset_table, 'hotel_room', '0')
        }
    });
    var progress_ajax;

    function close_update_popup() {
        if ($('.update-glocation-wrapper').hasClass('open')) {
            if ($('.update-glocation-button').hasClass('running')) {
                var cf = confirm('Are you sure? If it is running, it will be canceled.');
                if (cf == !0) {
                    progress_ajax.abort();
                    $('.update-glocation-button .text').text('Start');
                    $('.update-glocation-progress .progress-bar span').css('width', '0%');
                    $('.update-glocation-button').removeClass('running');
                    $('.update-glocation-wrapper').removeClass('open');
                    $('.update-glocation-message').html('')
                } else {
                    return !1
                }
            } else {
                $('.update-glocation-wrapper').removeClass('open');
                $('.update-glocation-button .text').text('Start');
                $('.update-glocation-progress .progress-bar span').css('width', '0%');
                $('.update-glocation-message').html('')
            }
        }
    }

    function get_date_glocation(page, number_page, step, update_table_post_type, update_location_nested, update_location_relationships, reset_table, post_type, progress) {
        var data = {
            'action': 'st_get_data_location_nested',
            'page': page,
            'number_page': number_page,
            'step': step,
            'update_table_post_type': update_table_post_type,
            'update_location_nested': update_location_nested,
            'update_location_relationships': update_location_relationships,
            'reset_table': reset_table,
            'post_type': post_type,
            'progress': progress
        }

        $('.update-glocation-button').text('Running');
        $('.update-glocation-message').html('');
        progress_ajax = $.post(ajaxurl, data, function (respon, textStatus, xhr) {
            $('.update-glocation-message').html('');
            if (typeof respon == 'object') {
                $('.update-glocation-progress .progress-bar span').css('width', respon.progress + '%');
                if (respon.status == 'continue') {
                    get_date_glocation(respon.page, respon.number_page, respon.step, respon.update_table_post_type, respon.update_location_nested, respon.update_location_relationships, respon.reset_table, respon.post_type, respon.progress);
                    if (respon.step == 'update_table_post_type') {
                        $('.update-item-form .step-1 .status').text('Running...').addClass('running')
                    }

                    if (respon.step == 'update_location_nested') {
                        $('.update-item-form .step-1 .status').text('Completed').addClass('completed');
                        $('.update-item-form .step-2 .status').text('Running...').addClass('running')
                    }

                    if (respon.step == 'update_location_relationships') {
                        $('.update-item-form .step-1 .status').text('Completed').addClass('completed');
                        $('.update-item-form .step-2 .status').text('Completed').addClass('completed');
                        $('.update-item-form .step-3 .status').text('Running...').addClass('running')
                    }

                    $('.update-glocation-message').html(respon.message)
                } else {
                    $('.update-glocation-button').removeClass('running');
                    if (respon.status == 'completed') {
                        $('.update-item-form .step-3 .status').text('Completed').addClass('completed');
                        $('.update-glocation-button').text('Completed').off('click')
                    } else {
                        $('.update-glocation-button').text('Error').off('click');
                        $('.update-glocation-message').html(respon.message)
                    }
                }
            }
        }, 'json')
    }
})
