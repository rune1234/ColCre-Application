<?php
/**
 * @package      Projectfork
 * @subpackage   Dashboard
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2012 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();


jimport('joomla.application.component.controller');


/**
 * Projectfork main controller
 *
 * @see    JController
 */
class ProjectforkController extends JControllerLegacy
{
    /**
     * Constructor
     *
     * @param    array    $config    Optional config options
     */
    function __construct($config = array())
    {
        parent::__construct($config);
    }


    /**
     * Displays the current view
     *
     * @param     boolean    $cachable    If true, the view output will be cached  (Not Used!)
     * @param     array      $urlparams   An array of safe url parameters and their variable types (Not Used!)
     *
     * @return    JController             A JController object to support chaining.
     */
    public function msgOwner()//redacron function, message a project Owner
    {
        $post = $_POST;
        $db = JFactory::getDbo();
        $proposal = $db->escape($post['proposal']);
        $howwould = $db->escape($post['howwould']);
        $project_id = $post['project_id'];
        $created_by = $post['created_by'];
        $user_id = $post['user_id'];
        if (!is_numeric($user_id) || !is_numeric($created_by) || !is_numeric($project_id)) return;
        $name = JFactory::getUser($user_id);
        $query = "INSERT INTO #__pf_projects_msg (project_id,owner_id, user_id,from_name,posted_on,howwould,proposal) VALUES ($project_id, $created_by, $user_id, '".$name->name."', ".time().", '$howwould', '$proposal')";
        $db->setQuery($query);
        $db->Query();
        $this->_messageUser($post, $name->name, $db);
        $post['proposal'] = nl2br($post['proposal']);
        $post['howwould'] = nl2br($post['howwould']);
        echo json_encode($post);
        exit;
    }
    private function _messageUser($post, $name, & $db)
    {
        $db =& JFactory::getDBO();
        $subject = "Project ".$this->_projectTitle($post['project_id'],  $db)." Proposal from ".ucwords($name);
        $query = "INSERT INTO `#__community_msg` (`id`, `from`, `parent`, `deleted`, `from_name`, `posted_on`, `subject`, `body`) VALUES (NULL, ".$post['user_id'].", 1, 0, '".$name."', '".date('Y-m-d H:i:s', time())."', '".$subject."', '".$db->escape($post['proposal'])."')";
        $db->setQuery($query);
        $db->Query();
        $insertId = $db->insertid();
        if (is_numeric($insertId))
        {
            $query = "INSERT INTO #__community_msg_recepient (`msg_id`,`msg_parent`,`msg_from`,`to`,`bcc`,`is_read`,`deleted`) VALUES ($insertId, 1, ".$post['created_by'].",".$post['user_id'].", 0, 0, 0)";
            $db->setQuery($query);
            $db->Query();
        }
        return;
    }
    private function _projectTitle($id, & $db)
    {
        $query = "SELECT title FROM #__pf_projects WHERE id = $id LIMIT 1";
        $db->setQuery($query);
        return $db->loadResult();
    }
    public function display($cachable = false, $urlparams = false)
    {
        // Load CSS and JS assets
        JHtml::_('pfhtml.style.bootstrap');
        JHtml::_('pfhtml.style.projectfork');

        JHtml::_('pfhtml.script.jquery');
        JHtml::_('pfhtml.script.bootstrap');
        JHtml::_('pfhtml.script.projectfork');

        JHtml::_('behavior.tooltip');

        // Override method arguments
        $urlparams = array('id'               => 'INT',
                           'filter_project'   => 'CMD'
                           );
        $document = JFactory::getDocument();
        $document->addScript(JURI::root() . 'components/com_pfprojects/js/pfp.js');
        $js = "var projectURL = '".JURI::root()."';";
        $document->addScriptDeclaration($js);
        // Display the view
        parent::display($cachable, $urlparams);

        // Return own instance for chaining
        return $this;
    }
}