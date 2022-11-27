<?php

namespace Kanboard\Plugin\KanboardEmailHistory\Action;

use Kanboard\Model\TaskModel;
use Kanboard\Model\CommentModel;
use Kanboard\Model\ProjectModel;
use Kanboard\Model\UserMetadataModel;
use Kanboard\Action\Base;

class EmailTaskHistory extends Base
{
   
    public function getDescription()
    {
        return t('EmailTaskHistory > Send task description and complete comment history by email on task closure');
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
	        'check_box_include_title' => t('Include the Task Title and ID in the subject line?'),
            'check_box_include_project' => t('Include the Project Name in the subject line?'),
            'check_box_include_project_identifier' => t('Include the Project Identifier in the subject line?'),
        );
    }
   
    public function getEventRequiredParameters()
    {
        return array(
            'task',
            // These are event parameters and the task close event does not have a comment parameter
        );
    }
    
    public function doAction(array $data)
    {
        // TASK COMMENTS
        // here is where we will pull all the comments from a task using the same method in the TaskViewController, note we would need to add the user metadata model up above in "use"
        $commentSortingDirection = $this->userMetadataCacheDecorator->get(UserMetadataModel::KEY_COMMENT_SORTING_DIRECTION, 'ASC');
        $comments = $this->commentModel->getAll($data['task']['id'], $commentSortingDirection);
        
        $historySent = FALSE;

        // SUBJECT OPTIONS
        if ($this->getParam('check_box_include_title') == true ) {
            // TASK TITLE // Subject becomes: `subject` `task title` `task id`
            $subject = $this->getParam('subject') . ": " . $data['task']['title'] . " (#" . $data['task']['id'] . ")";

        } elseif ($this->getParam('check_box_include_project') == true ) {
            // PROJECT NAME // Subject becomes: `subject` `project name` `task title` `task id`
            $project = $this->projectModel->getById($project_id);
            $subject = $this->getParam('subject') . ": " $project['name'] . " " . $data['task']['title'] . " (#" . $data['task']['id'] . ")";

        } elseif ($this->getParam('check_box_include_title') == true && $this->getParam('check_box_include_project_identifier')) {
            // PROJECT IDENTIFIER // Subject becomes: `subject` `project identifier`
            $project = $this->projectModel->getById($project_id);
            $subject = $this->getParam('subject') . ": " $project['identifier'];

        } elseif ($this->getParam('check_box_include_title') == true && $this->getParam('check_box_include_project_identifier')) {
            // PROJECT IDENTIFIER + TITLE // Subject becomes: `subject` `project identifier` `task title` `task id`
            $project = $this->projectModel->getById($project_id);
            $subject = $this->getParam('subject') . ": " $project['identifier'] . " " . $data['task']['title'] . " (#" . $data['task']['id'] . ")";

        } elseif ($this->getParam('check_box_include_project') == true && $this->getParam('check_box_include_project_identifier')) {
            // PROJECT NAME + PROJECT IDENTIFIER // Subject becomes: `subject` `project identifier`
            $project = $this->projectModel->getById($project_id);
            $subject = $this->getParam('subject') . ": " $project['name'] . " " . $project['identifier'];

        } elseif ($this->getParam('check_box_include_title') == true && $this->getParam('check_box_include_project')) {
            // PROJECT NAME + TITLE // Subject becomes: `subject` `project name` `task title` `task id`
            $project = $this->projectModel->getById($project_id);
            $subject = $this->getParam('subject') . ": " $project['name'] . " " . $data['task']['title'] . " (#" . $data['task']['id'] . ")";

        } elseif ($this->getParam('check_box_include_title') == true && $this->getParam('check_box_include_project_identifier') && $this->getParam('check_box_include_project')) {
            // PROJECT NAME + PROJECT IDENTIFIER + TITLE // Subject becomes: `subject` `project name` `project identifier` `task title` `task id`
            $project = $this->projectModel->getById($project_id);
            $subject = $this->getParam('subject') . ": " $project['name'] . " " .$project['identifier'] . " " . $data['task']['title'] . " (#" . $data['task']['id'] . ")";

        } else {
            // NO SELECTION // Subject becomes: `subject`
            $subject = $this->getParam('subject');
        }
        
        // CONSTRUCT EMAIL - SEND TO SELECTED OR BOTH
        if ($this->getParam('send_to') !== null) {
            $send_to = $this->getParam('send_to');
        } else {
            $send_to = 'both';
        }
        
            // CONSTRUCT EMAIL - SEND TO 'ASSIGNEE' OR BOTH
            if ($send_to == 'assignee' || $send_to == 'both') {
                $user = $this->userModel->getById($data['task']['owner_id']);
    
                if (! empty($user['email'])) {
                    $myHTML = $this->template->render('kanboardEmailHistory:notification/task_create', array('task' => $data['task']));
                    $myHTML = $myHTML . $this->template->render('kanboardEmailHistory:task_comments/show', array(
                        'task' => $data['task'],
                        'comments' => $comments,
                        'project' => $this->projectModel->getById($data['task']['project_id']),
                        'editable' => false,
                    ));
                    $myHTML = $myHTML . $this->template->render('kanboardEmailHistory:notification/footer', array('task' => $data['task']));
                    
                    // Send email
                    $this->emailClient->send(
                        $user['email'],
                        $user['name'] ?: $user['username'],
                        $subject,
                        $myHTML
                    );

                    // Add comment to task to show an email has been fired
                    $this->commentModel->create( array(
                    	'comment' => t('Task history emailed to the task assignee @'.$user['username'].' with subject "'. $subject).'".',
                    	'user_id' => $user['id'],
                    	'task_id' => $data['task']['id'],
                    ));
                    
                    $historySent = TRUE;

                    // An easy way to test code is to use error_log
                    error_log("KanboardEmailHistory > Email Sent",0);
                } 
            }

            // CONSTRUCT EMAIL - SEND TO 'CREATOR' OR BOTH
            if ($send_to == 'creator' || $send_to == 'both') {
                $user = $this->userModel->getById($data['task']['creator_id']);
    
                if (! empty($user['email'])) {
                    $myHTML = $this->template->render('kanboardEmailHistory:notification/task_create', array('task' => $data['task']));
                    $myHTML = $myHTML . $this->template->render('kanboardEmailHistory:task_comments/show', array(
                        'task' => $data['task'],
                        'comments' => $comments,
                        'project' => $this->projectModel->getById($data['task']['project_id']),
                        'editable' => false,
                    ));
                    $myHTML = $myHTML . $this->template->render('kanboardEmailHistory:notification/footer', array('task' => $data['task']));

                    // Send email
                    $this->emailClient->send(
                        $user['email'],
                        $user['name'] ?: $user['username'],
                        $subject,
                        $myHTML
                    );

                    // Add comment to task to show an email has been fired
                    $this->commentModel->create( array(
                    	'comment' => t('Task history emailed to the task creator @'.$user['username'].' with subject "'. $subject).'".',
                    	'user_id' => $user['id'],
                    	'task_id' => $data['task']['id'],
                    ));

                    $historySent = TRUE;

                    // An easy way to test code is to use error_log
                    error_log("KanboardEmailHistory > Email Sent",0);
                } 
            }
        return $historySent;
    }
   
    public function hasRequiredCondition(array $data)
    {
        return true;
    }
}
