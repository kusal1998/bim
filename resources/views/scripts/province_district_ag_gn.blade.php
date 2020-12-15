<script>
@if(Request::segment(2)=='create')
$(document).ready(function(){
    var urlseg1='{{url("/get-gns-by-ag/")}}';
    var ag_div='{{Auth::user()->branch_id}}';
    var lan="{{trans('sentence.lang')}}";
        $.ajax({url:urlseg1+'/'+ag_div,type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else{
                    if(response.length>0){
                        //$('#gn_div_id').prop('disabled', false);
                        $('#gn_div_id').empty().append('<option value="">Please Select</option>');
                        $(response).each(function(i,element){
                            if(lan=='EN')
                            {
                            $('#gn_div_id').append('<option value="'+element.id+'">'+element.gn_name+'</option>');
                            }
                            else
                            {
                            $('#gn_div_id').append('<option value="'+element.id+'">'+element.sinhala_name+'</option>');
                            }
                        });
                    }else{
                       // $('#gn_div_id').prop('disabled', true);
                        $('#gn_div_id').empty().append('<option value="">Please Select</option>');
                    }
                }
            }else{
                alert('Information not available');
            }
        }});
});
@endif
$("#province_id").change(function(){
    if($("#province_id").val()==''){
        alert('Please select a valid province..')
    }else{
        var urlseg1='{{url("/get-districts-by-province/")}}';
        $.ajax({url:urlseg1+'/'+$("#province_id").val(),type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else{
                   // console.log(response);
                    if(response.length>0){
                        $('#district_id').prop('disabled', false);
                        $('#district_id').empty().append('<option value="">Please Select</option>');
                        $(response).each(function(i,element){
                            //console.log(element);
                            $('#district_id').append('<option value="'+element.id+'">'+element.districts_name+'</option>');
                        });
                    }else{
                        $('#district_id').prop('disabled', true);
                        $('#district_id').empty().append('<option value="">Please Select</option>');
                    }
                }
            }else{
                alert('Information not available');
            }
        }});
    }
});
$("#district_id").change(function(){
    if($("#district_id").val()==''){
        alert('Please select a valid province..')
    }else{
        var urlseg1='{{url("/get-ags-by-district/")}}';
        $.ajax({url:urlseg1+'/'+$("#district_id").val(),type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else{
                   // console.log(response);
                    if(response.length>0){
                        $('#ag_div_id').prop('disabled', false);
                        $('#ag_div_id').empty().append('<option value="">Please Select</option>');
                        $(response).each(function(i,element){
                            //console.log(element);
                            $('#ag_div_id').append('<option value="'+element.id+'">'+element.ag_name+'</option>');
                        });
                    }else{
                        $('#ag_div_id').prop('disabled', true);
                        $('#ag_div_id').empty().append('<option value="">Please Select</option>');
                    }
                }
            }else{
                alert('Information not available');
            }
        }});
    }
});
$("#ag_div_id").change(function(){
    if($("#ag_div_id").val()==''){
        alert('Please select a valid province..')
    }else{
        var urlseg1='{{url("/get-gns-by-ag/")}}';
        $.ajax({url:urlseg1+'/'+$("#ag_div_id").val(),type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else{
                    if(response.length>0){
                        $('#gn_div_id').prop('disabled', false);
                        $('#gn_div_id').empty().append('<option value="">Please Select</option>');
                        $(response).each(function(i,element){
                            $('#gn_div_id').append('<option value="'+element.id+'">'+element.gn_name+'</option>');
                        });
                    }else{
                        $('#gn_div_id').prop('disabled', true);
                        $('#gn_div_id').empty().append('<option value="">Please Select</option>');
                    }
                }
            }else{
                alert('Information not available');
            }
        }});
    }
});
@if(Request::segment(1)=='12th-sentence')
var villages = '';
$("#gn_div_id").select2();
$("#gn_div_id").on("select2:select", function (evt) {
    if($("#gn_div_id").val()==''){
        alert('Please select a valid province..')
    }else{
      //  alert($("#gn_div_id").val());
      var element = evt.params.data.element;
        var $element = $(element);  
        $element.detach();
        $(this).append($element);
        $(this).trigger("change");
        

        var urlseg1='{{url("/get-villages-by-gn/")}}';
        $.ajax({url:urlseg1+'/'+$("#gn_div_id").val(),type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else{
                    if(response.length>0){
                        $('#village_id').prop('disabled', false);
                       // $('#village_id').append('<option value="">Please Select</option>');
                        $(response).each(function(i,element){
                            $('#village_id').append('<option data-id="'+element.gn_division+'" value="'+element.id+'"  >'+element.village+'</option>');
                        });
                        $("#village_id").select2();
                    }else{
                       // $('#village_id').prop('disabled', true);
                       // $('#village_id').empty().append('<option value="">Please Select</option>');id="'+element.gn_division+'"
                    }
                }
            }else{
                alert('Information not available');
            }
        }});
    }
});

$("#gn_div_id").on("select2:unselect", function (e) {
    var villages= [];
    var data='';
    var gnid = e.params.data.id;
    var selectobject = document.getElementById("village_id");
     for (var i=0; i<selectobject.length; i++) {
         if (selectobject.options[i].getAttribute('data-id') == gnid){
             selectobject.remove(i);
             i=i-1;
         }
     }
    //   $("#village_id option:selected").each(function(i){
       
    //          if($(this).attr('id') == gnid){  
    //           $(this).removeAttr('data-select2-id');  
    //           $("#village_id option[id='"+gnid+"']").detach();   
    //          }else{
    //               villages.push( $(this).val() );
    //               $(this).prop('selected');
    //          }

    // //      //console.log(element);
    //   });
   
    //   console.log(villages.toString());
    //   data = villages.toString();
    //   $("#village_id").val(villages).trigger("change"); 
    

});

@else
$("#gn_div_id").change(function(){
   
    if($("#gn_div_id").val()==''){
        alert('Please select a valid province..')
    }else{
        var urlseg1='{{url("/get-villages-by-gn/")}}';
        $.ajax({url:urlseg1+'/'+$("#gn_div_id").val(),type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else{
                    if(response.length>0){
                        $('#village_id').prop('disabled', false);
                        $('#village_id').append('<option value="">Please Select</option>');
                        $(response).each(function(i,element){
                            $('#village_id').append('<option value="'+element.village+'">'+element.village+'</option>');
                        });
                    }else{
                        $('#village_id').prop('disabled', true);
                        $('#village_id').empty().append('<option value="">Please Select</option>');
                    }
                }
            }else{
                alert('Information not available');
            }
        }});
    }
});
@endif
</script>
