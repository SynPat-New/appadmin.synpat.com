<?php
class Request_to_participates extends AppModel {
    public $name = 'Request_to_participates';
	public $belongsTo = array('License'=>array(
								'className'=>'License',
								'foreignKey'=>'license_id'
						  ));
}

