<?php
$main_color =st()->get_option('main_color','#1A2B48');
$link_color =st()->get_option('link_color','#5191FA');
$star_color=st()->get_option('star_color', '#FA5636');
$bg_featured = st()->get_option('st_text_featured_bg','#3366cc');
$bg_sale = st()->get_option('st_text_sale_bg','#3366cc');
?>

@media screen and (max-width: 782px) {
  html {
    margin-top: 0px !important;
  }

  .admin-bar.logged-in #header {
    padding-top: 45px;
  }

  .logged-in #header {
    margin-top: 0;
  }
}

:root {
	--main-color: <?php echo esc_attr($main_color); ?>;
    --link-color: <?php echo esc_attr($link_color); ?>;
    --link-color-dark: <?php echo esc_attr($link_color); ?>;
	--grey-color: #5E6D77;
	--light-grey-color: #EAEEF3;
    --orange-color: #FA5636;
}

<?php if($star_color):?>
    .booking-item-rating .fa ,
    .booking-item.booking-item-small .booking-item-rating-stars,
    .comment-form .add_rating,
    .booking-item-payment .booking-item-rating-stars .fa-star,
    .st-item-rating .fa,
    li  .fa-star , li  .fa-star-o , li  .fa-star-half-o{
    color:<?php echo esc_attr($star_color)?>
    }
<?php endif;?>

.feature_class{
 background: <?php echo esc_attr($bg_featured) ?>;
}
.feature_class::before {
   border-color: <?php echo esc_attr($bg_featured) ?> <?php echo esc_attr($bg_featured) ?> transparent transparent;
}
.feature_class::after {
    border-color: <?php echo esc_attr($bg_featured) ?> transparent <?php echo esc_attr($bg_featured) ?> <?php echo esc_attr($bg_featured) ?>;
}
.featured_single .feature_class::before{
   border-color: transparent <?php echo esc_attr($bg_featured) ?> transparent transparent;
}
.item-nearby .st_featured::before {
    border-color: transparent transparent <?php echo esc_attr($bg_featured) ?> <?php echo esc_attr($bg_featured) ?>;
}
.item-nearby .st_featured::after {
   border-color: <?php echo esc_attr($bg_featured) ?> <?php echo esc_attr($bg_featured) ?> <?php echo esc_attr($bg_featured) ?> transparent  ;
}

.st_sale_class{
    background-color: <?php echo esc_attr($bg_sale) ?>;
}
.st_sale_class.st_sale_paper * {color: <?php echo esc_attr($bg_sale) ;?> }
.st_sale_class .st_star_label_sale_div::after,.st_sale_label_1::before{
    border-color: <?php echo esc_attr($bg_sale) ;?> transparent transparent <?php echo esc_attr($bg_sale) ;?> ;
}

.btn.active.focus, .btn.active:focus, .btn.focus, .btn:active.focus, .btn:active:focus, .btn:focus {
  outline: none;
}

.st_sale_class .st_star_label_sale_div::after{
border-color: <?php echo esc_attr($bg_sale) ;?>



<?php  if(st()->get_option('right_to_left') == 'on' ){ ?>
    .st_featured{
       padding: 0 13px 0 3px;
    }
    .featured_single .st_featured::before {
        border-color: <?php echo esc_attr($bg_featured) ?> transparent transparent transparent  ;
        right: -26px;
    }
    .item-nearby  .st_featured::before {
    border-color: <?php echo esc_attr($bg_featured) ?> transparent transparent <?php echo esc_attr($bg_featured) ?>;
    }

    .item-nearby .st_featured {
        bottom: 10px;
        left: -10px;
        right: auto;
        top: auto;
        padding-left:13px!important;
    }
    .item-nearby  .st_featured::before {
        left: 0;
        right: auto;
        bottom: -10px;
        top: auto;
    }
    .item-nearby .st_featured::before {
          border-color: transparent <?php echo esc_attr($bg_featured) ?> <?php echo esc_attr($bg_featured) ?>  transparent;
    }
    .item-nearby .st_featured::after {
        border-color:   transparent <?php echo esc_attr($bg_featured) ?> transparent transparent;
        border-width: 14px;
        right: -26px;
    }
    .featured_single {
        padding-left: 70px;
        padding-right: 0px;
    }
<?php } ?>
