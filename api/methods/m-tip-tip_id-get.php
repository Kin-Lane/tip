<?php
$route = '/tip/:tip_id/';
$app->get($route, function ($tip_id)  use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$tip_id = prepareIdIn($tip_id,$host);

	$ReturnObject = array();

	$Query = "SELECT * FROM tip WHERE tip_id = " . $tip_id;

	$DatabaseResult = mysql_query($Query) or die('Query failed: ' . mysql_error());

	while ($Database = mysql_fetch_assoc($DatabaseResult))
		{

		$tip_id = $Database['ID'];
		$post_date = $Database['Post_Date'];
		$title = $Database['Title'];
		$author = $Database['Author'];
		$details = $Database['details'];
		$image = $Database['image'];

		// manipulation zone

		$tip_id = prepareIdOut($tip_id,$host);

		$F = array();
		$F['tip_id'] = $tip_id;
		$F['post_date'] = $post_date;
		$F['title'] = $title;
		$F['author'] = $author;
		$F['details'] = $details;
		$F['image'] = $image;

		$ReturnObject = $F;
		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
