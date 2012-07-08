<?php

/**
 * Classe component, responsavel por manipular datas no controller.
 * Class Component to handle dates in the controller. 
 *
 * If you live in other country, please change the default_timezone_set() of method now()
 * 
 * @author Lucas Simon Rodrigues Magalhaes <lucas.simon@lucassimon.net>
 * @version 1.0
*/
class DateComponent extends Component {

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
	 * Metodo responsavel por transformar a data em uma data aceita pela maioria dos banco de dados
	 * Mysql e postgres trabalham com campos do tipo data no formato aaaa-mm-dd. Este metodo coloca
	 * a data passada por parametro neste formato.
	 * 
	 * @param  string  $date     data passada por parametro
	 * @param  boolean $midnight se True sera no fim do dia, senao sera inicio da madrugada
	 * @return [type]            [description]
	*/
	function toDatabaseFormat($date, $hours=0,$minutes=0,$seconds=0, $midnight = false)
	{
		// verifica se a data for vazia
		if (empty($date)) {
			return null;
		}
		// guarda nas variaveis dia, mes e ano o que foi divido por '/ ou . -' na variavel $date
		list($day,$month,$year) = split('[/.-]', $date);

		// se a opcão midnight for setada como true
		if($midnight) {
			// as horas, minutos, segundos passam a ser 23:59:59
			$hours = 23;
			$minutes = 59;
			$seconds = 59;
		}

		// valida se o campo ano é maior do que 4 caracteres
		// se verdadeiro guarda na variavel somente os 4 primeiros
		// caracteres

		if (strlen($year) > 4) {
			$year = substr($year, 0, 4);
		}
		// retorna a data no formato para ser gravado no banco de dados
		return date("Y-m-d H:i:s", mktime($hours, $minutes, $seconds, $month, $day, $year));
	}

	/**
	 * Transforma uma variavel do tipo datetime no formato paradrao de sql
	 * @param  [string] $datetime guarda infromacoes do tipo datetime
	 * @return Function Retorna a data no padrão sql de acordo com a função toDatabaseFormat()
	*/
	function dateTimeToDatabaseFormat($datetime) {

		// retorna a funcao generica toDatabaseFormat passando como argumentos a data horas e minutos
		return $datetime->format('Y-m-d H:i:s');
	}

	/**
	 * Retorna a data e hora atual do sistema de acordo com o padrão sql
	 * @return [type] [description]
	 */
	function nowToDatabaseFormat() {

		return date("Y-m-d H:i:s");
	}

	/**
	 * Retorna a data atual
	 * @param  boolean $zeroTime Se true zera a variavel time senao gera a hora atual do sistema
	 * @return [type]            [description]
	 */
	function now($zeroTime = false) {
		// Guarda o padrao de 00:00:00 para ser usado no comando date
		$time = 'H:i:s';

		// verifica se zeroTime for true
		if($zeroTime) {
			// zera o formato da hora para ser usado no comando date
			$time = '00:00:00';
		}

		// seta o timezone para "America/Sao_Paulo"
		date_default_timezone_set("America/Sao_Paulo");
		// retorna a data
		return date("Y-m-d " . $time);
	}

	/**
	 * Retorna a data do dia anterior ao atual
	 * @return string Dia anterior
	 */
	function yesterday() {
		// Resgata a data atual
		$today = $this->now();
		// Returns a timestamp 
		return strtotime($today . '-1 DAY');
	}

	/**
	 * Retorna o primeiro dia da semana
	 * @return string Primeiro dia da semana
	 */
	function getFirstDayOfWeek() {
		// ISO-8601 numeric representation of the day of the week (added in PHP 5.1.0)	1 (for Monday) through 7 (for Sunday)
		$todayWeek = date('N');
		// resgata da data atual
		$today = $this->now();
		// Returns a timestamp 
		return strtotime($today . ' -' . $todayWeek . ' DAY');
	}

	/**
	 * Retorna o ultimo dia da semana
	 * @return timestamp Retorna o ultimo dia da semana
	 */
	function getLastDayOfWeek() {
		// ISO-8601 numeric representation of the day of the week (added in PHP 5.1.0)	1 (for Monday) through 7 (for Sunday)
		$todayWeek = date('N');
		// resgata da data atual
		$today = $this->now();
		// Returns a timestamp 
		return strtotime($today . ' +' . (7-$todayWeek) . ' DAY');

	}

	/**
	 * Retorna o primeiro dia do mes atual
	 * @return timestamp Retorna o primeiro dia do mes atual
	 */
	function getFirstDayOfMonth() {
		// resgata o ano corrente
		$year = date('Y');
		// resgata o mes corrente
		$month = date('n');
		// Returns a timestamp 
		return strtotime($year . '-' . $month . '-1');
	}


	/**
	 * Retorna o ultima dia do mes
	 * @return timestamp Retorna o ultima dia do mes
	 */
	function getLastDayofMonth() {
		// A full numeric representation of a year, 4 digits
		$year = date('Y');
		// Numeric representation of a month, without leading zeros	1 through 12
		$month = date('n');
		// Number of days in the given month	28 through 31
		$day = date('t');
		// Returns a timestamp 
		return strtotime($year . '-' . $month . '-' . $day);
	}

	/**
	 * Retorna o primeiro dia do mes anterior
	 * @return timestamp Retorna o primeiro dia do mes anterior
	 */
	function getFirstDayOfPreviousMonth() {
		// A full numeric representation of a year, 4 digits
		$year = date('Y');
		// Numeric representation of a month, without leading zeros	1 through 12
		$month = date('n');
		// Returns a timestamp 
		return strtotime($year . '-' . $month . '-1 -1 MONTH');
	}

	/**
	 * Retorna o ultimo dia do mes anterior
	 * @return timestamp Retorna o ultimo dia do mes anterior
	 */
	function getLastDayOfPreviousMonth() {
		// A full numeric representation of a year, 4 digits
		$year = date('Y');
		// Numeric representation of a month, without leading zeros	1 through 12
		$month = date('n');
		// Number of days in the given month	28 through 31
		$day = date('t');
		// Returns a timestamp 
		return strtotime($year . '-' . $month . '-' . $day . ' -1 MONTH');
	}

	/**
	 * Retorna a data atraves de uma string strtotime
	 * @param  String $strtotime Valore correspondente a um campo data do tito strtotime
	 * @return Date            Retorna a data no formato legivel aos humanos
	 */
	function strTotimeToDate($strtotime) {
		return date("Y-m-d H:i:s", $strtotime);
	}

	/**
	 * Faz a comparacao entre 02 datas e retorna a diferenca em minutos delas
	 * @param  Date $date1 Primeira data a ser comparada
	 * @param  Date $date2 Segunda data a ser comparada
	 * @return String        diferenca em minutos entre 02 datas
	 */
	function minutesBetweenDates($date1, $date2){
		// transforma as datas em strtotime, faz a subtracao delas
		// e divide por 60 para achar os minutos
		$temp = (strtotime($date1) - strtotime($date2))/60;
		// retorna o valor achado pelo calculo acima
		return $temp;
	}

}


?>
