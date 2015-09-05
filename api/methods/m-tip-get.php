<?php
$route = '/tip/';
$app->get($route, function ()  use ($app){

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['query'])){ $query = trim(mysql_real_escape_string($params['query'])); } else { $query = '';}
	if(isset($params['page'])){ $page = trim(mysql_real_escape_string($params['page'])); } else { $page = 0;}
	if(isset($params['count'])){ $count = trim(mysql_real_escape_string($params['count'])); } else { $count = 250;}
	if(isset($params['sort'])){ $sort = trim(mysql_real_escape_string($params['sort'])); } else { $sort = 'title';}
	if(isset($params['order'])){ $order = trim(mysql_real_escape_string($params['order'])); } else { $order = 'DESC';}

	// Pull from MySQL
	if($query!='')
		{
		$Query = "SELECT * FROM tip WHERE title LIKE '%" . $query . "%'";
		}
	else
		{
		$Query = "SELECT * FROM tip";
		}
	$Query .= " ORDER BY " . $sort . " " . $order . " LIMIT " . $page . "," . $count;
	//echo $Query . "<br />";

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
		$F['image'] = $image;

		array_push($ReturnObject, $F);
		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
