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

		//Task Comments
        	//$this->template->setTemplateOverride('notification/task_create', 'kanboardEmailHistory:notification/task_create');
        	//$this->template->setTemplateOverride('task_comments/show', 'kanboardEmailHistory:task_comments/show');
        	//$this->template->setTemplateOverride('comment/show', 'kanboardEmailHistory:comment/show');
        	//$this->template->setTemplateOverride('notification/footer', 'kanboardEmailHistory:notification/footer');
    
		
	}
	
	public function onStartup()
        {
               Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
        }

	
	public function getPluginName()	
	{ 		 
		return 'EmailTaskHistory'; 
	}

	public function getPluginAuthor() 
	{ 	 
		return 'aljawaid'; 
	}

	public function getPluginVersion() 
	{ 	 
		return '1.0.0'; 
	}

	public function getPluginDescription() 
	{ 
		return 'Action to email a tasks history on close'; 
	}

	public function getPluginHomepage() 
	{ 	 
		return 'https://github.com/aljawaid/KanboardEmailHistory'; 
	}
}
