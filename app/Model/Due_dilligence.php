<?php
class Due_dilligence extends AppModel {
    public $name = 'Due_dilligence';
	public $belongsTo = array('License'=>array(
								'className'=>'License',
								'foreignKey'=>'license_id'
						  ));
}

