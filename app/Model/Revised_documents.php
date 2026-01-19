<?php
class Revised_documents extends AppModel {
    public $name = 'Revised_documents';
	public $belongsTo = array('License'=>array(
								'className'=>'License',
								'foreignKey'=>'license_id'
						  ));
}
