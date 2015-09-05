<?php
$route = '/tip/';
$app->post($route, function () use ($app){

	$Add = 1;
	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['post_date'])){ $post_date = mysql_real_escape_string($params['post_date']); } else { $post_date = date('Y-m-d H:i:s'); }
	if(isset($params['title'])){ $title = mysql_real_escape_string($params['title']); } else { $title = 'No Title'; }
	if(isset($params['author'])){ $author = mysql_real_escape_string($params['author']); } else { $author = ''; }
	if(isset($params['details'])){ $details = mysql_real_escape_string($params['details']); } else { $details = ''; }
	if(isset($params['image'])){ $image = mysql_real_escape_string($params['image']); } else { $image = ''; }

  	$Query = "SELECT * FROM tip WHERE title = '" . $title . "' AND Author = '" . $author . "'";
	//echo $Query . "<br />";
	$Database = mysql_query($Query) or die('Query failed: ' . mysql_error());

	if($Database && mysql_num_rows($Database))
		{
		$Thistip = mysql_fetch_assoc($Database);
		$tip_id = $Thistip['tip_id'];
		}
	else
		{
		$Query = "INSERT INTO tip(post_date,title,author,details,image)";
		$Query .= " VALUES(";
		$Query .= "'" . mysql_real_escape_string($post_date) . "',";
		$Query .= "'" . mysql_real_escape_string($title) . "',";
		$Query .= "'" . mysql_real_escape_string($author) . "',";
		$Query .= "'" . mysql_real_escape_string($details) . "',";
		$Query .= "'" . mysql_real_escape_string($image) . "'";
		$Query .= ")";
		//echo $Query . "<br />";
		mysql_query($Query) or die('Query failed: ' . mysql_error());
		$tip_id = mysql_insert_id();
		}

	$tip_id = prepareIdOut($tip_id,$host);

	$ReturnObject['tip_id'] = $tip_id;

	$app->response()->header("Content-Type", "application/json");
	echo format_json(json_encode($ReturnObject));

	});
?>
