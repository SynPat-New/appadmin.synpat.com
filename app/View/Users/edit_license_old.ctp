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
	$inviteesList = $license['Invitee'];
	$commitmentList = $license['Commitment'];
	$dueDilligenceList = $license['Due_dilligence'];
	$chartLeft = $license['Chart_left'];
	$potentialSyndicate = $license['Potential_syndicate'];
	//echo "<pre>";
	//print_r($chartLeft);
	$chartMiddle = $license['Chart_middle'];
	//print_r($chartMiddle);
	$chartRight = $license['Chart_right'];
	//print_r($chartRight);
	//die;
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
                __data = [
        <?php
        for ($i = 0; $i < count($inviteesList); $i++) {
            echo "['" . $inviteesList[$i]['company'] . "','" . $inviteesList[$i]['aar'] . "']";
            if ($i < count($inviteesList) - 1) {
                echo ",";
            }
        }
        ?>
                ];

                __data1 = [
        <?php
        for ($i = 0; $i < count($commitmentList); $i++) {
            echo "['" . $commitmentList[$i]['commitment_date'] . "','" . $commitmentList[$i]['amount'] . "','" . $commitmentList[$i]['ar'] . "']";
            if ($i < count($commitmentList) - 1) {
                echo ",";
            }
        }
        ?>
                ];
                __data2 = [
        <?php
        for ($i = 0; $i < count($dueDilligenceList); $i++) {           
            echo "['" . $dueDilligenceList[$i]['patent_number'] . "','" . $dueDilligenceList[$i]['pdf'] . "','" . $dueDilligenceList[$i]['illustration'] . "','" . $dueDilligenceList[$i]['family'] . "','" . $dueDilligenceList[$i]['citation'] . "']";
            if ($i < count($dueDilligenceList) - 1) {
                echo ",";
            }
        }
        ?>
                ];
                
                __data3 = [
        <?php
        for ($i = 0; $i < count($chartLeft); $i++) {
            echo "['" . $chartLeft[$i]['country'] . "','" . $chartLeft[$i]['applications'] . "','" . $chartLeft[$i]['patents'] . "']";
            if ($i < count($chartLeft) - 1) {
                echo ",";
            }
        }
        ?>
                ];
                
                __data4 = [
        <?php
        for ($i = 0; $i < count($chartMiddle); $i++) {
            echo "['" . $chartMiddle[$i]['technologies'] . "','" . $chartMiddle[$i]['data'] . "']";
            if ($i < count($chartMiddle) - 1) {
                echo ",";
            }
        }
        ?>
                ];
                
                __data5 = [
        <?php
        for ($i = 0; $i < count($chartRight); $i++) {
            echo "['" . $chartRight[$i]['year'] . "','" . $chartRight[$i]['data'] . "']";
            if ($i < count($chartRight) - 1) {
                echo ",";
            }
        }
        ?>
                ];
                __data6 = [
        <?php
        for ($i = 0; $i < count($potentialSyndicate); $i++) {
            echo "['" . $potentialSyndicate[$i]['participate'] . "','" . $potentialSyndicate[$i]['fees'] . "']";
            if ($i < count($potentialSyndicate) - 1) {
                echo ",";
            }
        }
        ?>
                ];
                
                
               
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
                jQuery("#grid_excel").data('handsontable').loadData(__data);

                var $container1 = jQuery("#grid_excel2");
                $container1.handsontable({
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
                jQuery("#grid_excel2").data('handsontable').loadData(__data1);

                var $container2 = jQuery("#grid_due_dilligence");
                $container2.handsontable({
                    rows: 1,
                     cols: 5,
                    rowHeaders: true,
                    colHeaders: ['US Number', 'PDF' , 'Illustration', 'Family', 'Citations'],
                    minSpareCols: 0,
                    minSpareRows: 1,
                    contextMenu: false,
                    Controller: {
                        button: [''],
                        ui: 'hide'
                    }
                });
                jQuery("#grid_due_dilligence").data('handsontable').loadData(__data2);
                
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
                 jQuery("#grid_chart_left").data('handsontable').loadData(__data3);
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
                 jQuery("#grid_chart_middle").data('handsontable').loadData(__data4);
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
                 jQuery("#grid_chart_right").data('handsontable').loadData(__data5);
                 
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
                 jQuery("#grid_potential_client").data('handsontable').loadData(__data6);
            });
        </script>
 <div class="span12">
<div class="account-container register">
	
	<div class="content clearfix">
		<div class="widget ">
		
		
			<div class="widget-header">
				<i class="icon-columns"></i>
				<h3>Edit License</h3>
			</div>	
			<div class="widget-content">
				<?php 
		
					echo $this->Form->create('Users',array('action'=>'edit_license/'.$license['License']['id'], 'type' => 'file','id'=>'UsersEditLicense','class'=>'form-horizontal','inputDefaults' => array(
							'label' => false,
							'div' => false
						)));
				?>
			
				<fieldset>
					<div class="control-group">
						<label class="control-label" for="username">Project Access Code:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.access_code',array('value'=>$license['License']['access_code'],'class'=>'span6 '));
							?>
							<p class="help-block">Please enter Project Access Code</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Syndicate Number:</label>
						<div class="controls">
							<h2><?php echo $license['License']['license_number']?></h2>
							<?php 
								echo $this->Form->input('license.license_number',array('type'=>'hidden','value'=>$license['License']['license_number'],'class'=>'span6 '));
							?>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Name:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.name',array('value'=>$license['License']['name'],'class'=>'span6 '));
							?>
							<p class="help-block">Please enter name.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Current Requesting Companies:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.current_requesting_companies',array('value'=>$license['License']['current_requesting_companies'],'class'=>'span6 ','required'=>true));
							?>
							<p class="help-block">Please enter Current Requesting Companies.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Introduction Text:</label>
						<div class="controls">
							<?php echo $this->Form->input('license.introduction',array('type'=>'textarea','value'=>$license['License']['introduction'],'class'=>'elm1 span6','label'=>false)) ?>
							<p class="help-block">Please enter Introduction text for Simulator how to use</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Commitment Chart URL:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.commitment_file',array('class'=>'span6 ','value'=>$license['License']['commitment_file']));
							?>
							<p class="help-block"><a href="https://drive.google.com" target="_blank">Google docs</a>: File / Publish to the Web / Document Link</p>
						</div> 
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Program URL:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.program_file',array('class'=>'span6 ','value'=>$license['License']['program_file']));
							?>
							<p class="help-block"><a href="https://drive.google.com" target="_blank">Google docs</a>: File / Publish to the Web / Document Link</p>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="username">Syndication URL:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.syndication_file',array('class'=>'span6 ','value'=>$license['License']['syndication_file']));
							?>
							<p class="help-block"><a href="https://drive.google.com" target="_blank">Google docs</a>: File / Publish to the Web / Document Link</p>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="username">Purchase Agreement:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.purchase_file',array('value'=>$license['License']['purchase_file'],'class'=>'span6 '));
							?>
							<p class="help-block"><a href="https://drive.google.com" target="_blank">Google docs</a>: File / Publish to the Web / Document Link</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">License Agreement:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.purchase_license',array('value'=>$license['License']['purchase_license'],'class'=>'span6 '));
							?>
							<p class="help-block"><a href="https://drive.google.com" target="_blank">Google docs</a>: File / Publish to the Web / Document Link</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Buy a License:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.purchase_buy',array('value'=>$license['License']['purchase_buy'],'class'=>'span6 '));
							?>
							<p class="help-block"><a href="https://drive.google.com" target="_blank">Google docs</a>: File / Publish to the Web / Document Link</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Damage:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.damage',array('value'=>$license['License']['damage'],'class'=>'span6 '));
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
							
							<?php 
								$baseName  = pathinfo($license['License']['purchase_file']);
								echo $baseName['basename'];
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
							<?php 
								$baseName  = pathinfo($license['License']['purchase_license']);
								echo $baseName['basename'];
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
							<?php 
								$baseName  = pathinfo($license['License']['purchase_buy']);
								echo $baseName['basename'];
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
							<?php echo $this->Form->input('license.purchase_file_doc',array('type'=>'textarea','value'=>$license['License']['purchase_file_doc'],'class'=>'elm1 span6','label'=>false)) ?>
							<p class="help-block">Enter Purchase Agreement </p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">License Agreement Text:</label>
						<div class="controls">
							<?php echo $this->Form->input('license.purchase_license_doc',array('type'=>'textarea','value'=>$license['License']['purchase_license_doc'],'class'=>'elm1 span6','label'=>false)) ?>
							<p class="help-block">Enter License Agreement</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Buy a License Text:</label>
						<div class="controls">
							<?php echo $this->Form->input('license.purchase_buy_doc',array('type'=>'textarea','value'=>$license['License']['purchase_buy_doc'],'class'=>'elm1 span6','label'=>false)) ?>
							<p class="help-block">Enter Buy a License Agreement</p>
						</div>
					</div>
					-->
					<!-- editor area ends -->
					<div class="control-group">
						<label class="control-label" for="username">ClaimCharts:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.cliamchart',array('value'=>$license['License']['cliamchart'],'class'=>'span6 '));
							?>
							<p class="help-block">Please enter name of project at <a href="http://www.claimcharts.info" target="_BLANK">ClaimCharts.info</a></p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Prior Art:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.prior_art',array('value'=>$license['License']['prior_art'],'class'=>'span6 '));
							?>
							<p class="help-block">Please enter name of project at <a href="http://www.claimcharts.info" target="_BLANK">ClaimCharts.info</a></p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Damage:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.prior_art',array('value'=>$license['License']['damage'],'class'=>'span6 '));
							?>
							<p class="help-block"><a href="https://drive.google.com" target="_blank">Google docs</a>: File / Publish to the Web / Document Link</p>
						</div>
					</div>
					<!--
					<div class="control-group">
						<label class="control-label" for="username">Assets:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.title',array('value'=>$license['License']['title'],'class'=>'span6 '));
							?>
							<p class="help-block">Please enter Assets numbers with comma between them.</p>
						</div>
					</div>-->
					
					<div class="control-group">
						<label class="control-label" for="username">Due Dilligence Access Code:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.due_access_code',array('value'=>$license['License']['due_access_code'],'class'=>'span6 '));
							?>
							<p class="help-block">Please enter Seller's Questionnaire Access Code</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Tabs Access Code:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.tabs_access_code',array('value'=>$license['License']['tabs_access_code'],'class'=>'span6 '));
							?>
							<p class="help-block">Please enter Tabs Access Code</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Option Expiration Date:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.expiration_date',array('value'=>$license['License']['expiration_date'],'class'=>'span6 '));
							?>
							<p class="help-block">Please enter Option Expiration Date in Format MM-DD-YYYY</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Seller Asking Price:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.asking_price',array('value'=>$license['License']['asking_price'],'class'=>'span6 '));
							?>
							<p class="help-block">Please enter Seller Asking Price in Number only</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Red Button text:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.red_button',array('value'=>$license['License']['red_button'],'class'=>'span6 '));
							?>
							<p class="help-block">Please enter text for red button in Portfolio tab.</p>
						</div>
					</div>
					<!--
					<div class="control-group">
						<label class="control-label" for="username">Portfolio Button Text:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.portfolio_button_text',array('value'=>$license['License']['portfolio_button_text'],'class'=>'span6 '));
							?>
							<p class="help-block">Please enter Portfolio Button Text.</p>
						</div>
					</div>
					-->
					<div class="control-group">
						<label class="control-label" for="username">Committed Date and Amount:</label>
						<div class="controls">
							<div id="grid_excel2" class="dataTable" style="width: 600px; min-height:60px; height: auto; overflow: auto"></div>
							<p class="help-block">Enter data into excel OR you can copy and paste data. Commited Date format YYYY-MM-DD HH:MM:SS and Amount in decimal</p>
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
							<p class="help-block">Please enter Chart Data for Patent Assets By Jurisdiction</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Chart Middle:</label>
						<div class="controls">
							<div id="grid_chart_middle" class="dataTable" style="width: 600px; min-height:60px; height: auto; overflow: auto"></div>
							<p class="help-block">Please enter Chart Data for Covered Technologies</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Chart Right:</label>
						<div class="controls">
							<div id="grid_chart_right" class="dataTable" style="width: 600px; min-height:60px; height: auto; overflow: auto"></div>
							<p class="help-block">Please enter Chart Data for Portfolio Lifespan</p>
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
								echo $this->Form->file('license.file_left',array('type'=>'file','class'=>'form-control '));
							?>
							<p class="help-block"><?php 
								$baseName  = pathinfo($license['License']['file_left']);
								echo $baseName['basename'];
							?></p>
							<p class="help-block">Please enter Image left.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Image Middle:</label>
						<div class="controls">
							<?php 
								echo $this->Form->file('license.file_middle',array('type'=>'file','class'=>'form-control '));
							?>
						
							<p class="help-block"><?php 
								$baseName  = pathinfo($license['License']['file_middle']);
								echo $baseName['basename'];
							?></p>
							<p class="help-block">Please enter Image middle.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Image Right:</label>
						<div class="controls">
							<?php 
								echo $this->Form->file('license.file_right',array('type'=>'file','class'=>'form-control '));
							?>
							
							<p class="help-block"><?php 
								$baseName  = pathinfo($license['License']['file_right']);
								echo $baseName['basename'];
							?></p>
							<p class="help-block">Please enter Image right</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Portfolio Tab Text:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.portfolio_text',array('type' => 'textarea','class'=>'form-control ','value'=>$license['License']['portfolio_text'],'style'=>array('width:500px;')));
							?>
							
							<p class="help-block">Please enter portfolio tab text shows in Application</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Syndication:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.syndication_tab',array('type' => 'textarea','class'=>'form-control ','value'=>$license['License']['syndication_tab'],'style'=>array('width:500px;')));
							?>
							<p class="help-block">Please enter syndication tab text shows in Application</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Program:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.program_tab',array('type' => 'textarea','class'=>'form-control ','value'=>$license['License']['program_tab'],'style'=>array('width:500px;')));
							?>
							<p class="help-block">Please enter program tab text shows in Application</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Simulator Tab Text:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.simulator_text',array('type' => 'textarea','class'=>'form-control ','value'=>$license['License']['simulator_text'],'style'=>array('width:500px;')));
							?>
							
							<p class="help-block">Please enter simulator tab text shows in Application</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Purchase Agreement:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.purchase_agreement',array('type' => 'textarea','class'=>'form-control ','value'=>$license['License']['purchase_agreement'],'style'=>array('width:500px;')));
							?>
							
							<p class="help-block">Please enter purchase agreement tab text shows in Application</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">License:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.license_tab',array('type' => 'textarea','class'=>'form-control ','value'=>$license['License']['license_tab'],'style'=>array('width:500px;')));
							?>
							
							<p class="help-block">Please enter license tab text shows in Application</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Buy a License:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.buy_a_license_tab',array('type' => 'textarea','class'=>'form-control ','value'=>$license['License']['buy_a_license_tab'],'style'=>array('width:500px;')));
							?>
							<p class="help-block">Please enter buy a license tab text shows in Application</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Due Diligence:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.due_diligence_tab',array('type' => 'textarea','class'=>'form-control ','value'=>$license['License']['due_diligence_tab'],'style'=>array('width:500px;')));
							?>
							<p class="help-block">Please enter due diligence tab text shows in Application</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">ClaimCharts:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.claim_chart_tab',array('type' => 'textarea','class'=>'form-control ','value'=>$license['License']['claim_chart_tab'],'style'=>array('width:500px;')));
							?>
							<p class="help-block">Please enter claimchart tab text shows in Application</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username">Prior Art:</label>
						<div class="controls">
							<?php 
								echo $this->Form->input('license.prior_art_tab',array('type' => 'textarea','class'=>'form-control ','value'=>$license['License']['prior_art_tab'],'style'=>array('width:500px;')));
							?>
							<p class="help-block">Please enter prior art tab text shows in Application</p>
						</div>
					</div>
					
				</fieldset>
		
				<div class="login-actions navbar-fixed-bottom">
					<!--<div class="control-group">
						<label class="control-label" for="username"></label>
						<div class="controls">-->
							<button class="button btn btn-primary btn-large">Save</button>
							<button class="button btn btn-danger btn-large" type="button" onclick="window.location.href='<?php echo Router::url(array('controller'=>'Users', 'action'=>'list_license'));?>'">Cancel</button>
						<!--</div>
					</div>-->
				</div> <!-- .actions -->
			
		<?php 
			echo $this->Form->input('license.invitees_companies',array('type'=>'hidden'));
			echo $this->Form->input('license.commited_data',array('type'=>'hidden'));
			echo $this->Form->input('license.due_dilligence_data',array('type'=>'hidden'));
			echo $this->Form->input('license.chart_left',array('type'=>'hidden'));
			echo $this->Form->input('license.chart_middle',array('type'=>'hidden'));
			echo $this->Form->input('license.chart_right',array('type'=>'hidden'));
			echo $this->Form->input('license.potential_syndicate',array('type'=>'hidden'));
			echo $this->Form->input('license.id',array('type'=>'hidden','value'=>$license['License']['id']));
			echo $this->Form->end();?>
		</div>
		</div>
	</div> <!-- /content -->
	
</div> <!-- /account-container -->

</div>
<script>
jQuery(document).ready(function(){
	jQuery("#UsersEditLicense").submit(function(){
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
		jQuery("#licenseChartRight").val(JSON.stringify(handsontable5));
		var handsontable6 = jQuery("#grid_potential_client").handsontable("getData");
		jQuery("#licensePotentialSyndicate").val(JSON.stringify(handsontable6));
	});
});

</script>
