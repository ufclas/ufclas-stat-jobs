<?php 
	include 'functions.php';
	include 'header.php';
	
	// Request post and page number
	$request_url = API_URL . 'get_category_posts/?';
	$params = array('category_slug' => 'jobs');
	if( is_valid_id($_GET['page']) ){
		$params['page'] = $_GET['page'];
	}
	$request_url .= http_build_query($params);
	
	$data = get_job_data( $request_url );
	$pages = $data->pages;
	
	// Check status and display content
	if( $data->status == 'ok' ){
				
		// Group posts by month/year
		$posts = array();
		foreach( $data->posts as $post ){
			$new_post = get_post( $post );
			$posts[$new_post['heading']][] = $new_post;
		}
		display_posts_tables($posts);
	}
	else {
		// Status is not 'ok'
		$post_error = (empty($data->status))? 'Error: Information not found':ucfirst($data->status) . ": " . $data->error;
?>
		<h2><?php echo $post_error; ?></h2>
		<div id="content" class="error">There was an error displaying this page. Please try again later.</div>
<?php
	}
	include 'footer.php'; 
?>
