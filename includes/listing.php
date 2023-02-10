
<?php

global $wpdb;


$result = $wpdb->get_results ("SELECT * FROM bsend_transaction ORDER BY id DESC ");

function fantimes($times){
    $date=date_create($times);
    return date_format($date,"H:i");
   }

   function fandates($daten){
    $date=date_create($daten);
    return date_format($date,"d M Y");
   }
   function fandatestime($daten){
    $date=date_create($daten);
    return date_format($date,"d M Y , H:i");
   }


  

?>
<div class="wrap" style=" padding: 50px 20px">


    <div class="bsend_main_panel">
      <div class="row"> 
        <div class="d-flex flex-row  align-items-center  justify-content-between">
          <h3> Transactions</h3>
        </div>
     </div>
      <hr>
   </div>

<div class="bsend_body_wrapper">
  <div class="table-responsive pt-3 Ib-list-day-title ">
    <table class="table table-bordered table-hover" id="dftsgts-buttons " 
        data-search="true"
        data-pagination="true"
        data-show-columns="true"
        data-show-pagination-switch="true"
        data-show-toggle="true" 
        data-resizable="true" 
        data-toggle="table"
        >
      <thead>
      <tr>
            <th data-field="state" data-checkbox="true"></th>
            <th data-field="numero">#</th>
            <th data-field="date" ><?php echo 'Date/Time'  ?></th>
            <th data-field="type" ><?php echo'Type' ?></th>
            <th data-field="somme" ><?php echo'Somme'  ?></th>          
            <th data-field="status" ><?php echo'status'  ?></th>
            <th data-field="commande" ><?php echo'Commande'  ?></th>
            <th data-field="source" ><?php echo'Source'  ?></th>
            <th data-field="client" ><?php echo'Client'  ?></th>
            <th data-field="description" ><?php echo'Description'  ?></th>
        </tr>
      </thead>
      <tbody>
      <?php
      $i=1;
        foreach($result as $ligne){
            
            $ID = intval($ligne->id);  
            $id_order = intval($ligne->id_order);              
            $name = $ligne->first_name;
            $email = $ligne->email;
            $phone = $ligne->phone;
            $amount = $ligne->amount;
            $currency = strval($ligne->currency);
            $country = strval($ligne->country);
            $type = strval($ligne->cmd);
            $source = $ligne->source;
            $description = $ligne->b_description;
            $date_initiate = fandatestime($ligne->created_on) ;
            $status = $ligne->b_status;
            $date_on = date('Y-m-d');
    
    ?>
    
    
        <tr>
           <td></td>
            <td><?php echo $i?></td>    
            <td>
              <div class="ib_date_create"> 
                <?php echo $date_initiate?>
              </div>
            </td>
            <td>
              <div class=""> 
                <?php echo $type?>
              </div>
            </td>
            <td><?php echo $amount ?><?php echo ' '.$currency?>  </td>
            <td>
              <div class="appointment-status-icon ml-3" >     
                     <span ><?php echo $status ;?></span>
              </div>    
            </td>
            <td class="text-danger"> 
              <span class="fw-bold"> <?php echo 'dd'?>
              </span>
            </td>
           
            <td>
                <div class="ib_date_details">    
                    <span class="ib_date_name">  <?php echo $source ;?> </span>
                </div>
            </td>
            <td>
              <div class="ib_user_details">
                          <span><?php echo $name ;?> </span></br>
                          <span> <?php echo $email;?> </span></br>
                          <span><?php echo  $phone;?></span>
                  </div>               
            </td>
            
            <td>  
              <div class="ib_date_details">    
                  <span class="ib_date_name">  <?php echo $description?> </span>
              </div>
            </td>
        
        </tr>
        
        
    <?php ++$i; }?>
    
      </tbody>
    </table>
    
  </div>
</div>
   

</div>

