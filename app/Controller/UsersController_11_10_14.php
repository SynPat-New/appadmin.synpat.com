<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class UsersController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('User','License','Commitment','Due_dilligence','Due_dilligence_title','Due_dilligence_event','Invitee','Chart_left','Chart_middle','Chart_right','Potential_syndicate');

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */
	public function index() {
		$this->Session->write('redirect','list_license');
		if(!$this->isUser()){
			$this->Session->setFlash('<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Please login first.</div>');
			$this->redirect(array('controller'=>'Users','action'=>'login'));
		} else {
			$this->redirect(array('controller'=>'Users','action'=>'list_license'));
		}
		$title_for_layout = 'Home';
		$this->set(compact('title_for_layout'));		
	}
	
	public function isUser(){
		if(!is_array($this->Session->read('User')) && count($this->Session->read('User'))==0){
			return false;
		} else { 
			return true;
		}
	}
	
	public function login(){
		$redirect = $this->Session->read('redirect');
		if(empty($redirect)){
			$redirect = 'list_license';
		}
		if($this->isUser()){
			$this->redirect(array('controller'=>'Users','action'=>$redirect));
		}
		if(!empty($this->data)){
			$checkLogin = $this->User->find('first',array('conditions'=>array('email'=>$this->data['login']['username'],'password'=>md5($this->data['login']['password']))));
			if(count($checkLogin)>0){
				$this->Session->write('User',$checkLogin);
				$this->redirect(array('controller'=>'Users','action'=>$redirect));
			} else {
				$this->Session->setFlash('<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong>Invalid username or password.</div>');
				$this->redirect(array('controller'=>'Users','action'=>'login'));
			}	
		}		
		$title_for_layout = 'Login';
		$this->layout='login';
		$this->set(compact('title_for_layout'));
	}
	
	public function logout(){
		$this->Session->destroy();
		$this->redirect(array('controller'=>'Users','action'=>'login'));
		
	}
	
	public function create_new_application(){
		$this->Session->write('redirect','create_new_application');
		if(!$this->isUser()){
			$this->Session->setFlash('<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Please login first.</div>');
			$this->redirect(array('controller'=>'Users','action'=>'login'));
		}
		$title_for_layout = 'Create new Application';
		$this->set(compact('title_for_layout'));
	}
	
	public function new_application(){
		if(count($this->data)){
			mkdir(ROOT.DS.'../synpat_com/syndicate/'.$this->data['project']['syndication_number'], 0777); 
			/*copy(APP."/webroot/dummy.php", ROOT.DS.'../synpat_com/syndicate/'.$this->data['project']['syndication_number']."/"."index.php");*/
			$this->recurse_copy(ROOT.DS."../synpat_com/syndicate/empty",ROOT.DS.'../synpat_com/syndicate/'.$this->data['project']['syndication_number']);
			$fp = fopen(ROOT.DS.'../synpat_com/syndicate/'.$this->data['project']['syndication_number'].'/license.php', 'w'); 
			fwrite($fp, "<?php define('LICENSE_NUMBER','".$this->data['project']['syndication_number']."');?>");
			$title_for_layout = 'New Application Created';
			$syndicationNumber = $this->data['project']['syndication_number'];
			$this->set(compact('title_for_layout','syndicationNumber'));
		} else {
			$this->Session->setFlash('<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Please enter syndication number.</div>');
			$this->redirect(array('controller'=>'Users','action'=>'create_new_application'));
		}
	}
	
	public function recurse_copy($src,$dst) { 
		$dir = opendir($src); 
		try{
			if(!mkdir($dst)){
				
			} 
			while(false !== ( $file = readdir($dir)) ) { 
				if (( $file != '.' ) && ( $file != '..' )) { 
					if ( is_dir($src . '/' . $file) ) { 
						$this->recurse_copy($src . '/' . $file,$dst . '/' . $file); 
					} 
					else { 
						copy($src . '/' . $file,$dst . '/' . $file); 
					} 
				} 
			} 
			closedir($dir); 
		}catch(ErrorException $ex) {
		  
		}
	} 


	public function list_license(){
		$this->Session->write('redirect','list');
		if(!$this->isUser()){
			$this->Session->setFlash('<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Please login first.</div>');
			$this->redirect(array('controller'=>'Users','action'=>'login'));
		}
		$licenses = $this->License->find('all',array('order'=>array('License.id'=>'ASC')));
		$title_for_layout = 'List Licenses';
		$this->set(compact('title_for_layout','licenses'));
	}	
	public function edit_license($ID = null){
		$this->Session->write('edit_license/'.$ID,'list');
		if(!$this->isUser()){
			$this->Session->setFlash('<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Please login first.</div>');
			$this->redirect(array('controller'=>'Users','action'=>'login'));
		}
		$license = $this->License->find('first',array('conditions'=>array('License.id'=>$ID)));
		
		if(count($license)==0){
			//pr($license);
			//die;
			$this->Session->setFlash('<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Record not found.</div>');
			$this->redirect(array('controller'=>'Users','action'=>'list_license'));
		}
		//pr($license);
		// Store previous uploaded file for purchase,license and buy
		 $purchase_file=$license['License']['purchase_file'];
		 $purchase_license=$license['License']['purchase_license'];
		 $purchase_buy=$license['License']['purchase_buy'];
		 $file_person=$license['License']['person_picture'];
		 $file_left=$license['License']['file_left'];
		 $file_middle=$license['License']['file_middle'];
		 $file_right=$license['License']['file_right'];
		
		/*--------------*/
		if(!empty($this->data)){
			//pr($this->data);
			$lid = $this->request->data('license.id');
			/*---First empty the record start---*/
			//$this->License->delete($lid);
			$this->Invitee->deleteAll(array('Invitee.license_id' => $lid));
			$this->Commitment->deleteAll(array('Commitment.license_id' => $lid));
			$this->Due_dilligence->deleteAll(array('Due_dilligence.license_id' => $lid));
			$this->Chart_left->deleteAll(array('Chart_left.license_id' => $lid));
			$this->Chart_middle->deleteAll(array('Chart_middle.license_id' => $lid));
			$this->Chart_right->deleteAll(array('Chart_right.license_id' => $lid));
			$this->Potential_syndicate->deleteAll(array('Potential_syndicate.license_id' => $lid));
			//die;
			/*---First empty the record end---*/
			$licenseData = $this->data;		
			if(isset($this->data['license']['person_picture']['name']) && !empty($this->data['license']['person_picture']['name'])){
				$imageFileName = WWW_ROOT . 'files' . DS .time()."__image_person".$this->data['license']['person_picture']['name'];
				if(move_uploaded_file($this->data['license']['person_picture']['tmp_name'],$imageFileName)) {
					$licenseData['license']['person_picture']= $imageFileName;	
				} else {
					$licenseData['license']['person_picture']=  $file_person;	
				}
			} else {
				$licenseData['license']['person_picture']=  $file_person;	
			}	
			if(isset($this->data['license']['file_left']['name']) && !empty($this->data['license']['file_left']['name'])){
				$imageFileName = WWW_ROOT . 'files' . DS .time()."__image_first".$this->data['license']['file_left']['name'];
				if(move_uploaded_file($this->data['license']['file_left']['tmp_name'],$imageFileName)) {
					$licenseData['license']['file_left']= $imageFileName;	
				} else {
					$licenseData['license']['file_left']=  $file_left;	
				}
			} else {
				$licenseData['license']['file_left']=  $file_left;	
			}
			if(isset($this->data['license']['file_middle']['name']) && !empty($this->data['license']['file_middle']['name'])){
				$imageFileName1 = WWW_ROOT . 'files' . DS .time()."__image_middle".$this->data['license']['file_middle']['name'];
				if(move_uploaded_file($this->data['license']['file_middle']['tmp_name'],$imageFileName1)) {
					$licenseData['license']['file_middle']= $imageFileName1;	
				} else {
					$licenseData['license']['file_middle']= $file_middle;	
				}
			} else {
				$licenseData['license']['file_middle']= $file_middle;	
			}
			if(isset($this->data['license']['file_right']['name']) && !empty($this->data['license']['file_right']['name'])){
				$imageFileName2 = WWW_ROOT . 'files' . DS .time()."__image_third".$this->data['license']['file_right']['name'];
				if(move_uploaded_file($this->data['license']['file_right']['tmp_name'],$imageFileName2)) {
					$licenseData['license']['file_right']= $imageFileName2;	
				} else {
					$licenseData['license']['file_right']= $file_right;	
				}
			} else {
				$licenseData['license']['file_right']= $file_right;	
			}
			$inviteesCompanies = json_decode($this->data['license']['invitees_companies']);
			
			
			$commitedData = json_decode($this->data['license']['commited_data']);
			$dueDilligenceData = json_decode($this->data['license']['due_dilligence_data']);
			$chart_left = json_decode($this->data['license']['chart_left']);
			$chart_middle = json_decode($this->data['license']['chart_middle']);
			$chart_right = json_decode($this->data['license']['chart_right']);
			$potential_syndicate = json_decode($this->data['license']['potential_syndicate']);
			//pr($chart_right);
			//die;
			unset($licenseData['license']['invitees_companies']);
			unset($licenseData['license']['due_dilligence_data']);
			unset($licenseData['license']['commited_data']);
			$this->License->id = $lid;
		
			$this->License->save($licenseData['license'],false);
			$licenseID = $lid;
			if($licenseID>0){
				
				if(count($inviteesCompanies)>0){
					//$this->Invitee->deleteAll(array('Invitee.license_id'=>$licenseID));
					for($i=0;$i<count($inviteesCompanies)-1;$i++){
						if($inviteesCompanies[$i][0]!="" ){
							$this->Invitee->create();
							$aar = "";
							if(isset($inviteesCompanies[$i][1])){
								$aar = $inviteesCompanies[$i][1];
							}
							$this->Invitee->save(array('license_id'=>$licenseID,'company'=>$inviteesCompanies[$i][0],'aar'=>$inviteesCompanies[$i][1]),false);							
						}
					}
				}

				if(count($commitedData)>0){
					//$this->Commitment->deleteAll(array('Commitment.license_id'=>$licenseID));
					for($i=0;$i<count($commitedData)-1;$i++){
						if($commitedData[$i][0]!="" && $commitedData[$i][1]!=""){
							$this->Commitment->create();
							$this->Commitment->save(array('license_id'=>$licenseID,'commitment_date'=>$commitedData[$i][0],'amount'=>$commitedData[$i][1],'ar'=>$commitedData[$i][2]),false);
						}
					}
				}
				if(count($dueDilligenceData)>0){
					for($i=0;$i<count($dueDilligenceData)-1;$i++){
						/*if($dueDilligenceData[$i][0]!="" && $dueDilligenceData[$i][1]!="" && $dueDilligenceData[$i][3]!=""){*/
						if($dueDilligenceData[$i][0]!=""){
							$this->Due_dilligence->create();
							$this->Due_dilligence->save(array('license_id'=>$licenseID,'patent_number'=>$dueDilligenceData[$i][0],'pdf'=>$dueDilligenceData[$i][1],'illustration'=>$dueDilligenceData[$i][2],'family'=>$dueDilligenceData[$i][3],'citation'=>$dueDilligenceData[$i][4]),false);
							$getData = $this->Due_dilligence_title->find('all',array('conditions'=>array('Due_dilligence_title.patent_id'=>substr($dueDilligenceData[$i][0],2))));
							if(count($getData)==0){
								$this->runAssignmentCrawler(substr($dueDilligenceData[$i][0],2));
							}
							$getEvent = $this->Due_dilligence_event->find('all',array('conditions'=>array('Due_dilligence_event.patent_id'=>$dueDilligenceData[$i][0])));
							if(count($getEvent)==0){
								try{
									$this->runEventCrawler($dueDilligenceData[$i][0]);
								} catch(Exception $e){
								}
							}						
						}
					}
				}
				
				/*Check Content for Assigment Patent*/
						
				
				/* modifiyed area */
				if(count($chart_left)>0){
					for($i=0;$i<count($chart_left)-1;$i++){
						if($chart_left[$i][0]!="" && $chart_left[$i][1]!=""){
							$this->Chart_left->create();
							$this->Chart_left->save(array('license_id'=>$licenseID,'country'=>$chart_left[$i][0],'applications'=>$chart_left[$i][1],'patents'=>$chart_left[$i][2]),false);
						}
					}
				}
				if(count($chart_middle)>0){
					for($i=0;$i<count($chart_middle)-1;$i++){
						if($chart_middle[$i][0]!="" && $chart_middle[$i][1]!=""){
							$this->Chart_middle->create();
							$this->Chart_middle->save(array('license_id'=>$licenseID,'technologies'=>$chart_middle[$i][0],'data'=>$chart_middle[$i][1]),false);
						}
					}
				}
				if(count($chart_right)>0){
					for($i=0;$i<count($chart_right)-1;$i++){
						if($chart_right[$i][0]!="" && $chart_right[$i][1]!=""){
							$this->Chart_right->create();
							$this->Chart_right->save(array('license_id'=>$licenseID,'year'=>$chart_right[$i][0],'data'=>$chart_right[$i][1]),false);
						}
					}
				}
				/*
				print_r($potential_syndicate);
				die;*/
				if(count($potential_syndicate)>0){
					for($i=0;$i<count($potential_syndicate)-1;$i++){
						if($potential_syndicate[$i][0]!="" && $potential_syndicate[$i][1]!=""){
							$this->Potential_syndicate->create();
							$this->Potential_syndicate->save(array('license_id'=>$licenseID,'participate'=>$potential_syndicate[$i][0],'fees'=>$potential_syndicate[$i][1]),false);
						}
					}
				}
				
				/* modifiyed area */
				$this->Session->setFlash('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Record Updated Successfully!</strong>.</div>');
			}
			
			$this->redirect(array('controller'=>'Users','action'=>'list_license'));
		}
		/*--------------*/
		$title_for_layout = 'Edit License';
		$this->set(compact('title_for_layout','license'));
	}
	
	
	function findWordInString($string){
		$arrayString = "";
		if(strlen($string)>0){
			$firstExec = strpos($string,'Exec&nbsp;Dt:');
			$name = trim(substr($string,0,$firstExec));
			$string = substr($string,$firstExec);
			$date = trim(substr($string,13,10));
			$arrayString[] = array('Name'=>$name ,"Date"=>$date);
			$string = substr($string,23);
			if(strlen($string)>0){
				$arrayNewString = $this->findWordInString($string);
				if(count($arrayNewString)>0){
					for($s=0;$s<count($arrayNewString);$s++){
						$arrayString[] = array("Name"=>$arrayNewString[$s]['Name'],'Date'=>$arrayNewString[$s]['Date']);
					}
				}
			}
		}
		return $arrayString;
	}
	
	function runEventCrawler($getPatentNumber){
		if(strpos($getPatentNumber,'US')===false){
			$getPatentNumber = 'US'.$getPatentNumber;
		}
		require_once APP . 'Vendor' . DS.'simple_html_dom.php';
		set_time_limit(0);
		//$content = file_get_contents("https://www.google.com/patents/".$getPatentNumber);
		$url = "https://www.google.com/patents/".$getPatentNumber;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $content = curl_exec($ch);
		curl_close($ch);
		$forwardArray = array();
		$needle = '<a id="legal-events"></a>';
		if (strpos($content, $needle,0) !== false) {
			$startPost = strpos($content, $needle);
			$anotherNeedle = '<div class="patent-section-footer"></div>';
			if (strpos($content, $anotherNeedle,1) !== false) {
				$endPost =  strpos($content, $anotherNeedle);		
				$dataPatent =  substr($content,$startPost,$endPost);
				$html = str_get_html($dataPatent);
				$forwardArray ="";
				$n=0;
				foreach($html->find('<table class="patent-data-table">') as $asTable) {											
					foreach($asTable->find('tr') as $tr){	
						if($n>0){
							$anchor = "";
							$fillingDate = "";
							$publicationDate = "";
							$applicant = "";	
							$c=0;
							foreach($tr->find('td') as $td){
								switch($c){
									case 0:
										$anchor = $td->innertext;
									break;
									case 1:
										$fillingDate = $td->innertext;														
									break;
									case 2:
										$publicationDate = $td->innertext;
									break;
									case 3:
										$applicant = $td->innertext;
									break;
								}
								$c++;
							}
							$forwardArray[] = array('date'=>$anchor,'code'=>$fillingDate,'event'=>$publicationDate,'description'=>$applicant);													
						}
						$n++;
					}
					//echo $dataPatent;
				} 
			}
		}
		$needle = '<table class="patent-bibdata">';
		$key = array();
		$value = array();		
		if(strpos($content, $needle,0) !== false) {
			$startPost = strpos($content, $needle);
			$anotherNeedle = '<div class="number-and-title">';
			if (stripos($content, $anotherNeedle,1) !== false) {
				$endPost =  stripos($content, $anotherNeedle);										
				$dataPatent =  substr($content,$startPost,$endPost);									
				$html = str_get_html($dataPatent);
				$flag=0;
				foreach($html->find('<table class="patent-bibdata">') as $asTable) {		
					foreach($asTable->find('tr') as $tr){	
						$c=0;
						foreach($tr->find('td') as $td){												
							if( stripos($td->innertext, 'External Links:')!==false){
								$flag=1;
							}
							if($flag==0){
								switch($c){
									case 0:
										$key[] = $td->innertext;
									break;
									case 1:
										if($td->find('a')){
											$anchor_text = "";
											foreach($td->find('a') as $anchor){
												$anchor_text .= $anchor->innertext.", ";
											}																
											$value[] = substr($anchor_text,0,-2);		
										} else {
											$value[] = $td->innertext;		
										}										
									break;														
								}													
							}
							$c++;
						}																			
					}
				}
			}
		}
		$newKey = array();
		$newVal = array();
		if(is_array($key))
		{
			$key = array_unique($key);
			$key = array_values($key);
		}
		
		if(is_array($value) && count($value)>0)
		{
			unset($value[8]);
			$value = array_values($value);
		}
		if(count($key)>0 && count($key)>10){
			for($k=0;$k<10;$k++){
				$newKey[]=$key[$k];
				$newVal[]=$value[$k];
			}
		}
		$newPubData = array();
		if(is_array($newKey) && is_array($newVal) && count($newKey) > 0 && count($newVal) > 0){
			$newPubData = array_combine($newKey, $newVal);
		}
		$publication ="";
		if(count($newPubData)>0){
			$publication .="<table class='table' style='border:1px solid #d1d1d1;background-color:#f5f5f5;margin-top:15px;'>";
			foreach($newPubData as $key=>$value){
				$publication .="<tr>";
				$publication .="<td style='min-width:120px; max-width:120px;'><b>".$key."</b></td>";
				$publication .="<td>".$value."</td>";
				$publication .="</tr>";
			}
			$publication .="</table>";
		}
		
		if(count($forwardArray)>0){
			for($j=0;$j<count($forwardArray);$j++){
				$eventArray = array('patent_id'=>$getPatentNumber,'date_event'=>$forwardArray[$j]['date'],'code'=>$forwardArray[$j]['code'],'event_title'=>$forwardArray[$j]['event'],'description'=>$forwardArray[$j]['description'],'publication'=>$publication);
				$this->Due_dilligence_event->create();
				$this->Due_dilligence_event->save($eventArray);
			}
		}
	}
	
	function runAssignmentCrawler($patentNUMBER){
		$url = 'http://assignments.uspto.gov/assignments/q?db=pat&qt=pat&reel=&frame=&pat='.$patentNUMBER;
		$html=file_get_contents($url);
		$stringPosi = strstr($html,'Total Assignments');	
		$lastStringEnd = strpos($stringPosi,'Search Results');
		$refreshString = substr($stringPosi,0,$lastStringEnd);	
		/*Last Occurenece Total Assignments:*/
		$refreshString = strip_tags($refreshString);
		$findLastTAssPost = strrpos($refreshString,'Total Assignments:');
		$findFirstTAssPost = strpos($refreshString,'Patent&nbsp;#:');
		$TotalAssignment = trim(substr( $refreshString , 0,$findFirstTAssPost));
		$newSubstring = substr($refreshString,$findFirstTAssPost);	
		$findFirstIssuedDate = strpos($newSubstring,'Issue&nbsp;Dt:');
		$Patent = trim(substr( $newSubstring , 0,$findFirstIssuedDate));
		$newSubstring = substr($newSubstring,$findFirstIssuedDate);	
		$findFirstAppDate = strpos($newSubstring,'Application&nbsp;#:');
		$IssuesDate = trim(substr( $newSubstring , 0,$findFirstAppDate));
		$newSubstring = substr($newSubstring,$findFirstAppDate);	
		$findFirstFillingDate = strpos($newSubstring,'Filing&nbsp;Dt:');
		$ApplicationDate = trim(substr( $newSubstring , 0,$findFirstFillingDate));
		$newSubstring = substr($newSubstring,$findFirstFillingDate);	
		$findFirstPublicationDate = strpos($newSubstring,'Publication&nbsp;#:');
		if($findFirstPublicationDate===false){
			$findFirstInvestor = strpos($newSubstring,'Inventors:');
			if($findFirstInvestor===false){
				$findFirstInvestor = strpos($newSubstring,'Inventor:');
			}
			$FillingDate = trim(substr( $newSubstring , 0,$findFirstInvestor));
			$newSubstring = substr($newSubstring,$findFirstInvestor);	
			$findFirstTitle = strpos($newSubstring,'Title:');
			$Investors = trim(substr( $newSubstring , 0,$findFirstTitle));
			$newSubstring = substr($newSubstring,$findFirstTitle);	
			$findFirstAssigment = strpos($newSubstring,'Assignment:');
			$Title = trim(substr( $newSubstring , 0,$findFirstAssigment));
		} else {
			$FillingDate = trim(substr( $newSubstring , 0,$findFirstPublicationDate));
			$newSubstring = substr($newSubstring,$findFirstPublicationDate);	
			$findFirstPubDate = strpos($newSubstring,'Pub&nbsp;Dt:');
			$PublicationDate = trim(substr( $newSubstring , 0,$findFirstPubDate));
			$newSubstring = substr($newSubstring,$findFirstPubDate);	
			$findFirstInvestor = strpos($newSubstring,'Inventors:');
			if($findFirstInvestor===false){
				$findFirstInvestor = strpos($newSubstring,'Inventor:');
			}
			$PubDate = trim(substr( $newSubstring , 0,$findFirstInvestor));
			$newSubstring = substr($newSubstring,$findFirstInvestor);	
			$findFirstTitle = strpos($newSubstring,'Title:');
			$Investors = trim(substr( $newSubstring , 0,$findFirstTitle));
			$newSubstring = substr($newSubstring,$findFirstTitle);	
			$findFirstAssigment = strpos($newSubstring,'Assignment:');
			$Title = trim(substr( $newSubstring , 0,$findFirstAssigment));
		}
		$expoTotalAssigment = explode(':',$TotalAssignment);
		//echo "<pre>";
		//print_r($expoTotalAssigment);
		$totalAssignmentCount = preg_replace('/\n/', '', $expoTotalAssigment[1]);
		$FillingDate = explode(':',$FillingDate);
		$FillingDate = preg_replace('/\n/', '', $FillingDate[1]);
		if(!empty($Investors)){
			$explodeFirstInvestor = explode(':',$Investors);
			$explodeFirstInvestor = preg_replace('/\n/', '', $explodeFirstInvestor[1]);
			$explodeInventor = explode(',',$explodeFirstInvestor);
			if(count($explodeInventor)>0){
				for($v=0;$v<count($explodeInventor);$v++){
					$titleFile ="";
					$assignor="";
					$assignee="";
					$executiveDt ="";
					$this->Due_dilligence_title->create();
					if(!empty($explodeInventor[$v])){
						$splitString	= str_split($FillingDate,2);
						$stringSH = "";
						if(count($splitString)>0){
							for($st=0;$st<count($splitString);$st++){
								$stringSH .= trim($splitString[$st]);
							}
						}
						$executiveDt = date('Y-m-d',strtotime($stringSH));
						$assignee= ucwords(strtolower(trim($explodeInventor[$v])));
						$this->Due_dilligence_title->save(array('patent_id'=>$patentNUMBER,'assignee'=>$assignee,'assignee_date'=>$executiveDt));
					}
				}
			}
		}
		$singleArray = array();
		$multiArray = "";
		for($i=0;$i<(int)$totalAssignmentCount;$i++){	
			$findFirstAssigment = strpos($newSubstring,'Assignment:');
			$newSubstring = substr($newSubstring,$findFirstAssigment);	
			$findFirst = strpos($newSubstring,'Reel/Frame:');
			$newSubstring = substr($newSubstring,$findFirst);
			$findRecorded = strpos($newSubstring,'Recorded:');
			$Reel = trim(substr( $newSubstring , 0,$findRecorded));
			/*echo "<br/>".$Reel."<br/>";*/		
			$newSubstring = substr($newSubstring,$findRecorded);		
			$findPages = strpos($newSubstring,'Pages:');
			$Recorded = trim(substr( $newSubstring , 0,$findPages));
			/*echo $Recorded."<br/>";*/
			$newSubstring = substr($newSubstring,$findPages);
			$findConveyance = strpos($newSubstring,'Conveyance:');
			$Pages = trim(substr( $newSubstring , 0,$findConveyance));
			/*echo $Pages."<br/>";*/
			$newSubstring = substr($newSubstring,$findConveyance);
			
			/*$findAssignors = strpos($newSubstring,'Assignors:');*/
			$findAssignors = strpos($newSubstring,'Assign');		
			$Conveyance = trim(substr( $newSubstring , 0,$findAssignors));
			/*echo $Conveyance."<br/>";	*/	
			$newSubstring = substr($newSubstring,$findAssignors);
			
			$findAssignee = strpos($newSubstring,'Assignee:');
			$Assignors = trim(substr( $newSubstring , 0,$findAssignee));
			/*echo $Assignors."<br/>";*/
			$newSubstring = substr($newSubstring,$findAssignee);
			$findCorrespondent = strpos($newSubstring,'Correspondent:');
			$Assignee = trim(substr( $newSubstring , 0,$findCorrespondent));
			/*echo $Assignee."<br/>";*/
			$newSubstring = substr($newSubstring,$findCorrespondent);
			$findLastAssignment = strpos($newSubstring,'Assignment:');
			if($findLastAssignment===false){
				$Correspondent = trim($newSubstring);
			} else {
				$Correspondent = trim(substr( $newSubstring , 0,$findLastAssignment));
				$newSubstring = substr($newSubstring,$findLastAssignment);
			}
			/*
			echo $Correspondent."<br/>";
			echo "==============================";*/
			
			$allAssignor = trim(preg_replace('/\n/','',$Assignors));
			$allAssignorPos = strpos($allAssignor,':');
			$allAssignor = substr($allAssignor,$allAssignorPos + 1);					
			$dataArray = $this->findWordInString($allAssignor);
			$Conveyance = explode(':',$Conveyance);
			$Conveyance = trim(preg_replace('/\n/','',$Conveyance[1]));			
			$strPos = strpos($Conveyance,'(');
			if($strPos!==false){
				$Conveyance = substr($Conveyance,0,$strPos);
			}	
			
			$record = explode(':',$Recorded);
			$record = trim(preg_replace('/\n/','',$record[1]));
			$Assignee = explode(':',$Assignee);

			$dbdd = explode(' ',trim($Assignee[1]));
			if(count($dbdd)>=4){
				$db1 = explode('.',$dbdd[3]);
				$lastString = "";
				if(isset($db1[0])){
					$pat = '(\d+)';
					preg_match($pat,$db1[0],$matches);
					if(isset($matches[0])){
						$getPos = strpos($db1[0],$matches[0]);
						if($getPos!==false){
							$lastString =  substr($db1[0],0,$getPos);
						} else {
							$lastString = $db1[0];
						}
					}else {
						$lastString = $db1[0];
					}
				}
				
				$Assignee = $dbdd[0]." ".$dbdd[1]." ".$dbdd[2]." ".$lastString;							
			}	
			if(count($dataArray)>0){
				for($a=0;$a<count($dataArray);$a++){
					if(!in_array(trim($Conveyance),$singleArray)){
						$singleArray[] = trim($Conveyance);
					} 
					$titleFile ="";
					$assignor="";
					$assignee="";
					$executiveDt ="";
					$recorded ="";
					$this->Due_dilligence_title->create();
					$executiveDt = date('Y-m-d',strtotime($dataArray[$a]['Date']));
					$assignor = ucwords(strtolower($dataArray[$a]['Name']));
					$assignee = ucwords(strtolower($Assignee));
					$recorded = $record;
					$titleFile = ucwords(strtolower($Conveyance));
					$this->Due_dilligence_title->save(array('patent_id'=>$patentNUMBER,'rights'=>$titleFile,'assignor'=>$assignor,'assignee'=>$assignee,'assignee_date'=>$executiveDt,'recorded'=>$recorded));
				}
			}
		}
	}
	
	
	public function add_license(){
		$this->Session->write('redirect','add_license');
		if(!$this->isUser()){
			$this->Session->setFlash('<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Please login first.</div>');
			$this->redirect(array('controller'=>'Users','action'=>'login'));
		}
		if(!empty($this->data)){
			//pr($this->data);
			//die;
			
			$purchase_file="";
			$purchase_license="";
			$purchase_buy="";
			$licenseData = $this->data;
			/*
			if(isset($this->data['license']['purchase_file']['name']) && !empty($this->data['license']['purchase_file']['name'])){
				$filename1 = WWW_ROOT . 'files' . DS .time()."_purchase_file".$this->data['license']['purchase_file']['name'];			
				if(move_uploaded_file($this->data['license']['purchase_file']['tmp_name'],$filename1)) {
					$licenseData['license']['purchase_file']= $filename1;	
				} else {
					$licenseData['license']['purchase_file']= "";	
				}
			} else {
				$licenseData['license']['purchase_file']= "";	
			}
			if(isset($this->data['license']['purchase_license']['name']) && !empty($this->data['license']['purchase_license']['name'])){
				$filename2 = WWW_ROOT . 'files' . DS .time()."__purchase_license".$this->data['license']['purchase_license']['name'];
				//$filename = "app/webroot/files/".time()."_".$this->data['license']['purchase_license']['name']; 
				if(move_uploaded_file($this->data['license']['purchase_license']['tmp_name'],$filename2)) {
					$licenseData['license']['purchase_license']= $filename2;	
				} else {
					$licenseData['license']['purchase_license']= "";	
				}
			} else {
					$licenseData['license']['purchase_license']= "";	
			}
			if(isset($this->data['license']['purchase_buy']['name']) && !empty($this->data['license']['purchase_buy']['name'])){
				$filename3 = WWW_ROOT . 'files' . DS .time()."__purchase_buy".$this->data['license']['purchase_buy']['name'];
				//$filename = "app/webroot/files/".time()."_".$this->data['license']['purchase_buy']['name']; 
				if(move_uploaded_file($this->data['license']['purchase_buy']['tmp_name'],$filename3)) {
					$licenseData['license']['purchase_buy']= $filename3;	
				} else {
					$licenseData['license']['purchase_buy']= "";	
				}
			} else {
				$licenseData['license']['purchase_buy']= "";	
			}
			*/
			if(isset($this->data['license']['person_picture']['name']) && !empty($this->data['license']['person_picture']['name'])){
				$imageFileName = WWW_ROOT . 'files' . DS .time()."__image_person".$this->data['license']['person_picture']['name'];
				if(move_uploaded_file($this->data['license']['person_picture']['tmp_name'],$imageFileName)) {
					$licenseData['license']['person_picture']= $imageFileName;	
				} else {
					$licenseData['license']['person_picture']=  "";	
				}
			} else {
				$licenseData['license']['person_picture']=  "";	
			}	
			if(isset($this->data['license']['file_left']['name']) && !empty($this->data['license']['file_left']['name'])){
				$imageFileName = WWW_ROOT . 'files' . DS .time()."__image_first".$this->data['license']['file_left']['name'];
				if(move_uploaded_file($this->data['license']['file_left']['tmp_name'],$imageFileName)) {
					$licenseData['license']['file_left']= $imageFileName;	
				} else {
					$licenseData['license']['file_left']= "";	
				}
			} else {
				$licenseData['license']['file_left']= "";	
			}
			if(isset($this->data['license']['file_middle']['name']) && !empty($this->data['license']['file_middle']['name'])){
				$imageFileName1 = WWW_ROOT . 'files' . DS .time()."__image_middle".$this->data['license']['file_middle']['name'];
				
				if(move_uploaded_file($this->data['license']['file_middle']['tmp_name'],$imageFileName1)) {
					$licenseData['license']['file_middle']= $imageFileName1;	
				} else {
					$licenseData['license']['file_middle']= "";	
				}
			} else {
				$licenseData['license']['file_middle']= "";	
			}
			if(isset($this->data['license']['file_right']['name']) && !empty($this->data['license']['file_right']['name'])){
				$imageFileName2 = WWW_ROOT . 'files' . DS .time()."__image_third".$this->data['license']['file_right']['name'];
				
				if(move_uploaded_file($this->data['license']['file_right']['tmp_name'],$imageFileName2)) {
					$licenseData['license']['file_right']= $imageFileName2;	
				} else {
					$licenseData['license']['file_right']= "";	
				}
			} else {
				$licenseData['license']['file_right']= "";	
			}
			$inviteesCompanies = json_decode($this->data['license']['invitees_companies']);
			$commitedData = json_decode($this->data['license']['commited_data']);
			$dueDilligenceData = json_decode($this->data['license']['due_dilligence_data']);
			$chart_left = json_decode($this->data['license']['chart_left']);
			$chart_middle = json_decode($this->data['license']['chart_middle']);
			$chart_right = json_decode($this->data['license']['chart_right']);			
			$potential_syndicate = json_decode($this->data['license']['potential_syndicate']);			
			unset($licenseData['license']['invitees_companies']);
			unset($licenseData['license']['due_dilligence_data']);
			unset($licenseData['license']['commited_data']);
			$this->License->create();
			$this->License->save($licenseData['license'],false);
			$licenseID = $this->License->getLastInsertId();
			if($licenseID>0){
				if(count($inviteesCompanies)>0){
					for($i=0;$i<count($inviteesCompanies)-1;$i++){
						if($inviteesCompanies[$i][0]!="" && $inviteesCompanies[$i][1]!=""){
							$this->Invitee->create();
							$this->Invitee->save(array('license_id'=>$licenseID,'company'=>$inviteesCompanies[$i][0],'aar'=>$inviteesCompanies[$i][1]),false);
						}
					}
				}if(count($commitedData)>0){
					for($i=0;$i<count($commitedData)-1;$i++){
						if($commitedData[$i][0]!="" && $commitedData[$i][1]!=""){
							$this->Commitment->create();
							$this->Commitment->save(array('license_id'=>$licenseID,'commitment_date'=>$commitedData[$i][0],'amount'=>$commitedData[$i][1],'ar'=>$commitedData[$i][2]),false);
						}
					}
				}
				if(count($dueDilligenceData)>0){
					for($i=0;$i<count($dueDilligenceData)-1;$i++){
						if($dueDilligenceData[$i][0]!=""){
							/*
							$parent=0;
							if(!empty($dueDilligenceData[$i][2])){
			
								$getData = $this->Due_dilligence->find('first',array('conditions'=>array('patent_number'=>$dueDilligenceData[$i][2])));
								if(count($getData)>0){
									$parent = $getData['Due_dilligence']['id'];
								}
							}*/
							$this->Due_dilligence->create();
							$this->Due_dilligence->save(array('license_id'=>$licenseID,'patent_number'=>$dueDilligenceData[$i][0],'pdf'=>$dueDilligenceData[$i][1],'illustration'=>$dueDilligenceData[$i][2],'family'=>$dueDilligenceData[$i][3],'citation'=>$dueDilligenceData[$i][4]),false);
							$getData = $this->Due_dilligence_title->find('all',array('conditions'=>array('Due_dilligence_title.patent_id'=>substr($dueDilligenceData[$i][0],2))));
							if(count($getData)==0){
								$this->runAssignmentCrawler(substr($dueDilligenceData[$i][0],2));
							}
							$getEvent = $this->Due_dilligence_event->find('all',array('conditions'=>array('Due_dilligence_event.patent_id'=>$dueDilligenceData[$i][0])));
							if(count($getEvent)==0){
								try{
									$this->runEventCrawler($dueDilligenceData[$i][0]);
								} catch(Exception $e){
								}
							}	
						}
					}
				}
				/* modifiyed area */
				if(count($chart_left)>0){
					for($i=0;$i<count($chart_left)-1;$i++){
						if($chart_left[$i][0]!="" && $chart_left[$i][1]!=""){
							$this->Chart_left->create();
							$this->Chart_left->save(array('license_id'=>$licenseID,'country'=>$chart_left[$i][0],'applications'=>$chart_left[$i][1],'patents'=>$chart_left[$i][2]),false);
						}
					}
				}
				if(count($chart_middle)>0){
					for($i=0;$i<count($chart_middle)-1;$i++){
						if($chart_middle[$i][0]!="" && $chart_middle[$i][1]!=""){
							$this->Chart_middle->create();
							$this->Chart_middle->save(array('license_id'=>$licenseID,'technologies'=>$chart_middle[$i][0],'data'=>$chart_middle[$i][1]),false);
						}
					}
				}
				if(count($chart_right)>0){
					for($i=0;$i<count($chart_right)-1;$i++){
						if($chart_right[$i][0]!="" && $chart_right[$i][1]!=""){
							$this->Chart_right->create();
							$this->Chart_right->save(array('license_id'=>$licenseID,'Year'=>$chart_right[$i][0],'data'=>$chart_right[$i][1]),false);
						}
					}
				}
				
				if(count($potential_syndicate)>0){
					for($i=0;$i<count($potential_syndicate)-1;$i++){
						if($potential_syndicate[$i][0]!="" && $potential_syndicate[$i][1]!=""){
							$this->Potential_syndicate->create();
							$this->Potential_syndicate->save(array('license_id'=>$licenseID,'participate'=>$potential_syndicate[$i][0],'fees'=>$potential_syndicate[$i][1]),false);
						}
					}
				}
				/* modifiyed area */
				$this->Session->setFlash('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Saved!</strong>.</div>');
			}			
			$this->redirect(array('controller'=>'Users','action'=>'list_license'));
		}
		$title_for_layout = 'Add License';
		$newDataForAccordionText = $this->License->find('first',array('conditions'=>array('License.license_number'=>'12345')));
		$this->set(compact('title_for_layout','newDataForAccordionText'));
	}
}
