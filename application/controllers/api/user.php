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
/* File Name     : User.php                                           */
/* Purpose       :                                                                 */
/*                                                                                 */
/*                                                                                 */
/***********************************************************************************/
class User extends REST_Controller
{
    
    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');        
        // $this->load->helper(array('form','url'));
        $this->load->model('user_model');
    }   
    
    /*************************Start Function index()***********************************/
    //Owner : Madhuranga Senadheera
    //
    //@ type :
    //#return type :
    function data_get()
    {   
        // return araray ini
        $json_return_array = array();

        // If id send send single record
        $id = $this->input->get('id'); 
        if($id!=false)
        {
            $user = $this->user_model->get_by(array('id'=>$id),'*');
            $json_return_array = end($user);

            $user = $this->user_model->get_by(array('id'=>$id),'*');
            if (sizeof($user)>0)
            {
                $json_return_array['data']      = end($user);
                $json_return_array['status']    = 'success';
            }
            else
            {
                // get all record
                $users_list = $this->user_model->get_by(array('enable'=>'1'),'id,fname,lname,email,img,enable');   
                if (sizeof($users_list)>0)
                {
                    $json_return_array['data'] = $users_list;
                    $json_return_array['status'] = 'success';
                }
                else
                {
                    // no data 
                    $json_return_array['msg'] = 'No Data';
                    $json_return_array['status'] = 'no_data';
                }
            }
        }
        else
        {
            // send all record
            $json_return_array = $this->user_model->get_by(array(),'id,fname,lname,email,img,enable');   
        } 

        // response
        $this->response($json_return_array, 200); 
    }//Function End data_get()---------------------------------------------------FUNEND()

    
    
    /*************************Start Function edit()***********************************/
    //Owner : Madhuranga Senadheera
    //
    //@ type :
    //#return type :
    function data_put($id=NULL)
    {
        // return araray ini
        $json_return_array = array();  
        // load helpers 
        $this->load->library('form_validation');   
        // set validation
 
        $this->form_validation->set_rules($this->user_model->rules);  
        if ($this->form_validation->run()==false)
        {
            // validation error
            $json_return_array['msg']       = validation_errors(); 
            $json_return_array['status']    = 'error'; 
            
        }
        else
        {
            $form_data = $this->post_get_as_array(array('id,fname,lname,email,password,img,enable')); 
    
            if ($this->user_model->save($form_data,$form_data['id'])) {
    
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
    public function data_post()
    { 
        // return araray ini
        $json_return_array = array();
        // load helpers 
        $this->load->library('form_validation'); 
        // set validation
        $this->form_validation->set_rules($this->user_model->rules);
        if ($this->form_validation->run()==false)
        {
            // validation error
            $json_return_array['msg']       = validation_errors(); 
            $json_return_array['status']    = 'error'; 
            
        }
        else
        {
            $form_data = $this->post_get_as_array(array('id,fname,lname,email,password,img,user_type,enable')); 
    
            if ($this->user_model->save($form_data)) {
    
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
    public function data_delete()
    {   
        // return araray ini
        $json_return_array = array();  
        // load helpers 
        $this->load->library('form_validation');   
        // set validation
        $_POST['id']  = $_GET['id'];
        $this->form_validation->set_rules($this->user_model->rules);
        if ($this->form_validation->run()==false)
        {
            // validation error
            $json_return_array['msg']       = validation_errors(); 
            $json_return_array['status']    = 'error'; 
            
        }
        else
        {
            
            $form_data = $this->post_get_as_array(array('id')); 
            if ($this->user_model->delete($form_data['id'])) {
    
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


    /**
     * @author                          Madhuranga Senadheera
     * Purpose of the function          Description
     * @variable                         : type
     * @return                             return_type 
     */
    public function login_post()
    {
        $_POST['email'] = 'user1@gmail.com';
        $_POST['password'] = 'aaa';
         // return araray ini
        $json_return_array = array();
        // load helpers 
        $this->load->library('form_validation'); 
        // set validation
        $this->form_validation->set_rules($this->user_model->login_rules);
        if ($this->form_validation->run()==false)
        {
            // validation error
            $json_return_array['msg']       = 'Operation fail';
            $json_return_array['status']    = 'error';
            $json_return_array['statuss']    = validation_errors(); 
            
        }
        else
        {
            $form_data = $this->post_get_as_array(array('email','password')); 
            // user login
            $json_return_array = $this->user_model->user_login($form_data['email'],$form_data['password']);
        }
    
        $this->response($json_return_array, 200); 
    }
    /*---------------End of login_post()---------------*/

 
    /**
     * @author                          Madhuranga Senadheera
     * Purpose of the function          Description
     * @variable                         : type
     * @return                             return_type 
     */
    public function logout_get()
    {
        // user log out 
        $this->user_model->user_logout();
        
        $json_return_array['msg']       = 'Logout successfull';
        $json_return_array['status']    = 'success'; 

        $this->response($json_return_array, 200); 
    }
    /*---------------End of logout_post()---------------*/

}

/* End of file user.php */
/* Location: ./system/application/controllers/user.php */