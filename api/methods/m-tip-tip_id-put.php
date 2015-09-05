<?php
$route = '/tip/:tip_id/';
$app->put($route, function ($tip_id) use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$tip_id = prepareIdIn($tip_id,$host);

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['post_date'])){ $post_date = mysql_real_escape_string($params['post_date']); } else { $post_date = date('Y-m-d H:i:s'); }
	if(isset($params['title'])){ $title = mysql_real_escape_string($params['title']); } else { $title = 'No Title'; }
	if(isset($params['author'])){ $author = mysql_real_escape_string($params['author']); } else { $author = ''; }
	if(isset($params['details'])){ $details = mysql_real_escape_string($params['details']); } else { $details = ''; }
	if(isset($params['image'])){ $image = mysql_real_escape_string($params['image']); } else { $image = ''; }

  	$Query = "SELECT * FROM tip WHERE tip_id= " . $tip_id;
	//echo $Query . "<br />";
	$Database = mysql_query($Query) or die('Query failed: ' . mysql_error());

	if($Database && mysql_num_rows($Database))
		{
		$query = "UPDATE tip SET";

		$query .= " title = '" . mysql_real_escape_string($title) . "'";
		$query .= ", post_date = '" . mysql_real_escape_string($post_date) . "'";

		if($author!='') { $query .= ", author = '" . $author . "'"; }
		if($details!='') { $query .= ", details = '" . $details . "'"; }
		if($image!='') { $query .= ", image = '" . $image . "'"; }

		$query .= " WHERE tip_id = '" . $tip_id . "'";

		//echo $query . "<br />";
		mysql_query($query) or die('Query failed: ' . mysql_error());
		}

	$tip_id = prepareIdOut($tip_id,$host);

	$F = array();
	$F['tip_id'] = $tip_id;
	$F['post_date'] = $post_date;
	$F['title'] = $title;
	$F['author'] = $author;
	$F['details'] = $details;
	$F['image'] = $image;

	array_push($ReturnObject, $F);

	$app->response()->header("Content-Type", "application/json");
	echo stripslashes(format_json(json_encode($ReturnObject)));

	});
?>
