<?php

/**
 * Classe component, responsavel retirar e inserir mascaras.
 *
 * 
 * @author Lucas Simon Rodrigues Magalhaes <lucas.simon@lucassimon.net>
 * @version 1.0
*/
class MaskComponent extends Component {


	/**
	 * O método initialize é chamado antes do método beforeFilter do controller.
	 * The initialize method is called before the controller’s beforeFilter method.
	 * 
	 * @param  Controller $controller Passa por referencia o controllador que chama o componente
	 * @return null
	*/
	public function initialize(Controller $controller) {

	}

	/**
	 * [removeCPF description]
	 * @param  [type] $cpfcnpj [description]
	 * @return [type]      [description]
	 */
	function removeCPFOrCNPJ($cpfcnpj) {

		$cpfcnpj = str_replace(".", "", $cpfcnpj);
		$cpfcnpj = str_replace("/", "", $cpfcnpj);
		$cpfcnpj = str_replace("-", "", $cpfcnpj);
		return $cpfcnpj;
	}

	/**
	 * [removePhone description]
	 * @param  [type] $phone [description]
	 * @return [type]        [description]
	 */
	function removePhone($phone) {
		$phone = str_replace("(", "", $phone);
		$phone = str_replace(")", "", $phone);
		$phone = str_replace(" ", "", $phone);
		$phone = str_replace("-", "", $phone);
		return $phone;
	}

	/**
	 * [removeCEP description]
	 * @param  [type] $cep [description]
	 * @return [type]      [description]
	 */
	function removeCEP($cep) {
		$cep = str_replace("-", "", $cep);
	}

	/**
	 * [removeMoney description]
	 * @param  [type] $money [description]
	 * 
	 * @return [type]        [description]
	 */
	function removeMoney($money) {
		$money = str_replace('R$', '', $money);
		$money = str_replace(' ', '', $money);
		$money = str_replace('.', '', $money);
		$money = str_replace(',', '.', $money);
		return $money;
	}

	/**
	 * [removePlate description]
	 * @param  [type] $plate [description]
	 * @return [type]        [description]
	 */
	function removePlate($plate) {
		$plate = str_replace('-', '', $plate);,
		$plate = strtoupper($plate);
		return $plate;
	}

	/**
	 * [addCEP description]
	 * @param [type] $cep [description]
	 */
	function addCEP($cep) {
		$cep = preg_replace("/^(\d{5})(\d)/","$1-$2", $cep);
		return $cep;
	}

	/**
	 * [addPlate description]
	 * @param [type] $plate [description]
	 */
	function addPlate($plate){
		$plate = preg_replace("/^([A-Za-z]{3})(\d)/","$1-$2", $plate);
		return $plate;
	}


	/**
	 * [addCNPJ description]
	 * @param [type] $cnpj [description]
	 */
	function addCNPJ($cnpj) {
		$cnpj = preg_replace("/^(\d{2})(\d)/", "$1.$2", $cnpj);
		$cnpj = preg_replace("/^(\d{2})\.(\d{3})(\d)/","$1.$2.$3", $cnpj);
		$cnpj = preg_replace("/\.(\d{3})(\d)/",".$1/$2", $cnpj);
		$cnpj = preg_replace("/(\d{4})(\d)/","$1-$2", $cnpj);
		return $cnpj;
	}

	/**
	 * [addCPF description]
	 * @param [type] $cpf [description]
	 */
	function addCPF($cpf) {
		$cpf = preg_replace("/^(\d{3})(\d)/", "$1.$2", $cpf);
		$cpf = preg_replace("/^(\d{3})\.(\d{3})(\d)/","$1.$2.$3", $cpf);
		$cpf = preg_replace("/\.(\d{3})(\d)/",".$1-$2", $cpf);
		return $cpf;
	}

	/**
	 * [addMoney description]
	 * @param [type] $money [description]
	 */
	function addMoney($money,$currency='BRL',$options=array()) {

		if (isset($options) && !(empty($options))) {
			$option =	array(
				'wholeSymbol'      => 'R$',
				'wholePosition'    => 'before',
				'fractionSymbol'   => '',
				'fractionPosition' => 'after',
				'zero'             => 0,
				'places'           => 2,
				'thousands'        => '.',
				'decimals'         => ',',
				'negative'         => '()',
				'escape'           => true
			);	
		}
		
		App::uses('CakeNumber', 'Utility');
		return CakeNumber::currency($money, $currency, $options);   
	}

    /**
     * [formatDateTime description]
     * @param  [type] $str [description]
     * @return [type]      [description]
     */
	function formatDateTime($str) {
		return date("d/m/Y H:i:s", strtotime( $str ));
	}
	
	/**
	 * [formatDate description]
	 * @param  [type] $str [description]
	 * @return [type]      [description]
	 */
	function formatDate($str) {
		return date("d/m/Y", strtotime( $str ));
	}
}
?>
