 <style>
 form{margin:0px;}input[type='text']{height:14px;}
.table-bordered th, .table-bordered td{text-align:center;}
 </style>
 <div class="span12">
        <div class="widget widget-table action-table">
			
			<?php
				
					echo $this->Form->create('Users',array('action'=>'list_license','id'=>'UsersSyndicationNumber','class'=>'form-horizontal','inputDefaults' => array(
							'label' => false,
							'div' => false
						)),array('style'=>array('margin'=>'0px')));
					?>
					<div class="" style='margin-bottom:10px;' id="demo">
					
					<fieldset>
					<div class="control-group">
						<label class="control-label" for="username" style='width:490px;text-align:left;'>Create a new Syndication Docket, and give it a name in the format DEMOMMDD:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('project.syndication_number',array('class'=>'span1','maxlength'=>'8','minlength'=>8,'syle'=>'border-radius:0.24635em;'));
							?>
							<button class="btn btn-small" style='background:#990000;border:1px solid #690000;border-radius:0.24635em;color:#fff;text-transform:uppercase;font-family:Arial,sans-serif !important;'>Save</button>
						</div>
					</div>
					</fieldset>				
				</div>
				<?php 
						echo $this->Form->end();
					?>				
				<?php 
					
					echo $this->Form->create('Users',array('action'=>'real_license','id'=>'UsersSyndicationNumber','class'=>'form-horizontal','inputDefaults' => array(
							'label' => false,
							'div' => false
						),'onsubmit'=>'return validateForm();'));
					?>
				<div class="" style='margin-bottom:10px;' id="real">					
					<fieldset>
					<div class="control-group">
						<label class="control-label" for="username" style='width:485px;text-align:left;'>In order to convert a Demo Syndication Docket, into a Real Syndication Docket and move it into the Real Store, select the Demo Syndication Docket and enter a new name in the format YYYYMMDD:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('project.syndication_number',array('class'=>'span1','maxlength'=>'8','minlength'=>8,'required'=>'required','style'=>'border-radius:0.24635em;'));
							?>
							<input type="hidden" name="project[demo_id]" id="demo_id"/>
							<button class="btn btn-small" style='background:#990000;border:1px solid #690000;border-radius:0.24635em;color:#fff;text-transform:uppercase;font-family:Arial,sans-serif !important;'>Save</button>
						</div>
					</div>
					</fieldset>	
				<?php 
					echo $this->Form->end();
				?>
				</div>
				<?php if(isset($syndicationNumber) && !isset($realPort)):?>
				<p style='padding-left:8px;'>The new Syndicate number is : <b><?php echo $syndicationNumber;?></b>. Click <?php echo $this->Html->link('here',array('controller'=>'Users','action'=>'add_license',$syndicationNumber));?>  to continue.				
				</p>
				<?php elseif(isset($realPort)):?>
				<p>Please <?php echo $this->Html->link('log',array('controller'=>'Users','action'=>'edit_license',$license_id));?> in into the new real docket and change the access code. The access code for the real docket must be different from the access code for the corresponding Demo docket</p>
				<?php endif;?>
				
            <div class="widget-header"> <i class="icon-th-list"></i>
              <h3>Syndication Dockets</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <table class="table table-striped table-bordered" >
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>License Number</th>
                    <th>ClaimCharts</th>
                    <th>Prior Art</th>
                    <!--<th>Title</th>-->
                    <th>Access Code</th>
                    <th class="td-actions">Select</th>
                    <th class="td-actions">Edit</th>
                    <th class="td-actions">Delete</th>
                  </tr>
                </thead>
                <tbody>
					<?php 
						if(count($licenses)>0){
							foreach($licenses as $license){
					?>
								<tr>
									<td><?php echo $license['License']['name'];?></td>
									<td><?php echo $license['License']['license_number'];?></td>
									<td><?php echo $license['License']['cliamchart'];?></td>
									<td><?php echo $license['License']['prior_art'];?></td>
									<!--<td><?php echo $license['License']['title'];?></td>-->
									<td><?php echo $license['License']['access_code'];?></td>
									<td class="td-actions">
									<?php if($license['License']['name']!='Template'):?>
									<?php if((int)$license['License']['license_status']==0):?><input type="radio" name="status" value="<?php echo $license['License']['id'];?>" onclick="checkStatus(jQuery(this))"/><?php endif;?>
									<?php endif;?>
									</td>
									<td class="td-actions"><?php echo $this->Html->link('<i class="btn-icon-only icon-edit"> </i>',array('controller'=>'Users','action'=>'edit_license',$license['License']['id']), array('class' => 'btn btn-small btn-success','style'=>'border-radius:0.24635em;' , 'escape' => false));?> </td>
									<td class="td-actions">
									<?php if($license['License']['name']!='Template'):?>
										<?php echo $this->Html->link('<i class="btn-icon-only icon-remove"> </i>',array('controller'=>'Users','action'=>'delete_license','?'=>array('id'=>$license['License']['id'])), array('class' => 'btn btn-small','style'=>'background:#990000;border:1px solid #690000;color:#fff;border-radius:0.24635em;' ,'escape' => false,'confirm' => 'Are you sure you want to delete this portfolio?'));?>
									<?php endif;?>
									</td>
								</tr>
					<?php
							}
						}
					?>
                   
                
                </tbody>
              </table>
			  
            </div>
            <!-- /widget-content --> 
			<table align="right">
				<tr><td><?php echo $this->Html->link('Deleted Dockets',array('controller'=>'Users','action'=>'trash_license'), array('class' => 'btn btn-small', 'escape' => false,'style'=>'background:#990000;border:1px solid #690000;color:#fff;border-radius:0.24635em;'));?></td></tr>
			  </table>
          </div>
          <!-- /widget --> 

</div>

<script>
function checkStatus(object){
 if(object.is(':checked')){
	jQuery("#demo_id").val(object.val());
 } 
}

function validateForm(){
	if(jQuery("#demo_id").val()=="" || jQuery("#demo_id").val()=='0'){
		alert('Please select License which you want to convert to Real Syndication Docket.');
		return false;
	}
	return true;
}
</script>