<?php
ob_start();
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");


CModule::IncludeModule("iblock");
CModule::IncludeModule('vladsold.tablecache');
CJSCore::Init(array("vladsold.tablecache_ext"));


$iblockID = filter_var($_GET['block_id'], FILTER_VALIDATE_INT);
$symbolName = filter_var($_GET['sumbol_id'], FILTER_SANITIZE_STRING);
$clearCaheFlag = filter_var($_GET['clearcache_id'], FILTER_VALIDATE_INT);


if ($iblockID && $symbolName) {

    if ($clearCaheFlag == 1) {
        $obCache = new CPHPCache();
        $obCache->CleanDir(); // clear memcached
    }

    $resultDB = CIBlock::GetList(array(), array('ID' => $iblockID), false);
    $row = $resultDB->Fetch();
    $urlCatalog = "https://" . str_replace('#SITE_DIR#', $row["SERVER_NAME"] . '/', $row["LIST_PAGE_URL"]);

    ob_end_clean();
    ob_end_flush();
    session_start();
    session_unset();
    session_destroy();
    header("Content-type: application/json;charset=utf-8");
    if ($urlCatalog) {
        $buttonRun = new vladsoldTableCache;
        $buttonRun->runList($iblockID, $symbolName, $urlCatalog);
        echo('[{"message":"Проход завершен"}, {"message_status":"-"}]');
    } else {
        echo('[{"message":"Ошибка получения url каталога"}, {"message_status":"error"}]');

    }
    exit();
}
else{
    echo('[{"message":"Ошибка исполнения"}, {"message_status":"не переданы все аргументы"}]');
}

require($DOCUMENT_ROOT . "/bitrix/modules/main/include/epilog_admin.php");