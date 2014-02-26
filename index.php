<?php 
	require '../_lib/FirePHPCore/fb.php';
	include 'functions.php';
	include('header.php');
	
	
	// Request post
	$api_url = "http://test.example.com/stat/api/get_category_posts/?category_slug=jobs";
	$data = get_job_data( $api_url );
	//FB::info($data, 'data');
	
	// Check status and display content
	if( $data->status == 'ok' ){
		/*
		$post = $data->post;
		$post_date = $post->date;
		$post_title = $post->title; 
		$post_body = $post->content; 
		*/
		
		$posts = $data->posts;
		
		FB::info($data, 'posts');
		
		echo '<h2></h2>';
				
		echo '<table class="joblist"><tr><th>University/Company</th><th>Position Title</th><th>Date</th></tr>';
		foreach($posts as $post){
			$title = explode('&#8211;', $post->title_plain);
			$employer = trim($title[0]);
			$position = trim($title[1]);
			$post_id = $post->id;
			$date = get_post_date( $post->date );
			
			echo '<tr><td><a href="job.php?id=' . $post_id . '">' . $employer . '</a></td>';
			echo "<td>{$position}</td>";
			echo "<td>{$date}</td></tr>";
		}
		echo '</table>';
		
?>
	<!--<h2><?php echo $post_date; ?><br><?php echo $post_title; ?></h2>
	<div id="content"><?php echo $post_body; ?></div>-->
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
	include('footer.php'); 
?>
