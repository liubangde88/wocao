<?php
/**
 * Handler File Class
 *
 * @author liliang <liliang@wolive.cc>
 * @email liliang@wolive.cc
 * @date 2017/06/01
 */
session_start();
header('Content-Type: text/html; charset=utf-8');
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
define('WLIVE_ROOT', str_replace('\\', '/', substr(dirname(__FILE__), 0, -7)));

$basename = strtolower($_SERVER['SCRIPT_NAME']);

if (strstr($basename,'addons') !== false || strstr($basename,'zjhj_cs') !== false) {
    exit('error');
}
$sqlFile = '../install/data.sql';
$PHP_SELF = addslashes(htmlspecialchars($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']));
@extract($_POST);
@extract($_GET);

/**
 * @param $var
 * @return bool
 */
function writable($var)
{
    $writeable = false;
    $var = WLIVE_ROOT . $var;
    if (is_writable($var)) {
        $writeable = true;
    }
    return $writeable;
}

$dirarray = array(

    "/config",
    "/public",
    "/public/upload",
    "/runtime",
    "/public/assets/front",
    "/public/assets/layer"

);

$writeable = array();
$quit = false;
foreach ($dirarray as $key => $dir) {
    $writeable[$key]['name'] = $dir;
    if (writable($dir)) {
        $writeable[$key]['status'] = 'OK';
    } else {
        $writeable[$key]['status'] = 'False';
        $quit = true;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="iiSNS install">
    <meta name="author" content="Shiyang">
    <title>installer - yunziyuan.com.cn 云资源分享网 </title>
    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" media="screen">

    <!-- Custom styles for this template -->
    <link href="assets/css/main.css" rel="stylesheet" media="screen">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .red {
            color: red;
        }

        .green {
            color: green;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header clearfix">
        <h3 class="text-muted">安装向导</h3>
    </div>
    <div class="row marketing">
        <?php if (!file_exists('index.php')): ?>
        <?php if (!@$step): ?>
            <div class="progress" style="height:30px;font-weight: bold;">
                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                     aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                     style="width: 33.3%;border-right: 2px solid #fff;font-size: 16px;">
                    第一步
                </div>
                <div class="progress-bar " role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                     style="width: 33.3%;border-right: 2px solid #fff;background: #dddddd;font-size: 16px;">
                    第二步
                </div>
                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                     style="width: 33.4%;background: #ddd;font-size: 16px;">
                    第三步
                </div>

            </div>
            <h2>环境检测</h2>

            <table class="table table-hover">
                <caption>php扩展检测</caption>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Current server</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>

                <tr>
                    <td>PHP Version</td>
                    <td><?php echo PHP_VERSION; ?></td>
                    <?php
                    if (PHP_VERSION >= '5.4') {
                        ?>
                        <td class="green">OK</td>
                        <?php
                    } else {
                        ?>
                        <td class='red'>False</td>
                        <?php
                        $quit = true;
                    }
                    ?>

                </tr>
                </tr>

                <tr>
                    <td>Mbstring</td>
                    <td><?php echo extension_loaded('mbstring'); ?></td>
                    <?php
                    if (extension_loaded('mbstring')) {
                        ?>
                        <td class="green">OK</td>
                        <?php
                    } else {
                        ?>
                        <td class='red'>False</td>
                        <?php
                        $quit = true;
                    }
                    ?>

                </tr>

                <tr>
                    <td>GD</td>
                    <td><?php echo extension_loaded('gd'); ?></td>

                    <?php
                    if (extension_loaded('gd')) {
                        ?>
                        <td class="green">OK</td>
                        <?php
                    } else {
                        ?>
                        <td class='red'>False</td>
                        <?php
                        $quit = true;
                    }
                    ?>
                </tr>

                <tr>
                    <td>curl</td>
                    <td><?php echo extension_loaded('mbstring'); ?></td>
                    <?php
                    if (extension_loaded('curl')) {
                        ?>
                        <td class="green">OK</td>
                        <?php
                    } else {
                        ?>
                        <td class='red'>False</td>
                        <?php
                        $quit = true;
                    }
                    ?>

                </tr>

                <tr>
                    <td>pdo_mysql</td>
                    <td><?php echo extension_loaded('pdo_mysql'); ?></td>

                    <?php
                    if (extension_loaded('pdo_mysql')) {
                        ?>
                        <td class="green">OK</td>
                        <?php
                    } else {
                        ?>
                        <td class='red'>False</td>
                        <?php
                        $quit = true;
                    }
                    ?>
                </tr>


                </tbody>
            </table>
            <table class="table table-hover">
                <caption>文件可写检测</caption>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($writeable as $item): ?>
                    <tr class="<?php echo ($item['status'] == 'OK') ? 'success' : 'danger'; ?>">
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['status']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($quit): ?>
                <center style="margin-bottom: 20px;">
                    <a href="install.php" style="display: inline-block;width: 90px;" class="btn btn-danger">重新检测</a>
                </center>
            <?php else: ?>
                <center style="margin-bottom: 20px;">
                    <a href="install.php?step=2" style="display: inline-block;width: 90px;"
                       class="btn btn-success">下一步</a>
                </center>
            <?php endif; ?>

        <?php elseif ($step == 2): ?>
        <div class="progress" style="height:30px;font-weight: bold;">
            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="60"
                 aria-valuemin="0" aria-valuemax="100"
                 style="width: 33.3%;border-right: 2px solid #fff;font-size: 16px;">
                第一步
            </div>
            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="60"
                 aria-valuemin="0" aria-valuemax="100"
                 style="width: 33.3%;border-right: 2px solid #fff;font-size: 16px;">
                第二步
            </div>
            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                 style="width: 33.4%;background: #ddd;font-size: 16px;">
                第三步
            </div>

        </div>
        <form method="post" action="install.php?step=3">
            <div class="col-md-4">
                <fieldset>
                    <legend>
                        <small>填写数据库信息</small>
                    </legend>
                    <div class="form-group">
                        <label class="control-label" for="dbHost">Host</label>
                        <input type="text" class="form-control" id="dbHost" name="dbHost"
                               value="<?php if (isset($_POST['dbHost'])) echo $_POST['dbHost']; else echo 'localhost'; ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="dbName">Database name</label>
                        <input type="text" class="form-control" id="dbName" name="dbName"
                               value="<?php if (isset($_POST['dbName'])) echo $_POST['dbName']; ?>"
                               placeholder="Database Name">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="dbUser">Username</label>
                        <input type="text" class="form-control" id="dbUser" name="dbUser"
                               value="<?php if (isset($_POST['dbUser'])) echo $_POST['dbUser']; ?>"
                               placeholder="Database Username">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="dbPass">Password</label>
                        <input type="text" class="form-control" id="dbPass" name="dbPass"
                               value="<?php if (isset($_POST['dbPass'])) echo $_POST['dbPass']; ?>"
                               placeholder="Database Password">
                    </div>


                </fieldset>
            </div>
            <div class="col-md-4">

                <fieldset>
                    <legend>
                        <small>超级管理员注册</small>
                    </legend>
                    <div class="form-group">
                        <label class="control-label" for="adminUser">Username</label>
                        <input type="text" class="form-control" id="adminUser" name="adminUser"
                               value="<?php if (isset($_POST['adminUser'])) echo $_POST['adminUser']; else echo 'admin'; ?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="adminPass">Password</label>
                        <input type="text" class="form-control" id="adminPass" name="adminPass" minlength="6"
                               maxlength="16"
                               value="<?php if (isset($_POST['adminPass'])) echo $_POST['adminPass']; ?>">
                    </div>

                </fieldset>
            </div>
            <div class="col-md-4">
                <fieldset>
                    <legend>
                        <small>服务器端口配置</small>
                    </legend>

                    <div class="form-group">
                        <label class="control-label" for="app_key">App_key</label>
                        <input type="text" class="form-control" id="app_key" name="app_key"
                               value="<?php if (isset($_POST['app_key'])) echo $_POST['app_key'];else echo '3331333731383036' ?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="secret">App_secret</label>
                        <input type="text" class="form-control" id="secret" name="secret"
                               value="<?php if (isset($_POST['secret'])) echo $_POST['secret']; else echo '6842a54e4aab6e22bf368e5b7291efdf'?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="app_id">App_id</label>
                        <input type="text" class="form-control" id="app_id" name="app_id"
                               value="<?php if (isset($_POST['app_id'])) echo $_POST['app_id'];else echo '232' ?>">
                    </div>
                    <?php

                        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';

                    ?>
                    <div class="form-group">
                        <label class="control-label" for="whost">websocket 地址</label>
                        <input type="text" class="form-control" id="whost" name="whost"
                               value="<?php if (isset($_POST['whost'])) echo $_POST['whost']; else echo "ws://".$_SERVER['HTTP_HOST']; ?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="ahost">Api 地址</label>
                        <input type="text" class="form-control" id="ahost" name="ahost"
                               value="<?php if (isset($_POST['ahost'])) echo $_POST['ahost']; else echo $http_type.$_SERVER['HTTP_HOST']; ?>">
                    </div>


                    <div class="form-group">
                        <label class="control-label" for="wport">websocket 端口</label>
                        <input type="text" class="form-control" id="wport" name="wport"
                               value="<?php if (isset($_POST['wport'])) echo $_POST['wport']; else echo '9090' ?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="aport">Api 端口</label>
                        <input type="text" class="form-control" id="aport" name="aport"
                               value="<?php if (isset($_POST['aport'])) echo $_POST['aport']; else echo '2080' ?>">
                    </div>

                      <div class="form-group">
                        <label class="control-label" for="registToken">registToken</label>
                        <input type="text" class="form-control" id="registToken" name="registToken"
                               value="<?php if (isset($_POST['registToken'])) echo $_POST['registToken']; else echo rand() ?>">
                    </div>
                </fieldset>
            </div>
            <center>
                <div class="form-actions" style="margin-bottom:20px;">
                    <button type="submit" style="width: 90px;" class=" btn btn-success">下一步</button>
                </div>
            </center>
    </div>
    </form>
    <?php elseif ($step == 3): ?>
        <div class="progress" style="height:30px;font-weight: bold;">
            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="60"
                 aria-valuemin="0" aria-valuemax="100"
                 style="width: 33.3%;border-right: 2px solid #fff;font-size: 16px;">
                第一步
            </div>
            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="60"
                 aria-valuemin="0" aria-valuemax="100"
                 style="width: 33.3%;border-right: 2px solid #fff;font-size: 16px;">
                第二步
            </div>
            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="60"
                 aria-valuemin="0" aria-valuemax="100" style="width: 33.4%;font-size: 16px;">
                第三步
            </div>

        </div>


        <?php

        if (empty($dbHost) || empty($dbUser) || empty($dbName) || empty($dbPass) || empty($adminUser) || empty($adminPass) || empty($secret) || empty($app_key) || empty($app_id) || empty($whost) || empty($wport) || empty($ahost) || empty($aport) || empty($registToken)
        ): ?>

            <div class="alert alert-danger" role="alert"><strong>错误.</strong> 请把表单数据填完</div>

            <center style="margin-bottom: 20px;">
                <a href="javascript:history.go(-1)" style="display: inline-block;width: 90px;" class="btn btn-default">返回上一步</a>
            </center>

        <?php elseif (strlen($adminPass) < 5): ?>
            <div class="alert alert-danger" role="alert"><strong>错误.</strong> 密码不能小于5位数.</div>

            <center>
                <a href="javascript:history.go(-1)" style="display: inline-block;width: 90px;" class="btn btn-default">返回上一步</a>
            </center>

        <?php else: ?>


            <?php


            $link = mysqli_connect($dbHost, $dbUser, $dbPass);
            if (mysqli_connect_errno()) {
                echo '<div class="alert alert-danger" role="alert">Connection failed: ' . mysqli_connect_error() . '</div>';
                echo "<br>";
                echo '<a href="javascript:history.go(-1)" class="btn btn-default">返回上一步</a>';
                exit();
            }

            if (mysqli_select_db($link, $dbName) === false) {
                mysqli_query($link, "CREATE DATABASE {$dbName}  DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;");
                mysqli_select_db($link, $dbName);
            }

            $fp = fopen($sqlFile, 'rb');
            $sql = fread($fp, filesize($sqlFile));
            // var_dump($sql);exit;
            fclose($fp);
            foreach (explode(";", trim($sql)) as $query) {

                mysqli_select_db($link, $dbName);
                // var_dump($query);exit;
                if(trim($query)){
                    mysqli_query($link, trim($query));
                }
                // var_dump($result);exit;
            }


            mysqli_select_db($link, $dbName);


            $nickname = $adminUser;

            $pass = md5(md5($adminPass) . $adminUser);

            $result = mysqli_query($link, "insert into wolive_admin(username,password)value('{$adminUser}','{$pass}')");

            if (!$result) {

                echo '<div class="alert alert-danger" role="alert">Connection failed: ' . mysqli_error($link) . '</div>';
                echo "<br>";
                echo '<a href="javascript:history.go(-1)" class="btn btn-default">返回上一步</a>';
                exit();

            }

            session_destroy();
            mysqli_close($link);
            ?>
            <div class="alert alert-success"><strong>数据已经导入!</strong>安装已经成功!</div>

            <?php sleep(2); ?>

            <center style="margin-bottom: 20px;">
                <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/platform" style="display:inline-block;width: 90px;"
                   class="btn btn-success">完成</a>
            </center>
            <?php

            $http_type = ((isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS'])== 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';

            $domain =$http_type.$_SERVER['HTTP_HOST'];

            file_put_contents("../config/database.php", "<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // 数据库调试模式
    'debug'          => false,
    // 是否严格检查字段是否存在
    'fields_strict'  => true,
    // 是否自动写入时间戳字段
    'auto_timestamp' => false,
    // 是否需要进行SQL性能分析
    'sql_explain'    => false,

    // 数据库类型
    'type'           => 'mysql',
    // 服务器地址
    'hostname'       => '{$dbHost}',
    // 数据库名
    'database'       => '{$dbName}',
    // 用户名
    'username'       => '{$dbUser}',
    // 密码
    'password'       => '{$dbPass}',
    // 端口
    'hostport'       => '',
    // 数据库表前缀
    'prefix'         => '',
    // 数据库编码默认采用utf8
    'charset'        => 'utf8',
    // 数据库连接参数
    'params'         => [],
];
");


            file_put_contents('index.php', "<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

ini_set('session.gc_maxlifetime', 432000);
ini_set('session.cookie_lifetime', 432000);
ini_set('session.gc_probability',1);
ini_set('session.gc_divisor',1000);


isset(\$_SESSION) or session_start();

// 定义环境版本

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
define('VENDOR',__DIR__.'/../vendor/');

// 定义配置文件目录
define('CONF_PATH', __DIR__ . '/../config/'); 


// 定义pusher密匙
define('app_key','{$app_key}');
define('app_secret','{$secret}');
define('app_id',{$app_id});
define('whost','{$whost}');
define('ahost','{$ahost}');
define('wport',{$wport});
define('aport',{$aport});
define('registToken','{$registToken}');

// 自定义一个 入口 目录
define('PUBLIC_PATH',__DIR__);
// 定义 类的文件路径
define('EXTEND_PATH','../extend/');

// 定义微信配置
define('appid','');
define('appsecret','');
define('token','');
define('domain','{$domain}');

// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';");


 file_put_contents("wolive.js","
  /**
 *
 * wolive 浮层版 客服咨询js
 * [wolive description]
 * @return {[type]} [description]
 */

var wolive ={
     appName:'WoLive',
     icon:'http://www.wolive.tt/assets/images/admin/avatar-admin2.png',
     business_id:'',
     groupid:0,
     visiter_id:'',
     visiter_name:'',
     avatar:'',
     open:function(){
        var d =document.getElementById('wolive-box');
        if(!d){
            var div =document.createElement('div');
            div.id ='wolive-box';
            div.className +='wolive-tag';
            div.style='display:block';
            document.body.appendChild(div);
            var w =document.getElementById('wolive-box');
            w.innerHTML='<div id=\"wolive-min\" class=\"wolive-content\" onclick=\"wolive.connenct(this)\"><img src=\"'+this.icon+'\" ><span>'+this.appName+'</span></div>';

        }else{
            d.style ='display:block';
        }

        var s =document.getElementById('wolive-talk');

        if(s){
           s.style='display:none';
        }
     },
     connenct:function(obj){
      
       if ((navigator.userAgent.match(/(phone|pad|pod|iPhone|iPod|ios|iPad|Android|Mobile|BlackBerry|IEMobile|MQQBrowser|JUC|Fennec|wOSBrowser|BrowserNG|WebOS|Symbian|Windows Phone)/i))) {
          
         window.open(\"http://www.wolive.tt/mobile/index?visiter_id=\"+this.visiter_id+\"&visiter_name=\"+this.visiter_name+\"&avatar=\"+this.avatar+\"&business_id=\"+this.business_id+\"&groupid=\"+this.groupid+\"&product=\", \"_blank\"); 
       
       }else{

       document.getElementById('wolive-box').style='display:none';
       var s =document.getElementById('wolive-talk');
        
       if(!s){
            var div = document.createElement('div');
            div.id ='wolive-talk';
            document.body.appendChild(div);
            div.innerHTML='<i class=\"wolive-close\" onclick=\"wolive.narrow()\"></i><iframe id=\"wolive-iframe\" src=\"http://www.wolive.tt/layer?visiter_id='+this.visiter_id+'&visiter_name='+this.visiter_name+'&avatar='+this.avatar+'&business_id='+this.business_id+'&groupid='+this.groupid+'\"></iframe>'
          
        }else{
          document.getElementById('wolive-talk').style='display:block';
        }
      
       }

     },
     narrow:function(){
        document.getElementById('wolive-box').style ='display:block';
        document.getElementById('wolive-talk').style='display:none';
     }
}






    ");

            file_put_contents(__DIR__.'/../cgwl_pusher/config.php',"<?php
// 这里填写客服系统域名，前面带http://，用于pusher系统通知客服平台客户或者客服上下线
\$domain = '{$ahost}';

// App_key，客服系统与pusher通讯的key
\$app_key = '{$app_key}';

// App_secret，客服系统与pusher通讯的密钥
\$app_secret = '{$secret}';

// App id
\$app_id = {$app_id};

// websocket 端口，客服系统网页会连这个端口
\$websocket_port = {$wport};

// Api 端口，用于后端与pusher通讯
\$api_port = $aport;

");



            ?>

        <?php endif; ?>

    <?php endif; ?>

    <?php else: ?>

        <div class="alert alert-success"><strong>已经安装成功</strong></div>
        <p>如果想重新安装，请删除<code>public\index.php</code></p>
        <div class="header clearfix">
            <h3 class="text-muted">微信公众号设置向导</h3>
        </div>
        <p><a href="index.php"></a></p>
        <div>如果要添加微信公众号的用户，还需以下步骤：</div>
        <br>
        <p><b>第一，需要到<a href="https://mp.weixin.qq.com">微信公众号平台</a>,基本配置中配置服务器配置。格式如图：</b></p>
        <p><img src="/assets/images/index/one.png"><b style="color: red">域名要改成自己的，如http://www.xxx.xx/weixin</b></p>
        <p>Token值是自定义的，这里举例设为<code>'weixin'</code>，但<code>一定要和上一步填写的Token值一致。</code></p>
        <p>还需要添加自己有效IP</p>
        <p><img src="/assets/images/index/four.png"></p>

        <p><b>第二，需要获取网页授权并修改，如图：</b></p>
        <br>
        <p><img src="/assets/images/index/two.png"></p>
        <p><img src="/assets/images/index/third.png">  <b style="color: red"> 只填写自己域名，不要加上http://等协议头</b></p>
        <br>
        <p><b>最后启用就好了！</b></p>


    <?php endif; ?>
</div>
</div>
<footer class="footer">
    <div class="container">
        <p class="text-muted">©2017 辰光PHP客服系统</p>
    </div>
</footer>
<!-- javascript -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>