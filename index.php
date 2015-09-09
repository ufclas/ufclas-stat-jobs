<?php 
	// Includes
	include 'inc/job_post.php';
	include 'inc/job_list.php';
	
	// Initialize list
	$job_list = new Job_List( array(
		'request_type' => 'posts',
		'request_params' => array(
			'category_name' => 'jobs',
			'posts_per_page' => '30',
		),
	) );
	
	// Display header
	include 'inc/header.php';
	?>
	
	<p>This is current listing of job announcements related to Statistics. If you have any questions or comments regarding this
listing, please contact <a href="mailto:jobs@stat.ufl.edu">jobs@stat.ufl.edu</a>. To submit a job for posting please use the <a href="http://forms.stat.ufl.edu/forms/job-announce/">Statistics Job Submission Form</a>.</p>
	
	<?php
	
	// Get information from the request response
	$status = $job_list->request_status();
	
	if( $status == '200' ){
		
		// Make a request for the list of posts
		$data = $job_list->request_data();
		
		if( !empty($data) ){
			// Create the list of posts from data
			$job_list->set_posts( $data );
			
			// Display the table of job posts
			$job_list->display();
		}
	}
	else {
		// Request to API failed
		$job_list->display_error();
	}
	
	// Display header
	include 'inc/footer.php';