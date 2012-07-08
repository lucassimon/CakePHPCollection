<?php
/**
 * 
 */
class MenuComponent extends Component {
	/**
	 * [$components description]
	 * @var array
	 */
	var $components = array('Session');
	/**
	 * [$company description]
	 * @var integer
	 */
	var $company = 0;
	/**
	 * [$user description]
	 * @var [type]
	 */
	var $user;

	/**
	 * [startup description]
	 * @return [type] [description]
	 */
	function startup() {
		$this->company = $this->Session->read('Company');
		$this->user = $this->Session->read('User');

		$menuBar = array();
		$this->makeAccessMenu($menuBar);
		$this->makeRegistrationMenu($menuBar);


		$menuBar['Sair'] = '/auth/users/logout';

		$this->Session->write('Menu', $menuBar);

	}

	/**
	 * [makeUserMenu description]
	 * @param  [type] &$menuBar [description]
	 * @return [type]           [description]
	 */
	function makeUserMenu(&$menuBar) {
		if ($this->checkPermission(array('CRUD_USER'))) {
			$menuBar['Acesso']['Usuários']['Adicionar'] = '/auth/users/add';
			$menuBar['Acesso']['Usuários']['Listar'] = '/auth/users/index';
		}
		if ($this->checkPermission(array('CRUD_PROFILE'))) {
			$menuBar['Acesso']['Usuários']['Perfil']['Adicionar'] = '/auth/profiles/add';
			$menuBar['Acesso']['Usuários']['Perfil']['Listar'] = '/auth/profiles/index';
		}

		if ($this->checkPermission(array('CRUD_MODULE')) && $this->user['motorsoft']) {
			$menuBar['Acesso']['Usuários']['Perfil']['Editar recursos'] = '/auth/profiles/edit_resources';
		}
	}

	/**
	 * [makeAccessMenu description]
	 * @param  [type] &$menuBar [description]
	 * @return [type]           [description]
	 */
	function makeAccessMenu(&$menuBar) {
		$menuBar['Acesso']['Modificar minha senha'] = '/auth/users/change_own_password';
		$menuBar['Acesso']['Modificar meus dados'] = '/auth/users/change_own_data';        
		$this->makeUserMenu($menuBar);
	}

	/**
	 * [makeRegistrationMenu description]
	 * @param  [type] &$menuBar [description]
	 * @return [type]           [description]
	 */
	function makeRegistrationMenu(&$menuBar) {
		$menuBar['Cadastro']['Estados'] = '/states/index';
		$menuBar['Cadastro']['Cidades'] = '/cities/index';
		$menuBar['Cadastro']['Bairros'] = '/neighborhoods/index';
		$menuBar['Cadastro']['Tipos'] = '/types/index';
		$menuBar['Cadastro']['Opções'] = '/options/index';
		$menuBar['Cadastro']['Imóveis'] = '/properties/index';
	}

	/**
	 * [checkPermission description]
	 * @param  [type] $options [description]
	 * @return [type]          [description]
	 */
	function checkPermission ($options) {
		$permissions = $this->Session->read('Permissions');
		if($permissions) {
			foreach ($options as $option) {
				foreach ($permissions as $permission) {
					if ($option == $permission['code']) {
						return true;
					}
				}
			}
		}
		return false;
	}

	/**
	 * [checkModules description]
	 * @param  [type] $options [description]
	 * @return [type]          [description]
	 */
	function checkModules ($options) {
		$modules = $this->Session->read('Modules');
		if ($modules) {
			foreach ($options as $option) {
				foreach($modules as $module) {
					if ($option == $module['code']) {
						return true;
					}
				}
			}
		}
		return false;
	}
}
?>
