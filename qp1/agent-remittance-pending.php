<?php

include("header-agent.php");

$msg  = "";
$msgR = "";

if (isset($_POST['add_to_pay'])) {
    
    $agentId        = $_POST['agent_id'];
    $sub_id         = $_POST['subscriber'];
    $boq_id         = $_POST['bouquet'];
    //$plan_id=$_POST['plan'];
    $plan_id        = $_POST['item_number'];
    $added_datetime = new DateTime("now");
    $added_datetime = $added_datetime->format("Y-m-d H:i:s");
    
    $uname = get_var("select user_login from wp_users where ID=" . $sub_id . " ");
    
    $sql01  = "SELECT * FROM agent_remittence_pending where agent_id=" . $agentId . " and subscriber_id=" . $sub_id . " and bouquet_id=" . $boq_id . " and plan_id=" . $plan_id . " and status='penidng' ";
    $stmt01 = $dbcon->prepare($sql01, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL
    ));
    $stmt01->execute();
    $result01 = $stmt01->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($result01) == 0) {
        $sql1 = "INSERT INTO agent_remittence_pending(agent_id,subscriber_id,bouquet_id,plan_id,added_datetime) VALUES(?,?,?,?,?)";
        try {
            $stmt1 = $dbcon->prepare($sql1, array(
                PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL
            ));
            $stmt1->execute(array(
                $agentId,
                $sub_id,
                $boq_id,
                $plan_id,
                $added_datetime
            ));
            $stmt1 = null;
        }
        catch (PDOException $e) {
            print $e->getMessage();
        }
        
        $sq = "DELETE FROM agent_credit_requests where agent_id=" . $agent_id . " and user_name='" . $uname . "' ";
        
        $st = $dbcon->prepare($sq, array(
            PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL
        ));
        $st->execute();
    } else {
        //echo "<script>swal('Already in Payment Pending List ')</script>";
    }
    $added_hide = 1;
    $userPR     = 1;
}

if (isset($_POST['PRids'])) {
    // print_r($_POST);
    $id     = $_POST['PRids'];
    $stmt13 = $dbcon->prepare("DELETE FROM agent_remittence_pending WHERE id = " . $id . "", array(
        PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL
    ));
    $stmt13->execute();
    
    
    $msgR = "<span style='color:green'>Pending Remittance deleted Successfully</span>";
    
    $userPR = 1;
   
}
if (isset($_REQUEST['payCRs'])) {
    
    $id       = $_REQUEST['pCRids'];
    $email_CR = $_REQUEST['CRemails'];
    //echo "id:".$id."   email:".$email_CR;
    
    $userPR   = 1;
    $CR_to_PR = 1;
    
}

?>

<article class="content items-list-page">
<div class="msg" align="center" style="display:block"><h4><?php
    echo $msgR;
?></h4></div>     
    <br />    <div style="float:right;">
        <a class="btn btn-pill-left btn-primary" style="display:inline-block;" href="javascript:void(0)" onclick="return other_user_btn()" >Validate Another User</a>
<span style="display:inline-block;">&nbsp; &nbsp;</span>
        <a class="btn btn-pill-right btn-primary" style="display:inline-block;" style="display:inline-block;" href="<?php
    echo SITE_URL;
?>/register" target="_blank">Create New Registration</a><span style="display:inline-block;">&nbsp; &nbsp;</span></div>
<br>
<br>
<br>
<section class="section">
        <div id="emailchkdiv">
            
                <!-- <div class="xoouserultra-main"> -->
                    <div id="emailchecking" style="display:none">
                         
			<div class="row sameheight-container"></div>
				 <div class="col-md-3">
                             
                            </div>
                            <div class="col-md-6" style="border:3px solid;margin: 10px auto;
    padding: 12px;">
			                    
                            <center><span>Validate subscriber:</span></center>
                       
                        <div class="form-group"> <label class="control-label">User Name or E-mail</label> <input onclick="clearError()" required onkeypress="return event.charCode !=32" type="text" name='user-emailUN' id='user-emailUN' class="form-control underlined"> </div>                
                                              
                            <div class="xoouserultra-field-value" style="float:unset">
                                <input type="button" onclick='return checkMail();' name='validate' value="Validate" class="" name="xoouserultra-login" id="validate">
                            </div>

			<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show" id='searchErrDiv' style="display:none";>
                            <label for="user_login" class="xoouserultra-field-type" >&nbsp;</label>
                            <div class="xoouserultra-field-value" style="float: unset;
    width: 100%;">
                                <div id='searchErr' style="color:red; "></div>
                            </div>
                        </div>           
                        
                        <div  id="subscribed-div" style='display:none;color:red;text-align: center;'>
				<p class="" id="subscribed"></p></div>    
                    <div id="sub-pending-div" style='display:none;color:red;text-align: center;
    '><p id="sub-pending"></p></div>
                    <div id='usernotfoundiv' style='color:red;display:none;text-align: center;
    '></div>
                        </div>
<div class="col-md-3">
                             
                            </div>
                        
                    </div>

                    
<div id="selection" style="display:none"> 
			
				 
                        <form role="form" class='paypal' method='post' id='paypal_form' target='_self' style='display:none;border 1px solid grey;'>			<div class="row sameheight-container">
			</div>
			<div class="col-md-3">
                             
                            </div>
                            <div class="col-md-6" style="border:3px solid;margin: 10px auto;
    padding: 12px;">
                        <div id='userdiv' style='color:black;'></div><br>
                                
                        <input type='hidden' name='subscriber' id='subscriber' />
    			<div class="form-group"> <label class="control-label">Bouquet</label> <select class="form-control underlined" style="color:black;" name='bouquet' required><option value=''>Select Bouquet</option><option value='1'>Bangla Bouquet</option></select> </div>			<div class="form-group"> <label class="control-label">Plan</label> <select class="form-control underlined" style="color:black;" name='item_number' required><option value=''> --Select Plan-- </option>                                
                                <?php
    $sql2 = "SELECT id, name, billing_amount FROM wp_pmpro_membership_levels where allow_signups=1";
    try {
        $stmt2 = $dbcon->prepare($sql2, array(
            PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL
        ));
        $stmt2->execute();
        $result2 = $stmt2->fetchall(PDO::FETCH_ASSOC);
        $stmt2   = null;
        foreach ($result2 as $plan) {
            $planid   = $plan["id"];
            $planname = $plan["name"];
            if ($plan["billing_amount"] > 0) {
                echo "<option value='" . $planid . "'>" . $planname . "</option>";
            }
        }
    }
    catch (PDOException $e) {
        print $e->getMessage();
    }
?>
                               </select> </div>
                          <div class="form-group"> <label class="control-label"></label><input class="form-control underlined" type='submit' name='add_to_pay' value='Add to Pay List' id='add_to_pay' /></div>
                           
                        <input type='hidden' name='payer_email' value=''  />    
                        <input type='hidden' name='agent_id' value="<?php  echo $agent_id;?>" / >
		</div>
			<div class="col-md-3">
                             
                            </div>
                    </form>
	</div>
                <!-- </div> -->

            </div>
       

        <!--<div class="xoouserultra-wrap xoouserultra-login" style="float:left;width:50%;padding:0px 10px">
            <div class="xoouserultra-inner xoouserultra-login-wrapper">
                <div class="xoouserultra-main"> -->
</section>
<section class="section">
			<div id="PayReady" style="max-width: 100%;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-block">
                                        <div class="card-title-block">
                                            <h3 class="title">
							Payment Pending List
						</h3> </div>
                                        <section class="example">		<!-- <div class="table-responsive"	style="max-height: 400px; overflow-y: scroll;"> -->

		<form id="frm1" method="post">
		 <div class="table-responsive">
                                                <table id="pending_list" class="table table-striped table-bordered table-hover">
<?php
    echo '
        
            <thead>
                <tr>                    
                    <th>UserName(E-mail)</th>
                    <th>Bouquet</th>
                    <th>PlanName</th>
                    <th>Amount Paid(USD)</th>
                    <th>Paid Date</th>
                    <th>Status</th>
                    <th>Action<i class="fa fa-cog"></i></th>
                    
                </tr>
            </thead>
            <tbody class="ui-sortable">';
    
    $sql99  = "SELECT a.id,a.subscriber_id,c.user_login,c.user_email,a.bouquet_id,a.plan_id,b.name,b.billing_amount,a.added_datetime,a.status FROM agent_remittence_pending a inner join wp_pmpro_membership_levels b on b.id=a.plan_id inner join wp_users c on c.ID=a.subscriber_id where a.agent_id=" . $agent_id . " and status='pending'";
    $stmt99 = $dbcon->prepare($sql99, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL
    ));
    $stmt99->execute();
    $result99 = $stmt99->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($result99) == 0) {
        echo "<style>.no_pending{display:none}</style>";
        echo "<tr style='text-align:center'><td colspan='7'>No Pending Remittances to Show</td></tr>";
    }
    
    foreach ($result99 as $row99) {
        
        
        $id = $row99['id'];
        //$aid=$row99['agent_id'];
        
        //if($aid!=$agent_id)
        //    continue;
        $userid   = $row99['subscriber_id'];
        $username = $row99['user_login'];
        
        $email = $row99['user_email'];
        $boq   = $row99['bouquet_id'];
        if ($boq == 1) {
            $boq_name = "Bangla Bouquet";
        }
        
        $plan_name   = $row99['name'];
        $plan_amount = $row99['billing_amount'];
        $status      = $row99['status'];
        
        $time = $row99['added_datetime'];
        
        //if($status=="read")
        //{
        //echo "<style>#CR-".$id."{background-color:green !important;color:black !important;}</style>";
        //echo "<style>#readCR-".$id."{display:none !important}</style>";
        //}
        //else
        //{
        //echo "<style>#F-".$id."{background-color:yellow !important;color:red !important;}</style>";
        //}
        
        
        echo '<tr id="PR-' . $id . '" style="" class="ui-sortable-handle">                            
                <td style="width: 192px;" class="level_name">' . $username . '(' . $email . ')</td>
                <td style="width: 192px;">' . $boq_name . '</td>
                <td style="width: 184px;">' . $plan_name . '</td>
                <td style="width: 184px;">' . $plan_amount . '</td>
                <td style="width: 184px;">' . $time . '</td>
                <td style="width: 184px;">' . $status . '</td>
                <td style="width: 100px;">
                <input type="hidden" id="PRid" name="PRid" value="' . $id . '">
                <input type="hidden" id="delPR" name="delPR" value="false">
                <a style="padding: 1px 4px !important;
    background: none !important;
    border: 0px transparent !important;
    box-shadow: none !important;
    color: #2a85e8 !important;
    font-size: 15px;
    font-weight: bold;
    margin: 0px 5px !important;" href="#" id="removePR" name="removePR" onclick="return confirmDelPR(' . $id . ')"><span title="Remove" class="fa fa-trash-o"></span></a>
                
                
                        <input type="hidden" name="cmd" value="_xclick"/>
                        <input type="hidden" name="no_note" value="1"/>
                        <input type="hidden" name="lc" value="IND"/>
                        <input type="hidden" name="currency_code" value="USD"/>
                        <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest"/>
                        <input type="hidden" name="first_name" value=""/>
                        <input type="hidden" name="last_name" value=""/>
                        <input type="hidden" name="item_name1" value="Paying: ' . $username . '"/>
                        <!-- <input type="hidden" name="amount1" id="amount1" value="' . $plan_amount . '"/> -->
                        <input type="hidden" name="agent_id" value="' . $agent_id . '">
                        <input type="hidden" name="user_id" value="' . $userid . '">
                        <input type="hidden" name="bulk1" value="false">                        
                        <button style="padding: 1px 4px !important;
    background: none !important;
    border: 0px transparent !important;
    box-shadow: none !important;
    color: #2a85e8 !important;
    font-size: 15px;
    font-weight: bold;
    margin: 0px 5px !important;" type="submit" name="payPR" onclick="this.form.action=\'Paypal/payments.php?Rid=' . $id . '&amount=' . $plan_amount . '&item_name=Paying: ' . $username . '&bulk=false\'"><em title="Subscribe" class="fa fa-dollar"></em><em title="Subscribe" class="fa fa-money"></em><em title="Subscribe" class="fa fa-dollar"></em></button>
                        <input type="hidden" name="payer_email" value="">                            
              
</td>
                
                </tr>';
    }
    
    echo '</tbody>
            ';
    
    
?>
   </table></div></form>
 </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <!--</div> -->
        <!-- </div></div>-->
        <!-- <div> -->
        <form class='paypal' action='Paypal/payments.php' method='post' id='paypal_formAll' target='_self' style=''>
                        <input type='hidden' name='cmd' value='_xclick'/>
                        <input type='hidden' name='no_note' value='1'/>
                        <input type='hidden' name='lc' value='IND'/>
                        <input type='hidden' name='currency_code' value='USD'/>
                        <input type='hidden' name='bn' value='PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest'/>
                        <input type='hidden' name='first_name' value=''/>
                        <input type='hidden' name='last_name' value=''/>
                        <input type='hidden' name='item_name' value='Bulk Pay'/>
                        <input type='hidden' name='total_amount' id="total_amount" value=''/>
                        
                        
                            
                        <input class="no_pending btn btn-primary btn-lg" type='submit' name='submit' value='Pay All' id='Paypal' style="float:right;margin:15px" />
                        <label class="no_pending" style="float: right;
    margin: 25px;
    font-size: 18px;">Total Amount :  $ <span style="color:black" id="total_amount1"></span>  USD</label>                        
                                                                            
                        <div class="xoouserultra-clear"></div>
                        <input type='hidden' name='payer_email' value=''  />    
                        <input type='hidden' name='agent_id' value="<?php
    echo $agent_id;
?>" / >
                        
        <br>
        </form>
        <!-- </div> -->
        </div>
	</section> 
<script>
function other_user_btn()
{
	document.getElementById('user-emailUN').value="";
	jQuery("#sub-pending").html("");
	jQuery("#subscribed").html("");
	jQuery("#usernotfoundiv").html("");
	jQuery("#selection").hide();
	var x=document.getElementById('emailchecking');
	if(x.style.display=="none")
	x.style.display='block';
	else
	x.style.display='none';

}
setTimeout(function(){
jQuery(".msg").slideUp();
},3000);


a1=document.getElementById("paypal_form").style.display;

divWide(a1);
function divWide(a1){
if(a1=="none")
{

document.getElementById("emailchkdiv").setAttribute("style","");
document.getElementById("emailchecking").setAttribute("style","display:none;");

}
else
{

document.getElementById("emailchkdiv").setAttribute("style","");
document.getElementById("emailchecking").setAttribute("style","display:none;");

}
}
function confirmDelPR(PRid)
{

swal({
         title:' ', 
  text: 'Do you really want to remove this Pending Remittance?',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Delete",   cancelButtonText: "Cancel",  closeOnCancel: true
},function(){ 

var input = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "PRids").val(PRid);
jQuery('#frm1').append(jQuery(input));
 jQuery("#frm1").submit();

}); 
   
}
var sum=colSum("pending_list",3);

jQuery("#total_amount1").html(sum);
jQuery("#total_amount").val(sum);
//alert(s);
function colSum(tableId, colNumber)
{

  // find the table with id attribute tableId
  // return the total of the numerical elements in column colNumber
  // skip the top row (headers) and bottom row (where the total will go)
    var debugScript=false;    
  var result = 0;
        
  try
  {
    var tableElem = window.document.getElementById(tableId);     
       
    var tableBody = tableElem.getElementsByTagName("tbody").item(0);
    var i;
    var howManyRows = tableBody.rows.length;
    //alert(howManyRows);    
    for (i=0; i<howManyRows; i++) // skip first and last row (hence i=1, and howManyRows-1)
    {
       var thisTrElem = tableBody.rows[i];
    //alert(thisTrElem);
       var thisTdElem = thisTrElem.cells[colNumber];            
       var thisTextNode = thisTdElem.childNodes.item(0);
       if (debugScript)
       {
          window.alert("text is " + thisTextNode.data);
       } // end if

       // try to convert text to numeric
       var thisNumber = parseFloat(thisTextNode.data);
       // if you didn't get back the value NaN (i.e. not a number), add into result
       if (!isNaN(thisNumber))
         result += thisNumber;
     } // end for
         
  } // end try
  catch (ex)
  {
     //window.alert("Exception in function computeTableColumnTotal()\n" + ex);
     result = 0;
  }
  finally
  {
     return Math.round(result * 100) / 100;
  }
    
}

jQuery( "#user-email, #user-username,#user-emailUN" ).click(function() {
  
  jQuery("#sub-pending").html("");
jQuery("#subscribed").html("");
jQuery("#usernotfoundiv").html("");
jQuery("#user-emailUN").val("");

});

jQuery( "#user-email, #user-username,#user-emailUN" ).focus(function() {
  
  jQuery("#sub-pending").html("");
jQuery("#subscribed").html("");
jQuery("#usernotfoundiv").html("");
jQuery("#user-emailUN").val("");

});

function clearError(){
        document.getElementById('searchErr').innerHTML = '';
        document.getElementById('searchErrDiv').style.display = 'none';
    }

    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    function checkMail(){    
        
        //var email = document.getElementById('user-email').value;
        var emailUN = document.getElementById('user-emailUN').value;
        //var firstname = document.getElementById('user-firstname').value;
        //var lastname = document.getElementById('user-lastname').value;
        //var username = document.getElementById('user-username').value;
        //var phone = document.getElementById('user-phone').value;

        //if(email == "" && firstname == "" && lastname == "" && username == "" && phone == ""){
        if(emailUN == ""){
            document.getElementById('searchErrDiv').style.display = 'block';
            document.getElementById('searchErr').innerHTML = 'Please enter any one of Username or Email';
            return false;
        }else{
        
            /*if(email != ""){
                var emailValidateCheck = validateEmail(email);

                if(!emailValidateCheck){

                    document.getElementById('searchErrDiv').style.display = 'block';
                    document.getElementById('searchErr').innerHTML='Please enter valid Email-id';
                    return false;
                }    
            }*/

            //var values = {'action':'searchuser', 'email':email, 'firstname':firstname, 'lastname':lastname, 'username':username, 'phone':phone};
            var values = {'action':'searchuser', 'emailUN':emailUN};
            
            jQuery.ajax({url: 'uservalidation_check.php',
            type: 'post',
            data: values,
            success: function(response){  
                    
                    if(response == ""){    
                        document.getElementById('usernotfoundiv').style.display = 'block';                        
                        document.getElementById('usernotfoundiv').innerHTML = '<b>No subscriber found</b>';
                    }else{
                        
                        document.getElementById('usernotfoundiv').innerHTML = '';
                        document.getElementById('usernotfoundiv').style.display = 'none';
                                                
                        var obj = jQuery.parseJSON(response);    
                        if(obj.ID != ""){

                            document.getElementById('subscriber').value = obj.ID;
                            document.getElementById('userdiv').innerHTML = '<Br><b>Subscriber: </b> ' +obj.user_email+'('+obj.user_login+')';    

                            var values1 = {'action':'useractive', 'user_id':obj.ID};
            
                            jQuery.ajax({url: 'uservalidation_check.php',
                            type: 'post',
                            data: values1,
                            success: function(res){
                            if(res==0) 
                                {
                                

                                var values2 = {'action':'userpending', 'user_id':obj.ID};
            
                                jQuery.ajax({url: 'uservalidation_check.php',
                                type: 'post',
                                data: values2,
                                success: function(re){
                                if(re==0) 
                                {
                                
                                
                                document.getElementById('paypal_form').style.display = 'block';
                                document.getElementById('emailchecking').style.display = 'none';
                                document.getElementById('selection').style.display = 'block';
                                a1=document.getElementById("paypal_form").style.display;
                                //divWide(a1);
                                document.getElementById('subscribed-div').style.display="none";
                                document.getElementById('sub-pending-div').style.display="none";
                                document.getElementById('subscribed').innerHTML ="";
                                document.getElementById('sub-pending').innerHTML ="";
                                document.getElementById('user-emailUN').value="";
                                //document.getElementById('user-email').value="";
                                //document.getElementById('user-username').value="";
                                //document.getElementById('user-phone').value="";
                                }
                                else
                                {
                                document.getElementById('paypal_form').style.display = 'none';
                                document.getElementById('sub-pending-div').style.display = 'block';
                                    if(re==document.getElementById("AID").value)
                                    {
                                    document.getElementById('sub-pending').innerHTML = '<b>This user already in your Payment Pending List.<br /> Remove and Try Again. ';                                    }
                                    else
                                    {
                                    document.getElementById('sub-pending').innerHTML = '<b>This user already in other Agent\'s Payment Pending List.<br /> You can\'t proceed.';                                    }
                                    
                                document.getElementById('subscribed').innerHTML ="";
                                document.getElementById('subscribed-div').style.display = 'none';
                                }
                                }
                            });
                            
                                }
                            else
                                {document.getElementById('paypal_form').style.display = 'none';
                                document.getElementById('subscribed-div').style.display = 'block';
                                document.getElementById('subscribed').innerHTML = '<b>This user already subscribed. You cant proceed with him/her.</b>';        document.getElementById('sub-pending-div').style.display = 'none';    
                                document.getElementById('sub-pending').innerHTML ="";
                                }
                            }
                            });
                        }
                        
                    }
                }
            });                
            
        }
    }

</script>
<?php
if ($CR_to_PR == 1) {
            echo "<script>jQuery('#emailchecking').show();
		jQuery('#user-emailUN').val('" . $email_CR . "');
                jQuery('#validate').click();</script>";
        }
?>

</article>
	<?php	

	include("footer-agent.php");
	
?>
