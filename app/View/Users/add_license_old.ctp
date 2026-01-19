<?php 
	echo $this->Html->css('assets/lib/jQuery-contextMenu/jquery.contextMenu.css');
	echo $this->Html->css('assets/lib/jquery.handsontable.css');
	echo $this->Html->css('assets/extensions/jquery.handsontable.removeRow.css');
	echo $this->Html->css('assets/extensions/jquery.handsontable.controller.css');
	echo $this->Html->script('assets/lib/jquery.handsontable.js');
	echo $this->Html->script('assets/lib/bootstrap-typeahead.js');
	echo $this->Html->script('assets/lib/jquery.autoresize.js');
	echo $this->Html->script('assets/lib/jquery.expage.js');
	echo $this->Html->script('assets/extensions/jquery-ui-1.8.23.draggable.min.js');
	echo $this->Html->script('assets/extensions/jquery.handsontable.controller.js');
	echo $this->Html->script('assets/lib/jQuery-contextMenu/jquery.contextMenu.js');
	echo $this->Html->script('assets/lib/jQuery-contextMenu/jquery.ui.position.js');
	echo $this->Html->script('assets/extensions/jquery.handsontable.removeRow.js');
	
?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.2/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.8.2/jquery-ui.js"></script>
 <!-- tinymce script start -->
 <?php echo $this->Html->script('tinymce/tinymce.min.js'); ?>
<script>
tinymce.init({
    selector: "textarea.elm1",
    theme: "modern",
    width: 580,
    height: 200,
    plugins: [
         "advlist autolink lists print preview spellchecker",
         "searchreplace wordcount visualblocks visualchars code fullscreen ",
         "table contextmenu directionality emoticons paste textcolor"
   ],
   content_css: "css/content.css",
   toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link  | print preview fullpage  code | forecolor backcolor emoticons", 
   style_formats: [
        {title: 'Bold text', inline: 'b'},
        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
        {title: 'Example 1', inline: 'span', classes: 'example1'},
        {title: 'Example 2', inline: 'span', classes: 'example2'},
        {title: 'Table styles'},
        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
    ]
 }); 
</script>
 <!--   tinymce script ends--> 
<script>
            jQuery(document).ready(function() {
                
               
                var $container = jQuery("#grid_excel");
                $container.handsontable({
                    rows: 1,
                    cols: 2,
                    rowHeaders: true,
                    colHeaders: ['Invited Companies', 'AAR'],
                    minSpareCols: 0,
                    minSpareRows: 1,
                    contextMenu: false,
                    Controller: {
                        button: [''],
                        ui: 'hide'
                    }
                });
                
                 var $container2 = jQuery("#grid_excel2");
                $container2.handsontable({
                    rows: 1,
                    cols: 3,
                    rowHeaders: true,
                    colHeaders: ['Committed Date', 'Amount','AR'],
                    minSpareCols: 0,
                    minSpareRows: 1,
                    contextMenu: false,
                    Controller: {
                        button: [''],
                        ui: 'hide'
                    }
                });  
                
                
                var $container1 = jQuery("#grid_due_dilligence");
                $container1.handsontable({
                    rows: 1,
                    cols: 5,
                    rowHeaders: true,
					colHeaders: ['US Number', 'PDF' , 'Illustration', 'Family', 'Citations'],
                   /* colHeaders: ['US Number', 'Pat/App/Prov', 'Parent', 'Filed', 'Published', 'Expiration', 'C/CIP/RE', 'Country', 'Counterparts', 'Applicant', 'Assignee', 'Licensses', 'Illustration', 'Family', 'Citations', 'PDF'],*/
                    minSpareCols: 0,
                    minSpareRows: 1,
                    contextMenu: false,
                    Controller: {
                        button: [''],
                        ui: 'hide'
                    }
                });
                
                 var $container3 = jQuery("#grid_chart_left");
                $container3.handsontable({
                    rows: 1,
                    cols: 3,
                    rowHeaders: true,
                    colHeaders: ['Country', 'Applications', 'Patents'],
                    minSpareCols: 0,
                    minSpareRows: 1,
                    contextMenu: false,
                    Controller: {
                        button: [''],
                        ui: 'hide'
                    }
                }); 
                
                 var $container4 = jQuery("#grid_chart_middle");
                $container4.handsontable({
                    rows: 1,
                    cols: 2,
                    rowHeaders: true,
                    colHeaders: ['Technologies', 'Data'],
                    minSpareCols: 0,
                    minSpareRows: 1,
                    contextMenu: false,
                    Controller: {
                        button: [''],
                        ui: 'hide'
                    }
                }); 
                
                 var $container5 = jQuery("#grid_chart_right");
                $container5.handsontable({
                    rows: 1,
                    cols: 2,
                    rowHeaders: true,
                    colHeaders: ['Year', 'Data'],
                    minSpareCols: 0,
                    minSpareRows: 1,
                    contextMenu: false,
                    Controller: {
                        button: [''],
                        ui: 'hide'
                    }
                }); 
                var $container6 = jQuery("#grid_potential_client");
                $container6.handsontable({
                    rows: 1,
                    cols: 2,
                    rowHeaders: true,
                    colHeaders: ['Participate', 'Fee'],
                    minSpareCols: 0,
                    minSpareRows: 1,
                    contextMenu: false,
                    Controller: {
                        button: [''],
                        ui: 'hide'
                    }
                }); 
            });

        </script>
 <div class="span12">
<div class="account-container register">
	
	<div class="content clearfix">
		<div class="widget ">
		
		
			<div class="widget-header">
				<i class="icon-user"></i>
				<h3>Add License</h3>
			</div>	
			<div class="widget-content">
				<?php 
		
					echo $this->Form->create('User',array('action'=>'add_license', 'type' => 'file','enctype'=>'multipart/form-data','class'=>'form-horizontal','inputDefaults' => array(
							'label' => false,
							'div' => false
						)));
				?>
			
				<fieldset>
					<div class="control-group">
						<label class="control-label" for="username">Project Access Code:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.access_code',array('class'=>'span6 ','required'=>true));
							?>
							<p class="help-block">Please enter Access Code.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Syndicate Number:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.license_number',array('class'=>'span6 ','required'=>true));
							?>
							<p class="help-block">Please enter Syndicate Number.</p>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="username">Name:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.name',array('class'=>'span6 '));
							?>
							<p class="help-block">Please enter name.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Current Requesting Companies:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.current_requesting_companies',array('class'=>'span6 ','required'=>true));
							?>
							<p class="help-block">Please enter Current Requesting Companies.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Introduction Text:</label>
						<div class="controls">
							<?php echo $this->Form->input('license.introduction',array('type'=>'textarea','class'=>'elm1 span6','label'=>false)) ?>
							<p class="help-block">Please enter Introduction text for Simulator how to use</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Syndication URL:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.syndication_file',array('class'=>'span6 '));
							?>
							<p class="help-block"><a href="https://drive.google.com" target="_blank">Google docs</a>: File / Publish to the Web / Document Link</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Program URL:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.program_file',array('class'=>'span6 ','required'=>false));
							?>
							<p class="help-block"><a href="https://drive.google.com" target="_blank">Google docs</a>: File / Publish to the Web / Document Link</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Purchase Agreement:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.purchase_file',array('class'=>'span6 ','required'=>false));
							?>
							<p class="help-block"><a href="https://drive.google.com" target="_blank">Google docs</a>: File / Publish to the Web / Document Link</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">License Agreement:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.purchase_license',array('class'=>'span6 ','required'=>false));
							?>
							<p class="help-block"><a href="https://drive.google.com" target="_blank">Google docs</a>: File / Publish to the Web / Document Link</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Buy a License:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.purchase_buy',array('class'=>'span6 ','required'=>false));
							?>
							<p class="help-block"><a href="https://drive.google.com" target="_blank">Google docs</a>: File / Publish to the Web / Document Link</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Damage:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.damage',array('class'=>'span6 ','required'=>false));
							?>
							<p class="help-block"><a href="https://drive.google.com" target="_blank">Google docs</a>: File / Publish to the Web / Document Link</p>
						</div>
					</div>
					<!--
					<div class="control-group">
						<label class="control-label" for="username">Purchase Agreement:</label>
						<div class="controls">
							<?php 
								echo $this->Form->file('license.purchase_file',array('type'=>'file','class'=>'span6 '));
							?>
							<p class="help-block">Upload Purchase Agreement</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">License Agreement:</label>
						<div class="controls">
							<?php 
								echo $this->Form->file('license.purchase_license',array('type'=>'file','class'=>'span6 '));
							?>
							<p class="help-block">Upload License Agreement</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Buy a License:</label>
						<div class="controls">
							<?php 
								echo $this->Form->file('license.purchase_buy',array('class'=>'span6 '));
							?>
							<p class="help-block">Upload Buy a License Agreement</p>
						</div>
					</div>
					-->
					<!-- editor area start -->
					<!--
					<div class="control-group">
						<label class="control-label" for="username">Purchase Agreement Text:</label>
						<div class="controls">
							<?php echo $this->Form->input('license.purchase_file_doc',array('type'=>'textarea','class'=>'elm1 span6','label'=>false)) ?>
							<p class="help-block">Enter Purchase Agreement </p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">License Agreement Text:</label>
						<div class="controls">
							<?php echo $this->Form->input('license.purchase_license_doc',array('type'=>'textarea','class'=>'elm1 span6','label'=>false)) ?>
							<p class="help-block">Enter License Agreement</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Buy a License Text:</label>
						<div class="controls">
							<?php echo $this->Form->input('license.purchase_buy_doc',array('type'=>'textarea','class'=>'elm1 span6','label'=>false)) ?>
							<p class="help-block">Enter Buy a License Agreement</p>
						</div>
					</div>
					-->
					<!-- editor area ends -->
					
					<div class="control-group">
						<label class="control-label" for="username">ClaimCharts:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.cliamchart',array('class'=>'span6 '));
							?>
							<p class="help-block">Please enter name of project at <a href="http://www.claimcharts.info" target="_BLANK">ClaimCharts.info</a>.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Prior Art:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.prior_art',array('class'=>'span6 '));
							?>
							<p class="help-block">Please enter name of project at <a href="http://www.claimcharts.info" target="_BLANK">ClaimCharts.info</a>.</p>
						</div>
					</div>
					<!--<div class="control-group">
						<label class="control-label" for="username">Assets:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.title',array('class'=>'span6 '));
							?>
							<p class="help-block">Please enter Assets numbers with comma between them.</p>
						</div>
					</div>-->
					
					<div class="control-group">
						<label class="control-label" for="username">Due Dilligence Access Code:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.due_access_code',array('class'=>'span6 '));
							?>
							<p class="help-block">Please enter Seller Asking Price in Number only</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Tabs Access Code:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.tabs_access_code',array('class'=>'span6 '));
							?>
							<p class="help-block">Please enter Tabs Access Code.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Option Expiration Date:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.expiration_date',array('class'=>'span6 '));
							?>
							<p class="help-block">Please enter Option Expiration Date in Format MM-DD-YYYY.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Seller Asking Price:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.asking_price',array('class'=>'span6 '));
							?>
							<p class="help-block">Please enter Seller Asking Price in Number only.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Red Button text:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.red_button',array('class'=>'span6 '));
							?>
							<p class="help-block">Please enter text for red button in Portfolio tab.</p>
						</div>
					</div>
					<!--
					<div class="control-group">
						<label class="control-label" for="username">Portfolio Button Text:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.portfolio_button_text',array('class'=>'span6 '));
							?>
							<p class="help-block">Please enter Portfolio Button Text.</p>
						</div>
					</div>
					-->
					<div class="control-group">
						<label class="control-label" for="username">Committed Date and Amount:</label>
						<div class="controls">
							<div id="grid_excel2" class="dataTable" style="width: 600px; min-height:60px; height: auto; overflow: auto"></div>
							<p class="help-block">Enter data into excel OR you can copy and paste data. Commited Date format YYYY-MM-DD HH:MM:SS and Amount in decimal.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Invites Companies and AAR:</label>
						<div class="controls">
							<div id="grid_excel" class="dataTable" style="width: 600px; min-height:60px; height: auto; overflow: auto"></div>
							<p class="help-block">Enter data into excel OR you can copy and paste data</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">US Patents and Data:</label>
						<div class="controls">
							<div id="grid_due_dilligence" class="dataTable" style="width: 800px; min-height:60px; height: auto; overflow: auto"></div>
							<p class="help-block">Enter data into excel OR you can copy and paste data</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Chart Left:</label>
						<div class="controls">
							<div id="grid_chart_left" class="dataTable" style="width: 600px; min-height:60px; height: auto; overflow: auto"></div>
							<p class="help-block">Please enter Chart Data for Patent Assets By Jurisdiction.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Chart Middle:</label>
						<div class="controls">
							<div id="grid_chart_middle" class="dataTable" style="width: 600px; min-height:60px; height: auto; overflow: auto"></div>
							<p class="help-block">Please enter Chart Data for Covered Technologies.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Chart Right:</label>
						<div class="controls">
							<div id="grid_chart_right" class="dataTable" style="width: 600px; min-height:60px; height: auto; overflow: auto"></div>
							<p class="help-block">Please enter Chart Data for Portfolio Lifespan.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Potential Syndicate:</label>
						<div class="controls">
							<div id="grid_potential_client" class="dataTable" style="width: 600px; min-height:60px; height: auto; overflow: auto"></div>
							<p class="help-block">Please enter Potential Syndicate data</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Image Left:</label>
						<div class="controls">
							<?php 
								echo $this->Form->file('license.file_left',array('class'=>'span6 '));
							?>
							<p class="help-block">Please enter Image left.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Image Middle:</label>
						<div class="controls">
							<?php 
								echo $this->Form->file('license.file_middle',array('class'=>'span6 '));
							?>
							
							<p class="help-block">Please enter Image middle.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Image Right:</label>
						<div class="controls">
							
							<?php 
								echo $this->Form->file('license.file_right',array('class'=>'span6 '));
							?>
							<p class="help-block">Please enter Image right.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Portfolio Tab Text:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.portfolio_text',array('type' => 'textarea','class'=>'form-control ','style'=>array('width:500px;')));
							?>
							
							<p class="help-block">Please enter portfolio tab text shows in Application.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Commitment Chart URL:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.commitment_file',array('class'=>'span6 '));
							?>
							<p class="help-block"><a href="https://drive.google.com" target="_blank">Google docs</a>: File / Publish to the Web / Document Link</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Syndication:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.syndication_tab',array('type' => 'textarea','class'=>'form-control ','style'=>array('width:500px;')));
							?>
							<p class="help-block">Please enter syndication tab text shows in Application.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Program:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.program_tab',array('type' => 'textarea','class'=>'form-control ','style'=>array('width:500px;')));
							?>
							<p class="help-block">Please enter program tab text shows in Application.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Simulator Tab Text:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.simulator_text',array('type' => 'textarea','class'=>'form-control ','style'=>array('width:500px;')));
							?>
							
							<p class="help-block">Please enter simulator tab text shows in Application.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Purchase Agreement:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.purchase_agreement',array('type' => 'textarea','class'=>'form-control ','style'=>array('width:500px;')));
							?>
							
							<p class="help-block">Please enter purchase agreement tab text shows in Application.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">License:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.license_tab',array('type' => 'textarea','class'=>'form-control ','style'=>array('width:500px;')));
							?>
							
							<p class="help-block">Please enter license tab text shows in Application.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Buy a License:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.buy_a_license_tab',array('type' => 'textarea','class'=>'form-control ','style'=>array('width:500px;')));
							?>
							<p class="help-block">Please enter buy a license tab text shows in Application.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Due Diligence:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.due_diligence_tab',array('type' => 'textarea','class'=>'form-control ','style'=>array('width:500px;')));
							?>
							<p class="help-block">Please enter due diligence tab text shows in Application.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">ClaimCharts:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.claim_chart_tab',array('type' => 'textarea','class'=>'form-control ','style'=>array('width:500px;')));
							?>
							<p class="help-block">Please enter claimchart tab text shows in Application.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Prior Art:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.prior_art_tab',array('type' => 'textarea','class'=>'form-control ','style'=>array('width:500px;')));
							?>
							<p class="help-block">Please enter prior art tab text shows in Application.</p>
						</div>
					</div>
				</fieldset>
		
				<div class="login-actions navbar-fixed-bottom">
					<button class="button btn btn-primary btn-large">Save</button>
					
					<button class="button btn btn-danger btn-large" type="button" onclick="window.location.href='<?php echo Router::url(array('controller'=>'Users', 'action'=>'list_license'));?>'">Cancel</button>
				</div> <!-- .actions -->
			
		<?php 
			echo $this->Form->input('license.invitees_companies',array('type'=>'hidden'));
			echo $this->Form->input('license.commited_data',array('type'=>'hidden'));
			echo $this->Form->input('license.due_dilligence_data',array('type'=>'hidden'));
			echo $this->Form->input('license.chart_left',array('type'=>'hidden'));
			echo $this->Form->input('license.chart_middle',array('type'=>'hidden'));
			echo $this->Form->input('license.chart_right',array('type'=>'hidden'));
		
		echo $this->Form->end();?>
		</div>
		</div>
	</div> <!-- /content -->
	
</div> <!-- /account-container -->

</div>
<script>
jQuery(document).ready(function(){
	jQuery("#UserAddLicenseForm").submit(function(){
		var handsontable = jQuery("#grid_excel2").handsontable("getData");
		var handsontable1 = jQuery("#grid_excel").handsontable("getData");
		jQuery("#licenseInviteesCompanies").val(JSON.stringify(handsontable1));
		jQuery("#licenseCommitedData").val(JSON.stringify(handsontable));
		var handsontable2 = jQuery("#grid_due_dilligence").handsontable("getData");
		jQuery("#licenseDueDilligenceData").val(JSON.stringify(handsontable2));
		var handsontable3 = jQuery("#grid_chart_left").handsontable("getData");
		jQuery("#licenseChartLeft").val(JSON.stringify(handsontable3));
		var handsontable4 = jQuery("#grid_chart_middle").handsontable("getData");
		jQuery("#licenseChartMiddle").val(JSON.stringify(handsontable4));
		var handsontable5 = jQuery("#grid_chart_right").handsontable("getData");
		jQuery("#licenseChatRight").val(JSON.stringify(handsontable5));
		var handsontable6 = jQuery("#grid_potential_client").handsontable("getData");
		jQuery("#licensePotentialSyndicate").val(JSON.stringify(handsontable6));
	});
});

</script>
