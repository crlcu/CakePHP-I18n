<?php 
class TranslationsController extends I18nAppController {
	public $components = array('I18n.Translate');
	
    public function index() {
		$this->set('translations', $this->paginate());
	}
    
    public function add() {		
		if ( $this->request->is('post') ) {
			if ( $this->Translation->save( $this->data ) ) {
				$this->Translate->export();
				$this->Session->setFlash(__('Sentence successfully added!'), 'success');
				
				return $this->redirect(array('action' => 'index'));
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
				return $this->redirect(array('action' => 'index'));
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
				
				return $this->redirect(array('action' => 'index'));
			}
		} else {
			$this->data = $translation;
		}
		
		$this->set(compact('languages', 'translation'));
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
