<?php $this->load->view('test/components/page_head',array('meta_title'=>$meta_title,'site_name'=>$site_name)); ?>

<?php $this->load->view($sub_view,$result); // Subview is set in controller ?>

<?php $this->load->view('test/components/page_tail',array('meta_title'=>$meta_title,'site_name'=>$site_name)); ?>