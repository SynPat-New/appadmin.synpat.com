<?php 
	echo $this->Html->css('pages/signin.css');
	echo $this->Html->script('signin.js');
?>







<?php 
		
		echo $this->Form->create('Users',array('action'=>'login','inputDefaults' => array(
				'label' => false,
				'div' => false
			)));
	?>

		
				
			
			<div class="login-fields">
				
				
				
				<div class="field">
					<label for="username">Username</label>
					<?php echo $this->form->input('login.username',array('placeholder'=>'Username','class'=>'login username-field'));?>
				</div> <!-- /field -->
				
				<div class="field">
					<label for="password">Password:</label>
					<?php echo $this->form->input('login.password',array('placeholder'=>'password','class'=>'login password-field'));?>
				</div> <!-- /password -->
				
			</div> <!-- /login-fields -->
			
			<div class="login-actions">
				
				<span class="login-checkbox">
					<input id="Field" name="Field" type="checkbox" class="field login-checkbox" value="First Choice" tabindex="4" />
					<label class="choice" for="Field">Keep me signed in</label>
				</span>
									
				<button class="button btn btn-success btn-large">Sign In</button>
				
			</div> <!-- .actions -->
			
			
			
		<?php echo $this->form->end();?>
