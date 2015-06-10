<?php
/**
*
*
* @copyright  2015
* @license    None
* @version    1.0
* @link       None 
*
**/     
        
/***********************************************************************************/
/*                                                                                 */
/* File Name     : user_model.php                                               */
/* Purpose       :                                                                 */
/*                                                                                 */
/*                                                                                 */
/***********************************************************************************/
class  user_model extends MY_Model
{
    protected $_table_name      ='tbl_user';
    protected $_primary_key     ='id';
    protected $_order_by        ='id';
    // protected $_primary_filter  ='';
    protected $_timestamps      =TRUE;    
    // rules
    public $rules = array(
                        array(
                            'field'=>'fname',
                            'label'=>'Fist Name',
                            'rules'=>'required|trim|xss_clean|max_length[45]'
                        ),
                        array(
                            'field'=>'lname',
                            'label'=>'Last Name',
                            'rules'=>'required|trim|xss_clean|max_length[45]'
                        ),
                        array(
                            'field'=>'email',
                            'label'=>'Email',
                            'rules'=>'required|trim|valid_email|xss_clean|max_length[45]'
                        ),
                        array(
                            'field'=>'password',
                            'label'=>'Password',
                            'rules'=>'required|trim|xss_clean|max_length[250]'
                        ),
                        array(
                            'field'=>'img',
                            'label'=>'Image',
                            'rules'=>'trim|xss_clean|max_length[45]'
                        ),
                        array(
                            'field'=>'user_type',
                            'label'=>'User Role',
                            'rules'=>'trim|xss_clean|max_length[11]'
                        ),
                        array(
                            'field'=>'enable',
                            'label'=>'Enable',
                            'rules'=>'trim|xss_clean|max_length[1]'
                        )
        );
    // login rules
    public $login_rules = array( 
                        array(
                            'field'=>'email',
                            'label'=>'Email',
                            'rules'=>'required|trim|valid_email|xss_clean|max_length[45]'
                        ),
                        array(
                            'field'=>'password',
                            'label'=>'Password',
                            'rules'=>'required|trim|xss_clean|max_length[250]'
                        )
        );

    /*********************Construct()****************************/
    function __construct()
    {
        parent::__construct();
    }

    function count(){
        return $this->db->count_all($this->_table_name);
    }

    public function get_new(){
        $user = new stdClass();
        $user->fname="";
								$user->lname="";
								$user->email="";
								$user->password="";
								$user->img="";
								$user->enable="";
        return $user;
    }

        /**
     * @author                          Madhuranga Senadheera
     * Purpose of the function           check currnt user is logged in
     * 
    */
    public function is_logged()
    { 
        return (bool) $this->session->userdata('loggedin');
    }
    /*---------------- ---------End of functionName()---------------------------*/
    

    
    /**
     * @author                          Madhuranga Senadheera
     * Purpose of the function           check email is exist
     * 
    */
    public function is_email()
    {
        if (count(get_by(array('email'=>$email))))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    /*---------------- ---------End of is_email()---------------------------*/
       

    /**
     * @author                          Madhuranga Senadheera
     * Purpose of the function          Get the user tyep
     * 
    */
    public function get_user_type()
    {
        return  $this->session->userdata('user_type');
    }
    /*---------------- ---------End of get_user_type()---------------------------*/
   

    /**
     * @author                          Madhuranga Senadheera
     * Purpose of the function           
     * 
    */
    public function get_fname()
    {
        return  $this->session->userdata('fname');
    }
    /*---------------- ---------End of get_fname()---------------------------*/
      

    /**
     * @author                          Madhuranga Senadheera
     * Purpose of the function          
     * 
    */
    public function get_lname()
    {
        return  $this->session->userdata('lname');
    }
    /*---------------- ---------End of get_lname()---------------------------*/
   

    
    /**
     * @author                          Madhuranga Senadheera
     * Purpose of the function          return current user id
     * 
    */
    public function get_current_user_id()
    {
        return  $this->session->userdata('user_id');
    }
    /*---------------- ---------End of get_current_user_id()---------------------------*/
    

   
   /**
    * @author                          Madhuranga Senadheera
    * Purpose of the function         return current user email
    * 
    */
   public function  get_current_user_email()
   {
       return  $this->session->userdata('email');
   }
   /*---------------- ---------End of get_current_user_email()---------------------------*/
   

    /**
     * @author                          Madhuranga Senadheera
     * Purpose of the function         admin user login function
     * 
    */
    public function user_login($email,$password)
    { 
        
        
        $user_data = $this->user_model->get_by(array('email'=>$email,'password'=>$this->hash_password($password)), array('id', 'email','fname','lname','enable','user_type'),NULL , TRUE); 
         // echo $this->db->last_query(); exit('users_model_file ln:'.__LINE__);
        // user name and password corect
        if (sizeof($user_data)==1) 
        {  
            
            if($user_data->enable=='1')
            {
                // set session data 
                $session_data = array(
                                    'user_id'=> $user_data->id,
                                    'email'  => $user_data->email,
                                    'fname'  => $user_data->fname,
                                    'user_type'  => $user_data->user_type,
                                    'lname'  => $user_data->lname, 
                                    'loggedin'  => TRUE,
                                ); 
                
                $this->session->set_userdata($session_data); 
                return array('status'=>'success','user'=>$user_data);
            }
            else
            {
                return array('status'=>'error','msg'=>'Inactive User','user'=>$user_data);
            }

        }
        else
        {
            return array('status'=>'error','msg'=>'Email and Password Not match','user'=>$user_data);
        }
        
    }
    /*---------------- ---------End of user_login()---------------------------*/
    


    /**
     * @author                          Madhuranga Senadheera
     * Purpose of the function          Hass password
     * 
     */
    public function hash_password($password)
    {
        return  md5($password). $this->config->item('encryption_key');
    }
    /*---------------- ---------End of hash_password()---------------------------*/


    /**
     * @author                          Madhuranga Senadheera
     * Purpose of the function          Log out the user
     * @variable                         : type
     * @return                             boolean 
     */
    public function user_logout()
    {
         $array_items = array(  'user_id'=>'',
                                'email'=>'',
                                'fname'=>'',
                                'lname'=>'',
                                'user_type'=>'',
                                'loggedin'=>''
                         );

        return (bool) $this->session->unset_userdata($array_items);
        
    }
    /*---------------End of logout()---------------*/
    


    
}// ------------------End user_model --------------Class{}
//Owner : Madhuranga Senadheera



