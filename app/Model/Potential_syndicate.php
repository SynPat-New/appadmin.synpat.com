<?php
class Potential_syndicate extends AppModel {
    public $name = 'Potential_syndicate';
	public $belongsTo = array('License'=>array(
								'className'=>'License',
								'foreignKey'=>'license_id'
						  ));
}

 