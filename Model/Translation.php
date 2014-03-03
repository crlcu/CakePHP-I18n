<?php 
class Translation extends I18nAppModel {
    public $belongsTo = array(  
        'Language'  => array(  
            'className'  => 'I18n.Language'
        )
    );
    
    public $hasMany = array(  
        'Translations'  => array(  
            'className'  => 'I18n.Translation',
			'dependent'  => true,
            'foreignKey' => 'parent_id',
            'conditions' => array(
                'Translations.deleted' => 0
            )
        )
    );
    
    /**
	 *	model search conditions
	 */
	public $search = array(
        'Translation' => array(
			'msgid' => array(
                'condition' => 'like'
            )
        )
    );
}
