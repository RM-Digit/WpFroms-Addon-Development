
<?php
/**
 * WPFormsDB Admin section
 */

if (!defined( 'ABSPATH')) exit;

/**
 * WPFormsDB_Wp_List_Table class will create the page to load the table
 */
class WPFormsDB_Wp_Main_Page
{
    /**
     * Constructor will create the menu item
     */
    public function __construct()
    {
        add_action( 'admin_menu', array($this, 'admin_list_table_page' ) );
    }


    /**
     * Menu item will allow us to load the page to display the table
     */
    public function admin_list_table_page()
    {

		// Fallback: Make sure admin always has access
		$WPFormsDB_cap = ( current_user_can( 'WPFormsDB_access') ) ? 'WPFormsDB_access' : 'manage_options';
       
        add_menu_page( __( 'WPForms Submissions', 'contact-form-WPFormsDB' ), 'COVID Form Dashboard', 
        $WPFormsDB_cap, 'wp-forms-db-list.php', array($this, 'list_table_page'), 
        plugins_url('../images/icon.png', __FILE__) );
        
           
    }
    
    
    /**
     * Display the list table page
     *
     * @return Void
     */
    public function list_table_page()
    {
        if ( ! function_exists('wpforms') ) {

           wp_die( 'Please activate <a href="https://wordpress.org/plugins/wpforms-lite/" target="_blank">WPForms</a> plugin.' );
        }

        $fid  = empty($_GET['fid']) ? 0 : (int) $_GET['fid'];
        $ufid = empty($_GET['ufid']) ? 0 : (int) $_GET['ufid'];

        if ( !empty($fid) && empty($_GET['ufid']) ) {

            new WPFormsDB_Wp_Sub_Page();
            return;
        }

        if( !empty($ufid) && !empty($fid) ){

            new WPFormsDB_Form_Details();
            return;
        }
      
        $ListTable = new WPFormsDB_Main_List_Table();
        $ListTable->prepare_items();
        ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                
                <h2><?php _e( 'WPForms List', 'contact-form-WPFormsDB' ); ?></h2>
                <?php $ListTable->display(); ?>
                <?php 
                    global $wpdb;
                    $cfdb         = apply_filters( 'WPFormsDB_database', $wpdb );
                    $search       = empty( $_REQUEST['s'] ) ? false :  esc_sql( $_POST['s'] );
                    $table_name   = $cfdb->prefix.'wpforms_db';
                    $results =  $cfdb->get_results( "SELECT form_value FROM $table_name");
                    ?>
                    <script>
                        var report_list=[];
                    </script>
                    <?php
                     foreach ($results as $key_1 => $values) {
                       
                       foreach ($values as $key_2 => $value) {
                         
                           $val =json_encode($value);
                          ?>
                          <script>
                             report_list.push(<?php echo $val; ?>);
        
                          </script>
                          <?php

                       }
                     }
                ?>
                <div style ="border-style: dashed; padding: 40px;margin-bottom: 10px;">
                    <div class="btncontainer" style="text-align: center;"> <button class = "denied" style="cursor:pointer;padding: 20px;color: inherit;margin:10px;" onclick="denied_list()">DISPLAY DENIED MEMBERS</button>
                    </div>   
                    <div id="denied_list" class="denied_memebrs" >            
                    </div>
                </div>
                <div style ="border-style: dashed;padding: 40px;">
              
                     <h2 style = "text-align: center;font-size: 30px;">Members who did not fill out the form</h2>
                    <div class="tab">
                    <button class="tablinks" onclick="openCity(event, 'tab-1')">Staff</button>
                    <button class="tablinks" onclick="openCity(event, 'tab-2')">Vinal AM</button>
                    <button class="tablinks" onclick="openCity(event, 'tab-3')">Vinal PM</button>
                    <button class="tablinks" onclick="openCity(event, 'tab-4')">Cole AM</button>
                    <button class="tablinks" onclick="openCity(event, 'tab-5')">Cole PM</button>
                    <button class="tablinks" onclick="openCity(event, 'tab-6')">Norwell High School Blue</button>
                    <button class="tablinks" onclick="openCity(event, 'tab-7')">Norwell High School Gold</button>
                    <button class="tablinks" onclick="openCity(event, 'tab-8')">Norwell Middle School Blue</button>
                    <button class="tablinks" onclick="openCity(event, 'tab-9')">Norwell Middle School Gold</button>
                    </div>

                    <!-- Tab content -->
                    <div id="tab-1" class="tabcontent"></div>
                    <div id="tab-2" class="tabcontent"></div>     
                    <div id="tab-3" class="tabcontent"></div>
                    <div id="tab-4" class="tabcontent"></div>
                    <div id="tab-5" class="tabcontent"></div>
                    <div id="tab-6" class="tabcontent"></div>
                    <div id="tab-7" class="tabcontent"></div>
                    <div id="tab-8" class="tabcontent"></div>
                    <div id="tab-9" class="tabcontent"></div>
                   
                </div> 
                 
                <style>
                        body {font-family: Arial;}
                        #denied_list{
                            display:none;
                        }
                        
                        /* Style the tab */
                        .tab {
                        overflow: hidden;
                        border: 1px solid #ccc;
                        background-color: #f1f1f1;
                        }
                        #denied_list_table th, #denied_list_table td{
                            border:1px solid grey;
                        }
                        #denied_list_table th {
                            background: lightgrey;
                        }
                        #denied_list_table tr:nth-child(even) {
                        background-color: #dddddd;
                        }
                        /* Style the buttons inside the tab */
                        .tab button {
                        background-color: inherit;
                        float: left;
                        border: none;
                        outline: none;
                        cursor: pointer;
                        color:grey;
                        padding: 14px 16px;
                        transition: 0.3s;
                        font-size: 17px;
                        }

                        /* Change background color of buttons on hover */
                        .tab button:hover {
                        background-color: #ddd;
                        }

                        /* Create an active/current tablink class */
                        .tab button.active {
                        background-color: #ccc;
                        }

                        /* Style the tab content */
                        .tabcontent {
                        display: none;
                        padding: 6px 12px;
                        border: 1px solid #ccc;
                        border-top: none;
                        }
                        #school_list td{
                            text-align:center;
                            width:500px;
                            border: groove;
                        }
                        #school_list thead{
                            font-size:18px;
                        }
                        </style>
               
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
               
                <script>

                function openCity(evt, cityName) {
                    var i, tabcontent, tablinks;
                   
                    tabcontent = document.getElementsByClassName("tabcontent");
                    for (i = 0; i < tabcontent.length; i++) {
                        tabcontent[i].style.display = "none";
                    }
                    tablinks = document.getElementsByClassName("tablinks");
                    for (i = 0; i < tablinks.length; i++) {
                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                    }
                    document.getElementById(cityName).style.display = "block";
                    evt.currentTarget.className += " active";
                }

                    $.ajax({
                        url: '../wp-content/plugins/COVID-Form-Dashboard/table/staff.csv',
                        dataType: 'text',
                    }).done(successFunction1);
                    $.ajax({
                        url: '../wp-content/plugins/COVID-Form-Dashboard/table/ColeElementary.csv',
                        dataType: 'text',
                    }).done(successFunction2);
                    $.ajax({
                        url: '../wp-content/plugins/COVID-Form-Dashboard/table/HighSchool.csv',
                        dataType: 'text',
                    }).done(successFunction3);
                    $.ajax({
                        url: '../wp-content/plugins/COVID-Form-Dashboard/table/MiddleSchool.csv',
                        dataType: 'text',
                    }).done(successFunction4);
                    $.ajax({
                        url: '../wp-content/plugins/COVID-Form-Dashboard/table/VinalElementary.csv',
                        dataType: 'text',
                    }).done(successFunction5);
                    
                    function successFunction1(data) {
                       
                        var allRows = data.split(/\r?\n|\r/);
                        var table = '<table id = "school_list">';
                        var did;
                        var key_word_order;
                        for(let i=0;i<allRows[0].split(',').length;i++){
                            if(allRows[0].split(',')[i].includes("mail")) key_word_order= i;
                            console.log(key_word_order);
                        }
                       
                        for (var singleRow = 0; singleRow < allRows.length; singleRow++) {
                            did = 0;
                            if(allRows[singleRow].length == 0) continue;
                            if (singleRow === 0) {
                               
                                table += '<thead>';
                                table += '<tr>';
                                }
                            var rowCells = allRows[singleRow].split(',');
                            if(report_list.toString().includes(rowCells[key_word_order])){
                                console.log("DID");
                                did = 1;
                            }else {
                                did = 0;
                            }
                            if(singleRow !== 0 && did === 0) {
                                table += '<tr>';
                            }
                           
                            for (var rowCell = 0; rowCell < rowCells.length; rowCell++) {
                                if (singleRow === 0) {
                                    table += '<th>';
                                    table += rowCells[rowCell];
                                    table += '</th>';
                                }   
                                else if (did === 0){
                                   
                                    table += '<td>';
                                    table += rowCells[rowCell];
                                    table += '</td>';
                                }
                               
                            }
                            if (singleRow === 0 ) {
                                table += '</tr>';
                                table += '</thead>';
                                table += '<tbody>';
                            } else if(did === 0){
                                table += '</tr>';
                            }
                        } 
                        table += '</tbody>';
                        table += '</table>';
            
                         $("#tab-1").html(table);
                    }

                    function successFunction2(data) {
                        
                        var allRows = data.split(/\r?\n|\r/);
                        var table = '<table id = "school_list">';
                        var did,group;
                        var key_word_order;
                        for(let i=0;i<allRows[0].split(',').length;i++){
                            if(allRows[0].split(',')[i].includes("mail")) key_word_order= i;
                        }
                         for (var singleRow = 0; singleRow < allRows.length; singleRow++) {
                            did = 0;
                            if(allRows[singleRow].length == 0) continue;
                            if (singleRow === 0) {
                                table += '<thead>';
                                table += '<tr>';
                                }
                            var rowCells = allRows[singleRow].split(',');
                            if(rowCells[5]=="AM") group  = 1;
                            else group = 0;
                            if(report_list.toString().includes(rowCells[key_word_order])){
                                console.log("DID");
                              
                                did = 1;
                            }else {
                                did = 0;
                            }
                            if(singleRow !== 0 && did === 0 && group === 1) {
                                table += '<tr>';
                            }
                           
                            for (var rowCell = 0; rowCell < rowCells.length; rowCell++) {
                                if (singleRow === 0) {
                                    table += '<th>';
                                    table += rowCells[rowCell];
                                    table += '</th>';
                                }   
                                else if (did === 0 && group === 1){
                                   
                                    table += '<td>';
                                    table += rowCells[rowCell];
                                    table += '</td>';
                                }
                               
                            }
                            if (singleRow === 0 ) {
                                table += '</tr>';
                                table += '</thead>';
                                table += '<tbody>';
                            } else if(did === 0 && group === 1){
                                table += '</tr>';
                            }
                        } 
                        table += '</tbody>';
                        table += '</table>';
                        
                         $("#tab-4").html(table);
                            
                         table = '<table id = "school_list">';
                          for (var singleRow = 0; singleRow < allRows.length; singleRow++) {
                            did = 0;
                            if(allRows[singleRow].length == 0) continue;
                            if (singleRow === 0) {
                                table += '<thead>';
                                table += '<tr>';
                                }
                            var rowCells = allRows[singleRow].split(',');
                            if(rowCells[5]=="PM") group  = 1;
                            else group = 0;
                            if(report_list.toString().includes(rowCells[key_word_order])){
                                console.log("DID");
                               
                                did = 1;
                            }else {
                                did = 0;
                            }
                            if(singleRow !== 0 && did === 0 && group === 1) {
                                table += '<tr>';
                            }
                           
                            for (var rowCell = 0; rowCell < rowCells.length; rowCell++) {
                                if (singleRow === 0) {
                                    table += '<th>';
                                    table += rowCells[rowCell];
                                    table += '</th>';
                                }   
                                else if (did === 0 && group === 1){
                                   
                                    table += '<td>';
                                    table += rowCells[rowCell];
                                    table += '</td>';
                                }
                               
                            }
                            if (singleRow === 0 ) {
                                table += '</tr>';
                                table += '</thead>';
                                table += '<tbody>';
                            } else if(did === 0 && group === 1){
                                table += '</tr>';
                            }
                        } 
                        table += '</tbody>';
                        table += '</table>';
                        $("#tab-5").html(table);
                        }
                    function successFunction3(data) {
                        
                        var allRows = data.split(/\r?\n|\r/);
                        var table = '<table id = "school_list">';
                        var did,group;
                        var key_word_order;
                        for(let i=0;i<allRows[0].split(',').length;i++){
                            if(allRows[0].split(',')[i].includes("mail")) key_word_order= i;
                        }
                         for (var singleRow = 0; singleRow < allRows.length; singleRow++) {
                            did = 0;
                            if(allRows[singleRow].length == 0) continue;
                            if (singleRow === 0) {
                                table += '<thead>';
                                table += '<tr>';
                                }
                            var rowCells = allRows[singleRow].split(',');
                            if(rowCells[3]=="Blue") group  = 1;
                            else group = 0;
                            if(report_list.toString().includes(rowCells[key_word_order])){
                                console.log("DID");
                              
                                did = 1;
                            }else {
                                did = 0;
                            }
                            if(singleRow !== 0 && did === 0 && group === 1) {
                                table += '<tr>';
                            }
                           
                            for (var rowCell = 0; rowCell < rowCells.length; rowCell++) {
                                if (singleRow === 0) {
                                    table += '<th>';
                                    table += rowCells[rowCell];
                                    table += '</th>';
                                }   
                                else if (did === 0 && group === 1){
                                   
                                    table += '<td>';
                                    table += rowCells[rowCell];
                                    table += '</td>';
                                }
                               
                            }
                            if (singleRow === 0 ) {
                                table += '</tr>';
                                table += '</thead>';
                                table += '<tbody>';
                            } else if(did === 0 && group === 1){
                                table += '</tr>';
                            }
                        } 
                        table += '</tbody>';
                        table += '</table>';
                        
                         $("#tab-6").html(table);
                            
                         table = '<table id = "school_list">';
                          for (var singleRow = 0; singleRow < allRows.length; singleRow++) {
                            did = 0;
                            if(allRows[singleRow].length == 0) continue;
                            if (singleRow === 0) {
                                table += '<thead>';
                                table += '<tr>';
                                }
                            var rowCells = allRows[singleRow].split(',');
                            if(rowCells[3]=="Gold") group  = 1;
                            else group = 0;
                            if(report_list.toString().includes(rowCells[key_word_order])){
                                console.log("DID");
                               
                                did = 1;
                            }else {
                                did = 0;
                            }
                            if(singleRow !== 0 && did === 0 && group === 1) {
                                table += '<tr>';
                            }
                           
                            for (var rowCell = 0; rowCell < rowCells.length; rowCell++) {
                                if (singleRow === 0) {
                                    table += '<th>';
                                    table += rowCells[rowCell];
                                    table += '</th>';
                                }   
                                else if (did === 0 && group === 1){
                                   
                                    table += '<td>';
                                    table += rowCells[rowCell];
                                    table += '</td>';
                                }
                               
                            }
                            if (singleRow === 0 ) {
                                table += '</tr>';
                                table += '</thead>';
                                table += '<tbody>';
                            } else if(did === 0 && group === 1){
                                table += '</tr>';
                            }
                        } 
                        table += '</tbody>';
                        table += '</table>';
                        $("#tab-7").html(table);
                       
                        }
                    function successFunction4(data) {
                        
                        var allRows = data.split(/\r?\n|\r/);
                        var table = '<table id = "school_list">';
                        var did,group;
                        var key_word_order;
                        for(let i=0;i<allRows[0].split(',').length;i++){
                            if(allRows[0].split(',')[i].includes("mail")) key_word_order= i;
                        }
                         for (var singleRow = 0; singleRow < allRows.length; singleRow++) {
                            did = 0;
                            if(allRows[singleRow].length == 0) continue;
                            if (singleRow === 0) {
                                table += '<thead>';
                                table += '<tr>';
                                }
                            var rowCells = allRows[singleRow].split(',');
                            if(rowCells[3]=="Blue") group  = 1;
                            else group = 0;
                            if(report_list.toString().includes(rowCells[key_word_order])){
                                console.log("DID");
                              
                                did = 1;
                            }else {
                                did = 0;
                            }
                            if(singleRow !== 0 && did === 0 && group === 1) {
                                table += '<tr>';
                            }
                           
                            for (var rowCell = 0; rowCell < rowCells.length; rowCell++) {
                                if (singleRow === 0) {
                                    table += '<th>';
                                    table += rowCells[rowCell];
                                    table += '</th>';
                                }   
                                else if (did === 0 && group === 1){
                                   
                                    table += '<td>';
                                    table += rowCells[rowCell];
                                    table += '</td>';
                                }
                               
                            }
                            if (singleRow === 0 ) {
                                table += '</tr>';
                                table += '</thead>';
                                table += '<tbody>';
                            } else if(did === 0 && group === 1){
                                table += '</tr>';
                            }
                        } 
                        table += '</tbody>';
                        table += '</table>';
                        
                         $("#tab-8").html(table);
                            
                         table = '<table id = "school_list">';
                          for (var singleRow = 0; singleRow < allRows.length; singleRow++) {
                            did = 0;
                            if(allRows[singleRow].length == 0) continue;
                            if (singleRow === 0) {
                                table += '<thead>';
                                table += '<tr>';
                                }
                            var rowCells = allRows[singleRow].split(',');
                            if(rowCells[3]=="Gold") group  = 1;
                            else group = 0;
                            if(report_list.toString().includes(rowCells[key_word_order])){
                                console.log("DID");
                               
                                did = 1;
                            }else {
                                did = 0;
                            }
                            if(singleRow !== 0 && did === 0 && group === 1) {
                                table += '<tr>';
                            }
                           
                            for (var rowCell = 0; rowCell < rowCells.length; rowCell++) {
                                if (singleRow === 0) {
                                    table += '<th>';
                                    table += rowCells[rowCell];
                                    table += '</th>';
                                }   
                                else if (did === 0 && group === 1){
                                   
                                    table += '<td>';
                                    table += rowCells[rowCell];
                                    table += '</td>';
                                }
                               
                            }
                            if (singleRow === 0 ) {
                                table += '</tr>';
                                table += '</thead>';
                                table += '<tbody>';
                            } else if(did === 0 && group === 1){
                                table += '</tr>';
                            }
                        } 
                        table += '</tbody>';
                        table += '</table>';
                        $("#tab-9").html(table);
                        }
                    function successFunction5(data) {
                        
                        var allRows = data.split(/\r?\n|\r/);
                        var table = '<table id = "school_list">';
                        var did,group;
                        var key_word_order;
                        for(let i=0;i<allRows[0].split(',').length;i++){
                            if(allRows[0].split(',')[i].includes("mail")) key_word_order= i;
                        }
                         for (var singleRow = 0; singleRow < allRows.length; singleRow++) {
                            did = 0;
                            if(allRows[singleRow].length == 0) continue;
                            if (singleRow === 0) {
                                table += '<thead>';
                                table += '<tr>';
                                }
                            var rowCells = allRows[singleRow].split(',');
                            if(rowCells[5]=="AM") group  = 1;
                            else group = 0;
                            if(report_list.toString().includes(rowCells[key_word_order])){
                                console.log("DID");
                              
                                did = 1;
                            }else {
                                did = 0;
                            }
                            if(singleRow !== 0 && did === 0 && group === 1) {
                                table += '<tr>';
                            }
                           
                            for (var rowCell = 0; rowCell < rowCells.length; rowCell++) {
                                if (singleRow === 0) {
                                    table += '<th>';
                                    table += rowCells[rowCell];
                                    table += '</th>';
                                }   
                                else if (did === 0 && group === 1){
                                   
                                    table += '<td>';
                                    table += rowCells[rowCell];
                                    table += '</td>';
                                }
                               
                            }
                            if (singleRow === 0 ) {
                                table += '</tr>';
                                table += '</thead>';
                                table += '<tbody>';
                            } else if(did === 0 && group === 1){
                                table += '</tr>';
                            }
                        } 
                        table += '</tbody>';
                        table += '</table>';
                        
                         $("#tab-2").html(table);
                            
                         table = '<table id = "school_list">';
                          for (var singleRow = 0; singleRow < allRows.length; singleRow++) {
                            did = 0;
                            if(allRows[singleRow].length == 0) continue;
                            if (singleRow === 0) {
                                table += '<thead>';
                                table += '<tr>';
                                }
                            var rowCells = allRows[singleRow].split(',');
                            if(rowCells[5]=="PM") group  = 1;
                            else group = 0;
                            if(report_list.toString().includes(rowCells[key_word_order])){
                                console.log("DID");
                               
                                did = 1;
                            }else {
                                did = 0;
                            }
                            if(singleRow !== 0 && did === 0 && group === 1) {
                                table += '<tr>';
                            }
                           
                            for (var rowCell = 0; rowCell < rowCells.length; rowCell++) {
                                if (singleRow === 0) {
                                    table += '<th>';
                                    table += rowCells[rowCell];
                                    table += '</th>';
                                }   
                                else if (did === 0 && group === 1){
                                   
                                    table += '<td>';
                                    table += rowCells[rowCell];
                                    table += '</td>';
                                }
                               
                            }
                            if (singleRow === 0 ) {
                                table += '</tr>';
                                table += '</thead>';
                                table += '<tbody>';
                            } else if(did === 0 && group === 1){
                                table += '</tr>';
                            }
                        } 
                        table += '</tbody>';
                        table += '</table>';
                        $("#tab-3").html(table);
                    }
                function denied_list(){
                  
                    if( $('#denied_list').css('display') == "none"){
                      
                        $('#denied_list').slideDown();
                    
                    } else{
                        $('#denied_list').slideUp();
                    }

                    var denied_list_head =[];
                    var denied_list_body = [];
                    var head_array_index = 0,body_array_index = 0;
                    var form_type1 = report_list[0].split('"')[0].split('{')[0];
                    var denied_list_keyword_order =[];
                    var studnet_table_head_capture =0;

                    var stuff_table_head_capture = 0;
                    var stuff_denied_list_head =[];
                    var stuff_denied_list_body = [];
                    var stuff_head_array_index = 0,stuff_body_array_index = 0;
                    var stuff_denied_list_keyword_order =[];
                    for (let index = 0; index < report_list.length; index++) {
                        const element = report_list[index];  
                        var report_single_row_array = element.split('"');
                        
                        if(report_single_row_array[0].split('{')[0] != form_type1) 
                        {
                            if(stuff_table_head_capture == 0){
                                stuff_table_head_capture = 1;
                                for(let ii =0;ii<element.split('"').length;ii++ ){
                                    if(ii>=5 && ii%4 == 1){
                                    
                                        if(report_single_row_array[ii] == "No Symptoms") stuff_denied_list_keyword_order[0] = stuff_head_array_index;
                                        if(report_single_row_array[ii] == "Are you in compliance with the State of Massachusetts Travel ordinance?") stuff_denied_list_keyword_order[1] = stuff_head_array_index;
                                        if(report_single_row_array[ii] == "In the past 14 days, have you had close contact* with a person known to be infected with COVID-19?") stuff_denied_list_keyword_order[2] = stuff_head_array_index;
                                        stuff_denied_list_head[stuff_head_array_index++]=report_single_row_array[ii];
                                    }
                                }
                            }
                            for(let i = 0; i<element.split('"').length;i++)
                            { 
                                if(i>=5 && i%4 == 3){
                                    stuff_denied_list_body[stuff_body_array_index++] = report_single_row_array[i];
                                   
                                }
                            }
                               
                        }
                        else{
                                if(studnet_table_head_capture == 0) {
                                    studnet_table_head_capture=1;
                                for(let ii =0;ii<element.split('"').length;ii++ ){
                                    if(ii>=5 && ii%4 == 1){
                                    
                                        if(report_single_row_array[ii] == "No Symptoms") denied_list_keyword_order[0] = head_array_index;
                                        if(report_single_row_array[ii] == "Are you in compliance with the State of Massachusetts Travel ordinance?") denied_list_keyword_order[1] = head_array_index;
                                        if(report_single_row_array[ii] == "In the past 14 days, have you had close contact* with a person known to be infected with COVID-19?") denied_list_keyword_order[2] = head_array_index;
                                        denied_list_head[head_array_index++]=report_single_row_array[ii];
                                    }
                                }
                            }
                            for(let i = 0; i<element.split('"').length;i++)
                            {
                                if(i>=5 && i%4 == 3){
                                    denied_list_body[body_array_index++] = report_single_row_array[i];
                                }
                            }
                        }       
                    
                    }
                    var table = '<h2 style="margin-top:50px">Student Table</h2><table id = "denied_list_table">';
                    var thead = '<thead><tr>'
                    for(let i = 0;i<denied_list_head.length;i++ ){
                        thead += '<th>';
                        thead += denied_list_head[i]
                        thead += '</th>';
                    }
                    thead += '</tr></thead>';
                    var tbody = '<tbody>';
                   
                        for(let i = 0;i<denied_list_body.length;i++ ){
                    
                            if(i % denied_list_head.length == 0){
                                if(denied_list_body[i+denied_list_keyword_order[2]] !== "Yes") {
                                    i+=(denied_list_head.length-1);
                                    continue;
                                }
                                if(denied_list_body[i+denied_list_keyword_order[1]] !== "No" && denied_list_body[i+denied_list_keyword_order[0]] !== "") {
                                    i+=(denied_list_head.length-1);
                                    continue;
                                }
                            }
                            if(i % denied_list_head.length == 0) tbody += '</tr><tr>';
                            tbody += '<td>';
                            tbody += denied_list_body[i]
                            tbody += '</td>';
                        
                        }
                    
                    tbody+= '</tbody>';
                    table += thead + tbody + '</table>';

                    var stuff_table = '<h2 style="margin-top:50px">Stuff Table</h2><table id = "denied_list_table" >';
                    var stuff_thead = '<thead><tr>'
                   
                    for(let i = 0;i<stuff_denied_list_head.length;i++ ){
                        stuff_thead += '<th>';
                        stuff_thead += stuff_denied_list_head[i]
                        stuff_thead += '</th>';
                    }
                    stuff_thead += '</tr></thead>';
                    var stuff_tbody = '<tbody>';
                   
                        for(let i = 0;i<stuff_denied_list_body.length;i++ ){
                    
                            if(i % stuff_denied_list_head.length == 0){
                                if(stuff_denied_list_body[i+stuff_denied_list_keyword_order[2]] !== "Yes") {
                                    i+=(stuff_denied_list_head.length-1);
                                    continue;
                                }
                                if(stuff_denied_list_body[i+stuff_denied_list_keyword_order[1]] !== "No" && stuff_denied_list_body[i+stuff_denied_list_keyword_order[0]] !== "") {
                                    i+=(stuff_denied_list_head.length-1);
                                    continue;
                                }
                            }
                            if(i % stuff_denied_list_head.length == 0) stuff_tbody += '</tr><tr>';
                            stuff_tbody += '<td>';
                            stuff_tbody += stuff_denied_list_body[i]
                            stuff_tbody += '</td>';
                        
                        }
                    
                        stuff_tbody+= '</tbody>';
                        stuff_table += stuff_thead + stuff_tbody + '</table>';
                         $("#denied_list").html(table+stuff_table);
                }
                
                </script>

               
            </div>
        <?php
    }

}
// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class WPFormsDB_Main_List_Table extends WP_List_Table
{

    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {

        global $wpdb;
        $cfdb        = apply_filters( 'WPFormsDB_database', $wpdb );
        $table_name  = $cfdb->prefix.'wpforms_db';
        $columns     = $this->get_columns();
        $hidden      = $this->get_hidden_columns();
        $data        = $this->table_data();
        $perPage     = 10;
        $currentPage = $this->get_pagenum();
        $count_forms = wp_count_posts('wpforms');
        $totalItems  = $count_forms->publish;


        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $this->_column_headers = array($columns, $hidden );
        $this->items = $data;
    }
    
    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {


        $columns = array(
            'name' => __( 'Name', 'contact-form-WPFormsDB' ),
            'count'=> __( 'Count', 'contact-form-WPFormsDB' )
        );

        return $columns;
    }
    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        global $wpdb;

        $cfdb         = apply_filters( 'WPFormsDB_database', $wpdb );
        $data         = array();
        $table_name   = $cfdb->prefix.'wpforms_db';
        $page         = $this->get_pagenum();
        $page         = $page - 1;
        $start        = $page * 10;

        $args = array(
            'post_type'=> 'wpforms',
            'order'    => 'ASC',
            'posts_per_page' => 10,
            'offset' => $start
        );

        $the_query = new WP_Query( $args );

        while ( $the_query->have_posts() ) : $the_query->the_post();
            $form_post_id = get_the_id();
            $totalItems   = $cfdb->get_var("SELECT COUNT(*) FROM $table_name WHERE form_post_id = $form_post_id");
            $title = get_the_title();
            $link  = "<a class='row-title' href=admin.php?page=wp-forms-db-list.php&fid=$form_post_id>%s</a>";
            $data_value['name']  = sprintf( $link, $title );
            $data_value['count'] = sprintf( $link, $totalItems );
            $data[] = $data_value;
        endwhile;

        return $data;
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

}

