<?php
class TreeComponent extends Component {
	
	public $model=null;
	public $params = array();
	public $displayField = null;
	public $treeName = 'tree';

	var $components = array('RequestHandler');
		
	function initialize(&$controller, $settings = array()) {
		$this->model = ClassRegistry::init($controller->uses[0]);
		$this->params = $controller->params;
		$this->displayField = $this->model->displayField;
		if(isset($settings['treeName'])){
			$this->treeName = $settings['treeName'];
		}
		if(isset($settings['displayField'])){
			$this->displayField = $settings['displayField'];
		}
		$this->_set($settings);
	} 
	
    function startup(&$controller) {
		if($controller->request->is('ajax')){
			$controller->autoRender=false;
			//print_r($this->params);die();
			if(isset($this->params['form']["operation"])){
				$data = $this->params['form'];
				$method = $this->params['form']["operation"];
			} else {
				$data = $this->params['url'];
				$method = $this->params['url']["operation"];
			}
			header("HTTP/1.0 200 OK");
			header('Content-type: text/json; charset=utf-8');
			header("Cache-Control: no-cache, must-revalidate");
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Pragma: no-cache");
			echo $this->{$method}($data);


			die();
		}
	}
	
	function _get_children($id, $recursive = false) {
		$children = array();
		if($recursive) {
			//$node = $this->_get_node($id);
			//$this->db->query("SELECT `".implode("` , `", $this->fields)."` FROM `".$this->table."` WHERE `".$this->fields["left"]."` >= ".(int) $node[$this->fields["left"]]." AND `".$this->fields["right"]."` <= ".(int) $node[$this->fields["right"]]." ORDER BY `".$this->fields["left"]."` ASC");
			$nodes = $this->model->children($id);
		}
		else {
			//$this->db->query("SELECT `".implode("` , `", $this->fields)."` FROM `".$this->table."` WHERE `".$this->fields["parent_id"]."` = ".(int) $id." ORDER BY `".$this->fields["position"]."` ASC");
			$nodes = $this->model->children($id,true);
		}
		// foreach($nodes as $node){ 
		// 		$children[$node[$this->model->name]["id"]] = $node[$this->model->name][$this->displayField];
		// 	
		// 	}
		return $nodes;
	}
	
	function get_children($data) {
		if($data["id"]==-1){
			//$this->model->recover();
			$result[] = array(
				"attr" => array("id" => "node_0", "rel" => "drive"),
				"data" => $this->treeName,
				"state" => "closed"
			);
			return json_encode($result);
		}
		$tmp = $this->_get_children((int)$data["id"]);
		// if((int)$data["id"] === 1 && count($tmp) === 0) {
		// 			$tmp = $this->_get_children((int)$data["id"]);
		// 		}
		// 		$result = array();
		// 		if((int)$data["id"] === 0) return json_encode($result);
		foreach($tmp as $k => $v) {
			if(((int)$v[$this->model->name]['rght'] - (int)$v[$this->model->name]['lft'] > 1)){
				$rel = 'folder';
			} else{
				$rel='default';
			}
			$result[] = array(
				"attr" => array("id" => "node_".$v[$this->model->name]['id'], "rel" => $rel),
				"data" => $v[$this->model->name][$this->displayField],
				"state" => ((int)$v[$this->model->name]['rght'] - (int)$v[$this->model->name]['lft'] > 1) ? "closed" : ""
			);
		}
		return json_encode($result);
	}
	
	function _move($id, $ref_id, $position = 0, $is_copy = false) {
		return true;
	}
	
	function move_node($data) { 
		//$id = $this->_move((int)$data["id"], (int)$data["ref"], (int)$data["position"], (int)$data["copy"]);
		$return = true;
		$nodeId = (int)$data["id"];
		$parentId = (int)$data["ref"];
		$newPosition = (int)$data["position"];
		$copy = (int)$data["copy"];
		$node = $this->model->find('first',array('conditions'=>array('id'=>$nodeId),'recursive'=>-1));
		if($node[$this->model->name]['parent_id']==$parentId){ //reorder
			$children = $this->model->children($parentId,true);
			$position = 1;
			foreach($children as $child){
				if($child[$this->model->name]['id']==$nodeId){
					break;
				}
				$position++;
			}
			$delta = $newPosition-$position;
			if ($delta > 0) {
		       	$return = $this->model->movedown($nodeId, abs($delta));
		    } elseif ($delta < 0) {
				$delta++;
		       	$return = $this->model->moveup($nodeId, abs($delta));
		    }
		} else { //reparent
			$nodeId = (int)$data["id"];
			$parentId = (int)$data["ref"];
			$newPosition = (int)$data["position"];

			// save the Menu node with the new parent id
			// this will move the Menu node to the bottom of the parent list

			$this->model->id = $nodeId;
			$return = $this->model->saveField('parent_id', $parentId);

		}
		if($return)
			return "{ \"status\" : 1, \"id\" : ".$nodeId." }";
		return "{ \"status\" : 0, \"id\" : ".$data['id']." }";
	}
	
	function create_node($data){
		$parentId = ($data['id']==0)?null:$data['id'];
		$node[$this->model->name]['parent_id']=$parentId;
		$node[$this->model->name][$this->displayField]=$data['title'];
		$this->model->create();
		if($this->model->save($node)){
			return "{ \"status\" : 1, \"id\" : ".$data['id']." }";
		}
		return "{ \"status\" : 0, \"id\" : ".$data['id']." }";
	}
	function rename_node($data){
		$this->model->recursive=-1;
		$node = $this->model->read(null,$data['id']);
		$node[$this->model->name][$this->displayField]=$data['title'];
		if($this->model->save($node)){
			return "{ \"status\" : 1, \"id\" : ".$data['id']." }";
		}
		return "{ \"status\" : 0, \"id\" : ".$data['id']." }";
	}


	public function remove_node($data){
		
		if($this->model->delete($data['id'])){
			return "{ \"status\" : 1, \"id\" : ".$data['id']." }";
		}
		return "{ \"status\" : 0, \"id\" : ".$data['id']." }";
	}
}
?>