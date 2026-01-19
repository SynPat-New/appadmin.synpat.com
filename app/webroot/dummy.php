<?php


define('INSTALL_DB',false);


if(INSTALL_DB==false){
?>
<input type='button' value='Install' onclick="javascript:loadProgressBar();"/>
<?php
}
?>

<script type="text/javascript" src="progressbar_mini.js"></script>

<div style="width: 100%; height: 100%; margin: 0px auto;">
	<div id="my_progress_bar_1" style="position: relative; top: 45%; margin: 0px auto;"></div>
</div>

 
<script type="text/javascript">

var myProgressBar = null
var timerId = null
 
function loadProgressBar(){ 
	myProgressBar = new ProgressBar("my_progress_bar_1",{
		borderRadius: 10,
		width: 300,
		height: 20,
		maxValue: 100, 
		labelText: "Loaded in {value,0} %",
		orientation: ProgressBar.Orientation.Horizontal,
		direction: ProgressBar.Direction.LeftToRight,
		animationStyle: ProgressBar.AnimationStyle.LeftToRight1,
		animationSpeed: 1.5,
		imageUrl: 'img/v_fg12.png',
		backgroundUrl: 'img/h_bg2.png',
		markerUrl: 'img/marker2.png'  
	}); 
	
	timerId = window.setInterval(function() {
		if (myProgressBar.value >= myProgressBar.maxValue){
			 path = window.location.pathname;
			/* path= path.split('/')[1];*/
			 clearInterval(timerId);
			window.location = window.location.protocol+'//'+window.location.host+path+'final.php';
		}else{
			myProgressBar.setValue(myProgressBar.value+1);
		}
		
	},
	100);
}

</script>

<style>

.progressbar_parent {
	
}
.progressbar_background {

}

.progressbar_wrapper {
	border: 1px solid #A0A0A0;
}
.progressbar_left {
	border: 1px solid #A0A0A0;
}
.progressbar_middle {
	border: 1px solid #A0A0A0;
}
.progressbar_right {
	border: 1px solid #A0A0A0;
}

.progressbar_text_horizontal {
	text-align: center;
	color: black;
	font-family: arial;
	font-size: 12px;
}
.progressbar_text_vertical {
	text-align: center;
	color: black;
	font-weight: bold;
	font-family: arial;
	font-size: 8px;
}
.progressbar_marker {
	background-image: url('images/marker2.png');
}

</style>
