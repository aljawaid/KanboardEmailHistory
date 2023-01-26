<?php

namespace Kanboard\Plugin\KanboardEmailHistory\Controller;

use Kanboard\Controller\BaseController;
use Kanboard\Core\Plugin\Directory;

/**
 * Plugin KanboardEmailHistory
 * Class EmailHistoryController
 * @author aljawaid
 */

class EmailHistoryController extends \Kanboard\Controller\ActionCreationController
{
    /**
     * Define action parameters (step 3)
     *
     * @access public
     */
    public function parameters()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();
        $values['project_id'] = $project['id'];

        if (empty($values['action_name']) || empty($values['event_name'])) {
            $this->create();
            return;
        }

        $action = $this->actionManager->getAction($values['action_name']);
        $action_params = $action->getActionRequiredParameters();

        if (empty($action_params)) {
            $this->doCreation($project, $values + array('params' => array()));
        }

        $projects_list = $this->projectUserRoleModel->getActiveProjectsByUser($this->userSession->getId());
        unset($projects_list[$project['id']]);

        $this->response->html($this->template->render('kanboardEmailHistory:action_creation/params', array(
            'values' => $values,
            'action_params' => $action_params,
            'columns_list' => $this->columnModel->getList($project['id']),
            'users_list' => $this->projectUserRoleModel->getAssignableUsersList($project['id']),
            'projects_list' => $projects_list,
            'colors_list' => $this->colorModel->getList(),
            'categories_list' => $this->categoryModel->getList($project['id']),
            'links_list' => $this->linkModel->getList(0, false),
            'priorities_list' => $this->projectTaskPriorityModel->getPriorities($project),
            'project' => $project,
            'available_actions' => $this->actionManager->getAvailableActions(),
            'swimlane_list' => $this->swimlaneModel->getList($project['id']),
            'events' => $this->actionManager->getCompatibleEvents($values['action_name']),
        )));
    }
}
