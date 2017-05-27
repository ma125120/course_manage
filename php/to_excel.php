<?php
    header("Content-type: text/html; charset=utf-8");
    error_reporting(0);
 //   $dbc=mysqli_connect('localhost','root','125120','test') or die('连接失败');
    $action=$_POST['action'];
    $action_type=$_POST['action_type'];
    if($action=='to_excel') {
    	    require_once 'Classes/PHPExcel.php';
            $json=json_decode($action_type,JSON_UNESCAPED_UNICODE);
               $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("PHPExcel Test Document")
							 ->setSubject("PHPExcel Test Document")
							 ->setDescription("Test document for PHPExcel")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("Test result file");
      if($json['users']) {
            $count=count($json['users']);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', '序号')
                ->setCellValue('B1', '用户名')
                ->setCellValue('C1', '身份级别')
                ->setCellValue('D1', '真实姓名');
            for($i=2;$i<$count+2;$i++) {
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$i,$i-1)
                            ->setCellValue('B'.$i," ".$json['users'][$i-2]['username'])
                            ->setCellValue('C'.$i,$json['users'][$i-2]['id_level'])
                            ->setCellValue('D'.$i,$json['users'][$i-2]['real_name']);
             }
      }
      if($json['course_libs']) {
                $count=count($json['course_libs']);
                            $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A1', '序号')
                                ->setCellValue('B1', '课程名字')->setCellValue('C1', '学分')
                                ->setCellValue('D1', '总学时')->setCellValue('E1', '教授学时')
                                ->setCellValue('F1', '类型')->setCellValue('G1', '授课方式')
                                ->setCellValue('H1', '考核方式')->setCellValue('I1', '周次');
                            for($i=2;$i<$count+2;$i++) {
                                $objPHPExcel->setActiveSheetIndex(0)
                                            ->setCellValue('A'.$i,$i-1)
                                            ->setCellValue('B'.$i," ".$json['course_libs'][$i-2]['course_name'])
                                            ->setCellValue('C'.$i,$json['course_libs'][$i-2]['credit'])
                                            ->setCellValue('D'.$i,$json['course_libs'][$i-2]['total_hours'])
                                            ->setCellValue('E'.$i,$json['course_libs'][$i-2]['teach_hour'])
                                            ->setCellValue('F'.$i,$json['course_libs'][$i-2]['type'])
                                            ->setCellValue('G'.$i,$json['course_libs'][$i-2]['teach_type'])
                                            ->setCellValue('H'.$i,$json['course_libs'][$i-2]['test_mode'])
                                            ->setCellValue('I'.$i,$json['course_libs'][$i-2]['week_time']);
                             }
           }
           if($json['course_classs']) {
                 $count=count($json['course_classs']);
                 $objPHPExcel->setActiveSheetIndex(0)
                      ->setCellValue('A1', '序号')
                      ->setCellValue('B1', '课程名字')->setCellValue('C1', '学分')
                      ->setCellValue('D1', '总学时')->setCellValue('E1', '讲授学时')
                      ->setCellValue('F1', '类型')->setCellValue('G1', '授课方式')
                      ->setCellValue('H1', '考核方式')->setCellValue('I1', '任课教师')
                      ->setCellValue('J1', '开设学期')
                      ->setCellValue('K1', '周次')->setCellValue('L1', '节次')
                      ->setCellValue('M1', '上课地点');
                  for($i=2;$i<$count+2;$i++) {
                       $objPHPExcel->setActiveSheetIndex(0)
                           ->setCellValue('A'.$i,$i-1)
                           ->setCellValue('B'.$i," ".$json['course_classs'][$i-2]['course_name'])
                           ->setCellValue('C'.$i,$json['course_classs'][$i-2]['credit'])
                           ->setCellValue('D'.$i,$json['course_classs'][$i-2]['total_hours'])
                           ->setCellValue('E'.$i,$json['course_classs'][$i-2]['teach_hour'])
                           ->setCellValue('F'.$i,$json['course_classs'][$i-2]['type'])
                           ->setCellValue('G'.$i,$json['course_classs'][$i-2]['teach_type'])
                           ->setCellValue('H'.$i,$json['course_classs'][$i-2]['test_mode'])
                           ->setCellValue('I'.$i,$json['course_classs'][$i-2]['course_teacher'])
                           ->setCellValue('J'.$i,$json['course_classs'][$i-2]['start_term'])
                           ->setCellValue('K'.$i,$json['course_classs'][$i-2]['week_time'])
                           ->setCellValue('L'.$i,$json['course_classs'][$i-2]['course_time'])
                           ->setCellValue('M'.$i,$json['course_classs'][$i-2]['course_room']);
                    }
      }
      if($json['scores']) {
            $count=count($json['scores']);
            $table1=$json['scores'];
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', '序号')->setCellValue('B1', '所属学期')
                ->setCellValue('C1', '学号')
                ->setCellValue('D1', '姓名')->setCellValue('E1', '课程')
                ->setCellValue('F1', '学分')->setCellValue('G1', '类别')
                ->setCellValue('H1', '考核方式')->setCellValue('I1', '平时成绩')
                ->setCellValue('J1', '末考成绩')->setCellValue('K1', '综合成绩')
                ->setCellValue('L1', '绩点')->setCellValue('M1', '学分绩点');
            for($i=2;$i<$count+2;$i++) {
                     $objPHPExcel->setActiveSheetIndex(0)
                         ->setCellValue('A'.$i,$i-1)
                         ->setCellValue('B'.$i," ".$table1[$i-2]['start_term'])
                         ->setCellValue('C'.$i,' '.$table1[$i-2]['username'])
                         ->setCellValue('D'.$i,$table1[$i-2]['real_name'])
                         ->setCellValue('E'.$i,$table1[$i-2]['course_name'])
                         ->setCellValue('F'.$i,$table1[$i-2]['credit'])
                         ->setCellValue('G'.$i,$table1[$i-2]['type'])
                         ->setCellValue('H'.$i,$table1[$i-2]['test_mode'])
                         ->setCellValue('I'.$i,$table1[$i-2]['peace_score'])
                         ->setCellValue('J'.$i,$table1[$i-2]['final_score'])
                         ->setCellValue('K'.$i,$table1[$i-2]['total_score'])
                         ->setCellValue('L'.$i,$table1[$i-2]['point'])
                         ->setCellValue('M'.$i,$table1[$i-2]['point']*$table1[$i-2]['credit']);
                  }
      }
$objPHPExcel->getActiveSheet()->setTitle('Sheet1');
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx',__FILE__));
echo date('H:i:s') , " File written to " , str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME));exit();

}
?>
