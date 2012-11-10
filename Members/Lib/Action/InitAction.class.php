<?php
/**
 * InitAction.class.php 
 * 初始货函数 
 * @author Lok 2012/9/15
 */

class InitAction extends Action {
	public function _initialize() {
		if (!session('?user_id')) {
			header('Location: ' . C('WEB_ROOT') . '/index.php?m=Login');
			exit;
		}
	}
}