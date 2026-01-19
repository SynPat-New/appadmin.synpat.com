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
	public $uses = array('User','License','Commitment','Due_dilligence','Invitee','Chart_left','Chart_middle','Chart_right');

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
	
	function logout(){
		$this->Session->destroy();
		$this->redirect(array('controller'=>'Users','action'=>'login'));
		
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
		//pr($license);
		//die;
		if(count($license)==0){
			$this->Session->setFlash('<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Record not found.</div>');
			$this->redirect(array('controller'=>'Users','action'=>'list_license'));
		}
		//pr($license);
		// Store previous uploaded file for purchase,license and buy
		 $purchase_file=$license['License']['purchase_file'];
		 $purchase_license=$license['License']['purchase_license'];
		 $purchase_buy=$license['License']['purchase_buy'];
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
			//die;
			/*---First empty the record end---*/
			$licenseData = $this->data;
			if(isset($this->data['license']['purchase_file']['name']) && !empty($this->data['license']['purchase_file']['name'])){
				$filename1 = WWW_ROOT . 'files' . DS .time()."_purchase_file".$this->data['license']['purchase_file']['name'];			
				if(move_uploaded_file($this->data['license']['purchase_file']['tmp_name'],$filename1)) {
					$licenseData['license']['purchase_file']= $filename1;	
				} else {
					$licenseData['license']['purchase_file']= $purchase_file;	
				}
			} else {
				$licenseData['license']['purchase_file']= $purchase_file;	
			}
			if(isset($this->data['license']['purchase_license']['name']) && !empty($this->data['license']['purchase_license']['name'])){
				$filename2 = WWW_ROOT . 'files' . DS .time()."__purchase_license".$this->data['license']['purchase_license']['name'];
				if(move_uploaded_file($this->data['license']['purchase_license']['tmp_name'],$filename2)) {
					$licenseData['license']['purchase_license']= $filename2;	
				} else {
					$licenseData['license']['purchase_license']= $purchase_license;	
				}
			} else {
				$licenseData['license']['purchase_license']= $purchase_license;	
			}
			if(isset($this->data['license']['purchase_buy']['name']) && !empty($this->data['license']['purchase_buy']['name'])){
				$filename3 = WWW_ROOT . 'files' . DS .time()."__purchase_buy".$this->data['license']['purchase_buy']['name'];
				if(move_uploaded_file($this->data['license']['purchase_buy']['tmp_name'],$filename3)) {
					$licenseData['license']['purchase_buy']= $filename3;	
				} else {
					$licenseData['license']['purchase_buy']= $purchase_buy;	
				}
			} else {
				$licenseData['license']['purchase_buy']= $purchase_buy;	
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
					$this->Invitee->deleteAll(array('Invitee.license_id'=>$licenseID));
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
					$this->Commitment->deleteAll(array('Commitment.license_id'=>$licenseID));
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
						if($dueDilligenceData[$i][0]!="" && $dueDilligenceData[$i][1]!=""){
							$parent=0;
							if(!empty($dueDilligenceData[$i][2])){
								$getData = $this->Due_dilligence->find('first',array('conditions'=>array('patent_number'=>$dueDilligenceData[$i][2])));
								if(count($getData)>0){
									$parent = $getData['Due_dilligence']['id'];
								}
							}
							$this->Due_dilligence->create();
							$this->Due_dilligence->save(array('license_id'=>$licenseID,'patent_number'=>$dueDilligenceData[$i][0],'pat_app_prov'=>$dueDilligenceData[$i][1],'parent_1'=>$parent,'parent_2'=>$dueDilligenceData[$i][2],'filled'=>$dueDilligenceData[$i][3],'published'=>$dueDilligenceData[$i][4],'expiration'=>$dueDilligenceData[$i][5],'c_cip_cre'=>$dueDilligenceData[$i][6],'country'=>$dueDilligenceData[$i][7],'counterparts'=>$dueDilligenceData[$i][8],'applicant'=>$dueDilligenceData[$i][9],'assignee'=>$dueDilligenceData[$i][10],'licensees'=>$dueDilligenceData[$i][11],'illustration'=>$dueDilligenceData[$i][12],'family'=>$dueDilligenceData[$i][13],'citation'=>$dueDilligenceData[$i][14]),false);
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
							$this->Chart_right->save(array('license_id'=>$licenseID,'year'=>$chart_right[$i][0],'data'=>$chart_right[$i][1]),false);
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
						if($dueDilligenceData[$i][0]!="" && $dueDilligenceData[$i][1]!="" && $dueDilligenceData[$i][3]!=""){
							$parent=0;
							if(!empty($dueDilligenceData[$i][2])){
			
								$getData = $this->Due_dilligence->find('first',array('conditions'=>array('patent_number'=>$dueDilligenceData[$i][2])));
								if(count($getData)>0){
									$parent = $getData['Due_dilligence']['id'];
								}
							}
							$this->Due_dilligence->create();
							$this->Due_dilligence->save(array('license_id'=>$licenseID,'patent_number'=>$dueDilligenceData[$i][0],'pat_app_prov'=>$dueDilligenceData[$i][1],'parent_1'=>$parent,'parent_2'=>$dueDilligenceData[$i][2],'filled'=>$dueDilligenceData[$i][3],'published'=>$dueDilligenceData[$i][4],'expiration'=>$dueDilligenceData[$i][5],'c_cip_cre'=>$dueDilligenceData[$i][6],'country'=>$dueDilligenceData[$i][7],'counterparts'=>$dueDilligenceData[$i][8],'applicant'=>$dueDilligenceData[$i][9],'assignee'=>$dueDilligenceData[$i][10],'licensees'=>$dueDilligenceData[$i][11],'illustration'=>$dueDilligenceData[$i][12],'family'=>$dueDilligenceData[$i][13],'citation'=>$dueDilligenceData[$i][14]),false);
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
				/* modifiyed area */
				$this->Session->setFlash('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Saved!</strong>.</div>');
			}			
			$this->redirect(array('controller'=>'Users','action'=>'list_license'));
		}
		$title_for_layout = 'Add License';
		$this->set(compact('title_for_layout'));
	}
}
