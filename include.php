<?php


$pathCSS = '/bitrix/panel/';
$pathJS = '/bitrix/js/' . 'vladsold.tablecache';
//$pathLang = BX_ROOT.'/modules/'.MODULE_ID.'/lang/'.LANGUAGE_ID;


CModule::AddAutoloadClasses(
    'vladsold.tablecache',
    array(
        'vladsoldTableCache' => 'classes/vladsoldTableCache.php'
    )
);

$moduleConfig = array(
        'vladsold.tablecache_ext' => array(
        'js' => array($pathJS.'/vue.js', $pathJS.'/vue-simple-progress.min.js',$pathJS.'/script.js'),
        'css' => array($pathCSS.'/vladsold_tablecache_panel.css'),
        // 'lang' => $pathLang.'/js_admin.php'
        // 'rel' => array('jquery'),
    )
);
foreach ($moduleConfig as $ext => $arExt) {
    CJSCore::RegisterExt($ext, $arExt);
}

