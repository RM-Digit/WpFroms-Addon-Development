<?php 
/*
Plugin Name:COVID Form Dashboard
Description: COVID Form Dashboard. Shortcode [CorvidForm type = "member"],[CorvidForm type = "student"],[CorvidForm type = "stuff"],[CorvidForm type = "deniedMember"].
Author: Abbas Mirza
Text Domain: corvid-form
Domain Path: /languages/
Version: 1.0.0
*/

add_action('init', 'wpforms_db_init');

function wpforms_db_init(){
    if( is_admin() ){
        require_once 'inc/class-main-page.php';
        require_once 'inc/class-sub-page.php';
        require_once 'inc/class-form-details.php';
        require_once 'inc/class-export-csv.php';
        
        if( isset($_REQUEST['wpforms-csv']) && ( $_REQUEST['wpforms-csv'] == true ) && isset( $_REQUEST['nonce'] ) ) {

            $nonce  = filter_input( INPUT_GET, 'nonce', FILTER_SANITIZE_STRING );

            if ( ! wp_verify_nonce( $nonce, 'dnonce' ) ) wp_die('Invalid nonce..!!');
            $csv = new WPForms_Export_CSV();
            $csv->download_csv_file();
        }
        new WPFormsDB_Wp_Main_Page;
    }
}


function WPFormsDB_create_table(){

    global $wpdb;
    $wpform       = apply_filters( 'WPFormsDB_database', $wpdb );
    $table_name = $wpform->prefix.'wpforms_db';

    if( $wpform->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {

        $charset_collate = $wpform->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            form_id bigint(20) NOT NULL AUTO_INCREMENT,
            form_post_id bigint(20) NOT NULL,
            form_value longtext NOT NULL,
            form_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY  (form_id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    add_option( 'WPFormsDB_view_install_date', date('Y-m-d G:i:s'), '', 'yes');

}

function WPFormsDB_on_activate( $network_wide ){

    global $wpdb;
    if ( is_multisite() && $network_wide ) {
        // Get all blogs in the network and activate plugin on each one
        $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
        foreach ( $blog_ids as $blog_id ) {
            switch_to_blog( $blog_id );
            WPFormsDB_create_table();
            restore_current_blog();
        }
    } else {
        WPFormsDB_create_table();
    }

	// Add custom capability
	$role = get_role( 'administrator' );
	$role->add_cap( 'WPFormsDB_access' );
}

register_activation_hook( __FILE__, 'WPFormsDB_on_activate' );


function WPFormsDB_on_deactivate() {

	// Remove custom capability from all roles
	global $wp_roles;

	foreach( array_keys( $wp_roles->roles ) as $role ) {
		$wp_roles->remove_cap( $role, 'WPFormsDB_access' );
	}
}

register_deactivation_hook( __FILE__, 'WPFormsDB_on_deactivate' );


function WPFormsDB_save( $fields, $entry, $form_id ) {

    global $wpdb;
    $wpform          = apply_filters( 'WPFormsDB_database', $wpdb );
    $table_name    = $wpform->prefix.'wpforms_db';
    $upload_dir    = wp_upload_dir();


    if ( $fields ) {

        $data           = $fields;
        $uploaded_files = array();

        $form_data   = array();

        $form_data['WPFormsDB_status'] = 'unread';
        foreach ($data as $key => $d) {

            $d['value'] = is_array( $d['value'] ) ? implode(',', $d['value']) : $d['value'];

            $bl   = array('\"',"\'",'/','\\','"',"'");
            $wl   = array('&quot;','&#039;','&#047;', '&#092;','&quot;','&#039;');
            $d['value'] = str_replace($bl, $wl, $d['value'] );

            $form_data[ $d['name'] ] = $d['value'];
                
        }

        /* WPFormsDB before save data. */
        $form_data = apply_filters('WPFormsDB_before_save_data', $form_data);

        do_action( 'WPFormsDB_before_save_data', $form_data );

        $form_post_id = $form_id;
        $form_value   = serialize( $form_data );
        $form_date    = current_time('Y-m-d H:i:s');

        $wpform->insert( $table_name, array(
            'form_post_id' => $form_post_id,
            'form_value'   => $form_value,
            'form_date'    => $form_date
        ) );

        /* WPFormsDB after save data */
        $insert_id = $wpform->insert_id;
        do_action( 'WPFormsDB_after_save_data', $insert_id );
    }

}

add_action( 'wpforms_process_entry_save',  'WPFormsDB_save', 10, 3 );

/**
 * Plugin settings link
 * @param  array $links list of links
 * @return array of links
 */
function wpformsdb_settings_link( $links ) {
    $forms_link = '<a href="admin.php?page=wp-forms-db-list.php">Custom Table  </a>';
    array_unshift($links, $forms_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'wpformsdb_settings_link' );

add_action( 'admin_enqueue_scripts', 'wpse_239302_hide_action_links' );
function wpse_239302_hide_action_links() {
    global $pagenow; 
    if ( $pagenow == 'plugins.php' ) {
        ?>
        <style type="text/css">
            .visible .proupgrade,
            .visible .docs,
            .visible .forum,
            .visible .jetpack-home,
            .visible .support { display: none; } 
        </style>
        <?php
    }
}
// Add Shortcode

add_shortcode( 'CorvidForm', 'corvid_form_dashboard_name_shortcode' );
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
            <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<?php
function corvid_form_dashboard_name_shortcode($atts) {
    $atts = shortcode_atts(
		array(
			'type' => '',
		),
		$atts,
		'CorvidForm'
    );
  
   switch ( $atts['type']) {
       case 'member':
            require_once 'pages/MemebrDidnotFill.php';   
            new MemeberDisplay;
            return;
          break;
        case 'student':
            require_once 'pages/studentForm.php';
            new StudneTable;
            return;
         break;
        case 'stuff':
            require_once 'pages/stuffForm.php';
            new StuffTable;
            return;
         break;
         case 'deniedMember':
            require_once 'pages/deniedMember.php';
            new deniedMemberTable;
            return;
         break;
         
       default:
            require_once 'pages/MemebrDidnotFill.php';    
            new MemeberDisplay;
       return;
           break;
   }
    
}
