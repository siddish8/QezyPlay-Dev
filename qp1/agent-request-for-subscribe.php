<?php

include("header-agent.php");
function sendPost($data, $url)
{
    
    $ch = curl_init();
    // you should put here url of your getinfo.php script
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    curl_close($ch);
    
    return $result;
}

$msg  = "";
$msgR = "";

if (isset($_REQUEST['delCRs'])) {
    
    $id     = $_REQUEST['CRids'];
    //echo "del CR:".$id;
    //exit;
    $stmt11 = $dbcon->prepare("DELETE FROM agent_credit_requests WHERE id = " . $id . "", array(
        PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL
    ));
    $stmt11->execute();
    
    
    $msg = "<span style='color:green'>Subscribe Request deleted Successfully</span>";
    
    $userCR = 1;
    //echo '<script>window.location.href="http://ideabytestraining.com/newqezyplay/qp/user_forms.php";</script>';
    
}
if (isset($_REQUEST['payCRs'])) {
    
    $id       = $_REQUEST['pCRids'];
    $email_CR = $_REQUEST['CRemails'];
    //echo "id:".$id."   email:".$email_CR;
    
    $userPR   = 1;
    $CR_to_PR = 1;
    
}
?>
<?php
    echo '
<div class="msg" align="center" style="display:block"><h4>' . $msg . '</h4></div>     
    
    <section class="section" style="margin:150px auto">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-block">
                                        <div class="card-title-block">
                                            <h3 class="title">
                            Request for Subscribe List 
                        </h3> </div>
                                        <section class="example">        <!-- <div class="table-responsive"    style="max-height: 400px; overflow-y: scroll;"> -->

        <form id="frm2" method="post" action="agent-remittance-pending.php">
         <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>    <!-- <th>Read Status</th>                 -->
                    <th>Name</th>
                    <th>E-mail</th>
                    <th>Phone</th>
                    <th>Requested DateTime</th>
                    <!-- th>Validation/User-subscription status</th -->
                    <th style="text-align:center">Action<i class="fa fa-cog"></i></th>
                    
                </tr>
            </thead>
            <tbody class="ui-sortable">';
    
    $stmt2 = $dbcon->prepare("SELECT * FROM agent_credit_requests where agent_id=" . $agent_id . "", array(
        PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL
    ));
    $stmt2->execute();
    $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($result2) == 0) {
        echo "<tr style='text-align:center'><td colspan='6'>No Subscribe Requests to Show</td></tr>";
    }
    
    foreach ($result2 as $row2) {
        
        
        $id  = $row2['id'];
        $aid = $row2['agent_id'];
        
        if ($aid != $agent_id)
            continue;
        $name = $row2['user_name'];
        
        $email  = $row2['user_email'];
        $phone  = $row2['phone'];
        $status = $row2['status'];
        
        $time        = $row2['requested_time'];
        $user_status = "";
        
        $go_url = SITE_URL . "/qp/uservalidation_check.php";
        $data1  = sendPost(array(
            'action' => 'searchuser',
            'emailUN' => $email
        ), $go_url);
        //echo $data1;
        $usr    = json_decode($data1);
        
        $data2 = sendPost(array(
            'action' => 'useractive',
            'user_id' => $usr->{'ID'}
        ), $go_url);
        if ($data2 !== "0") {
            $user_status .= "User Subscribed \n" . $data2 . "\n";
            echo "<style>#payCR-" . $id . "{pointer-events:none;opacity:0.5}</style>";
        }
        $data3 = sendPost(array(
            'action' => 'userpending',
            'user_id' => $usr->{'ID'}
        ), $go_url);
        if ($data3 !== "0") {
            if ($data3 == $aid)
                $user_status .= "User in your pending remittance list";
            else
                $user_status .= "User in other Agent's list";
            echo "<style>#payCR-" . $id . "{pointer-events:none;opacity:0.5}</style>";
        }
        
        if ($data2 == "0" and $data3 == "0") {
            $user_status .= "Available";
        }
        
        
        if ($status == "read") {
            //echo "<style>#CR-".$id."{background-color:green !important;color:black !important;}</style>";
            echo "<style>#readCR-" . $id . "{  pointer-events: none;  cursor: default;background-color: #CCCCCC;border:#CCCCCC;}#gr_img-" . $id . "{display:block !important}</style>";
        } else {
            //echo "<style>#F-".$id."{background-color:yellow !important;color:red !important;}</style>";
        }
        
        
        echo '<tr id="CR-' . $id . '" style="" class="ui-sortable-handle">            
                <!-- <td style="width: 50px;" ><img id="gr_img-' . $id . '" src="' . SITE_URL . '/qp/green_tick.png" style="display:none;width:30px;"/></td> -->                
                <td style="width: 192px;" class="level_name">' . $name . '</td>
                <td style="width: 192px;">' . $email . '</td>
                <td style="width: 184px;">' . $phone . '</td>
                <td style="width: 184px;">' . $time . '</td>
                <!-- td style="width: 184px;">' . $user_status . '</td -->
                <td style="width: 100px;text-align:center">                
<a href="#" style="float:;color:#2a85e8 !important" id="removeCR" name="removeCR" value="" onclick="return confirmDelCR(true,' . $id . ')" ><em title="Remove" class="fa fa-trash-o"></em></a> <span>  |  </span>
<a href="#" style="float:;color:#2a85e8 !important" id="payCR-' . $id . '" name="payCR" value="" onclick="return confirmPayCR(true,\'' . $email . '\',' . $id . ')" ><em title="Subscribe" class="fa fa-shopping-cart"></em><a/>

 </td>
                
                </tr>';
    }
    
    echo '</tbody>
        </table></div></form><br><br>
 </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
</div>';
    
    if ($userCR == 1) {
        echo '<script>
        document.getElementById("SL").setAttribute("class","tablinks");
        document.getElementById("PR").setAttribute("class","tablinks");
        document.getElementById("CR").setAttribute("class","tablinks active");
        document.getElementById("CreditRequest").setAttribute("class","tabcontent mx show");
        document.getElementById("PendingRemittance").setAttribute("class","tabcontent mx hide");
        document.getElementById("SubscriberList").setAttribute("class","tabcontent mx hide");
</script>';
        
        
    }
?>
<script>
function confirmDelCR(delCR,CRid)
{

 swal({
         title:' ', 
  text: 'Do you really want to remove this Subscribe Request?',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Delete",   cancelButtonText: "Cancel",  closeOnCancel: true
},function(){ 

var input1 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "CRids").val(CRid);
var input2 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "delCRs").val(delCR);
jQuery('#frm2').append(jQuery(input1));
jQuery('#frm2').append(jQuery(input2));
 jQuery("#frm2").submit();

}); 
   
}

function confirmPayCR(payCR,CRemail,CRid)
{

 swal({
         title:' ', 
  text: 'Do you want to process for Payment?',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Proceed",   cancelButtonText: "Cancel",  closeOnCancel: true
},function(){ 

var input1 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "pCRids").val(CRid);
var input2 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "payCRs").val(payCR);
var input3 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "CRemails").val(CRemail);
jQuery('#frm2').append(jQuery(input1));
jQuery('#frm2').append(jQuery(input2));
jQuery('#frm2').append(jQuery(input3));
 jQuery("#frm2").submit();

}); 
   
}


setTimeout(function(){
jQuery(".msg").slideUp();
},3000);


</script>
</article>
	<?php	

	include("footer-agent.php");
	

?>
