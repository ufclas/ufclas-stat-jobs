<?php

function get_job_data( $request ){
	$session = curl_init($request);
	curl_setopt($session, CURLOPT_HEADER, false); 
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($session); 
	curl_close($session); 
	
	return json_decode($response);
}

function valid_id( $id ) {
	return (!empty( $id )) && filter_var($id, FILTER_VALIDATE_INT);
}

function get_post_date( $date ){
	$d = Datetime::createFromFormat( 'Y-m-d H:i:s', $date );
	return $d->format('Y-m-d');
}

?>