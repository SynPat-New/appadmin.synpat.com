 <style>
 form{margin:0px;}.table-bordered th, .table-bordered td{text-align:center;}
 </style>
 <div class="span12">
        <div class="widget widget-table action-table">
            <div class="widget-header"> <i class="icon-th-list"></i>
              <h3>Delete Dockets</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>License Number</th>
                    <th>ClaimCharts</th>
                    <th>Prior Art</th>
                    <!--<th>Title</th>-->
                    <th>Access Code</th>
                    <th class="td-actions">Move</th>
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
									<td class="td-actions">  <?php echo $this->Html->link('<i class="btn-icon-only icon-move"> </i>',array('controller'=>'Users','action'=>'move_license','?'=>array('id'=>$license['License']['id'])), array('class' => 'btn btn-small btn-warning','style'=>'border-radius:0.24635em;' , 'escape' => false,'confirm' => 'Are you sure you want to move this portfolio?'));?></td>
								</tr>
					<?php
							}
						} else {
					?>
						<tr><td colspan='7'><p class='alert alert-warning'>No license in trash.</p></td></tr>
					<?php
						}
					?>
                   
                
                </tbody>
              </table>
			  
            </div>
            <!-- /widget-content --> 
			<table align="right">
				<tr><td><?php echo $this->Html->link('Back',array('controller'=>'Users','action'=>'list_license'), array('class' => 'btn btn-small','style'=>'background:#990000;border:1px solid #690000;color:#fff;border-radius:0.24635em;' , 'escape' => false));?></td></tr>
			  </table>
          </div>
          <!-- /widget --> 

</div>

