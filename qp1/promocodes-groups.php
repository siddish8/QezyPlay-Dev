<?php 
	include('header.php');
?>

<article class="content items-list-page">

    <?php

	$msg = ""; $action = "Add";

	if(isset($_REQUEST['delShpc'])){

		$id = $_REQUEST['delShpcid'];

        //$sql="DELETE FROM promocodes_vs_shows WHERE id = ".$id."";

        $sql="SELECT * from promo_codes where coupon_code=(SELECT promocode FROM tradmin_newqezy.promocodes_vs_shows where id = ".$id.")";
        $res=get_all($sql);

        foreach($res as $r){

             $count=$r['usage_count'];
             $pc=$r['coupon_code'];

        }

       

       $sql1="UPDATE promo_codes SET max_count=".$count." where coupon_code='".$pc."'";

		$stmt11 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt11->execute();
		$stmt11=null;

		$msg = "<span id='msg' style='color:green'>Deactivated Successfully</span>";
		
	}


	if(isset($_POST['Add_Submit'])){
		
		$pcode=$promocode=trim($_POST['promocode']);
		$promocode=strtolower(preg_replace('/\s+/', '', $promocode));
		$assign=trim($_POST['assign']);
		$assign=strtolower(preg_replace('/\s+/', '', $assign));
		$date = gmdate("Y-m-d H:i:s");
		
		$stmt21 = $dbcon->prepare('SELECT * FROM promo_codes WHERE LOWER(REPLACE(coupon_code," ","")) = "'.$promocode.'"  ', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt21->execute();
		$result21 = $stmt21->fetchAll(PDO::FETCH_ASSOC);
		$shpcexist1 = count($result21);	
		$stmt21=null;

		$stmt21 = $dbcon->prepare('SELECT * FROM promocodes_vs_shows WHERE LOWER(REPLACE(assigned_to," ","")) = "'.$assign.'"  ', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt21->execute();
		$result21 = $stmt21->fetchAll(PDO::FETCH_ASSOC);
		$shpcexist2 = count($result21);	
		$stmt21=null;

		if((int)$shpcexist1 > 0){

			$msg1="";
			$msg1.= "This promocode already exist <br>";
			$msg = "<span id='msg' style='color:red'>".$msg1."</span>";
			
		}elseif((int)$shpcexist2 > 0){

			$msg1="";
			$msg1.= "This assigned info already exist <br>";
			$msg = "<span id='msg' style='color:red'>".$msg1."</span>";

		}
		
		else{	
						
			
			$stmt210 = $dbcon->prepare('INSERT INTO promo_codes(bouquet_id,plan_id,pay_amount,coupon_code,added_datetime) VALUES("4","4","0.00","'.$pcode.'","'.$date.'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt210->execute();


				
			$stmt22 = $dbcon->prepare('INSERT INTO promocodes_vs_shows(promocode,created_by,assigned_to,created_datetime) VALUES("'.$pcode.'","'.$admin.'","'.$assign.'","'.$date.'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt22->execute();
			$shpc_id = $dbcon->lastInsertId();

			

			$msg = "<span style='color:green'>Promocode created sucessfully</span>";
						
		
		}
	}


	if(isset($_POST['Edit_Submit'])){
		
		$shpcid=trim($_POST['shpcid']);

		$pcode=$promocode=trim($_POST['promocode']);
		$promocode=strtolower(preg_replace('/\s+/', '', $promocode));
		$assign=trim($_POST['assign']);
		$assign=strtolower(preg_replace('/\s+/', '', $assign));
		$date = gmdate("Y-m-d H:i:s");

	//	echo 'SELECT * FROM promocodes_vs_shows WHERE LOWER(REPLACE(assigned_to," ","")) = "'.$assign.'" AND id != "'.$shpcid.'" ';
		$stmt21 = $dbcon->prepare('SELECT * FROM promocodes_vs_shows WHERE LOWER(REPLACE(assigned_to," ","")) = "'.$assign.'" AND id != "'.$shpcid.'" ', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt21->execute();
		$result21 = $stmt21->fetchAll(PDO::FETCH_ASSOC);
		$shpcexist = count($result21);	
		$stmt21=null;	
		
		if((int)$shpcexist > 0){
			
			$msg1="";
			$msg1.= "This assigned info already exist <br>";
			$msg = "<span id='msg' style='color:red'>".$msg1."</span>";
			
		} 
		else{
										
			//update query
		//	echo 'UPDATE promocodes_vs_shows SET assigned_to = "'.$assign.'" WHERE id = '.$shpcid.' ';
			$stmt22 = $dbcon->prepare('UPDATE promocodes_vs_shows SET assigned_to = "'.$assign.'" WHERE id = '.$shpcid.' ', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	 
			$stmt22->execute();

			$stmt22=null;
		
			$msg = '<span style="color:green;">Info updated Successfully</span>';

            $x=1;
									
		
	}

	}

	if(isset($_REQUEST['editShpc'])){

		$id = $_REQUEST['editShpcid'];		

		$stmt1 = $dbcon->prepare("SELECT * FROM promocodes_vs_shows where id='".$id."'", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt1->execute();
		$result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
		foreach($result1 as $row1){
			$shpcid1=$id;
			$promocode1=$row1['promocode'];
			$assign1=$row1['assigned_to'];
			
		}
		
		$action = "Edit";

	}	


    if($x==1){
        $action = "Add";
        $shpcid1 = "";
        $promocode1 = "";
        $assign1 = "";


    }
?>

        <style>
            .xoouserultra-field-value,
            .xoouserultra-field-type {
                width: unset !important;
                padding: 5px;
            }
            
            form[name="f_timezone"] {
                display: none;
            }
            
            #search_shpc>input[type="text"] {
                max-width: 250px;
            }
            
            .link_btn:hover {
                background: #000;
                color: #fff;
                border: solid 1px #000;
            }
            
            .link_btn:visited {
                color: white !important
            }
            
            .link_btn {
                border-radius: 3px;
                line-height: 2.75;
                text-align: center;
                margin: 5px;
                padding: 6.5px 25px;
                outline: none;
                background: #4141a0;
                border: solid 1px #4141a0;
                color: #fff;
                transition: all .2s ease;
                text-decoration: none;
                border-radius: 5px;
            }
        </style>

        <h2 style="text-align:center"></h2>

        <?php		

	//if($_SESSION['adminlevel']<=1){
		if ($action=="Edit"){ 
			echo "<style>#add_shpc_btn{display:none} #shpc-form-section{display:block !important;}</style>";
		}
        

?>
            <section class="section">
                <span style="float:"></span>
                <div class="msg" align="center" style="display:inline-block">
                    <h4>
                        <?php echo $msg;?>
                    </h4>
                </div>
                <div class="row sameheight-container">
                    <div class="col-md-3">
                        <button id="add_shpc_btn" class="btn btn-primary" onclick="return shpc_form()">Create Promocode</button>
                    </div>
                    <div class="col-md-6">
                        <div id="shpc-form-section" class="card card-block sameheight-item" style="display:none;height:auto;/*height: 721px;*/">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <div class="header-block">
                                        <p class="title">
                                            <?php echo $action; ?> Promocode </p>
                                    </div>
                                </div>
                                <div class="xoouserultra-field-value" style="">
                                    <div id='error' style='color:red;'></div>
					</div>
					<div class="card-block ">
                                    <form role="form" method='post' enctype="multipart/form-data">
					<input type="hidden" id="shpcid" name="shpcid" value="<?php echo $id; ?>"/>

                                        <div class="form-group"> <label class="control-label">Promo Code</label> <input <?php if($action=="Edit" ){ echo "disabled";} ?> onclick="clearError()" type="text" value="<?php echo @$promocode1;?>" id="promocode" name="promocode" class="form-control underlined"> </div>
										 <?php 
									if($action=="Edit"){
									?>

										<div>*Contact DB-Admin to change Promocode</div>
									   <?php
								   } 
								?>
                                        <div class="form-group"> <label class="control-label">Assigned To</label> <input onclick="clearError()" type="text" value="<?php echo @$assign1;?>" id="assign" name="assign" class="form-control underlined"> </div>

                                        <div class="xoouserultra-field-value" style="padding-left: 10px;">
                                            <?php 
									if($action=="Edit"){
								?>
                                            <input type="hidden" name="editShpc" value="true" />
                                            <input type="hidden" name="editShpcid" value="<?php echo @$shpcid1;?>" />
                                            <?php 
									} 
								?>
                                            <input class="btn btn-primary" type="submit" onclick="return callsubmit();" name="<?php echo $action;?>_Submit" value="Submit" />
                                            <?php
									if($action=="Edit"){
								?>
                                                <a class="btn btn-primary" href="<?php echo SITE_URL.'/qp1/promocodes-groups.php'?>">Back</a>
                                                <?php
								   } 
								?>
                                        </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">

                        </div>
                    </div>
            </section>
            <?php //}

if($_SESSION['adminlevel']<=1){
?>


          <!--  <section class="section" style="margin:100px auto">
 <div class="col-md-3">
                             
                            </div>
<div id="search_sub_list" align="center" style="margin:15px auto;" class="col-md-6">

        Search using any criteria: <br />
	<div class="form-group"> 
		<input placeholder="Enter PromoCode" id="searchPC" type="text" name="searchPC" class="form-control boxed"><span class="_or"> </span> 
	</div>	
	<div class="form-group"> 
		<input placeholder="Enter Creator" id="searchCr" type="text" name="searchCr" class="form-control boxed"><span class="_or"> </span> 
	</div>	
	<div class="form-group"> 
		<input placeholder="Enter Assigned group" id="searchAs" type="text" name="searchAs" class="form-control boxed"><span class="_or"> </span> 
	</div>	
	<div class="form-group"> 
		<input placeholder="Enter Promocode Status-active/deactivated" id="searchSt" type="text" name="searchSt" class="form-control boxed"><span class="_or"> </span> 
	</div>	
	

	</div>
 <div class="col-md-3">
                             
                            </div>
   </section> -->

<?php

}

?>

            <section class="section">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-block">
                                <div class="card-title-block">
                                    <h3 class="title">
                                        Promocodes-Groups
                                    </h3>
                                </div>
                                <section class="example">
                                    <form id="frm2" method="post">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">

                                                <thead>
                                                 <?php //}

if($_SESSION['adminlevel']<=1){
?>
                                                    <tr>
                                                        <th><input placeholder="Search PromoCode" id="searchPC" type="text" name="searchPC" class="form-control boxed"><span class="_or"> </span></th>
                                                        <th><input placeholder="Search Creator" id="searchCr" type="text" name="searchCr" class="form-control boxed"><span class="_or"> </span></th>
                                                        <th><input placeholder="Search Assigned group" id="searchAs" type="text" name="searchAs" class="form-control boxed"><span class="_or"> </span></th>
                                                        <th><input placeholder="Search Status-active/deactivated" id="searchSt" type="text" name="searchSt" class="form-control boxed"><span class="_or"> </span></th>
                                                        <th colspan='3' style='text-align:center;font-size:large'><i class='fa fa-arrow-left' aria-hidden='true'></i> Search</th>
                                                    </tr>
<?php
}
?>
                                                    <tr>
                                                        <th>PromoCode</th>
                                                        <th>Created by</th>
                                                        <th>Assigned to</th>
                                                        <th>Status</th>
                                                        <th>UsageCount</th>
                                                        <th>UsageDetails</th>
                                                        <th> Action <i class="fa fa-cog"></i></th>
                                                        <?php		
					
						if($_SESSION['adminlevel']<=1){ 
							
					?>

                                                            

                                                            <?php 
                                                            $sql="SELECT * FROM promocodes_vs_shows";
					
						} else
                        {
                                    $sql="SELECT * FROM promocodes_vs_shows where created_by='".$admin."' ";
                        }
					?>
                                                    </tr>
                                                </thead>
                                                <tbody id="shpc_list" class="ui-sortable">
                                                    <?php

				$stmt4 = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
				$stmt4->execute();
				
				$result4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);
				$stmt4=null;
               if(count($result4)>0){

               
				foreach($result4 as $row){

					$id=$row['id'];
					$promo_code=$row['promocode'];
					$assigned_to=$row['assigned_to'];
					$created=$row['created_by'];
					$usage_count=get_var("SELECT usage_count from promo_codes where coupon_code='".$promo_code."' ");
                    $status=get_var("SELECT count(*) FROM tradmin_newqezy.promo_codes where coupon_code='".$promo_code."' and usage_count<max_count;")?"Active":"Deactivated";
					
			?>
                                                        <tr style="" class="ui-sortable-handle">
                                                            <td style="width: 200px;" class="level_name">
                                                                <?php echo $promo_code;?>
                                                            </td>
                                                            <td style="" class="level_name">
                                                                <?php echo $created;?>
                                                            </td>
                                                            <td style="" class="level_name">
                                                                <?php echo $assigned_to;?>
                                                            </td>
                                                            <td style="" class="level_name">
                                                                <?php echo $status;?>
                                                            </td>
                                                            <td style="" class="level_name">
                                                                <?php echo $usage_count;?>
                                                            </td>

                                                            <td style="" class="level_name"><span style='visibility:'><a id='pm_btn' style='/*color: #8e2c09;border: 1px solid #ece4e4;background-color: #e3eaea;*/padding: 0px 4px;text-decoration: none;font-size: 15px;font-weight: bolder;
' href='javascript:void(0)' class='btn btn-info btn-lg' onclick='return user_details(<?php echo $id;?>,"<?php echo $promo_code;?>")' data-toggle='modal' data-target='#myModal'><i class="fa fa-user" aria-hidden="true"> Users</i>
</a></span></td>

                                                            <td style="width: 100px;">
                                                                    <a style="cursor:pointer;" id="editShpc" name="editShpc" onclick="return confirmEditShpc(<?php echo $id;?>)"><em title="Edit" class="fa fa-pencil"> Edit</em></a> &nbsp;
                                                                    <a style="cursor:pointer;" id="removeShpc" name="removeShpc" onclick="return confirmDeActShpc(<?php echo $id;?>)"><i class="fa fa-power-off" aria-hidden="true">Deactivate</i></a>
                                                                </td>


                                                            <?php		
	if($_SESSION['adminlevel']<=1){ 
?>
                                                                
                                                                <?php 
	} 
?>
                                                        </tr>
                                                        <?php 
	} 
    }//if
    else{
        echo "<tr style='text-align:center'><td colspan='5'>No Results</td></tr>";
    }
?>
                                                </tbody>
                                                <tr id="no_res_div" align="center" style="margin: 5px auto; border-bottom: 1px solid #999;  max-width: 80%;
    font-size: 15px;">
                                                    <td colspan="7" id="no_res" style="margin:0 auto;text-align:center"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </form>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Modal -->
            <div class="modal fade" id="myModal" role="dialog">
                <div height="auto" class="modal-dialog" style="width:75%;heigh:80%;">

                    <!-- Modal content-->
                    <div class="modal-content" style="height:80%">
                        <div class="modal-header">
                            <button type="button" id="close_pop" onclick="return other_user_btn()" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Promo code:<span id="pc"></span></h4>
                        </div>
                        <div class="modal-body" style="height:70%">
                            <section class='section'>
                                <div class='row'>

                                    <style>
                                        td:nth-child(odd) {
                                            background: unset;
                                            color: black;
                                        }
                                    </style>

                                    <div class='col-xs-18 col-sm-12'>
                                        <div class='card'>
                                            <div class='card-block'>
                                                <div class='card-title-block'>
                                                    <h3 class='title'>
                                                        Users List
                                                    </h3>
                                                </div>
                                                <section class='example'>
                                                    <div class='table-responsive' align='center'>
                                                        <table class='table table-striped table-bordered table-hover' style='max-width:600px;max-height:400px'>
                                                            <thead>
                                                                <tr>
                                                                    <th>User</th>
                                                                    <th>IP Address</th>
                                                                    <th>City(State,Country)</th>
                                                                    <th>Days Left</th>
                                                                    <th>User-PromoCode Valid Range</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id='user_details'>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </section>

                        </div>
                    </div>

                </div>
            </div>

            <script>
                jQuery("#close_pop").click(function() {
                    jQuery("#pc").html("");
                    jQuery("#user_details").html("");

                });

                function validateEmail(email) {
                    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    return re.test(email);
                }

                function validatePhone(phone) {
                    var re = /^([0|\+[0-9]{1,5})?([1-9][0-9]{9})$/;
                    return re.test(phone);
                }

                function validatePromo(pc){
                    var re=/^(?!Q)(?!0)[A-Z0-9]{6,12}$/;
                    return re.test(pc);
                }

                function callsubmit() {

                    $("#shpc-form-section").css("height", "auto");

                    var pcode = $("#promocode").val();
                    var pcassign = jQuery("#assign").val();

                       
                    var check=validatePromo(pcode);
                    

                    if (pcode == "") {
                        $("#error").html("Please enter Promo Code");
                        return false;
                    }

                    else if (!check) {
                            $("#error").html("Promo Code must be Alpha-Numeric, Capital letters between 6-12 characters and should not start with 'Q' or '0'");
                        return false;
                    }

                    if (pcassign == "") {
                        $("#error").html("Please enter Person/Group to which promocode assigned");
                        return false;
                    }


                    if (pcode != "" && pcassign != "") {
                        return true;
                    }

                    return false;

                }


                

                function clearError() {

                    $("#error").html("");
                    $(".msg h4").html("");
                }


                function confirmDeActShpc(Delid) {

                    swal({
                        title: ' ',
                        text: 'Do you really want to de-activate this promocode ?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Deactivate",
                        cancelButtonText: "Cancel",
                        closeOnCancel: true
                    }, function() {
                        var input1 = $("<input>")
                            .attr("type", "hidden")
                            .attr("name", "delShpcid").val(Delid);
                        var input2 = $("<input>")
                            .attr("type", "hidden")
                            .attr("name", "delShpc").val(true);
                        $('#frm2').append($(input1));
                        $('#frm2').append($(input2));
                        $("#frm2").submit();

                    });

                }

                function confirmEditShpc(Editid) {

                    swal({
                        title: ' ',
                        text: 'Do you want to edit this info?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Edit",
                        cancelButtonText: "Cancel",
                        closeOnCancel: true
                    }, function() {
                        var input1 = $("<input>")
                            .attr("type", "hidden")
                            .attr("name", "editShpcid").val(Editid);
                        var input2 = $("<input>")
                            .attr("type", "hidden")
                            .attr("name", "editShpc").val(true);
                        $('#frm2').append($(input1));
                        $('#frm2').append($(input2));
                        $("#frm2").submit();

                    });

                }

                function user_details(id, pc) {

                    jQuery("#pc").html(pc);
                    var values = {
                        'action': 'getPromoUserDetails',
                        'pid': id
                    };

                    jQuery.ajax({
                        url: 'uservalidation_check',
                        type: 'post',
                        data: values,
                        success: function(response) {
                            jQuery("#user_details").html(response);
                        }
                    });

                }


                function countChar() {

                    var max = 78;
                    var count = jQuery("#channel_desc").val().length;

                    var left = max - count;
                    jQuery("#left_char").html(left);

                }

                setTimeout(function(){
                    $(".msg").hide();
                },5000);
                

                $("#shpc-form-section").css("height", "auto");

                function shpc_form() {

                    $("#promocode").val("");
                    $("#assign").val("");
                    $("#shpcid").val("");

                    var s = document.getElementById("shpc-form-section");

                    if (s.style.display == "none") {
                        jQuery("#shpc-form-section").show();
                    } else {
                        jQuery("#shpc-form-section").hide();
                    }
                }


                if ($("tr#no_res_div td").html() == "") {
                    $("tr#no_res_div").hide();
                }

<?php 
 if($_SESSION['adminlevel']<=1){ 
    ?>

            function findRows(table,searchText1,col1,searchText2,col2,searchText3,col3,searchText4,col4) {
    var rows = table.rows,
        r = 0,
        found = false,
        anyFound = false;
   
  var found1=false,found2=false,found3=false,found4=false;
  
    for (; r < rows.length; r += 1) {
        row = rows.item(r);
          
     // var i=column;
       // alert(searchText1+"in col:"+col1+"  row::"+r);
        
        if(col1!=99)
        found1 = (row.cells.item(col1).textContent.toLowerCase().indexOf(searchText1.toLowerCase().trim()) !== -1);
        else
        found1=true;
         if(col2!=99)
          found2 = (row.cells.item(col2).textContent.toLowerCase().indexOf(searchText2.toLowerCase().trim()) !== -1);else
        found2=true;
          if(col3!=99)
            found3 = (row.cells.item(col3).textContent.toLowerCase().indexOf(searchText3.toLowerCase().trim()) !== -1);else
        found3=true;
          if(col4!=99)
          found4 = (row.cells.item(col4).textContent.toLowerCase().indexOf(searchText4.toLowerCase().trim()) !== -1);else
        found4=true;
         
            
            found=found1 && found2 && found3 && found4;
        anyFound = anyFound || found;
//alert(found);
            //if(row.style.display=="none")
          //    found=false;
        row.style.display = found ? "table-row" : "none";
       }
        
       
    if(col1==99 && col2==99 && col3==99 && col4==99)
    {
        for (; r < rows.length; r += 1) {
            row = rows.item(r);
             row.style.display = "table-row" ;}
    }
    //document.getElementById('no_res').style.display = anyFound ? "none" : "block";
var x = document.getElementById("shpc_list").rows.length;
//alert("x"+x);
var cnt=0;

for(i=0;i<x;i=i+1)
{
 var y=document.getElementById("shpc_list").rows[i].style.display;

if(y=="none")
cnt=cnt+1;
}

//alert("cnt:"+cnt);
if(x==(cnt))
{
document.getElementById("no_res").innerHTML="NO SEARCH RESULTS";
jQuery("#no_res").html("NO SEARCH RESULTS");
jQuery("#no_res_div").show();
}
else
{
jQuery("#no_res").html("");
jQuery("#no_res_div").hide();
}

}

function performSearch() {
    var searchText1 = document.getElementById('searchPC').value,
        searchText2 = document.getElementById('searchCr').value,
        searchText3 = document.getElementById('searchAs').value,
        searchText4 = document.getElementById('searchSt').value,
        //searchText5 = document.getElementById('searchPH').value,
        targetTable = document.getElementById('shpc_list');
    var searchText=[],col;
    
    if(searchText1!="")
    {
    searchText[0]=searchText1;
    col1=0;
    }
  else{col1=99;}
  
  
    if(searchText2!="")
    {
    searchText[1]=searchText2;
    col2=1;
    }
  else{col2=99;}
  
  
 if(searchText3!="")
    {
    searchText[2]=searchText3;
    col3=2;
    }
  else{col3=99;
}

if(searchText4!="")
    {
    searchText[3]=searchText4;
    col4=3;
    }
  else{col4=99;}
  
    //alert(searchText);
    findRows(targetTable,searchText1,col1,searchText2,col2,searchText3,col3,searchText4,col4);

}

//document.getElementById("search").onclick = performSearch;
document.getElementById("searchPC").onkeyup = performSearch;
document.getElementById("searchCr").onkeyup = performSearch;
document.getElementById("searchAs").onkeyup = performSearch;
document.getElementById("searchSt").onkeyup = performSearch;
//document.getElementById("searchPH").onkeyup = performSearch;

<?php } ?>
            </script>

</article>

<?php

include('footer.php');
?>