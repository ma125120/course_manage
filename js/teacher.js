/**
 * Created by Administrato on 2016/12/9.
 */
$(function(){
    $.extend({ My: {} }); //jq下的net命名空间
    $.extend($.My, {
        "s_real_name":$(".s_real_name").text(),
        "s_username":$(".s_username").text(),
        "send_name":$(".s_username").text()+$(".s_real_name").text(),
        "id_level":$("span.id_level").text()
    });
    /*回复的输入框显示*/
    $(document).on("click",".reply_btn", function () {
        $(this).siblings(".sure_btn,.content_div").toggle(200);
    });
    /*查询下载的内容*/
    function queryDown(flag) {
        var data_id=localStorage.data_id?localStorage.data_id:1;
        var ols=$("#download_data").find("ol");
        var data={"action":"data_info","action_type":"query","action_condition":"all",
            "data_id":data_id,"flag":flag};
        $.post("php/service.php",data,function(json) {
            var data_infos=JSON.parse(json)['data_infos'],str="";
            var len=data_infos.length;
            for(var i= 0;i<len;i++) {
                var ap_na=data_infos[i]['data_name'];
                var path="upload/"+ap_na;
                str+="<li><a href='"+path+"' class='l' download='"+ap_na+"'>下载</a><a href='#' data-info='"+data_infos[i]['data_id']+","
                    +ap_na+",data_info' class='r del_data'>删除</a></li>";
            }
            ols.prepend(str);
            if(data_infos.length>0)
                localStorage.data_id=data_infos[0]['data_id'];
        });
    }
    //确认回复并上传消息
    $(document).on("click",".sure_btn", function () {
        var li=$(this).parents("li");
        var id=li.data("info");
        var txt=$(this).siblings(".content_div").html();
        if(txt=='') {
          return false;
        }
        var data={"action":"mail","action_type":"edit","action_condition":"reply",
                "mail_id":id,"reply":txt};
        $.post("php/service.php",data, function (json) {
            li.find(".show_pg~div,.content_div,.sure_btn").hide();
            //$("#show_mail_ol1").prepend(li.remove());
        });
    });
    function ajaxFileUpload(to_data,varid){
        $.ajaxFileUpload(
            {
                url:"php/service.php",            //需要链接到服务器地址
                secureuri:false,
                fileElementId:varid,                        //文件选择框的id属性
                dataType: 'text',
                data:to_data,//服务器返回的格式，可以是json
                success: function (data, status)            //相当于Java中try语句块的用法
                {
                    alert(data);
                },
                error: function (data, status, e)            //相当于java中catch语句块的用法
                {
                    alert('添加失败');
                }
            }
        );
    }
    /*删除相应的文件*/
    $(document).on("click",".del_data", function () {
            var info=$(this).data("info").split(",");
            var li=$(this).parents("li");
            if($.My['s_real_name']==info[3]|| $.My['id_level']=='管理员') {
                var sure=confirm("确认删除吗？");
                if(sure) {
                    $.post("php/service.php", {
                        "action": "del", "action_type": info[0], "action_condition": info[1],
                        "del_val": info[2]
                    }, function (json) {
                        li.remove();
                        alert(json);
                    });
                }
            }
            else {
                alert("您没有权限删除该文件");
            }
             return false;
        });
    /*上传资料*/
    $("#upload_data_btn").click(function() {
            var d1=$(this).siblings(".dir_name1").val();
            var d2=$(this).siblings(".dir_name2").val();
            var data={"dir_name1":d1,"dir_name2":d2,"action":"upload",
                "name_val":"data_in","upload_user":$.My['s_real_name']};
            ajaxFileUpload(data,'data_in');
        });
    //上传公告
    $("#upload_notice_btn").click(function() {
        var d1=$(this).siblings(".n_subject").val();
        var data={"action":"upload","n_subject":d1,
            "name_val":"notice_in","n_person":$.My['s_real_name']};
        ajaxFileUpload(data,'notice_in');
    });
        //导入EXCEL
    $(".import_btn").click(function() {
            var info=$(this).data("info").split(",");
            var data={"action":info[0],"name_val":info[1]};
            if(info[0]=='score') {
                if($.My['id_level']=='管理员') data['teacher']='管理员';
                else data['teacher']=$.My['s_real_name'];
            }
           ajaxFileUpload(data,info[1]);
        });

     //类型及课程名下拉菜单的显示
     $(document).on("change",".type",function() {
                var inputValue = $(this).val();
                var data={"action":"course_lib","action_type":"query","action_condition":"0",
                            "type":inputValue};
                $.post("php/service.php",data,function (json) {
                    var obj = $.parseJSON(json);
                    var course_libs = obj.course_libs;
                    var str = "<option>请选择：</option>";
                    for (var i = 0, len = course_libs.length; i < len; i++)
                        str += "<option value='" + course_libs[i]['course_name'] + "'>" + course_libs[i]['course_name'] + "</option>";
                    $("select.course_name").empty().append(str);
                });
        });
     $(document).on("change",".course_name",function() {
            $(this).siblings(".cc").blur();
            if($(this).parents("div").attr("id")=='edit-course_lib') {
                var inputValue=$(this).val();
                var data={"action":"course_lib","action_type":"query","action_condition":"2",
                    "course_name":inputValue};
                $.post("php/service.php",data,function(json) {
                    var obj = JSON.parse(json);
                    var course_libs=obj['course_libs'];
                    $(".total_hours").val(course_libs[0]['total_hours']);
                    $(".credit").val(course_libs[0]['credit']);
                    $(".teach_hour").val(course_libs[0]['teach_hour']);
                    $(".test_mode").val(course_libs[0]['test_mode']);
                    $(".week_time").val(course_libs[0]['week_time']);
                    $(".teach_type").val(course_libs[0]['teach_type']);
                });
            }
        });
        //编辑查询课程表
     $(document).on("blur",".cc", function () {
            var f=$(this).parents("form"),c_name=f.find(".course_name").val().trim(),
                n_class=$(this).next("span").attr("class"),t_val=$(this).val().trim(),
                n_cont=$(this).nextAll("input").val().trim();
            if(t_val!="") {
                $.post("php/service.php",{
                    "action":"course_class","action_type":"query","action_condition":"1",
                    "course_name":c_name,"course_dept":t_val
                },function(json) {
                    var course_classs=JSON.parse(json)['course_classs'];
                    if(course_classs.length>0) {
                      course_classs=course_classs[0];f.find("input[type=hidden]").remove();
                      f.append("<input type='hidden' name='cid' value='"+course_classs['course_cid']+"'>");
                      $(".course_room").val(course_classs['course_room']);
                      $(".course_time").val(course_classs['course_time']);
                      $(".course_dept").val(course_classs['course_dept']);
                      $(".course_teacher").val(course_classs['course_teacher']);
                      $(".start_term").val(course_classs['start_term']);
                    }

                });
            }
        });
        //编辑用户查询
     $(document).on("blur",".q_user",function() {
            var data={"action":"user","action_type":"query","action_condition":"single",
            "username":$(this).val()};
            $.post("php/service.php",data, function (json) {
                var user=JSON.parse(json)['users'][0];
                if(user) {
                  $(".id_level").val(user['id_level']);
                  $(".real_name").val(user['real_name']);
                  $(".id_card").val(user['id_card']);
                }
            })
    });
    $(document).on("change","#edit_id",function() {
        $(this).parents("form").find("input[type=text]").val("").next("span").attr("class","");
   });
});
