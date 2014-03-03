<?php
App::import('Model', 'I18n.Translation');
App::import('Model', 'I18n.Language');

class TranslateComponent extends Component {
	public function __construct(ComponentCollection $collection, $settings = array()) {
		parent::__construct($collection, $settings);
		
		$this->Translation = new Translation();
	}
	
	public function initialize(Controller $controller) {
	}

	public function startup(Controller $controller) {
	}
	
    public function languages( $type = 'list' ) {
        $this->Language = new Language();
        
        switch ($type) {
            case 'list':
                return $this->Language->find($type, array('fields' => array('id', 'language'), 'conditions' => array('Language.enabled' => 1)));
                break;
            case 'all':
                return $this->Language->find($type, array('conditions' => array('Language.enabled' => 1)));
                break;
            default:
                return $this->Language->find($type, array('conditions' => array('Language.enabled' => 1)));
                break;
        }
    }
    
	/**
	* Translate a piece of text with the Google Translate API
	* @return String
	* @param $text String
	* @param $from String[optional] Original language of $text. An empty String will let google decide the language of origin
	* @param $to String[optional] Language to translate $text to
	*/
	public function translate($text, $from = '', $to = 'eng') {
		$url = 'http://mymemory.translated.net/api/get?q='.rawurlencode($text).'&langpair='.rawurlencode($from.'|'.$to);
        
		$response = file_get_contents(
			$url,
			null,
			stream_context_create(
				array(
					'http' => array(
						'method' => "GET",
						'header' => "Referer: http://".$_SERVER['HTTP_HOST']."/\r\n"
					)
				)
			)
		);
		
		if (preg_match("/{\"translatedText\":\"([^\"]+)\"/i", $response, $matches)) {
			return self::_unescapeUTF8EscapeSeq($matches[1]);
		}
		
		return false;
	}
	
	public function export() {
		$filename = 'tmp' . gmdate('YmdHis');
        $languages = $this->languages('all');
		
		foreach ($languages as $language) {
			Cache::delete('default_' . $language['Language']['iso'], '_cake_core_');
			Cache::delete('cake_dev_' . $language['Language']['iso'], '_cake_core_');
			
			$path = ROOT . DS . 'app' . DS . 'Locale' . DS . $language['Language']['locale'];
			
			if ( !file_exists($path) ) {
				mkdir($path);
            }
            
			$path .= DS . 'LC_MESSAGES';
			
			if (!file_exists($path))
				mkdir($path);
				
			$file = $path . DS . $filename;
			
			if (!file_exists($path))
				touch($file); 
			
			$file = new File($path . DS . $filename); 
			$sentences = $this->Translation->find('all', array(
                'conditions' => array(
                    'Translation.language_id' => $language['Language']['id'],
                    'Translation.msgid NOT' => null,
                    'Translation.msgstr NOT' => null
                )
            ));
			
            // write an empty string
            $file->write('');
            
			foreach ($sentences as $sentence) {
				$file->write('msgid "' . $sentence['Translation']['msgid'] . '"'."\n"); 
				$file->write('msgstr "' . $sentence['Translation']['msgstr'] . '"'."\n"); 
			}
			
			$file->close();
            
			rename ($path . DS . $filename, $path . DS . 'default.po');
		}
	}
   
	/**
	* Convert UTF-8 Escape sequences in a string to UTF-8 Bytes
	* @return UTF-8 String
	* @param $str String
	*/
	protected function _unescapeUTF8EscapeSeq($str) {
		return preg_replace_callback("/\\\u([0-9a-f]{4})/i", create_function('$matches', 'return TranslateComponent::_bin2utf8(hexdec($matches[1]));'), $str);
	}
   
	/**
	* Convert binary character code to UTF-8 byte sequence
	* @return String
	* @param $bin Mixed Interger or Hex code of character
	*/
	protected function _bin2utf8($bin) {
		if ($bin <= 0x7F) {
			return chr($bin);
		} else if ($bin >= 0x80 && $bin <= 0x7FF) {
			return pack("C*", 0xC0 | $bin >> 6, 0x80 | $bin & 0x3F);
		} else if ($bin >= 0x800 && $bin <= 0xFFF) {
			return pack("C*", 0xE0 | $bin >> 11, 0x80 | $bin >> 6 & 0x3F, 0x80 | $bin & 0x3F);
		} else if ($bin >= 0x10000 && $bin <= 0x10FFFF) {
			return pack("C*", 0xE0 | $bin >> 17, 0x80 | $bin >> 12 & 0x3F, 0x80 | $bin >> 6& 0x3F, 0x80 | $bin & 0x3F);
		}
	}
}
