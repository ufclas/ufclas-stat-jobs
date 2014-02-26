<?php

define('API_URL', 'http://test.clas.ufl.edu/stat/api/');

function get_job_data( $request ){
	$session = curl_init($request);
	curl_setopt($session, CURLOPT_HEADER, false); 
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($session);
	curl_close($session); 
	
	return json_decode($response);
}

function is_valid_id( $id ) {
	return (!empty( $id )) && filter_var($id, FILTER_VALIDATE_INT);
}

function get_post( $post ) {
	$title = explode('&#8211;', $post->title_plain);
	$employer = trim($title[0]);
	$position = trim($title[1]);
	$date = Datetime::createFromFormat( 'Y-m-d H:i:s', $post->date );
	$date_publish = $date->format('m/d/Y');
	$date_heading = $date->format('F Y');
	
	$post_item = array(
		'employer' => iconv('UTF-8', 'ISO-8859-15//TRANSLIT', $employer),
		'position' => $position,
		'date' => $date_publish,
		'heading' => $date_heading,
		'content' => $post->content,
		'id' => $post->id
	);
	return $post_item;
}

function display_posts_tables($posts){
	foreach($posts as $heading => $post_data){
		echo '<h2>' . $heading . '</h2>';
		echo '<table class="joblist"><tr><th class="employer">University/Company</th><th class="position">Position Title</th><th class="date">Date</th></tr>';
		foreach($post_data as $post){
			echo '<tr><td><a href="job.php?id=' . $post['id'] . '">' . $post['employer'] . '</a></td>';
			echo '<td>' . $post['position'] . '</td>';
			echo '<td>' . $post['date'] . '</td></tr>';
		}
		echo '</table>';
	}
}

function display_pages($num_pages){ 
	$current = (empty($_GET['page']))? 1:$_GET['page'];
	echo '<ul id="nav-pages">';
	for($i=1; $i<=$num_pages; $i++){
		$label = ($i == $current)? "{$i}":"<a href='?page={$i}'>{$i}</a>";
		echo "<li>{$label}</li>";
	}
	echo '</ul>';
}

?>