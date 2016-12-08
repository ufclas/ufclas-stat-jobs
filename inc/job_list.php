<?php 

class Job_List {
	
	// Class Properties
	public static $api_url = 'http://forms.stat.ufl.edu/wp-json/wp/v2';
	public static $total_pages = 1;
	public static $app_title = 'Statistics Jobs';
	
	// Properties
	public $page_title = '';
	public $current_page;
	public $current_post;
	public $request_url = '';
	public $response_status;
	public $posts = array();
	
	// Constructor
	public function __construct( $args ){
		$type = (isset($args['request_type']))? $args['request_type']:'posts';
		$params = (isset($args['request_params']))? $args['request_params']:array();
		
		// Set current page
		if( $type == 'posts' ){
			$page = ( isset($_GET['page']) )? $_GET['page'] : 0;
			$this->set_current_page( $page );
		}
		if( $type == 'post' ){
			$id = ( isset($_GET['id']) )? $_GET['id'] : 0;
			$this->set_current_post( $id );	
		}
		
		// Set request url
		$this->set_request_url( $type, $params );
		
		// Set page title
		$this->set_page_title();
	}
	
	// Methods
	public function get_page_title(){
		return $this->page_title;
	}
	public function get_total_pages(){
		return self::$total_pages;
	}
	public function get_current_page(){
		return $this->current_page;
	}
	public function set_page_title(){
		$this->page_title = self::$app_title;
	}
	public function set_current_page( $page ){
		$this->current_page = (empty($page))? 1:intval($page);
	}
	public function set_current_post( $id ){
		$this->current_post = (empty($id))? 1:intval($id);
	}
	
	/**
	 * Set the page request url
	 */
	public function set_request_url( $type, $request_params = array() ){
		$request_url = self::$api_url;	
		$params = $request_params;
		
		// Build query
		if( $type == 'posts' ){
			$request_url .= '/posts/?';	
			if( !empty($request_params) ){
				$params['filter'] = $request_params;
			}
			if( $this->current_page > 1 ){
				$params['page'] = $this->current_page;
			}
		}
		if( $type == 'post' ){
			$request_url .= '/posts/';	
			if( !empty($this->current_post) ){
				$request_url .= $this->current_post;
			}
		}
		
		// Add the params to the request
		$request_url .= http_build_query( $params );

		// Set the url
		$this->request_url = $request_url;
	}
	
	public function set_response_status( $headers ){
		// Set the value from the status code in the rewponse header
		preg_match("/HTTP\/[\d\.]+ (\d+) /", $headers, $matches);
		if( !empty($matches) ){
			$this->response_status = intval($matches[1]);
		}
	}
	
	public function set_total_pages( $headers ){
		// Get the value from X-WP-TotalPage in the response header
		preg_match("/X-WP-TotalPages: (\d+)/", $headers, $matches);
		if( !empty($matches) ){
			self::$total_pages = intval($matches[1]);
		}
	}
	
	/**
	 * Get the status code
	 */
	public function request_status(){
		return $this->response_status;
	}
	
	/**
	 * Send a request for data, set the response status and total pages
	 */
	public function request_data(){ 
		$request = $this->request_url;
		$session = curl_init($request);
		curl_setopt($session, CURLOPT_HEADER, true); 
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($session);
        $header_size = curl_getinfo($session, CURLINFO_HEADER_SIZE);
		curl_close($session);
        
        // Parse the request header and body
        $response_header = substr( $response, 0, $header_size );
        $response_body = substr( $response, $header_size );
        
        // Set the status code from the header
		$this->set_response_status( $response_header );
        
        // Set the value from the X-WP-TotalPage header
		$this->set_total_pages( $response_header );
        
		return json_decode($response_body);
	}
	
	/**
	 * Set the page request url
	 */
	public function set_posts( $response_data ){
		
		foreach( $response_data as $post_data ){
			
			// Create job post
			$job_post = new Job_Post( $post_data );
			
			// Add job post to the list by month/year
			$this->posts[ $job_post->grouping ][] = $job_post;
		}
	}
	
	function display(){
		foreach($this->posts as $heading => $job_posts){
			echo '<h2>' . $heading . '</h2>';
			echo '<table class="joblist"><tr><th class="employer">University/Company</th><th class="position">Position Title</th><th class="date">Date</th></tr>';
			foreach($job_posts as $job_post){
				echo '<tr><td><a href="job.php?id=' . $job_post->id . '">' . $job_post->employer . '</a></td>';
				echo '<td>' . $job_post->position . '</td>';
				echo '<td>' . $job_post->date . '</td></tr>';
			}
			echo '</table>';
		}
	}
	
	/**
	 * Echo an html unordered list with footer page links
	 */
	public function display_pages(){ 
		$total_pages = $this->get_total_pages();
		$current_page = $this->current_page;
		$prev_class = ( $current_page == 1 )? 'hidden':'';
		$next_class = ( $current_page == $total_pages )? 'hidden':'';
		
		if( $total_pages > 1 ){		
			echo '<ul id="nav-pages">';
			echo '<li class="' . $prev_class . '"><a href="?page=' . ($current_page-1) . '">&laquo;&nbsp;Previous</a></li>';
			
            $label = "Page {$current_page} of {$total_pages}";
            
            echo "<li><a href='?page={$current_page}'>{$label}</a></li>";
        
			echo '<li class="' . $next_class . '"><a href="?page=' . ($current_page+1) . '">Next&nbsp;&raquo;</a></li>';
			echo '</ul>';
		}
	}
	
	/**
	 * Echo an html unordered list with footer page links
	 */
	public function display_error(){ 
		$error_message = (empty($this->response_status))? 'Error: Problem with Request':'Error: ' . $this->response_status;
		?>
        <h2><?php echo $error_message; ?></h2>
        <div id="content" class="error"><p>There was an error displaying this page. Please try again later or report the problem to the site administrator.<p></div>
        <?php
	}
}