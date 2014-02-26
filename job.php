<?php 
	require '../_lib/FirePHPCore/fb.php';
	include 'functions.php';
	include('header.php');
	
	$post_id = $_GET['id'];
	
	if( valid_id($post_id) ){
	
		// Request post
		$api_url = "http://test.example.com/stat/api/get_post/?post_id=";
		$request = $api_url . $post_id;
		$data = get_job_data( $request );
		
		// Check status and display content
		if( $data->status == 'ok' ){
			$post = $data->post;
			$post_date = $post->date;
			$post_title = $post->title; 
			$post_body = $post->content; 
?>
	<h2><?php echo $post_date; ?><br><?php echo $post_title; ?></h2>
	<div id="content" class="jobs"><?php echo $post_body; ?></div>
<?php
		}
		else {
			// Status is not 'ok'
			$post_error = ucfirst($data->status) . ": " . $data->error;
	?>
		<h2><?php echo $post_error; ?></h2>
		<div id="content" class="error">There was an error displaying this page. Please try again later.</div>
	
<?php
		}
	}
	else {
		// Not a valid post id
?>
		<h2>Error: Invalid Request</h2>
		<div id="content" class="error">There was an error displaying this page. Please try again later.</div>
<?php
	}
	include('footer.php'); 
?>
