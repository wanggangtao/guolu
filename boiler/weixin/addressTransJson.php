<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/24
 * Time: 18:57
 */
require_once ("admin_init.php");

try {

    $res = array();


    $firstAddress = District::getAddressType(3,0);


    if(empty($firstAddress))
    {
        throw new MyException("不存在!",301);
    }

    foreach ($firstAddress as $i=>$firstAddress)
    {
        $res[$i]["name"] = $firstAddress["name"];
        $res[$i]["code"] = $firstAddress["id"];

        $secondAddress =Table_district::getAddressType(0,$firstAddress["id"]);
        $second = array();
        foreach ($secondAddress as $j=>$secondAddress)
        {
            $second[$j]["code"] = $secondAddress["id"];
            $thirdAddress = Table_district::getAddressType(0,$secondAddress["id"]);
            $third = array();
            foreach ($thirdAddress as $z=>$thirdAddress)
            {
                $third[$z]["code"] = $thirdAddress["id"];
                $third[$z]["name"] = $thirdAddress["name"];
            }
            $second[$j]["name"] = $secondAddress["name"];
            $second[$j]["sub"]=$third;
        }
        $res[$i]["sub"] = $second;
    }
    header('Content-Type:application/json; charset=utf-8');
    $res = json_encode($res, JSON_UNESCAPED_UNICODE);

    $filepath_pdf_rel = "userfiles/upload/weixin/".date("Ymd")."/";
    $filepath_pdf_abs    = $FILE_PATH.$filepath_pdf_rel;//绝对路径
    if (!file_exists($filepath_pdf_abs)){
        mkdir($filepath_pdf_abs,0777,true);
    }

    $file_pdf_name = "addressTransJson.xml";

    $file=$filepath_pdf_abs.$file_pdf_name;

    file_put_contents($file,$res);


    print_r($file);

}catch (MyException $e){
     $e->getMessage();
}

?>