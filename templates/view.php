<div class="modal-header">
	<h3>View {C_table_name} </h3>
</div>
<div class="modal-body">
	<table class="table ">
		{ind_view_list}
	</table>
	
</div>
<div class="modal-footer" class="clearfix">
	<!-- left footer -->
	<div class="pull-left">
		<?php echo anchor((site_url('{user_type}/{controller_name_l}/edit/'.$result->{primaryKey_id}))  , "Edit", array('class'=>'btn btn-primary')); ?>
		<?php echo anchor((site_url('{user_type}/{controller_name_l}')) , '{C_controller_name_l} list', array('class'=>'btn btn-default')); ?>
	</div>
	<!-- right footer  -->
	<div class="span4 pull-right"><?php echo anchor(site_url(), ' &copy; '. date('Y').' '.$site_name); ?></div>
</div>
