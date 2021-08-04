<?php

/**

 * @package WordPress

 * @subpackage Traveler

 * @since 1.0

 *

 * Header custom full

 *

 * Created by ShineTheme

 *

 */

?>

<!DOCTYPE html>

<html <?php language_attributes(); ?> class="full">

<head>

    <meta charset="<?php bloginfo( 'charset' ); ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <?php if(defined('ST_TRAVELER_VERSION')){?>  <meta name="traveler" content="<?php echo esc_attr(ST_TRAVELER_VERSION) ?>"/>  <?php };?>

    <link rel="profile" href="http://gmpg.org/xfn/11">

    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

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

<body <?php body_class('full'); ?>>

<?php do_action('before_body_content')?>

<?php

$class_bg_img = "";

$class_bg_blur ="";

$css = "";

$background= get_post_meta(get_the_ID(),'cs_bgr',true);



if (!empty($background) and is_array($background)){

    foreach ($background as $key=>$val){

        if (!empty($val)){

            if ($key =='background-image'){

                $css.= $key.": url(".$val .");";

            }else {

                $css.= $key.": ".$val .";";

            }

        }

    }

}

$class_bg_img = Assets::build_css($css);



/*if(has_post_thumbnail( get_the_ID() )){

    $img = $thumb_url_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true);

    $class_bg_img = Assets::build_css("background-image: url(".$img[0].")");

}*/



if(is_404()){

    $img = st()->get_option('404_bg');

    $class_bg_blur = Assets::build_css("

                                            background-image: url(".$img.")

                                         ");

}

?>

<div class="global-wrap <?php echo apply_filters('st_container',true) ?>" style="height: 100%">

    <div class="row st-full st-header-center">

        <div class="full-page <?php if(is_page_template("tempalate-commingsoon.php")) echo "text-center"; if(is_404()){echo "full_404";} ?> ">

            <div class="bg-holder full">

                <div class="bg-mask"></div>

                <div class="bg-img <?php echo esc_attr($class_bg_img)?>"></div>

                <div class="bg-blur <?php echo esc_attr($class_bg_blur)?>"></div>

                <div class="bg-holder-content full text-center">

                    <a class="logo_big_center" href="<?php echo site_url()?>">

                        <img src="<?php echo st()->get_option('logo',get_template_directory_uri().'/img/logo-invert.png') ?>" alt="logo" title="<?php bloginfo('name')?>">

                    </a>







