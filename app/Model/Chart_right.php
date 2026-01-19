<?php
class Chart_right extends AppModel {
    public $name = 'Chart_right';
	public $belongsTo = array('License'=>array(
								'className'=>'License',
								'foreignKey'=>'license_id'
						  ));
}

