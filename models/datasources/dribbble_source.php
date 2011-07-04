<?php
App::import('Core', 'HttpSocket');
class DribbbleSource extends DataSource {

	public $description = "Dribbble API Data Source";
	public $version = '0.1';
	
	protected $_schema = array(
        'dribbbleshots' => array(
			'id' => array('type' => 'Integer'),
			'title' => array('type' => 'String'),
			'short_url' => array('type' => 'String'),
			'image_url' => array('type' => 'String'),
			'image_teaser_url' => array('type' => 'String'),
			'width' => array('type' => 'Integer'),
			'height' => array('type' => 'Integer'),
			'views_count' => array('type' => 'Integer'),
			'likes_count' => array('type' => 'Integer'),
			'comments_count' => array('type' => 'Integer'),
			'rebounds_count' => array('type' => 'Integer'),
			'rebound_source_id' => array('type' => 'Integer'),
			'created_at' => array('type' => 'Integer')
		),
		'dribbbleplayers' => array(
			'id' => array('type' => 'integer'),
			'name' => array('type' => 'String'),
			'username' => array('type' => 'String'),
			'url' => array('type' => 'String'),
			'avatar_url' => array('type' => 'String'),
			'location' => array('type' => 'String'),
			'twitter_screen_name' => array('type' => 'integer'),
			'drafted_by_player_id' => array('type' => 'integer'),
			'shots_count' => array('type' => 'integer'),
			'draftees_count' => array('type' => 'integer'),
			'followers_count' => array('type' => 'integer'),
			'following_count' => array('type' => 'String'),
			'comments_count' => array('type' => 'String'),
			'comments_received_count' => array('type' => 'integer'),
			'likes_count' => array('type' => 'integer'),
			'likes_received_count' => array('type' => 'String'),
			'rebounds_count' => array('type' => 'String'),
			'rebounds_received_count' => array('type' => 'String'),
			'created_at' => array('type' => 'String')
		)
    );

	function __construct($config) {
		$this->connection = new HttpSocket('http://api.dribbble.com');
		parent::__construct($config);
	}
	
	public function listSources() {
		return array('dribbbleshots','dribbbleplayers');
	}

	public function read(&$model, $queryData = array()) 
	{
		$results = array();	
		$data = array();
		
		if (isset($queryData['conditions']['page'])) {
			$data['page'] = $queryData['conditions']['page'];
		}	
		if (isset($queryData['conditions']['per_page'])) {
			$data['per_page'] = $queryData['conditions']['per_page'];
		}
		
		$u = http_build_query($data);
		if(strlen($u) > 0) {
			$pag = "?".$u;
		} else {
			$pag = "";
		}
				
		switch($model->useTable)
		{
			case "dribbbleshots":
				
				//default
				$url = "/shots/"; 
				
				
				//for find by id
				if(isset($queryData['conditions']['id'])) {
					$url = "/shots/".$queryData['conditions']['id']; 
					
					//for rebounds
					if(isset($queryData['conditions']['reboundsonly']))
					{
						if($queryData['conditions']['reboundsonly'])
						{
							$url = "/shots/".$queryData['conditions']['id']."/rebounds"; 
						}
					}
					
					//for comments
					if(isset($queryData['conditions']['commentsonly']))
					{
						if($queryData['conditions']['commentsonly'])
						{
							$url = "/shots/".$queryData['conditions']['id']."/comments"; 
						}
					}
				}
				
				//for list
				if(isset($queryData['conditions']['list'])) {
					if(in_array($queryData['conditions']['list'],array('everyone','debuts','popular')))
					{
						$url = "/shots/".$queryData['conditions']['list'];
					}
				}
				
				$url .= $pag;
				$response = json_decode($this->connection->get($url), true);
				$results[$model->useTable] = array();
				
				if(isset($response['shots'])) 
				{
					foreach($response['shots'] as $shots)
					{
						$results[$model->useTable][] = $shots;
					}	
				} else {
					$results[$model->useTable] = $response;
				}
								
				break;
			case "dribbbleplayers":
			
				//default
				$url = "/players/";
			
				//for find by id
				if(isset($queryData['conditions']['id'])) {
					$url = "/players/".$queryData['conditions']['id']; 
				}
				
				if(isset($queryData['conditions']['PlayerId'])) {
					$url = "/players/".$queryData['conditions']['PlayerId']."/shots"; 
				}
				
				if(isset($queryData['conditions']['FollowPlayerId'])) {
					$url = "/players/".$queryData['conditions']['FollowPlayerId']."/shots/following"; 
				}
				
				if(isset($queryData['conditions']['LikesPlayerId'])) {
					$url = "/players/".$queryData['conditions']['LikesPlayerId']."/shots/likes"; 
				}
				
				if(isset($queryData['conditions']['FollowersPlayerId'])) {
					$url = "/players/".$queryData['conditions']['FollowersPlayerId']."/shots/followers"; 
				}
				
				if(isset($queryData['conditions']['PlayersFollowedPlayerId'])) {
					$url = "/players/".$queryData['conditions']['PlayersFollowedPlayerId']."/following"; 
				}
				
				if(isset($queryData['conditions']['PlayersDrafteesPlayerId'])) {
					$url = "/players/".$queryData['conditions']['PlayersDrafteesPlayerId']."/draftees"; 
				}
				
				$url .= $pag;
				$response = json_decode($this->connection->get($url), true);
				$results[$model->useTable] = array();
				
				//find by id
				$results[$model->useTable] = $response;
				
				break;
		}
		
		return $results;
	}
	
	public function query($method, $args, &$model) 
	{
		if (strpos(strtolower($method), 'findby') === 0) {
			$field = Inflector::underscore(preg_replace('/^findBy/i', '', $method));
			if ($field == 'id') {
				$field = $model->primaryKey;
			}
			return $model->find('all', array('limit'=>1,'conditions' => array($field => current($args))));
			#return $model->find('first', array('conditions' => array($field => current($args))));
		} else {
			switch($method)
			{
				case "findShotsByPlayerId":
					return $model->find('all', array('limit'=>1,'conditions' => array('PlayerId' => current($args))));
					break;
				case "findFollowersShots":
					return $model->find('all', array('limit'=>1,'conditions' => array('FollowPlayerId' => current($args))));
					break;
				case "findLikedShots":
					return $model->find('all', array('limit'=>1,'conditions' => array('LikesPlayerId' => current($args))));
					break;
				case "findPlayerFollowers":
					return $model->find('all', array('limit'=>1,'conditions' => array('FollowersPlayerId' => current($args))));
					break;
				case "findPlayersFollowed":
					return $model->find('all', array('limit'=>1,'conditions' => array('PlayersFollowedPlayerId' => current($args))));
					break;
				case "findPlayersDraftees":
					return $model->find('all', array('limit'=>1,'conditions' => array('PlayersDrafteesPlayerId' => current($args))));
					break;
			}
		}
	}
		
	public function describe($model) {
		#return $model->_schema;
		return $this->_schema['dribbbleshots'];
	}
}
?>