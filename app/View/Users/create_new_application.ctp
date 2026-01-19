<div class="span12">
	<div class="account-container register">
		<div class="content clearfix">
			<div class="widget ">
				<div class="widget-header">
					<i class="icon-columns"></i>
					<h3>Create New Application</h3>
				</div>	
				<div class="widget-content">
					<?php 
		
					echo $this->Form->create('Users',array('action'=>'new_application','id'=>'UsersSyndicationNumber','class'=>'form-horizontal','inputDefaults' => array(
							'label' => false,
							'div' => false
						)));
				?>
					<fieldset>
					<div class="control-group">
						<label class="control-label" for="username">Syndicate Number:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('project.syndication_number',array('class'=>'span6 '));
							?>
							<p class="help-block">Please enter Project Syndication Number</p>
						</div>
					</div>
					</fieldset>
					<div class="login-actions">
						<button class="button btn btn-primary btn-large">Save</button>
					</div> <!-- .actions -->
					<?php 
						$this->Form->end();
					?>
				</div>
			</div>
		</div>
	</div>
</div>
