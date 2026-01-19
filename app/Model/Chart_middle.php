<?php
class Chart_middle extends AppModel {
    public $name = 'Chart_middle';
	public $belongsTo = array('License'=>array(
								'className'=>'License',
								'foreignKey'=>'license_id'
						  ));
}

