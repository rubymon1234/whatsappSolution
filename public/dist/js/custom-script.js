  $(document).ready(function(){
        $(".toggle-on").removeClass('active');
        $(".toggle-off").addClass('active');
        $(".toggle-inner").css('width','75px');
        $(".toggle-inner").css('margin-left','-25px');
        var scheduleOn = $(".toggle-on").hasClass('active');
        if(scheduleOn){
            $('.schduleRow').show();
            $('#is_scheduled').val(1);
        }else{
            $('.schduleRow').hide();
            $('#is_scheduled').val(0);
        }
    })
 function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //if (charCode > 31 && (charCode != 46 &&(charCode < 48 || charCode > 57)))//46 '.'
    if (charCode > 31 && ((charCode < 48 || charCode > 57)))
        return false;
    return true;
}
$('#clearNums').on('click',function () {
    $('#mobile').val('');
    $('#num-counter').text(0);
});
$('#removeDups').on('click',function () {
    var numbers = $('#mobile').val();
    var num_array = numbers.split("\n");
    num_array = num_array.filter( function( item, index, inputArray ) {
        return inputArray.indexOf(item) == index;
    });

    for (let i = 0; i < num_array.length; i++) {
      num_array[i] = num_array[i] + "\n";
    }

    num_array = num_array.join("");
    $('#mobile').empty().val(num_array);
    
});
function smsCounter() {
    var msg = $('#message').val();
    var char_count  = msg.length;
    /*var msg_count   = 1;
    if(char_count>15){
        msg_count = char_count / 15;
    }
    var counter_text = char_count +' / ' + msg_count;*/

    var maxLength = 1000;
    var counter_text = maxLength - char_count;
    //alert(msg);
    $('#msg_count_id').empty().html(counter_text);  
}
$("#mobile").on({
           
    keyup: function(){
        checkNumberCount();
    },  
                   
    paste: function(){
        checkNumberCount();
    },
                   
});
function checkNumberCount() {
    var text_val = $('#mobile').val().split("\n");
    var myArrayNew = text_val.filter(function (el) {
        return el != null && el != "";
    });
    //console.log(myArrayNew);
    if(myArrayNew.length > 1000) {
         alert("You've exceeded the 1000 number limit!");
         event.preventDefault(); // prevent characters from appearing
     } else {
        for(var i = 0; i < myArrayNew.length; i++) {
            if(myArrayNew[i].length > 14 ) {
                alert("Mobile number length exceeded");
                event.preventDefault(); // prevent characters from appearing
            }
        }
    }
    if(myArrayNew==''){
        num_counter = 0;
    }else{
        num_counter = myArrayNew.length;
    }
    //console.log(arr.length + " : " + JSON.stringify(arr));
    $('#num-counter').html(num_counter);
}
$(document).on('change', '#message_type', function (e) {
var selected_dt = $(this).val();
$('.sel_image').hide();
$('.sel_document').hide();
$('.sel_audio').hide();
$('.sel_video').hide();
$('.sel_image').prop('required',false);
$('.sel_document').prop('required',false);
$('.sel_audio').prop('required',false);
$('.sel_video').prop('required',false);
$('#message').prop('required',false);
if(selected_dt==''){
    return false;
}else{
    if (selected_dt=='image') {
        $('.sel_image').show();
        $('.sel_image').prop('required',true);
        $('#message').prop('required',true);
        $('.sel_msg').show();
    }
    else if (selected_dt=='document') {
        $('.sel_document').show();
        $('.sel_document').prop('required',true);
        $('#message').prop('required',false);
        $('.sel_msg').hide();
    }
    else if (selected_dt=='audio') {
        $('.sel_audio').show();
        $('.sel_audio').prop('required',true);
        $('#message').prop('required',false);
        $('.sel_msg').hide();            
    }
    else if (selected_dt=='video') {
        $('.sel_video').show();
        $('.sel_video').prop('required',true);
        $('#message').prop('required',true);
        $('.sel_msg').show();
    }
    else{
        $('.sel_image').hide();
        $('.sel_document').hide();
        $('.sel_audio').hide();
        $('.sel_video').hide();

        $('.sel_image').prop('required',false);
        $('.sel_document').prop('required',false);
        $('.sel_audio').prop('required',false);
        $('.sel_video').prop('required',false);
        $('#message').prop('required',true);
        $('.sel_msg').show();

    }
}     
});