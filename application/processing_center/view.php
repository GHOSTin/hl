<?php
class view_processing_center{

    public static function private_create_processing_center($args){
        return load_template('processing_center.private_create_processing_center', $args);
    }

    public static function private_get_dialog_create_processing_center($args){
        return load_template('processing_center.private_get_dialog_create_processing_center', $args);
    }

    public static function private_get_dialog_rename_processing_center($args){
        return load_template('processing_center.private_get_dialog_rename_processing_center', $args);
    }

    public static function private_get_processing_center_content($args){
        return load_template('processing_center.private_get_processing_center_content', $args);
    }

    public static function private_rename_processing_center($args){
        return load_template('processing_center.private_rename_processing_center', $args);
    }
    
    public static function private_show_default_page($args){
        return load_template('processing_center.private_show_default_page', $args);
    }
}