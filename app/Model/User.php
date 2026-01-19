<?php
class User extends AppModel {
    public $name = 'User';
	 public $validate = array(
			'purchase_file' => array(
				'required' => array(
					'rule' => array('notEmpty'),
					'message' => 'A purchase file is required'
				)
			),
			'purchase_license' => array(
				'required' => array(
					'rule' => array('notEmpty'),
					'message' => 'A purchase license file is required'
				)
			),
			'purchase_buy' => array(
				'required' => array(
					'rule' => array('notEmpty'),
					'message' => 'A purchase buy file is required'
				)
			)
		);
	
	public function beforeSave($options = array()) {
		if (empty($this->data[$this->alias]['purchase_file'])) {
			$this->Session->setFlash('<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Please select file for purchase agreement.</div>');
		}
		return false;
	}

}

