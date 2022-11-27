<?php

namespace Kanboard\Plugin\KanboardEmailHistory;

use Kanboard\Core\Plugin\Base;
use Kanboard\Plugin\KanboardEmailHistory\Action\EmailTaskHistory;
use Kanboard\Core\Translator;

class Plugin extends Base

{
    
	public function initialize()
    
	{
        
		$this->actionManager->register(new EmailTaskHistory($this->container));
		
		if (!file_exists('plugins/AutoSubtasks')) {
		    $this->template->setTemplateOverride('action_creation/params', 'kanboardEmailHistory:action_creation/params');
		}
    		
	}
	
	public function onStartup()
        {
               Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
        }

	
	public function getPluginName()	
	{ 		 
		return 'KanboardEmailHistory'; 
	}

	public function getPluginAuthor() 
	{ 	 
		return 'aljawaid'; 
	}

	public function getPluginVersion() 
	{ 	 
		return '1.2.0'; 
	}

    	public function getCompatibleVersion()
    	{
        	// Examples:
        	// >=1.0.37
        	// <1.0.37
        	// <=1.0.37
        	return '>=1.2.20';
    	}	

	public function getPluginDescription() 
	{ 
		return 'Action to email the full history of a task on closure'; 
	}

	public function getPluginHomepage() 
	{ 	 
		return 'https://github.com/aljawaid/KanboardEmailHistory'; 
	}
}
