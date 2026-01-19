<div class="span12">
	<div class="account-container register">
		<div class="content clearfix">
			<div class="widget ">
				<div class="widget-header">
					<i class="icon-columns"></i>
					<h3>New Syndication Docket</h3>
				</div>	
				<div class="widget-content">
					<p>The new Syndicate number is : <b><?php echo $syndicationNumber;?></b></p>
					<!--<a href='http://www.synpat.com/store/<?php echo $syndicationNumber;?>' target='_BLANK'><?php echo $syndicationNumber;?></a>-->
					<p>Create a new Syndication Docket. <?php echo $this->Html('Click',array('controller'=>'Users','action'=>'add_license'));?> here to continue.</p>
				</div>
			</div>
		</div>
	</div>
</div>
