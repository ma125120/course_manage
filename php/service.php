<?php
    header("Content-type: text/html; charset=utf-8");
    error_reporting(0);
    const MAX_COUNT=10;
    $dbc=mysqli_connect('localhost','root','125120','test') or die('发生错误');
    mysqli_query($dbc,'SET NAMES UTF8');

    if($_FILES) {
    date_default_timezone_set('PRC');
    $m=date(Y.m.d);
    $action=$_POST['action'];
    $name_val=$_POST['name_val'];
    $dir_name1=$_POST['dir_name1'];
    $dir_name2=$_POST['dir_name2'];
    $upload_user=$_POST['upload_user'];
    if($action=='upload') {
            $name_rea=$_FILES[$name_val]['name'];
            $dir=end(explode(".",$name_rea));
            if($dir=='pdf'||$dir=='mp4'||$dir=='ogv') {
                switch($name_val) {
                  case 'data_in':$name=iconv("utf-8","gbk","../upload/".$dir_name1."/".$dir_name2."/".$name_rea);
                      if(file_exists($name)) {
                        echo '服务器已存在该文件，请重新上传';
                      }
                      else {
                            $ok=move_uploaded_file($_FILES['data_in']['tmp_name'],$name);
                                 if($ok === FALSE)
                                   echo '上传失败';
                                else{
                                  echo '上传成功';
                                  $type=0;
                                  if($dir!='pdf')  $type=1;
                                  $query="INSERT INTO data_info(data_name,store_time,upload_user,dir_name1,dir_name2,type)"
                                        ." VALUES('$name_rea',NOW(),'$upload_user','$dir_name1','$dir_name2','$type')";
                                }
                       }
                    break;
                  case 'notice_in':
                   if($dir=='pdf') {
                    $n_subject=$_POST['n_subject'];
                    $n_person=$_POST['n_person'];$na=$m.$name_rea;
                    $name=iconv("utf-8","gbk","../upload/notice/".$m.$name_rea);
                    if(file_exists($name)) {
                      echo '服务器已存在该文件，请重新上传';mysqli_close($dbc);exit();
                    }

                    else {
                        $ok=move_uploaded_file($_FILES['notice_in']['tmp_name'],$name);
                         if($ok === FALSE)
                            echo '上传失败';
                        else{
                           echo '上传成功';
                           $query="INSERT INTO notice(n_subject,n_content,n_person,n_time)"
                                ." VALUES('$n_subject','$na','$n_person',NOW())";
                              }
                         }
                  }
                    break;
                }
            $result= mysqli_query($dbc,$query) or die('提交错误，请检查');
            }
            else echo '请上传PDF文件或mp4,ogv文件';
            mysqli_close($dbc);exit();

          }

     else {
        require_once 'Classes/PHPExcel.php';
        $name=$_FILES[$name_val]['name'];
        $names=$_FILES[$name_val]['tmp_name'];
        $name=iconv("UTF-8","GBK","$name");
        $dirname=end(explode(".",$name));
        if($dirname!='xlsx'&&$dirname!='xls')  exit("请上传正确的EXCEL");
        else {
        if($dirname=='xlsx')
            $reader = PHPExcel_IOFactory::
            createReader('Excel2007'); //设置以Excel5格式(Excel97-2003工作簿)
        if($dirname=='xls')
                    $reader = PHPExcel_IOFactory::createReader('Excel5');
        $PHPExcel = $reader->load($names); // 载入excel文件
        $sheetCount = $PHPExcel->getSheetCount();
        if($action=='user')
            $query="REPLACE INTO user(username,id_level,real_name,id_card) VALUES";
        if($action=='course_lib')
            $query="INSERT INTO course_lib(course_name,credit,total_hours,teach_hour,type,teach_type,test_mode,week_time) VALUES";
        if($action=='course_class') {
            $query="REPLACE INTO course_class(course_id,course_teacher,course_dept,start_term,course_time,course_room) VALUES";
            $j=1;
        }
        if($action=='score') {
            $sheet = $PHPExcel->getSheet(0);
            $start_term = $sheet->getCell(A2)->getValue();
            $course_name=$sheet->getCell(B2)->getValue();
            $teacher=$_POST['teacher'];
            $qu="SELECT course_time FROM course_lib a JOIN course_class WHERE course_teacher='$teacher' AND start_term='$start_term'".
                " AND a.course_id=(SELECT course_id FROM course_lib WHERE course_name='$course_name')";
            $result= mysqli_query($dbc,$qu) or die('提交错误，请检查');
            $result=mysqli_fetch_array($result,MYSQLI_ASSOC);
            if($result||$teacher=='管理员') {
                $query="REPLACE INTO score(start_term,course_name,username,peace_score,peace_per,final_score,final_per) VALUES";
            }
            else {echo '你没有录入该课程成绩的权限';exit();}
        }
        $j=$j?$j:0;
    for($i=0;$i<$sheetCount;$i++) {
        $sheet = $PHPExcel->getSheet($i); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestCol = $sheet->getHighestColumn(); // 取得总列数
        for ($row = 2; $row <= $highestRow; $row++){//行数是以第2行开始
            if($row==2&&$i==0)  $query.="(";
             else $query.=",(";
                for ($col='A';$col<=$highestCol;$col++) {//列数是以A列开始
                   $dataset = $sheet->getCell($col.$row)->getValue();
            if($col!='A') $query.=",";
            if($col=='A'&&$j==1)
              $query.="(SELECT course_id from course_lib WHERE course_name='$dataset')";
            else
                $query.="'$dataset'";
                }
            $query.=")";
        }
    }
        $result= mysqli_query($dbc,$query) or die('提交错误，请检查');
        if($result)   echo '成功';
                    else echo '失败';
                    mysqli_close($dbc,$query);exit();
        }

     }
    }

  if($_POST)	 {
   $action=$_POST['action'];
   $action_type=$_POST['action_type'];
   $action_condition=$_POST['action_condition'];
   if($action=='score') {
        $username=$_POST['username'];
        $len=strlen($username);
        $course_name=$_POST['course_name'];
        $start_term=$_POST['start_term'];
        if($action_type=='query') {
            $query="SELECT *FROM score WHERE LEFT(username,$len)='$username' AND start_term='$start_term' ";
            if($len!=9)    $query.=" AND  course_name='$course_name'";
            $result=mysqli_query($dbc,$query) or die('查询错误，请检查');
            $scores=array();
            while($score=mysqli_fetch_array($result,MYSQLI_ASSOC))	{
                $c_name1=$score['course_name'];
                $query="SELECT credit,type,test_mode FROM course_lib WHERE course_name='$c_name1'";
                $result1=mysqli_query($dbc,$query) or die('查询错误，请检查');
                $row=mysqli_fetch_array($result1,MYSQLI_ASSOC);
                $name=$score['username'];
                $query2="SELECT real_name FROM user WHERE username='$name'";
                $result2=mysqli_query($dbc,$query2) or die('查询错误，请检查');
                $row1=mysqli_fetch_array($result2,MYSQLI_ASSOC);
                array_push($scores,array('start_term'=>$score['start_term'],
                             'course_name'=>$score['course_name'],
                             'username'=>$score['username'],
                             'peace_score'=>$score['peace_score']*$score['peace_per'],
                             'final_score'=>$score['final_score']*$score['final_per'],
                             'credit'=>$row['credit'],
                             'real_name'=>$row1['real_name'],
                             'type'=>$row['type'],
                             'test_mode'=>$row['test_mode'],
                             'point'=>(round($score['peace_score']*$score['peace_per']+$score['final_score']*$score['final_per'])-50)/10,
                             'total_score'=>round($score['peace_score']*$score['peace_per']+$score['final_score']*$score['final_per'])
                ));
            }
            echo	 json_encode(array("scores"=>$scores),JSON_UNESCAPED_UNICODE);
            mysqli_close($dbc);exit();
        }
   }
   if($action=='dir_name') {
        $dir_name1=$_POST['dir_name1'];
        $dir_name2=$_POST['dir_name2'];
        $dirname="../upload/".$dir_name1."/".$dir_name2;
        $dirnames=iconv("UTF-8","GBK",$dirname);
        mkdir($dirnames);
        if($action_type=='add') {
            $query="INSERT INTO dir_name(dir_name1,dir_name2) VALUES('$dir_name1','$dir_name2')";
        }
        if($action_type=='query') {
            $query="SELECT dir_name2 from dir_name WHERE dir_name1='$dir_name1'";
            $result=mysqli_query($dbc,$query) or die('查询错误，请检查');
            $dir_names=array();
            while($dir_name=mysqli_fetch_array($result,MYSQLI_ASSOC))	{
                 array_push($dir_names,array('dir_name2'=>$dir_name['dir_name2']));
            }
           echo	 json_encode(array("dir_names"=>$dir_names),JSON_UNESCAPED_UNICODE);
           mysqli_close($dbc);exit();
        }
        $result= mysqli_query($dbc,$query) or die('提交错误，请检查');
        if(mysqli_affected_rows($dbc)) echo '成功';
              else echo '失败';exit();
   }
   if($action=='mail') {
        $subject=$_POST['subject'];
        $content=$_POST['n_content'];
        $to_p=$_POST['to_p'];
        $from_p=$_POST['from_p'];
        $reply=$_POST['reply'];
        $mail_id=$_POST['mail_id'];
        $flag=$_POST['flag'];
        if($action_type=='add') {
            $query="INSERT INTO mail(from_p,to_p,subject,content,send_time) VALUES('$from_p','$to_p','$subject','$content',NOW())";
        }
        if($action_type=='edit') {
            if($action_condition=='s_flag')
                $query="UPDATE mail SET s_flag=1 WHERE mail_id='$mail_id'";
            if($action_condition=='reply')
              $query="UPDATE mail SET reply='$reply',reply_time=NOW(),reply_flag=1 WHERE mail_id='$mail_id'";
        }
        if($action_type=='query') {
	        $query1="SELECT *FROM mail WHERE reply_flag='$flag' AND (from_p='$from_p' or to_p='$to_p')";
	        $query2=" ORDER BY mail_id DESC LIMIT ".MAX_COUNT;
	        $query=$query1.$query2;
	        if($mail_id)
	            $query=$query1." AND mail_id<'$mail_id'".$query2;
            $result=mysqli_query($dbc,$query) or die('查询错误，请检查');
            $mails=array();
            while($mail=mysqli_fetch_array($result,MYSQLI_ASSOC))	{
                          array_push($mails,array('content'=>$mail['content'],
                                                   'mail_id'=>$mail['mail_id'],
                                              	  'subject'=>$mail['subject'],
                                              	    'to_p'=>$mail['to_p'],
                                              	    'reply'=>$mail['reply'],
                                              	    'reply_time'=>$mail['reply_time'],
                                              	    'from_p'=>$mail['from_p']));
                                              	  }
           echo	 json_encode(array("mails"=>$mails),JSON_UNESCAPED_UNICODE);
           mysqli_close($dbc);exit();
        }
        $result= mysqli_query($dbc,$query) or die('提交错误，请检查');
        if(mysqli_affected_rows($dbc)) echo '成功';
              else echo '失败';exit();
   }
    //编辑公告
   if($action=='notice')  {
        $n_subject=$_POST['n_subject'];
        $n_content=trim($_POST['n_content']);
        $n_person=$_POST['n_person'];
        $n_id=$_POST['n_id'];
        $flag=$_POST['flag'];
        if($action_type=='add')
            $query="INSERT INTO  notice(n_subject,n_content,n_person,n_time) VALUES('$n_subject','$n_content','$n_person',NOW())";
        if($action_type=='query') {
           $query="SELECT *FROM notice ORDER BY n_id DESC LIMIT  ".MAX_COUNT;
           if($n_id) {
             $query="SELECT *FROM notice WHERE n_id<'$n_id' ORDER BY n_id DESC LIMIT ".MAX_COUNT;
           }
        $result=mysqli_query($dbc,$query) or die('查询错误，请检查');
            $notices=array();
            while($notice=mysqli_fetch_array($result,MYSQLI_ASSOC))	{
                              array_push($notices,array('n_content'=>$notice['n_content'],
                                         'n_id'=>$notice['n_id'],
                                  	     'n_person'=>$notice['n_person'],
                                  	     'n_subject'=>$notice['n_subject'],
                                  	     'n_time'=>$notice['n_time']));
                                  	  }
        echo	 json_encode(array("notices"=>$notices),JSON_UNESCAPED_UNICODE);
        mysqli_close($dbc);exit();
        }
        $result= mysqli_query($dbc,$query) or die('公告提交错误，请检查');
        if(mysqli_affected_rows($dbc)) echo '成功';
              else echo '更新失败，请检查信息';exit();
    }
    //用户表
    if($action=='user') {
        $username=$_POST['username'];
        $old_pwd=$_POST['old_pwd'];
        $pwd1=$_POST['new_pwd'];
        $username=$_POST['username'];
        $len=strlen($username);
        $id_level=$_POST['id_level'];
        $real_name=$_POST['real_name'];
        $password=$_POST['password'];
        $id_card=$_POST['id_card'];
        if($action_type=='add') {
            $query="INSERT  INTO user(username,id_level,real_name,id_card) VALUES('$username','$id_level','$real_name','$id_card')";
        }
        if($action_type=='delete') {
            if($action_condition=='single')
                 $query="DELETE FROM  user WHERE username='$username' LIMIT 1";
            if($action_condition=='class')
                 $query="DELETE FROM  user WHERE LEFT(username,$len)='$username'";
        }
        if($action_type=='edit') {
            if($action_condition=='single')
                $query="UPDATE user SET real_name='$real_name',id_level='$id_level',id_card='$id_card' WHERE username='$username'";
            if($pwd1==$old_pwd) {
                echo '新密码与旧密码一致，请重新填写';exit();
            }  else {
              if($action_condition=='pwd') {
                  $query="UPDATE user SET password=MD5('$pwd1') WHERE username='$username' AND password=MD5('$old_pwd') LIMIT 1";
              }
              if($action_condition=='reset')  {
                  $query="UPDATE user SET password=MD5('$pwd1') WHERE username='$username' AND real_name='$real_name' AND id_card='$id_card' LIMIT 1";
              }
            }
            $result=mysqli_query($dbc,$query) or die('提交错误，请检查');
            if(mysqli_affected_rows($dbc)) echo '密码更新成功';
                    else echo '更新失败，请检查信息';exit();
            }

        if($action_type=='query') {
            if($action_condition=='teacher')
                 $query="SELECT real_name FROM user WHERE id_level='教师'";
            if($action_condition=='single')
                 $query="SELECT username,id_level,id_card,real_name FROM user WHERE username='$username'";
            if($action_condition=='class')
                 $query="SELECT username,id_level,id_card,real_name FROM  user WHERE LEFT(username,$len)='$username'";
           if($action_condition=='login')
                 $query="SELECT username,id_level,id_card,real_name FROM  user WHERE username='$username' AND password=MD5('$password')"
                        ."  AND id_level='$id_level'";
            $query.="ORDER BY username ";
            $result=mysqli_query($dbc,$query) or die('提交错误，请检查');
            $users=array();
            while($user=mysqli_fetch_array($result,MYSQLI_ASSOC))	{
                            array_push($users,array('username'=>$user['username'],
                                       'id_level'=>$user['id_level'],'id_card'=>$user['id_card'],
                                       'real_name'=>$user['real_name']
                                                                ));
                               }
            echo	 json_encode(array("users"=>$users),JSON_UNESCAPED_UNICODE);
            mysqli_close($dbc); exit();
        }
        $result=mysqli_query($dbc,$query) or die('提交错误，请检查');
        if(mysqli_affected_rows($dbc)) echo '成功';
              else echo '失败';exit();
    }
    //课程库相关功能
    if($action=='course_lib') {
        $course_name=$_POST['course_name'];
        $credit=$_POST['credit'];
        $total_hours=$_POST['total_hours'];
        $teach_hour=$_POST['teach_hour'];
        $type=$_POST['type'];
        $test_mode=$_POST['test_mode'];
        $week_time=$_POST['week_time'];
        $action_type=$_POST['action_type'];
        $teach_type=$_POST['teach_type'];
        if($action_type=='add') {
            $query="INSERT INTO  course_lib(course_name,credit,total_hours,teach_hour,
            type,test_mode,week_time,teach_type)
                    VALUES('$course_name','$credit','$total_hours','$teach_hour','$type',
                    '$test_mode','$week_time','$teach_type')";
        }
        if($action_type=='delete') {
            $query="DELETE FROM course_lib WHERE type='".$type."' AND course_name='".$course_name."'";
        }
        if($action_type=='edit')   {
            $query="UPDATE course_lib
                    SET course_name='$course_name',credit='$credit',total_hours='$total_hours',teach_type='$teach_type'".
                   ",teach_hour='$teach_hour',type='$type',test_mode='$test_mode',week_time='$week_time'".
                    "WHERE type='".$type."' AND course_name='".$course_name."'";
        }
        if($action_type=='query') {
            if($action_condition=='0') {
                $query="SELECT *FROM course_lib WHERE type='$type'";
            }
            if($action_condition=='1') {
                $query="SELECT *FROM course_lib";
            }
            if($action_condition=='2') {
                 $query="SELECT *FROM course_lib WHERE course_name='$course_name'";
            }
            $result=mysqli_query($dbc,$query) or die('查询错误，请检查');
            $course_libs=array();
            while($course_lib=mysqli_fetch_array($result,MYSQLI_ASSOC))	{
                   array_push($course_libs,array('course_name'=>$course_lib['course_name'],
                              'credit'=>$course_lib['credit'],
                              'total_hours'=>$course_lib['total_hours'],
                              'teach_hour'=>$course_lib['teach_hour'],
                               'type'=>$course_lib['type'],
                               'teach_type'=>$course_lib['teach_type'],
                               'test_mode'=>$course_lib['test_mode'],
                                'week_time'=>$course_lib['week_time']
                                     	    ));
                                     	  }
            echo	 json_encode(array("course_libs"=>$course_libs),JSON_UNESCAPED_UNICODE);
           mysqli_close($dbc); exit();
        }
       $result= mysqli_query($dbc,$query) or die('提交错误，请检查');
       if(mysqli_affected_rows($dbc))   echo '成功';
            else echo '失败';
     }
     //上传文件相关信息
     if($action=='data_info') {
         $data_id=$_POST['data_id'];
         $flag=$_POST['flag'];
         $type=$_POST['type'];
         $dir_name1=$_POST['dir_name1'];
         $dir_name2=$_POST['dir_name2'];
        if($action_type=='query') {
            $query="SELECT *FROM data_info WHERE dir_name1='$dir_name1' "
                ." AND dir_name2='$dir_name2' AND type='$type' ";
            if($flag)  $query.=" AND data_id<'$data_id'";
            $query.=" ORDER BY data_id DESC LIMIT ".MAX_COUNT;
            $result= mysqli_query($dbc,$query) or die('查询出错，请检查');
            $data_infos=array();
            while($data_info=mysqli_fetch_array($result,MYSQLI_ASSOC))	{
                  array_push($data_infos,array('data_name'=>$data_info['data_name'],'upload_user'=>$data_info['upload_user'],
                    'data_id'=>$data_info['data_id'],'store_time'=>$data_info['store_time'],
                    'dir_name1'=>$data_info['dir_name1'],'dir_name2'=>$data_info['dir_name2']
                            	  ));
            }
           echo json_encode(array("data_infos"=>$data_infos),JSON_UNESCAPED_UNICODE);
        }
     }
     //课程表相关功能
     if($action=='course_class') {
            $course_name=$_POST['course_name'];
            $course_time=$_POST['course_time'];
            $course_room=$_POST['course_room'];
            $course_dept=$_POST['course_dept'];
            $cid=$_POST['cid'];
            $course_teacher=$_POST['course_teacher'];
            $start_term=$_POST['start_term'];
            if($action_type=='add') {
                $query="INSERT INTO course_class(course_time,course_room,course_dept,course_teacher,start_term,course_id)"
                        ."VALUES('$course_time','$course_room','$course_dept','$course_teacher',
                        '$start_term',(SELECT course_id FROM course_lib WHERE course_name='$course_name'))";
            }
            if($action_type=='delete') {
                $query="DELETE FROM course_class WHERE course_cid='$cid' LIMIT 1";
                $result= mysqli_query($dbc,$query) or die('提交错误，请检查');
                if(mysqli_affected_rows($dbc))   echo '成功';
                     else echo '数据库中并不存在该班级的该课程表，请检查输入';
            }
            if($action_type=='edit')  {
                $query="UPDATE course_class SET course_time='$course_time',start_term='$start_term',"
                        ."course_teacher='$course_teacher',course_room='$course_room',course_dept='$course_dept'"
                        ." WHERE course_cid='$cid' LIMIT 1";
            }
            if($action_type=='query') {
                switch($action_condition) {
                  case '1':$query="SELECT *FROM course_class WHERE course_dept LIKE '%$course_dept%' AND course_id=(SELECT course_id FROM course_lib WHERE course_name='$course_name')";
                            break;
                  case 'all':$query="SELECT *FROM course_class a JOIN course_lib b WHERE a.course_dept LIKE '%$course_dept%' AND a.start_term='$start_term' AND a.course_id=b.course_id ORDER  BY course_time";
                                                         break;
            }
                $result= mysqli_query($dbc,$query) or die('提交的数据有冲突，请检查');
                $course_classs=array();
                while($course_class=mysqli_fetch_array($result,MYSQLI_ASSOC))	{
                	  array_push($course_classs,array('course_time'=>$course_class['course_time'],
                	            'course_room'=>$course_class['course_room'],'start_term'=>$course_class['start_term'],
                	            'course_teacher'=>$course_class['course_teacher'],
                	            'course_name'=>$course_class['course_name'],
                              'credit'=>$course_class['credit'],'course_dept'=>$course_class['course_dept'],
                                'total_hours'=>$course_class['total_hours'],
                                'teach_hour'=>$course_class['teach_hour'],
                                'type'=>$course_class['type'],'course_cid'=>$course_class['course_cid'],
                                'teach_type'=>$course_class['teach_type'],
                                'test_mode'=>$course_class['test_mode'],
                                'week_time'=>$course_class['week_time']
                	  ));
                	  }
                echo
                	 json_encode(array("course_classs"=>$course_classs),JSON_UNESCAPED_UNICODE);
                	mysqli_close($dbc);exit();
            }
           $result= mysqli_query($dbc,$query) or die('提交错误，请检查');
           if(mysqli_affected_rows($dbc))   echo '成功';
                else echo '失败';
     }
    if($action=='del') {
        $del_val="../".$_POST['del_val'];
        if($action_type=='data_info') {
            unlink(iconv("utf-8","gbk",$del_val));
            $query="DELETE FROM $action_type WHERE data_id='$action_condition'";
        }
        if($action_type=='notice') {
                    $query="DELETE FROM notice WHERE n_id='$action_condition'";
                }
        $result= mysqli_query($dbc,$query) or die('提交错误，请检查');
        if(mysqli_affected_rows($dbc)) echo '成功';
              else echo '失败';exit();
    }
}
    mysqli_close($dbc);
         exit();
?>
