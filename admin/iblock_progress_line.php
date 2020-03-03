<?
session_name('TABLCECACHE');
session_id('0000000777');
session_start(array('read_and_close' => true));
header("Content-type: application/json;charset=utf-8");
if($_SESSION['progress_status'])
    echo('[{"progress":"'.$_SESSION['progress_status'].'"}, {"details":"'.$_SESSION['details_progress'].'"}]');
else
    echo('[{"progress":"0"}, {"details":0"}]');
