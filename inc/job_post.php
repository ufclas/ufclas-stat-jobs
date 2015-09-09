<?php 

class Job_Post {
	public $id;
	public $title;
	public $employer;
	public $position;
	public $date;
	public $grouping;
	public $content;
	
	function __construct( $post_data ){
		// Title, employer, position
		$title = explode('&#8211;', $post_data->title);
		$employer = trim($title[0]);
		$position = trim($title[1]);
		
		// Date and grouping header	
		$date = Datetime::createFromFormat( 'Y-m-d\TH:i:s', $post_data->date );
		$date_publish = $date->format('m/d/Y');
		$date_heading = $date->format('F Y');
		
		// Set properties
		$this->id = $post_data->ID;
		$this->employer = iconv('UTF-8', 'ISO-8859-15//TRANSLIT', $employer);
		$this->position = iconv('UTF-8', 'ISO-8859-15//TRANSLIT', $position);
		$this->date = $date_publish;
		$this->grouping = $date_heading;
		$this->content = iconv('UTF-8', 'ISO-8859-15//TRANSLIT', $post_data->content);
	}
	
	public function display(){
		?>
        <h2><?php echo $this->date; ?><br /><?php echo $this->position . '<br />' . $this->employer; ?></h2>
        <div id="content" class="jobs"><?php echo $this->content; ?></div>
    <?php	
	}
	
}
