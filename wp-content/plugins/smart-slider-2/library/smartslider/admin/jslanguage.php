<?php
nextendimport('nextend.javascript.javascript');
$js = NextendJavascript::getInstance();
$jslang = array();
function ss2_lang_add(&$a, $key){
    $a[$key] = NextendText::_($key);
}


//ss2_lang_add($jslang, 'Read_the_documentation');
//ss2_lang_add($jslang, 'percent');
ss2_lang_add($jslang, 'Layer_name');
ss2_lang_add($jslang, 'Left');
ss2_lang_add($jslang, 'Top');
ss2_lang_add($jslang, 'Width');
ss2_lang_add($jslang, 'Height');
ss2_lang_add($jslang, 'Animation');
ss2_lang_add($jslang, 'Duration');
ss2_lang_add($jslang, 'Easing');
ss2_lang_add($jslang, 'Delay');
ss2_lang_add($jslang, 'Parallax');
ss2_lang_add($jslang, 'Play_out');
ss2_lang_add($jslang, 'In');
ss2_lang_add($jslang, 'Out');
ss2_lang_add($jslang, 'Choose_a_layer');
ss2_lang_add($jslang, 'active');
ss2_lang_add($jslang, 'copy');
ss2_lang_add($jslang, 'Move_layer_drag');
ss2_lang_add($jslang, 'Increment_z_index_click');
ss2_lang_add($jslang, 'Decrement_z_index_click');
ss2_lang_add($jslang, 'Current_z_index');
ss2_lang_add($jslang, 'Delete_layer_click');
ss2_lang_add($jslang, 'Are_you_sure_that_you_want_to_delete_the_layer');
ss2_lang_add($jslang, 'Layer_selected');
ss2_lang_add($jslang, 'Item_selected');
ss2_lang_add($jslang, 'Drop_the_item_into_a_layer');
ss2_lang_add($jslang, 'Item_dropped_into_the_layer');
ss2_lang_add($jslang, 'Layer_created');
ss2_lang_add($jslang, 'Resize_layer_drag');
ss2_lang_add($jslang, 'Layer_deleted');
ss2_lang_add($jslang, 'Are_you_sure_that_you_want_to_delete_the_item');
ss2_lang_add($jslang, 'Item_not_selected');
ss2_lang_add($jslang, 'Drag_the_item_and_drop_into_a_layer');
ss2_lang_add($jslang, 'Item_deleted');
ss2_lang_add($jslang, 'Delete_item_click');
ss2_lang_add($jslang, 'Move_item_drag_and_drop_into_layers');
ss2_lang_add($jslang, 'Select_item_click');
ss2_lang_add($jslang, 'Ordering_saved');
ss2_lang_add($jslang, 'Title_is_empty_Save_failed');
ss2_lang_add($jslang, 'Unexpected_error_Saving_failed');
ss2_lang_add($jslang, 'Layer_not_selected');
ss2_lang_add($jslang, 'Layer');
ss2_lang_add($jslang, 'empty');
ss2_lang_add($jslang, 'Drop_the_item_into_a_layer');
ss2_lang_add($jslang, 'Item_dropped_into_the_layer');
ss2_lang_add($jslang, 'The_editor_was_tested_under_Internet_Explorer_10_Firefox_and_Chrome_Please_use_one_of_the_tested_browser');
ss2_lang_add($jslang, 'Playing_in_animations_edit_and_save_disabled');
ss2_lang_add($jslang, 'Playing_out_animations_edit_and_save_disabled');
ss2_lang_add($jslang, 'In_animations_ended_edit_and_save_disabled');
ss2_lang_add($jslang, 'Animations_ended_edit_and_save_enabled');
ss2_lang_add($jslang, 'Done');
ss2_lang_add($jslang, 'Are_you_sure_that_you_want_to_delete_all_the_slides_of_this_slider');
ss2_lang_add($jslang, 'Are_you_sure_that_you_want_to_delete_the_this_slider');
ss2_lang_add($jslang, 'Are_you_sure_that_you_want_to_delete_the_slide');
ss2_lang_add($jslang, 'Layer_hidden_for_this_device_type');
ss2_lang_add($jslang, 'Hide_layer_click');

$js->addInlineJs('window.ss2lang = '.json_encode($jslang).';');
