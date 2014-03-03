<?php 
class TranslationsController extends I18nAppController {
	public $components = array('I18n.Translate');
	
    public function index() {		
        $this->paginate = array(
            'conditions' => array(
                $this->Translation->search( $this->Authentication->User->search('Translation') ),
                'Translation.parent_id' => null
            ),
            'limit' => I18N_TRANSLATIONS_INDEX_LIMIT,
            'recursive' => 3
        );
        
		$this->request->data = $this->Authentication->User->search('Translation');
		$this->set('translations', $this->paginate());
	}
    
    public function add() {		
		if ( $this->request->is('post') ) {
			if ( $this->Translation->save( $this->data ) ) {
				$this->Translate->export();
				$this->Session->setFlash(__('Sentence successfully added!'), 'success');
				
				$this->redirect(array('action' => 'index'));
			}
		}
	}
    
    public function translate( $id = null ) {
        $translation = $this->Translation->findById( $id );
        $languages = $this->Translate->languages();
        
		if ( $this->request->is('put') ) {
			if ( $this->Translation->save( $this->data ) ) {
				$this->Translate->export();
				$this->Session->setFlash(__('Translation successfully saved!'), 'success');
				$this->redirect(array('action' => 'index'));
			}
		} else {
            $this->data = $translation;
		}
		
		$this->set(compact('languages', 'translation'));
	}
    
	public function edit( $id = null ) {
		$translation = $this->Translation->findById( $id );
		$languages = $this->Translate->languages();
		
		if ( !$id || empty($translation) ) {
			$this->Session->setFlash(__('Invalid translation id!'), 'warning');
			$this->redirect(array('action' => 'index'));

		}
		
		if ( $this->request->is('put') ) {
			if ( $this->Translation->save( $this->data ) ) {
				$this->Translate->export();
				$this->Session->setFlash(__('Translation successfully saved!'), 'success');
				
				$this->redirect(array('action' => 'index'));
			}
		} else {
			$this->data = $translation;
		}
		
		$this->set(compact('languages', 'translation'));
	}
	
	public function _translate() {
		$sentences = $this->Translation->findAllByLanguage('eng');
		
		foreach ($sentences as $sentence) {
			foreach ($this->Translate as $language => $country) {
				$translated = $this->Translation->find('all', array('conditions' => array(
                    'Translation.language' => $language,
                    'Translation.msgid' => $sentence['Translation']['msgid']
                )));
				
				if (count($translated) == 0) {
				    $this->Translation->create();
                    
                    $this->request->data['Translation']['parent_id']  = $sentence['Translation']['id']; 
					$this->request->data['Translation']['msgstr'] = $this->Translate->translate($sentence['Translation']['msgid'], 'en', substr($language, 0, 2)); 
					$this->request->data['Translation']['msgid']  = $sentence['Translation']['msgid']; 
					$this->request->data['Translation']['language'] = $language; 
					$this->request->data['Translation']['status'] = 'm';
					
					$this->Translation->save($this->request->data); 
				} 
			}
		}
        
        $this->Session->setFlash(__('Automate translation successfully done!'), 'success');
        $this->redirect(array('action' => 'index'));
    }
    
    protected function _delete( $id = null ) {
        $translation = $this->Translation->findById( $id );
        
        $translation['Translation']['user_id'] = $this->Authentication->User->id();
        $translation['Translation']['deleted'] = 1;
        
        foreach ($translation['Languages'] as $index => $language) {
            $translation['Languages'][$index]['user_id'] = $this->Authentication->User->id();
            $translation['Languages'][$index]['deleted'] = 1;
        }
        
		return $translation;
    }
    
    protected function _undo( $id = null ) {
        $this->Translation->checkDeleted = 0;
        $translation = $this->Translation->findById( $id );
        
        $translation['Translation']['user_id'] = $this->Authentication->User->id();
        $translation['Translation']['deleted'] = 0;
        
        foreach ($translation['Languages'] as $index => $language) {
            $translation['Languages'][$index]['user_id'] = $this->Authentication->User->id();
            $translation['Languages'][$index]['deleted'] = 0;
        }
        
		return $translation;    
    }
}
