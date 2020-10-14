<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<?php
class StuffTable 
{
    public function __construct()
    {
        
        $this->list_table_page_display();
    }
    public function table_display(){
        ?>
 <table class="wp-list-table wp-list-table widefat fixed striped contact_forms">
     <thead>
         <tr>
              <th scope="col" id="Date" class="manage-column column-Date column-primary">No</th>
             <th scope="col" id="Date" class="manage-column column-Date column-primary">Date</th>
             <th scope="col" id="Name" class="manage-column column-Name">Name</th>
             <th scope="col" id="Email Address" class="manage-column column-Email Address">Email Address</th>
             <th scope="col" id="School" class="manage-column column-School">Which school will you be entering today? Please check all that apply:</th>
             <th scope="col" id="School" class="manage-column column-School">In the past 24 hours have you had...</th>
             <th scope="col" id="School" class="manage-column column-School">In the past 14 days, have you had close contact* with a person known to be infected with COVID-19?</th>
         </tr>
     </thead>
 <script>
     var report_list,no = 0;
     var renderHtml="",date,email,school;
 </script>
 <style>
      table {
         font-family: arial, sans-serif;
         border-collapse: collapse;
         width: 100%;
         }
 
 #the-list-frontend td, th{
   border: 1px solid #dddddd;
   text-align: left;
   padding: 8px;
 }
 
 tr:nth-child(even) {
   background-color: #dddddd;
 }
 </style>
 </style>
     <tbody id="the-list-frontend" data-wp-lists="list:contact_form">
     
                 <?php 
                     global $wpdb;
                     $cfdb         = apply_filters( 'WPFormsDB_database', $wpdb );
                     $search       = empty( $_REQUEST['s'] ) ? false :  esc_sql( $_POST['s'] );
                     $table_name   = $cfdb->prefix.'wpforms_db';
                     $results =  $cfdb->get_results( "SELECT form_value FROM $table_name WHERE form_post_id = 329");
                     
                      foreach ($results as $key_1 => $values) {
                        
                        foreach ($values as $key_2 => $value) {
                          
                            $val =json_encode($value);
                           ?>
                           <script>
                            report_list = <?php echo $val; ?>;
                             if(!report_list.includes('"a:10"')) {
                               date = report_list.split('"Date"')[1].split('"Name"')[0].split('"')[1];
                               name = report_list.split('"Name"')[1].split('"Email Address"')[0].split('"')[1];
                               email = report_list.split('"Email Address"')[1].split('"Please choose one of the following:"')[0].split('"')[1];
                               
                               which = report_list.split('"Please choose one of the following:"')[1].split('"Which school will you be entering today? Please check all that apply:"')[0].split('"')[1];
                               school = report_list.split('"Which school will you be entering today? Please check all that apply:"')[1].split('"In the past 24 hours have you had..."')[0].split('"')[1];
                               Corvid = report_list.split('"In the past 14 days, have you had close contact* with a person known to be infected with COVID-19?"')[1].split('"')[1];
                               
                               renderHtml += "<tr>"+"<td>"+no+"</td>"+"<td>"+date+"</td>" +"<td>"+name+"</td>"+"<td>"+email+"</td>"+"<td>"+which+"</td>"+"<td>"+school+"</td>"+"<td>"+Corvid+"</td>"+ "</tr>";
                                 no++;
                             }   
                           </script>
                           <?php
 
                        }
                      }
                     
                 ?> 
                 <script>
                     $('#the-list-frontend').html(renderHtml);
                 </script>
     </tbody>
 
     <tfoot>
         <tr>
             
         <th scope="col" id="Date" class="manage-column column-Date column-primary">No</th>
             <th scope="col" id="Date" class="manage-column column-Date column-primary">Date</th>
             <th scope="col" id="Name" class="manage-column column-Name">Name</th>
             <th scope="col" id="Email Address" class="manage-column column-Email Address">Email Address</th>
             <th scope="col" id="School" class="manage-column column-School">Which school will you be entering today? Please check all that apply:</th>
             <th scope="col" id="School" class="manage-column column-School">In the past 24 hours have you had...</th>
             <th scope="col" id="School" class="manage-column column-School">In the past 14 days, have you had close contact* with a person known to be infected with COVID-19?</th>
       
         </tr>
     </tfoot>
 
 </table>
 <?php
     
 }
 public function list_table_page_display(){
     if ( ! function_exists('wpforms') ) {
         wp_die( 'Please activate <a href="https://wordpress.org/plugins/wpforms-lite/" target="_blank">WPForms</a> plugin.' );
      }
      ?>
 <div class="wrap">
     <div id="icon-users" class="icon32"></div>
 
     <h2><?php _e( 'WPForms Stuff List', 'contact-form-WPFormsDB' ); ?></h2>
     <?php $this->table_display(); ?>
     <?php
 }
}
