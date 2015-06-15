<?php 

class MY_Model extends CI_Model
{

	protected $_table_name ='';
	protected $_primary_key ='';
	protected $_primary_filter ='intval';
	protected $_order_by ='';
    protected $_timestamps =FALSE;
	public     $rules = array();

/*********************Construct()**************************************/
	function __construct()
    {
    	parent::__construct();
    }


    /*************************Start Function get()***********************************/
    //Owner : Madhuranga Senadheera
    //
    //@ type :
    //#return type :
    public function get($fields = NULL, $id = NULL, $single = FALSE, $asOrder="ASC", $perpage=0, $start=0, $array='array')
    {
        if ($fields !==NULL)
        {
            $this->db->select($fields);
        }
    	if($id != NULL)
    	{
    		$filter = $this->_primary_filter;
    		// $id = $filter($id);
    		$this->db->where($this->_primary_key, $id);
    		$method =  'row';
    	}
    	elseif($single == TRUE)
    	{
           
    		$method = 'row';
    	}
    	else
    	{
    		$method = 'result';
    	}

    	if($this->_order_by!='')
    	{
    		$this->db->order_by($this->_order_by,$asOrder);
    	}
        if($start!=0) $this->db->limit($perpage,$start);
    	return $this->db->get($this->_table_name)->$method($array); 
    	
    }//Function End get()---------------------------------------------------FUNEND()


    /*************************Start Function get_by()***********************************/
    //Owner : Madhuranga Senadheera
    //
    //@ type :
    //#return type :
    public function get_by($where='', $fields = NULL, $id = NULL, $single = FALSE, $asOrder="ASC", $perpage=0, $start=0, $array='array')
    {
    	$this->db->where($where);
    	return $this->get($fields, $id, $single, $asOrder);
    }//Function End get_by()---------------------------------------------------FUNEND()


    /*************************Start Function save()***********************************/
    //Owner : Madhuranga Senadheera
    //
    //@ type :
    //#return type :
    public function save($data, $id = NULL)
    {
    	// set timestamp
    	$this->_timestamps = TRUE;
    	$now = date('Y-m-d H:i:s');
    	$id || $data['created'] = $now;
    	$data['modified'] = $now; 
    	// insert
    	if($id == NULL)
    	{            
    		$this->db->set($data);
    		!isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
    		$this->db->insert($this->_table_name);
    		$id = $this->db->insert_id();
    	}
    	// update
    	else
    	{
    		// $filter = $this->_primary_filter;
    		// $id = $filter($id);
    		$this->db->set($data);
    		$this->db->where($this->_primary_key, $id);
    		$this->db->update($this->_table_name);
    	}

    	return $id;
    }//Function End save()---------------------------------------------------FUNEND()


    /*************************Start Function delete()***********************************/
    //Owner : Madhuranga Senadheera
    //
    //@ type :
    //#return type :
    public function delete($id=NULL)
    {
    	$filter =  $this->_primary_filter;
    	$id = $filter($id);

    	if(!$id)
    	{
    		return FALSE;
    	}
    	else
    	{

    		$this->db->where(array($this->_primary_key=>$id));
    		$this->db->limit(1);
    		$this->db->delete($this->_table_name);
    	}
    	
    }//Function End delete()---------------------------------------------------FUNEND()

    /*************************Start Function array_from_post()***********************************/
    //Owner : Madhuranga Senadheera
    //
    //@ type :
    //#return type :
    public function array_from_post($fields)
    {
        $data = array();       
        foreach ($fields as $field) {
            $data[$field] = $this->input->post($field);
        }       
        return $data;
    }//Function End array_from_post()---------------------------------------------------FUNEND()

    
}// End MY_Model --------------Class{}
//Owner : Madhuranga Senadheera
 ?>
