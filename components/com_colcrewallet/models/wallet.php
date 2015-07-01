<?php
jimport('joomla.application.component.modellist');
 
class WalletModelColcre extends JModelList
{ 
    public function getBanks($user_id)
    {
        if (!is_numeric($user_id) || $user_id == 0) return false;
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__colcrewalletbank WHERE user_id = $user_id LIMIT 5";
        $row = $db->setQuery($query)->loadObjectList();
        return ($row) ? $row : false;
    }
    public function getBankInfo($user_id, $id)
    {
        if (!is_numeric($user_id) || $user_id == 0) return false;
        if (!is_numeric($id) || $id == 0) return false;
        $db = JFactory::getDbo();
        $query = "SELECT * FROM #__colcrewalletbank WHERE user_id = $user_id AND id = $id LIMIT 1";
        //echo $query;
        $row = $db->setQuery($query)->loadObject();
        return ($row) ? $row : false;
    }
    public function bankStore($post)
    {
         JRequest::checkToken() or die( 'Invalid Token' );
         $user_id = $post['user_id'];
         $id = $post['id'];
         if (!is_numeric($user_id) || $user_id == 0) return;
         $db = JFactory::getDbo();
         $bankaccount = $db->escape($post['bankaccount']);
         $bicswift = $db->escape($post['bicswift']);
         $accountholdername = $db->escape($post['accountholdername']);
         $accountholdernumber = $db->escape($post['accountholdernumber']);
         $payment_type = $db->escape($post['payment_type']);
         $row = $this->getBankInfo($user_id, $id);
         if (!$row)
         { $query = "INSERT INTO #__colcrewalletbank (user_id, holder_name, account, bic, iban, last_updated) VALUES ('$user_id', '$accountholdername', '$bankaccount', '$bicswift', '$accountholdernumber', '".time()."')"; }
         else
         { $query = "UPDATE #__colcrewalletbank SET holder_name = '$accountholdername', account = '$bankaccount', bic = '$bicswift', iban = '$accountholdernumber', last_updated =  '".time()."' WHERE id = $id AND user_id = $user_id LIMIT 1"; }
         $a = $db->setQuery($query)->Query();
         return $a;
    }
    public function addressType($post)//function is used to add or update payment addresses
    {
          JRequest::checkToken() or die( 'Invalid Token' );
          if (!isset($post['address']) || trim($post['address']) == '')
          {
              if ($post['payment_type'] != 2) echo "<div style='color: #a00;'>ERROR - please enter an email address</div>";
              else echo "<div style='color: #a00;'>ERROR - please enter an ddress</div>";
              return;
          }
          if (!is_numeric($post['payment_type']))
          {
              echo "<div style='color: #a00;'>ERROR - wrong payment type</div>";
              return;
          }
          if (!is_numeric($post['user_id']) || $post['user_id'] == 0)
          {
              echo "<div style='color: #a00;'>ERROR - wrong user id</div>";
              return;
          }
          $db = JFactory::getDbo();
          $row = $this->getAddressInfo($post['payment_type'], $post['user_id'], $post['id']);
           
          if ($row)
          {
              $query = "UPDATE #__colcretypesaddresses SET address = '".$post['address']."', last_updated = '".time()."'";
              $query .= " WHERE id ='".$post['id']."' && payment_type = '".$post['payment_type']."' AND user_id = '".$post['user_id']."' LIMIT 1";
          }
          else
          { 
              $query = "INSERT INTO  #__colcretypesaddresses (payment_type,address,user_id,last_updated)";
              $query .= " VALUES ('".$post['payment_type']."',  '".$db->escape($post['address'])."', '".$post['user_id']."',  '".time()."');";
          }
          $a = $db->setQuery($query)->Query();
          return ($a) ? true : false;
      }
      public function getAddressInfo($payment_type, $user_id, $id = '')
      {
          $db = JFactory::getDbo();
          if (!is_numeric($user_id) || $user_id == 0) return false;
          
          if ($id == 0) return false;
          $query = "SELECT * FROM #__colcretypesaddresses WHERE id = $id && payment_type = '".$payment_type."' AND user_id = '".$user_id."' LIMIT 1";
          $row = $db->setQuery($query)->loadObject();
          return $row;
      }
      public function getAddresses($user_id)
      {
          $db = JFactory::getDbo();
          if (!is_numeric($user_id) || $user_id == 0) return false;
          $query = "SELECT * FROM #__colcretypesaddresses WHERE user_id = '".$user_id."' LIMIT 5";
          $row = $db->setQuery($query)->loadObjectList();
          return $row;
      }
      public function getList($userid)
      {
          $db = JFactory::getDbo();
          if (!is_numeric($userid) || $userid == 0) return;
          $limitstart = JRequest::getInt('limitstart');
          $limit = JRequest::getVar( "viewlistlimit", '10', 'get', 'int');
          $total = $db->setQuery("SELECT count(id) FROM #__colcrewallet")->loadResult();
          $pagination = new JPagination($total, $limitstart, $limit);
          $rows = $db->loadObjectList();
          $query = "SELECT * FROM #__colcrewallet WHERE (sender_id = $userid OR recipient_id = $userid) ORDER BY id DESC LIMIT $limitstart, $limit";
          $rows = $db->setQuery($query)->loadObjectList();
          $pagination = $pagination->getPagesLinks();
          $i = 0;
          foreach ($rows as $row)
          {
              if ($row->type == 2) $rows[$i]->recipientData = $this->getProjectData($row->recipient_id, $db);
              else { $rows[$i]->recipientData = $this->getUserData($row->recipient_id, $db); }
              $rows[$i]->userData = $this->getUserData($row->sender_id, $db);
              $i++;
          }
          return array($rows, $pagination);
      }
      public function getUserAssets($userid)
      {
          if (!is_numeric($userid)) return;
          $db = JFactory::getDbo();
          //with this limit, you cannot have more ten payment systems:
          $query = "SELECT * FROM #__colcreassets WHERE recipient_id = $userid and type = 1 LIMIT 10";
          //echo $query;
          $db->setQuery($query);
          return $db->loadObjectList();
      }
      public function getCash($id, $post)
      {
          JRequest::checkToken() or die( 'Invalid Token' );
          if (!is_numeric($id) || $id == 0) { return "ERROR - non-numeric user id";  }
          if (!is_numeric($post['amount']) || $post['amount'] == 0) {  return "<p>ERROR - incorrect amount.</p>";  }
          $db = JFactory::getDbo();
          $query = "SELECT last_updated FROM #__colcremoney WHERE user_id = $id ORDER BY last_updated DESC limit 1";
          $row = $db->setQuery($query)->loadObject();
          if ($row)
          {
              $minutes = time() - $row->last_updated;
              if ($minutes < 600)
              {
                  return "<p>You need to wait at least ten minutes before widthdrawing money again.</p>";
                  
              }
          }
          $query = "INSERT INTO #__colcremoney (user_id, money, description, method_type, last_updated) VALUES ($id,  ".$post['amount'].", '".$db->escape($post['cashoutmsg'])."', '".$db->escape($post['widthmethod'])."',".time().")";
          $row = $db->setQuery($query)->Query();
          return ($row) ? 1 : 'There has been an error - contact administrator for more information';
      }
      private function getUserData($id, & $db)
      {
          if (!is_numeric($id)) return false;
          $query = "SELECT * FROM #__users WHERE id = $id LIMIT 1";
          return $db->setQuery($query)->loadObject();
          
      }
      private function getProjectData($id, & $db)
      {
          if (!is_numeric($id)) return false;
          $query = "SELECT id, title, created_by FROM #__pf_projects WHERE id = $id LIMIT 1";
          return $db->setQuery($query)->loadObject();
      }
      public function sendPoints($post)
      {
          //print_r($post); return;
          if (!is_numeric($post['user_id']) && trim($post['username']) == '') return array("Invalid Data", false);
          if (!is_numeric($post['points']) && $post['points']== 0) return array("Invalid Number of Points", false);
          //with this system, you cannot have more than ten payment systems:
          if ($post['method'] > 10 || $post['method'] < 1) return array("ERROR - unknown method. If this is happening, contact Make Whatever as soon as possible", false);
          $myUser = JFactory::getUser();
          $myUser = $myUser->id;
          //print_r($post); exit;
          if ($myUser == 0) return array("You need to log in first", false);
          $db = JFactory::getDbo();
          if (!is_numeric($post['user_id']))
          {
              $recUser = $db->setQuery("SELECT id, username FROM #__users WHERE username = '".trim($db->escape($post['username']))."' LIMIT 1")->loadObject();
              $post['user_id'] = $recUser->id;
              $recUserTo = $recUser->username;
              if (!$post['user_id']) return array("Invalid User", false);
          }
          if ($post['user_id'] == $myUser) { return array("You cannot send points to yourself", false); }
          $myPoints = $this->getUserPoints($myUser, $post['method']);
          if (!$myPoints || $post['points'] > $myPoints) { return array("You do not have enough points!", false); }
          $s = $this->sendPointsTo($post['user_id'], $post['points'], $myUser, $post['message'], $post['method']);
          
          if ($s) { 
              $this->sendMoney($post);
              return array($post['points']. " points were sent to ".$recUser->username.".", true); }
          else return array("We were unable to send your points. Sorry", false);
      }
      private function sendMoney($post)
      {
          if (!is_numeric($post['user_id']) && trim($post['username']) == '') return array("Invalid Data", false);
          if (!is_numeric($post['points']) && $post['points']== 0) return array("Invalid Number of Points", false);
          $myUser = JFactory::getUser();
          $myUser = $myUser->id;
          //print_r($post); exit;
          if ($myUser == 0) return array("You need to log in first", false);
          $db = JFactory::getDbo();
          if ($post['user_id'] == $myUser) { return array("You cannot send money to yourself", false); }
          $myMoney = $this->getUserMoney($myUser);
           
          $s = $this->sendMoneyTo($post['user_id'], $post['points'], $myUser, $post['message'], $post['method']);
          if ($s) { return array("Points were sent to ".$recUser->username.".", true); }
          else return array("We were unable to send your points. Sorry", false);
      }
      public function getUserPoints($userid, $payment_type)
      {
          if (!is_numeric($userid) || $userid == 0) return false;
          if (!is_numeric($payment_type) || $payment_type == 0) return false;
          $db = JFactory::getDbo();
          $points = $db->setQuery("SELECT points FROM #__colcreassets WHERE type = 1 AND recipient_id = $userid AND payment_type = $payment_type LIMIT 1")->loadResult();
          return ($points) ? $points : false;
      }
      public function getUserMoney($userid)
      {
          if (!is_numeric($userid) || $userid == 0) return false;
          $db = JFactory::getDbo();
          $money = $db->setQuery("SELECT money FROM #__colcremoney WHERE user_id = $userid LIMIT 1")->loadResult();
          return ($money) ? $money : false;
      }
      private function sendMoneyTo($userid, $points, $sender, $message, $method)
      { 
          if (!is_numeric($userid) || $userid == 0) return;
          if (!is_numeric($sender) || $sender == 0) return;
          if (!is_numeric($points) || $points == 0) return;
          $db = JFactory::getDbo();
          $myMoney = $this->getUserMoney($sender);
          $newMoney = $this->pointsTOmoney($points, $method);
          if (!$newMoney) $newMoney = 0;
          $oldMoney = $this->getUserMoney($userid);
          if (!$oldMoney) $oldMoney = 0;
          if ($newMoney > $myMoney) { echo "<div class='warn'>There has been an error</div>"; return false; }
          if ($oldMoney == false)//not ===, since $oldMoney may be zero
          {
              $query = "INSERT INTO #__colcremoney (user_id, money, last_updated) VALUES ($userid, $newMoney, ".time().")";
              $succ = $db->setQuery($query)->Query();
          }
          else
          {
              $newMoney2 = $newMoney + $oldMoney;
              $query = "UPDATE #__colcremoney SET last_updated ='".time()."', money = $newMoney2 WHERE user_id = $userid LIMIT 1";
              $succ = $db->setQuery($query)->Query();
          }
          if ($succ)//take points from the sender
          {
              $query = "UPDATE #__colcremoney SET last_updated ='".time()."', money = ".($myMoney - $newMoney)." WHERE user_id = $sender LIMIT 1";
              $db->setQuery($query)->Query();
          }
          return true;
      }
      private function pointsTOmoney($points, $method)
      {
          //1 = dollars, 2 = tokens, 3 = bitcoins
          $methodArray = array();
          $methodArray[1] = 1;
          $methodArray[2] = 1;
          $methodArray[3] = 1;
          return $methodArray[$method] * $points;
      }
      public function sendPointsTo($userid, $points, $sender, $message, $method)
      { 
          JRequest::checkToken() or die( 'Invalid Token' );
          if (!is_numeric($userid) || $userid == 0) return;
          if (!is_numeric($sender) || $sender == 0) return;
          if (!is_numeric($points) || $points == 0) return;
          $db = JFactory::getDbo();
          $mypoints = $this->getUserPoints($sender, $method);
          
          $oldPoints = $this->getUserPoints($userid, $method);
          if ($points > $mypoints) { echo "<div class='warn'>There has been an error</div>"; return false; }
          if ($oldPoints === false)
          {
              $query = "INSERT INTO #__colcreassets (recipient_id, points, last_updated, type, payment_type) VALUES ($userid, $points, ".time().", 1, $method)";
              $succ = $db->setQuery($query)->Query();
          }
          else
          {
              $newpoints = $points + $oldPoints;
              $query = "UPDATE #__colcreassets SET last_updated ='".time()."', points = $newpoints WHERE recipient_id = $userid AND type = 1 AND payment_type = $method LIMIT 1";
              $succ = $db->setQuery($query)->Query();
          }
          $query = "INSERT INTO #__colcrewallet (sender_id, message, points, status, type, recipient_id, payment_type, date_added) VALUES ($sender, '".$db->escape($message)."', $points, 'released', 1, $userid, $method, ".time().")";
          $db->setQuery($query)->Query();
          if ($succ)//take points from the sender
          {
              $query = "UPDATE #__colcreassets SET last_updated ='".time()."', points = ".($mypoints - $points)." WHERE recipient_id = $sender AND type = 1 AND payment_type = $method LIMIT 1";
              $db->setQuery($query)->Query();
              $this->_messageUser($sender, $userid, $points, $message,$db);
              $this->_inviteUser($sender, $userid, $points, $message);
          }
          return true;
      }
      private function _messageUser($sender, $receiver, $points, $msg, & $db)
      {
            $db =& JFactory::getDBO();
            $sedData = JFactory::getUser($sender);
            $recData = JFactory::getUser($receiver);
            $subject = 'User '.$sedData->name. " has sent you points";
            $msg = "<p>Hello,<br />".$sedData->name." has sent you $points points.</p><p>".$sedData->name." message: <br />".$db->escape($msg)."</p>";
            $query = "INSERT INTO `#__community_msg` (`id`, `from`, `parent`, `deleted`, `from_name`, `posted_on`, `subject`, `body`) VALUES (NULL, ".$sender.", 1, 0, '".$sedData->name."', '".date('Y-m-d H:i:s', time())."', '".$subject."', '".$msg."')";
            $db->setQuery($query);
             
            $db->Query();
            $insertId = $db->insertid();
            if (is_numeric($insertId))
            {
                $query = "INSERT INTO #__community_msg_recepient (`msg_id`,`msg_parent`,`msg_from`,`to`,`bcc`,`is_read`,`deleted`) VALUES ($insertId, $insertId, ".$sender.",".$receiver.", 0, 0, 0)";
                $db->setQuery($query);
                $db->Query();
                $query = "UPDATE `#__community_msg` SET parent = $insertId WHERE id = $insertId LIMIT 1";
                $db->setQuery($query);
                $db->Query();
            }
            return;
     }
     private function _inviteUser($sender, $receiver, $points, $msg)
    {
         $sedData = JFactory::getUser($sender);
         $recData = JFactory::getUser($receiver);
         $mailMSG = "<p>Hi ".ucwords($recData->name).",</p>
<p>User ".ucwords($sedData->name)." has sent you $points points.</p>
<p>".ucwords($sedData->name)." message: <br />$msg</p>";
         $mainframe = JFactory::getApplication();
         $mailfrom = $mainframe->getCfg('mailfrom');
         $fromname = $mainframe->getCfg('fromname');
         $mail = JFactory::getMailer();
         $mail->sendMail($mailfrom, $fromname, $recData->email, "You have earned points", $mailMSG, true);    
         return;
    }
}
?>