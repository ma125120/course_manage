<?php
  header("Content-type: text/html; charset=utf-8");
  error_reporting(0);
  session_start();
  $_SESSION['username']=$_POST['username'];
  $_SESSION['id_level']=$_POST['id_level'];
  $_SESSION['real_name']=$_POST['real_name'];
  if($_SESSION['id_level']=='教师'||$_SESSION['id_level']=='管理员') {
?>
<!DOCTYPE html>
<html>
<head >  
    <meta charset="UTF-8" >
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="msapplication-tap-highlight" content="no">
    <title >教师管理界面</title >
    <link rel="stylesheet" href="css/common.css" >
    <style>input.col-6,select.col-6 {
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
    <script src="js/teacher.js"></script >
    <script src="js/common/common.js"></script >
</head >
<body >
<div id="teacher">
    <header>
        <nav>
            <ul id="teacher-ul" class='col-10'>
                <li>账户管理
                    <ul class="hidden" style="width:76px;">
                        <li data-id="edit_pwd_div">修改密码</li >
    <?php if($_SESSION['id_level']=='管理员') { ?>  <li data-id="add_user_div">增加用户</li >
                        <li data-id="edit_user_div">编辑用户</li >
                        <li data-id="delete_user_div">删除用户</li >
                        <li data-id="query_user_div">查询用户</li > <?php } ?>
                    </ul>
                </li>
                <li>
                    课程库管理
                    <ul class="hidden">
                        <li data-id="add-course_lib">添加课程库</li >
                        <li data-id="delete-course_lib">删除课程库</li >
                        <li data-id="edit-course_lib">编辑课程库</li >
                        <li data-id="query-course_lib">查询课程库</li>
                    </ul>
                </li>
                <li>
                    课程表管理
                    <ul class="hidden">
                        <li data-id="add-course_class">添加课程表</li >
                        <li data-id="delete-course_class">删除课程表</li >
                        <li data-id="edit-course_class">编辑课程表</li >
                        <li data-id="query-course_class">查询课程表</li>
                    </ul>
                </li>
                <li >成绩管理
                    <ul class="hidden">
                        <li data-id="import_score_div">录入成绩</li >
                        <li data-id="query_score_div">查看成绩</li >
                    </ul>
                </li >
                <li>
                    课程资料
                    <ul class="hidden" style="width:76px;">
<?php if($_SESSION['id_level']=='管理员') { ?>    <li data-id="new_dir">新建目录</li > <?php } ?>
                        <li data-id="upload_data">上传资料</li >
                    </ul>
                </li>
                <li >下载专区
                         <ul class="hidden">
                            <li data-id="download_data">下载资料</li >
                            <li data-id="download_video">下载视频</li >
                         </ul>
                </li >
                <li >公告管理
                         <ul class="hidden">
                            <li data-id="notice-div">发布公告</li >
                            <li data-id="query_notice_div">查看公告</li >
                         </ul>
                </li >
                <li data-id="import-excel-div">导入EXCEL</li >
                <li data-id="query_mail">师生互动</li >
            </ul>
        </nav>
    </header>
 <main>
    <div class="container">
        <div class="col-11 main_info" >
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
                      <ol style="position:realtive">
                        <div id="video_div">
                          <video id='video' controls="controls">
                          您的浏览器不支持 video 标签。
                          </video>
                        </div>
                      </ol>
                      <p class="more">加载更多</p>
                  </form>
              </div>
            <div id="import_score_div" class='hidden'>
                <p class='login cir1 col-12'>录入成绩</p>
                <form  data-info="score"  method="post">
                    <label>请选择成绩EXCEL：</label><br>
                    <input id="import_score" type="file" name="import_score" >
                    <input type="button" value="提交" class="import_btn" data-info='score,import_score'>
                </form >
            </div>
            <div id="query_score_div" class='hidden'>
                <p class='login cir1 col-12'>查询成绩</p>
                <form    method="post" data-info="score,query,0">
                        <label  class="col-2">班级或学号:</label >
                        <input type='text' name='username' class='cir col-6 username' id='score_user'>
                        <br >
                        <label  class="col-2">开课学期：</label >
                        <select  name="start_term" class="col-6 cir start_term">
                        </select>
                        <span></span>
                        <br >
                        <label  class="col-2" >类型:</label >
                        <select name="type" class="col-6 cir type" >
                            <option value="" >请选择：</option >
                            <option value="公共课/任选课" >公共课/任选课</option >
                            <option value="公共课/必修课" >公共课/必修课</option >
                            <option value="专业基础课/限选课" >专业基础课/限选课</option >
                            <option value="专业基础课/必修课" >专业基础课/必修课</option >
                            <option value="专业课/限选课" >专业课/限选课</option >
                            <option value="专业课/必修课" >专业课/必修课</option >
                        </select >
                        <span></span>
                        <br >
                        <label  class="col-2">课程名称:</label >
                        <select type="text" name="course_name" class="col-6 cir course_name" >
                            <option value="" >请选择：</option >
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
        <?php if($_SESSION['id_level']=='管理员') { ?>
            <div id="new_dir" class="hidden">
                <p class="login cir1 col-12">新建目录</p>
                <form action="#" method="post" data-info="dir_name,add,0">
                    <label class="col-2">类别：</label>
                    <select name="dir_name1" class="col-6 cir">
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
                    <input type="text" name="dir_name2" class="cir col-6" placeholder="输入中不得含有标点符号">
                    <span></span>
                    <br >
                    <input type="button" value="确认" class="col-8 cir submit">
                    <br >
                    <input type="reset" value="取消" class="col-8 cir">
                    <br >
                </form>
            </div>
<?php } ?>
            <div id='query_notice_div' class='hidden notice'>
                <p class='login cir1 col-12'>查看公告</p>
                <ol>

                </ol>
                <p class="more">加载更多</p>
            </div>
            <div id="upload_data" class="hidden" >
                <p class=" login cir1 col-12">上传资料</p>
                <form action="#" method="post" data-info="data_info,add,0">
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
                      <select name="dir_name2" class="cir col-6 dir_name2">
                      </select>
                      <span></span>
                      <br >
                      <input id="data_in" type="file" name="data_in" >
                      <input type="button" value="提交" id="upload_data_btn">
                  </form>
            </div >
            <div id="download_data" class="hidden">
                <p class='login cir1 col-12'>下载资料</p>
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
                      <br >
                      <label for="" class="col-2">科目：</label>
                      <select name="dir_name2" class="cir col-6 dir_name2 data" >
                      </select>
                      <hr >
                      <ol></ol>
                      <p class="more">加载更多</p>
                  </form>
            </div>
            <div id="query_mail" class='container'>
                <div id='show_mail0' class='col-6 float_l col-mb-12'>
                    <p class='login cir1 cir-12'>未回复信件</p>
                    <div class='edit_able'></div><hr>
                    <ol id="show_mail_ol0">
                    </ol>
                    <p class="more">加载更多</p>
                </div>
                <div id='show_mail1' class='col-6 col-mb-12'>
                    <p class='login cir1 cir-12'>已回复信件</p>
                    <ol id="show_mail_ol1">
                    </ol>
                    <p class="more">加载更多</p>
                </div>
            </div>
            <div id="edit_pwd_div" class="hidden">
                <p class='login cir1 cir-12'> 修改密码界面</p>
                <form class="user" data-info="user,edit,pwd"  method="post">
                        <label for="" class="col-2" >原始密码:</label >
                        <input name="old_pwd" class="col-6 cir pwd" type="password" placeholder="请输入您的原始密码">
                        <span></span>
                        <br >
                        <label for="" class="col-2">修改密码:</label >
                        <input name="new_pwd" class="col-6 cir new_pwd" type="password" placeholder="请输入您的重置密码">
                        <span></span>
                        <br >
                        <label for="" class="col-2">确认密码：</label >
                        <input name="new_pwd1" class="col-6 cir new_pwd1" type="password" placeholder="请输入您的重置密码">
                        <span></span>
                        <br >
                        <input type="button" value="确认" class="col-8 cir submit">
                        <br >
                        <input type="reset" value="取消" class="col-8 cir">
                        <br >

                </form >
            </div>
            <div id="add_user_div" class="hidden">
                <p class='login cir1 cir-12'> 增加用户界面</p>
                <form class="user" data-info="user,add,pwd"  method="post">
                        <label for="" class="col-2">身份级别:</label >
                        <select name="id_level" class="col-6 cir id_level">
                            <option value="学生" >学生</option >
                            <option value="教师" >教师</option >
                            <option value="管理员" >管理员</option >
                        </select>
                        <span></span>
                        <br >
                        <label for="" class="col-2" >用户名:</label >
                        <input name="username" class="col-6 cir username" type="text" placeholder="请输入用户名，如学号和职工号">
                        <span></span>
                        <br >

                        <label for="" class="col-2">真实姓名：</label >
                        <input type="text" class="col-6 cir real_name" name="real_name" placeholder="请输入该用户的真实姓名">
                        <span></span>
                        <br >
                        <label  class="col-2">身份证：</label >
                        <input type="text" class="col-6 cir id_card" name="id_card" placeholder="请输入该用户的身份证">
                        <span></span>
                        <br >
                        <input type="button" value="确认" class="col-8 cir submit">
                        <br >
                        <input type="reset" value="取消" class="col-8 cir">
                        <br >
                </form >
            </div>
            <div id="edit_user_div" class="hidden">
                <p class='login cir1 cir-12'> 编辑用户界面</p>
                <form class="user" data-info="user,edit,single"  method="post" >
                        <label for="" class="col-2">身份级别:</label >
                        <select name="id_level" class="col-6 cir id_level" id="edit_id">
                            <option value="学生" >学生</option >
                            <option value="教师" >教师</option >
                            <option value="管理员" >管理员</option >
                        </select>
                        <span></span>
                        <br >
                        <label for="" class="col-2" >用户名:</label >
                        <input name="username" class="col-6 cir pwd q_user" type="text" placeholder="请输入用户名，如学号和职工号">
                        <span></span>
                        <br >
                        <label for="" class="col-2">真实姓名：</label >
                        <input type="text" class="col-6 cir real_name" name="real_name" placeholder="请输入该用户的真实姓名">
                        <span></span>
                        <br >
                        <label  class="col-2">身份证：</label >
                        <input type="text" class="col-6 cir id_card" name="id_card" placeholder="请输入该用户的身份证">
                        <span></span>
                        <br >
                        <input type="button" value="确认" class="col-8 cir submit">
                        <br >
                        <input type="reset" value="取消" class="col-8 cir">
                        <br >
                </form >
            </div>
            <div id="delete_user_div" class="hidden">
                <p class='login cir1 cir-12'> 删除用户界面</p>
                <form class="user" data-info="user,delete,single"  method="post">
                        <label  class="col-2" >用户名:</label >
                        <input name="username" class="col-6 cir pwd" type="text"  placeholder="请输入用户名，如学号和职工号">
                        <span></span>
                        <br >
                        <input type="button" value="确认" class="col-8 cir submit">
                        <br >
                        <input type="reset" value="取消" class="col-8 cir">
                        <br >
                    </fieldset>
                </form >
                <p class='login cir1 cir-12'> 删除专业界面</p>
                <form class="user" data-info="user,delete,class"  method="post">
                        <label for="" class="col-2" >输入专业或班级:</label >
                        <input name="username" class="col-6 cir pwd" type="text"  placeholder="专业名为6位数，班级为7位数">
                        <span></span>
                        <br >
                        <input type="button" value="确认" class="col-8 cir submit">
                        <br >
                        <input type="reset" value="取消" class="col-8 cir">
                        <br >
                </form >
            </div>
            <div id="query_user_div" class="hidden">
                <p class='login cir1 cir-12'> 查询用户界面</p>
                <form class="user" data-info="user,query,single"  method="post">
                        <label for="" class="col-2" >用户名:</label >
                        <input name="username" class="col-6 cir pwd" type="text"  placeholder="请输入用户名，如学号和职工号">
                        <span></span>
                        <br >
                        <input type="button" value="确认" class="col-8 cir submit">
                        <br >
                        <input type="reset" value="取消" class="col-8 cir">
                        <br >
                    </fieldset>
                </form >
                <form class="user" data-info="user,query,class"  method="post">
                    <p class='login cir1 cir-12'> 查询专业界面</p>
                        <label for="" class="col-2" >输入专业或班级:</label >
                        <input name="username" class="col-6 cir pwd" type="text"  placeholder="专业为6位数，班级为7位数">
                        <span></span>
                        <br >
                        <input type="button" value="确认" class="col-8 cir submit">
                        <br >
                        <input type="reset" value="取消" class="col-8 cir">
                        <br >
                </form >
                <div class="print_div" data-info="user">

                </div>
                <button class="print_btn" >打印</button>
                <button class="to_excel_btn">导出为EXCEL</button>
            </div>
            <div id="notice-div" class="hidden">
                <p class='login cir1 col-12'>发布公告</p>
                <form action="#" data-info="notice,add,all"  method="post">
                    <label  class="col-3">主题:</label >
                    <input type="text" name="n_subject" class="cir col-8 n_subject"  placeholder="请输入主题，不要超过100字">
                    <br >
                    <label  class="col-3">内容:</label >
                    <input id="notice_in" type="file" name="notice_in" >
                    <input type="button" value="提交" id="upload_notice_btn">
                    <br >
                </form >
            </div>
            <div id="add-course_lib" class='hidden'>
                <p class='login cir1 cir-12'> 添加课程界面</p>
                <form data-info="course_lib,add,0" method="post">
                        <label for="add-type" class="col-2" >类型:</label >
                        <select name="type"  class="col-6 cir type" id="add-type">
                            <option value="" >请选择：</option >
                            <option value="公共课/任选课" >公共课/任选课</option >
                            <option value="公共课/必修课" >公共课/必修课</option >
                            <option value="专业基础课/限选课" >专业基础课/限选课</option >
                            <option value="专业基础课/必修课" >专业基础课/必修课</option >
                            <option value="专业课/限选课" >专业课/限选课</option >
                            <option value="专业课/必修课" >专业课/必修课</option >
                        </select >
                        <span></span>
                        <br >
                        <label for="add-course-name" class="col-2">课程名称:</label >
                        <input type="text" name="course_name" class="col-6 cir course_name" id="add-course-name"   placeholder="请输入课程名字">
                        <span></span>
                        <br >
                        <label for="add-credit" class="col-2" >学分:</label >
                        <input type="text" name="credit" class="col-6 cir credit" id="add-credit"  placeholder="请输入学分">
                        <span></span>
                        <br >
                        <label for="add-total-hours" class="col-2" >总学时:</label >
                        <input type="text" name="total_hours" class="col-6 cir total_hours" id="add-total-hours"  placeholder="请输入总学时">
                        <span></span>
                        <br >
                        <label for="add-teach-hour" class="col-2" >教授学时:</label >
                        <input type="text" name="teach_hour" class="col-6 cir teach_hour" id="add-teach-hour"  placeholder="请输入教授学时">
                        <span></span>
                        <br >
                        <label for="add-teach-type" class="col-2" >授课方式:</label >
                        <select name="teach_type" id="add-teach-type" class="col-6 cir teach_type" >
                            <option value="" >请选择：</option >
                            <option value="讲授" >讲授</option >
                            <option value="上机" >上机</option >
                            <option value="体育" >体育</option >
                        </select >
                        <span></span>
                        <br >
                        <label for="add-test-mode" class="col-2" >考核方式:</label >
                        <select name="test_mode" id="add-test-mode" class="col-6 cir test_mode" >
                            <option value="" >请选择：</option >
                            <option value="闭卷" >闭卷</option >
                            <option value="开卷" >开卷</option >
                            <option value="考查" >考查</option >
                            <option value="笔试+口试" >笔试+口试</option >
                            <option value="操作" >操作</option >
                            <option value="理论+操作" >理论+操作</option >
                            <option value="论文" >论文</option >
                        </select >
                        <span></span>
                        <br >
                        <label for="add-week-time" class="col-2" >上课周次:</label >
                        <input type="text" name="week_time" class="col-6 cir week_time" id="add-week-time"  placeholder="请输入上课周次，如1-16的形式">
                        <span></span>
                        <br >
                        <input type="button" value="确认" class="col-8 cir submit">
                        <br >
                        <input type="reset" value="取消" class="col-8 cir">
                        <br >
                </form >
            </div>
            <div id="edit-course_lib" class="hidden">
                <p class='login cir1 cir-12'> 编辑课程界面</p>
                <form data-info="course_lib,edit,0"  method="post">
                        <label for="edit-type" class="col-2" >类型:</label >
                        <select name="type"  class="col-6 cir type" id="edit-type">
                            <option value="" >请选择：</option >
                            <option value="公共课/任选课" >公共课/任选课</option >
                            <option value="公共课/必修课" >公共课/必修课</option >
                            <option value="专业基础课/限选课" >专业基础课/限选课</option >
                            <option value="专业基础课/必修课" >专业基础课/必修课</option >
                            <option value="专业课/限选课" >专业课/限选课</option >
                            <option value="专业课/必修课" >专业课/必修课</option >
                        </select >
                        <span></span>
                        <br >
                        <label for="edit-course-name" class="col-2">课程名称:</label >
                        <select type="text" name="course_name" class="col-6 cir course_name" id="edit-course-name"  >
                            <option value="" >请选择：</option >
                        </select>
                        <span></span>
                        <br >
                        <label for="edit-credit" class="col-2" >学分:</label >
                        <input type="text"  name="credit" class="col-6 cir credit" id="edit-credit" placeholder="请输入学分">
                        <span></span>
                        <br >
                        <label for="edit-total-hours" class="col-2" >总学时:</label >
                        <input type="text"  name="total_hours" class="col-6 cir total_hours" id="edit-total-hours" placeholder="请输入总学时">
                        <span></span>
                        <br >
                        <label for="edit-teach-hour" class="col-2" >教授学时:</label >
                        <input type="text" name="teach_hour" class="col-6 cir teach_hour" id="edit-teach-hour" placeholder="请输入教授学时">
                        <span></span>
                        <br >
                        <label for="edit-teach-type" class="col-2" >授课方式:</label >
                        <select name="teach_type" id="edit-teach-type" class="col-6 cir teach_type" >
                            <option value="" >请选择：</option >
                            <option value="讲授" >讲授</option >
                            <option value="上机" >上机</option >
                            <option value="体育" >体育</option >
                        </select >
                        <span></span>
                        <br >
                        <label for="edit-test-mode" class="col-2" >考核方式:</label >
                        <select name="test_mode" id="edit-test-mode" class="col-6 cir test_mode" >
                            <option value="" >请选择：</option >
                            <option value="闭卷" >闭卷</option >
                            <option value="开卷" >开卷</option >
                            <option value="考查" >考查</option >
                            <option value="笔试+口试" >笔试+口试</option >
                            <option value="操作" >操作</option >
                            <option value="理论+操作" >理论+操作</option >
                            <option value="论文" >论文</option >
                        </select >
                        <span></span>
                        <br >
                        <label for="edit-week-time" class="col-2" >上课周次:</label >
                        <input type="text" name="week_time" class="col-6 cir week_time" id="edit-week-time" placeholder="请输入上课周次，如1-16的形式">
                        <span></span>
                        <br >
                        <input type="button" value="确认" class="col-8 cir submit">
                        <br >
                        <input type="reset" value="取消" class="col-8 cir">
                        <br >
                </form >
            </div>
            <div id="delete-course_lib" class="hidden">
                <p class='login cir1 cir-12'> 删除课程界面</p>
                <form data-info="course_lib,delete,0"  method="post">
                        <label for="delete-type" class="col-2" >类型:</label >
                        <select name="type"  class="col-6 cir type" id="delete-type">
                            <option value="" >请选择：</option >
                            <option value="公共课/任选课" >公共课/任选课</option >
                            <option value="公共课/必修课" >公共课/必修课</option >
                            <option value="专业基础课/限选课" >专业基础课/限选课</option >
                            <option value="专业基础课/必修课" >专业基础课/必修课</option >
                            <option value="专业课/限选课" >专业课/限选课</option >
                            <option value="专业课/必修课" >专业课/必修课</option >
                        </select >
                        <br >
                        <label for="delete-course-name" class="col-2">课程名称:</label >
                        <select name="course_name" class="col-6 cir course_name" id="delete-course-name"  >
                            <option value="" >请选择：</option >
                        </select>
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
                <p class='login cir1 col-12'>查询课程库</p>
                <button class="query-course_lib">检索</button>
                <button class="print_btn">打印</button>
                <button class="to_excel_btn">导出为EXCEL</button>
                <div class="print_div">
                </div>

            </div>
            <div id="add-course_class" class="hidden">
                <p class='login cir1 cir-12'>添加课程表界面</p>
                <form class="course_class" data-info="course_class,add,0"  method="post">
                        <label for="" class="col-2" >类型:</label >
                        <select name="type" class="col-6 cir type" >
                            <option value="" >请选择：</option >
                            <option value="公共课/任选课" >公共课/任选课</option >
                            <option value="公共课/必修课" >公共课/必修课</option >
                            <option value="专业基础课/限选课" >专业基础课/限选课</option >
                            <option value="专业基础课/必修课" >专业基础课/必修课</option >
                            <option value="专业课/限选课" >专业课/限选课</option >
                            <option value="专业课/必修课" >专业课/必修课</option >
                        </select >
                        <span></span>
                        <br >
                        <label for="" class="col-2">课程名称:</label >
                        <select type="text" name="course_name" class="col-6 cir course_name" >
                            <option value="" >请选择：</option >
                        </select>
                        <span></span>
                        <br >
                        <label for="" class="col-2">开课班级：</label >
                        <input type="text" name="course_dept" class="col-6 cir course_dept" placeholder="请输入班级，班级之间用英文逗号隔开">
                        <span></span>
                        <br >
                        <label for="" class="col-2">上课时间：</label >
                        <input type="text" name="course_time" class="col-6 cir course_time" placeholder="请输入上课节次，如一[1-2节]或二[1-2节]，一周两节时中间的逗号必须为英文">
                        <span></span>
                        <br >
                        <label  class="col-2">开课学期：</label >
                        <select  name="start_term" class="col-6 cir start_term">
                        </select>
                        <span></span>
                        <br >
                        <label for="" class="col-2">授课教师：</label >
                        <select  name="course_teacher" class="col-6 cir course_teacher" placeholder="请输入教师的名字">
                        </select>
                        <span></span>
                        <br >
                        <label for="" class="col-2">上课地点：</label >
                        <input type="text" name="course_room" class="col-6 cir course_room" placeholder="请输入上课地点，如D01001，一周两节时中间的逗号必须为英文">
                        <span></span>
                        <br >
                        <input type="button" value="确认" class="col-8 cir submit">
                        <br >
                        <input type="reset" value="取消" class="col-8 cir">
                        <br >
                </form >
            </div>
            <div id="delete-course_class" class="hidden">
                <p class='login cir1 cir-12'>删除课程表界面</p>
                <form class="course_class" data-info="course_class,delete,0"  method="post">
                        <label for="" class="col-2" >类型:</label >
                        <select name="type" class="col-6 cir type" >
                            <option value="" >请选择：</option >
                            <option value="公共课/任选课" >公共课/任选课</option >
                            <option value="公共课/必修课" >公共课/必修课</option >
                            <option value="专业基础课/限选课" >专业基础课/限选课</option >
                            <option value="专业基础课/必修课" >专业基础课/必修课</option >
                            <option value="专业课/限选课" >专业课/限选课</option >
                            <option value="专业课/必修课" >专业课/必修课</option >
                        </select >
                        <span></span>
                        <br >
                        <label for="" class="col-2">课程名称:</label >
                        <select type="text" name="course_name" class="col-6 cir course_name" >
                            <option value="" >请选择：</option >
                        </select>
                        <span></span>
                        <br >
                        <label for="" class="col-2 ">开课班级：</label >
                        <input type="text" name="course_dept" class="col-6 cir course_dept cc" placeholder="请输入班级，班级之间用英文逗号隔开">
                        <span></span>
                        <br >
                        <input type="button" value="确认" class="col-8 cir submit">
                        <br >
                        <input type="reset" value="取消" class="col-8 cir">
                        <br >
                    </fieldset>
                </form >
            </div>
            <div id="edit-course_class" class="hidden">
                <p class='login cir1 cir-12'>编辑课程表界面</p>
                <form class="course_class" data-info="course_class,edit,0"  method="post">
                        <label for="" class="col-2" >类型:</label >
                        <select name="type" class="col-6 cir type" >
                            <option value="" >请选择：</option >
                            <option value="公共课/任选课" >公共课/任选课</option >
                            <option value="公共课/必修课" >公共课/必修课</option >
                            <option value="专业基础课/限选课" >专业基础课/限选课</option >
                            <option value="专业基础课/必修课" >专业基础课/必修课</option >
                            <option value="专业课/限选课" >专业课/限选课</option >
                            <option value="专业课/必修课" >专业课/必修课</option >
                        </select >
                        <span></span>
                        <br >
                        <label for="" class="col-2">课程名称:</label >
                        <select type="text" name="course_name" class="col-6 cir course_name" >
                            <option value="" >请选择：</option >
                        </select>
                        <span></span>
                        <br >
                        <label for="" class="col-2">开课班级：</label >
                        <input type="text" name="course_dept" class="col-6 cir course_dept cc" placeholder="请输入班级，班级之间用英文逗号隔开">
                        <span></span>
                        <br >
                        <label for="" class="col-2">上课时间：</label >
                        <input type="text" name="course_time" class="col-6 cir course_time" placeholder="请输入上课节次，如一[1-2节]或二[1-2节]，一周两节时中间的逗号必须为英文">
                        <span></span>
                        <br >
                        <label for="" class="col-2">开课学期：</label >
                        <select  name="start_term" class="col-6 cir start_term">
                        </select>
                        <span></span>
                        <br >
                        <label for="" class="col-2">授课教师：</label >
                        <select name="course_teacher" class="col-6 cir course_teacher" >
                        </select>
                        <span></span>
                        <br >
                        <label for="" class="col-2">上课地点：</label >
                        <input type="text" name="course_room" class="col-6 cir course_room" placeholder="请输入上课地点，如D01001，一周两节时中间的逗号必须为英文">
                        <span></span>
                        <br >
                        <input type="button" value="确认" class="col-8 cir submit">
                        <br >
                        <input type="reset" value="取消" class="col-8 cir">
                        <br >
                </form >
            </div>
            <div id="query-course_class" class="hidden">
                 <p class='login cir1 col-12'>查询课表</p>
                 <form class="course_class" data-info="course_class,query,all" method="post">
                    <label for="" class="col-2">开课班级：</label >
                    <input type="text" name="course_dept" class="col-6 cir course_dept" placeholder="请输入班级，班级之间用英文逗号隔开">
                    <span></span>
                    <br >
                    <label for="" class="col-2">开课学期：</label >
                    <select  name="start_term" class="col-6 cir start_term">
                    </select>
                    <span></span>
                    <br >
                    <input type="button" class="submit" value="检索专业课表">
                </form >
                <div class="print_div"  data-info="course_class">

                </div>
                <button class="print_btn" >打印</button>
                <button class="to_excel_btn">导出为EXCEL</button>
            </div>
            <div id="import-excel-div" class="hidden">
                <p class='login cir1 col-12'>上传EXCEL</p>
                <ol>
<?php if($_SESSION['id_level']=='管理员') { ?>    <li >
                        <form  data-info="user"  method="post">
                            <label>请选择用户表EXCEL：</label><br>
                            <input id="import_user" type="file" name="import_user" >
                            <input type="button" value="提交" class="import_btn" data-info='user,import_user'>
                        </form >
                    </li > <?php  } ?>
                    <li >
                        <form  data-info="course_lib"  method="post">
                            <label>请选择课程库EXCEL：</label><br>
                            <input id="import_course_lib" type="file" name="import_course_lib" >
                            <input type="button" value="提交" class="import_btn" data-info='course_lib,import_course_lib'>
                        </form >
                    </li >
                    <li >
                        <form  data-info="course_class"  method="post">
                            <label>请选择课程表EXCEL：</label><br>
                            <input id="import_course_class" type="file" name="import_course_class" >
                            <input type="button" value="提交" class="import_btn" data-info='course_class,import_course_class'>
                        </form >
                    </li >
                </ol>
            </div>
        </div >
       <form action='link.php' method='post' id='pre_form' class='hidden' target="_blank"></form>
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
 </main>
 <?php  echo '<div class="info p_info"><span class="id_level">'.$_SESSION['id_level'].'</span>:[<span class="s_username">'
       .$_SESSION['username'].'</span>]<span class="s_real_name">'
       .$_SESSION['real_name'].'</span><span class="time_info"></span></div>';
   ?>
</div>
<footer>
  <span class="col-2">太原工业学院</span>
  <span class="col-5">地址：山西省太原市尖草坪区新兰路31号</span>
  <span class="col-2">邮编：030008 </span>
  <p >Copyright  @  All  Right </p>
</footer>
</body >
</html >
<?php  } ?>
