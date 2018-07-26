<?php
header("content-type:text/html; charset=utf-8");
$imgDogCode = $_POST["imgDogCode"];
$info1 = $_FILES["fileto1"];
$info2 = $_FILES["fileto2"];
$info3 = $_FILES["fileto3"];

$ahead = upload_image($info1,"upload/",$imgDogCode,"ahead");
$side = upload_image($info2,"upload/",$imgDogCode,"side");
$back = upload_image($info3,"upload/",$imgDogCode,"back");
$host="";
$username="";
$password="";

$connection= mysql_connect ($host, $username, $password);

if (!$connection) {
    die ("数据库连接失败");
}

$result=mysql_select_db ("dixin");

if (! $result) {
    die ("保存失败，请重试");
}
mysql_query("set character set 'utf8'");//读库
mysql_query("set names 'utf8'");//写库
//这个是你要插入的数组
//删除
//$exec="delete from dixin.leagueoflegends where id IN ($i)";
//写入
$exec="UPDATE dixin.table set $ahead =  where channelOrderNo = ";
$result= mysql_query($exec);
if (!$result){
    echo '保存失败';
}
else{
    echo '保存成功';

}
//这里是插入数据库的语句
mysql_close($connection);

//图片上传函数：//参数：$info 上传图片信息   $path 上传后保存图片的文件夹路径
function upload_image($info,$path,$imgDogCode,$direction){
//获取图片后缀
    $pre = strrchr($info["name"],".");
    $img_name = $imgDogCode.$direction.date("Ymd").$pre;
//错误过滤
    if($info["error"]>0){
        switch($info["error"]){
            case 1:
                echo "文件大小超过php.ini设置的大小 2M。";
                break;
            case 2:
                echo "文件大小超过表单设置的大小。";
                break;
            case 3:
                echo "文件只有部分被上传。";
                break;
            case 4:
                echo "没有文件被上传。";
                break;
            case 6:
                echo "找不到临时文件夹。";
                break;
            case 7:
                echo "文件写入失败。";
                break;
        }
        echo "<a href='add.php'>返回添加图片页面</a>";
        exit;
    }

//图片类型过滤
    $pic_arr = array("image/jpeg","image/jpg","image/pjpeg","image/png","image/x-png","image/gif");
    if(!in_array($info["type"],$pic_arr)){
        echo "上传的文件必须是 jpg、png、gif格式的。";
        echo $info["type"];
        exit;
    }

//图片大小过滤
    if($info["size"]>(2*1024*1024)){
        echo "上传图片的大小不能超过 2M。";
        echo "<a href='add.php'>返回添加图片页面</a>";
        exit;
    }

    if(is_uploaded_file($info["tmp_name"])){
        move_uploaded_file($info["tmp_name"],$path.$img_name);
        echo "OK";
        return $path.$img_name;
    }
}
?>