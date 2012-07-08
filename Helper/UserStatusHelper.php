<?php

/**
 * Classe helper, responsavel por selecionar o status do usuario.
 * 
 * @author Lucas Simon Rodrigues Magalhaes <lucas.simon@lucassimon.net>
 * @version 1.0
*/
class UserStatusHelper extends AppHelper {
	public $helpers = array('Form','Html');

	/**
	 * Status do usuario pode ser Ativo/Bloqueado/Suspenso
	 * @var array
	 */
	private $__user_status = array(
		'Ativo' => 'A',
		'Bloqueado' => 'B',
		'Suspenso' => 'C'
	);

	public function select($fieldname, $label, $selected=null, $showEmpty=false) {
		

		foreach ($this->__user_status as $desc => $key) {
			$options[$key] = $desc;
		}

		
		$list = $this->Form->input($fieldname, array('options' => $options,'selected' => $selected,'empty' => $showEmpty));

		if (!empty($label)) {
			$list = '<div class="input">' . $this->Form->label($fieldname, $label) . $list . '</div>';
		}

		return $this->output($list);
	}

	public function translate($code) {
		

		foreach ($this->__user_status as $desc => $key) {
			$options[$key] = $desc;
		}

		return $options[$code];
	}
}

?>
