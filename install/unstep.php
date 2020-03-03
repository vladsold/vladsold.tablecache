<?

if (!check_bitrix_sessid()) {
    return;
}

IncludeModuleLangFile(__FILE__);
CAdminMessage::ShowNote("Модуль " . GetMessage("MODULE_TITLE") . "  успешно удален из системы");
