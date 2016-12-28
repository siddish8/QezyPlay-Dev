<?php

/* * ****************************************************************
 * Ideabytes Software India Pvt Ltd.                              *
 * 50 Jayabheri Enclave, Gachibowli, HYD                          *
 * Created Date : 22/11/2016                                      *
 * Created By : Siddish G                                        *
 * Vision :   Channel Streaming Status                              *
 * Modified by :Siddish G     Date : 22/11/2016       Version : V1  *
 * Description : Manage Channels Streaming page                  *
 * *************************************************************** */

/* Includes header file and class file */

include('qp1/db-config.php');
include('qp1/function_common.php');
?>
<article class="content items-list-page" style="    background: #FFC107;
    padding: 30px;
    border: 8px solid #e64d4d;
    border-radius: 10px;">

<?php




			/*$stmt4 = $dbcon->prepare("SELECT * FROM channel_stream_status", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt4->execute();
			$result4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);
			$stmt4=null;*/
			
			$sql4="SELECT * FROM channel_stream_status";
			$result4=get_all($sql4);
			
			
?>

<section class="section">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-block">
                                        <div class="card-title-block">
                                            <h3 class="title">
							Channels and Streaming Status
						</h3> </div>
                                        <section class="example">		
		<form id="frm2" method="post">
		 <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover">
		
			<thead>
                                    <th width="5%">Channel ID</th>                                  
                                    <th width="25%">Channel Name</th>
	                           <th width="15%">Streaming Status</th>
                                    <th width="15%">Updated DateTime</th>
                                </tr>
			</thead>
			<tbody id="channel_status_list" class="ui-sortable">
			 
                                <?php
                               
                               foreach($result4 as $row){

				$id=$row['id'];
				$channel_id = $row['channel_id'];
				$channel_name=$row['channel_name'];
				$status=$row['status'];
				$date=$row['updated_datetime'];
				
				                 ?>
                               <tr>
					<td style="display:none" id="rid"><?php echo $id ?></td>
                                    <td><?php echo $channel_id;?></td>                                  
                                    <td><?php echo $channel_name;?></td>
									
									
                                    <td>
                                        <select name="active_status_list[]" id="<?php echo $id;?>" onChange="changeStatus(this.id);">
                                           
                                           <option value="">-Set Channel Status-</option>
									<?php
									"";									
									for($i=0;$i<=1;$i++)
									{
										$selected = ($i == $status) ? "selected='selected'" : "";
										$ival = ($i == 1) ? "Active" : "Inactive";
									echo "<option value='".$i."'".$selected.">".$ival."</option>";
									}
									
									?>
				
                                            
                                           
                                        </select>
										

                                    </td>

				<td><?php echo $date?>		</td>
                                    
                              
                                </tr>
                                <?php
                                }
                                ?>
				
                           </tbody>
			<tr id="no_res_div" align="center" style="margin: 5px auto;
    border-bottom: 1px solid #999;
    max-width: 80%;
    font-size: 15px;"><td colspan="6" id="no_res" style="margin:0 auto;text-align:center"></td></tr>
		</table></div></form>
 </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
<script>
    function changeStatus(sent) {
        var statusValue = document.getElementById(sent).value;
	alert(sent+statusValue);
     
        jQuery.ajax({
            type: "POST",
            url: "http://ideabytestraining.com/newqezyplay/qp1/uservalidation_check",
		
            data: { "action" : "setStreamStatus","id" : sent, "status" : statusValue},
		
            success: function(response)
            {

		alert(response);
                //$("#msgInHeader").empty().html(response);
            }
        });
    }

setTimeout(function(){
   window.location.reload(1);
}, 60000);
</script>
</article>
<?php

?>
