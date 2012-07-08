<?php
/**
 * Classe helper, responsavel por manipular campos de filtros e pesquisas.
 * 
 * @author Lucas Simon Rodrigues Magalhaes <lucas.simon@lucassimon.net>
 * @version 1.0
*/
class FilterHelper extends AppHelper {

	/**
	 * [$helpers description]
	 * @var array
	 */
	public $helpers = array('Html','Form');

	/**
	 * [$model description]
	 * @var [type]
	 */
	public $model = null;

	/**
	 * [$rows description]
	 * @var array
	 */
	public $rows = array();

	/**
	 * [$options description]
	 * @var [type]
	 */
	public $options = null;

	/**
	 * [$output description]
	 * @var string
	 */
	public $output = '';

	/**
	 * [$dateTime description]
	 * @var array
	 */
	public $dateTime = array();

	/**
	 * [$filter description]
	 * @var array
	 */
	public $filter = array();

	/**
	 * [$numberOfColumns description]
	 * @var integer
	 */
	public $numberOfColumns = 1;

	/**
	 * [$collapsible description]
	 * @var boolean
	 */
	public $collapsible = true;

	/**
	 * [create description]
	 * @param  [type]  $model           [description]
	 * @param  array   $options         [description]
	 * @param  integer $numberOfColumns [description]
	 * @param  boolean $collapsible     [description]
	 * @return [type]                   [description]
	 */
	public function create($model, $options = array(), $numberOfColumns = 1, $collapsible = true) {
		$this->model = $model;
		$this->options = $options;
		$this->numberOfColumns = $numberOfColumns;
		$this->collapsible = $collapsible;
		$this->output = $this->Form->create($this->model, $this->options);
	}

	/**
	 * [input description]
	 * @param  [type] $fieldName  [description]
	 * @param  [type] $label      [description]
	 * @param  [type] $attributes [description]
	 * @return [type]             [description]
	 */
	public function input($fieldName, $label, $attributes) {

		$this->rows[] = array($label, $this->Html->tag('input', null, am(array('name' => "data[$this->model][$fieldName]"), $attributes)));
		$this->filter['url'][$fieldName] = $attributes['value'];
	}

	/**
	 * [select description]
	 * @param  [type] $fieldName  [description]
	 * @param  [type] $label      [description]
	 * @param  [type] $options    [description]
	 * @param  [type] $selected   [description]
	 * @param  [type] $attributes [description]
	 * @param  [type] $showEmpty  [description]
	 * @return [type]             [description]
	 */
	public function select($fieldName, $label, $options, $selected, $attributes, $showEmpty) {

		$this->rows[] = array($label, $this->Form->input($fieldName, array('options' => $options,'selected' => $selected,'empty' => $showEmpty)));
		
		$this->filter['url'][$fieldName] =  $selected;
	}

	/**
	 * [dateRange description]
	 * @param  [type]  $startDateFieldName [description]
	 * @param  [type]  $endDateFieldName   [description]
	 * @param  [type]  $label              [description]
	 * @param  string  $startDateValue=''  [description]
	 * @param  string  $endDateValue=''    [description]
	 * @param  boolean $readOnly=true      [description]
	 * @param  boolean $showToday=true     [description]
	 * @return [type]                      [description]
	 */
	function dateRange($startDateFieldName, $endDateFieldName, $label, $startDateValue='', $endDateValue='', $readOnly=true, $showToday=true) {

		$startDate = $startDateValue;
		$endDate = $endDateValue;
		if ($showToday && empty($startDate) && empty($endDate)) {
			$startDate = date('d/m/Y');
			$endDate =  date('d/m/Y', strtotime('now +1 DAY'));
		}

		$this->rows[] = array($label, 
		$this->Html->tag('input', null, array_merge(array('id' => $this->model . ucfirst($startDateFieldName), 'name' => "data[$this->model][$startDateFieldName]", 'style' => 'width:100px', 'readonly' => $readOnly, 'value' => $startDate))) . ' até ' .
		$this->Html->tag('input', null, array_merge(array('id' => $this->model . ucfirst($endDateFieldName), 'name' => "data[$this->model][$endDateFieldName]", 'style' => 'width:100px', 'readonly' => $readOnly, 'value' => $endDate))) );
		$this->dateTime[] = $this->model . ucfirst($startDateFieldName);
		$this->dateTime[] = $this->model . ucfirst($endDateFieldName);
		$this->filter['url'][$startDateFieldName] = str_replace('/', '-', $startDateValue);
		$this->filter['url'][$endDateFieldName] = str_replace('/', '-', $endDateValue);
	}

	/**
	 * [object description]
	 * @param  [type] $label     [description]
	 * @param  [type] $object    [description]
	 * @param  [type] $fieldName [description]
	 * @param  [type] $value     [description]
	 * @return [type]            [description]
	 */
	public function object($label, $object, $fieldName = null, $value = null) {
		$this->rows[] = array($label, $object);
		if ($fieldName && $value)
		{
			$this->filter['url'][$fieldName] = $value;
		}
	}

	public function render($submitLabel='Filtrar', $labelColumnWidth="150px") {
		$this->output .= '<div id="filter">';

		if ($this->collapsible) {
			$this->output .= '<div><h2><a href="#"></a></h2><div>';
		}
		$this->output .= '<table>';
		$rowCount = 0;
		while ($rowCount < count($this->rows)) {
			$this->output .= '<tr>';
			for($columnCount = 0; $columnCount < $this->numberOfColumns && $rowCount < count($this->rows); $columnCount++) {
				$row = $this->rows[$rowCount];
				$this->output .= "<td width='{$labelColumnWidth}'>" . $row[0] . '</td><td>' . $row[1] . '</td>';
				$rowCount++;
			}
			$this->output .= '</tr>';
		}

		$colspan = $this->numberOfColumns * 2;
		$this->output .= "<tr><td style='text-align:center' colspan={$colspan}>{$this->Form->submit($submitLabel, array('div' => false))}</td></tr>";
		$this->output .= '</table></div>';

		if ($this->collapsible) {
			$this->output .= '</div></div>';
		}

		$this->output .= $this->Form->end();

		$this->output .= '<script>$(function () {';

		foreach ($this->dateTime as $dt) {
			$this->output .= "makeDatePicker('#$dt');";
		}

		if ($this->collapsible) {
			$this->output .= '$("#filter").accordion({header: "h2", collapsible: true});';
			$this->output .= '$("#filter div div").height("");';
		}

		$this->output .= '});</script>';

		return $this->output;
	}

	/**
	 * [getFilter description]
	 * @return [type] [description]
	 */
	public function getFilter() {
		return $this->filter;
	}

	/**
	 * [showServiceOrderDefaultFilter description]
	 * @param  [type] $model [description]
	 * @param  [type] $link  [description]
	 * @return [type]        [description]
	 */
	public function showServiceOrderDefaultFilter($model, $link) {
			echo $this->Form->create($model, $link);
		?>
		<table style='width:600px'>
			<tr>
				<td style='width:150px'>
					Número:
				</td>
				<td>
				<?php
					echo $this->Form->input('service_order_number', array('div' => false, 'label' => '', 'style' => 'width:200px', 'onkeydown' => "checkEnterKey(event, 'btnFindOS')"));
				?>
				</td>
				<td style='width:150px'>
					Placa:
				</td>
				<td>
				<?php
					echo $this->Form->input('vehicle_plate',array('div' => false, 'label' => '', 'style' => 'width:200px', 'onkeydown' => "checkEnterKey(event, 'btnFindOS')", 'alt' => 'plate'));
				?>
				</td>
			</tr>
			<tr>
				<td style='width:150px'>
					CPF:
				</td>
				<td>
				<?php
					echo $this->Form->input('vehicle_cpf', array('div' => false, 'label' => '', 'style' => 'width:200px', 'onkeydown' => "checkEnterKey(event, 'btnFindOS')",'alt' => 'cpf'));
				?>
				</td>
				<td style='width:150px'>
					CNPJ:
				</td>
				<td>
				<?php
					echo $this->Form->input('vehicle_cnpj', array('div' => false, 'label' => '', 'style' => 'width:200px', 'onkeydown' => "checkEnterKey(event, 'btnFindOS')",'alt'=>'cnpj'));
				?>
				</td>
			</tr>
			<tr>
				<td>
					
				</td>
				<td>
					<?php 
						echo $this->Form->submit('Localizar', array('div' => false, 'onclick' => '$("#loading").show();'));
					?>
				</td>
				<td colspan=2>
					<?php
						echo '<span style="display:none" id="loading">&nbsp;&nbsp;' .
						$this->Html->image('loading.gif') .
						'&nbsp;Buscando OS ...</span>';
					?>
				</td>
			</tr>
		</table>
		<?php echo $this->Form->end();
	}
}

?>