<div class="col-xs-12">
<div class="mt10">
<?php 
if(!$this->ion_auth->logged_in()){?>
<a data-toggle="modal" href="#loginModal" style="color:#000;font-size:24px"><span class="glyphicon glyphicon-user"></span>Login</a>
<?php 
}else{?>
<a href="<?php echo site_url('user/logout');?>" style="color:#000;font-size:24px"><span class="glyphicon glyphicon-user"></span>Logout</a>
<?php
} 
?>
</div>
	<div class="row">
		<div class="col-md-8 col-md-offset-2 mt10">
		<img src="<?php echo site_url('assets/supcat.gif');?>" width="100%" height="auto" />
		</div>
	</div>
	<div class="row text-center">
	<h4>Welcome to Codeigniter <?php echo CI_VERSION;?></h4>
	<h4>Supcat is here in {elapsed_time} seconds</h4>
	</div>
</div>

