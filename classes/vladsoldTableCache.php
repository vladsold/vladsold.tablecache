<?php

class vladsoldTableCache
{

    private $maxLevelCatalog = 7;
    private $dopArgument = "tablecache=1";

    function siteOpen($file, $cookie = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $file);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        if ($cookie) {
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Chrome 58.0.3029.110');

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    function runList($blockIDForm, $symbolNameForm, $urlNameForm)
    {
        $catalogTopCategory = array();
        $arSelect = Array("ID", "NAME");

        $sect = CIBlockSection::GetList(
            Array(
                array('sort' => 'asc'),
            ),
            Array(
                'IBLOCK_ID' => $blockIDForm,
                'SECTION_ID' => false,
                'CATALOG_AVAILABLE' => 'Y',
            ),
            false,
            $arSelect);

        while ($el = $sect->Fetch()) {
            $catalogTopCategory[$el["ID"]] .= $el["NAME"];
        }


        $count_cat_all = count($catalogTopCategory);
        $count_percent = 0;

        foreach ($catalogTopCategory as $keyID => $valueName) {
            $count_percent++;
            $subFolderLevel = 1;
            $subFolderID = $keyID;

            $catalog_details_debug = $valueName . " --> ";
            $catalog_percent_debug = ceil(($count_percent * 100) / $count_cat_all) - 2;


            while ($subFolderLevel <= $this->maxLevelCatalog) {
                $rsParentSection = CIBlockSection::GetByID($subFolderID);
                if ($arParentSection = $rsParentSection->GetNext()) {
                    $arFilter = array(
                        'IBLOCK_ID' => $blockIDForm,
                        '>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],
                        '<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],
                        'DEPTH_LEVEL' => $subFolderLevel,
                        'ACTIVE' => 'Y',
                        'NAME' => "%".$symbolNameForm."%"
                    );

                    $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilter,
                        array('ELEMENT_SUBSECTIONS' => 'Y', 'CNT_ACTIVE' => 'Y'));

                    while ($arSect = $rsSect->GetNext()) {
                        if (strpos($arSect['NAME'], $symbolNameForm) !== false && $arSect['ELEMENT_CNT'] > 1) {
                            $handle = $this->siteOpen($urlNameForm . $arSect['CODE'] . "/?" . $this->dopArgument);
                            if ($handle) {
                                $catalog_details_debug_subName = $arSect['NAME'];
                                session_name('TABLCECACHE');
                                session_id('0000000777');
                                session_start();
                                $_SESSION['progress_status'] = $catalog_percent_debug;
                                $_SESSION['details_progress'] = $catalog_details_debug . $catalog_details_debug_subName . " (читаем)";
                                $_SESSION['time_min'] = time();
                                session_write_close();
                            }
                        }

                    }
                } else {
                    break;
                }

                $subFolderLevel++;
            }


        }
        session_name('TABLCECACHE');
        session_id('0000000777');
        session_start();
        $_SESSION['progress_status'] = 100;
        $_SESSION['details_progress'] = 'ok';
        session_write_close();

    }
}
