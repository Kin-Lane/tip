<?php
$route = '/tip/tags/:tag/tip/';
$app->get($route, function ($tag)  use ($app){

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	$Query = "SELECT b.* from tags t";
	$Query .= " JOIN tip_tag_pivot btp ON t.tag_id = btp.tag_id";
	$Query .= " JOIN tip t ON btp.tip_id = b.tip_id";
	$Query .= " WHERE tag = '" . $tag . "'";

	$DatabaseResult = mysql_query($Query) or die('Query failed: ' . mysql_error());

	while ($Database = mysql_fetch_assoc($DatabaseResult))
		{

		$tip_id = $Database['tip_id'];
		$post_date = $Database['post_date'];
		$title = $Database['title'];
		$author = $Database['author'];
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
		$F['details'] = $details;
		$F['image'] = $image;

		array_push($ReturnObject, $F);
		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
