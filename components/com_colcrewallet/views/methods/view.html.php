<?php
/* @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * HTML Article View class for the Content component
 *
 * @package     Joomla.Site
 * @subpackage  com_content
 * @since       1.5
 */
class colcreViewMethods extends JViewLegacy
{
       public function display($tpl = null)
       {
           $df = JRequest::getVar('df', 0);
           if ($df == 0)
           {
               $this->address = $this->thisModel->getAddresses($this->user_id);//we get all addresses except bank accounts
               $this->banks = $this->thisModel->getBanks($this->user_id);//now we get all bank accounts
               
           }
           elseif ($df > 0)
           {
                if ($df == 1) $pt = 1;
                elseif ($df == 2) $pt = 4;
                elseif ($df == 5) $pt = 2;
                $id = JRequest::getInt('id');
                if ($df != 4) 
                {
                    
                    if (is_numeric($id) && $id > 0) $data = $this->thisModel->getAddressInfo($pt, $this->user_id, $id);
                    else $data = false;
                    $this->address = ($data) ? $data->address : false;
                }
                else
                {
                    if (is_numeric($id) && $id > 0) $data = $this->thisModel->getBankInfo($this->user_id, $id);
                    else $data = false;
                    $this->address = $data;
                    //print_r($this->address);
                }
           }
           if ($df == 0) $this->setLayout('default');
           else $this->setLayout('default_'.$df);
           parent::display($tpl);
       }
}