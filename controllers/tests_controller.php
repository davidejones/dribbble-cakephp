<?php
class TestsController extends AppController {

	var $name = 'Tests';
	var $uses = array('Dribbbleshot','Dribbbleplayer');

	function index()
	{
		# SHOTS
	
		# Find all shots
		# pr($this->Dribbbleshot->find('all'));
		
		# Find all shots with pagination, 3 at a time and page 2
		# pr($this->Dribbbleshot->find('all',array('conditions'=>array('per_page'=>3,'page'=>2))));
		
		# Find shot by shots id
		# pr($this->Dribbbleshot->findById(21603));
		
		# Find list of shots - debuts, everyone, popular
		# pr($this->Dribbbleshot->find('all',array('conditions'=>array('list'=>'everyone'))));
		
		# Find rebounds (shots in response to a shot) specified by id
		# pr($this->Dribbbleshot->find('all',array('conditions'=>array('id'=>21603,'reboundsonly'=>true))));
		
		# Find comments for a shot specified by id
		# pr($this->Dribbbleshot->find('all',array('conditions'=>array('id'=>21603,'commentsonly'=>true))));
		
		
		# PLAYERS
		
		# Find player details by id
		# pr($this->Dribbbleplayer->findById(1));
		# pr($this->Dribbbleplayer->findById("simplebits"));
		
		# Find shots by player id
		# pr($this->Dribbbleplayer->findShotsByPlayerId(1));
		# pr($this->Dribbbleplayer->findShotsByPlayerId("simplebits"));
		
		# Find shots by followers of an player id
		# pr($this->Dribbbleplayer->findFollowersShots(1));
		# pr($this->Dribbbleplayer->findFollowersShots("simplebits"));
		
		# Find shots liked by player id
		# pr($this->Dribbbleplayer->findLikedShots(1));
		# pr($this->Dribbbleplayer->findLikedShots("simplebits"));
		
		# Find the list of followers for a player id
		# pr($this->Dribbbleplayer->findPlayerFollowers(1));
		# pr($this->Dribbbleplayer->findPlayerFollowers("simplebits"));
		
		# Find the list of players followed by the id
		# pr($this->Dribbbleplayer->findPlayersFollowed(1));
		# pr($this->Dribbbleplayer->findPlayersFollowed("simplebits"));
		
		# Find the list of players drafted by the player id
		# pr($this->Dribbbleplayer->findPlayersDraftees(1));
		# pr($this->Dribbbleplayer->findPlayersDraftees("simplebits"));
		
		$this->autoRender = false;
		exit();
	}

}
?>