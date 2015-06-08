<?php $this->load->view('{user_type}/components/page_head',array('meta_title'=>$meta_title,'site_name'=>$site_name)); ?>
<body>
    <div class="navbar navbar-static-top navbar-inverse">
	    <div class="navbar-inner">
		    <a class="brand" href="<?php echo site_url('{user_type}/dashboard'); ?>"><?php echo $meta_title; ?></a>
		    <ul class="nav">
			    <li class="active"><a href="<?php echo site_url('{user_type}/dashboard'); ?>">Dashboard</a></li>
			    <li><?php echo anchor('{user_type}/page', 'pages'); ?></li>
			    <li><?php echo anchor('{user_type}/user', 'users'); ?></li>
		    </ul>
	    </div>
    </div>

	<div class="container">
		<div class="row">
			<!-- Main column -->
			<div class="span9">
				<div class="container-fluid">
					<div class="row-fluid">
						<div class="span3">
								<div class="conatainer">
									<ul class="nav nav-list bs-docs-sidenav affix">
										{db_table_list}
									</ul>
								</div>
						</div>
						<div class="span9">
								<?php $this->load->view($sub_view,$results); ?>
						</div>
					</div>
				</div>
			</div>
			<!-- Sidebar -->
			<div class="span3">
				<section>
					<?php echo mailto('joost@codeigniter.tv', '<i class="icon-user"></i> joost@codeigniter.tv'); ?><br>
					<?php echo anchor('{user_type}/user/logout', '<i class="icon-off"></i> logout'); ?>
				</section>
			</div>
		</div>
	</div>

<?php $this->load->view('{user_type}/components/page_tail',array('meta_title'=>$meta_title, 'site_name'=>$site_name)); ?>