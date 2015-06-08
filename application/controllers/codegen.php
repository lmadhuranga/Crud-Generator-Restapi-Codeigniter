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
                // print_r($data['alias_2'][0]);
                // exit(); 
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
                    'application/models/codegen_model.php',
                    'application/views/'.$this->input->post('view').'_add.php',
                    'application/views/'.$this->input->post('view').'_edit.php',
                    'application/views/'.$this->input->post('view').'_list.php'
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
                        // var_dump($type[$k][0]);
                        // var_dump($data_type);
                        // exit();
                        // echo "<br>";

                        // echo "<br>";
                        // var_dump($type[$k]);
                        // echo "<br>";
                        // exit();
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
                        // if ((trim($data_type))=='int')
                        // {
                        //     $data_type = 'int';
                        // }
                        // elseif ((trim($data_type))=='varchar')
                        // {
                        //     $data_type = 'varchar';
                        // }
                        // elseif ((trim($data_type))=='datetime')
                        // {
                        //     $data_type = 'date';
                        // }
                        // elseif ((trim($data_type))=='email')
                        // {
                        //     $type[$k][0]= 'email';
                        // }

                        
                        // this will create a form for Add and Edit , quite dirty for now
                        if($type[$k][0] == 'textarea')
                        {
                             $add_form[] = '
                                        <p><label for="'.$k.'">'.$v.$required.'</label>                                
                                        <textarea class="form-control" id="'.$k.'" name="'.$k.'" '.$field_required.'><?php echo set_value(\''.$k.'\'); ?></textarea>
                                        <?php echo form_error(\''.$k.'\',\'<div class="ci_error">\',\'</div>\'); ?>
                                        </p>
                                        ';
                                        
                             $edit_form[] = '
                                        <p><label for="'.$k.'">'.$v.$required.'</label>                                
                                        <textarea class="form-control" id="'.$k.'" name="'.$k.'" '.$field_required.'><?php echo $result->'.$k.' ?></textarea>
                                        <?php echo form_error(\''.$k.'\',\'<div class="ci_error">\',\'</div>\'); ?>
                                        </p>
                                        '; 
                                    
                        }
                        // dropdown
                        else if($this->input->post($k.'default'))
                        {
                            $enum = explode(',',$this->input->post($k.'default'));
                             $add_form[] = '
                                        <p><label for="'.$k.'">'.$v.$required.'</label>                                
                                        <?php
                                            $enum = array('.$this->input->post($k.'default').'); 
                                            echo form_dropdown(\''.$k.'\', $enum); 
                                        ?>
                                        <?php echo form_error(\''.$k.'\',\'<div class="ci_error">\',\'</div>\'); ?>
                                        </p>
                                        ';
                            $edit_form[] = '
                                        <p><label for="'.$k.'">'.$v.$required.'</label>
                                        <?php
                                        $enum = array('.$this->input->post($k.'default').');                                                                    
                                        echo form_dropdown(\''.$k.'\', $enum,$result->'.$k.'); ?>
                                        <?php echo form_error(\''.$k.'\',\'<div class="ci_error">\',\'</div>\'); ?>
                                        </p>
                                        '; 
                        }
                        else
                        {
                            //input
                            $add_form[] = '
                                        <div class="form-group">
                                            <label for="'.$k.'">'.$v.$required.'</label>                              
                                            <input class="form-control"  id="'.$k.'" name="'.$k.'" type="'.$type[$k][0].'" value="<?php echo set_value(\''.$k.'\'); ?>" '.$field_required.' '.$max_length.'  />
                                            <?php echo form_error(\''.$k.'\',\'<div class="ci_error">\',\'</div>\'); ?>
                                            <p class="help-block"></p>
                                        </div>
                                        ';
                            $edit_form[] = '
                                        <div class="form-group">
                                            <label for="'.$k.'">'.$v.$required.'</label>                                
                                            <input class="form-control"  id="'.$k.'" name="'.$k.'" type="'.$type[$k][0].'" value="<?php echo $result->'.$k.' ?>" '.$field_required.' '.$max_length.' />
                                            <?php echo form_error(\''.$k.'\',\'<div class="ci_error">\',\'</div>\'); ?>
                                            <p class="help-block"></p>
                                        </div>
                                        ';
                        }                           
                         $ind_view_list[] = '
                                            <tr>
                                                <td><label>'.$v.'</label></td>
                                                <td><label><?php echo $result->'.$k.' ?></label></td>
                                            </tr>'; 
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
                
                //$search_form = array('{validation_name}','{form_val_data}');
               // $replace_form = array($this->input->post('validation'),$form_data);
				$form_validation_data = "'".$this->input->post('table')."' => array(".$form_data.")";
				
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
				
            	}
               //////////////////// path list
                $c_path = 'application/controllers/'.$this->input->post('user_type').'/';
                $m_path = 'application/models/';
                $v_path = 'application/views/'.$this->input->post('user_type').'/';
                
                // create user_type folder
                if(!is_dir($c_path)){
                    mkdir($c_path);
                }
                // create components
                if(!is_dir($v_path)){
                    mkdir($v_path);
                    $temp_v_path = $v_path.'components/';
                    
                    mkdir($temp_v_path);
                    
                }
                elseif(!is_dir($v_path.'components/'))
                {
                    mkdir($v_path.'components/');
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
                                '{user_type}'
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

                            );

                $c_content = str_replace($search, $replace, $controller);

                
                $file_controller = $c_path . $this->input->post('controller') . '.php';
                

              
                // create view/form, TODO, make this a function! and make a stop overwriting files
                
                //VIEW/LIST 
                $list_v = file_get_contents('templates/list.php');
                $search = array(
                                    '{table}',
                                    '{C_table_name}',
                                    '{user_type}',
                                );
                $replace = array(
                                    $this->input->post('table'),
                                    str_replace('_', ' ', ucfirst($this->input->post('table'))),
                                    $this->input->post('user_type'),
                                );

                $list_v = str_replace($search, $replace, $list_v);                
                
                $list_content = str_replace('{controller_name_l}',$this->input->post('controller'),$list_v);
                
 
                
                //VIEW/ indivieial 
                $ind_view_v = file_get_contents('templates/view.php');
                $ind_view_search = array(
                                        '{ind_view_list}',
                                        '{primary}',
                                        '{table}',
                                        '{C_table_name}',
                                        '{controller_name_l}',
                                        '{C_controller_name_l}',
                                        '{primaryKey_id}',
                                        '{user_type}',
                                    );
                $ind_view_replace = array(
                                        implode("\n",$ind_view_list),
                                        '<?php echo form_hidden(\''.$this->input->post('primaryKey').'\',$result->'.$this->input->post('primaryKey').') ?>',
                                        $this->input->post('table'),
                                        str_replace('_', ' ', ucfirst($this->input->post('table'))),
                                        $this->input->post('controller'),
                                        str_replace('_', ' ', ucfirst($this->input->post('controller'))),
                                        $this->input->post('primaryKey'),
                                        $this->input->post('user_type'),
                                    );
                
                $ind_view_content = str_replace($ind_view_search,$ind_view_replace,$ind_view_v);
                
 
                
                //ADD FORM
                $add_v = file_get_contents('templates/add.php');
                $search = array(
                                    '{table}',
                                    '{C_table_name}',
                                    '{C_controller_name_l}',
                                    '{user_type}',
                                );
                $replace = array(
                                    ucfirst($this->input->post('table')),
                                    str_replace('_', ' ', ucfirst($this->input->post('table'))),
                                    str_replace('_', ' ', ucfirst($this->input->post('controller'))),
                                    $this->input->post('user_type'),
                                );

                $add_v = str_replace($search, $replace, $add_v);
                
                $add_v_content = str_replace('{controller_name_l}',$this->input->post('controller'),$add_v);
                
                $add_content = str_replace('{forms_inputs}',implode("\n",$add_form),$add_v_content);
                
                //EDIT FORM
                $edit_v = file_get_contents('templates/edit.php');
                $edit_search = array(
                                        '{forms_inputs}',
                                        '{primary}',
                                        '{table}',
                                        '{C_table_name}',
                                        '{C_controller_name_l}',
                                        '{controller_name_l}',
                                        '{user_type}',
                                       
                                    );
                $edit_replace = array(
                                        implode("\n",$edit_form),
                                        
                                        '<?php if (@!empty($result->id)): ?>  <input type="hidden" value="<?php echo $result->'.$this->input->post('primaryKey').'; ?>" id="'.$this->input->post('primaryKey').'" name="'.$this->input->post('primaryKey').'">  <?php endif ?>',
                                        ucfirst($this->input->post('table')),
                                        str_replace('_', ' ', ucfirst($this->input->post('table'))),
                                        str_replace('_', ' ', ucfirst($this->input->post('controller'))),
                                        $this->input->post('controller'),
                                        $this->input->post('user_type'),
                                    );

                $edit_content = str_replace($edit_search,$edit_replace,$edit_v);
                
                $_layout_model_file = file_get_contents('templates/_layout_modal.php');
                
                //tb_table_list in _main_layout
                $_layout_main_file = file_get_contents('templates/_layout_main.php');
                $_layout_main_search = array(
                                       '{db_table_list}',
                                       '{controller_name_l}',
                                       '{user_type}',
                                    );
                $_layout_main_replace = array(
                                        implode("\n\t\t\t\t\t\t\t\t\t\t",$li_table_list),
                                        $this->input->post('controller'),
                                        $this->input->post('user_type'),
                                    );
                
                $_layout_main_content = str_replace($_layout_main_search,$_layout_main_replace,$_layout_main_file);
                
                $write_files = array(
                                'Model' => array($file_model, $m_content),
                                'Controller' => array($file_controller, $c_content),
                                'view_edit'  => array($v_path.$this->input->post('view').'_edit.php', $edit_content),
                                'view_list'  => array($v_path.$this->input->post('view').'_list.php', $list_content),
                                'view_add'   => array($v_path.$this->input->post('view').'_add.php', $add_content),
                                'view_ind'   => array($v_path.$this->input->post('view').'_view.php', $ind_view_content),
                                '_layout_main_content'   => array($v_path.'_layout_main.php', $_layout_main_content),
                                '_layout_model_file'   => array($v_path.'_layout_model.php', $_layout_model_file),
                                'page_head'   => array($v_path.'components/page_head.php', file_get_contents('templates/components/page_head.php')),
                                'page_tail'   => array($v_path.'components/page_tail.php', file_get_contents('templates/components/page_tail.php')),
                               //'form_validation'  => array($file_validation, $form_content) 
                               
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
                    $data['list_content'] = $list_content;
                    
                    $data['add_content'] = $add_content;
                                        
                    $data['edit_content'] = $edit_content;
                    
                    $data['controller'] = $c_content;
                    
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
