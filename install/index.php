<?

use \Bitrix\Main\IO\Directory;

IncludeModuleLangFile(__FILE__);

class vladsold_tablecache extends CModule
{
    public $MODULE_ID = "vladsold.tablecache"; // class vladsold_tablecache
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_CSS;

    private $allFilesInstall = array();

    function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__ . "/version.php");

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
        else{
            $this->MODULE_VERSION = '0';
            $this->MODULE_VERSION_DATE = '0';
        }

        $this->MODULE_NAME = GetMessage("MODULE_TITLE");
        $this->MODULE_DESCRIPTION = GetMessage("MODULE_DESCRIPTION");

        $countFile = 0;

        $this->allFilesInstall[$countFile]["pathFrom"] = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $this->MODULE_ID . "/install/admin/viewer.php";
        $this->allFilesInstall[$countFile]["pathTo"] = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/vladsold_tablecache_viewer.php";
        $countFile++;

        $this->allFilesInstall[$countFile]["pathFrom"] = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $this->MODULE_ID . "/install/admin/progress_line.php";
        $this->allFilesInstall[$countFile]["pathTo"] = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/vladsold_tablecache_progress_line.php";
        $countFile++;

        $this->allFilesInstall[$countFile]["pathFrom"] = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $this->MODULE_ID . "/install/admin/runner.php";
        $this->allFilesInstall[$countFile]["pathTo"] = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/vladsold_tablecache_runner.php";
        $countFile++;

        $this->allFilesInstall[$countFile]["pathFrom"] = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $this->MODULE_ID . "/install/panel/styles.css";
        $this->allFilesInstall[$countFile]["pathTo"] = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/panel/vladsold_tablecache_panel.css";
        $countFile++;

        $this->allFilesInstall[$countFile]["pathFrom"] = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $this->MODULE_ID . "/install/js";
        $this->allFilesInstall[$countFile]["pathTo"] = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/js/" . $this->MODULE_ID;
        $countFile++;

        $this->allFilesInstall[$countFile]["pathFrom"] = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $this->MODULE_ID . "/install/themes/.default/icons/module_icon.png";
        $this->allFilesInstall[$countFile]["pathTo"] = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/themes/.default/icons/" . $this->MODULE_ID . "/vladsold_tablecache.png";
        $countFile++;

        $this->allFilesInstall[$countFile]["pathFrom"] = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $this->MODULE_ID . "/install/themes/.default/module.css";
        $this->allFilesInstall[$countFile]["pathTo"] = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/themes/.default/vladsold_tablecache.css";
        $countFile++;

    }

    function InstallFiles()
    {
        foreach ($this->allFilesInstall as $value) {
            CopyDirFiles($value["pathFrom"], $value["pathTo"], true, true);
        }

        return true;
    }

    function UnInstallFiles()
    {
        foreach ($this->allFilesInstall as $value) {
            Directory::deleteDirectory($value["pathTo"]);
        }

        return true;
    }

    function DoInstall()
    {
        global $APPLICATION;
        $this->InstallFiles();
        RegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile(
            GetMessage("MODULE_INSTALL") . " " . GetMessage("MODULE_TITLE"),
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $this->MODULE_ID . "/install/step.php"
        );


    }

    function DoUninstall()
    {
        global $APPLICATION;
        $this->UnInstallFiles();
        UnRegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile(
            GetMessage("MODULE_UNISTALL") . " " . GetMessage("MODULE_TITLE"),
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $this->MODULE_ID . "/install/unstep.php"
        );

    }
}

