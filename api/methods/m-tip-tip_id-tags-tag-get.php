<?php
$route = '/tip/:tip_id/tags/:tag/';
$app->delete($route, function ($tip_id,$tag)  use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$tip_id = prepareIdIn($tip_id,$host);

	$ReturnObject = array();

 	$request = $app->request();
 	$param = $request->params();

	if($tag != '')
		{

		$tip_id = trim(mysql_real_escape_string($tip_id));
		$tag = trim(mysql_real_escape_string($tag));

		$CheckTagQuery = "SELECT tag_id FROM tags where tag = '" . $tag . "'";
		$CheckTagResults = mysql_query($CheckTagQuery) or die('Query failed: ' . mysql_error());
		if($CheckTagResults && mysql_num_rows($CheckTagResults))
			{
			$Tag = mysql_fetch_assoc($CheckTagResults);
			$tag_id = $Tag['tag_id'];

			$DeleteQuery = "DELETE FROM tip_tag_pivot where tag_id = " . trim($tag_id) . " AND tip_ID = " . trim($tip_id);
			$DeleteResult = mysql_query($DeleteQuery) or die('Query failed: ' . mysql_error());
			}

		$tag_id = prepareIdOut($tag_id,$host);

		$F = array();
		$F['tag_id'] = $tag_id;
		$F['tag'] = $tag;
		$F['tip_count'] = 0;

		array_push($ReturnObject, $F);

		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
