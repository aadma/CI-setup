<!--footer-->
		
		<div class="col-xs-12 footer fjalla">
		
		
				
			
		</div>
		</div>
	<!-- end container -->
	<!--Login popup-->
		<div class="modal fade login" id="loginModal" aria-hidden="true" >
		      <div class="modal-dialog login">
    		      <div class="modal-content">
    		         <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                        <h4 class="modal-title">Login with</h4>
                    </div>
                    <div class="modal-body">  
                        <div class="box">
                             <div class="content loginContent">
                                <div class="social">
                                   
                                    <a id="facebook_login" class="circle facebook" href="#">
                                        <i class="fa fa-facebook fa-fw"></i>
                                    </a>
                                </div>
                                <div class="division">
                                    <div class="line l"></div>
                                      <span>or</span>
                                    <div class="line r"></div>
                                </div>
                                <div class="error"></div>
                                <div class="form loginBox">
                             
									<?php echo form_open(''); ?>
									<?php echo form_input(array('id'=>'identity','class'=>'form-control','placeholder'=>'Email','name'=>'identity'));?>
									<?php echo form_password(array('id'=>'password','class'=>'form-control','placeholder'=>'Password','name'=>'password'));?>
									<?php echo form_input(array('class'=>'btn btn-default btn-login','type'=>'button','value'=>'Login','onclick'=>'loginAjax()'));?>
									<div class="checkbox">
										<label>
											
											<?php echo form_checkbox(array('name'=>'remember','id'=>'remember','value'=>'1'));?> 
											Remember me
										</label>
									</div>
                                    <!--</form>-->
									<?php echo form_close(); ?>
                                </div>
                             </div>
                        </div>
						<div class="box">
						
							<div class="content forgotBox" style="display:none;">
							<div class="error"></div>
							<div class="text-success"></div>
								<div class="form">
									<form method="" action="" accept-charset="UTF-8">
                                    <input id="identity" class="form-control" type="text" placeholder="Email" name="identity">
                                    
                                    <input class="btn btn-default btn-login" type="button" value="Reset" onclick="resetPassAjax()">
                                    </form>
								</div>
							</div>
						</div>
                        <div class="box">
                            <div class="content registerBox" style="display:none;">
							<div class="error"></div>
							<div class="text-success"></div>
                             <div class="form">
                                <form method=""  action="" accept-charset="UTF-8">
                                <input id="identity" class="form-control" type="text" placeholder="Email" name="identity">
                                <input id="password" class="form-control" type="password" placeholder="Password" name="password">
                                <input id="password_confirm" class="form-control" type="password" placeholder="Repeat Password" name="password_confirm">
                                <input class="btn btn-default btn-register" type="button" value="Create account" name="commit" onclick="registerAjax()">
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="forgot login-footer">
                            <span>
                                 <a href="javascript: showForgotPassForm();">Forgot password</a>
                            ?</span>
							<br>
							<span>Looking to 
                                 <a href="javascript: showRegisterForm();">create an account</a>
                            ?</span>
                        </div>
                        <div class="forgot register-footer" style="display:none">
                             <span>Already have an account?</span>
                             <a href="javascript: showLoginForm();">Login</a>
                        </div>
                    </div>        
    		      </div>
		      </div>
			  <script>
			  //login , register and forgotten password
				function loginAjax(){
					var identity = $(".loginBox #identity").val();
					var password = $(".loginBox #password").val();
					
					if($('#remember').prop('checked')) {
						var remember = $("#remember").val();
					} else {
						var remember = 0;
					}
					if(identity.length==0 || password.length==0){
						$(".loginContent .error").html("<?php echo $this->lang->line('login_ajax');?>");
					}else{
						$.ajax({
							url: "<?php echo site_url('user/login');?>",
							type: "post",
							data: {ajax: 1, identity: identity, password: password, remember:remember},
							cache: false,
							success: function (json) {
								var error_message = json.error;
								var success = json.logged_in;
								
								
								if(typeof error_message !== "undefined"){
								$("loginContent .error").html(error_message);
								}else if(typeof success !== "undefined" && success == "1"){
									window.location.href=json.goto;
								}
								
								

							}
						}); 
						
						
					}
					 
					
				}
				function registerAjax(){
					
					var identity = $(".registerBox #identity").val();
					var password = $(".registerBox #password").val();
					var password_confirm = $(".registerBox #password_confirm").val();
					if(password.length == 0 || password_confirm == 0 || identity.length == 0){
						$(".registerBox .error").html("<?php echo $this->lang->line('entire_form');?>");
					}else{
						
						$.ajax({
							url: "<?php echo site_url('user/register');?>",
							type: "post",
							data: {ajax: 1, identity: identity, password: password, password_confirm:password_confirm},
							cache: false,
							success: function (json) {
								var error_message = json.error;
								var success = json.registered;
								
								
								if(typeof error_message !== "undefined"){
								$(".registerBox .error").html(error_message);
								}else if(typeof success !== "undefined" && success == "1"){
									$(".registerBox .error").html();
									$("#loginModal").modal("hide");
									$("#regSuccess").modal("show");
									$("#regSuccess .text-success").html(json.message);
									
								}
								
								

							}
						}); 
						
					}
					
				}
				
				function resetPassAjax(){
					var identity = $(".forgotBox #identity").val();
					if(identity.length == 0){
						$(".forgotBox .error").html("<?php echo sprintf(lang('forgot_password_subheading'),$this->config->item('identity', 'ion_auth'));?>");
					}else{
						
						$.ajax({
							
							url: "<?php echo site_url('user/forgot');?>",
							type: 'post',
							cache:false,
							data: {ajax:1, identity:identity},
							success:function(json){
								var error_message = json.error;
								var success = json.reset_success;
								if(typeof error !== 'undefined'){
									$(".forgotBox .error").html(error_message);
								}else if(typeof success !== 'undefined' && success == '1'){
									$(".forgotBox .error").html();
									$("#loginModal").modal("hide");
									$("#resetPass").modal("show");
									$("#resetPass .text-success").html(json.message);
									
								}
							}
							
							
						});
						
					}
				}
				
				function showRegisterForm(){
					$(".loginContent, .login-footer").css("display","none");
					$(".registerBox, .register-footer").removeAttr("style");
					
					$("#loginModal .modal-title").text("Create account");
				}
				function showLoginForm(){
					$(".registerBox, .forgotBox, .register-footer").css("display","none");
					
					$(".loginContent, .login-footer").removeAttr("style");
					
					$("#loginModal .modal-title").text("Login with");
					$(".login-footer").first().removeAttr("style");
				}
				function showForgotPassForm(){
					$(".loginContent, .login-footer").css("display","none");
					$(".forgotBox, .register-footer").removeAttr("style");
					
					$("#loginModal .modal-title").text("Password reset");
				}
			  </script>
		  </div>
	<!--/login popup-->
	
	<!-- register success -->
	<div class="modal fade" id="regSuccess" aria-hidden="true" >
		      <div class="modal-dialog">
    		      <div class="modal-content">
    		         <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                        <h4>Account created</h4>
                    </div>
                    <div class="modal-body"> 
					<div class="text-success"></div>
					</div>
				    </div>	
				</div>
	</div>			
	<!-- /register success -->
	
	<!-- resetpass success -->
	<div class="modal fade" id="resetPass" aria-hidden="true" >
		      <div class="modal-dialog">
    		      <div class="modal-content">
    		         <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                        <h4>Reset password</h4>
                    </div>
                    <div class="modal-body"> 
					<div class="text-success"></div>
					</div>
				    </div>	
				</div>
	</div>			
	<!-- /resetpass success -->
	
	
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<!--Custom js-->
	<script src="<?php echo base_url('assets/js/javascript.js');?>"></script>
	

  </body>
</html>