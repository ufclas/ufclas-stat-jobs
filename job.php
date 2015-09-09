<?php 
	// Includes
	include 'inc/job_post.php';
	include 'inc/job_list.php';
	
	// Initialize list
	$job_list = new Job_List( array(
		'request_type' => 'post'
	) );
	
	// Display header
	include 'inc/header.php';
	
	// Get information from the request response
	$status = $job_list->request_status();
	
	if( $status == '200' ){
		
		// Make a request for the list of posts
		$data = $job_list->request_data();
		
		if( !empty($data) ){
			// Create job post
			$job_post = new Job_Post( $data );
			
			// Display the table of job posts
			$job_post->display();
		}
	}
	else {
		// Request to API failed
		$job_list->display_error();
	}
	
	// Display header
	include 'inc/footer.php';