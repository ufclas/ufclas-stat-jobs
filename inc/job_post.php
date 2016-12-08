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
        $title = $post_data->title->rendered;
        $employer = $title;
        $position = '';
        
        /* 
         * Get employer and position from the title if separator is present
         */
        $separators = array('&#8211;', '-');
        foreach ($separators as $sep){
            if ( strpos( $title, $sep ) !== false ){
                
                $title = explode($sep, $post_data->title->rendered);
                
                if ( isset($title[1]) ){
                    $employer = trim($title[0]);
                    $position = trim($title[1]);
                }
                break;
            }
        }
		
		// Date and grouping header	
		$date = Datetime::createFromFormat( 'Y-m-d\TH:i:s', $post_data->date );
		$date_publish = $date->format('m/d/Y');
		$date_heading = $date->format('F Y');
		
		// Set properties
		$this->id = $post_data->id;
		$this->title = $title;
		$this->employer = iconv('UTF-8', 'ISO-8859-15//TRANSLIT', $employer);
		$this->position = iconv('UTF-8', 'ISO-8859-15//TRANSLIT', $position);
		$this->date = $date_publish;
		$this->grouping = $date_heading;
		$this->content = $post_data->content->rendered;
	}
	
	public function display(){
		?>
        <h2><?php echo $this->date; ?><br /><?php echo $this->position . '<br />' . $this->employer; ?></h2>
        <div id="content" class="jobs"><?php echo $this->content; ?></div>
    <?php	
	}
	
}
