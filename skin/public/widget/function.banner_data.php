<?php
function smarty_function_banner_data($params, $smarty){
	$modelTemplate = $smarty->tpl_vars['modelTemplate']->value instanceof frontend_model_template ? $smarty->tpl_vars['modelTemplate']->value : new frontend_model_template();
	$banner = new plugins_banner_public($modelTemplate);
	$assign = isset($params['assign']) ? $params['assign'] : 'banners';
	$smarty->assign($assign,$banner->getBanners($params));
}