



$(function () {
    $("#myCheck").click(function () {
        if($(this).is(':checked')){
            $(".checkbox").prop('checked',true)
        }else{
            $(".checkbox").prop('checked',false)
        }

    });
});
