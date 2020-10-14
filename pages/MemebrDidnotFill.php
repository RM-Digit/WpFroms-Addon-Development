

<?php
class MemeberDisplay 
{
    public function __construct()
    {
        
        $this->memberDidnot();
    }
    public function memberDidnot(){
        ?>
        <div id="layout" class="pagewidth clearfix">

            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                
                <h2><?php _e( 'Member List', 'contact-form-WPFormsDB' ); ?></h2>

                <?php 
                    global $wpdb;
                    $cfdb         = apply_filters( 'WPFormsDB_database', $wpdb );
                    $search       = empty( $_REQUEST['s'] ) ? false :  esc_sql( $_POST['s'] );
                    $table_name   = $cfdb->prefix.'wpforms_db';
                    $results =  $cfdb->get_results( "SELECT form_value FROM $table_name");
                    $form_date = $cfdb->get_results( "SELECT form_date FROM $table_name");
                    ?>
                        <script>
                            var form_date = [];
                            var report_list = [];
                            var did_member_list = [];
                        </script>
                    <?php
                    foreach ( $form_date as $key => $val):
                    foreach($val as $key_3 => $val_date)
                    ?>
                        <script>
                          form_date.push('<?php echo $val_date; ?>');
                        </script>
                    <?php
                    endforeach;
                   
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
               
               <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
                <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
               
                <div style ="border-style: dashed;">
                     
                     <h2 style = "text-align: center;font-size: 30px;">Members who did not fill out the form</h2>
                     <input type="text" name="date_filter" placeholder="Select Date..." value="" />
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

                        /* Style the tab */
                        .tab {
                        overflow: hidden;
                        border: 1px solid #ccc;
                        background-color: #f1f1f1;
                        }

                        /* Style the buttons inside the tab */
                        .tab button {
                        background-color: inherit;
                        float: left;
                        border: none;
                        outline: none;
                        cursor: pointer;
                        padding: 14px 16px;
                        transition: 0.3s;
                        font-size: 17px;
                        color: grey;
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
                        </style>
               
                
                <script>
                
              
                var choosen_date_for_memebrlist = $('input[name="date_filter"]');
                did_member_list =report_list;
                $('input[name="date_filter"]').datepicker( {dateFormat : "yy-mm-dd"} );
                choosen_date_for_memebrlist.change(()=>{
                    did_member_list = [];
                 
                    for(var i = 0 ;i<report_list.length;i++){
                        if(choosen_date_for_memebrlist.val() == form_date[i].split(" ")[0]) did_member_list[i]=report_list[i]; 
                    }
                    for(i=1;i<=9 ; i++) $('#tab-'+i).html(" ");
                   
                    
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
                      
                })
        

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
                          
                            if(did_member_list.toString().includes(rowCells[key_word_order])){
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
                            if(did_member_list.toString().includes(rowCells[key_word_order])){
                                console.log("DID");
                                console.log(rowCells[5]);
                              
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
                         var key_word_order;
                        for(let i=0;i<allRows[0].split(',').length;i++){
                            if(allRows[0].split(',')[i].includes("mail")) key_word_order= i;
                        }
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
                            if(did_member_list.toString().includes(rowCells[key_word_order])){
                                console.log("DID");
                                console.log(rowCells[5]);
                               
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
                            if(did_member_list.toString().includes(rowCells[key_word_order])){
                                console.log("DID");
                                console.log(rowCells[5]);
                              
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
                         var key_word_order;
                        for(let i=0;i<allRows[0].split(',').length;i++){
                            if(allRows[0].split(',')[i].includes("mail")) key_word_order= i;
                        }
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
                            if(did_member_list.toString().includes(rowCells[key_word_order])){
                                console.log("DID");
                                console.log(rowCells[5]);
                               
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
                            if(did_member_list.toString().includes(rowCells[key_word_order])){
                                console.log("DID");
                                console.log(rowCells[5]);
                              
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
                         var key_word_order;
                        for(let i=0;i<allRows[0].split(',').length;i++){
                            if(allRows[0].split(',')[i].includes("mail")) key_word_order= i;
                        }
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
                            if(did_member_list.toString().includes(rowCells[key_word_order])){
                                console.log("DID");
                                console.log(rowCells[5]);
                               
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
                            if(did_member_list.toString().includes(rowCells[key_word_order])){
                                console.log("DID");
                                console.log(rowCells[5]);
                              
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
                         var key_word_order;
                        for(let i=0;i<allRows[0].split(',').length;i++){
                            if(allRows[0].split(',')[i].includes("mail")) key_word_order= i;
                        }
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
                            if(did_member_list.toString().includes(rowCells[key_word_order])){
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
               
                </script>
            </div>
        <?php
    } 
}
  
?>
