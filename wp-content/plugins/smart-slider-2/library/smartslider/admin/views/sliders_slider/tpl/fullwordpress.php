<?php
$this->loadFragment('headerstart');
?>
<?php
$this->loadFragment('headerend');
?>
<?php
$this->loadFragment('firstcolstart');
?>
<?php
$this->loadFragment('firstcolend');
?>
<?php
$this->loadFragment('secondcolstart');
$css = NextendCss::getInstance();
$css->addCssLibraryFile('form.css');
?>
<form method="post" action="" id="smartslider-form">              
    <div class="nextend-form" style="background: #EEF3F8;">
        <h2><?php echo NextendText::_('Buy_full_version'); ?></h2>
        <div class="ni wrapper">        
            <p><?php echo NextendText::_('We_are_very_happy_that_you_tried_out_our_free_version_of_Smart_Slider_2_We_hope_you_have_enjoyed_the_possibilities_also_you_are_convinced_how_good_is_Smart_Slider_2_To_honor_the_effort_that_you_tried_out_our_plugin_we_would_like_to_give_you_a_10_off_coupon_for_Smart_Slider_2_FULL'); ?></p>
            <h3 style="float: left; margin-top: 15px;"><?php echo NextendText::_('10_off_coupon_code_TRIAL'); ?></h3>
            <p><a class="button g" style="margin: 13px 0 10px 20px;" target="_blank" href="http://www.nextendweb.com/smart-slider/#pricing"><?php echo NextendText::_('Buy_it_now'); ?></a></p>


            <h3><?php echo NextendText::_('Why_should_I_upgrade_to_FULL_version_Because_you_get_all'); ?></h3>
            <ul>
                <li><?php echo NextendText::_('4_slider_types'); ?></li>
                <li><?php echo NextendText::_('extra_main_and_background_animations'); ?></li>
                <li><?php echo NextendText::_('20_layer_animations'); ?></li>
                <li><?php echo NextendText::_('12_items_to_build_better_sliders'); ?></li>
                <li><?php echo NextendText::_('8_widgets_to_create_thumbnails_galleries_bars_etc'); ?></li>
                <li><?php echo NextendText::_('12_items_to_build_better_sliders'); ?></li>
                <li><?php echo NextendText::_('10_slider_generators'); ?></li>
                <li><?php echo NextendText::_('also_a_lot_of_extra_feature_and_option'); ?></li>
                <li><?php echo NextendText::_('and_last_but_not_least_personal_support'); ?></li>
            </ul>
            </p>

            <h3><?php echo NextendText::_('Slider_features_and_limitations_in_free_version'); ?></h3>

            <p><?php echo NextendText::_('With_this_free_version_your_will_be_able_to_create_new_responsive_sliders_with_simple_slider_type_The_created_slider_main_animation_is_limited_to_the_horizontal_animation_but_also_one_special_background_animation_is_available_for_testing_The_autoplay_function_of_the_Smart_Slider_2_is_only_available_in_the_FULL_version_Free_version_will_allow_you_to_use_arrow_and_bullet_widgets_on_your_slider_the_arrows_are_limited_3_different_arrows_available_in_free_the_full_contains_more_than_35_different_colorizable_arrows_the_bullets_are_not_limited_in_free'); ?></p>

            <h3><?php echo NextendText::_('Slide_features_and_limitations_in_free_version'); ?></h3>

            <p><?php echo NextendText::_('This_is_the_most_important_part_of_the_Smart_Slider_2_so_we_have_not_limit_any_action_which_related_to_the_user_interaction_on_the_backend_You_can_change_the_title_of_your_slides_and_also_you_can_add_slide_background_images_and_thumbnail_images_In_layer_mode_with_the_free_version_you_can_use_two_layer_animations_Slide_down_and_Roll_in_the_full_version_contains_more_than_20_layer_animations_and_every_other_layer_related_setting_is_available_In_item_mode_with_the_free_version_you_can_use_two_items_Heading_and_Button_items_allowed_in_free_mode_which_give_you_some_experience_with_the_live_slide_editor_Also_in_free_mode_every_slide_contains_our_logo_on_the_bottom_left'); ?></p>

            <h3><?php echo NextendText::_('Generator_features_and_limitations_in_free_version'); ?></h3>

            <p><?php echo NextendText::_('The_free_version_comes_with_the_image_from_folder_generator_You_will_need_to_select_source_folder_which_contains_your_images_and_Smart_Slider_2_will_automatically_create_the_slides_for_you_The_full_version_comes_with_other_integrations_WordPress_posts_and_also_with_social_generators_Facebook_Twitter_Instagram_Flickr_YouTube'); ?></p>

            <h3><?php echo NextendText::_('What_comes_with_free_version'); ?></h3>

            <p><?php echo NextendText::_('If_you_are_trying_the_free_version_you_get_access_to_our_top_notch_support_If_you_experience_any_kind_of_problem_feel_free_to'); ?> <a href="http://www.nextendweb.com/smart-slider#support" target="_blank"><?php echo NextendText::_('contact_with_our_support_staff'); ?></a>. <?php echo NextendText::_('We_will_try_to_resolve_your_problem_as_soon_as_possible'); ?></p>
            <p>
                <?php echo NextendText::_('You_can_get_20_off_coupon_code_for_the_full_Smart_Slider_2_if_you_find_a_problem_and_report_us_The_todo_list_to_get_the_coupon_code'); ?>
            <ul>
                <li><?php echo NextendText::_('Describe_the_bug_to'); ?> <a href="http://www.nextendweb.com/smart-slider#support" target="_blank"><?php echo NextendText::_('the_support_staff'); ?></a></li>
                <li><?php echo NextendText::_('Cooperate_in_the_resolution_of_the_problem'); ?></li>
            </ul>
            </p>

            <h3><?php echo NextendText::_('Just_to_know'); ?></h3>
            <p>
                <?php echo NextendText::_('Smart_Slider_2_is_a_free_demo_version_for_our_commercial_Smart_Slider_2'); ?> <strong>
            </p> 
            <p>
                <?php echo NextendText::_('Smart_Slider_2_does'); ?>
            <ul>
                <li><?php echo NextendText::_('NOT_create_home_calls'); ?></li>
                <li><?php echo NextendText::_('NOT_track_anything_on_your_site'); ?></li>
                <li><?php echo NextendText::_('NOT_mess_up_your_site'); ?></li>
                <li><?php echo NextendText::_('what_this_documentation_page_describes_nothing_more_and_nothing_less_For_full_functionality_you_will_still_need_the_commercial'); ?> <a href="http://www.nextendweb.com/smart-slider/#pricing"><?php echo NextendText::_('Smart_Slider_2'); ?></a>.</li>
            </ul>
            </p>
        </div>
</form>

<?php
$this->loadFragment('secondcolend');
?>
<?php
$this->loadFragment('footer');
