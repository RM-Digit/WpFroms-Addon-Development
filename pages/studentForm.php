
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<?php
class StudneTable 
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
                        <th scope="col" id="School" class="manage-column column-School">School</th>
                        <th scope="col" id="School" class="manage-column column-School">Which session is your child in?</th>
                        <th scope="col" id="School" class="manage-column column-School">Do you take the bus?</th>
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

     <tbody id="the-list-frontend" data-wp-lists="list:contact_form">
     
                 <?php 
                     global $wpdb;
                     $cfdb         = apply_filters( 'WPFormsDB_database', $wpdb );
                     $search       = empty( $_REQUEST['s'] ) ? false :  esc_sql( $_POST['s'] );
                     $table_name   = $cfdb->prefix.'wpforms_db';
                     $results =  $cfdb->get_results( "SELECT form_value FROM $table_name WHERE form_post_id = 239");
                     
                      foreach ($results as $key_1 => $values) {
                        
                        foreach ($values as $key_2 => $value) {
                          
                            $val =json_encode($value);
                           ?>
                           <script>
                            report_list = <?php echo $val; ?>;
                             if(!report_list.includes('"a:10"')) {
                               date = report_list.split('"Date"')[1].split('"Name"')[0].split('"')[1];
                               name = report_list.split('"Name"')[1].split('"Email Address"')[0].split('"')[1];
                               email = report_list.split('"Email Address"')[1].split('"School"')[0].split('"')[1];
                               school = report_list.split('"School"')[1].split('"Which session is your child in?"')[0].split('"')[1];
                               Which = report_list.split('"Which session is your child in?"')[1].split('"Do you take the bus?"')[0].split('"')[1];
                               bus = report_list.split('"Do you take the bus?"')[1].split('"Bus Number"')[0].split('"')[1];
                               renderHtml += "<tr>"+"<td>"+no+"</td>"+"<td>"+date+"</td>" +"<td>"+name+"</td>"+"<td>"+email+"</td>"+"<td>"+school+"</td>"+"<td>"+Which+"</td>"+"<td>"+bus+"</td>"+ "</tr>";
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
             <th scope="col" id="School" class="manage-column column-School">School</th>
             <th scope="col" id="School" class="manage-column column-School">Which session is your child in?</th>
             <th scope="col" id="School" class="manage-column column-School">Do you take the bus?</th>
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
    
        <h2><?php _e( 'WPForms Studnet List', 'contact-form-WPFormsDB' ); ?></h2>
        <?php $this->table_display(); ?>
     <?php
 }
}
?>

