<?php

/**
 * WPFormsDB Admin subpage
 */

if (!defined( 'ABSPATH')) exit;

/**
 * WPFormsDB_Wp_List_Table class will create the page to load the table
 */
class WPFormsDB_Wp_Sub_Page
{
    private $form_post_id;
    private $search;

    /**
     * Constructor start subpage
     */
    public function __construct()
    {
        
        $this->form_post_id = (int) $_GET['fid'];
        $this->list_table_page();
    }
    
    /**
     * Display the list table page
     *
     * @return Void
     */
    public function list_table_page()
    {
      $ListTable = new WPFormsDB_List_Table();
        $ListTable->prepare_items();
        ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h2><?php echo get_the_title( $this->form_post_id ); ?></h2>
                <form method="post" action="">

                    <?php $ListTable->search_box('Search', 'search'); ?>
                    <?php $ListTable->display(); ?>
                </form>
            </div>
        <?php
    }

}

// WP_List_Table is not loaded automatically so we need to load it in our application

/**
 * Create a new table class that will extend the WP_List_Table
 */

class WPFormsDB_List_Table extends WP_List_Table
{
    private $form_post_id;
    private $column_titles;

    public function __construct() {
       
        parent::__construct(
            array(
                'singular' => 'contact_form',
                'plural'   => 'contact_forms',
                'ajax'     => false
            )
        );

    }

    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {

        $this->form_post_id =  (int) $_GET['fid'];
        $search = empty( $_REQUEST['s'] ) ? false :  esc_sql( $_POST['s'] );
        echo $this->search;
        $form_post_id  = $this->form_post_id;

        global $wpdb;

        $this->process_bulk_action();

        $cfdb        = apply_filters( 'WPFormsDB_database', $wpdb );
        $table_name  = $cfdb->prefix.'wpforms_db';
        $columns     = $this->get_columns();
        $hidden      = $this->get_hidden_columns();
        $sortable    = $this->get_sortable_columns();
        $data        = $this->table_data();

        //usort( $data, array( &$this, 'sort_data' ) );

        $perPage     = 100;
        $currentPage = $this->get_pagenum();
        if ( ! empty($search) ) {

            $totalItems  = $cfdb->get_var("SELECT COUNT(*) FROM $table_name WHERE form_value LIKE '%$search%' AND form_post_id = '$form_post_id' ");
         }else{

            $totalItems  = $cfdb->get_var("SELECT COUNT(*) FROM $table_name WHERE form_post_id = '$form_post_id'");
        }

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
        $this->_column_headers = array($columns, $hidden ,$sortable);
        $this->items = $data;
    }
    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $form_post_id  = $this->form_post_id;

        global $wpdb;
        $cfdb          = apply_filters( 'WPFormsDB_database', $wpdb );
        $table_name = $cfdb->prefix.'wpforms_db';

        $results    = $cfdb->get_results( "SELECT * FROM $table_name 
        WHERE form_post_id = $form_post_id ORDER BY form_id DESC LIMIT 1", OBJECT );

        $first_row            = isset($results[0]) ? unserialize( $results[0]->form_value ): 0 ;
        $columns              = array();
        $index =0;
        if( !empty($first_row) ){
            //$columns['form_id'] = $results[0]->form_id;
            $columns['cb']      = '<input type="checkbox" />';
            foreach ($first_row as $key => $value) {
                $index++;
                if ( $key == 'WPFormsDB_status' ) continue;
                
                $key_val       = str_replace( array('your-', 'WPFormsDB_file'), '', $key);
                $key_val_full =$key_val;
                $key_val = ( strlen($key_val) > 30 ) ? substr($key_val, 0, 30).'...': $key_val;
                $key_val = '<input style = "width:100%;margin-left: 0px;" type="text" onkeyup="myFilter(this.value,'.($index-2).',\''.$key_val_full.'\')" placeholder="'.$key_val.'"><br>';
                ?>
                <script>
                    var requestURL;
                    var index = <?php echo $index;?>;
                    
                    function myFilter(val,num,key_val_full){
                    var key_val =key_val_full.replaceAll(" ", "-*-");
                  
                    requestURL = key_val+"99"+val;
                    jQuery("#the-list").find("td").each(function(i, el) {
                        
                        if(i%index == num){
                            if(!jQuery(el).children().get(0).textContent.toUpperCase().includes(val.toUpperCase())) {
                                console.log("!=");
                                jQuery(this).parent().hide();
                            } else{
                                console.log("==");
                                jQuery(this).parent().show();
                            }
                        }
                    })
                }
                
               
                </script>
                <?php
                $columns[$key] = ucfirst( $key_val );
               
                $this->column_titles[] = $key_val;
               
             
                // if ( sizeof($columns) > 4) break;
            }
            $columns['form-date'] = 'Date';
        }


        return $columns;
    }
    /**
     * Define check box for bulk action (each row)
     * @param  $item
     * @return checkbox
     */
    public function column_cb($item){
        return sprintf(
             '<input type="checkbox" name="%1$s[]" value="%2$s" />',
             $this->_args['singular'],
             $item['form_id']
        );
    }
    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return  array('form_id');
    }
    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
       return array('form-date' => array('form-date', true));
    }
    /**
     * Define bulk action
     * @return Array
     */
    public function get_bulk_actions() {

        return array(
            'read'   => __( 'Read', 'contact-form-WPFormsDB' ),
            'unread' => __( 'Unread', 'contact-form-WPFormsDB' ),
            'delete' => __( 'Delete', 'contact-form-WPFormsDB' )
        );

    }
    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        $data = array();
        global $wpdb;
        $cfdb         = apply_filters( 'WPFormsDB_database', $wpdb );
        $search       = empty( $_REQUEST['s'] ) ? false :  esc_sql( $_POST['s'] );
        $table_name   = $cfdb->prefix.'wpforms_db';
        $page         = $this->get_pagenum();
        $page         = $page - 1;
        $start        = $page * 100;
        $form_post_id = $this->form_post_id;

        $orderby = isset($_GET['orderby']) ? 'form_date' : 'form_id';
        $order   = isset($_GET['order']) ? $_GET['order'] : 'desc';
        $order   = esc_sql($order);

        if ( ! empty($search) ) {

           $results = $cfdb->get_results( "SELECT * FROM $table_name WHERE  form_value LIKE '%$search%'
           AND form_post_id = '$form_post_id'
           ORDER BY $orderby $order
           LIMIT $start,100", OBJECT );
        }else{

            $results = $cfdb->get_results( "SELECT * FROM $table_name WHERE form_post_id = $form_post_id
            ORDER BY $orderby $order
            LIMIT $start,100", OBJECT );
        }

        foreach ( $results as $result ) {

            $form_value = unserialize( $result->form_value );

            $link  = "<b><a href=admin.php?page=wp-forms-db-list.php&fid=%s&ufid=%s>%s</a></b>";
            if(isset($form_value['WPFormsDB_status']) && ( $form_value['WPFormsDB_status'] === 'read' ) )
                $link  = "<a href=admin.php?page=wp-forms-db-list.php&fid=%s&ufid=%s>%s</a>";



            $fid   = $result->form_post_id;
            $form_values['form_id'] = $result->form_id;

            foreach ( $this->column_titles as $col_title) {
                $form_value[ $col_title ] = isset( $form_value[ $col_title ] ) ?
                                $form_value[ $col_title ] : '';
            }

            foreach ($form_value as $k => $value) {

                $ktmp = $k;
                
                $can_foreach = is_array($value) || is_object($value);

                if ( $can_foreach ) {

                    foreach ($value as $k_val => $val):
                        
                        $val                = esc_html( $val );
                        $form_values[$ktmp] = ( strlen($val) > 30 ) ? substr($val, 0, 30).'...': $val;
                        $form_values[$ktmp] = sprintf($link, $fid, $result->form_id, $form_values[$ktmp]);
                       
                    endforeach;
                }else{
                    $value = esc_html( $value );
                    $form_values[$ktmp] = ( strlen($value) > 30 ) ? substr($value, 0, 30).'...': $value;
                    $form_values[$ktmp] = sprintf($link, $fid, $result->form_id, $form_values[$ktmp]);
                    
                }
                
            }
            $form_values['form-date'] = sprintf($link, $fid, $result->form_id, $result->form_date );
            
            $data[] = $form_values;
          
        }
        ?>
        <script>
        var data = <?php echo json_encode($data);?>;
      
        data.forEach((element,index)=> {
            var result = {};
            Object.keys(element).forEach(function(key) {
              
            });

        });
          
         </script>
        <?php
        return $data;
    }
    /**
     * Define bulk action
     *
     */
    public function process_bulk_action(){

        global $wpdb;
        $cfdb       = apply_filters( 'WPFormsDB_database', $wpdb );
        $table_name = $cfdb->prefix.'wpforms_db';
        $action     = $this->current_action();

        if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {

            $nonce        = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
            $nonce_action = 'bulk-' . $this->_args['plural'];

            if ( !wp_verify_nonce( $nonce, $nonce_action ) ){

                wp_die( 'Not valid..!!' );
            }
        }

        if( 'delete' === $action ) {

            $form_ids = esc_sql( $_POST['contact_form'] );

            foreach ($form_ids as $form_id):

                $results       = $cfdb->get_results( "SELECT * FROM $table_name WHERE form_id = $form_id LIMIT 1", OBJECT );
                $result_value  = $results[0]->form_value;
                $result_values = unserialize($result_value);
                $upload_dir    = wp_upload_dir();
                $WPFormsDB_dirname = $upload_dir['basedir'].'/WPFormsDB_uploads';

                foreach ($result_values as $key => $result) {

                   if ( ( strpos($key, 'WPFormsDB_file') !== false ) &&
                        file_exists($WPFormsDB_dirname.'/'.$result) ) {

                       unlink($WPFormsDB_dirname.'/'.$result);
                   }

                }

                $cfdb->delete(
                    $table_name ,
                    array( 'form_id' => $form_id ),
                    array( '%d' )
                );
            endforeach;

        }else if( 'read' === $action ){

            $form_ids = esc_sql( $_POST['contact_form'] );
            foreach ($form_ids as $form_id):

                $results       = $cfdb->get_results( "SELECT * FROM $table_name WHERE form_id = '$form_id' LIMIT 1", OBJECT );
                $result_value  = $results[0]->form_value;
                $result_values = unserialize( $result_value );
                $result_values['WPFormsDB_status'] = 'read';
                $form_data = serialize( $result_values );
                $cfdb->query(
                    "UPDATE $table_name SET form_value = '$form_data' WHERE form_id = '$form_id'"
                );

            endforeach;

        }else if( 'unread' === $action ){

            $form_ids = esc_sql( $_POST['contact_form'] );
            foreach ($form_ids as $form_id):

                $results       = $cfdb->get_results( "SELECT * FROM $table_name WHERE form_id = '$form_id' LIMIT 1", OBJECT );
                $result_value  = $results[0]->form_value;
                $result_values = unserialize( $result_value );
                $result_values['WPFormsDB_status'] = 'unread';
                $form_data = serialize( $result_values );
                $cfdb->query(
                    "UPDATE $table_name SET form_value = '$form_data' WHERE form_id = '$form_id'"
                );
            endforeach;
        }else{

        }




    }
    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        return $item[ $column_name ];

    }
    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'form_date';
        $order = 'asc';
        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }
        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }
        $result = strcmp( $a[$orderby], $b[$orderby] );
        if($order === 'asc')
        {
            return $result;
        }
        return -$result;
    }
    /**
     * Display the bulk actions dropdown.
     *
     * @since 3.1.0
     * @access protected
     *
     * @param string $which The location of the bulk actions: 'top' or 'bottom'.
     *                      This is designated as optional for backward compatibility.
     */
    protected function bulk_actions( $which = '' ) {
        if ( is_null( $this->_actions ) ) {
            $this->_actions = $this->get_bulk_actions();
            /**
             * Filters the list table Bulk Actions drop-down.
             *
             * The dynamic portion of the hook name, `$this->screen->id`, refers
             * to the ID of the current screen, usually a string.
             *
             * This filter can currently only be used to remove bulk actions.
             *
             * @since 3.5.0
             *
             * @param array $actions An array of the available bulk actions.
             */
            $this->_actions = apply_filters( "bulk_actions-{$this->screen->id}", $this->_actions );
            $two = '';
        } else {
            $two = '2';
        }

        if ( empty( $this->_actions ) )
            return;

        echo '<label for="bulk-action-selector-' . esc_attr( $which ) . '" class="screen-reader-text">' . __( 'Select bulk action', 'contact-form-WPFormsDB' ) . '</label>';
        echo '<select name="action' . $two . '" id="bulk-action-selector-' . esc_attr( $which ) . "\">\n";
        echo '<option value="-1">' . __( 'Bulk Actions', 'contact-form-WPFormsDB' ) . "</option>\n";

        foreach ( $this->_actions as $name => $title ) {
            $class = 'edit' === $name ? ' class="hide-if-no-js"' : '';

            echo "\t" . '<option value="' . $name . '"' . $class . '>' . $title . "</option>\n";
        }

        echo "</select>\n";

        submit_button( __( 'Apply', 'contact-form-WPFormsDB' ), 'action', '', false, array( 'id' => "doaction$two" ) );
        echo "\n";
        do_action('form_data_UI');
        $nonce = wp_create_nonce( 'dnonce' );
        echo "<a id='exportbtn' href='".$_SERVER['REQUEST_URI']."&wpforms-csv=true&nonce=".$nonce."' style='float:right; margin:0;' class='button'>";
        _e( 'Export CSV', 'contact-form-WPFormsDB' );
        echo '</a>';
        ?>
        <script>
            jQuery('.bottom #exportbtn').click(()=>{        
                var href_value = jQuery('.bottom #exportbtn').attr('href');
                jQuery('.bottom #exportbtn').attr('href',href_value+'&reqData='+requestURL);
                
            })
            jQuery('.top #exportbtn').click(()=>{        
                var href_value = jQuery('.top #exportbtn').attr('href');
                jQuery('.top #exportbtn').attr('href',href_value+'&reqData='+requestURL);
                
            })
            
        </script>
        <?php
        do_action('WPFormsDB_after_export_button');
       
    }
}
	
		// include CSS/JS, in our case jQuery UI datepicker
		add_action( 'admin_enqueue_scripts', 'jqueryui');
 
		// HTML of the filter
		add_action( 'form_data_UI', 'form' );
 
		// the function that filters posts
	/*
	 * Add jQuery UI CSS and the datepicker script
	 * Everything else should be already included in /wp-admin/ like jquery, jquery-ui-core etc
	 * If you use WooCommerce, you can skip this function completely
	 */
	function jqueryui(){
		wp_enqueue_style( 'jquery-ui', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.min.css' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
	}
 
	/*
	 * Two input fields with CSS/JS
	 * If you would like to move CSS and JavaScript to the external file - welcome.
	 */
	function form(){
 
		$from = ( isset( $_GET['mishaDateFrom'] ) && $_GET['mishaDateFrom'] ) ? $_GET['mishaDateFrom'] : '';
		$to = ( isset( $_GET['mishaDateTo'] ) && $_GET['mishaDateTo'] ) ? $_GET['mishaDateTo'] : '';
 
		echo '<style>
		input[name="mishaDateFrom"], input[name="mishaDateTo"]{
			line-height: 28px;
			height: 28px;
			margin: 0;
			width:125px;
        }
        .manage-column {
            vertical-align: baseline;
        }
        #filter-query-submit{
            margin-right: 50px;
        }
		</style>
 
		<input type="text" name="mishaDateFrom" placeholder="Date From" value="' . esc_attr( $from ) . '" />
        <input type="text" name="mishaDateTo" placeholder="Date To" value="' . esc_attr( $to ) . '" />
        <input type="text" name="searchname" placeholder="name" value="" />
        <input type="text" name="searchemail" placeholder="email" value="" />
        <input type="button" name="filter_action" id="filter-query-submit" class="button" value="Filter">
        
        <select name="school" id="school" style = "float: initial;">
            <option value="all" selected>All Schools</option>
            <option value="High">Norwell High School</option>
            <option value="Middle">Norwell Middle School</option>
            <option value="Cole">Grace Farrar Cole Elementary School </option>
            <option value="William">William G. Vinal Elementary School</option>
        </select>
        <script>
        
		jQuery( function($) {

         
            console.log(index);
			var from = $(\'input[name="mishaDateFrom"]\'),
			    to = $(\'input[name="mishaDateTo"]\');
 
			$( \'input[name="mishaDateFrom"], input[name="mishaDateTo"]\' ).datepicker( {dateFormat : "mm/dd/yy"} );
    			from.on( \'change\', function() {
				to.datepicker( \'option\', \'minDate\', from.val() );
			});
            
			to.on( \'change\', function() {
				from.datepicker( \'option\', \'maxDate\', to.val() );
            });
            $("#school").change(()=>{
                
                var value = $("#school option:selected").val();
     
                requestURL = value;
                $("#the-list").find("td").each(function(i, el) {
                   
                    if(i%index == 3){
                       if(!$(el).children().get(0).textContent.includes(value) && value !== "all") {
                            console.log("!=");
                            $(this).parent().hide();
                        } else{
                            console.log("==");
                            $(this).parent().show();
                        }
                    }
                })
            })
            $("#filter-query-submit").click(()=>{
                var date_picker_from = $( \'input[name="mishaDateFrom"]\' ).val();
                var date_picker_to = $( \'input[name="mishaDateTo"]\' ).val();
                var from_d_m_y = date_picker_from.split("/");
                var to_d_m_y = date_picker_to.split("/");
                var display = true;
              
                $("#the-list").find("td").each(function(i, el) {
                    if(i%index == 0 && date_picker_from !== ""){
                    var inputEl_d_m_y = $(el).children().get(0).textContent.split("/");
                        for(var i=0; i<inputEl_d_m_y.length;i++) {
                            inputEl_d_m_y[i] = parseInt(inputEl_d_m_y[i]);
                            from_d_m_y[i] = parseInt(from_d_m_y[i]);
                            to_d_m_y[i] = parseInt(to_d_m_y[i]);
                 
                            if( !(inputEl_d_m_y[i] >= from_d_m_y[i] && inputEl_d_m_y[i] <= to_d_m_y[i]) ) display = false;
                            
                        }

                        if(!display){
                            $(this).parent().hide();
                        } else{
                            $(this).parent().show();
                        }
                    }
                })


                //name
                var searchname = $( \'input[name="searchname"]\' ).val();
                var searchemail = $( \'input[name="searchemail"]\' ).val();
                localStorage.setItem("req_header", searchname);
                 requestURL = searchname;
                $("#the-list").find("td").each(function(i, el) {
                   
                    if(i%index == 1 && searchname != ""){
                                   
                       if(!$(el).children().get(0).textContent.includes(searchname)  && searchname != "") {
                        
                            $(this).parent().hide();   
                        
                        } else{
                           
                            $(this).parent().show();
                        }
                    }
                })
                //email
                $("#the-list").find("td").each(function(i, el) {
                   
                    if(i%index == 2 && searchemail != ""){
                               
                       if( !$(el).children().get(0).textContent.includes(searchemail) && searchemail != "") {
                      
                        $(this).parent().hide();   
                    
                        } else{
                      
                            $(this).parent().show();
                        }
                    }
                })
            })
            

		});
		</script>';
 
    }


    

    ?>
    
 
