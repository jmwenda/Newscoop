<?php
/**
 * @package Campsite
 *
 * @author Mugur Rus <mugur.rus@gmail.com>
 * @copyright 2008 MDLF, Inc.
 * @license http://www.gnu.org/licenses/gpl.txt
 * @version $Revision$
 * @link http://www.campware.org
 */


$upgrade_trigger_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'upgrading.php';
if (!file_exists($upgrade_trigger_path)) {
    header('Location: index.php');
    exit(0);
}

// check if user have application.ini file and show message about chnages.
if (!file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'application.ini')) {
    if (!$_GET['skip_alert']) {
        echo $message = <<<EOF
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Newscoop - upgrade message</title>
    <style type="text/css">body{background-color:#fafafa;color:#696969;font:12px/20px Arial, Helvetica, sans-serif;padding-top:70px}
.pageWrapper{margin:0 auto;overflow:hidden;padding:15px;width:460px}
.content{background:linear-gradient(#f8f8f80%,#f2f2f2100%);border:1px solid #e4e4e4;padding:16px}
.footer{clear:both;color:#666;font-size:11px;line-height:16px;margin-top:4px;padding:5px 0 0;text-align:center}
.logo{margin:10px 0 30px;text-align:center}
a{color:#007fb3;text-decoration:none}
a:hover{text-decoration:underline}
h2{-moz-box-shadow:0 2px 2px rgba(0,0,0,.10);-webkit-box-shadow:0 2px 2px rgba(0,0,0,.10);background:linear-gradient(#fafafa0%,#f6f6f6100%);border:1px solid #afafaf;border-left-color:#e5e5e5;border-right-color:#e5e5e5;border-top-color:#FFF;box-shadow:0 2px 2px rgba(0,0,0,.10);color:#444;font-size:15px;margin:0 0 10px;padding:8px 10px;text-align:left}
p{margin:0 0 10px}</style>
</head>
<body>  
<div class="pageWrapper">
    <div class="content">
        <div class="logo"><img src="data:image/gif;base64,R0lGODlh6gBEAMQQAAB/sz2dxHq61LjX5Obu8Q+Gt5nJ3B+Ou9bm7cff6S6Vv1yrzKjQ4E2kyInB2Gyz0P///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C1hNUCBEYXRhWE1QPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS4wLWMwNjAgNjEuMTM0Nzc3LCAyMDEwLzAyLzEyLTE3OjMyOjAwICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1IFdpbmRvd3MiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MTA1ODM0QzIxRkRDMTFFMEIzRjE5NEE2RUM3QkJEN0EiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MTA1ODM0QzMxRkRDMTFFMEIzRjE5NEE2RUM3QkJEN0EiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDoxMDU4MzRDMDFGREMxMUUwQjNGMTk0QTZFQzdCQkQ3QSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDoxMDU4MzRDMTFGREMxMUUwQjNGMTk0QTZFQzdCQkQ3QSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/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" border="0" alt="Newscoop logo">
        </div>
        <h2>Configuration files was chnaged.</h2>
        <p>Read more about changes here: <a href="https://wiki.sourcefabric.org/display/CS/Changes+in+config+files">https://wiki.sourcefabric.org/display/CS/Changes+in+config+files</a></p>
        <p style="text-align:center"><a href="?skip_alert=true">Continue now (skip this)</p></p>
    </div>
    <div class="footer">
        <a href="http://newscoop.sourcefabric.org/" target="_blank">
            Newscoop</a>, the open content management system for professional journalists.
         <br>
            ©&nbsp;2013&nbsp;<a href="http://www.sourcefabric.org" target="_blank">Sourcefabric o.p.s.</a>&nbsp;Newscoop       is distributed under GNU GPL v.3    
    </div>
</div>
</body>
</html>
EOF;
die();
    }
}

require_once __DIR__ . '/constants.php';
require_once __DIR__ . '/application.php';

// removes library/Zend in favor of vendor
$libZend = __DIR__ . '/library/Zend';
if (file_exists($libZend)) {
    exec("rm -rf $libZend", $output = array(), $code);
    if ($code) {
        echo 'Upgrade script could not remove ' . $libZend . '.
            Please do it manually and run this script again.';
    }
}

$application->bootstrap('autoloader');
$application->bootstrap('container');

header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");

$g_documentRoot = dirname(__FILE__);

// goes to install process if configuration files does not exist yet
if (!file_exists($g_documentRoot.'/conf/configuration.php')
        || !file_exists($g_documentRoot.'/conf/database_conf.php')) {
    header('Location: install/index.php');
    exit(0);
}

require_once($g_documentRoot.'/db_connect.php');
require_once($g_documentRoot.'/include/campsite_init.php');
require_once($g_documentRoot.'/bin/cli_script_lib.php');
require_once($g_documentRoot.'/install/classes/CampInstallation.php');
require_once($g_documentRoot.'/classes/User.php');
require_once($g_documentRoot.'/classes/CampPlugin.php');

set_time_limit(0);
$dbVersion = '';
$dbRoll = '';
$res = camp_detect_database_version($Campsite['DATABASE_NAME'], $dbVersion, $dbRoll);
if ($res !== 0) {
    $dbVersion = '[unknown]';
}
$dbInfo = $dbVersion;
if (!in_array($dbRoll, array('', '.'))) {
    $dbInfo .= ', roll ' . $dbRoll;
}
echo "Upgrading the database from version $dbInfo...";

// initiates the campsite site
$campsite = new CampSite();

// loads site configuration settings
$campsite->loadConfiguration(CS_PATH_CONFIG.DIR_SEP.'configuration.php');

// starts the session
$campsite->initSession();

$session = CampSite::GetSessionInstance();
$configDb = array('hostname'=>$Campsite['db']['host'],
                  'hostport'=>$Campsite['db']['port'],
                  'username'=>$Campsite['db']['user'],
                  'userpass'=>$Campsite['db']['pass'],
                  'database'=>$Campsite['db']['name']);
$session->setData('config.db', $configDb, 'installation');

// upgrading the database
$res = camp_upgrade_database($Campsite['DATABASE_NAME'], true, true);
if ($res !== 0) {
    display_upgrade_error("While upgrading the database: $res");
}
CampCache::singleton()->clear('user');
CampCache::singleton()->clear();
SystemPref::DeleteSystemPrefsFromCache();

// update plugins
CampPlugin::OnUpgrade();

CampRequest::SetVar('step', 'finish');
$install = new CampInstallation();
$install->initSession();
$step = $install->execute();

// update plugins environment
CampPlugin::OnAfterUpgrade();

CampTemplate::singleton()->clearCache();

if (file_exists($upgrade_trigger_path)) {
    @unlink($upgrade_trigger_path);
}

function display_upgrade_error($errorMessage, $exit = TRUE)
{
    if (defined('APPLICATION_ENV') && APPLICATION_ENV == 'development') {
        var_dump($errorMessage);
        if ($exit) {
            exit(1);
        }
    }

    $template = CS_SYS_TEMPLATES_DIR.DIR_SEP.'_campsite_error.tpl';
    $templates_dir = CS_TEMPLATES_DIR;
    $params = array('context' => null,
                    'template' => $template,
                    'templates_dir' => $templates_dir,
                    'error_message' => $errorMessage
    );
    $document = CampSite::GetHTMLDocumentInstance();
    $document->render($params);
    if ($exit) exit(0);
}
?>
<p>finished<br/><a href="<?php echo "/$ADMIN";?>">Administration</a></p>
