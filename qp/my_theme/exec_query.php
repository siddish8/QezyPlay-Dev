<?php
include("theme-header.php");


if(isset($_POST['execQ']))
{

$sql=$_POST['query'];
$qt=$_POST['query_type'];

$msg="<br>";
//echo $qt;
switch($qt)
{

case 1:
	//echo "insert";
	$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	$res=$stmt->fetchAll(PDO::FETCH_ASSOC);
	if($res)
	{
	$msg.="Success...<br> Results: <br>";
	foreach($res as $r)
	{
		foreach($r as $key=>$value)
		$msg.=$key." : " .$r[$key]."<br>";
	}
	//print_r($res);
	}
	else
	{
	$msg.=print_r($stmt->errorInfo(),true);
	}
	
	$msg.="<br> Executed Query: ".$sql."<br>";

	break;
case 2:
	$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$res=$stmt->execute();
	if($res)
	{
	$msg.="Success...<br>";
	$msg.="Inserted:".$dbcon->lastInsertId();
		
	
	}


	$msg.="<br> Executed Query: ".$sql."<br>";
	break;
case 3:
	$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$res=$stmt->execute();
	$res1=$stmt->fetchAll();
	
	if($res)
	{
	$msg.="Success...<br>";
	
	$msg.="U.p.d.a.t.e.d.";
	}
	
	$msg.="<br> Executed Query: ".$sql."<br>";
	break;
case 4:	
	
	$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$res=$stmt->execute();
	if($res)
	{
	$msg.="Success...<br>";
	$msg.="D.e.l.t.e.d.";
	}


	$msg.="<br> Executed Query: ".$sql."<br>";
	break;
default:

	break;


}

}

if($msg!="")
{
echo "<style>#res{visibility:visible !important;}</style>";
}

echo '
<!-- Features -->
				<div id="features-wrapper">
					<div class="container">
						<div class="row">
<div id="res" style="width:50%;margin:0 auto;float:unset;visibility:hidden">

								<!-- Box -->
									<section class="box feature">
										
										<div class="inner">
											<header>
												<h2>RESULTS</h2>
												
											</header>
											<p>'.$msg.'</p>
										</div>
									</section>

							</div>
	</div>
					</div>
				</div>';

echo '<div id="main-wrapper">
					<div class="container">
						<div class="row 200%">
				<form method="post">
<div class="8u 12u(medium) important(medium)">

								<!-- Content -->
									<div id="content">
										<section class="last">
											<h2>Query Execution</h2>

											<p>
											
Enter Query: <input required type="textarea" name="query" id="query" /><br> <br>
Select Query Type: <select name="query_type" required><option id="0" value="">CHOOSE ANY</option><option id="1" value="1">SELECT</option><option id="2" value="2">INSERT</option><option id="3" value="3">UPDATE</option><option id="4" value="4">DELETE</option></select><br><br>
<input type="submit" name="execQ" value="Execute Query" /> <br /><br />

 										</p>

											<!-- a href="#" class="button icon fa-arrow-circle-right">Continue Reading</a -->
										</section>
									</div>

							</div>
</form>						
						</div>
					</div>
				</div>
';





include("theme-footer.php");
?>
