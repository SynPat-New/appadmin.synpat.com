<?php
class Invitee extends AppModel {
    public $name = 'Invitee';
	public $belongsTo = array('License'=>array(
								'className'=>'License',
								'foreignKey'=>'license_id'
						  ));
}

