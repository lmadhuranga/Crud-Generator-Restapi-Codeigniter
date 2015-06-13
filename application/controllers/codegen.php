<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * CI Generator
 * http://projects.keithics.com/crud-generator-for-codeigniter/ 
 * Copyright (c) 2011 Keith Levi Lumanog
 * Dual MIT and GPL licenses.
 *
 * A CI generator to easily generates CRUD CODE, feel free to improve my code or customized it the way you like.
 * as inspired by Gii of Yii Framework. Last update August 15, 2011
 */
 

class Codegen extends CI_Controller {


    function index()
    {
        $data = '';
        $this->load->library('form_validation');
		$this->load->database();
		$this->load->helper('url');
        $generated_year = date('Y');
        $generated_datetime = date('Y-m-d H:i:s');


        // load the form
        if ($this->input->post('table_data') || !$_POST)
        {
            // get table data
            $this->form_validation->set_rules('table', 'Table', 'required|trim|xss_clean|max_length[200]');

            if ($this->form_validation->run() == false)
            {
				
            }
            else
            {

                $table = $this->db->list_tables();
                $data['table'] = $table[$this->input->post('table')];
                $result = $this->db->query("SHOW FIELDS from " . $data['table']);
                $data['alias'] = $result->result();
                $result = $this->db->query("select COLUMN_NAME,DATA_TYPE, CHARACTER_MAXIMUM_LENGTH 
                    from information_schema.columns
                    where table_schema = '".$this->db->database."' AND
                          table_name = '". $data['table']."' 
                    ");

                $data['alias_2'] = $result->result();
                 
            }
            
            
            $this->load->view('codegen', $data);

        }
        // Generate the file
        else
            if ($this->input->post('generate'))
            {
                $this->load->helper('file');
                
                $all_files = array(
                    'application/config/form_validation.php',
                    'application/controllers/'.$this->input->post('controller').'.php', 
                    );

                //checking of files if they existed. comment if you want to overwrite files!
                $err = 0;
                /*** // uncomment me to allow overwrites
                foreach($all_files as $af)
                {
                    if($this->fexist($af))
                    {
                        $err++;
                        echo $this->fexist($af)."<br>";    
                    }
                }
                
                if($err > 0)
                {
					echo 'Files Exists - Generator stopped.<br>';
                    echo '<h3>Post Data Below:</h3><br>';
                    echo '<pre>';
                    print_r($_POST);
                    echo '<pre>';
                    exit;
                }
                ***/
                $rules = $this->input->post('rules');
                $label = $this->input->post('field');
                $type = $this->input->post('type');
                $length_min = $this->input->post('min');
                $length_max = $this->input->post('max_length');
                $data_type = $this->input->post('DATA_TYPE');
                
                // looping of labels and forms , for edit and add form
                foreach($label as $k => $v)
                {

                    if($type[$k][0] != 'exclude')
                    { 
                        $max_length ='';
                        $data_type ='';
                        $labels[] = $v;
                        $form_fields[] = $k;
                        if($rules[$k][0] != 'required')
                        {
                            $required = '';

                        }
                        if($rules[$k][0] != 'required')
                        {
                            $required = '';
                            $field_required = '';

                        }
                        else
                        {
                            $required = '<span class="required">*</span>';        
                            $field_required = 'required="required"';
                        }
                         
                        if (isset($length_max[$k])) {
                            $max_length = 'maxlength="'.$length_max[$k][0].'"';
                        }
                         
                    }
                    
                }// end foreach()
              
                // this will ensure that the primary key will be selected first.
                $fields_list[] = $this->input->post('primaryKey');
                // looping of rules 
                foreach($rules as $k => $v)
                {
                    $rules_array = array();
                    if($type[$k][0] != 'exclude')
                    {
                        
                        foreach($rules[$k] as $k1 => $v1)
                        {
                            if($v1)
                            {
                                $rules_array[] = $v1;
                            }
                        }
                        $form_rules = implode('|',$rules_array);
                        $form_val_data[] = "array(
                                \t'field'=>'".$k."',
                                \t'label'=>'".$label[$k]."',
                                \t'rules'=>'".$form_rules."'
                                )";
                        $controller_form_data[] = "'".$k."' => set_value('".$k."')";
                        $controller_form_editdata[] = "'".$k."' => \$this->input->post('".$k."')";
                        $fields_list[] = $k;
                        $model_new_function[] = '$'.$this->input->post('table').'->'.$k .'=""';
                        
                    }
                }
                
                
                $fields = implode(',',$fields_list);
                $fields_list_array = implode('","',$fields_list);
 
                
                $form_data = implode(','."\n\t\t\t\t\t\t\t\t",$form_val_data);
                
                $file_validation = 'application/config/form_validation.php';
                 
				$form_validation_data = "'".$this->input->post('table')."' => array(".$form_data.")";
				/*
				if(file_exists('application/config/form_validation.php'))
                {
					$form_v = file_get_contents('application/config/form_validation.php');
					$old_form =  str_replace(array('<?php','?>','$config = array(',');'),'',$form_v)."\t\t\t\t,\n\n\t\t\t\t";
					include('application/config/form_validation.php');
					
					if(isset($config[$this->input->post('table')]))
                    {
						// rules already existed , reload rules
						$form_content = str_replace('{form_validation_data}',$form_validation_data,file_get_contents('templates/form_validation.php'));	
					}
                    else
                    {
						// append new rule
						$form_content = str_replace('{form_validation_data}',$old_form.$form_validation_data,file_get_contents('templates/form_validation.php'));	
					}
				
				}
                else
                {	
                	$form_content = str_replace('{form_validation_data}',$form_validation_data,file_get_contents('templates/form_validation.php'));
				
            	}*/
               //////////////////// path list
                $c_path = 'application/controllers/'.$this->input->post('user_type').'/';
                $m_path = 'application/models/'; 
                
                // create user_type folder
                if(!is_dir($c_path)){
                    mkdir($c_path);
                }
                
               
                

                // $li_table_list = array();
                // table list
                $db_tables_list = $this->db->list_tables();
                foreach ($db_tables_list as $key => $table)
                {
                    $li_table_list[$table] = '<li><a href="<?php echo site_url('."'".$table."'".') ?> ">'.str_replace('_', ' ', ucfirst($table)).'</a></li>';
                }
                //  remove config table
                unset($li_table_list['sf_config']);

                $model_new_function_data = implode(';'."\n\t\t\t\t\t\t\t\t",$model_new_function);

                ///////////////// create model
                $model = file_get_contents('templates/model.php');
                $search = array(
                                '{table}',
                                '{prefix_table}',
                                '{C_table_name}',
                                '{model_name_1}',
                                '{primaryKey}',
                                '{form_data}',
                                '{fields_list}',
                                '{model_new_function_data}',
                                '{generated_year}',
                                '{generated_datetime}',
                                
                    );
                $replace = array(
                                $this->input->post('table'), 
                                $this->config->item('tbl_prefix').$this->input->post('table'),
                                str_replace('_', ' ', ucfirst($this->input->post('table'))),
                                ($this->input->post('table').'_model'), 
                                $this->input->post('primaryKey'),
                                $form_data,
                                $fields,
                                $model_new_function_data,
                                $generated_year,
                                $generated_datetime,
                    );


                $m_content = str_replace($search, $replace, $model);

                
                $file_model = $m_path . $this->input->post('table').'_model'. '.php';


                ///////////////// create controller
                $controller = file_get_contents('templates/controller.php');
                $search = array('{controller_name}',
                                '{view}',
                                '{table}',
                                '{validation_name}',                
                                '{data}',
                                '{edit_data}',
                                '{controller_name_l}',
                                '{primaryKey}',
                                '{fields_list}',
                                '{fields_list_array}',
                                '{model_name_1}',
                                '{C_controller_name_l}',
                                '{user_type}',
                                '{generated_year}',
                                '{generated_datetime}',
                                 );
                $replace = array(
                                ucfirst($this->input->post('controller')), 
                                $this->input->post('view'),
                                $this->input->post('table'),
                                $this->input->post('validation'),
                                implode(','."\n\t\t\t\t\t",$controller_form_data),
                                implode(','."\n\t\t\t\t\t",$controller_form_editdata),
                                $this->input->post('controller'),
                                $this->input->post('primaryKey'),
                                $fields,
                                $fields_list_array,
                                ($this->input->post('table').'_model'),
                                str_replace('_', ' ', ucfirst($this->input->post('controller'))),
                                $this->input->post('user_type'),
                                $generated_year,
                                $generated_datetime,

                            );

                $c_content = str_replace($search, $replace, $controller);

                
                $file_controller = $c_path . $this->input->post('controller') . '.php';
                

               
                
                
                
                $write_files = array(
                                'Model' => array($file_model, $m_content),
                                'Controller' => array($file_controller, $c_content) 
                                );

                foreach($write_files as $wf)
                {
                    if($this->writefile($wf[0],$wf[1]))
                    {
                        $err++;
                        echo $this->writefile($wf[0],$wf[1]);
                    }
                }        
                                                    
                if($err >0)
                {
                    exit;
                }
                else
                { 
                    
                    $data['controller'] = $c_content;

                    $data['model'] = $m_content;
                    
                    $this->load->view('done',$data);
                    //echo 'DONE! view it here '. anchor(base_url().'index.php/'.$this->input->post('controller').'/');
                }   
            }// if generate
    }
    
    function fexist($path)
    {
             if (file_exists($path))
            {
                // todo , automatically adds new validation
                return $path.' - File exists <br>';                    
            }
            else
            {
                return false;
            }        
    }
    
    function writefile($file,$content)
    {
        
        if (!write_file($file, $content))
        {
            return $file. ' - Unable to write the file';
        }
        else
        {
            return false;
        }
    }


}

/* End of file codegen.php */
/* Location: ./application/controllers/codegen.php */
