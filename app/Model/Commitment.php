<?php
class Commitment extends AppModel {
    public $name = 'Commitment';
	public $belongsTo = array('License'=>array(
								'className'=>'License',
								'foreignKey'=>'license_id'
						  ));
}

