<?

if (!check_bitrix_sessid()) {
    return;
}

IncludeModuleLangFile(__FILE__);
echo CAdminMessage::ShowNote("Модуль " . GetMessage("MODULE_TITLE") . " установлен");