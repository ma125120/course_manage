<?php
/**
 *
 * @copyright 2007-2012 Xiaoqiang.
 * @author Xiaoqiang.Wu <jamblues@gmail.com>
 * @version 1.01
 */

error_reporting(E_ALL);
 
date_default_timezone_set('Asia/ShangHai');
$ary="ma.xlsx";
$name=end(explode(".",$ary));
echo $name;


/** PHPExcel_IOFactory */
/*
require_once 'PHPExcel.php';
 $name=iconv("UTF-8","GBK","数据.xlsx");
 
// Check prerequisites

if (!file_exists($name)) {
	exit("not found 数据.xlsx.\n");
}
 
$reader = PHPExcel_IOFactory::createReader('Excel2007'); //设置以Excel5格式(Excel97-2003工作簿)
$PHPExcel = $reader->load($name); // 载入excel文件
$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
$highestRow = $sheet->getHighestRow(); // 取得总行数
$highestColumm = $sheet->getHighestColumn(); // 取得总列数
$query="INSERT INTO user(username) VALUES";
/** 循环读取每个单元格的数据 */
for ($row = 1; $row <= $highestRow; $row++){//行数是以第1行开始
if($row==1)
$query.="("; else $query.=",(";
    for ($col = 'A'; $col <= $highestColumm; $col++) {//列数是以A列开始
       $dataset = $sheet->getCell($col.$row)->getValue();
if($col !='A') $query.=",";
	$query.="'$dataset'";
    }

$query.=")";
}
 echo $query;*/
?>