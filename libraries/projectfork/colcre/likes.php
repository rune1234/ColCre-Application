<?php
defined('_JEXEC') or die();
class projectLikes
{
    var $db;
    function __construct()
    {
        $this->db = JFactory::getDbo();
    }
    public function likeProject($user_id, $type_id, $type)
    {
        $response = new stdClass;
        if ($user_id == 0) 
        {
            $response->msg = "Please log in";
            $response->error = 1;
            echo json_encode($response);
            return;
        }
        if ($this->alreadyLiked($user_id, $type_id, $type))
        {
            $response->msg = "Already liked";
            $response->error = 0;//if it is 0, user won't get a warning.
            echo json_encode($response);
            return;
        }
        $query = "INSERT INTO `#__pf_likes` (`id`, `user_id`, `type`, `type_id`) VALUES (NULL, '$user_id', '$type', '$type_id')";
        $this->db->setQuery($query);
        if ($this->db->Query())
        {
            $this->countLike($type_id, $type);
            $response->msg = "Successful";
            $response->error = 0;
            echo json_encode($response);
        }
        else
        {
             $response->msg = "Something went wrong. Try again";
            $response->error = 1;
            echo json_encode($response);
        }
    }
    private function alreadyLiked($user_id, $type_id, $type)
    {
        $query = "SELECT id FROM #__pf_likes WHERE type_id = $type_id AND user_id = $user_id AND type = $type LIMIT 1";
        $this->db->setQuery($query);
        $result = $this->db->loadResult();
        return ($result) ? true : false;
    }
    private function countLike($type_id, $type)
    {
        $query = "SELECT id FROM #__pf_likescount WHERE type_id = $type_id AND type = $type LIMIT 1";
        $this->db->setQuery($query);
        $result = $this->db->loadResult();
        if (!$result)
        { $query = "INSERT INTO `#__pf_likescount` (`id`, `type`, `type_id`,  `quantity`) VALUES (NULL, '$type', '$type_id', 1)"; }
        else { $query = "UPDATE `#__pf_likescount` SET quantity = quantity + 1 WHERE type = '$type' AND type_id = '$type_id'"; }
        $this->db->setQuery($query);
        $this->db->Query();
    }
    public function getLikes($type_id, $type)
    {
        $query = "SELECT quantity FROM #__pf_likescount WHERE type_id = $type_id AND type = $type LIMIT 1";
        $this->db->setQuery($query);
        $result = $this->db->loadResult();
        echo ($result) ? $result : 0;
    }
}

