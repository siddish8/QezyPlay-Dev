<?php

error_reporting(0);
 
//$channel_id = @$_GET["channel_id"];

include("../cron-config.php");



$presentdate =  date('d-m-Y');
//$date2 = date('Y-m-d', strtotime("-1 days"));
//$date1 = date('Y-m-d', strtotime("$date2 -6 days"));

		$datenow=new DateTime("now");
		$date2=$datenow=$datenow->format("Y-m-d");
		$date1=$datewkbck=date('Y-m-d', strtotime('-1 week +1 day')); 
		$datewkbckcal=$date = date('Y-m-d', strtotime('-1 week'));


$date_for_web = strtotime($date2);
$today = date('d-m-Y', strtotime($date1))." to ".date('d-m-Y', strtotime($date2));

// graph lib start here
require_once ('../lib/jpgraph/src/jpgraph.php');
require_once ('../lib/jpgraph/src/jpgraph_radar.php');
require_once ('../lib/jpgraph/src/jpgraph_bar.php');

$friendlyIpsCond = $friendlyWebsites = "";

require_once ('../classes/LastWeekReports.class.php');



$WkRpt = new LastWeekReports();
//$WkRpt->lastWeekHits();
//$WkRpt->lastWeekDuration();
$X=$WkRpt->lastWeekByChannelHits();
$Y=$WkRpt->lastWeekByChannelDuration();


if($stmt3->rowCount() > 0){
	

	$datay=array();
	$datax=array();
	$datax=$X[0];
	$datay=$X[1];
	
	// Size of graph
	$width=400;
	$height=500;

	// Set the basic parameters of the graph
	$graph = new Graph($width,$height,'auto');
	$graph->SetScale('textlin');

	// Rotate graph 90 degrees and set margin
	//$graph->Set270AndMargin(50,20,50,30);
	//$graph->SetMargin(50,20,50,30);
	//$graph->SetMargin(5,100,3,40);

	// Nice shadow
	$graph->SetShadow();

	// Setup title
	$graph->title->Set('QezyPlay Analytics '."\n".'Channels-Based Hits | '.$today);
	$graph->title->SetFont(FF_FONT1,FS_BOLD,14);

	// Setup X-axis
	$graph->xaxis->SetTickLabels($datax);
	//$graph->xaxis->SetFont(FF_FONT1,FS_NORMAL,12);
	$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
	$graph->xaxis->SetLabelAngle(45);

	// Some extra margin looks nicer
	$graph->xaxis->SetLabelMargin(0);

	// Label align for X-axis
	$graph->xaxis->SetLabelAlign('center','center');

	// Add some grace to y-axis so the bars doesn't go
	// all the way to the end of the plot area
	$graph->yaxis->scale->SetGrace(20);

	// We don't want to display Y-axis
	//$graph->yaxis->Hide();

	// Now create a bar pot
	$bplot = new BarPlot($datay);

	$bplot->SetFillColor("#B0C4DE");
	//  ALSO tried:
	//  $bplot->SetColor(array("red","green","blue","gray"));
	$bplot->SetShadow();

	//You can change the width of the bars if you like
	//$bplot->SetWidth(0.5);

	// We want to display the value of each bar at the top
	$bplot->value->Show();
	$bplot->value->SetFont(FF_FONT1,FS_BOLD,12);
	$bplot->value->SetAlign('left','center');
	$bplot->value->SetColor('black','darkred');
	$bplot->value->SetFormat('%.1f mkr');
	

	// Add the bar to the graph
	$graph->Add($bplot);
	$bplot->value->Show();

	// .. and stroke the graph
	//$graph->Stroke();

	// Create the basic radar graph
	/*$graph = new RadarGraph(700,600);

	// Set background color and shadow
	$graph->SetColor("white");
	$graph->SetShadow();

	// Position the graph
	$graph->SetCenter(0.4,0.55);

	// Setup the axis formatting     
	$graph->axis->SetFont(FF_FONT1,FS_BOLD);
	$graph->axis->SetWeight(2);

	// Setup the grid lines
	$graph->grid->SetLineStyle("longdashed");
	$graph->grid->SetColor("navy");
	$graph->grid->Show();
	$graph->HideTickMarks();

	// Setup graph titles
	$graph->title->Set("Qezyplay analytics               Country-City Based Hits                 ".$presentdate);
	$graph->title->SetFont(FF_FONT1,FS_BOLD);
	
	
	$graph->SetTitles($countrycityInfoHits);

	// Create the first radar plot        
	$plot = new RadarPlot($countrycityclicksInfo);
	$plot->SetLegend($today_in_legend);
	$plot->SetColor("red","lightred");
	$plot->SetFill(false);
	$plot->SetLineWeight(2);

	// Create the second radar plot
	$plot->SetFillColor('lightred');
	$graph->SetSize(0.6);
	$graph->SetPos(0.5,0.6);

	// Add the plots to the graph
	$graph->Add($plot);
*/
	// And output the graph
	$gdImgHandler = $graph->Stroke(_IMG_HANDLER);

	// Default is PNG so use ".png" as suffix
	$fileName3 = "imagefile5.png";
	$graph->img->Stream($fileName3);
	
}



if($stmt4->rowCount() > 0){
	
	
	$datay=array();
	$datax=array();
	$datax=$Y[0];
	$datay=$Y[1];
	

	
	// Size of graph
	$width=400;
	$height=500;

	// Set the basic parameters of the graph
	$graph = new Graph($width,$height,'auto');
	$graph->SetScale('textlin');

	// Rotate graph 90 degrees and set margin
	//$graph->Set270AndMargin(50,20,50,30);
	$graph->SetMargin(50,20,50,30);
	//$graph->SetMargin(5,100,3,40);
	// Nice shadow
	$graph->SetShadow();

	// Setup title
	$graph->title->Set('QezyPlay Analytics'."\n".'Channels-Based Durations | '.$today);
	$graph->title->SetFont(FF_FONT1,FS_BOLD,14);

	// Setup X-axis
	$graph->xaxis->SetTickLabels($datax);
	$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
	$graph->xaxis->SetLabelAngle(45);

	// Some extra margin looks nicer
	$graph->xaxis->SetLabelMargin(0);

	// Label align for X-axis
	$graph->xaxis->SetLabelAlign('center','center');

	// Add some grace to y-axis so the bars doesn't go
	// all the way to the end of the plot area
	$graph->yaxis->scale->SetGrace(20);

	// We don't want to display Y-axis
	//$graph->yaxis->Hide();

	// Now create a bar pot
	$bplot = new BarPlot($datay);

	$bplot->SetFillColor("#B0C4DE");
	//  ALSO tried:
	//  $bplot->SetColor(array("red","green","blue","gray"));
	$bplot->SetShadow();

	//You can change the width of the bars if you like
	//$bplot->SetWidth(0.5);

	// We want to display the value of each bar at the top
	$bplot->value->Show();
	$bplot->value->SetFont(FF_FONT1,FS_BOLD,12);
	$bplot->value->SetAlign('left','center');
	$bplot->value->SetColor('black','darkred');
	$bplot->value->SetFormat('%.2f mkr');

	// Add the bar to the graph
	$graph->Add($bplot);
	$bplot->value->Show();

	// .. and stroke the graph
	//$graph->Stroke();

	// Create the basic radar graph
	/*$graph = new RadarGraph(700,600);

	// Set background color and shadow
	$graph->SetColor("white");
	$graph->SetShadow();

	// Position the graph
	$graph->SetCenter(0.4,0.55);

	// Setup the axis formatting     
	$graph->axis->SetFont(FF_FONT1,FS_BOLD);
	$graph->axis->SetWeight(2);

	// Setup the grid lines
	$graph->grid->SetLineStyle("longdashed");
	$graph->grid->SetColor("navy");
	$graph->grid->Show();
	$graph->HideTickMarks();

	// Setup graph titles
	$graph->title->Set("Qezyplay analytics               Country-City Based Hits                 ".$presentdate);
	$graph->title->SetFont(FF_FONT1,FS_BOLD);
	
	
	$graph->SetTitles($countrycityInfoHits);

	// Create the first radar plot        
	$plot = new RadarPlot($countrycityclicksInfo);
	$plot->SetLegend($today_in_legend);
	$plot->SetColor("red","lightred");
	$plot->SetFill(false);
	$plot->SetLineWeight(2);

	// Create the second radar plot
	$plot->SetFillColor('lightred');
	$graph->SetSize(0.6);
	$graph->SetPos(0.5,0.6);

	// Add the plots to the graph
	$graph->Add($plot);
*/
	// And output the graph
	$gdImgHandler = $graph->Stroke(_IMG_HANDLER);


	// Default is PNG so use ".png" as suffix
	$fileName4 = "imagefile6.png";
	$graph->img->Stream($fileName4);
	
}
	
include "reports_lastweek.html";
?>
