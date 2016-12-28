<?php

error_reporting(0);
 
$channel_id = @$_GET["channel_id"];

include("../cron-config.php");

$presentdate =  date('d-m-Y');
$date2 = date('Y-m-d', strtotime("-1 days"));
$date1 = date('Y-m-d', strtotime("$date2 -6 days"));

$date_for_web = strtotime($date2);
$today = date('d-m-Y', strtotime($date1))." to ".date('d-m-Y', strtotime($date2));

// graph lib start here
require_once ('../lib/jpgraph/src/jpgraph.php');
require_once ('../lib/jpgraph/src/jpgraph_radar.php');

$friendlyIpsCond = $friendlyWebsites = "";

require_once ('../classes/WeeklyReports.class.php');
$WkRpt = new WeeklyReports();
$WkRpt->countrycityCodeHits();
$WkRpt->countrycityCodeDuration();

if($stmt->rowCount() > 0){
	

	// Create the basic radar graph
	$graph = new RadarGraph(700,600);

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

	// And output the graph
	$gdImgHandler = $graph->Stroke(_IMG_HANDLER);

	// Default is PNG so use ".png" as suffix
	$fileName3 = "imagefile3.png";
	$graph->img->Stream($fileName3);
	
}



if($stmt1->rowCount() > 0){
	
	
	// Create the basic radar graph
	$graph = new RadarGraph(700,600);

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
	$graph->title->Set("Qezyplay analytics               Country-City Based Viewed Duration                 ".$presentdate);
	$graph->title->SetFont(FF_FONT1,FS_BOLD);
	
	
	$graph->SetTitles($countrycityInfoDuration);

	// Create the first radar plot        
	$plot = new RadarPlot($countrycitydurationInfo);
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

	// And output the graph
	$gdImgHandler = $graph->Stroke(_IMG_HANDLER);

	// Default is PNG so use ".png" as suffix
	$fileName4 = "imagefile4.png";
	$graph->img->Stream($fileName4);
	
}
	
include "reports_weekly.html";
?>
