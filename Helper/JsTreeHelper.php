<?php
/**
 * Classe helper, responsavel por gerar arvore.
 * 
 * @author Lucas Simon Rodrigues Magalhaes <lucas.simon@lucassimon.net>
 * @version 1.0
*/
App::uses('AppHelper', 'View/Helper');

class JsTreeHelper extends AppHelper {
	/**
	 * [$helpers description]
	 * @var array
	 */
	public $helpers = array('Html');

	public function getJsTree(){
		return $this->Html->script(
			array(
				'/auth/js/jstree/_lib/jquery.cookie',
				'/app/webroot/js/jstree/_lib/jquery.hotkeys', 
				'/js/jstree/jquery.jstree'
			)
			
		); 

		
	}

	public function generate($div,$urls = array()){

		//debug($urls);
		//debug($this->Html->url($urls['actions']));
		$script='
		<script type="text/javascript">
		$(function () {
			$("'.$div.'")
				.jstree({ 
					"plugins" : ["themes", "json_data", "ui", "crrm",  "dnd", "search", "types", "contextmenu" ],
					"json_data" : { 
						"ajax" : {
							"url" : "'.$this->Html->url($urls['actions']).'",
							"data" : function (n) { 
								return { 
									"operation" : "get_children", 
									"id" : n.attr ? n.attr("id").replace("node_","") : -1
								}; 
							}
						}
					},
					"search" : {
						"ajax" : {
							"url" : "'.$this->Html->url($urls['actions']).'",
							"data" : function (str) {
								return { 
									"operation" : "search", 
									"search_str" : str 
								}; 
							}
						}
					},
					"contextmenu" : {
						"show_at_node" : true,
						"items" : {
							ccp: false,
						    "create" : {
							"label" : "Create",';
			if(isset($urls['create'])){
							$script.='"action" : function() {
								window.location = "'.$this->Html->url($urls['create']).'"
							},';
			}
							$script.='"seperator_after" : true,
							"seperator_before" : false,
							"icon" : false
						   },
							"rename":{
								"label":"Edit",';
								
			if(isset($urls['edit'])){
					$script.='"action": function(obj){window.location = "'.$this->Html->url($urls['edit']).'/"+obj.attr("id").replace("node_","")},';
			}
							$script.='}
						}
					},
					"types" : {
						"max_depth" : -2,
						"max_children" : -2,
						"valid_children" : [ "drive" ],
						"types" : {
							"default" : {
								"valid_children" : "none",
								
							},
							"folder" : {
								"valid_children" : [ "default", "folder" ],
								
							},
							"drive" : {
								"valid_children" : [ "default", "folder" ],
								"start_drag" : false,
								"move_node" : false,
								"delete_node" : false,
								"remove" : false,
								"rename":false,
								
							}
						}
					},
					"ui" : {
					},
					"core" : { 
					}
				})
				.bind("create.jstree", function (e, data) {
					$.post(
						"'.$this->Html->url($urls['actions']).'", 
						{ 
							"operation" : "create_node", 
							"id" : data.rslt.parent.attr("id").replace("node_",""), 
							"position" : data.rslt.position,
							"title" : data.rslt.name,
							"type" : data.rslt.obj.attr("rel")
						}, 
						function (r) {
							if(r.status) {
								$(data.rslt.obj).attr("id", "node_" + r.id);
							}
							else {
								$.jstree.rollback(data.rlbk);
							}
						}
					);
				})
				.bind("remove.jstree", function (e, data) {
					data.rslt.obj.each(function () {
						$.ajax({
							async : false,
							type: "POST",
							url: "'.$this->Html->url($urls['actions']).'",
							data : { 
								"operation" : "remove_node", 
								"id" : this.id.replace("node_","")
							}, 
							success : function (r) {
								if(!r.status) {
									data.inst.refresh();
								}
							}
						});
					});
				})
				.bind("rename.jstree", function (e, data) {
					$.post(
						"'.$this->Html->url($urls['actions']).'", 
						{ 
							"operation" : "rename_node", 
							"id" : data.rslt.obj.attr("id").replace("node_",""),
							"title" : data.rslt.new_name
						}, 
						function (r) {
							if(!r.status) {
								$.jstree.rollback(data.rlbk);
							}
						}
					);
				})
				.bind("move_node.jstree", function (e, data) {
					data.rslt.o.each(function (i) {
						$.ajax({
							async : false,
							type: "POST",
							url:  "'.$this->Html->url($urls['actions']).'",
							data : { 
								"operation" : "move_node", 
								"id" : $(this).attr("id").replace("node_",""), 
								"ref" : data.rslt.np.attr("id").replace("node_",""), 
								"position" : data.rslt.cp + i,
								"title" : data.rslt.name,
								"copy" : data.rslt.cy ? 1 : 0
							},
							success : function (r) {
								if(!r.status) {
									$.jstree.rollback(data.rlbk);
								}
								else {
									$(data.rslt.oc).attr("id", "node_" + r.id);
									if(data.rslt.cy && $(data.rslt.oc).children("UL").length) {
										data.inst.refresh(data.inst._get_parent(data.rslt.oc));
									}
								}
							}
						});
					});
				});
			
		});
		</script>';
	return $script;
	}



}