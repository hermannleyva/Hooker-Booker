<?php

/**

 * @package WordPress

 * @subpackage Traveler

 * @since 1.0

 *

 * Header

 *

 * Created by ShineTheme

 *

 */



?>

<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>

    <meta charset="<?php bloginfo('charset'); ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="profile" href="http://gmpg.org/xfn/11">

    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <?php wp_head(); ?>

                    <!-- Facebook Pixel Code -->
        <script>
            
        !function(f,b,e,v,n,t,s)

        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};

        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';

        n.queue=[];t=b.createElement(e);t.async=!0;

        t.src=v;s=b.getElementsByTagName(e)[0];

        s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');

         fbq('init', '137224048429884'); 

        fbq('track', 'PageView');

        </script>

        <noscript>

         <img height="1" width="1" src="https://www.facebook.com/tr?id=137224048429884&ev=PageView&noscript=1"/>

        </noscript>
        <!-- End Facebook Pixel Code -->

</head>

<body <?php body_class('hotel-alone'); ?>>

<?php

$enable_preload = st()->get_option('search_enable_preload', 'on');

if ($enable_preload == 'on' && !TravelHelper::is_service_search()) {

    echo st()->load_template('search-loading');

}

?>

<div class="site_wrapper helios_main_content">

    <?php

    $class = '';

    $extra_style = '';

    $transparent = st()->get_option('st_hotel_alone_topbar_background_transparent');

    $st_topbar_background = st()->get_option('st_hotel_alone_topbar_background');

    $custom_header_page = get_post_meta(get_the_ID(), 'custom_header_page', true);

    if ($custom_header_page == 'on') {

        $transparent = get_post_meta(get_the_ID(), 'st_topbar_background_transparent', true);

        $st_topbar_background = get_post_meta(get_the_ID(), 'st_topbar_background', true);

    }

    if ($transparent == 'on') {

        $class = " background-transparent ";

    } else {

	    $class = " no-transparent ".Hotel_Alone_Helper::inst()->build_css('background:'.esc_attr($st_topbar_background));

    }

    ?>

    <div class="topbar <?php echo esc_attr($class); ?>">

        <?php echo st_hotel_alone_load_view('header/top-bar/top-bar'); ?>

    </div>

    <?php

    $st_fixed_menu = st()->get_option('st_hotel_alone_fixed_menu', 'off');

    if ($st_fixed_menu == 'on') {

        ?>

        <div class="topbar-scroll">

            <?php echo st_hotel_alone_load_view('header/top-bar/top-bar-scroll'); ?>

        </div>

    <?php }

    ?>

    <?php echo st_hotel_alone_load_view('header/header-mobile'); ?>