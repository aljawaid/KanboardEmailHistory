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
	}
	
	public function onStartup()
    {
      	Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

	public function getPluginName()	
	{ 		 
		// Plugin Name MUST be identical to namespace for Plugin Directory to detect updated versions
		return 'KanboardEmailHistory';
	}

	public function getPluginDescription()
	{
		return t('This plugin adds a new Automatic Action to provide the user with a final email report. Upon closing tasks, automatic emails can be sent of each task description with full comment history to selected recipients or the assigned project email address.');
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

	public function getPluginHomepage() 
	{ 	 
		return 'https://github.com/aljawaid/KanboardEmailHistory'; 
	}

}
