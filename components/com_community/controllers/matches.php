<?php
class CommunityMatchesController extends CommunityBaseController
{
    function display()
    { 
        $model	=  $this->getModel ( 'matches' );
		$msg	=  $model->getMatches ();
		$modMsg	= array ();
 
		$view	=  $this->getView ( 'matches' );
		$my		= CFactory::getUser ();

                if($my->id == 0)
		{
			return $this->blockUnregister();
		}

		// Add small avatar to each image
		if (! empty ( $msg ))
		{
			foreach ( $msg as $key => $val )
			{
				// based on the grouped message parent. check the unread message
				// count for this user.
				$filter ['parent'] = $val->parent;
				$filter ['user_id'] = $my->id;
				$unRead = $model->countUnRead ( $filter );
				$msg [$key]->unRead = $unRead;
			}
		}
		$data = new stdClass ( );
		$data->msg = $msg;

		$newFilter ['user_id'] = $my->id;
		$data->inbox = $model->countUnRead ( $newFilter );
		$data->pagination =  $model->getPagination ();
		echo $view->get ( 'matches', $data );
    }
}

?>

