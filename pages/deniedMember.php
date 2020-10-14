

<?php
class deniedMemberTable 
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
                
                <h2><?php _e( 'Denied Member List', 'contact-form-WPFormsDB' ); ?></h2>

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
                            var denied_member_list = [];
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
               
                <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
                <!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
                <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
                <input type="text" name="date_filter_denied" placeholder="Select Date..." value="" />
                <div id="denied_list" class="denied_memebrs" > 
                 
               
                <script>
                  
                    var choosen_date = $('input[name="date_filter_denied"]');
                    var cache_data,cache_can=0,cache_data_student,cache_data_stuff;
                    var did_memebr_list_index=0;
                    denied_member_list =report_list;
                    $('input[name="date_filter_denied"]').datepicker( {dateFormat : "yy-mm-dd"} );
                    denied_list();
                    choosen_date.change(()=>{
                        denied_member_list = [];
                        did_memebr_list_index=0;
                        $("#denied_list").html('');
                        for(var i = 0 ;i<report_list.length;i++){
                            if(choosen_date.val() == form_date[i].split(" ")[0])
                            {   
                                denied_member_list[did_memebr_list_index++]=report_list[i];
                                 console.log(denied_member_list.length)
                            }
                        }
                        if(denied_member_list.length !== 0) denied_list(); 
                        else $("#denied_list").html(cache_data);    
                    })
            
                    function denied_list(){
                        var denied_list_head =[];
                        var denied_list_body = [];
                        var head_array_index = 0,body_array_index = 0;
                        var form_type1 ;
                        var denied_list_keyword_order =[];
                        var studnet_table_head_capture =0;

                        var stuff_table_head_capture = 0;
                        var stuff_denied_list_head =[];
                        var stuff_denied_list_body = [];
                        var stuff_head_array_index = 0,stuff_body_array_index = 0;
                        var stuff_denied_list_keyword_order =[];
                        if(denied_member_list.length > 0) form_type1 = denied_member_list[0].split('"')[0].split('{')[0];
                        for (let index = 0; index < denied_member_list.length; index++) {
                            const element = denied_member_list[index];  
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
                            var table = '<h2 style="margin-top:50px">Student Table</h2><table style=" border: 1px dashed;"id = "denied_list_table">';
                            var thead = '<thead><tr>'
                            for(let i = 0;i<denied_list_head.length;i++ ){
                                thead += '<th style=" border:1px solid grey;background: lightgrey;">';
                                thead += denied_list_head[i]
                                thead += '</th>';
                            }
                            thead += '</tr></thead>';
                            if(denied_list_head.length == 0)  thead = cache_data_student;
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
                                if(i % denied_list_head.length == 0) tbody += '</tr><tr style=" border:1px solid grey;">';
                                tbody += '<td style=" border:1px solid grey;">';
                                tbody += denied_list_body[i]
                                tbody += '</td>';
                            
                            }
                        
                            tbody+= '</tbody>';
                            table += thead + tbody + '</table>';

                            var stuff_table = '<h2 style="margin-top:50px;">Stuff Table</h2><table style ="border: 1px dashed;" id = "denied_list_table" >';
                            var stuff_thead = '<thead><tr >'
                            
                            for(let i = 0;i<stuff_denied_list_head.length;i++ ){
                                stuff_thead += '<th style=" border:1px solid grey;background: lightgrey;" >';
                                stuff_thead += stuff_denied_list_head[i]
                                stuff_thead += '</th>';
                            }
                            stuff_thead += '</tr></thead>';
                            if(stuff_denied_list_head.length == 0)  stuff_thead = cache_data_stuff;
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
                                if(i % stuff_denied_list_head.length == 0) stuff_tbody += '</tr><tr style=" border:1px solid grey;">';
                                stuff_tbody += '<td style=" border:1px solid grey;">';
                                stuff_tbody += stuff_denied_list_body[i]
                                stuff_tbody += '</td>';
                            
                            }
                        
                            stuff_tbody+= '</tbody>';
                            stuff_table += stuff_thead + stuff_tbody + '</table>';
                            if(cache_can==0){
                                cache_can=1;
                                cache_data_student = thead;
                                cache_data_stuff=stuff_thead;
                                cache_data = '<h2 style="margin-top:50px">Student Table</h2><table style=" border: 1px dashed;"id = "denied_list_table">' + cache_data_student  + '</table>' +'<h2 style="margin-top:50px;">Stuff Table</h2><table style ="border: 1px dashed;" id = "denied_list_table" >' + cache_data_stuff + '</table>';
                            }
                         
                             $("#denied_list").html(table+stuff_table);
                    }
                
                </script>
            </div>
        </div>
        <?php
    } 
}
  
?>
