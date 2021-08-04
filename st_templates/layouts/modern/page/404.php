<?php
get_header();
$option_404 = st()->get_option('404_text');
$img_404 = st()->get_option('404_img', '');
$bg_color_404 = st()->get_option('404_bg_color', '#fff');

?>
<div class="st-404-page" style="background-color: <?php echo esc_attr($bg_color_404); ?>">
    <div class="container">
        <?php
        if (!empty($option_404)) {
            echo st()->get_option('404_text');
            if($img_404){ ?>
                <img src="<?php echo esc_url($img_404); ?>" alt="404 Page">
            <?php }else{ ?>
                <img src="<?php echo get_template_directory_uri() . '/v2/images/404.jpg' ?>" alt="404 Page">
            <?php }
        } else {
            ?>
            <h1><?php echo __('OOPS...', 'traveler'); ?></h1>
            <h3><?php echo __('Something went wrong here :(', 'traveler'); ?></h3>
            <?php if($img_404){ ?>
                <img src="<?php echo esc_url($img_404); ?>" alt="404 Page">
            <?php }else{ ?>
                <img src="<?php echo get_template_directory_uri() . '/v2/images/404.jpg' ?>" alt="404 Page">
            <?php } ?>
            <p><?php echo __('Sorry, we couldn\'t find the page you\'re looking for.&nbsp;', 'traveler'); ?></p>
            <p><strong><?php echo __('Try returning to the', 'traveler'); ?></strong> <a href="<?php echo site_url('/'); ?>"><?php echo __('Homepage', 'traveler'); ?></a></p>
            <?php
        }
        ?>
    </div>
</div>
<?php
get_footer();
