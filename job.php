<?php 
	include 'functions.php';
	include 'header.php';
	
	$post_id = $_GET['id'];
	
	if( is_valid_id($post_id) ){
	
		// Request post
		$request_url = API_URL . 'get_post/?post_id=' . $post_id;
		$data = get_job_data( $request_url );
		
		// Check status and display content
		if( $data->status == 'ok' ){
			$post = get_post( $data->post );
?>
	<h2><?php echo $post['date']; ?><br><?php echo $post['position'] . '<br />' . $post['employer']; ?></h2>
	<div id="content" class="jobs"><?php echo $post['content']; ?></div>
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
	include 'footer.php'; 
?>
