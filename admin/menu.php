<?
if (!CModule::IncludeModule("iblock")) {
    return false;
}

IncludeModuleLangFile(__FILE__);

$aMenu = array();

global $USER;
$bUserIsAdmin = $USER->IsAdmin();

if ($bUserIsAdmin) {
    $aMenu[] = array(
        "parent_menu" => "global_menu_content", // in content category
        "section" => 'vladsold.tablecache',
        "sort" => 200,
        "text" => GetMessage("MENU_TITLE"),
        "title" => GetMessage("MENU_DESCRIPTION"),
        "url" => "vladsold_tablecache_viewer.php?lang=" . LANGUAGE_ID,
        "icon" => "module_icon",
        "items_id" => "menu_tablecache_parent",
        "module_id" => 'vladsold.tablecache',
        //"items" => $aSubMenu,
    );
}


return $aMenu;