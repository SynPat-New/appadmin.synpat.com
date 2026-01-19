<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
 
 ?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
<?php echo $this->Html->charset(); ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<title><?php echo $title_for_layout; ?></title>
<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('bootstrap.min.css');
		echo $this->Html->css('bootstrap-responsive.min.css');
?>
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet"/>
<?php
		echo $this->Html->css('font-awesome.css');
		echo $this->Html->css('style.css');
		
?>
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<?php
		echo $this->Html->script('jquery-1.7.2.min.js');
		echo $this->Html->script('bootstrap.js');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
?>
</head>
<body>
	<div class="navbar navbar-fixed-top">
	
	<div class="navbar-inner">
		
		<div class="container">
			
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			
			<a class="brand" href="index.html">
				Synpat				
			</a>		
			
			<div class="nav-collapse">
				<ul class="nav pull-right">
					<li class="">						
						<!--<a href="signup.html" class="">
							Don't have an account?
						</a>-->
					</li>					
				</ul>
				
			</div><!--/.nav-collapse -->	
	
		</div> <!-- /container -->
		
	</div> <!-- /navbar-inner -->
	
</div> <!-- /navbar -->


<div class="account-container">
	
	<div class="content clearfix">
		
		<?php echo $this->Session->flash(); ?>

		  <?php echo $this->fetch('content'); ?>
		
	</div> <!-- /content -->
	
</div> <!-- /account-container -->
</body>

</html>
