jQuery(function ($) {
    var list_data_post_type = $('#btn_update_content').data('json');
    var i_list_data_post_type = 0;
    $('#btn_update_content').on('click', function () {
        if (list_data_post_type[i_list_data_post_type]) {
            $('.console_iport').show().html('Working ... <br><img class="loding_import" src="images/wpspin_light.gif">');
            loop_update_content(list_data_post_type[i_list_data_post_type])
        }
    });

    function loop_update_content(post_type) {
        $.ajax({
            url: ajaxurl,
            type: "GET",
            data: {
                action: "st_my_update_content",
                post_type: post_type
            },
            dataType: "json",
            beforeSend: function () {}
        }).done(function (html) {
            $('.loding_import').remove();
            $('.console_iport').append(html.message);
            i_list_data_post_type++;
            if (list_data_post_type[i_list_data_post_type]) {
                $('.console_iport').append('<img class="loding_import" src="images/wpspin_light.gif">');
                loop_update_content(list_data_post_type[i_list_data_post_type])
            } else {
                $('.console_iport').append('<span>All Done !</span>')
            }
        })
    }
});
jQuery(function ($) {})
