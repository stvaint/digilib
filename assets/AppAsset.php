<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
		"theme/assets/global/plugins/font-awesome/css/font-awesome.min.css",
		"theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css",
		"theme/assets/global/plugins/bootstrap/css/bootstrap.min.css",
		"theme/assets/admin/pages/css/tasks.css",
		"theme/assets/global/css/components-rounded.css",
		"theme/assets/global/css/plugins.css",
		"theme/assets/admin/layout3/css/layout.css",
		"theme/assets/admin/layout3/css/themes/default.css",
		"theme/assets/admin/layout3/css/custom.css",
		"css/autocomplete.css"
    ];
    public $js = [
		"theme/assets/global/plugins/jquery-migrate.min.js",
		"theme/assets/global/plugins/jquery-ui/jquery-ui.min.js",
		"theme/assets/global/plugins/bootstrap/js/bootstrap.min.js",
		"theme/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js",
		"theme/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js",
		"theme/assets/global/plugins/jquery.blockui.min.js",
		"theme/assets/global/plugins/jquery.cokie.min.js",
		"theme/assets/global/plugins/uniform/jquery.uniform.min.js",
		"theme/assets/global/scripts/metronic.js",
		"theme/assets/admin/layout3/scripts/layout.js",
		"theme/assets/admin/layout3/scripts/demo.js",
		"theme/assets/admin/pages/scripts/index3.js",
		"theme/assets/admin/pages/scripts/tasks.js",
		"js/jquery.autocomplete.js",
		"js/jquery.bootpag.min.js"
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
