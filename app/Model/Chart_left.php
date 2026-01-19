<?php
class Chart_left extends AppModel {
    public $name = 'Chart_left';
	public $belongsTo = array('License'=>array(
								'className'=>'License',
								'foreignKey'=>'license_id'
						  ));
}

