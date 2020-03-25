<?
session_name('TABLCECACHE');
session_id('0000000777');
session_set_cookie_params(3600);
session_start();

$diff = time() - $_SESSION['time_min'];
//$second=$diff-(int)($diff/60)*60;

if ($diff > 300) {
    session_unset();
    session_destroy();
}

session_write_close();

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$APPLICATION->SetTitle("Прогрев каталога");

CModule::IncludeModule("iblock");
CModule::IncludeModule('vladsold.tablecache');
CJSCore::Init(array("vladsold.tablecache_ext"));


?>

    <div class="progressline" id="progressline">
        <form action="#" class="air" method="post">
            <div class="container">
                <div class="textinput">Укажите <b>ID инфоблока</b> для прогрева:</div>
                <input id="block_id" class="block" value="9" required/>
                <div class="textinput">Укажите <b>символ</b> в названии раздела:</div>
                <input id="sumbol_id" class="sumbol" value="!" required/>
                <div class="textinput">Сбросить ли кеш для сайта?</div>
                <input type="checkbox" id="clearcache_id"/>
                <div v-if="increasing == 0" class="btn" v-on:click="callScript()">
                    <? if (!$_SESSION['progress_status']): ?>
                        <div style="padding-top: 13px">Начать обход!</div>
                    <? else: ?>
                        <div style="padding-top: 13px">Просмотр статуса</div>
                    <? endif; ?>
                </div>
            </div>
        </form>
        <div class="textdetails container"><b>{{title}}</b><br> <small> {{details}} </small>
            <vue-simple-progress class="line" size="big" :val="increasing"
                                 :text="increasing + '%'"></vue-simple-progress>
        </div>
    </div>

    <script>
        new Vue({
            el: '#progressline',
            data: function () {
                return {
                    title: 'Состояние:',
                    increasing: 0,
                    details: '',
                    countprogress: ''
                }
            },
            methods: {
                callProgress() {
                    fetch('vladsold_tablecache_progress_line.php')
                        .then(response => response.json())
                        .then(json => {
                            this.increasing = json[0].progress;
                            this.details = "детали: " + json[1].details;
                        })
                },

                callScript() {
                    this.title = 'Состояние: в работе...';

                    <? if(!$_SESSION['progress_status']): ?>
                    let block_id = document.getElementById("block_id").value;
                    let sumbol_id = document.getElementById("sumbol_id").value;
                    let clearcache_id = 0;
                    if (document.getElementById("clearcache_id").checked == true) clearcache_id = 1;

                    fetch('vladsold_tablecache_runner.php?block_id=' + block_id + "&sumbol_id=" + sumbol_id + "&clearcache_id=" + clearcache_id)
                        .then(response => response.json())
                        .then(json => {
                            this.title = json[0].message;
                            this.details = json[1].message_status;
                        })
                        .catch(error => {
                            this.title = 'Ошибка';
                            this.details = '?';
                        });
                    <? endif; ?>

                    var interval_id = setInterval(() => {
                        this.callProgress();
                        if (this.increasing >= 100) {
                            clearInterval(interval_id);
                            this.title = "Проход завершен";
                            this.details = "-";
                        }
                    }, 2000); //2 sec

                }
            }
        })

    </script>

<?
require($DOCUMENT_ROOT . "/bitrix/modules/main/include/epilog_admin.php");

