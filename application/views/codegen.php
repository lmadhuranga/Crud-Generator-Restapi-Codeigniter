<form action="<?php echo current_url();?>" method="post">
<label style="font-weight: bold; color:red;">1. Set the table prefix in Config file ./application/config/site_config.php -> tbl_prefix</label>
<p style="font-weight: bold; color:red;">Database tables/data and files will be reverted every hour</p>
<p>MySQL Table
<?php
$db_tables = $this->db->list_tables();
echo form_dropdown('table',$db_tables);
?>
<input type="submit" name="table_data" value="Get Table Data" /></p>
</form>
<form action="<?php echo current_url();?>" method="post">
<?php
if(isset($alias))
{
?>
<input type="hidden" name="table" value="<?php echo rm_tbl($table) ?>" />
<table>
<tr>
    <td>
        <p>Controller Name: <input type="text" name="controller" value="<?php echo(rm_tbl($table)) ?>" />        
        View Name: <input type="text" name="view" value="<?php echo(rm_tbl($table)) ?>" />
        Validation Name: <input type="text" name="validation" value="<?php echo rm_tbl($table) ?>" />
        User type: <input type="text" name="user_type" value="api" />
         <input type="submit" name="generate" value="Generate" /></p>    
    </td>
</tr>
<tr>
    <td>
    <h3>Table Data</h3>
    <?php
    //p($alias);
    // var_dump($alias);
    // echo '<br>';
    // var_dump($alias_2);
    // exit();
    
    $type = array(
                'exclude'  =>'Do not include',
                'text'     => 'text input',
                'number'  => 'Numbers only',
                'date'     => 'date input',
                'email'    => 'email input',
                'password' => 'password',
                'textarea' => 'textarea' , 
                'dropdown' => 'dropdown'
                );
 
   $sel = '';
    if(isset($alias)){

        foreach($alias as $key => $a)
        {
            $email_default = FALSE;
            echo '<p> Field: '.$a->Field.'<br>Label:'.form_input('field['.$a->Field.']', str_replace("_"," ",ucfirst($a->Field))).' '.$a->Type;
            
            echo ' max :'.form_input('max_length['.$a->Field.'][]',(empty($alias_2[$key]->CHARACTER_MAXIMUM_LENGTH)&&($alias_2[$key]->DATA_TYPE=='int'))? 11 :  (!is_null($alias_2[$key]->CHARACTER_MAXIMUM_LENGTH)? $alias_2[$key]->CHARACTER_MAXIMUM_LENGTH:'')) ;
           
            if(strpos($a->Type,'enum') !== false)
            {
                echo ' <br>Enum Values (CSV): <input size="50" type="text" value="'.htmlspecialchars ("'0'=>'Value','1'=>'Another Value'").'" name="'.$a->Field.'default">';
                $sel = 'dropdown';
            }
            elseif($a->Key == 'PRI')
            {
                // exit('hi');
                $sel = 'exclude';
                echo form_hidden('primaryKey',$a->Field);
            }
            elseif(strpos($a->Type,'int') !== false)
            {
                $sel = 'number';
            }
            elseif(strpos($a->Type,'blob') !== false || strpos($a->Type,'text') !== false)
            {
                $sel = 'textarea';
            }
            // else if($a->Key == 'PRI')
            // {
            //     $sel = 'exclude';
            //     echo form_hidden('primaryKey',$a->Field);
            // }
            // else if(in_array('password',explode("_",$a->Field)))
            else if(strpos($a->Field,'password') !== false)
            {
                $sel = 'password';
            }
            else if(strpos($a->Field,'email') !== false)
            {
                $email_default = TRUE;
                $alias_2[$key]->DATA_TYPE = 'email';
                $sel = 'email';
            }
            else if(strpos($a->Field,'created') !== false)
            {
                $sel = 'exclude';
                $alias_2[$key]->DATA_TYPE = 'date';
            }
            else if(strpos($a->Field,'modified') !== false)
            {
                $sel = 'exclude';
                $alias_2[$key]->DATA_TYPE = 'date';
            }
            else{
                
                 $sel = 'text';
            }
            // echo $alias_2[$key]->COLUMN_NAME. " : ".$alias_2[$key]->DATA_TYPE. '<br>';

            // if((trim($alias_2[$key]->DATA_TYPE))=='int')
            // {
            //     $input_type='int';
            // }
            // elseif((trim($alias_2[$key]->DATA_TYPE))=='varchar')
            // {
            //     $input_type='varchar';
            // }
            // elseif((trim($alias_2[$key]->DATA_TYPE))=='datetime')
            // {
            //     $input_type='datetime';
            // }


            // echo ' max :'.form_input('rules['.$a->Field.'][]',(empty($alias_2[$key]->CHARACTER_MAXIMUM_LENGTH)&&($alias_2[$key]->DATA_TYPE=='int'))? 11 :  (!is_null($alias_2[$key]->CHARACTER_MAXIMUM_LENGTH)? $alias_2[$key]->CHARACTER_MAXIMUM_LENGTH:'')) ;

            // var_dump($alias_2[0]);
            // exit();
             echo '<br> Type::'.form_dropdown('type['.$a->Field.'][]', $type,$sel);
            echo '<br>';
            // if (!empty($input_type))
            // {
                // echo ' DATA_TYPE:'.form_input('DATA_TYPE['.$a->Field.']',$alias_2[$key]->DATA_TYPE);
            // }
            // echo ' max :'.form_input('rules['.$a->Field.'][]',(empty($alias_2[$key]->CHARACTER_MAXIMUM_LENGTH)&&($alias_2[$key]->DATA_TYPE=='int'))? 'max_length[11]' : !empty($alias_2[$key]->CHARACTER_MAXIMUM_LENGTH)?('max_length['.$alias_2[$key]->CHARACTER_MAXIMUM_LENGTH.']') : '' );
            echo '<br>';
            echo form_checkbox('rules['.$a->Field.'][]', 'required', ((strtolower($a->Null)=='no') ? TRUE : FALSE) ) . ' required :: ';
            echo form_checkbox('rules['.$a->Field.'][]', 'trim', TRUE) . ' trim :: ';
            echo form_checkbox('rules['.$a->Field.'][]', 'valid_email', $email_default) . ' email :: ';
            echo form_checkbox('rules['.$a->Field.'][]', 'integer', (($alias_2[$key]->DATA_TYPE=='int') ? TRUE : FALSE)) . 'integer ::';
            echo form_checkbox('rules['.$a->Field.'][]', 'xss_clean', TRUE) . 'xss_clean ::';
            echo ' max :'.form_hidden('rules['.$a->Field.'][]',(empty($alias_2[$key]->CHARACTER_MAXIMUM_LENGTH)&&($alias_2[$key]->DATA_TYPE=='int'))? 'max_length[11]' :  (!is_null($alias_2[$key]->CHARACTER_MAXIMUM_LENGTH)?'max_length['.$alias_2[$key]->CHARACTER_MAXIMUM_LENGTH.']':'')) ;
            //echo ':: custom rule '. form_input('rules['.$a->Field.'][]', '');
            echo '</p>';
            
        }
    }
    ?>
    </td>
</tr>
</table>
<?php } ?>
</form>
 