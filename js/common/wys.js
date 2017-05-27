$M={};
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
    });
};
$M.init=function(ele) {
    this.execContent("click",".wys");
    this.execContent("blur",".wys2");
    this.execContent("change",".wys1");
    var childDOM='<button class="wys" data-exec="bold">加粗</button><button class="wys" data-exec="italic">倾斜</button><button class="wys" data-exec="underline">下划线</button><button class="wys" data-exec="indent">缩进文本</button><button class="wys" data-exec="outdent">减少缩进</button><button class="wys" data-exec="insertorderedlist">有序列表</button><button class="wys" data-exec="insertunorderedlist">无序列表</button><button class="wys" data-exec="justifycenter">居中对齐</button><button class="wys" data-exec="justifyleft">左对齐</button><button class="wys" data-exec="inserthorizontalrule">插入水平线</button><select class="wys1"><option value="" >请选择字体大小：</option><option value="1" class="wys" data-exec="fontsize,1">1号字体</option><option value="2" class="wys" data-exec="fontsize,2">2号字体</option><option value="3" class="wys" data-exec="fontsize,3">3号字体</option><option value="4" class="wys" data-exec="fontsize,4">4号字体</option><option value="5" class="wys" data-exec="fontsize,5">5号字体</option><option value="6" class="wys" data-exec="fontsize,6">6号字体</option><option value="7" class="wys" data-exec="fontsize,7">7号字体</option></select><br><label >字体颜色</label><input type="color" class="wys2" data-exec="forecolor,color" placeholder="请输入颜色值，英文字符串或16进制均可">';
    ele.html(childDOM);
};/**
 * Created by Administrator on 2017/2/15.
 */
