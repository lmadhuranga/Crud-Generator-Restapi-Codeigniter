<?php 
/**
*
*
* @copyright  2015
* @license    None
* @version    1.0
* @link       None
* @since      Class available since Release 1.0
*
**/     
        
/***********************************************************************************/
/*                                                                                 */
/* File Name     : {controller_name}.php                                           */
/* Purpose       :                                                                 */
/*                                                                                 */
/*                                                                                 */
/***********************************************************************************/
class {controller_name} extends REST_Controller
{
    
    function __construct()
    {
        parent::__construct();
		$this->load->library('form_validation');		
		// $this->load->helper(array('form','url'));
		$this->load->model('{model_name_1}');
	}	
	
    /*************************Start Function index_get()***********************************/
    //  Owner : Madhuranga Senadheera 
    //  Description
    //  If not set @id list all record, If  set @id list a single record for id
    //  @id type :int
    //  #return type : json
	function index_get()
	{   
        // init return array()
        $json_return_array = array();

        // If id send send single record
        $id = $this->get('id');  
        if($id!=false)
        {
            // get the record for id
            ${controller_name_l} = $this->{model_name_1}->get_by(array('id'=>$id),'*',TRUE);
            // any record exist
            if (sizeof(${controller_name_l})>0)
            {
                $json_return_array['data']      = (${controller_name_l});
                $json_return_array['status']    = 'success';
            }
            else
            {
                // not found any record
                $json_return_array['msg'] = 'Not Found {controller_name_l}';
                $json_return_array['status'] = 'no_data';
            }
        }
        else
        {
            // send all record
            $json_return_array = $this->{model_name_1}->get_by(array(),'{fields_list}');   
        } 

        // end, reponce
        $this->response($json_return_array, 200); 
        
	}//Function End index_get()---------------------------------------------------FUNEND()

	
    
    /*************************Start Function index_put()***********************************/
    //  Owner : Madhuranga Senadheera 
    //  Description
    //  Update existing record
    //  @ type :
    //  #return type :
    function index_put($id=NULL)
    {
        // init return array()
        $json_return_array = array();  
        // load helpers 
        $this->load->library('form_validation');   
        // set validation 
        $this->form_validation->set_rules($this->{model_name_1}->rules);  
        // check validation
        if ($this->form_validation->run()==false)
        {
            // validation error
            $json_return_array['msg']       = validation_errors();;
            $json_return_array['status']    = 'error'; 
            
        }
        else
        {
            // get data from form
            $form_data = $this->post_get_as_array(array('{fields_list}')); 
            // update database
            if ($this->{model_name_1}->save($form_data,$form_data['id']))
            {
                // set success msg
                $json_return_array['msg']       = 'System update success';
                $json_return_array['status']    = 'success'; 
            }
            else
            {
                // set faile msg
                $json_return_array['msg']       = 'System update failier';
                $json_return_array['status']    = 'error'; 
            }
        }
        // end, reponce
        $this->response($json_return_array, 200);
    
    }//Function End index_put()---------------------------------------------------FUNEND()
    


    /*************************Start Function input_post()********************************/
    //  Owner: Madhuranga Senadheera 
    //  Description
    //  @ type :
    //  #return type :
    public function index_post()
    { 
        // init return array()
        $json_return_array = array();
        // load helpers 
        $this->load->library('form_validation'); 
        // set validation
        $this->form_validation->set_rules($this->{model_name_1}->rules);
        // check validation
        if ($this->form_validation->run()==false)
        {
            // set validation error
            $json_return_array['msg']       = validation_errors();
            $json_return_array['status']    = 'error'; 
            
        }
        else
        {
            // get data from form
            $form_data = $this->post_get_as_array(array('{fields_list}'));
            // add to database
            if ($this->{model_name_1}->save($form_data))
            {    
                // set success msg
                $json_return_array['msg']       = 'System update success';
                $json_return_array['status']    = 'success';    
            }
            else
            { 
                // set faile msg
                $json_return_array['msg']       = 'System update failier';
                $json_return_array['status']    = 'error'; 
            }
        }
        // end, reponce
        $this->response($json_return_array, 200); 
    
    }//Function End input_post()---------------------------------------------------FUNEND()




    /*************************Start Function index_delete()*******************************/
    //  Owner: Madhuranga Senadheera 
    //  Description
    //  @ type :
    //  #return type :
    public function index_delete()
    {   
        // init return array()
        $json_return_array = array();  
        // load helpers 
        $this->load->library('form_validation');   
        // set validation rules
        $this->form_validation->set_rules($this->{model_name_1}->rules);
        // convert get reques to post (CI not validating GET request)
        $_POST['id']  = $_GET['id'];
        // check validation
        if ($this->form_validation->run()==false)
        {
            // set validation error
            $json_return_array['msg']       = validation_errors(); 
            $json_return_array['status']    = 'error';
            
        }
        else
        {
            // get data from form
            $form_data = $this->post_get_as_array(array('id'));
            // delete the record
            if ($this->{model_name_1}->delete($form_data['id']))
            {
                // set success msg
                $json_return_array['msg']       = 'Deleted';
                $json_return_array['status']    = 'success'; 
    
            }
            else
            {
                // set faile msg
                $json_return_array['msg']       = 'System update failier';
                $json_return_array['status']    = 'error'; 
            }
        }
        // end, reponce
        $this->response($json_return_array, 200); 

    }//Function End index_delete()-----------------------------------------------FUNEND()


}

/* End of file {controller_name_l}.php */
/* Location: ./application/api/controllers/{controller_name}.php */