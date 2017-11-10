/**
 * Created by Administrator on 2017/6/30 0030.
 */
(function($){
    $(".checkitem").each(function(){
        var nurse_id=$(this).attr('data');
        var that=$(this)
        $.getJSON('admin.php?act=nurse&op=get_status&nurse_id='+nurse_id,function(data){
            if(data.get_time.get_time!==''&&data.get_time.get_time!==undefined){
                that.parent().siblings('.getTime').text(data.get_time.get_time);
            }
            if(data.get_time.nurse_status!==''&&data.get_time.nurse_status!==undefined){
                if(data.get_time.nurse_status=='0'){
                    that.parent().siblings('.lastStatus').text('无法接通');
                }else if(data.get_time.nurse_status=='1'){
                    that.parent().siblings('.lastStatus').text('已经工作');
                }else if(data.get_time.nurse_status=='2'){
                    that.parent().siblings('.lastStatus').text('通话成功');
                }
            }
        })
    });
    function swapRow(i, k) {
        var tb = $(".goods_tbody").find("tr");
        $(tb).eq(k).insertBefore($(tb).eq(i));
        $(tb).eq(i).insertAfter($(tb).eq(k));
    }
    $(".sort").on('click',function(){
        var tb = $(".goods_tbody").find("tr");
        var total = tb.length;
        //外层循环，共要进行arr.length次求最大值操作
        for (var i = 0; i < total - 1; i++)
        {
            //内层循环，找到第i大的元素，并将其和第i个元素交换
            for (var j = i; j < total - 1; j++)
            {
                var v = tb.children('.notAvailable').text()[i];
                var v2 = tb.children('.notAvailable').text()[j];
                if (v < v2)
                {
                    //交换两个元素的位置
                    swapRow(i, j);
                    tb = $(".goods_tbody").find("tr");
                }
            }
        }
        return;
    })
    $(".sort1").on('click',function(){
        var tb = $(".goods_tbody").find("tr");
        var total = tb.length;
        //外层循环，共要进行arr.length次求最大值操作
        for (var i = 0; i < total - 1; i++)
        {
            //内层循环，找到第i大的元素，并将其和第i个元素交换
            for (var j = i; j < total - 1; j++)
            {
                var v = tb.children('.onWorking').text()[i];
                var v2 = tb.children('.onWorking').text()[j];
                if (v < v2)
                {
                    //交换两个元素的位置
                    swapRow(i, j);
                    tb = $(".goods_tbody").find("tr");
                }
            }
        }
        return;
    })
    $(".stateOrder").on('click',function(e){
        //window.location.reload();
        var e = event || window.event;
        var scrollX = document.documentElement.scrollLeft || document.body.scrollLeft;
        var scrollY = document.documentElement.scrollTop || document.body.scrollTop;
        var x = e.pageX || e.clientX + scrollX;
        var y = e.pageY || e.clientY + scrollY;
        $("#phone-box").css("top",y-30+'px');
        $("#state-box").css("top",y-30+'px');
        $("#phone-box").css("left",x+50+'px');
        $("#state-box").css("left",x+50+'px');
        $(".nurse_name").text($(this).parent().children('td').eq(1).text()+':');
        $(".reviseState").attr('data',$(this).attr('data'));
        var nurse_id=$(this).attr('data');
        $.getJSON('admin.php?act=nurse&op=state_bain&nurse_id='+nurse_id,function(data){
            if(data.done=='true'){
                if(data.state_cideci==1){
                    $("#hunting").prop("checked",true);
                }else{
                    $("#hunting").prop("checked",false);
                }
                if(data.state_cideci==2){
                    $("#working").prop("checked",true);
                }else{
                    $("#working").prop("checked",false);
                }
                if(data.state_cideci==3){
                    $("#holiday").prop("checked",true);
                }else{
                    $("#holiday").prop("checked",false);
                }
                if(data.state_cideci==4){
                    $("#trouble").prop("checked",true);
                }else{
                    $("#trouble").prop("checked",false);
                }
                if(data.state_cideci==5){
                    $("#unknow").prop("checked",true);
                }else{
                    $("#unknow").prop("checked",false);
                }
            }
            $("#phone-box").show();
            $("#state-box").show();

        });
    })
    $(".btn-quxiao").click(function(){
        $("#phone-box").hide();
        $("#state-box").hide();
    });
    $(".reviseState").on('click',function(){
        var state_cideci=0;
       if($("input[type='radio']:checked").val()!==undefined){
           state_cideci=$("input[type='radio']:checked").val();
       }else {
           state_cideci=0;
       }
        var nurse_id=$(this).attr('data');
        var url='admin.php?act=nurse&op=state_cideci';
        var submitData={
            'state_cideci':state_cideci,
            'nurse_id':nurse_id
        }
        $.ajax({
            type : "POST",
            url : url,
            data:submitData,
            contentType: "application/x-www-form-urlencoded; charset=utf-8",
            dataType : "json",
            success:function(data){
                console.log(data)
            },
            error:function(data){
                console.log(data.msg);
            }
        });
        $("#phone-box").hide();
        $("#state-box").hide();
    })
    $("#stateSelect").change(function () {
        var value=$(this).val();
        if(value==0){
            $("tbody").show();
        }else{
            $("tbody").each(function(){
                var data=$(this).attr('data');
                if(data==undefined||data==value){
                    $(this).show()
                }else{
                    $(this).hide();
                }

            })
        }

    });
})(jQuery)

