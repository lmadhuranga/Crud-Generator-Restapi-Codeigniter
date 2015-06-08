<h2>{C_table_name}</h2>

<?php
echo anchor(site_url("{user_type}/{controller_name_l}/edit/"),'Add a {C_table_name}','class="btn btn-primary"');
echo "<br>";
echo "<br>";
if(!$results){
	echo '<h1>{C_table_name} No Data</h1>';
	exit;
}

$header = array_keys($results[0]);

for($i=0;$i<count($results);$i++)
{
    $id = array_values($results[$i]);
    $results[$i]['View']     = anchor(site_url('{user_type}/{controller_name_l}/view/'.$id[0]),"<i class='glyphicon glyphicon-folder-open'></i>",'title="View"');
    $results[$i]['Edit']     = anchor(site_url('{user_type}/{controller_name_l}/edit/'.$id[0]),"<i class='glyphicon glyphicon-pencil'></i>",'title="Edit"');
    $results[$i]['Delete']   = anchor(site_url('{user_type}/{controller_name_l}/delete/'.$id[0]),"<i class='glyphicon glyphicon-remove'></i>",array('onClick'=>'return deletechecked(\' '.site_url('{user_type}/{controller_name_l}/delete/'.$id[0]).' \')', 'title'=>'Delete'));
                                // anchor(site_url('{controller_name_l}/delete/'.$id[0]),'Delete',array('onClick'=>'return deletechecked(\' '.base_url().'index.php/{controller_name_l}/delete/'.$id[0].' \')'));
	array_shift($results[$i]);                        
}
        
$clean_header = clean_header($header);
array_shift($clean_header);
$tmpl = array ( 'table_open'  => '<table class="table table-hover table-bordered">');

$this->table->set_template($tmpl); 
$this->table->set_heading($clean_header); 

// view
echo $this->table->generate($results); 
echo $this->pagination->create_links();
?>
<script type="text/javascript">
    function deletechecked(link)
    {
        var answer = confirm('Delete item?')
        if (answer)
        {
            window.location = link;
        }
        
        return false;  
    }

</script>