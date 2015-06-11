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
	
    /*************************Start Function index()***********************************/
    //Owner : Madhuranga Senadheera
    //
    //@ type :
    //#return type :
	function index_get()
	{   
        // return araray ini
        $json_return_array = array();

        // If id send send single record
        $id = $this->input->get('id'); 
        if($id!=false)
        {
            ${controller_name_l} = $this->{model_name_1}->get_by(array('id'=>$id),'*',TRUE);
            if (sizeof(${controller_name_l})>0)
            {
                $json_return_array['data']      = (${controller_name_l});
                $json_return_array['status']    = 'success';
            }
            else
            {
                // no data 
                $json_return_array['msg'] = 'Not Found ${controller_name_l}';
                $json_return_array['status'] = 'no_data';
            }
        }
        else
        {
            // send all record
            $json_return_array = $this->{model_name_1}->get_by(array(),'{fields_list}');   
        } 

        // response
        $this->response($json_return_array, 200); 
        
	}//Function End data_get()---------------------------------------------------FUNEND()

	
    
    /*************************Start Function edit()***********************************/
    //Owner : Madhuranga Senadheera
    //
    //@ type :
    //#return type :
    function index_put($id=NULL)
    {
        // return araray ini
        $json_return_array = array();  
        // load helpers 
        $this->load->library('form_validation');   
        // set validation
 
        $this->form_validation->set_rules($this->{model_name_1}->rules);  
        if ($this->form_validation->run()==false)
        {
            // validation error
            $json_return_array['msg']       = 'Operation fail';
            $json_return_array['status']    = 'error';
            $json_return_array['statuss']    = validation_errors(); 
            
        }
        else
        {
            $form_data = $this->post_get_as_array(array('{fields_list}')); 
    
            if ($this->{model_name_1}->save($form_data,$form_data['id'])) {
    
                $json_return_array['msg']       = 'System update success';
                $json_return_array['status']    = 'success'; 
    
            }
            else {
                $json_return_array['msg']       = 'System update failier';
                $json_return_array['status']    = 'error'; 
            }
        }
        $this->response($json_return_array, 200);
    
    }//Function End data_put()---------------------------------------------------FUNEND()
    


    /*************************Start Function input_post()********************************/
    //  Owner: Madhuranga Senadheera
    //  Description
    //  @ type :
    //  #return type :
    public function index_post()
    { 
        // return araray ini
        $json_return_array = array();
        // load helpers 
        $this->load->library('form_validation'); 
        // set validation
        $this->form_validation->set_rules($this->{model_name_1}->rules);
        if ($this->form_validation->run()==false)
        {
            // validation error
            $json_return_array['msg']       = 'Operation fail';
            $json_return_array['status']    = 'error';
            $json_return_array['statuss']    = validation_errors(); 
            
        }
        else
        {
            $form_data = $this->post_get_as_array(array('{fields_list}')); 
    
            if ($this->{model_name_1}->save($form_data)) {
    
                $json_return_array['msg']       = 'System update success';
                $json_return_array['status']    = 'success'; 
    
            }
            else {
                $json_return_array['msg']       = 'System update failier';
                $json_return_array['status']    = 'error'; 
            }
        }
    
        $this->response($json_return_array, 200); 
    
    }//Function End data_post()---------------------------------------------------FUNEND()




    /*************************Start Function index_delete()*******************************/
    //  Owner                                       : Madhuranga Senadheera
    //  Description
    //  @ type :
    //  #return type :
    public function index_delete()
    {   
        // return araray ini
        $json_return_array = array();  
        // load helpers 
        $this->load->library('form_validation');   
        // set validation
        $_POST['id']  = $_GET['id'];
        $this->form_validation->set_rules($this->{model_name_1}->rules);
        if ($this->form_validation->run()==false)
        {
            // validation error
            $json_return_array['msg']       = 'Operation fail';
            $json_return_array['status']    = 'error';
            $json_return_array['statuss']    = validation_errors(); 
            
        }
        else
        {
            
            $form_data = $this->post_get_as_array(array('id')); 
            if ($this->{model_name_1}->delete($form_data['id'])) {
    
                $json_return_array['msg']       = 'Deleted';
                $json_return_array['status']    = 'success'; 
    
            }
            else {
                $json_return_array['msg']       = 'System update failier';
                $json_return_array['status']    = 'error'; 
            }
        }
        $this->response($json_return_array, 200); 

    }//Function End data_delete()---------------------------------------------------FUNEND()


}

/* End of file {controller_name_l}.php */
/* Location: ./system/application/controllers/{controller_name_l}.php */