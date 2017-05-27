/**
 * Created by Administrato on 2016/12/7.
 */
$(function() {
    if($("#login").length>0) {
        $("nav").on("click","li:not(:first-of-type)",function() {
            alert("请先登录再进行查看");
        });
        //加载更多
        $(document).on("click","p.more", function () {
          try {
            var text=$(this).parents("form").siblings("p")[0].innerText;
          }
          catch(err) {
            var text=$(this).siblings("p").text();
          }
            if(text=='查看公告'){
                queryNotice(1);
            }
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
                var notices= JSON.parse(json)['notices'],str="";
                for (var i=0,len=notices.length;i<len;i++) {
                    str+="<li><a href='#' class='col-5 pre_notice' data-info='"+notices[i]['n_content']+"'>"+notices[i]['n_subject']+"</a><span class='r col-6'>"
                        +notices[i]['n_time'].slice(0,10)+"</span></li><hr>";
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
        (function init() {
          queryNotice(0);
          (function(len) {
            var l=$(".slider_child")
            setInterval(function() {
              if(l.css("left")<=-400*(len-3)+"px") {
                l.animate({"left":"0px"},1500);
              } else {
                l.animate({left:"-=400px"},1500);
              }
            },1500);
          })(6);
        })();

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
        //重置密码
        $(".reset_pwd").click(function () {
          var pLogin=$(this).parents("form").siblings("p.login");
          if($(this)[0].nodeName.toLowerCase()=='a') {
            pLogin.text("重置密码");
          } else {
            pLogin.text("用户登录");
          }
           $(this).parents("form").hide().siblings("form").show();
            return false;
        });
        /*重置密码输入验证*/
        $(document).on("blur",".new_pwd2",function() {
            var new_pwd=$(".new_pwd").val();
            var new_pwd1=$(".new_pwd1").val();
            if(new_pwd!=new_pwd1)  $(this).next("span").attr("class","error").empty().text("两次密码不一致");
                else if(new_pwd1!="") $(this).next("span").empty().attr("class","success");
        });
        /*用户登录*/
        $(document).on("click", ".submit", function () {
            var inp=$(this).siblings("input,select");
            for(var i= 0,len=inp.length;i<len;i++) {
                if (inp[i].value.trim() == "") {
                    alert("输入不能为空");
                    return false;
                }
            }
            var data = $(this).parents("form").serializeArray();
            var action = $(this).parents("form").data("info").split(",");
            data.push({'name': 'action', 'value': action[0]},
                {'name': 'action_type', 'value': action[1]},
                {'name': 'action_condition', 'value': action[2]});
            $.post("php/service.php", data, function (json) {
                if(json[0]!='{') {
                    alert(json);
                    $("#loginForm").show();$("#edit_form").hide();
                    return false;
                }
                else {
                    var users = JSON.parse(json)['users'];
                    if (users.length > 0) {
                        var act = users[0]['id_level'];
                        if (act == '学生') action1 = 'student.php';
                        else action1 = 'teacher.php';
                        var form1 = "<form id='hide_form' method='post' action='" + action1 + "'><input type='hidden' value='"
                            + users[0]['id_level'] + "' name='id_level'><input type='hidden' value='"
                            + users[0]['real_name'] + "' name='real_name'><input type='hidden' value='"
                            + users[0]['username'] + "' name='username'></form>";
                        $("body").append(form1);
                        $("#hide_form").trigger("submit");
                    }
                    else alert("用户名或者密码错误");
                }
            });
            return false;
        });
    }
    if($("#student").length>0) {
        $.extend({ My: {} }); //jq下的net命名空间
        $.extend($.My, {
            "s_real_name":$(".s_real_name").text(),
            "s_username":$(".s_username").text(),
            "send_name":$(".s_username").text()+$(".s_real_name").text(),
            "dept":$(".s_username").text().substr(0,7)
        });
    }
});
