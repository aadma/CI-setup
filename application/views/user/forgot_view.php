<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container">
    <div class="row">
        <h1>Did you forget your password?</h1>
        <div class="col-lg-4 col-lg-offset-4">
            <?php if(isset($_SESSION['auth_message']))echo $_SESSION['auth_message'];?>
            <?php echo form_open('',array('class'=>'form-horizontal'));?>
            <div class="form-group">
                <?php echo form_label('Email','identity');?>
                <?php echo form_error('identity');?>
                <?php echo form_input('identity','','class="form-control"');?>
            </div>
            <div class="form-group">
                <?php echo form_submit('submit', 'Won\'t happen again...', 'class="btn btn-primary btn-lg btn-block"');?>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>
