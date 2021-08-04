/**
 * Created by MSI on 23/11/2015.
 */
var package_name = '';
jQuery(function($){
    /*Install*/
    $('.st-install-plugin').on('click',function(event){
        console.log("event");
        event.preventDefault();
        var button_parent = $(this).closest('.button-name');
        package_name =$(this).data('plugin-id');
       $(this)
            .addClass( 'updating-message' )
            .text( wp.updates.l10n.installing );
       do_import(package_name,button_parent);
    });
    $('.st-install-plugin').on( "st_install_plugin", function( event, param1, param2 ) {
    });
    function do_import(package_name,button_parent)
    {
        var erorr_count=0;
        var last_update_url;
        function start_loop_import(data){
            $.ajax({
                url: ajaxurl,
                type: "POST",
                data: data,
                dataType: "json",
            }).done(function( html ) {
                if(html){
                    button_parent.html(html.html);

                }
            }).error(function(html) {
                erorr_count++;
                if(erorr_count<=6)
                {

                    $('#console_iport').animate({scrollTop: $('#console_iport').prop("scrollHeight")}, 500);
                    start_loop_import(data);
                }

            });
        }
        // start fist
        var first_loop_data={
            plugin_name:package_name,
            step:1,
            action:'st_install_plugin'
        };
        start_loop_import( first_loop_data);
    }


    $( '.st-install-plugin-poup' ).on( 'click', function( event ) {
        console.log("fgvfgfg");

            var target = window.parent === window ? null : window.parent,
                install;

            $.support.postMessage = !! window.postMessage;

            if ( false === $.support.postMessage || null === target || -1 !== window.parent.location.pathname.indexOf( 'index.php' ) ) {
                return;
            }

            event.preventDefault();

            install = {
                action: 'install-plugin-extension',
                data:   {
                    slug: $( this ).data( 'slug' )
                }
            };

            target.postMessage( JSON.stringify( install ), window.location.origin );

    });
    $( window ).on( 'message', function( event ) {
        var originalEvent  = event.originalEvent,
            expectedOrigin = document.location.protocol + '//' + document.location.host,
            message;

        if ( originalEvent.origin !== expectedOrigin ) {
            return;
        }

        try {
            message = $.parseJSON( originalEvent.data );
        } catch ( e ) {
            return;
        }

        if ( ! message || 'undefined' === typeof message.action ) {
            return;
        }

        switch ( message.action ) {

            case 'install-plugin-extension':
                /* jscs:disable requireCamelCaseOrUpperCaseIdentifiers */
                window.tb_remove();
                console.log( message.data.slug);
                $('.st-install-plugin').each(function(){
                    console.log($(this).attr('data-plugin-id'));
                    if($(this).attr('data-plugin-id') == message.data.slug){
                        console.log("sfasdasd");
                        $(this).trigger( 'click' );
                    }
                })

                break;
        }

    } );

});
