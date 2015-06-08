<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

	public $meta_title;
	public $data =array();
	// public $data = array();
	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->data['meta_title'] = $this->config->item('site_name');
		$this->data['site_name'] = $this->config->item('site_name');
	}


	/**
	 * @author                          Madhuranga Senadheera
	 * Purpose of the function          Description
	 * 
	 */
	public function get_remain_time($starttime)
	{

		$diff =  (now() - $startdate);

		// immediately convert to days 
		$temp=$diff/86400; // 60 sec/min*60 min/hr*24 hr/day=86400 sec/day 

		// days 
		$days=floor($temp); 
		//echo "days: $days<br/>\n"; $temp=24*($temp-$days); 
		// hours 
		$hours=floor($temp); 
		//echo "hours: $hours<br/>\n"; $temp=60*($temp-$hours); 
		// minutes 
		$minutes=floor($temp); 
		//echo "minutes: $minutes<br/>\n"; $temp=60*($temp-$minutes); 
		// seconds 
		$seconds=floor($temp); 
		//echo "seconds: $seconds<br/>\n<br/>\n"; 

		echo "Result: {$days}d {$hours}h {$minutes}m {$seconds}s<br/>\n"; 
		echo "Expected: 7d 0h 0m 0s<br/>\n"; 


		echo "time isss".time();

		echo   $date = date('Y-m-d H:i:s');



	}
	/*---------------- ---------End of get_remain_time()---------------------------*/
	


    /**
     * @author                          Madhuranga Senadheera
     * Purpose of the function          POST data get as array
     * 
     */
    public function post_get_as_array($name_array = array())
    {
        $post_data_array = array();
        foreach ($name_array as $key => $value)
        {
        	var_dump($value[1]);
        	var_dump($_POST[$value]);
        	echo('<br>');
        	 $post_data_array[$value] = @$_POST[$value];
        }
 
        return $post_data_array;
    }
    /*---------------- ---------End of post_get_as_array()---------------------------*/



}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */