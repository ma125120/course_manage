<?php
  header("Content-type: text/html; charset=utf-8");
  session_start();
  $_SESSION['username']=$_POST['username'];
  $_SESSION['id_level']=$_POST['id_level'];
  $_SESSION['real_name']=$_POST['real_name'];
  if($_SESSION['id_level']=='学生') {
?>

<!DOCTYPE html>
<html lang="en" >
<head >
  <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
  <meta name="msapplication-tap-highlight" content="no">
    <meta charset="UTF-8" >
    <title >学生管理界面</title >
    <link rel="stylesheet" href="css/common.css" >
    <style>input.col-6,select.col-6,.content_div {
      width:30rem;
    }
    label.col-2,label.col-3 {
      width:10rem;
    }
    input.col-8,button.col-8 {
      width:40.5rem;
    }</style>
    <script src="js/common/jq.js"></script >
    <script src="js/common/upload.js"></script >
    <script src="js/index.js"></script >
    <script src="js/common/common.js"></script >
</head >
<body >
<div id="student">
    <header>
        <nav>
            <ul id="teacher-ul" class='col-10'>
                <li data-id="edit_pwd_div">修改密码</li >
                <li data-id="query-course_lib">查询课程库</li>
                <li data-id="query-course_class">查询课程表</li>
                <li data-id="query_score_div">查询成绩</li>
                <li>师生互动
                    <ul class='hidden'>
                       <li data-id="send_mail">发送信件</li>
                       <li data-id="query_mail">查看信件</li>
                    </ul>
                </li >
                <li >下载专区
                         <ul class="hidden">
                            <li data-id="download_data">下载资料</li >
                            <li data-id="download_video">下载视频</li >
                         </ul>
                </li >
                <li data-id="query_notice_div">查看公告</li >
            </ul>
        </nav>
    </header>
    <main>
        <div class="container bg_gray">
          <div class="col-10 main_info" >
          <div id="download_video" class="hidden">
            <p class="login cir1 cir-12">下载视频</p>
            <form action="#" data-info='video'>
               <label class="col-2">类别：</label>
               <select name="dir_name1" class="col-6 cir dir_name1">
                    <option value="" >请选择：</option >
                    <option value="公共课任选课" >公共课任选课</option >
                    <option value="公共课必修课" >公共课必修课</option >
                    <option value="专业基础课限选课" >专业基础课限选课</option >
                    <option value="专业基础课必修课" >专业基础课必修课</option >
                    <option value="专业课限选课" >专业课限选课</option >
                    <option value="专业课必修课" >专业课必修课</option >
                 </select>
                  <br >
                  <label  class="col-2">科目：</label>
                  <select name="dir_name2" class="cir col-6 dir_name2 data" >
                  </select>
                  <span></span>
                  <hr >
                  <ol style="position:relative">
                    <div id="video_div">
                      <video id='video' controls="controls">
                      您的浏览器不支持 video 标签。
                      </video>
                    </div>
                  </ol>
                  <p class="more">加载更多</p>
              </form>
          </div>
            <div id='query_notice_div' class='hidden notice'>
                            <p class='login cir1 col-12'>查看公告</p>
                            <ol></ol>
                            <p class="more">加载更多</p>
             </div>
             <div id="query_score_div" class='hidden'>
                 <p class='login cir1 col-12'>查询成绩</p>
                 <form   method="post" data-info="score,query,0">
                    <label  class="col-2">开课学期：</label >
                    <select  name="start_term" class="col-6 cir start_term">
                    </select>
                    <span></span>
                    <br >
                    <input type="button" value="确认" class="col-8 cir submit">
                    <input type="reset" value="取消" class="col-8 cir">
                 </form >
                 <div class="print_div" data-info="score">

                </div>
                <button class="print_btn" >打印</button>
                <button class="to_excel_btn">导出为EXCEL</button>
            </div>
            <div id="download_data" class="hidden">
                <p class="login cir1 col-12">下载资料</p>
                            <form action="#" data-info='data_in'>
                               <label class="col-2">类别：</label>
                               <select name="dir_name1" class="col-6 cir dir_name1">
                                    <option value="" >请选择：</option >
                                    <option value="公共课任选课" >公共课任选课</option >
                                    <option value="公共课必修课" >公共课必修课</option >
                                    <option value="专业基础课限选课" >专业基础课限选课</option >
                                    <option value="专业基础课必修课" >专业基础课必修课</option >
                                    <option value="专业课限选课" >专业课限选课</option >
                                    <option value="专业课必修课" >专业课必修课</option >
                                 </select>
                                 <span></span>
                                  <br >
                                  <label for="" class="col-2">科目：</label>
                                  <select name="dir_name2" class="cir col-6 dir_name2 data" id="dir_name2">
                                  </select>
                                  <span></span>
                                  <br >
                                  <ol></ol>
                              </form>
                              <p class="more">加载更多</p>
                        </div>
                <div id="send_mail" class='hidden'>
                    <p class="login cir1 col-12">发送信件</p>
                    <form method="post" data-info="mail,add,0" >
                        <label  class="col-2">主题:</label >
                        <input type="text" name="subject" class="cir col-6" >
                        <br >
                        <label class="col-2" >内容:</label >
                        <div contenteditable="true" class="content_div cir" ></div>
                        <br >
                        <div class='edit_able'></div>
                        <label class="col-2" >发送至:</label >
                        <select  class="cir col-6 to_p" name="to_p">
                        </select>
                        <br >
                        <input type="button" class="submit  cir col-4" value="提交">
                        <input type="reset" class="col-4 cir" value='重置'>
                    </form >
                </div>
                <div id="query_mail" class='container'>
                    <div id='show_mail0' class='col-6 float_l col-mb-12'>
                        <p class='login cir1 '>未回复信件</p>
                        <ol id="show_mail_ol0">
                        </ol>
                        <p class="more">加载更多</p>
                    </div>
                    <div id='show_mail1' class='col-6 col-mb-12'>
                        <p class='login cir1'>已回复信件</p>
                        <ol id="show_mail_ol1">
                        </ol>
                        <p class="more">加载更多</p>
                    </div>
                </div>
                <div id="edit_pwd_div" class="hidden">
                    <p class="login cir1 col-12">修改密码界面</p>
                    <form class="user" data-info="user,edit,pwd"  method="post">
                            <label for="" class="col-2" >原始密码:</label >
                            <input name="old_pwd" class="col-6 cir pwd" type="password">
                            <span></span>
                            <br >
                            <label for="" class="col-2">修改密码:</label >
                            <input name="new_pwd" class="col-6 cir new_pwd" type="password">
                            <span></span>
                            <br >
                            <label for="" class="col-2">确认密码：</label >
                            <input name="new_pwd1" class="col-6 cir new_pwd1" type="password">
                            <span></span>
                            <br >
                            <input type="button" value="确认" class="col-8 cir submit">
                            <br >
                            <input type="reset" value="取消" class="col-8 cir">
                            <br >
                    </form >
                </div>
                <!--查询课程库界面-->
                <div id="query-course_lib" class="hidden">

                    <button class="query-course_lib">检索</button>
                    <button class="print_btn">打印</button>
                    <button class="to_excel_btn">导出为EXCEL</button>
                    <div class="print_div">
                    </div>
                </div>
                <div id="query-course_class" class="hidden">
                    <p class="login cir1 col-12">查询班级课表</p>
                    <form class="course_class" data-info="course_class,query,all" method="post">
                        <label for="" class="col-2">开课学期：</label >
                        <select  name="start_term" class="col-6 cir start_term">
                        </select>
                        <span></span>
                        <br >
                        <input type="button" class="submit" value="检索课表">
                    </form >
                    <div class="print_div"  data-info="course_class">

                    </div>
                    <button class="print_btn" >打印</button>
                    <button class="to_excel_btn">导出为EXCEL</button>
                </div>
            </div >
            <aside>
              <nav>
                <h1 style="background:#DEDEDE;color:#006ece"> 友情链接</h1>
                <ol>
                 <li><a href="http://www.nlc.cn" target="_blank">国家图书馆</a></li>
                 <li><a href="http://ziyuan.eol.cn/list.php?listid=690" target="_blank">国内科技网站</a></li>
                 <li><a href="http://ziyuan.eol.cn/list.php?listid=691" target="_blank">国外科技网站</a></li>
                 <li><a href="http://jwxt.tit.edu.cn/" target="_blank">教务处</a></li>
                 <li><a href="http://tygyxsc.tit.edu.cn/" target="_blank">学生处</a></li>
                 <li><a href="http://ytw.tit.edu.cn" target="_blank">团委</a></li>
                 <li><a href="http://gcxlzx.tit.edu.cn/" target="_blank">工程训练中心</a></li>
                 <li><a href="http://www.neea.edu.cn/" target="_blank">中国教育考试网</a></li>
                 <li><a href="http://www.ccert.edu.cn/" target="_blank">网络安全服务</a></li>
                 <li><a href="http://source.eol.cn/zyzx/gjzy.htm" target="_blank">大学网络课程</a></li>
                 <li><a href="http://souky.eol.cn/" target="_blank">考研参考系统</a></li>
                 <li><a href="http://www.cernet2.edu.cn/" target="_blank">下一代互联网</a></li>
                 <li><a href="http://kaoyan.eol.cn/" target="_blank">中国教育在线考研</a></li>
                 <li><a href="http://www.sxedu.gov.cn/" target="_blank">山西教育厅</a></li>
                 <li><a href="http://www.sxbys.com.cn/" target="_blank">山西毕业生就业信息网</a></li>
                 <li><a href="http://www.chsi.com.cn/" target="_blank">高等教育学生信息网</a></li>
                </ol>
              </nav>
            </aside>
        </div>
     <form action='link.php' method='post' id='pre_form' class='hidden' target="_blank"></form>

    </main>
</div>
<?php  echo '<div class="info p_info"><span class="id_level">'.$_SESSION['id_level'].':</span>[<span class="s_username">'
      .$_SESSION['username'].'</span>]<span class="s_real_name">'
      .$_SESSION['real_name'].'</span><span class="time_info"></span></div>';
  ?>
  <footer>
    <span class="col-2">太原工业学院</span>
    <span class="col-5">地址：山西省太原市尖草坪区新兰路31号</span>
    <span class="col-2">邮编：030008 </span>
    <p >Copyright  @  All  Right </p>
  </footer>
</body >
</html >
<?php  } ?>
