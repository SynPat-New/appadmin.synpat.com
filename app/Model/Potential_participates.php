<?php
class Potential_participates extends AppModel {
    public $name = 'Potential_participates';
	public $belongsTo = array('License'=>array(
								'className'=>'License',
								'foreignKey'=>'license_id'
						  ));
}
