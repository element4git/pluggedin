<?php
	class mongoDBcontrol{
		private $db;
		private $m;
		public function __construct($dbPass = null){
			$this->m =  new MongoClient(mongoServer);
			
			$this->collection = $this->m->selectCollection('pluggedin','bandList');
		}
		public function find($passCollection, $query = array()){
			
			return $this->collection->find($query);
		}
		public function findOne($passCollection, $query = array()){
			$collection = $this->db->$passCollection;
			return $this->collection->findOne($query);
		}
		public function insert($passCollection, $passInsertArray){
			$this->collection->insert($passInsertArray);		
		}
		public function remove($passCollection, $passInsertArray){
			$collection = $this->db->$passCollection;
			
			if(!isset($passInsertArray)){
				echo json_encode(array('error'=>'no delete paramaters sent'));
				die;
			}
			
			$collection->remove($passInsertArray);
			return true;
		}
		public function update($passCollection, $passIdObj, $passUpdateParams){
			$collection = $this->db->$passCollection;
			
			if(!isset($passIdObj) || !isset($passUpdateParams)){
				echo json_encode(array('error'=>'no update paramaters sent'));
				die;
			}
			$collection->update($passIdObj,$passUpdateParams);
			return true;
		}
		public function error($reset = false){
			if($reset)
				$this->db->resetError();
			return $this->db->lastError();
		}
	}
	
	
	/*?>$md = new mongoDBcontrol('pluggedIn');
	
	if(isset($_REQUEST['updatePhoto'])){
		
		$photoUpdate = json_decode($_REQUEST['updatePhoto']);
		$exists = $md->findOne('moderated',array('photo.id'=>$photoUpdate->picid));
		if($exists)
			$md->update('moderated',array('photo.id'=>$photoUpdate->picid),array('$set'=>array('photo.display'=>$photoUpdate->setValue)));
		else
			$md->insert('moderated',array('photo'=>array('id'=>$photoUpdate->picid, 'display'=>$photoUpdate->setValue)));
		
		echo json_encode(array('status'=>'ok'));
	}else{		
		$results = $md->find('moderated');
		$results = iterator_to_array($results);
		echo json_encode($results);
	}<?php */
?>