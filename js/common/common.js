/**
 * Created by Administrato on 2017/2/17.
 */
$(function () {
  var err="<p class='p_error' style='font-weight:normal;font-size:1rem'>搜索结果：<span class='error'>没有查询到数据</span></p>";
  !function queryTeacher() {
      var data={action:'user',action_type:'query',action_condition:'teacher'};
      $.post('php/service.php',data,function(json) {
          var tea=JSON.parse(json)['users'],str="<option value=''>请选择教师：</option>";
          for(var i= 0,len=tea.length;i<len;i++) {
              str+="<option value='"+tea[i]['real_name']+"'>"+tea[i]['real_name']+"</option>";
          }
          $("select.course_teacher,select.to_p").append(str);
      });
  }();
    /*enter键BUG修改*/
    $(document).on("keydown",document,function(e) {
      if(e.keyCode==13) {
        return false;
      }
    });
    //加载更多
    $(document).on("click","p.more", function () {
      try {
        var text=$(this).parents("form").siblings("p")[0].innerText;
      }
      catch(err) {
        var text=$(this).siblings("p").text();
      }
        if(text=='未回复信件')
            $.query_mail(0,localStorage.mail_id0);
        if(text=='已回复信件'){
            $.query_mail(1,localStorage.mail_id1);
        }
        if(text=='查看公告'){
            queryNotice(1);
        }
         if(text=='下载资料'){
            query_data(1,$("#download_data"));
        }
        if(text=='下载视频'){
            query_data(1,$("#download_video"));
        }
    });
    //信件显示内容与回复按钮
    $(document).on("click",".show_pg", function () {
        $(this).siblings("div").toggle(300);
    });
    /*信件查询*/
    jQuery.query_mail=function(flag) {
        if($("#query_mail")[0].style.display!='none') {
            var ols0 = $("#show_mail_ol0");
            var ols1 = $("#show_mail_ol1");
            var err_len=$("#show_mail_ol"+flag+" p.p_error").length;
            var data = {
                "action": "mail", "action_type": "query",
                "action_condition": "s_flag","flag": flag
            };
            if($.My['send_name']) {
              data.from_p=$.My['send_name'];
            }
            if($.My['s_real_name']) {
              data.to_p=$.My['s_real_name'];
            }
            if(localStorage.getItem("mail_id"+flag)) {
              data['mail_id']=localStorage.getItem("mail_id"+flag);
            }
            $.post("php/service.php", data, function (json) {
                var mails = JSON.parse(json)['mails'], str = "";
                var len = mails.length;
                for (var i = 0; i < len; i++) {
                    str += "<li class='show_li' data-info='" + mails[i]['mail_id'] + "'><p class='show_pg'>主题：" + mails[i]['subject'] + "</p><div class='hidden'><p class='bor'>内容："
                        + mails[i]['content'] + "</p><p>发送人："+mails[i]['from_p']+"</p>";
                    if($("#teacher").length>0&&flag==0) {
                      str+="<button class='reply_btn cir col-2'>回复</button>"
                          +"<button class='hidden col-2 cir sure_btn '>确认</button><br><div contenteditable='true' class='content_div hidden cir'></div>";
                    }
                    if(flag==1) {
                       str+="<p class='bor'>"+mails[i]['to_p']+"老师的回复为：" + mails[i]['reply'] + "</p>"
                            +"<p>回复时间为："+mails[i]['reply_time']+"</p></div></li>";
                    }
                }
                if(arguments[1]&&len<=0&&err_len==0) {
                  str="<p class='p_error'>搜索结果为：<span class='error'>没有查询到更多数据</span></p>";
                  if(flag==0) {
                      ols0.append(str);
                  }
                  if(flag==1) {
                      ols1.append(str);
                  }
                }
                if(len>0) {
                  if(flag==0) {
                      ols0.append(str);
                      localStorage.mail_id0=mails[len-1]['mail_id'];
                  }
                  if(flag==1) {
                      ols1.append(str);
                      localStorage.mail_id1=mails[len-1]['mail_id'];
                  }
                }

            });
        }
    }
    /*初始化及信件查询*/
    ! function init() {
      localStorage.clear();
        queryNotice(0);
        !function get_term() {
            var d=new Date();
            var y= d.getFullYear();
            var m= d.getMonth()+1;
            var ary=[y+"-"+(y+1)+"学年第一学期",(y-1)+"-"+y+"学年第二学期",(y-1)+"-"+y+"学年第一学期",
                (y-2)+"-"+(y-1)+"学年第二学期",(y-2)+"-"+(y-1)+"学年第一学期",(y-3)+"-"+(y-2)+"学年第二学期",
                (y-3)+"-"+(y-2)+"学年第一学期",(y-4)+"-"+(y-3)+"学年第二学期",(y-4)+"-"+(y-3)+"学年第一学期"];
            if(m==12||(m>=1&&m<=5))
                ary.shift();
            if(m>=6&&m<=11)
                ary.pop();
            var str="";
            for(var i= 0;i<8;i++) {
                str+="<option value='" + ary[i] + "'>" + ary[i] + "</option>";
            }
            $(".start_term").empty().append(str);
        }();
        !function get_time() {
            var d=new Date();
            var y= d.getFullYear();
            var m= d.getMonth()+1;
            var date= d.getDate();
            var str=y+"年"+m+"月"+date+"日";
            $(".time_info").text(str);
        }();
        jQuery.query_mail(0);
        jQuery.query_mail(1);
    }();
    /*相应页面的显示*/
    $("header nav").on("click","li",function() {
        var divId="#"+$(this).data("id");
        $("form").trigger("reset");
        $(".print_div,div[contenteditable],.dir_name2").empty();
        $("form ol li,p.p_error").remove();
        $(divId).show(280).siblings().hide(280).find("span").removeClass();
        $(this).addClass("hov").siblings("li").removeClass("hov");
    });
    /*预览下载文件*/
    $(document).on("click",".pre_pdf",function() {
        var info=$(this).data("info");
	       if(browser.mobile==true||browser.msie==true) {
                self.location=info;
                return false;
            }
        var str="<input type='hidden' name='info' value='"+info+"'>";
        $("#pre_form").empty().append(str).submit();
        return false;
    });

    //课程库查询
    $(document).on("click","button.query-course_lib",function() {
      var $table=$(this).nextAll(".print_div");
      $.post("php/service.php",{
          "action":"course_lib","action_type":"query","action_condition":"1"
      },function(json) {
          localStorage.to_excel=json;
          var str="<table border='1' cellspacing='0'><tr><th >课程名称</th ><th >类型</th ><th >学分</th ><th >总学时</th ><th >教授学时</th ><th>授课方式</th><th >考核方式</th ><th >上课周次</th ></tr> ";
          var course_libs= JSON.parse(json)['course_libs'];
          for(var i= 0,len=course_libs.length;i<len;i++) {
              var index=course_libs[i];
              str+="<tr><td>"+index['course_name']+"</td><td>"+index['type']+"</td><td>"+index['credit']
                  +"</td><td>"+index['total_hours']+"</td><td>"+index['teach_hour']+"</td><td>"+
                  index['teach_type']+"</td><td>"+index['test_mode']+"</td><td>"+index['week_time']+"</td></tr>";
          }
          str+="<table>";
          $table.empty().append(str);
      });
    });
    //打印设置
    $(".print_btn").click(function() {
        var print_div=$(this).siblings(".print_div");
        print_div.jqprint({
            operaSupport: false});
    });
    //导出为EXCEL
    $(document).on("click",".to_excel_btn",function() {
        var data={"action":"to_excel","action_type":localStorage.to_excel};
        $.post("php/to_excel.php",data, function (json) {
            window.location.href='php/to_excel.xlsx';
        });
    });
    //根据类型显示上传文件
    $(document).on("change",".data", function () {
        var ele=$(this).parents("div")[0].id;
        query_data(0,$("#"+ele));
    });
    //显示类别下的科目
    $(document).on("change",".dir_name1", function () {
        var sel=$(this).nextAll("select.dir_name2");
        var data={"action":"dir_name","action_type":"query","dir_name1":$(this).val()};
        $.post("php/service.php",data,function(json) {
            var dir_name=JSON.parse(json)['dir_names'],str="";
            for(var i= 0,len=dir_name.length;i<len;i++) {
                str+="<option value='"+dir_name[i]['dir_name2']+"'>"+dir_name[i]['dir_name2']+"</option>";
            }
            sel.empty().append("<option value=''>请选择：</option>"+str);
        });
    });
    //输入格式检查
    $(document).on("blur","input,select",function() {
           //输入格式验证函数
           var classAry=["id_card","dir_name2","real_name","username","new_pwd","new_pwd1","old_pwd","teach_type","type","course_name","credit","total_hours","teach_hour","test_mode",
               "week_time","course_teacher","start_term","course_dept","course_room","course_time"];
           var regAry= [/^\d{18}$/g,/^([\u4e00-\u9fa5]|\w)+$/g,/^([\u4e00-\u9fa5]){2,4}$/g,/^\w/g,/^\w{5,20}$/g,/^\w{5,20}$/g,/^\w{5,20}$/g,/^[\u4e00-\u9fa5]{2}$/g,/^[\u4e00-\u9fa5]{3,5}\/[\u4e00-\u9fa5]{3}$/g,/^([\u4e00-\u9fa5]|\w)+$/g,/^[1-9][0-9]{0,1}(\.[05]){0,1}$/g,
                       /^[1-9][0-9]?$/g,/^[1-9][0-9]?$/g,/^[\u4e00-\u9fa5]{2}(\+[\u4e00-\u9fa5]{2}){0,1}$/g,/^([1]{1}[0-9]|[1-9])-[1-2][0-9]?$/g,
                   /^[^\x00-\xff]{2,5}$/g,/^20[0-9]{2}-20[0-9]{2}学年第[一二]学期$/g,/^\d{7}(\,\d{7}){0,}$/g,/^[DZFS][\d]{5}(,[DZFS][\d]{5}){0,2}$/g,
                   /^[一二三四五六七]\[\d-[\d]{1,2}节\][单双]{0,1}(,[一二三四五六七]\[\d-[\d]{1,2}节\][单双]{0,1}){0,2}$/g];
           function inputAuth(inputValue,reg) {
                   if(!reg.test(inputValue)) nextSpan.attr("class","errorFormat").empty();
                   else  nextSpan.attr("class","success").empty();

           }
           var inputValue=$(this).val();
           var nextSpan=$(this).next();
           var thisClass=$(this).attr("name");
          if(inputValue=="") {
            nextSpan.attr("class","errorEmpty");
            return false;
          }
           for(var i=0,len=classAry.length;i<len;i++) {
               if(thisClass==classAry[i])
                   inputAuth(inputValue,regAry[i]);
           }
       });
    //课程表提交
    $(document).on("click",".submit",function() {
        var form=$(this).parents("form");form.find("input").blur();
        var action=form.data("info").split(",");
        var term=$(this).siblings(".start_term").val();
        var dept=$(this).siblings(".course_dept").val()||$.My['dept'];
        var inputAll=form.find("input,select:not('.wys1,.wys2')");
        var print_div=$(this).parents("form").siblings("div.print_div");
        //检查是否为空
        for(var i=0,len=inputAll.length;i<len;i++) {
            if(inputAll[i].value==""&&print_div.data("info")!="score")  {
                alert("输入不能为空");
                return false;
            }
        }//检查格式是否正确并给出提示
        var $spanClass=form.find("span");
        for(var i=0,len=$spanClass.length;i<len;i++) {
            if(!$spanClass[i].className.indexOf('error')&&print_div.data("info")!="score") {
                alert("请检查你的输入格式");
                return false;
            }
        }
        var data=form.serializeArray();
        data.push({'name':'action','value':action[0]},
            {'name':'action_type','value':action[1]},
            {'name':'action_condition','value':action[2]});
        if(action[0]=='user'&&action[1]=='edit')
                data.push({"name":"username","value": $.My['s_username']});
        if($("#student").length>0) {
            if(action[0]=='course_class')
                data.push({"name":"course_dept","value":$.My['dept']});
            if(action[0]=='mail')
                data.push({"name":"from_p","value":$.My['send_name']},
                    {"name":"n_content","value":$(".content_div").html()});
            if(action[0]=='score')
                data.push({"name":"username","value":$.My['s_username']});
        }
        function query_table(index,json) {
            switch(index) {
                case 'course_class':var course_classs = JSON.parse(json)['course_classs'];
                    if(course_classs.length>0) {
                        var str = "<p>"+term+"</p><p>所属班级："+dept+"</p><table border='1'><tr><th>序号</th><th>课程</th><th>学分</th><th>总学时</th><th>讲授学时</th>" +
                            "<th>类别</th><th>授课方式</th><th>考核方式</th><th>任课教师</th><th>周次</th>" +
                            "<th>节次</th><th>地点</th></tr>";
                        for (var i = 0, len = course_classs.length; i < len; i++) {
                            var time = course_classs[i]['course_time'].split(",");
                            var t_len = time.length;
                            var c_room = course_classs[i]['course_room'].split(",");
                            str += "<tr><td>"+(i+1)+"</td><td>" + course_classs[i]['course_name'] + "</td><td>" + course_classs[i]['credit'] + "</td>" +
                                "<td>" + course_classs[i]['total_hours'] + "</td><td>" + course_classs[i]['teach_hour'] + "</td>" +
                                "<td>" + course_classs[i]['type'] + "</td><td>" + course_classs[i]['teach_type'] + "</td><td>" + course_classs[i]['test_mode'] +
                                "<td>" + course_classs[i]['course_teacher'] + "</td><td>" + course_classs[i]['week_time'] + "</td>" +
                                "<td>" + time[0] + "</td><td>" + c_room[0] + "</td></tr>";
                            if (t_len>1) {
                                for(var j=1;j<t_len;j++) {
                                    str += "<tr><td></td><td></td><td></td>" +
                                        "<td></td><td></td>" +
                                        "<td></td><td></td><td>" +
                                        "<td></td><td>"+ course_classs[i]['week_time'] + "</td>" +
                                        "<td>" + time[j] + "</td><td>";
                                    if (c_room.length>1) { for(var m=1;m<c_room.length;m++)
                                        str += c_room[m] + "</td></tr>";
                                    }
                                    else str += c_room[0] + "</td></tr>";
                                }

                            }
                        }
                        str += "</table>";
                        print_div.empty().append(str);
                    }
                    else  print_div.empty().append(err);
                    break;
                case 'user':var users=JSON.parse(json)['users'];
                    if(users.length>0) {
                        var str="<table border='1' cellspacing='0'><tr><th>序号</th><th>用户名</th><th>用户级别</th><th>真实姓名</th><th>身份证</th></tr>";
                        for(var i= 0,len=users.length;i<len;i++ ) {
                            str+="<tr><td>"+(i+1)+"</td><td>"+users[i]['username']+"</td><td>"+users[i]['id_level']+"</td><td>"
                                +users[i]['real_name']+"</td><td>"+users[i]['id_card']+"</td></tr>";
                        }
                        str+="</table>";
                        print_div.empty().append(str);
                    }
                    else  print_div.empty().append(err);
                    break;
                case 'score':var scores=JSON.parse(json)['scores'];
                    if(scores.length>0) {
                        var str="<p>"+scores[0]['start_term']+"</p><table border='1' cellspacing='0'><tr><th>序号</th><th>学号</th><th>姓名</th><th>课程</th><th>学分</th>"
                            +"<th>类别</th><th>考核方式</th><th>平时成绩</th><th>末考成绩</th><th>综合成绩</th><th>绩点</th><th>学分绩点</th></tr>";
                        for(var i= 0,len=scores.length;i<len;i++) {
                            var a=(scores[i]['point']*scores[i]['credit']+"").substr(0,3);
                            str+="<tr><td>"+(i+1)+"</td><td>"+scores[i]['username']+"</td><td>"+scores[i]['real_name']+"</td><td>"+scores[i]['course_name']+"</td><td>"
                                +scores[i]['credit']+"</td><td>"+scores[i]['type']+"</td><td>"+scores[i]['test_mode']+"</td><td>"+scores[i]['peace_score']+"</td><td>"
                                + +scores[i]['final_score']+"</td><td>"+scores[i]['total_score']+"</td><td>" +scores[i]['point']+"</td><td>" +a+"</td></tr>";
                        }
                        print_div.empty().append(str);
                    }
                    else  print_div.empty().append(err);
                    break;
            }
        }
        $.post("php/service.php",data,function(json) {
            if(json[0]!='{') {
                alert(json);
                return false;
            }
            else {
                localStorage.to_excel=json;
                query_table(print_div.data("info"),json);
                return false;
            }
        });
    });
    /*查询公告*/
    function  queryNotice(flag) {
        var data={"action":"notice","action_type":"query","action_condition":"all"};
        var p_error=$("#query_notice_div ol p.p_error").length;
        if(flag==1) {
            data['n_id']=localStorage.n_id;
        }
        $.post("php/service.php",data, function (json) {
          if(json[0]=="{") {
            var notices= JSON.parse(json)['notices'];
            var str="";
            for (var i=0,len=notices.length;i<len;i++) {
                str+="<li><a href='#' class='col-5 pre_notice' data-info='"+notices[i]['n_content']+"'>"+notices[i]['n_subject']+"</a><span class='r col-6'>"
                    +notices[i]['n_time']+"</span></li><hr>";
            }
            if(len>0) {
                localStorage.n_id=notices[len-1]['n_id'];
            }
            if(flag==1&&len<=0) {
              str="<p class='p_error'>搜索结果为：<span class='error'>没有查询到更多数据</span></p>";
              if(p_error>0) str='';
            }
            $(".notice ol").append(str);
          }
        });
    }
    /*密码修改输入验证*/
    $(document).on("blur",".new_pwd1",function() {
        var new_pwd=$(".new_pwd").val().trim(),new_pwd1=$(".new_pwd1").val().trim();
        if(new_pwd&&new_pwd1&&new_pwd!=new_pwd1)  $(this).next("span").attr("class","error").empty().text("两次密码不一致");
    });
    /*公告显示*/
    $(document).on("click",".pre_notice",function() {
        var info="upload/notice/"+$(this).data("info");
        if(browser.mobile==true||browser.msie==true) {
            self.location=info;
            return false;
        }
        var str="<input type='hidden' name='info' value='"+info+"'>";
        $("#pre_form").empty().append(str).submit();
        return false;
    });
    $(document).on("click",".pre_video",function() {
      var video_info=$(this).data('info');
      $("#video")[0].src=video_info;
      return false;
    });
	var $M={};
  $M.execContent=function(event,ele) {
      $(document).on(event,ele,function() {
          try {
              var exec=$(this).data("exec").split(",");
          }
          catch(err) {
              exec=$(this).find("option:selected").data("exec").split(",");
          }
          var exec2=exec[1]?exec[1]:null;
          if(exec2=='color')
              exec2=$(this).val();
          document.execCommand(exec[0],false,exec2);
          return false;
      });
  };
  $M.init=function(ele) {
      $M.execContent("click",".wys");
      $M.execContent("blur",".wys2");
      $M.execContent("change",".wys1");
      ele.load("js/common/frag.html");
  };
      $M.init($(".edit_able"));
  });
  $(document).on("click",".btn",function() {
    if(navigator.appName=='Microsoft Internet Explorer') self.location=$(this).attr("href");
  });
  function query_data(flag,ele) {
    var dir_name2=ele.find(".data").val();
    var ols=ele.find("ol");
    var d1=ele.find(".dir_name1").val();
    var type1=ele.find("form").data('info');
    type=(type1=='video'?1:0);
    var data={"action":"data_info","action_type":"query","dir_name1":d1,
              "dir_name2":dir_name2,type:type,data_id:localStorage.data_id};
    if(flag==1)
      data.flag=1;
    $.post("php/service.php",data,function(json) {
        var infos=JSON.parse(json)['data_infos'],str="";
        if(infos.length>0) {
            for(var i= 0,len=infos.length;i<len;i++) {
                var a1=infos[i]['data_id'],a2=infos[i]['store_time'],a3=infos[i]['data_name'],
                    a4=infos[i]['upload_user'],a5=infos[i]['dir_name1'],a6=infos[i]['dir_name2'],
                    link="upload/"+a5+"/"+a6+"/"+a3;
                str+="<li><p class='tr_bg col-6'>"+a3+"</p><div class='col-6 no_mar'><p>上传者："+a4+"</p><p>上传时间："
                    +a2+"</p>";
                if(type1=='data_in')   str+= "<button class='pre_pdf' data-info='"+link+"'>预览</button>";
                if(type1=='video')    str+= "<button class='pre_video' data-info='"+link+"'>预览</button>";
                str+="<button><a href='"+link+"' download='"+a3+"' class='bt'>下载</a></button>";
                if($("#teacher").length>0) {
                    str+="<button class='del_data' data-info='data_info,"+a1+","+link+","+a4+"'>删除</button>";
                }
                str+="</div></li>";
            }
            localStorage.data_id=infos[len-1]['data_id'];
        }
        else {
          if(flag==0) {
            ols.find("li,p").remove();
            if(ols.find("span.error").length<=0) {
              ols.append("<p class='p_error' style='font-weight:normal;'>搜索结果：<span class='error'>没有查询到数据</span></p>");return false;
            }

          }
        }
          if(flag==1&&infos.length<=0) {
          str="<p class='p_error'>搜索结果：<span class='error'>没有查询到更多数据</span></p>";
          }
        ols.find("p.p_error").remove();
        ols.append(str);
    });
  }
