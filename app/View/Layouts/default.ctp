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
		echo $this->Html->script('excanvas.min.js');
		echo $this->Html->script('chart.min.js');
		echo $this->Html->script('bootstrap.js');
		echo $this->Html->script('full-calendar/fullcalendar.min.js');
		echo $this->Html->script('base.js');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
?>
</head>
<body>
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span
                    class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span> </a> <?php echo $this->Html->link('Synpat Admin',array('controller'=>'Users','action'=>'list_license'),array('class'=>'brand'));?>
      <div class="nav-collapse">
        <ul class="nav pull-right">
          <!--<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                            class="icon-cog"></i> Account <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="javascript:;">Settings</a></li>
              <li><a href="javascript:;">Help</a></li>
            </ul>
          </li>-->
          <li class="dropdown"><a href="javascript://" target='_blank' class="dropdown-toggle" data-toggle="dropdown"><i
                            class="icon-user"></i> Admin <b class="caret"></b></a>
            <ul class="dropdown-menu">
            <!--  <li><a href="javascript:;">Profile</a></li>-->
              <li><?php echo $this->Html->link('Logout',array('controller'=>'Users','action'=>'logout'));?></li>
            </ul>
          </li>
        </ul>
        <!--
        <form class="navbar-search pull-right">
          <input type="text" class="search-query" placeholder="Search">
        </form>-->
      </div>
      <!--/.nav-collapse --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /navbar-inner --> 
</div>
<!-- /navbar -->

<!-- /subnavbar -->
<!-- /subnavbar -->
<div class="main" style='min-height:400px;'>
  <div class="main-inner">
    <div class="container" style='margin-top:10px;'>
      <div class="row">
		  <?php echo $this->Session->flash(); ?>

		  <?php echo $this->fetch('content'); ?>
	  </div>
		 <!-- /row --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /main-inner --> 
</div>
<!-- /main -->
<div class="extra">
  <div class="extra-inner">
    <div class="container">
      <div class="row">
                    <!--
                    <div class="span3">
                        <h4>
                            About Free Admin Template</h4>
                        <ul>
                            <li><a href="javascript:;">EGrappler.com</a></li>
                            <li><a href="javascript:;">Web Development Resources</a></li>
                            <li><a href="javascript:;">Responsive HTML5 Portfolio Templates</a></li>
                            <li><a href="javascript:;">Free Resources and Scripts</a></li>
                        </ul>
                    </div>-->
                    <!-- /span3 -->
                    <!--
                    <div class="span3">
                        <h4>
                            Support</h4>
                        <ul>
                            <li><a href="javascript:;">Frequently Asked Questions</a></li>
                            <li><a href="javascript:;">Ask a Question</a></li>
                            <li><a href="javascript:;">Video Tutorial</a></li>
                            <li><a href="javascript:;">Feedback</a></li>
                        </ul>
                    </div>-->
                    <!-- /span3 -->
                    <!--
                    <div class="span3">
                        <h4>
                            Something Legal</h4>
                        <ul>
                            <li><a href="javascript:;">Read License</a></li>
                            <li><a href="javascript:;">Terms of Use</a></li>
                            <li><a href="javascript:;">Privacy Policy</a></li>
                        </ul>
                    </div>-->
                    <!-- /span3 -->
                    <!--
                    <div class="span3">
                        <h4>
                            Open Source jQuery Plugins</h4>
                        <ul>
                            <li><a href="http://www.egrappler.com">Open Source jQuery Plugins</a></li>
                            <li><a href="http://www.egrappler.com;">HTML5 Responsive Tempaltes</a></li>
                            <li><a href="http://www.egrappler.com;">Free Contact Form Plugin</a></li>
                            <li><a href="http://www.egrappler.com;">Flat UI PSD</a></li>
                        </ul>
                    </div>-->
                    <!-- /span3 -->
                </div>
      <!-- /row --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /extra-inner --> 
</div>
<!-- /extra -->
<div class="footer">
  <div class="footer-inner">
    <div class="container">
      <div class="row">
        <div class="span12"> &copy; <?php echo date('Y');?> <a href="http://www.synpatapp.com/" target='_blank'>Synpatapp.com</a>. </div>
        <!-- /span12 --> 
      </div>
      <!-- /row --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /footer-inner --> 
</div>
<!-- /footer --> 


</body>

</html>
