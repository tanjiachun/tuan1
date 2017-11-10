function showwarning(msg) {
	$('#tip').show();
	$('#tip_content').html(msg);
	setTimeout(function(){
		$('#tip').hide();
		$('#tip_content').html('');
	},3000);		
}

function endtime_open(nurse_id, nurse_name) {
	$('.edit-flow').remove();
	var html = '<div class="edit-flow" id="endtime_box_'+nurse_id+'"><div class="edit-content edit-order-content confirmation"><div class="sku-item">确认向'+nurse_name+'收取年费吗？</div></div><div class="act-edit"><a href="javascript:;" class="btn btn_default" onclick="endtime_close(\''+nurse_id+'\')">取消</a>&nbsp;&nbsp;<a href="javascript:;" class="btn btn_primary" onclick="endtime_confirm(\''+nurse_id+'\')">确定</a></div></div>';
	$('#endtime_'+nurse_id).append(html);
}

function endtime_close(nurse_id) {
	$('#endtime_box_'+nurse_id).remove();
}

var endtime_confirm_btn = false;
function endtime_confirm(nurse_id) {
	var formhash = $('#formhash').val();
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'nurse_id' : nurse_id,
	};
	if(endtime_confirm_btn) return;
	endtime_confirm_btn = true;
	$.ajax({
		type : 'POST',
		url : 'admin.php?act=pnurse&op=endtime',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			endtime_confirm_btn = false;
			if(data.done == 'true') {
				showwarning('操作成功');
				$('.edit-flow').remove();
				$('#endtime_'+nurse_id).html(data.endtime);
				$('#available_'+nurse_id).html(data.available);
			} else {
				showwarning(data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			endtime_confirm_btn = false;
			showwarning('网路不稳定，请稍候重试');
		}
	});	
}

function cash_open(nurse_id, nurse_name) {
	$('.edit-flow').remove();
	html = '<div class="edit-flow" id="cash_box_'+nurse_id+'"><div class="edit-content edit-order-content"><div class="sku-item"><label class="sku-caption"></label>&nbsp;&nbsp;确定向'+nurse_name+'汇款？</div><div class="sku-item"><label class="sku-caption">汇款金额</label>&nbsp;&nbsp;<input class="form_input input_xxlarge" type="text" id="cash_amount" name="cash_amount" value=""></div></div><div class="act-edit"><a href="javascript:;" class="btn btn_default" onclick="cash_close('+nurse_id+')">取消</a>&nbsp;&nbsp;<a href="javascript:;" class="btn btn_primary" onclick="cash_confirm('+nurse_id+')">确定</a></div></div>';
	$('#nurse_'+nurse_id).append(html);
}

function cash_close(nurse_id) {
	$('#cash_box_'+nurse_id).remove();
}

var cash_confirm_btn = false;
function cash_confirm(nurse_id) {
	var formhash = $('#formhash').val();
	var cash_amount = $('#cash_amount').val();
	if(cash_amount == '') {
		showwarning('请输入汇款金额');
		return;	
	}
	var submitData = {
		'form_submit' : 'ok',
		'formhash' : formhash,
		'nurse_id' : nurse_id,
		'cash_amount' : cash_amount,
	};
	if(cash_confirm_btn) return;
	cash_confirm_btn = true;
	$.ajax({
		type : 'POST',
		url : 'admin.php?act=pnurse&op=cash',
		data : submitData,
		contentType: 'application/x-www-form-urlencoded; charset=utf-8',
		dataType : 'json',
		success : function(data){
			cash_confirm_btn = false;
			if(data.done == 'true') {
				showwarning('操作成功');
				$('.edit-flow').remove();
				$('#available_'+nurse_id).html(data.available);
			} else {
				showwarning(data.msg);
			}
		},
		timeout : 15000,
		error : function(xhr, type){
			cash_confirm_btn = false;
			showwarning('网路不稳定，请稍候重试');
		}
	});	
}