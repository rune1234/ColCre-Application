<?php
require_once JPATH_COMPONENT.'/models/wallet.php';
class WalletControllerMainwallet extends WalletController
{
    public function display()
    { 
        $thisModel = $this->getModel('colcre');
        $user = JFactory::getUser();
         
        list($rows, $pagination) = $thisModel->getList($user->id);
        $viewType = "html";
        $this->name = 'Colcre';
        $view = &$this->getView('walletview', $viewType);
        $view->assign('rows', $rows);
        $view->assign('userid', $user->id);
        $view->assign('pagination', $pagination);
        $view->display();
    }
     public function deleteMethod()
    {
        $msg = array();
        $msg['error'] = 0;
        $msg['msg'] = 'Payment type successfully deleted';
        if ($_GET['user_id'] != $_GET['userid']) 
        {
            $msg['error'] = 1;
            $msg['msg'] = "Wrong User ID";
            echo json_encode($msg);
            exit;
        }
        $id = $_GET['id'];
        $userid = $_GET['userid'];
        $type = $_GET['type'];
        $token = $_GET['token'];
        $tokenHere = md5($id."idtype".$type.$userid);
        if ($token != $tokenHere || ( !is_numeric($id) || !is_numeric($userid) || !is_numeric($type) )) 
        {
            $msg['error'] = 1;
            $msg['msg'] = "Wrong Data";
            echo json_encode($msg);
            exit;
        }
        $db = JFactory::getDbo();
        $query = "DELETE FROM #__colcretypesaddresses WHERE user_id = $userid AND payment_type = $type AND id = $id LIMIT 1";
        $r = $db->setQuery($query)->Query();
        if (! $r)
        {
            $msg['error'] = 1;
            $msg['msg'] = "ERROR"; 
        }
        echo json_encode($msg);
        exit;
    }
    public function assets()
    {
        $viewType = "html";
        $this->name = 'Colcre';
        $thisModel = $this->getModel('colcre');
        $view = &$this->getView('assets', $viewType);
        $user_id = (int)JRequest::getVar('user_id');//(int)$_GET['user_id'];
        if ($user_id === 0)
        {
            $user = JFactory::getUser();
            $user_id = $user->id;
        }
        $rows = $thisModel->getUserAssets($user_id);
        $view->assign('rows', $rows);
        $view->display();
    }
    public function sendPoints()
    {
        $viewType = "html";
        $this->name = 'Colcre';
        $post = JRequest::get( 'post' );
        $result = false;
        $thisModel = $this->getModel('colcre');
        $view = &$this->getView('sendpoints', $viewType);
         
        if (isset($post) && isset($post['sendingpoints']) &&  $post['sendingpoints'] == 1)
        { list($msg, $result) = $thisModel->sendPoints($post); }
        else 
        { 
            $myUser = JFactory::getUser(); 
            $msg = '';
           // $myPoints = $thisModel->getUserPoints($myUser->id); 
            
        }
        //$view->assign('mypoints', $myPoints);
        $view->assign('msg', $msg);
        $view->assign('result', $result);
        $view->assign('post', $post);
        $view->display();
    }
    public function method()
    {
        $viewType = "html";
        $this->name = 'Colcre';
        $user = JFactory::getUser();
        $user_id = $user->id;
        $post = JRequest::get( 'post' );
        $thisModel = $this->getModel('colcre');
        if ($post && isset($post['payment_type']))
        {   
           // print_r($post); exit;
            if ((int)$post['payment_type'] != 3)
            { $a = $thisModel->addressType($post); } 
            else
            {
               $a = $thisModel->bankStore($post);
            }
            if ($a)
            { echo "<script language='javascript'>window.location = '".JRoute::_('index.php?option=com_colcrewallet&task=method')."';</script>"; }
        }
        $cash = "";//$thisModel->getCash($user_id);
        $view = &$this->getView('methods', $viewType);
        $view->assign('cash', $cash);
        $view->assign('thisModel', $thisModel);
        $address = '';
        $view->assign('address', $address);
        $view->assign('user_id', $user_id);
        $view->display();
    }
    public function cashOut()
    {
        $viewType = "html";
        $this->name = 'Colcre';
        $user = JFactory::getUser();
        $user_id = $user->id;
        $cash = 0;
        $post = JRequest::get( 'post' );
        if (isset($post) && isset($post['getcash']) &&  $post['getcash'] == 1)
        {
            $thisModel = $this->getModel('colcre');
            $cash = $thisModel->getCash($user_id, $post); 
            if ($cash === 1)
            {
                echo "<div style='background: #fff; padding: 10px;'><p>Your transfer of ".$post['amount']." will be processed in a few days.</p>";
                echo "<p>Click <a href='".JRoute::_('index.php?option=com_colcrewallet&task=assets')."'>here</a> to return to your assets.</p></div>";
            }
           
        }
       // print_r($cash);
        if ($cash !== 1) 
        {
            $thisModel = $this->getModel('colcre');
            $view = &$this->getView('cashout', $viewType);
            $view->assign('cash', $cash);
            $view->assign('user_id', $user_id);
            $view->display();
        }
    }
}

