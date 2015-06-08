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
/* File Name     : {table}_model.php                                               */
/* Purpose       :                                                                 */
/*                                                                                 */
/*                                                                                 */
/***********************************************************************************/
class  {table}_model extends MY_Model
{
    protected $_table_name      ='{prefix_table}';
    protected $_primary_key     ='{primaryKey}';
    protected $_order_by        ='ASC';
    // protected $_primary_filter  ='';
    protected $_timestamps      =TRUE;    
    // rules
    public $rules = array(
                    {form_data}
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
        ${table} = new stdClass();
        {model_new_function_data};
        return ${table};
    }

    
}// ------------------End {table}_model --------------Class{}
//Owner : Madhuranga Senadheera



