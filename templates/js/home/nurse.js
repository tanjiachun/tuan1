function showprompt(msg) {
	$('.alert-box .tip-title').html(msg);
	$('.alert-box').show();
	setTimeout(function() {
		$('.alert-box .tip-title').html('');
		$('.alert-box').hide();
	}, 2000);	
}
function selectevaluate(obj, field, variable) {
    if(field == 'page') {
        page = variable;
    } else {
        page = 1;
    }
    var field_value;
    $('input[type="radio"]').each(function () {
        if($(this).is(':checked')){
            field_value=$(this).val();
        }
    });
    var value=$("#sort").val();
    var url='index.php?act=nurse&op=comment&nurse_id='+nurse_id+'&page='+page+'&field_value='+field_value+'&value'+value;
    $('.comment_details').load(url);
}
$(function() {
	$('.zoomify').zoomify();
	$('.comment_choose').on('click', 'input[type="radio"]', function() {
		var field_value = $(this).val();
		var url = 'index.php?act=nurse&op=comment&nurse_id='+nurse_id+'&page='+page+'&field_value='+field_value;
		$('.comment_details').load(url);
	});
	$("#sort").change(function () {
		var value=$(this).val();
		var field_value;
		$('input[type="radio"]').each(function () {
			if($(this).is(':checked')){
				field_value=$(this).val();
			}
        });
        var url = 'index.php?act=nurse&op=comment&nurse_id='+nurse_id+'&page='+page+'&value='+value+'&field_value='+field_value;
        $('.comment_details').load(url);
    });
    $(".hasContentLabel").click(function(){
    	var content;
        if($("#hasContent").is(':checked')){
        	content='hascontent';
		}else{
        	content='';
		}
		var value=$("#sort").val();
        var field_value;
        $('input[type="radio"]').each(function () {
            if($(this).is(':checked')){
                field_value=$(this).val();
            }
        })
        var url = 'index.php?act=nurse&op=comment&nurse_id='+nurse_id+'&page='+page+'&value='+value+'&field_value='+field_value+'&content='+content;
        $('.comment_details').load(url);
    })
	//$('.search-phone').on('click', function() {
	//	$.getJSON('index.php?act=nurse&op=search_phone&nurse_id='+nurse_id, function(data){
	//		if(data.done == 'true') {
	//			$('#phone-box .tip-icon').html('<i class="iconfont icon-check"></i>');
	//			$('#phone-box .tip-title').html('您好，家政人员电话号码是'+data.nurse_phone);
	//		} else {
	//			$('#phone-box .tip-icon').html('<i class="iconfont icon-error"></i>');
	//			$('#phone-box .tip-title').html(data.msg);
	//		}
	//		Custombox.open({
	//			target : '#phone-box',
	//			effect : 'blur',
	//			overlayClose : true,
	//			speed : 500,
	//			overlaySpeed : 300
	//		});
	//	});
	//});
	
	$('.nurse_state').on('click',function(){
		var nurse_state;
		if($(this).text()=='无法接通'){
			nurse_state=0;
		}else if($(this).text()=='已经工作'){
			nurse_state=1;
		}else if($(this).text()=='通话成功'){
			nurse_state=2;
		}
		$.getJSON('index.php?act=nurse&op=get_state&nurse_id='+nurse_id+'&nurse_state='+nurse_state,function(data){
			if(data.done=="true"){
				Custombox.close();
			}
		})
	})
	$('.search-phone').on('click', function() {
		$.getJSON('index.php?act=nurse&op=get_phone&nurse_id='+nurse_id, function(data){
			//console.log(data);
			if(data.done=="login"){
				Custombox.open({
					target : '#login-box',
					effect : 'blur',
					overlayClose : true,
					speed : 500,
					overlaySpeed : 300,
					open: function () {
						setTimeout(function(){
							window.location.href = 'index.php?act=login';
						}, 3000);
					}
				});
			}else{
		$.getJSON('index.php?act=nurse&op=search_phone&nurse_id='+nurse_id, function(data){
			if(data.done == 'true') {
				$('#phone-box .tip-icon').html('<i class="iconfont icon-check"></i>');
				$('#phone-box .tip-title').html('您好，家政人员电话号码是'+data.nurse_phone);
			} else {
				$('#phone-box .tip-icon').html('<i class="iconfont icon-error"></i>');
				$('#phone-box .tip-title').html(data.msg);
			}
			Custombox.open({
				target : '#phone-box',
				effect : 'blur',
				overlayClose : true,
				speed : 500,
						overlaySpeed : 300
					});
			});	
				$.getJSON('index.php?act=nurse&op=new_time&nurse_id='+nurse_id,function(data){
					if(data.done=="true"){
						Custombox.close();
					}
				})
			}
			//if(data.done == 'true') {
			//	$('#phone-box .tip-icon').html('<i class="iconfont icon-check"></i>');
			//	$('#phone-box .tip-title').html('您好，家政人员电话号码是'+data.nurse_phone);
			//} else {
			//	$('#phone-box .tip-icon').html('<i class="iconfont icon-error"></i>');
			//	$('#phone-box .tip-title').html(data.msg);
			//}
			//Custombox.open({
			//	target : '#phone-box',
			//	effect : 'blur',
			//	overlayClose : true,
			//	speed : 500,
			//	overlaySpeed : 300
			//});
		});
	});
	var favorite_btn = false;
	$('.nurse_focus').on('click', function() {
		var url = 'index.php?act=nurse&op=favorite&fav_id='+nurse_id;
		if(favorite_btn) return;
		favorite_btn = true;
		$.ajax({
			type : "POST",
			url : url,
			contentType: "application/x-www-form-urlencoded; charset=utf-8",
			dataType : "json",
			success : function(data){
				favorite_btn = false;
				if(data.done == 'true') {
					showalert('关注成功', 'succ');
				} else if(data.done == 'login') {
					Custombox.open({
						target : '#login-box',
						effect : 'blur',
						overlayClose : true,
						speed : 500,
						overlaySpeed : 300,
						open: function () {
							setTimeout(function(){
								window.location.href = 'index.php?act=login';
							}, 3000);
						},
					});	
				} else {
					showprompt(data.msg);
				}
			},
			timeout : 15000,
			error : function(xhr, type){
				favorite_btn = false;
				showprompt('网路不稳定，请稍候重试');
			}
		});
    });
	$(".agent_focus").click(function(){
        var url = 'index.php?act=nurse&op=focus&agent_id='+agent_id;
        $.ajax({
            type : "POST",
            url : url,
            contentType: "application/x-www-form-urlencoded; charset=utf-8",
            dataType : "json",
            success : function(data){
                if(data.done == 'true') {
                    showalert('关注成功', 'succ');
                } else if(data.done == 'login') {
                    Custombox.open({
                        target : '#login-box',
                        effect : 'blur',
                        overlayClose : true,
                        speed : 500,
                        overlaySpeed : 300,
                        open: function () {
                            setTimeout(function(){
                                window.location.href = 'index.php?act=login';
                            }, 3000);
                        },
                    });
                } else {
                    showprompt(data.msg);
                }
            },
            timeout : 15000,
            error : function(xhr, type){
                showprompt('网路不稳定，请稍候重试');
            }
        });
	})

    // $(".favorite-add").click(function(){
    //     var url = 'index.php?act=nurse&op=collect&nurse_id='+nurse_id+'&agent_id='+agent_id;
    //     $.ajax({
    //         type : "POST",
    //         url : url,
    //         contentType: "application/x-www-form-urlencoded; charset=utf-8",
    //         dataType : "json",
    //         success : function(data){
    //             if(data.done == 'true') {
    //                 showalert('收藏成功', 'succ');
    //             } else if(data.done == 'login') {
    //                 Custombox.open({
    //                     target : '#login-box',
    //                     effect : 'blur',
    //                     overlayClose : true,
    //                     speed : 500,
    //                     overlaySpeed : 300,
    //                     open: function () {
    //                         setTimeout(function(){
    //                             window.location.href = 'index.php?act=login';
    //                         }, 3000);
    //                     },
    //                 });
    //             } else {
    //                 showprompt(data.msg);
    //             }
    //         },
    //         timeout : 15000,
    //         error : function(xhr, type){
    //             showprompt('网路不稳定，请稍候重试');
    //         }
    //     });
    // })


    $(".myself_resume").click(function () {
        $(".resume_box").show();
        $(".evaluate_box").hide();
    })
    $(".total_evaluate").click(function () {
        $(".resume_box").hide();
        $(".evaluate_box").show();
    });
    $(".nurse_bottom_center>ul").on('click','li',function(){
        $(this).addClass('active');
        $(this).siblings('li').removeClass('active');
    })
    // $('.price-box').on('click', 'li', function() {
    //     var price_type = $(this).attr('price_type');
    //     $('#price_type').val(price_type);
    //     $(this).addClass('active');
    //     $(this).siblings('li').removeClass('active');
    // });
    // $('.time-box').on('click', 'li', function() {
    //     var service_time = $(this).attr('service_time');
    //     $('#service_time').val(service_time);
    //     $(this).addClass('active');
    //     $(this).siblings('li').removeClass('active');
    // });
    function setKeywords() {
        var message=['找做潮州菜的保姆搜   潮州菜','找通风病人医护搜   通风','找清洗吸油烟机保洁搜   油烟机','找修微波炉的维修工搜  微波炉','找修锁的开锁工搜  锁','找管道疏通的师傅搜  管道疏通','找教钢琴的家教搜  钢琴']
        var length=0;
        setInterval(function () {
            $("#keywords").prop('placeholder',message[length]);
            length++;
            if(length==3){
                length=0;
            }
        },6000)
    }
    setKeywords();
    $(".toimg").on('click',function () {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var $target = $(this.hash);
            $target = $target.length && $target || $('[name=' + this.hash.slice(1) + ']');
            if ($target.length) {
                var targetOffset = $target.offset().top;
                $('html,body').animate({scrollTop: targetOffset},800);
                return false;
            }
        }
    })



});