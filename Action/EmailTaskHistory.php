<?php

namespace Kanboard\Plugin\KanboardEmailHistory\Action;

use Kanboard\Model\TaskModel;
use Kanboard\Model\CommentModel;
use Kanboard\Action\Base;

class EmailTaskHistory extends Base
{
   
    public function getDescription()
    {
        return t('Send full task description and comments by email');
    }

   
    public function getCompatibleEvents()
    {
        return array(
            TaskModel::EVENT_CLOSE,
	);
    }

   
    public function getActionRequiredParameters()
    {
        return array(
	        'subject' => t('Email subject'),
	        'send_to' => array('assignee' => t('Send to Assignee'), 'creator' => t('Send to Creator'), 'both' => t('Send to Both')),
	        'check_box_include_title' => t('Include Task Title and ID in subject line?'),
        );
    }

   
    public function getEventRequiredParameters()
    {
        return array(
            'task',
            'comment',
        );
    }

    
    public function doAction(array $data)
    {
        $historySent = FALSE;
        if ($this->getParam('check_box_include_title') == true ){
            $subject = $this->getParam('subject') . ": " . $data['task']['title'] . "(#" . $data['task']['id'] . ")";
        } else {
            $subject = $this->getParam('subject');
        }
        
        if ($this->getParam('send_to') !== null) { $send_to = $this->getParam('send_to'); } else { $send_to = 'both'; }
        
            if ($send_to == 'assignee' || $send_to == 'both') {
                $user = $this->userModel->getById($data['task']['owner_id']);
    
                if (! empty($user['email'])) {
                    // Send email
                    $this->emailClient->send(
                        $user['email'],
                        $user['name'] ?: $user['username'],
                        $subject,
                        <?= $this->template->render('task_comments/show', array(
                        	'task' => $task,
                        	'comments' => $comments,
                        	'project' => $project,
                        	'editable' => false,
                        ))
                    );
                    // Add comment to task to show an email has been fired
                    $this->commentModel->create( array(
                    	'comment' => t('Task history emailed to "%s" with subject "%s".', $values['email'], $values['subject']),
                    	'user_id' => $user,
                    	'task_id' => $task['id'],
                    ));
                    $historySent = TRUE;
                } 
            }
            if ($send_to == 'creator' || $send_to == 'both') {
                $user = $this->userModel->getById($data['task']['creator_id']);
    
                if (! empty($user['email'])) {
                    // Send email
                    $this->emailClient->send(
                        $user['email'],
                        $user['name'] ?: $user['username'],
                        $subject,
                        <?= $this->template->render('task_comments/show', array(
                        	'task' => $this->getTask(),
                        	'comments' => $this->commentModel->getAll($task['id'], $commentSortingDirection),
                        	'project' => $this->projectModel->getById($task['project_id']),
                        	'editable' => false,
                        ))
                    );
                    // Add comment to task to show an email has been fired
                    $this->commentModel->create( array(
                    	'comment' => t('Task history emailed to "%s" with subject "%s".', $values['email'], $values['subject']),
                    	'user_id' => $user,
                    	'task_id' => $task['id'],
                    ));
                    $historySent = TRUE;
                } 
            }
        return $historySent;
    }


   
    public function hasRequiredCondition(array $data)
    {
        return true;
    }
}

