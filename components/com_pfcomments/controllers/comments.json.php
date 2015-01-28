<?php
/**
 * @package      Projectfork
 * @subpackage   Comments
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2012 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();


jimport('projectfork.controller.admin.json');


/**
 * Projectfork Comment List Controller
 *
 */
class PFcommentsControllerComments extends PFControllerAdminJson //Redacron warning: this *********************** attention ******** is in the projectFork library. Most of the task functions are there
{
    /**
     * The default view
     *
     * @var    string
     */
    protected $view_list = 'comments';


    /**
     * Method to get a model object, loading it if required.
     *
     * @param     string    $name      The model name. Optional.
     * @param     string    $prefix    The class prefix. Optional.
     * @param     array     $config    Configuration array for model. Optional.
     *
     * @return    object               The model.
     */
    private function commentTrash($cid, $value, $task)//redacron function. We had to create this to override projectfork stuff
    {
        $db = JFactory::getDbo();
        jimport('projectfork.colcre.permissions');
        $perm = new colcrePermissions();//redacron alteration. We are overriding access here
        
        foreach ($cid as $id)
        {
            $item = $perm->commentData($id);
            if (!$perm->comments($item, $task))
            { return false; } 
            else
            {
                $query = "UPDATE #__pf_comments SET state = $value WHERE id = $id LIMIT 1";
                $db->setQuery($query);
                $db->Query();
            }
             
        }
        return true;
    }
    public function publish()//redacron alteration. It is used to override the publish used by ProjectFork
    {
         $rdata = array();
        $rdata['success']  = "true";
        $rdata['messages'] = array();
       // $rdata['data']     = array();

        // Check for request forgeries
        if (!JSession::checkToken()) {
            $rdata['success']    = false;
            $rdata['messages'][] = JText::_('JINVALID_TOKEN');

            $this->sendResponse($rdata);
        }

        // Get items to publish from the request.
        $cid   = JRequest::getVar('cid', array(), '', 'array');
        $data  = array('publish' => 1, 'unpublish' => 0, 'archive' => 2, 'trash' => -2, 'report' => -3);
        $task  = $this->getTask();
        $value = JArrayHelper::getValue($data, $task, 0, 'int');
        
        if (empty($cid)) {
            $rdata['success']    = "false";
            $rdata['messages'][] = JText::_($this->text_prefix . '_NO_ITEM_SELECTED');
        }
        else {
            // Get the model.
           // $model = $this->getModel();

            // Make sure the item ids are integers
            JArrayHelper::toInteger($cid);

            // Publish the items.
        if (!$this->commentTrash($cid, $value, $task)) {///*|| /*!$model->publish($cid, $value*/)) {  
                 $rdata['success']    = "false";
                 $rdata['messages'][] = "There is a $task error";//$model->getError();
            }
            else {
                if ($value == 1) {
                    $ntext = $this->text_prefix . '_N_ITEMS_PUBLISHED';
                }
                elseif ($value == 0) {
                    $ntext = $this->text_prefix . '_N_ITEMS_UNPUBLISHED';
                }
                elseif ($value == 2) {
                    $ntext = $this->text_prefix . '_N_ITEMS_ARCHIVED';
                }
                else {
                    $ntext = $this->text_prefix . '_N_ITEMS_TRASHED';
                }

                $rdata['success']    = "true";
                $rdata['messages'][] = JText::plural($ntext, count($cid));
            }
        }
        
        $this->sendResponse($rdata);
    }
    public function &getModel($name = 'Form', $prefix = 'PFcommentsModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
 
        return $model;
    }
    
}
