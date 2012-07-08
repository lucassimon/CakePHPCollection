<?php
/**
 * Classe helper, responsavel por utilizar mascaras na view.
 * 
 * @author Lucas Simon Rodrigues Magalhaes <lucas.simon@lucassimon.net>
 * @version 1.0
*/
class MaskHelper extends AppHelper {

	/**
	 * Adiciona mascara para o cep
	 * @param String $cep cep informado somente com numeros
	 */
	function addCEP($cep) {
		$cep = preg_replace("/^(\d{5})(\d)/","$1-$2", $cep);
		return $cep;
	}

	/**
	 * Adiciona mascara para o telefone
	 * @param String $phone telefone informado
	 */
	function addPhone($phone) {
		$phone = preg_replace("/^(\d\d)(\d)/", "($1) $2", $phone);
		$phone = preg_replace("/(\d{4})(\d)/", "$1-$2", $phone);
		return $phone;
	}

	/**
	 * Adiciona mascara para o CNPJ
	 * @param String $cnpj cnpj informado
	 */
	function addCNPJ($cnpj)
	{
		$cnpj = preg_replace("/^(\d{2})(\d)/", "$1.$2", $cnpj);
		$cnpj = preg_replace("/^(\d{2})\.(\d{3})(\d)/","$1.$2.$3", $cnpj);
		$cnpj = preg_replace("/\.(\d{3})(\d)/",".$1/$2", $cnpj);
		$cnpj = preg_replace("/(\d{4})(\d)/","$1-$2", $cnpj);
		return $cnpj;
	}

	/**
	 * Adiciona mascara para o cpf
	 * @param String $cpf cpf informado
	 */
	function addCPF($cpf)
	{
		$cpf = preg_replace("/^(\d{3})(\d)/", "$1.$2", $cpf);
		$cpf = preg_replace("/^(\d{3})\.(\d{3})(\d)/","$1.$2.$3", $cpf);
		$cpf = preg_replace("/\.(\d{3})(\d)/",".$1-$2", $cpf);
		return $cpf;
	}

	/**
	 * Adiciona mascara a placa do veiculo
	 * @param String $plate placa informada
	 */
	function addPlate($plate)
	{
		$plate = preg_replace("/^([A-Za-z]{3})(\d)/","$1-$2", $plate);
		return $plate;
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
	 * Insere uma mascara no padrao d/m/y H:M:S
	 * @param  String $str data informada
	 * @return Date      retorna a data informada
	 */
	function formatDateTime($str) {
		return date("d/m/Y H:i:s", strtotime( $str ));
	}

	/**
	 * Insere uma mascara no padrao d/m/y
	 * @param  String $str data informada
	 * @return Date/null      retorna a data informada ou vazio quando nao existir
	 * a data no paremetro
	 */
	function formatDate($str) {
		if (!empty($str)) {
			return date("d/m/Y", strtotime( $str ));
		}
		else {
			return "";
		}
	}

	/**
	 * Insere uma mascara do mes por extenso
	 * @param  Integer $month_number numero do mes
	 * @return String 	Retorna o mes por extenso
	 */
	function getStringMonth($month_number) {
		if ($month_number == 1) {
			return 'janeiro';
		} else if ($month_number == 2) {
			return 'fevereiro';
		} else if ($month_number == 3) {
			return 'março';
		} else if ($month_number == 4) {
			return 'abril';
		} else if ($month_number == 5) {
			return 'maio';
		} else if ($month_number == 6) {
			return 'junho';
		} else if ($month_number == 7) {
			return 'julho';
		} else if ($month_number == 8) {
			return 'agosto';
		} else if ($month_number == 9) {
			return 'setembro';
		} else if ($month_number == 10) {
			return 'outubro';
		} else if ($month_number == 11) {
			return 'novembro';
		}
		return 'dezembro';
	}

	/**
	 * [addPad description]
	 * @param string  $str=''                [description]
	 * @param integer $length=6              [description]
	 * @param string  $pad_str='0'           [description]
	 * @param [type]  $pad_type=STR_PAD_LEFT [description]
	 */
	function addPad($str='', $length=6, $pad_str='0', $pad_type=STR_PAD_LEFT) {
		return str_pad($str, $length, $pad_str, $pad_type);
	}

	/**
	 * [addHashSpanBorder description]
	 * @param [type] $hash [description]
	 */
	function addHashSpanBorder($hash) {
		return '<span style="border:1px solid; padding:2px">' . $hash . '</span>';
	}
}

?>