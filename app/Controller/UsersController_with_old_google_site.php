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

class UsersController extends AppController {


	public $uses = array('User','License','Commitment','Due_dilligence','Due_dilligence_title','Due_dilligence_event','Invitee','Chart_left','Chart_middle','Chart_right','Potential_syndicate','Store_user','Due_dilligence_license','Map_patent_license','Due_dilligence_standard','Due_dilligence_party','Due_dilligence_party','Due_dilligence_collateral','Due_dilligence_invention','Due_dilligence_illustration','Due_dilligence_citation','Request_to_participates','Potential_participates','Revised_documents','Comparable','Damage','Due_dilligence_counterpart');


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
	
	/*public function findRequestToParticipant($licenseNumber=null){
		$allRequestData =array();
		$allRequestLicenseData =array();		
		if($licenseNumber!=null){			
			$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$this->data['license_number'])));
			if(count($getLicenseData)>0){
				$licenseID = $getLicenseData['License']['id'];
				$allRequestData = $this->Request_to_participates->find("all",array('conditions'=>array('id'=>$licenseID,"type"=>0),'order'=>array('Request_to_participates.id'=>'DESC')));
				$allRequestLicenseData = $this->Request_to_participates->find("all",array('conditions'=>array('id'=>$licenseID,"type"=>1),'order'=>array('Request_to_participates.id'=>'DESC')));
			}
		} else {			
			$allRequestData = $this->Request_to_participates->find("all",array('conditions'=>array("type"=>0),'order'=>array('Request_to_participates.id'=>'DESC')));
			$allRequestLicenseData = $this->Request_to_participates->find("all",array('conditions'=>array("type"=>1),'order'=>array('Request_to_participates.id'=>'DESC')));
		}
		echo json_encode(array('participant'=>$allRequestData,'license'=>$allRequestLicenseData));
		die;
	}*/
	
	
	public function getLicenseData(){
		if(count($this->data)>0){
			$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$this->data['license_number'])));
			echo json_encode($getLicenseData);
		} else {
			echo json_encode(array());
		}
		die;
	}
	public function updateillustrationData(){
		$data = 0;
		if(count($this->data)){
			$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$this->data['license_number'])));
			if(count($getLicenseData)>0){
				try{
					$inventionDatas = json_decode($this->data['inventions_data']);
					if(count($inventionDatas)>0){
						for($i=0;$i<count($inventionDatas);$i++){
							$dueDilligenceIllustrationData = $this->Due_dilligence_illustration->find('first',array('conditions'=>array('patent_id'=>$inventionDatas[$i]->patent_id)));
							if(count($dueDilligenceIllustrationData)==0){
								$this->Due_dilligence_illustration->create();
								$this->Due_dilligence_illustration->save(array('patent_id'=>$inventionDatas[$i]->patent_id,'file'=>$inventionDatas[$i]->file),false);
								$data = $this->Due_dilligence_illustration->getInsertID();
							} else {
								$this->Due_dilligence_illustration->clear();
								$this->Due_dilligence_illustration->save(array('patent_id'=>$inventionDatas[$i]->patent_id,'file'=>$inventionDatas[$i]->file,'id'=>$dueDilligenceIllustrationData['Due_dilligence_illustration']['id']),false);
								$data = $this->Due_dilligence_illustration->id;
							}
						}
					}
				} catch(Exception $e){
					
				}
			}
		}
		echo $data;
		die;
	}
	public function updateInventionData(){
		$data = 0;
		if(count($this->data)){
			$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$this->data['license_number'])));
			if(count($getLicenseData)>0){
				try{ 
					$inventionDatas = json_decode($this->data['inventions_data']);
					if(count($inventionDatas)>0){
						for($i=0;$i<count($inventionDatas);$i++){
							$dueDilligenceInventionData = $this->Due_dilligence_invention->find('first',array('conditions'=>array('patent_id'=>$inventionDatas[$i]->patent_id)));
							if(count($dueDilligenceInventionData)==0){
								$this->Due_dilligence_invention->create();
								$this->Due_dilligence_invention->save(array('patent_id'=>$inventionDatas[$i]->patent_id,'problem'=>nl2br($inventionDatas[$i]->problem),'solution'=>nl2br($inventionDatas[$i]->solution),'system'=>nl2br($inventionDatas[$i]->system),'products'=>nl2br($inventionDatas[$i]->products),'companies'=>nl2br($inventionDatas[$i]->companies),'p_infringe'=>nl2br($inventionDatas[$i]->p_infringe)),false);
								$data = $this->Due_dilligence_invention->getInsertID();
							} else {
								$this->Due_dilligence_invention->clear();
								$this->Due_dilligence_invention->save(array('patent_id'=>$inventionDatas[$i]->patent_id,'problem'=>nl2br($inventionDatas[$i]->problem),'solution'=>nl2br($inventionDatas[$i]->solution),'system'=>nl2br($inventionDatas[$i]->system),'products'=>nl2br($inventionDatas[$i]->products),'companies'=>nl2br($inventionDatas[$i]->companies),'p_infringe'=>nl2br($inventionDatas[$i]->p_infringe),'id'=>$dueDilligenceInventionData['Due_dilligence_invention']['id']),false);
								$data = $this->Due_dilligence_invention->id;
							}
						}
					}
				} catch(Exception $e){
					
				}
			}
		}
		echo $data;
		die;
	}
	
	
	public function findRequestToParticipant($licenseNumber=null){
		$allRequestData =array();	
		$allPotentialData =array();	
		if($licenseNumber!=null){			
			$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$licenseNumber)));
			if(count($getLicenseData)>0){
				$licenseID = $getLicenseData['License']['id'];
				$allRequestData = $this->Request_to_participates->find("all",array('conditions'=>array('Request_to_participates.license_id'=>$licenseID),'order'=>array('Request_to_participates.id'=>'DESC')));
				$allPotentialData = $this->Potential_participates->find("all",array('conditions'=>array('Potential_participates.license_id'=>$licenseID),'order'=>array('Potential_participates.id'=>'DESC')));
			}
		} else {			
			$allRequestData = $this->Request_to_participates->find("all",array('order'=>array('Request_to_participates.id'=>'DESC')));
			$allPotentialData = $this->Potential_participates->find("all",array('order'=>array('Potential_participates.id'=>'DESC')));
		}
		$getRevisedData = $this->findRevisedDocument($licenseNumber);
		echo json_encode(array('participant'=>$allRequestData,'potential'=>$allPotentialData,'revised'=>$getRevisedData));
		die;
	}
	
	public function findRevisedDocument($licenseNumber=null){
		$allRequestData =array();	
		if($licenseNumber!=null){			
			$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$licenseNumber)));
			if(count($getLicenseData)>0){
				$licenseID = $getLicenseData['License']['id'];
				$allRequestData = $this->Revised_documents->find("all",array('conditions'=>array('Revised_documents.license_id'=>$licenseID),'order'=>array('Revised_documents.id'=>'DESC')));
				
			}
		} else {			
			$allRequestData = $this->Revised_documents->find("all",array('order'=>array('Revised_documents.id'=>'DESC')));
			
		}
		return $allRequestData;
	}
	
	
	
	
	public function update_accordion_data(){
		if(count($this->data)>0){
			$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$this->data['license_number'])));
			$saveData = $this->data;
			unset($saveData['license_number']);
			$saveData['id'] = $getLicenseData['License']['id'];
			$updateData = $this->License->save($saveData);
			if($updateData){
				echo "1";
			} else {
				echo "0";
			}
		} else  {
			echo "0";
		}
		die;
	}
	
	
	public function potential_syndicate(){
		$data = 0;
		if(count($this->data)>0){
			$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$this->data['license_number'])));
			if(count($getLicenseData)>0){
				$this->Commitment->deleteAll(array('Commitment.license_id' => $getLicenseData['License']['id']));
				$commitedData = $this->Potential_participates->find("all",array('conditions'=>array('Potential_participates.id'=>json_decode($this->data['potential_syndicate']))));
				if(count($commitedData)>0){
					//$this->Commitment->deleteAll(array('Commitment.license_id'=>$licenseID));
					for($i=0;$i<count($commitedData);$i++){	
						$data = 1;
						$this->Commitment->create();
						$this->Potential_participates->save(array("display"=>1,'id'=>$commitedData[$i]['Potential_participates']['i']));
						$this->Commitment->save(array('license_id'=>$getLicenseData['License']['id'],'commitment_date'=>$commitedData[$i]['Potential_participates']['send_date'],'amount'=>$commitedData[$i]['Potential_participates']['price']),false);						
					}
				}
			}			
		}
		echo $data;
		die;
	}
	
	public function potential_particpant(){
		if(count($this->data)>0){
			$allRequestData = $this->Request_to_participates->find("first",array('conditions'=>array('Request_to_participates.id'=>$this->data['i'])));
			$newPotentialData = array('company_name'=>$allRequestData['Request_to_participates']['company_name'],'person_name'=>$allRequestData['Request_to_participates']['person_name'],'person_phone'=>$allRequestData['Request_to_participates']['person_phone'],'person_email'=>$allRequestData['Request_to_participates']['person_email'],'message'=>$allRequestData['Request_to_participates']['message'],'file'=>$this->data['file'],'create_date'=>$allRequestData['Request_to_participates']['create_date'],'send_date'=>date('Y-m-d H:i:s'),'type'=>$allRequestData['Request_to_participates']['type'],'license_id'=>$allRequestData['Request_to_participates']['license_id']);
			$this->Potential_participates->save($newPotentialData);
			$save = $this->Potential_participates->getLastInsertId();
			if($save>0){
				unlink($allRequestData['Request_to_participates']['file']);
				$this->Request_to_participates->deleteAll(array('Request_to_participates.id'=>$this->data['i']));
				echo $save;
			} else {
				echo "0";
			}
		} else  {
			echo "0";
		}
		die;
	}
	
	public function updatePricePotential(){
		$data = "0";
		if(count($this->data)>0){
			if(isset($this->data['p'])){
				$this->Potential_participates->save(array("price"=>$this->data['p'],'id'=>$this->data['i']));
			} else if($this->data['thread_id']){
				$this->Potential_participates->save(array("thread_id"=>$this->data['thread_id'],'id'=>$this->data['i']));
			}
			
			$data = $this->Potential_participates->getAffectedRows();
		}
		echo $data;
		die;
	}
	
	function delete_request_lead(){
		$data = "0";
		if(count($this->data)>0){
			$this->Request_to_participates->delete(array('id'=>$this->data['i']));
			$data = $this->Request_to_participates->getAffectedRows();
		}
		echo $data;
		die;
	}
	
	function removeRevisedDocument(){
		$data = "0";
		if(count($this->data)>0){
			$allRequestData = $this->Revised_documents->find("first",array('conditions'=>array('Revised_documents.id'=>$this->data['i'])));
			$this->Revised_documents->delete(array('id'=>$this->data['i']));
			$data = $this->Revised_documents->getAffectedRows();
			if($data>0){
				unlink($allRequestData['Revised_documents']['license_aggreement']);
				unlink($allRequestData['Revised_documents']['strategic_aggreement']);
				unlink($allRequestData['Revised_documents']['request_aggreement']);
				unlink($allRequestData['Revised_documents']['program_aggreement']);
			}
		}
		echo $data;
		die;
	}
	
	public function findRequestData(){
		$allRequestData = array();
		if(count($this->data)>0){
			$allRequestData = $this->Request_to_participates->find("first",array('conditions'=>array('Request_to_participates.id'=>$this->data['i'])));
		}
		echo json_encode($allRequestData);
		die;
	}
	
	public function fineRevisedDocData(){
		$allRequestData = array();
		if(count($this->data)>0){
			$allRequestData = $this->Revised_documents->find("first",array('conditions'=>array('Revised_documents.id'=>$this->data['i'])));
		}
		echo json_encode($allRequestData);
		die;
	}
	
	public function uploadFileToServer(){
		if(count($this->data)>0){
			$destination = $this->data['destination'];
			$source = $this->data['source'];
			if(file_exists($source)){
				/*$server = "184.154.232.5"; */
				$server = "181.224.147.196"; 
				$ftp_user_name = "synpatap"; 
				$ftp_user_pass = "1q2w3e4r5t"; 
				$connection = ftp_connect($server);
				$fp = fopen($source, 'r');
				$login = ftp_login($connection, $ftp_user_name, $ftp_user_pass);
				if (!$connection || !$login) { die('Connection attempt failed!'); }
				ftp_chdir($connection, '/public_html/public/upload/'); 
				
				$upload = ftp_put($connection, $destination, $source, FTP_BINARY);
				if ($upload) {echo "Done";}
				ftp_close($connection);
			}
		}
		die;
	}
	
	public function demo_portfolio(){
		if(count($this->data)>0){
			if (!file_exists(ROOT.DS.'../store/'.$this->data['license_number'])) {
				mkdir(ROOT.DS.'../store/'.$this->data['license_number'], 0777); 
				mkdir(ROOT.DS.'../dd/'.$this->data['serial_number'], 0777); 
				$licenseName = str_replace("_"," ",$this->data['license_number']);
				$this->recurse_copy(ROOT.DS."../store/12345",ROOT.DS.'../store/'.$this->data['license_number']);
				$this->recurse_copy(ROOT.DS."../dd/12345",ROOT.DS.'../dd/'.$this->data['serial_number']);
				$fp = fopen(ROOT.DS.'../store/'.$this->data['license_number'].'/license.php', 'w'); 
				fwrite($fp, "<?php define('LICENSE_NUMBER','".$this->data['license_number']."');?>");
				$fp = fopen(ROOT.DS.'../dd/'.$this->data['serial_number'].'/license.php', 'w'); 
				fwrite($fp, "<?php define('LICENSE_NUMBER','".$this->data['license_number']."');define('SERIAL_NUMBER','".$this->data['serial_number']."');?>");
				$licenseData['License']['license_status']=$this->data['license_status'];
				$licenseData['License']['license_number']=$this->data['license_number'];
				$licenseData['License']['name']= $licenseName;
				$licenseData['License']['access_code']=$this->data['access_code'];
				$licenseData['License']['due_access_code']=12345;
				$licenseData['License']['tabs_access_code']=12345;
				$licenseData['License']['asking_price']=$this->data['asking_price'];
				$licenseData['License']['serial_number']=$this->data['serial_number'];
				$newDataForAccordionText = $this->License->find('first',array('conditions'=>array('License.license_number'=>'12345')));
				$licenseData['License']['damage_tab']=$newDataForAccordionText['License']['damage_tab'];
				$licenseData['License']['prior_art_tab']=$newDataForAccordionText['License']['prior_art_tab'];
				$licenseData['License']['claim_chart_tab']=$newDataForAccordionText['License']['claim_chart_tab'];
				$licenseData['License']['due_diligence_tab']=$newDataForAccordionText['License']['due_diligence_tab'];
				$licenseData['License']['impact_tab']=$newDataForAccordionText['License']['impact_tab'];
				$licenseData['License']['document_text']=$newDataForAccordionText['License']['document_text'];
				$licenseData['License']['purchase_agreement']=$newDataForAccordionText['License']['purchase_agreement'];
				$licenseData['License']['license_tab']=$newDataForAccordionText['License']['license_tab'];
				$licenseData['License']['buy_a_license_tab']=$newDataForAccordionText['License']['buy_a_license_tab'];
				$licenseData['License']['program_tab']=$newDataForAccordionText['License']['program_tab'];
				$licenseData['License']['simulator_text']=$newDataForAccordionText['License']['simulator_text'];
				$licenseData['License']['syndication_tab']=$newDataForAccordionText['License']['syndication_tab'];
				$licenseData['License']['portfolio_text']=$newDataForAccordionText['License']['portfolio_text'];
				$licenseData['License']['deactive_tooltip']=$newDataForAccordionText['License']['deactive_tooltip'];
				$licenseData['License']['deactive_tooltip_regular']=$newDataForAccordionText['License']['deactive_tooltip_regular'];
				$licenseData['License']['deactive_tooltip_risk']=$newDataForAccordionText['License']['deactive_tooltip_risk'];
				$licenseData['License']['popup_text_participant']=$newDataForAccordionText['License']['popup_text_participant'];
				$licenseData['License']['popup_text_regular']=$newDataForAccordionText['License']['popup_text_regular'];
				$licenseData['License']['popup_text_risk']=$newDataForAccordionText['License']['popup_text_risk'];
				$licenseData['License']['introduction']=$newDataForAccordionText['License']['introduction'];
				$this->License->create();
				$this->License->save($licenseData['License'],false);
				$licenseID = $this->License->getLastInsertId();
				if($licenseID>0){
					$this->Store_user->create();
					$this->Store_user->save(array('project_id'=>$licenseID,'type'=>'user','access_code'=>$licenseData['License']['access_code']));
					echo json_encode(array('id'=>$licenseID,'name'=>$this->data['license_number']));
				} else {
					echo json_encode(array('id'=>0,'name'=>$this->data['license_number']));
				}
			} else {
				$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$this->data['license_number'])));
				echo json_encode(array('id'=>$getLicenseData['License']['id'],'name'=>$this->data['license_number']));
			}
		}
		die;
	} 
	
	
	function saveAccordionTemplateText(){
		if(count($this->data)>0){
			$newDataForAccordionText = $this->License->find('first',array('conditions'=>array('License.license_number'=>'12345')));
			$licenseData['License']['impact_tab']= $this->data['impact_tab'];
			$licenseData['License']['claim_chart_tab']=$this->data['claim_chart_tab'];
			$licenseData['License']['due_diligence_tab']=$this->data['due_diligence_tab'];
			$licenseData['License']['document_text']=$this->data['document_text'];
			$licenseData['License']['simulator_text']=$this->data['simulator_text'];
			$licenseData['License']['syndication_tab']=$this->data['syndication_tab'];
			$licenseData['License']['portfolio_text']=$this->data['portfolio_text'];
			$licenseData['License']['id']=$newDataForAccordionText['License']['id'];
			if($this->License->save($licenseData['License'],false)){
				echo "1";
			} else {
				echo "0";
			}
		} else {
			echo "0";
		}
		die;
	}
	
	public function add_due_diligence_patent(){
		if(count($this->data)>0){
			if(!empty($this->data['license_number'])){
				$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$this->data['license_number'])));
				if(count($getLicenseData)>0){
				$licenseID = $getLicenseData['License']['id'];
				$dueDilligenceData = json_decode($this->data['patent_data']);
				if(isset($this->data['asking_price'])){
					$updateData = $this->License->save(array('asking_price'=>$this->data['asking_price'],'id'=>$licenseID),false);
				}
				
					if(count($dueDilligenceData)>0){
						$send = 0;
						for($i=0;$i<count($dueDilligenceData);$i++){
							if($dueDilligenceData[$i]->name!=""){
								$this->Due_dilligence->create();
								$saveData = $this->Due_dilligence->save(array('license_id'=>$licenseID,'patent_number'=>$dueDilligenceData[$i]->name,'pdf'=>'','illustration'=>'','family'=>'','citation'=>''),false);
								$getData = $this->Due_dilligence_title->deleteAll(array('Due_dilligence_title.patent_id'=>$dueDilligenceData[$i]->name));
								$getData = $this->Due_dilligence_title->deleteAll(array('Due_dilligence_title.patent_id'=>substr($dueDilligenceData[$i]->name,2)));
								$patentData = "";
								if(strpos($dueDilligenceData[$i]->name,'US')===false){	
									$patentData =$dueDilligenceData[$i]->name;
								} else {
									$patentData =substr($dueDilligenceData[$i]->name,2);
								}
								if($patentData!=""){
									$this->getAssigmentNumber($patentData);
								}
								$getCititation = $this->Due_dilligence_citation->deleteAll(array('Due_dilligence_citation.patent_id'=>$dueDilligenceData[$i]->name));
								$this->runCitationCrawler($dueDilligenceData[$i]->name);								
								$getEvent = $this->Due_dilligence_event->deleteAll(array('Due_dilligence_event.patent_id'=>$dueDilligenceData[$i]->name));
								$this->runEventCrawler($dueDilligenceData[$i]->name);	
								$getPublished = $this->Due_dilligence_counterpart->deleteAll(array('Due_dilligence_counterpart.patent_id'=>$dueDilligenceData[$i]->name));
								$this->runPublishedAs($dueDilligenceData[$i]->name);		
								if($saveData){
									$send = 1;
								}
							}
						}
						/*for($i=0;$i<count($dueDilligenceData);$i++){
							if($dueDilligenceData[$i][0]!=""){
								$this->Due_dilligence->create();
								$saveData = $this->Due_dilligence->save(array('license_id'=>$licenseID,'patent_number'=>$dueDilligenceData[$i][0],'pdf'=>'','illustration'=>'','family'=>'','citation'=>''),false);
								if(strpos($dueDilligenceData[$i][0],'US')===false){										
									$getData = $this->Due_dilligence_title->find('all',array('conditions'=>array('Due_dilligence_title.patent_id'=>$dueDilligenceData[$i][0])));
								} else {
									$getData = $this->Due_dilligence_title->find('all',array('conditions'=>array('Due_dilligence_title.patent_id'=>substr($dueDilligenceData[$i][0]),2)));
								}	
								$patentData = "";
								if(strpos($dueDilligenceData[$i][0],'US')===false){	
									$patentData =$dueDilligenceData[$i][0];
								} else {
									$patentData =substr($dueDilligenceData[$i][0],2);
								}
								if(count($getData)==0){
									$this->getAssigmentNumber($patentData);
								}
								$getCititation = $this->Due_dilligence_citation->find('all',array('conditions'=>array('Due_dilligence_citation.patent_id'=>$dueDilligenceData[$i][0])));
								
								if(count($getCititation)==0){
									try{
										
										$this->runCitationCrawler($dueDilligenceData[$i][0]);
									} catch(Exception $e){
									}
								}
								
								$getEvent = $this->Due_dilligence_event->find('all',array('conditions'=>array('Due_dilligence_event.patent_id'=>$dueDilligenceData[$i][0])));
								
								
								if(count($getEvent)==0){
									try{
										$this->runEventCrawler($dueDilligenceData[$i][0]);
									} catch(Exception $e){
									}
								}
	
								if($saveData){
									$send = 1;
								}
							}
						}*/
						if($send>0){
							echo json_encode(array('send'=>1));
						} else {
							echo json_encode(array('send'=>0));
						}
					} else {
						echo json_encode(array('send'=>0));
					}
				} else {
					echo json_encode(array('send'=>0));
				}
			} else {
				echo json_encode(array('send'=>0));
			}
		} else {
			echo json_encode(array('send'=>0));
		}
		die;
	}
	
	public function updatePatentList(){
		$data = array('send'=>0);
		if(count($this->data)>0){
			if(!empty($this->data['license_number'])){
				$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$this->data['license_number'])));
				if(count($getLicenseData)>0){
					$licenseID = $getLicenseData['License']['id'];
					$dueDilligenceData = json_decode($this->data['patent_data']);
					if(isset($this->data['asking_price'])){
						$updateData = $this->License->save(array('asking_price'=>$this->data['asking_price'],'id'=>$licenseID),false);
					}								
					if(count($dueDilligenceData)>0){
						$this->Due_dilligence->deleteAll(array('Due_dilligence.license_id'=>$licenseID));
						for($i=0;$i<count($dueDilligenceData);$i++){
						if($dueDilligenceData[$i]->name!=""){								
								$this->Due_dilligence->create();
								$saveData = $this->Due_dilligence->save(array('license_id'=>$licenseID,'patent_number'=>$dueDilligenceData[$i]->name,'pdf'=>'','illustration'=>'','family'=>'','citation'=>''),false);								
								$getData = $this->Due_dilligence_title->deleteAll(array('Due_dilligence_title.patent_id'=>$dueDilligenceData[$i]->name));
								$getData = $this->Due_dilligence_title->deleteAll(array('Due_dilligence_title.patent_id'=>substr($dueDilligenceData[$i]->name,2)));
								$patentData = "";
								if(strpos($dueDilligenceData[$i]->name,'US')===false){	
									$patentData =$dueDilligenceData[$i]->name;
								} else {
									$patentData =substr($dueDilligenceData[$i]->name,2);
								}
								if($patentData!=""){
									$this->getAssigmentNumber($patentData);
								}								
								$getCititation = $this->Due_dilligence_citation->deleteAll(array('Due_dilligence_citation.patent_id'=>$dueDilligenceData[$i]->name));
								$this->runCitationCrawler($dueDilligenceData[$i]->name);								
								$getEvent = $this->Due_dilligence_event->deleteAll(array('Due_dilligence_event.patent_id'=>$dueDilligenceData[$i]->name));
								$this->runEventCrawler($dueDilligenceData[$i]->name);
								$getPublished = $this->Due_dilligence_counterpart->deleteAll(array('Due_dilligence_counterpart.patent_id'=>$dueDilligenceData[$i]->name));
								$this->runPublishedAs($dueDilligenceData[$i]->name);								
								if($saveData){
									$data['send'] = 1;
								}
							}
						}
					}
				}
			}
		}
		echo json_encode($data);
		die;
	}
	
	public function delete_single_invitee(){
		$send = 0;
		if(count($this->data)>0){
			if(!empty($this->data['license_number'])){
				$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$this->data['license_number'])));
				if(count($getLicenseData)>0){
					$licenseID = $getLicenseData['License']['id'];
					$this->Invitee->deleteAll(array('Invitee.license_id' => $licenseID,'company'=>$this->data['company_name']));
					$send = 1;
				}
			}
		}
		echo json_encode(array('send'=>$send));
		die;		
	}
	
	public function add_single_invitee(){
		$send = 0;
		if(count($this->data)>0){
			if(!empty($this->data['license_number'])){
				$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$this->data['license_number'])));
				if(count($getLicenseData)>0){
					$licenseID = $getLicenseData['License']['id'];
					$saveData = $this->Invitee->save(array('license_id'=>$licenseID,'company'=>$this->data['company_name']),false);
					if($saveData){
						$send = 1;
					}
				}
			}
		}
		echo json_encode(array('send'=>$send));
		die;
	}
	
	public function add_invitees(){
		if(count($this->data)>0){
			if(!empty($this->data['license_number'])){
				$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$this->data['license_number'])));
				if(count($getLicenseData)>0){
					$licenseID = $getLicenseData['License']['id'];
					$invitiesData = json_decode($this->data['invitees']);
					if(count($invitiesData)>0){
						$this->Invitee->deleteAll(array('Invitee.license_id' => $licenseID));
						$send = 0;
						for($i=0;$i<count($invitiesData);$i++){
							$this->Invitee->create();
							$name = (!empty($invitiesData[$i]->orgName))?$invitiesData[$i]->orgName:$invitiesData[$i]->name;
							$saveData = $this->Invitee->save(array('license_id'=>$licenseID,'company'=>$name),false);
							if($saveData){
								$send = 1;
							}
						} 
						if($send>0){
							echo json_encode(array('send'=>1));
						} else {
							echo json_encode(array('send'=>0));
						}
					}else {
						echo json_encode(array('send'=>0));
					}
				}else {
					echo json_encode(array('send'=>0));
				}
			} else {
				echo json_encode(array('send'=>0));
			}
		} else {
			echo json_encode(array('send'=>0));
		}
		die;
	}
	
				
	
	
	public function license_update(){
		if(count($this->data)>0){
			if(!empty($this->data['license_number'])){
				$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$this->data['license_number'])));
				if(count($getLicenseData)>0){
					$licenseID = $getLicenseData['License']['id'];
					$updateData = $this->License->save(array('damage'=>$this->data['damage'],'id'=>$licenseID),false);
					if($updateData){
						echo json_encode(array('send'=>1));
					} else {
						echo json_encode(array('send'=>0));
					}
				} else {
					echo json_encode(array('send'=>0));
				}
			} else {
				echo json_encode(array('send'=>0));
			}
		} else {
			echo json_encode(array('send'=>0));
		}
		die;
	}
	
	
	public function license_update_left_chart(){
		if(count($this->data)>0){
			$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$this->data['license_number'])));
			if(count($getLicenseData)>0){
				$licenseID = $getLicenseData['License']['id'];
				$chartLeftData = json_decode($this->data['chart_left']);
				if(count($chartLeftData)>0){
					$this->Chart_left->deleteAll(array('Chart_left.license_id' => $licenseID));
					for($i=0;$i<count($chartLeftData);$i++){
						if(isset($chartLeftData[$i]->{'0'}) && !empty($chartLeftData[$i]->{'0'})){						
							$this->Chart_left->create();
							$this->Chart_left->save(array('license_id'=>$licenseID,'country'=>$chartLeftData[$i]->{'0'},'applications'=>$chartLeftData[$i]->{'2'},'patents'=>$chartLeftData[$i]->{'1'}),false);
						} else if(!is_object($chartLeftData[$i]) && isset($chartLeftData[$i]['0']) && !empty($chartLeftData[$i]['0'])){
							$this->Chart_left->create();
							$this->Chart_left->save(array('license_id'=>$licenseID,'country'=>$chartLeftData[$i]['0'],'applications'=>$chartLeftData[$i]['2'],'patents'=>$chartLeftData[$i]['1']),false);
						}	
						echo json_encode(array('send'=>1));
					}
				} else {
					echo json_encode(array('send'=>0));
				}
			} else {
				echo json_encode(array('send'=>0));
			}
		} else {
			echo json_encode(array('send'=>0));
		}
		die;
	}
	
	
	public function license_update_other_docs(){
		if(count($this->data)>0){
			if(!empty($this->data['license_number'])){
				$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$this->data['license_number'])));
				if(count($getLicenseData)>0){
					$licenseID = $getLicenseData['License']['id'];
					$this->Potential_syndicate->deleteAll(array('Potential_syndicate.license_id' => $licenseID));
					/*Potential Syndicate*/
					$potentialSyndicateData = json_decode($this->data['potential_data']);
					if(count($potentialSyndicateData)>0){
						for($i=0;$i<count($potentialSyndicateData);$i++){
							if(!empty($potentialSyndicateData[$i]->{'0'})){
								$this->Potential_syndicate->create();
								$this->Potential_syndicate->save(array('license_id'=>$licenseID,'participate'=>$potentialSyndicateData[$i]->{'0'},'fees'=>$potentialSyndicateData[$i]->{'1'}),false);
							} else if(!is_object($potentialSyndicateData[$i]) && isset($potentialSyndicateData[$i]['0']) && !empty($potentialSyndicateData[$i]['0'])){								
								$this->Potential_syndicate->create();
								$this->Potential_syndicate->save(array('license_id'=>$licenseID,'participate'=>$potentialSyndicateData[$i]['0'],'fees'=>$potentialSyndicateData[$i]['1']),false);
							}							
						}
					}
					
					/*Commitment Data*/
					/*$commitedData = json_decode($this->data['commitment_data']);
					if(count($commitedData)>0){
						for($i=0;$i<count($commitedData);$i++){
							if(!empty($commitedData[$i]->{'0'})){
								$this->Commitment->create();
								$this->Commitment->save(array('license_id'=>$licenseID,'commitment_date'=>$commitedData[$i]->{'0'},'amount'=>$commitedData[$i]->{'1'},'ar'=>''),false);
							}							
						}
					}*/
					$this->Chart_left->deleteAll(array('Chart_left.license_id' => $licenseID));
					$this->Chart_middle->deleteAll(array('Chart_middle.license_id' => $licenseID));
					$this->Chart_right->deleteAll(array('Chart_right.license_id' => $licenseID));
					$this->Comparable->deleteAll(array('Comparable.license_id' => $licenseID));
					$this->Damage->deleteAll(array('Damage.license_id' => $licenseID));
					/*chart Left*/
					$chartLeftData = json_decode($this->data['chart_left']);
					if(count($chartLeftData)>0){
						for($i=0;$i<count($chartLeftData);$i++){

							if(isset($chartLeftData[$i]->{'0'}) && !empty($chartLeftData[$i]->{'0'})){
							
								$this->Chart_left->create();
								$this->Chart_left->save(array('license_id'=>$licenseID,'country'=>$chartLeftData[$i]->{'0'},'applications'=>$chartLeftData[$i]->{'1'},'patents'=>$chartLeftData[$i]->{'2'}),false);
							} else if(!is_object($chartLeftData[$i]) && isset($chartLeftData[$i]['0']) && !empty($chartLeftData[$i]['0'])){
								
								$this->Chart_left->create();
								$this->Chart_left->save(array('license_id'=>$licenseID,'country'=>$chartLeftData[$i]['0'],'applications'=>$chartLeftData[$i]['1'],'patents'=>$chartLeftData[$i]['2']),false);
							}						
						}
					}
					
					/*Chart Middle*/					
					$chartMiddleData = json_decode($this->data['chart_middle']);
					if(count($chartMiddleData)>0){

						for($i=0;$i<count($chartMiddleData);$i++){
							if(isset($chartMiddleData[$i]->{'0'}) && !empty($chartMiddleData[$i]->{'0'})){
								$this->Chart_middle->create();
								$this->Chart_middle->save(array('license_id'=>$licenseID,'technologies'=>$chartMiddleData[$i]->{'0'},'data'=>$chartMiddleData[$i]->{'1'}),false);
							} else if(!is_object($chartMiddleData[$i]) && isset($chartMiddleData[$i]['0']) && !empty($chartMiddleData[$i]['0'])){
								$this->Chart_middle->create();
								$this->Chart_middle->save(array('license_id'=>$licenseID,'technologies'=>$chartMiddleData[$i]['0'],'data'=>$chartMiddleData[$i]['1']),false);
							}							
						}
					}
					
					$chartRightData = json_decode($this->data['chart_right']);
					if(count($chartRightData)>0){
						for($i=0;$i<count($chartRightData);$i++){
							if(isset($chartRightData[$i]->{'0'}) && !empty($chartRightData[$i]->{'0'})){
								$this->Chart_right->create();
								$this->Chart_right->save(array('license_id'=>$licenseID,'year'=>$chartRightData[$i]->{'0'},'data'=>$chartRightData[$i]->{'1'}),false);
							} else if(!is_object($chartRightData[$i]) && isset($chartRightData[$i]['0']) && !empty($chartRightData[$i]['0'])){
								$this->Chart_right->create();
								$this->Chart_right->save(array('license_id'=>$licenseID,'year'=>$chartRightData[$i]['0'],'data'=>$chartRightData[$i]['1']),false);
							}						
						}
					}
					/*Chart Right*/
					
					$chartComparableData = json_decode($this->data['comparable']);
					if(count($chartComparableData)>0){
						for($i=0;$i<count($chartComparableData);$i++){
							if(isset($chartComparableData[$i]->{'0'}) && !empty($chartComparableData[$i]->{'0'})){
								$this->Comparable->create();
								$this->Comparable->save(array('license_id'=>$licenseID,'file_name'=>$chartComparableData[$i]->{'0'},'file_link'=>$chartComparableData[$i]->{'1'}),false);
							} else if(!is_object($chartComparableData[$i]) && isset($chartComparableData[$i]['0']) && !empty($chartComparableData[$i]['0'])){
								$this->Comparable->create();
								$this->Comparable->save(array('license_id'=>$licenseID,'file_name'=>$chartComparableData[$i]['0'],'file_link'=>$chartComparableData[$i]['1']),false);
							}						
						}
					}
					
					$chartDamageData = json_decode($this->data['damages']);
					if(count($chartDamageData)>0){
						for($i=0;$i<count($chartDamageData);$i++){
							if(isset($chartDamageData[$i]->{'0'}) && !empty($chartDamageData[$i]->{'0'})){
								$this->Damage->create();
								$this->Damage->save(array('license_id'=>$licenseID,'file_name'=>$chartDamageData[$i]->{'0'},'file_link'=>$chartDamageData[$i]->{'1'}),false);
							} else if(!is_object($chartDamageData[$i]) && isset($chartDamageData[$i]['0']) && !empty($chartDamageData[$i]['0'])){
								$this->Damage->create();
								$this->Damage->save(array('license_id'=>$licenseID,'file_name'=>$chartDamageData[$i]['0'],'file_link'=>$chartDamageData[$i]['1']),false);
							}						
						}
					}
					
					
					$updateData = $this->License->save(array('purchase_file'=>$this->data['ppa'],'purchase_license'=>$this->data['pla'],'purchase_buy'=>$this->data['rtp'],'program_policies_file'=>$this->data['ppp'],'strategic_license_agreement'=>$this->data['sla'],'syndication_file'=>$this->data['syndication_file'],'damage'=>$this->data['damage'],'id'=>$licenseID),false);
					if($updateData){
						echo json_encode(array('send'=>1));
					} else {
						echo json_encode(array('send'=>0));
					}
				} else {
					echo json_encode(array('send'=>0));
				}
			} else {
				echo json_encode(array('send'=>0));
			}
		} else {
			echo json_encode(array('send'=>0));
		}
		die;
	}
	
	public function license_update_embed_code(){
		if(count($this->data)>0){
			if(!empty($this->data['license_number'])){
				$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$this->data['license_number'])));
				if(count($getLicenseData)>0){					
					$licenseID = $getLicenseData['License']['id'];
					$updateData = $this->License->save(array('active_button'=>$this->data['active_button'],'cliamchart'=>$this->data['claimchart'],'prior_art'=>$this->data['prior_art'],'id'=>$licenseID),false);
					if($updateData){
						echo json_encode(array('send'=>1));
					} else {
						echo json_encode(array('send'=>0));
					}
				} else {
					echo json_encode(array('send'=>0));
				}
			} else {
				echo json_encode(array('send'=>0));
			}
		} else {
			echo json_encode(array('send'=>0));
		}
		die;
	}
	
	public function license_other_update_data(){
		if(count($this->data)>0){
			if(!empty($this->data['license_number'])){
				$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$this->data['license_number'])));
				if(count($getLicenseData)>0){
					$licenseID = $getLicenseData['License']['id'];
					if(isset($this->data['option_expiration_date'])){
						$updateData = $this->License->save(array('expiration_date'=>$this->data['option_expiration_date'],'asking_price'=>$this->data['asking_price'],'id'=>$licenseID),false);
					} else {
						$updateData = $this->License->save(array('asking_price'=>$this->data['asking_price'],'id'=>$licenseID),false);
					}
					
					if($updateData){
						echo json_encode(array('send'=>1));
					} else {
						echo json_encode(array('send'=>0));
					}
				} else {
					echo json_encode(array('send'=>0));
				}
			} else {
				echo json_encode(array('send'=>0));
			}
		} else {
			echo json_encode(array('send'=>0));
		}
		die;
	}
	
	public function update_docket_data(){
		if(count($this->data)>0){
			if(!empty($this->data['license_number'])){
				$getLicenseData = $this->License->find('first',array('conditions'=>array('license_number'=>$this->data['license_number'])));
				if(count($getLicenseData)>0){
					$licenseID = $getLicenseData['License']['id'];
					$updateData = $this->License->save(array('expiration_date'=>$this->data['expiration_date'],'file_left'=>$this->data['image_left'],'file_middle'=>$this->data['image_middle'],'file_right'=>$this->data['image_right'],
					'file_four'=>$this->data['image_four'],
					'file_five'=>$this->data['image_five'],
					'file_six'=>$this->data['image_six'],
					'file_seven'=>$this->data['image_seven'],
					'file_eight'=>$this->data['image_eight'],
					'file_nine'=>$this->data['image_nine'],
					'file_ten'=>$this->data['image_ten'],
					'file_eleven'=>$this->data['image_eleven'],
					'file_twelve'=>$this->data['image_twelve'],'asking_price'=>$this->data['asking_price'],'person_name'=>$this->data['person_name'],'person_email'=>$this->data['person_email'],'person_phone'=>$this->data['person_phone'],'person_picture'=>$this->data['person_picture'],'id'=>$licenseID),false);
					if($updateData){
						echo json_encode(array('send'=>1));
					} else {
						echo json_encode(array('send'=>0));
					}
				} else {
					echo json_encode(array('send'=>0));
				}
			} else {
				echo json_encode(array('send'=>0));
			}
		} else {
			echo json_encode(array('send'=>0));
		}
		die;
	}
	
	public function real_license(){
		if(count($this->data)>0){
			mkdir(ROOT.DS.'../synpat_com/store/'.$this->data['project']['syndication_number'], 0777); 
			$this->recurse_copy(ROOT.DS."../synpat_com/store/12345",ROOT.DS.'../synpat_com/store/'.$this->data['project']['syndication_number']);
			$fp = fopen(ROOT.DS.'../synpat_com/store/'.$this->data['project']['syndication_number'].'/license.php', 'w'); 
			fwrite($fp, "<?php define('LICENSE_NUMBER','".$this->data['project']['syndication_number']."');?>");
			$title_for_layout = 'New Application Created';
			$syndicationNumber = $this->data['project']['syndication_number'];
			//$this->set(compact('title_for_layout','syndicationNumber'));
			/*Get Demo Data and Create new ROW in DB*/
			$licenseData = $this->License->find('first',array('conditions'=>array('License.id'=>$this->data['project']['demo_id'])));
			unset($licenseData['License']['id']);
			$licenseData['License']['license_status']=1;
			$licenseData['License']['license_number']=$this->data['project']['syndication_number'];
			$this->License->create();
			$this->License->save($licenseData['License'],false);
			$licenseID = $this->License->getLastInsertId();
			if($licenseID>0){
				$this->Store_user->create();
				$this->Store_user->save(array('project_id'=>$licenseID,'type'=>'user','access_code'=>$licenseData['License']['access_code']));
				$invitiesData = $this->Invitee->find('all',array('conditions'=>array('license_id'=>$this->data['project']['demo_id'])));
				if(count($invitiesData)>0){
					for($i=0;$i<count($invitiesData);$i++){
						$this->Invitee->create();
						$this->Invitee->save(array('license_id'=>$licenseID,'company'=>$invitiesData[$i]['Invitee']['company'],'aar'=>$invitiesData[$i]['Invitee']['aar']),false);
					}
				}
				
				$commitedData = $this->Commitment->find('all',array('conditions'=>array('license_id'=>$this->data['project']['demo_id'])));
				if(count($commitedData)>0){
					for($i=0;$i<count($commitedData);$i++){
						$this->Commitment->create();
						$this->Commitment->save(array('license_id'=>$licenseID,'commitment_date'=>$commitedData[$i]['Commitment']['commitment_date'],'amount'=>$commitedData[$i]['Commitment']['amount'],'ar'=>$commitedData[$i]['Commitment']['ar']),false);
					}
				}
				$chartLeftData = $this->Chart_left->find('all',array('conditions'=>array('license_id'=>$this->data['project']['demo_id'])));
				if(count($chartLeftData)>0){
					for($i=0;$i<count($chartLeftData);$i++){
						$this->Chart_left->create();
						$this->Chart_left->save(array('license_id'=>$licenseID,'country'=>$chartLeftData[$i]['Chart_left']['country'],'applications'=>$chartLeftData[$i]['Chart_left']['applications'],'patents'=>$chartLeftData[$i]['Chart_left']['patents']),false);
					}
				}			
				$chartMiddleData = $this->Chart_middle->find('all',array('conditions'=>array('license_id'=>$this->data['project']['demo_id'])));
				if(count($chartMiddleData)>0){
					for($i=0;$i<count($chartMiddleData);$i++){
						$this->Chart_middle->create();
						$this->Chart_middle->save(array('license_id'=>$licenseID,'technologies'=>$chartMiddleData[$i]['Chart_middle']['technologies'],'data'=>$chartMiddleData[$i]['Chart_middle']['data']),false);
					}
				}	
				$chartRightData = $this->Chart_right->find('all',array('conditions'=>array('license_id'=>$this->data['project']['demo_id'])));
				if(count($chartRightData)>0){
					for($i=0;$i<count($chartRightData);$i++){
						$this->Chart_right->create();
						$this->Chart_right->save(array('license_id'=>$licenseID,'year'=>$chartRightData[$i]['Chart_right']['Year'],'data'=>$chartRightData[$i]['Chart_right']['data']),false);
					}
				}
				$potentialSyndicateData = $this->Potential_syndicate->find('all',array('conditions'=>array('license_id'=>$this->data['project']['demo_id'])));
				if(count($potentialSyndicateData)>0){
					for($i=0;$i<count($potentialSyndicateData);$i++){
						$this->Potential_syndicate->create();
						$this->Potential_syndicate->save(array('license_id'=>$licenseID,'participate'=>$potentialSyndicateData[$i]['Potential_syndicate']['participate'],'fees'=>$potentialSyndicateData[$i]['Potential_syndicate']['fees']),false);
					}
				}
				
				$dueDilligenceLicenseData = $this->Due_dilligence_license->find('all',array('conditions'=>array('license_id'=>$this->data['project']['demo_id'])));
				if(count($dueDilligenceLicenseData)>0){
					for($i=0;$i<count($dueDilligenceLicenseData);$i++){
						$this->Due_dilligence_license->create();
						$this->Due_dilligence_license->save(array('license_id'=>$licenseID,'licensee_file'=>$dueDilligenceLicenseData[$i]['Due_dilligence_license']['licensee_file'],'licensee'=>$dueDilligenceLicenseData[$i]['Due_dilligence_license']['licensee'],'expiration'=>$dueDilligenceLicenseData[$i]['Due_dilligence_license']['expiration'],'expiration_valid'=>$dueDilligenceLicenseData[$i]['Due_dilligence_license']['expiration_valid'],'license_products'=>$dueDilligenceLicenseData[$i]['Due_dilligence_license']['license_products'],'license_products_text'=>$dueDilligenceLicenseData[$i]['Due_dilligence_license']['license_products_text'],'territory'=>$dueDilligenceLicenseData[$i]['Due_dilligence_license']['territory'],'territory_text'=>$dueDilligenceLicenseData[$i]['Due_dilligence_license']['territory_text'],'license_fee'=>$dueDilligenceLicenseData[$i]['Due_dilligence_license']['license_fee'],'transferable'=>$dueDilligenceLicenseData[$i]['Due_dilligence_license']['transferable'],'subject_condition_assignable'=>$dueDilligenceLicenseData[$i]['Due_dilligence_license']['subject_condition_assignable'],'sublicense'=>$dueDilligenceLicenseData[$i]['Due_dilligence_license']['sublicense'],'subject_condition_sublicense'=>$dueDilligenceLicenseData[$i]['Due_dilligence_license']['subject_condition_sublicense'],'exclusive'=>$dueDilligenceLicenseData[$i]['Due_dilligence_license']['exclusive'],'subject_condition_exclusive'=>$dueDilligenceLicenseData[$i]['Due_dilligence_license']['subject_condition_exclusive'],'right_to_sue'=>$dueDilligenceLicenseData[$i]['Due_dilligence_license']['right_to_sue'],'subject_condition_right_to_sue'=>$dueDilligenceLicenseData[$i]['Due_dilligence_license']['subject_condition_right_to_sue'],'patent_data'=>$dueDilligenceLicenseData[$i]['Due_dilligence_license']['patent_data'],'target'=>$dueDilligenceLicenseData[$i]['Due_dilligence_license']['target']),false);
					}
				}
				$mapPatentLicenseData = $this->Map_patent_license->find('all',array('conditions'=>array('license_id'=>$this->data['project']['demo_id'])));
				if(count($mapPatentLicenseData)>0){
					for($i=0;$i<count($mapPatentLicenseData);$i++){
						$this->Map_patent_license->create();
						$this->Map_patent_license->save(array('license_id'=>$licenseID,'patent_id'=>$mapPatentLicenseData[$i]['Map_patent_license']['patent_id']),false);
					}
				}				
				$dueDilligenceData = $this->Due_dilligence->find('all',array('conditions'=>array('license_id'=>$this->data['project']['demo_id'])));
				if(count($dueDilligenceData)>0){
					for($i=0;$i<count($dueDilligenceData);$i++){
						$this->Due_dilligence->create();
						$this->Due_dilligence->save(array('license_id'=>$licenseID,'patent_number'=>$dueDilligenceData[$i]['Due_dilligence']['patent_number'],'pdf'=>$dueDilligenceData[$i]['Due_dilligence']['pdf'],'illustration'=>$dueDilligenceData[$i]['Due_dilligence']['illustration'],'family'=>$dueDilligenceData[$i]['Due_dilligence']['family'],'citation'=>$dueDilligenceData[$i]['Due_dilligence']['citation']),false);
						$dueDilligenceCollteralData = $this->Due_dilligence_collateral->find('all',array('conditions'=>array('patent_id'=>$dueDilligenceData[$i]['Due_dilligence']['patent_number'])));
						if(count($dueDilligenceCollteralData)>0){
							for($j=0;$j<count($dueDilligenceCollteralData);$j++){
								$this->Due_dilligence_collateral->create();
								$this->Due_dilligence_collateral->save(array('patent_id'=>$dueDilligenceData[$i]['Due_dilligence']['patent_number'],'creditor_name'=>$dueDilligenceCollteralData[$j]['Due_dilligence_collateral']['creditor_name'],'date_for_aggreement'=>$dueDilligenceCollteralData[$j]['Due_dilligence_collateral']['date_for_aggreement'],'expirtation_date'=>$dueDilligenceCollteralData[$j]['Due_dilligence_collateral']['expirtation_date'],'terminated_file'=>$dueDilligenceCollteralData[$j]['Due_dilligence_collateral']['terminated_file'],'comment'=>$dueDilligenceCollteralData[$j]['Due_dilligence_collateral']['comment'],'knowledge'=>$dueDilligenceCollteralData[$j]['Due_dilligence_collateral']['knowledge']),false);
							}
						}
						$dueDilligenceEventData = $this->Due_dilligence_event->find('all',array('conditions'=>array('patent_id'=>$dueDilligenceData[$i]['Due_dilligence']['patent_number'])));
						if(count($dueDilligenceEventData)>0){
							for($j=0;$j<count($dueDilligenceEventData);$j++){
								$this->Due_dilligence_event->create();
								$this->Due_dilligence_event->save(array('patent_id'=>$dueDilligenceData[$i]['Due_dilligence']['patent_number'],'date_event'=>$dueDilligenceEventData[$j]['Due_dilligence_event']['date_event'],'code'=>$dueDilligenceEventData[$j]['Due_dilligence_event']['code'],'event_title'=>$dueDilligenceEventData[$j]['Due_dilligence_event']['event_title'],'description'=>$dueDilligenceEventData[$j]['Due_dilligence_event']['description'],'publication'=>$dueDilligenceEventData[$j]['Due_dilligence_event']['publication']),false);
							}
						}
						$dueDilligenceInventionData = $this->Due_dilligence_invention->find('first',array('conditions'=>array('patent_id'=>$dueDilligenceData[$i]['Due_dilligence']['patent_number'])));
						$this->Due_dilligence_invention->create();
						$this->Due_dilligence_invention->save(array('patent_id'=>$dueDilligenceData[$i]['Due_dilligence']['patent_number'],'problem'=>$dueDilligenceInventionData['Due_dilligence_invention']['problem'],'solution'=>$dueDilligenceInventionData['Due_dilligence_invention']['solution'],'system'=>$dueDilligenceInventionData['Due_dilligence_invention']['system'],'products'=>$dueDilligenceInventionData['Due_dilligence_invention']['products'],'companies'=>$dueDilligenceInventionData['Due_dilligence_invention']['companies']),false);
						$dueDilligencePartieData = $this->Due_dilligence_party->find('all',array('conditions'=>array('patent_id'=>$dueDilligenceData[$i]['Due_dilligence']['patent_number'])));
						if(count($dueDilligencePartieData)>0){
							for($j=0;$j<count($dueDilligencePartieData);$j++){
								$this->Due_dilligence_party->create();
								$this->Due_dilligence_party->save(array('patent_id'=>$dueDilligenceData[$i]['Due_dilligence']['patent_number'],'invloved_third_party'=>$dueDilligencePartieData[$j]['Due_dilligence_party']['invloved_third_party'],'date_of_aggreement'=>$dueDilligencePartieData[$j]['Due_dilligence_party']['date_of_aggreement'],'comment'=>$dueDilligencePartieData[$j]['Due_dilligence_party']['comment'],'party'=>$dueDilligencePartieData[$j]['Due_dilligence_party']['party'],'knowledge'=>$dueDilligencePartieData[$j]['Due_dilligence_party']['knowledge']),false);
							}
						}
						$dueDilligenceStandarsData = $this->Due_dilligence_standard->find('all',array('conditions'=>array('patent_id'=>$dueDilligenceData[$i]['Due_dilligence']['patent_number'])));
						if(count($dueDilligenceStandarsData)>0){
							for($j=0;$j<count($dueDilligenceStandarsData);$j++){
								$this->Due_dilligence_standard->create();
								$this->Due_dilligence_standard->save(array('patent_id'=>$dueDilligenceData[$i]['Due_dilligence']['patent_number'],'sdo'=>$dueDilligenceStandarsData[$j]['Due_dilligence_standard']['sdo'],'working_group'=>$dueDilligenceStandarsData[$j]['Due_dilligence_standard']['working_group'],'start_date'=>$dueDilligenceStandarsData[$j]['Due_dilligence_standard']['start_date'],'participants_claim'=>$dueDilligenceStandarsData[$j]['Due_dilligence_standard']['participants_claim'],'patent_file'=>$dueDilligenceStandarsData[$j]['Due_dilligence_standard']['patent_file'],'commitment_file'=>$dueDilligenceStandarsData[$j]['Due_dilligence_standard']['commitment_file'],'knowledge'=>$dueDilligenceStandarsData[$j]['Due_dilligence_standard']['knowledge']),false);
							}
						}
						$dueDilligenceTitleData = $this->Due_dilligence_title->find('all',array('conditions'=>array('patent_id'=>$dueDilligenceData[$i]['Due_dilligence']['patent_number'])));
						if(count($dueDilligenceTitleData)>0){
							for($j=0;$j<count($dueDilligenceTitleData);$j++){
								$this->Due_dilligence_title->create();
								$this->Due_dilligence_title->save(array('patent_id'=>$dueDilligenceData[$i]['Due_dilligence']['patent_number'],'title_file'=>$dueDilligenceTitleData[$j]['Due_dilligence_title']['title_file'],'assignor'=>$dueDilligenceTitleData[$j]['Due_dilligence_title']['assignor'],'assignee'=>$dueDilligenceTitleData[$j]['Due_dilligence_title']['assignee'],'assignee_date'=>$dueDilligenceTitleData[$j]['Due_dilligence_title']['assignee_date'],'recorded'=>$dueDilligenceTitleData[$j]['Due_dilligence_title']['recorded'],'rights'=>$dueDilligenceTitleData[$j]['Due_dilligence_title']['rights'],'comment'=>$dueDilligenceTitleData[$j]['Due_dilligence_title']['comment']),false);
							}
						}						
					}
				}
				$syndicationNumber = $this->data['project']['syndication_number'];
				$realPort = 1;
				//$this->set(compact('realPort','syndicationNumber'));
				$this->Session->write('syndication_number',$syndicationNumber);
				$this->Session->write('realPort',$realPort);				
			}			
		}
		$this->redirect(array('controller'=>'Users','action'=>'list_license'));
	}
	
	
	
	
	
	
	
	public function new_application(){
		if(count($this->data)){
			mkdir(ROOT.DS.'../synpat_com/store/'.$this->data['project']['syndication_number'], 0777); 
			/*copy(APP."/webroot/dummy.php", ROOT.DS.'../synpat_com/store/'.$this->data['project']['syndication_number']."/"."index.php");*/
			$this->recurse_copy(ROOT.DS."../synpat_com/store/12345",ROOT.DS.'../synpat_com/store/'.$this->data['project']['syndication_number']);
			$fp = fopen(ROOT.DS.'../synpat_com/store/'.$this->data['project']['syndication_number'].'/license.php', 'w'); 
			fwrite($fp, "<?php define('LICENSE_NUMBER','".$this->data['project']['syndication_number']."');?>");
			$title_for_layout = 'New Application Created';
			$syndicationNumber = $this->data['project']['syndication_number'];
			$this->set(compact('title_for_layout','syndicationNumber'));
		} else {
			$this->Session->setFlash('<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Please enter syndication number.</div>');
			$this->redirect(array('controller'=>'Users','action'=>'list_license'));
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
		$this->Session->write('redirect','list_license');
		if(!empty($this->data)){
			mkdir(ROOT.DS.'../synpat_com/store/'.$this->data['project']['syndication_number'], 0777); 
			/*copy(APP."/webroot/dummy.php", ROOT.DS.'../synpat_com/store/'.$this->data['project']['syndication_number']."/"."index.php");*/
			$this->recurse_copy(ROOT.DS."../synpat_com/store/12345",ROOT.DS.'../synpat_com/store/'.$this->data['project']['syndication_number']);
			$fp = fopen(ROOT.DS.'../synpat_com/store/'.$this->data['project']['syndication_number'].'/license.php', 'w'); 
			fwrite($fp, "<?php define('LICENSE_NUMBER','".$this->data['project']['syndication_number']."');?>");
			$title_for_layout = 'New Application Created';
			$syndicationNumber = $this->data['project']['syndication_number'];
			$this->set(compact('title_for_layout','syndicationNumber'));		
		}
		if(!$this->isUser()){
			$this->Session->setFlash('<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Please login first.</div>');
			$this->redirect(array('controller'=>'Users','action'=>'login'));
		}
		$syndicationNumber = $this->Session->read('syndication_number');
		$realPort = $this->Session->read('realPort');

		if(!empty($syndicationNumber)){
			$licenseData = $this->License->find('first',array('conditions'=>array('License.license_number'=>$syndicationNumber)));
			if(count($licenseData)>0){
				$license_id = $licenseData['License']['id'];
				$this->set(compact('license_id'));
			}
			$this->set(compact('syndicationNumber'));
		}
		if(!empty($realPort)){
			$this->set(compact('realPort'));			
		}
		$licenses = $this->License->find('all',array('conditions'=>array('License.visibility'=>0),'order'=>array('License.id'=>'ASC')));
		$title_for_layout = 'List Licenses';
		$this->set(compact('title_for_layout','licenses'));
	}

	public function findLicenseList(){
		$productList = array();
		if(!empty($this->data)){
			
		}
		echo json_encode($productList);
		die;
	}
	
	public function delete_license(){
		$this->Session->write('redirect','list_license');
		$ID = $this->request->query['id'];
		if($ID!=null){
			$this->License->id = $ID;
			$this->License->saveField('visibility', '1',false);
			$this->Session->setFlash('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Record deleted Successfully!</strong>.</div>');
		}
		$this->redirect(array('controller'=>'Users','action'=>'list_license'));
	}
	
	public function move_license(){
		$this->Session->write('redirect','list_license');
		$ID = $this->request->query['id'];
		if($ID!=null){
			$this->License->id = $ID;
			$this->License->saveField('visibility', '0',false);
			$this->Session->setFlash('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Record successfully moved!</strong>.</div>');
		}
		$this->redirect(array('controller'=>'Users','action'=>'trash_license'));
	}
	
	public function trash_license(){
		$this->Session->write('redirect','trash_license');
		if(!$this->isUser()){
			$this->Session->setFlash('<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Please login first.</div>');
			$this->redirect(array('controller'=>'Users','action'=>'login'));
		}
		$licenses = $this->License->find('all',array('conditions'=>array('License.visibility'=>1),'order'=>array('License.id'=>'ASC')));
		$title_for_layout = 'Trash Licenses';
		$this->set(compact('title_for_layout','licenses'));
	}
	public function edit_license($ID = null){
		$this->Session->write('redirect','edit_license/'.$ID);
		$this->Session->delete('syndication_number');
		$this->Session->delete('realPort');
		if(!$this->isUser()){
			$this->Session->setFlash('<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Please login first.</div>');
			$this->redirect(array('controller'=>'Users','action'=>'login'));
		}
		$license = $this->License->find('first',array('conditions'=>array('License.id'=>$ID)));
		
		if(count($license)==0){
			$this->Session->setFlash('<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Record not found.</div>');
			$this->redirect(array('controller'=>'Users','action'=>'list_license'));
		}
		
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
			if(empty($licenseData['license']['asking_price'])){
				$licenseData['license']['asking_price'] = 0;
			}			
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
							if(strpos($dueDilligenceData[$i][0],'US')===false){										
								$getData = $this->Due_dilligence_title->find('all',array('conditions'=>array('Due_dilligence_title.patent_id'=>$dueDilligenceData[$i][0],2)));
							} else {
								$getData = $this->Due_dilligence_title->find('all',array('conditions'=>array('Due_dilligence_title.patent_id'=>substr($dueDilligenceData[$i][0],2))));
							}						
							if(count($getData)==0){
								$this->getAssigmentNumber(substr($dueDilligenceData[$i][0],2));
							}
							$getCititation = $this->Due_dilligence_citation->find('all',array('conditions'=>array('Due_dilligence_citation.patent_id'=>$dueDilligenceData[$i][0])));
							
							if(count($getCititation)==0){
								try{
									
									$this->runCitationCrawler($dueDilligenceData[$i][0]);
								} catch(Exception $e){
								}
							}
							
							$getEvent = $this->Due_dilligence_event->find('all',array('conditions'=>array('Due_dilligence_event.patent_id'=>$dueDilligenceData[$i][0])));
							
							
							if(count($getEvent)==0){
								try{
									$this->runEventCrawler($dueDilligenceData[$i][0]);
								} catch(Exception $e){
								}
							}	
							
							$getPublished = $this->Due_dilligence_counterpart->find('all',array('conditions'=>array('Due_dilligence_counterpart.patent_id'=>$dueDilligenceData[$i][0])));
							
							
							if(count($getPublished)==0){
								try{
									$this->runPublishedAs($dueDilligenceData[$i][0]);
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
	
	
	function getAssigmentNumber($getPatentNumber){		
		if(strpos($getPatentNumber,'US')===false){
			$getPatentNumber = 'US'.$getPatentNumber;
		}
		
		$assigmentDataArray = array();
		require_once APP . 'Vendor' . DS.'simple_html_dom.php';
		set_time_limit(0);
		$url = "https://www.google.com/patents/".$getPatentNumber;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $content = curl_exec($ch);
		curl_close($ch);
		$applicationNumber= "";
		$needle = '<table class="patent-bibdata">';
		if (strpos($content, $needle,0) !== false) {
			$startPost = strpos($content, $needle);			
			$anotherNeedle ='<div class="patent-section patent-abstract-section">';
			if (strpos($content, $anotherNeedle,1)!== false){
				$anotherNeedle = '<a id="classifications"></a>';
				$subContent = substr($content,$startPost);				
				$endPost =  strpos($subContent, $anotherNeedle);
				$dataPatent =  substr($content,$startPost,$endPost);
				$html = str_get_html($dataPatent);	
				$n=0;
				$applicationNumber = "";
				foreach($html->find('<table class="patent-bibdata">') as $asTable) {	
					if($n==0){
						foreach($asTable->find('tr') as $tr){
							if($n==0){
								for($i=0;$i<count($tr->find('td'));$i++){
									if($n==0){
										$td = $tr->find('td');
										if($td[$i]->innertext=="Application number"){
											$j = $i+1;
											$applicationNumber =  $td[$j]->innertext;
											$n=1;
										}
									}															
								}
							}
						}
					}					
				}
				$applicationNumber = str_replace(",","",$applicationNumber);
				$applicationNumber =  str_replace("/","",$applicationNumber);
				if(preg_match("#([0-9\.]+)#", $applicationNumber, $match)) { // search for number that may contain '.' 
					$applicationNumber = floatval($match[0]); 
				} else { 
					$applicationNumber =  floatval(applicationNumber); // take some last chances with floatval 
				}
				
				if(!empty($applicationNumber)){
					$url = "http://prod1-proxy-lb-1431886266.us-gov-west-1.elb.amazonaws.com/solr/aotw/select?json.wrf=jQuery110208744277886580676_1434443736617&fq=applNum%3A%22".$applicationNumber."%22&q=*%3A*&fl=*&hl=false&wt=json&lowercaseOperators=true&sort=patAssignorEarliestExDate+asc&rows=500";
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
					curl_setopt($ch, CURLOPT_TIMEOUT, 60);
					$dataUSPTO = curl_exec($ch);
					curl_close($ch);
					if(!empty($dataUSPTO)):
					$dataUSPTO = str_replace("jQuery110208744277886580676_1434443736617(","",$dataUSPTO);
					$dataUSPTO = str_replace(")","",$dataUSPTO);
					$dataArray = json_decode($dataUSPTO);
					
					/*echo "<pre>";
					echo $dataArray->response->numFound."<br/>";
					echo $dataArray->response->docs[0]->patAssignorName[0]."<br/>";
					echo $dataArray->response->docs[0]->patAssignorExDate[0]."<br/>";
					echo $dataArray->response->docs[0]->patAssignorName[1]."<br/>";
					echo $dataArray->response->docs[0]->patAssignorExDate[1]."<br/>";
					echo $dataArray->response->docs[0]->patAssignorName[2]."<br/>";
					echo $dataArray->response->docs[0]->patAssignorExDate[2]."<br/>";
					echo $dataArray->response->docs[0]->conveyanceText."<br/>";
					echo $dataArray->response->docs[0]->recordedDate."<br/>";
					print_r($dataArray);
					die;*/
	
					if(isset($dataArray->response)){
						if(isset($dataArray->response->numFound) && $dataArray->response->numFound>0){
							$p=0;
							for($d=0;$d<count($dataArray->response->docs);$d++){
								$assignmentData = $dataArray->response->docs[$d];
								$conveyanceText = $assignmentData->conveyanceText;
								$recordedDate = $assignmentData->recordedDate;
								if(count($assignmentData->patAssignorName)>0){
									for($a=0;$a<count($assignmentData->patAssignorName);$a++){
										$assignee = "";
										if(isset($assignmentData->patAssigneeName[$a])){
											$assignee = $assignmentData->patAssigneeName[$a];
										} else {
											$assignee = $assignmentData->patAssigneeName[0];
										}
										
										if($p==0){
											/*$assigmentDataArray[] = array("patent_id"=>substr($getPatentNumber,2),"assignee"=>$assignee,"assignor"=>"","assignee_date"=>date("Y-m-d H:i:s",strtotime($assignmentData->patAssignorEarliestExDate)),"rights"=>"","recorded"=>"");*/
											$this->Due_dilligence_title->create();
											$this->Due_dilligence_title->save(array('patent_id'=>substr($getPatentNumber,2),'assignee'=>$assignee,'assignee_date'=>date("Y-m-d H:i:s",strtotime($assignmentData->patAssignorEarliestExDate))));
											$p=1;
										}
										/*$assigmentDataArray[] = array("patent_id"=>substr($getPatentNumber,2),"assignee"=>$assignee,"assignor"=>$assignmentData->patAssignorName[$a],"assignee_date"=>date("Y-m-d H:i:s",strtotime($assignmentData->patAssignorExDate[$a])),"rights"=>$conveyanceText,"recorded"=>date("Y-m-d H:i:s",strtotime($recordedDate)));*/
										$this->Due_dilligence_title->create();
										$this->Due_dilligence_title->save(array('patent_id'=>substr($getPatentNumber,2),'rights'=>$conveyanceText,'assignor'=>$assignmentData->patAssignorName[$a],'assignee'=>$assignee,'assignee_date'=>date("Y-m-d H:i:s",strtotime($assignmentData->patAssignorExDate[$a])),'recorded'=>date("Y-m-d H:i:s",strtotime($recordedDate))));
									}
								}
							}
						}
					}
					endif;
				}				
			}
		}
	}
	
	
	
	function runCitationCrawler($getPatentNumber){
		if(strpos($getPatentNumber,'US')===false){
			$getPatentNumber = 'US'.$getPatentNumber;
		}
		require_once APP . 'Vendor' . DS.'simple_html_dom.php';
		set_time_limit(0);
		$url = "https://www.google.com/patents/".$getPatentNumber;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $content = curl_exec($ch);
		curl_close($ch);
		$forwardArray = array();
		$backwardArray = array();
		$needle = '<a id="backward-citations"></a>';
		if (strpos($content, $needle,0) !== false) {
			$startPost = strpos($content, $needle);
			$anotherNeedle = '<a id="forward-citations"></a>';
			if(strpos($content, $anotherNeedle,1)===false){
				$anotherNeedle = '<a id="classifications"></a>';
			}
			if (strpos($content, $anotherNeedle,1) !== false) {
				$subContent = substr($content,$startPost);				
				$endPost =  strpos($subContent, $anotherNeedle);
				$dataPatent =  substr($content,$startPost,$endPost);
				$html = str_get_html($dataPatent);				
				
				$n=0;
				foreach($html->find('<table class="patent-data-table">') as $asTable) {											
					foreach($asTable->find('tr') as $tr){	
						if($n>0){
							$anchor = "";
							$fillingDate = "";
							$publicationDate = "";
							$applicant = "";
							$title ="";							
							$c=0;
							foreach($tr->find('td') as $td){
								switch($c){
									case 0:
										$citedPatent="";
										foreach($td->find('a') as $anchor){
											$citedPatent .= $anchor->innertext.", ";
										}																
										$citedPatent = substr($citedPatent,0,-2);
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
									case 4:
										$title = $td->innertext;
									break;
								}
								$c++;
							}
							$backwardArray[] = array('cited_patent'=>$citedPatent,'filling_date'=>$fillingDate,'event'=>$publicationDate,'applicant'=>$applicant,'title'=>$title);													
						}
						$n++;
					}
					//echo $dataPatent;
				}
			}
		}
		$needle = '<a id="forward-citations"></a>';
		
		if (strpos($content, $needle,0) !== false) {
			$startPost = strpos($content, $needle);
			$anotherNeedle = '<a id="classifications"></a>';
			if (strpos($content, $anotherNeedle,1) !== false) {
				$subContent = substr($content,$startPost);				
				$endPost =  strpos($subContent, $anotherNeedle);
				$dataPatent =  substr($content,$startPost,$endPost);
				$html = str_get_html($dataPatent);				
				
				$n=0;
				foreach($html->find('<table class="patent-data-table">') as $asTable) {											
					foreach($asTable->find('tr') as $tr){	
						if($n>0){
							$anchor = "";
							$fillingDate = "";
							$publicationDate = "";
							$applicant = "";
							$title ="";							
							$c=0;
							foreach($tr->find('td') as $td){
								switch($c){
									case 0:
										$citedPatent="";
										foreach($td->find('a') as $anchor){
											$citedPatent .= $anchor->innertext.", ";
										}																
										$citedPatent = substr($citedPatent,0,-2);
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
									case 4:
										$title = $td->innertext;
									break;
								}
								$c++;
							}
							$forwardArray[] = array('cited_patent'=>$citedPatent,'filling_date'=>$fillingDate,'event'=>$publicationDate,'applicant'=>$applicant,'title'=>$title);													
						}
						$n++;
					}
					//echo $dataPatent;
				}
			}
		}
		if(count($backwardArray)>0){
			for($j=0;$j<count($backwardArray);$j++){
				if(!empty($backwardArray[$j]['cited_patent'])){
				$eventArray = array('patent_id'=>$getPatentNumber,'cited_patent'=>$backwardArray[$j]['cited_patent'],'filling_date'=>$backwardArray[$j]['filling_date'],'event'=>$backwardArray[$j]['event'],'applicant'=>$backwardArray[$j]['applicant'],'title'=>$backwardArray[$j]['title'],'type'=>0);
				$this->Due_dilligence_citation->create();
				$this->Due_dilligence_citation->save($eventArray);
				}
			}
		}
		if(count($forwardArray)>0){
		
			for($j=0;$j<count($forwardArray);$j++){
				if(!empty($forwardArray[$j]['cited_patent'])){
				$eventArray = array('patent_id'=>$getPatentNumber,'cited_patent'=>$forwardArray[$j]['cited_patent'],'filling_date'=>$forwardArray[$j]['filling_date'],'event'=>$forwardArray[$j]['event'],'applicant'=>$forwardArray[$j]['applicant'],'title'=>$forwardArray[$j]['title'],'type'=>1);
				$this->Due_dilligence_citation->create();
				$this->Due_dilligence_citation->save($eventArray);
				}
			}
		}
		
	}
	function runPublishedAs($getPatentNumber){
		if(strpos($getPatentNumber,'US')===false){
			$getPatentNumber = 'US'.$getPatentNumber;
		}
		require_once APP . 'Vendor' . DS.'simple_html_dom.php';
		set_time_limit(0);
		//$content = file_get_contents("https://www.google.com/patents/".$getPatentNumber);
		$url = "https://patents.google.com/patent/".$getPatentNumber."/en";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20150101 Firefox/31.0");
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $content = curl_exec($ch);
		curl_close($ch);
		$forwardArray = array();
		/*echo "<textarea>".$content."</textrea>";*/
		$needle = '<h3 id="alsoPublishedAs" class="layout horizontal">';
		if (strpos($content, $needle,0) !== false) {
			$startPost = strpos($content, $needle);
			$anotherNeedle = '<h3 id="legalEvents" class="layout horizontal">';
			if (strpos($content, $anotherNeedle,1) !== false) {
				$endPost =  strpos($content, $anotherNeedle);
				$dataPatent =  substr($content,$startPost,$endPost);
				$html = str_get_html($dataPatent);
				$t=0;
				if(is_object($html)):
				foreach($html->find('<table cellspacing="0">') as $asTable) {	
					if($t==0):
					$tR =0;
					foreach($asTable->find('tr') as $tr){
						$publication_number ="";
						$publication_date ="";
						$patent_office ="";
						$primary_language ="";
						$type ="";
						if($tR>0):						
						$c=0;
						foreach($tr->find('td') as $td){
							switch($c){
								case 0:
									$publication_number = trim(strip_tags($td->innertext));
								break;
								case 1:
									$publication_date = $td->innertext;														
								break;
								case 2:
									$patent_office = $td->innertext;
								break;
								case 3:
									$primary_language = $td->innertext;
								break;
								case 4:
									$type = $td->innertext;
								break;
							}
							$c++;
						}
						$forwardArray[] = array('publication_number'=>$publication_number,'publication_date'=>$publication_date,'patent_office'=>$patent_office,'primary_language'=>$primary_language,"type"=>$type);
						endif;
						$tR++;
					}
					endif;
					$t++;
				}
				endif;
			}
		}
		if(count($forwardArray)>0){
			for($j=0;$j<count($forwardArray);$j++){			
				$publishedArray = array('patent_id'=>$getPatentNumber,'publication_number'=>$forwardArray[$j]['publication_number'],'publication_date'=>$forwardArray[$j]['publication_date'],'patent_office'=>$forwardArray[$j]['patent_office'],'primary_language'=>$forwardArray[$j]['primary_language'],'type'=>$forwardArray[$j]['type']);
				$this->Due_dilligence_counterpart->create();
				$this->Due_dilligence_counterpart->save($publishedArray);
			}
		}
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
		if(count($expoTotalAssigment)>1){
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
		} else {
			$totalAssignmentCount = array();
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
	
	
	public function add_license($syndicateNumber = null){
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
			if(empty($this->data['license']['asking_price'])){
				$licenseData['license']['asking_price'] = 0;
			}
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
				/*Insert Store User */
				$this->Store_user->save(array('project_id'=>$licenseID,'type'=>'user','access_code'=>$licenseData['license']['access_code']));
				/*End Store User*/
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
								$this->getAssigmentNumber(substr($dueDilligenceData[$i][0],2));
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
							$this->Chart_right->save(array('license_id'=>$licenseID,'year'=>$chart_right[$i][0],'data'=>$chart_right[$i][1]),false);
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
		if($syndicateNumber==null){
			$syndicateNumber = '';
		}
		$newDataForAccordionText = $this->License->find('first',array('conditions'=>array('License.license_number'=>'12345')));
		
		$this->set(compact('title_for_layout','newDataForAccordionText','syndicateNumber'));
	}
}
