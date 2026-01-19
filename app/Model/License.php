<?php
class License extends AppModel {
    public $name = 'License';
    public $hasMany = array('Invitee'=>array(
							'className'=>'Invitee',
							'foreignKey'=>'license_id'
						  ),
						  'Due_dilligence'=>array(
							'className'=>'Due_dilligence',
							'foreignKey'=>'license_id'
						  ),
						  'Commitment'=>array(
							'className'=>'Commitment',
							'foreignKey'=>'license_id'
						  ),
						  'Chart_left'=>array(
							'className'=>'Chart_left',
							'foreignKey'=>'license_id'
						  ),
						  'Chart_middle'=>array(
							'className'=>'Chart_middle',
							'foreignKey'=>'license_id'
						  ),
						  'Chart_right'=>array(
							'className'=>'Chart_right',
							'foreignKey'=>'license_id'
						  ),
						  'Potential_syndicate'=>array(
							'className'=>'Potential_syndicate',
							'foreignKey'=>'license_id'
						  ));
	
}

