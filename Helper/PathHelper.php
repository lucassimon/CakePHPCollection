<?php
/**
* Classe helper, responsavel por resgatar os caminhos do cake.
* 
* @author Lucas Simon Rodrigues Magalhaes <lucas.simon@lucassimon.net>
* @version 1.0
*/

class PathHelper extends AppHelper {
	/**
	 * [$helpers description]
	 * @var array
	 */
	var $helpers = array('Session');

	/**
	 * [getImagePath description]
	 * @param  [type] $image [description]
	 * @return [type]        [description]
	 */
	function getImagePath($image){
		return $this->webroot("img/" . $image);
	}

	/**
	 * [getImageDirectory description]
	 * @return [type] [description]
	 */
	function getImageDirectory() {
		return $this->webroot("img/");
	}

	/**
	 * [getRealImagePath description]
	 * @param  [type] $image [description]
	 * @return [type]        [description]
	 */
	function getRealImagePath($image) {
		return realpath('../../app/webroot/img/') . '/' . $image;
	}

	/**
	 * [getAppletsPath description]
	 * @param  [type] $jar [description]
	 * @return [type]      [description]
	 */
	function getAppletsPath($jar) {
		return $this->link('/applets/', true) . $jar;
	}

	/**
	 * [getPhotoPath description]
	 * @return [type] [description]
	 */
	function getPhotoPath() {
		$company = $this->Session->read('Company');
		return $this->webroot("upload/photos/") . $company['id'] . "/";
	}

	/**
	 * [getReportPath description]
	 * @return [type] [description]
	 */
	function getReportPath() {
		$company = $this->Session->read('Company');
		return $this->webroot("upload/reports/") . $company['id'] . "/";
	}

	/**
	 * [getLogoPath description]
	 * @param  [type] $image [description]
	 * @return [type]        [description]
	 */
	function getLogoPath($image) {
		return $this->webroot("upload/logomarcas/" . $image);
	}

	/**
	 * [getRealPhotoPath description]
	 * @param  [type] $image      [description]
	 * @param  [type] $company_id [description]
	 * @return [type]             [description]
	 */
	function getRealPhotoPath($image, $company_id = null) {
		if (!$company_id) {
			$company = $this->Session->read('Company');
			$company_id = $company['id'];
		}
		return realpath('../../app/webroot/upload/photos/') . '/' . $company_id . "/" . $image;
	}

	/**
	 * [getRealLogoPath description]
	 * @param  [type] $logo [description]
	 * @return [type]       [description]
	 */
	function getRealLogoPath($logo) {
		return realpath('../../app/webroot/upload/logomarcas/') . '/' . $logo;
	}

	/**
	 * [getBrandPhotoPath description]
	 * @param  [type] $image_id [description]
	 * @return [type]           [description]
	 */
	function getBrandPhotoPath($image_id) {
		return $this->link(array('controller' => 'brand_photos', 'action' => 'get_brand_photo', $image_id), true);
	}

	/**
	 * [getPreviewPhotoPath description]
	 * @return [type] [description]
	 */
	function getPreviewPhotoPath() {
		return $this->webroot("upload/preview_photos/");
	}

	/**
	 * [link description]
	 * @param  [type]  $url  [description]
	 * @param  boolean $full [description]
	 * @return [type]        [description]
	 */
	function link($url, $full = false) {
		return Router::url($url, $full);
	}

	/**
	 * [getNonconformityReport description]
	 * @param  [type] $model [description]
	 * @param  [type] $id    [description]
	 * @return [type]        [description]
	 */
	function getNonconformityReport($model, $id) {
		return Router::url(array('controller'=> 'report_templates', 'action'=>'pdf_nonconformities_' . $model, $id), true);
	}

	/**
	 * [getChecklist description]
	 * @return [type] [description]
	 */
	function getChecklist() {
		return Router::url(array('controller'=> 'checklists', 'action'=>'pdf_checklist'), true);
	}

	/**
	 * [getViewInspectionList description]
	 * @return [type] [description]
	 */
	function getViewInspectionList() {
		return Router::url(array('controller'=> 'inspections', 'action'=>'pdf_view_inspection_list'), true);
	}

	/**
	 * [getInspectionReport description]
	 * @param  [type] $model [description]
	 * @param  [type] $id    [description]
	 * @return [type]        [description]
	 */
	function getInspectionReport($model, $id) {
		return Router::url(array('controller'=> 'report_templates', 'action'=>'pdf_inspection_' . $model, $id), true);
	}

	/**
	 * [getPDFAggregate description]
	 * @return [type] [description]
	 */
	function getPDFAggregate() {
		return Router::url(array('controller' => 'bin_caches', 'action' => 'pdf_aggregate'), true);
	}

	/**
	 * [getAmbientalReport description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function getAmbientalReport($id) {
		return Router::url(array('controller'=> 'reports', 'action'=>'pdf_ambiental', $id), true);
	}

	/**
	 * [getPDFInvoice description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function getPDFInvoice($id) {
		return Router::url(array('controller'=> 'invoices', 'action'=>'pdf_invoice', $id), true);
	}

	/**
	 * [getPDFReceipt description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function getPDFReceipt($id) {
		return Router::url(array('controller'=> 'receipts', 'action'=>'pdf_receipt', $id), true);
	}

	/**
	 * [getPDFServiceOrder description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function getPDFServiceOrder($id) {
		return Router::url(array('controller'=> 'service_orders', 'action'=>'pdf_service_order', $id), true);
	}

	/**
	 * [getPDFStatistic description]
	 * @param  [type] $action     [description]
	 * @param  string $controller [description]
	 * @return [type]             [description]
	 */
	function getPDFStatistic($action, $controller = 'service_orders') {
		return Router::url(array('controller'=> $controller, 'action'=>$action), true);
	}    

	/**
	 * [url_exists description]
	 * @param  [type] $url [description]
	 * @return [type]      [description]
	 */
	function url_exists($url) {
		$hdrs = @get_headers($url);
		return is_array($hdrs) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$hdrs[0]) : false;
	}

	/**
	 * [photo_exists description]
	 * @param  [type] $photo [description]
	 * @return [type]        [description]
	 */
	function photo_exists($photo) {
		$realPath = $this->getRealPhotoPath($photo);
		return file_exists($realPath);
	}

	/**
	 * [logo_exists description]
	 * @param  [type] $logo [description]
	 * @return [type]       [description]
	 */
	function logo_exists($logo) {
		$realPath = $this->getRealLogoPath($logo);
		return file_exists($realPath);
	}

}

?>
