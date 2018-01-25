<?php
$url_part = ($_SERVER['HTTP_HOST'] === 'yii2.loc:82') ? '' : Yii::$app->params['additional_url_part'];
$js1 = <<<JS

$('#selectSearchColumn').on('change',function() {    
    var sval = $(this).val(), stext = $('#selectSearchColumn').find(":selected").text();
    $('#searchColumn').attr('placeholder',stext);
    console.log('selectSearchColumn changed... to: '+stext+' option: '+sval);
    //console.log(sval);
    //console.log(stext);
});

$('#searchColumn').on('input', function() {
    var val = $(this).val(); var iv = val.length;
    if (iv === 0){
        console.log('поле ввода очищено');
        $.ajax({
            url : '{$url_part}/web/site/search-by-colval',
            method: 'GET',
            data: {idCol:5},
            success: function(res,status) {
              var rs = $.parseJSON(res);
              if (rs['success'] === 'yes'){
                  console.log('успешно, получили список');
                  if (rs['rs'].length) {
                      //console.log(rs['rs'].length);
                      $('table.gg-history > tbody tr').remove(); 
                      $("table.gg-history").after(rs['pages']); 
                      for(var i=0;i<rs['rs'].length;i++){
                        $('table.gg-history').append(rs['rs'][i]); 
                        //console.log(rs['rs'][i]);
                      }   
                  }
              }else{
                console.log('успешно, не нашли');    
              }              
            },
            error: function() {
              
            }
        });
    }
    if (iv >= 3){
        //console.log('current: '+val);
        $.ajax({
            url : '{$url_part}/web/site/search-by-colval',
            method: 'GET',
            data: {text:val,idCol:$('#selectSearchColumn option:selected').val()},
            success: function(res,status) {
              var rs = $.parseJSON(res);
              if (rs['success'] === 'yes'){
                  console.log('успешно, что-то нашли');
                  if (rs['rs'].length) {
                      //console.log(rs['rs'].length);
                      $('table.gg-history > tbody tr').remove();
                      $('ul.pagination').remove();
                      for(var i=0;i<rs['rs'].length;i++){
                        $('table.gg-history').append(rs['rs'][i]); 
                        //console.log(rs['rs'][i]);
                      }
                      
                  }
              }else{
                console.log('успешно, не нашли');    
              }              
            },
            error: function() {
              
            }
        });
    }     
});

JS;
$url_part = ($_SERVER['HTTP_HOST'] === 'yii2.loc:82') ? '' : Yii::$app->params['additional_url_part'];
$js2 = <<<JS
 //function changeEventSubmit() {
 $('.changeSubmitButton').on('click', function(e) {
 //$('form.changeEvent').on('beforeSubmit', function(e){ 
 //var data = $(this).serialize();
 var data = $('form.changeEvent').serialize();  
 var params = {};
 params['evid'] = $('#evid').val(); 
 params['event-summ'] = $('#event-summ').val();
 params['event-desc'] = $('#event-desc').val();
 params['event-catid'] = $('#changeEventModal_catId option:selected').val();
 params['event-date'] = $('#changeEventModal_datePicker').val();
 params['event-type'] = 1;
     if ($('#changeEventModal_radioId').find('label').last().find('input').prop("checked")){
         params['event-type'] = 2;
     } 
 
    console.log('changeEvent by modal form...');
     $.ajax({
         url: '{$url_part}/web/site/change-post-modal',
         type: 'POST',
         data: params,
         success: function(res){
            // doEditAndDel()        
            
            console.log(res);
            var np = $.parseJSON(res);
            //alert(np['message']);
            $('#modalEventEdit').modal('hide');
            if (np['success'] === 'yes'){
                console.log('id: '+np['item']['id']);
                // item['cat']  item['summ'] item['type'] 
                // item['dtr']    item['desc'] item['id']
                var c = $('.actionId_'+np['item']['id']);
                c.find('td[class=item_cat]').text(np['item']['cat']); 
                c.find('td[class=item_summ]').text(np['item']['summ']); 
                c.find('td[class=item_dtr]').text(np['item']['dtr']); 
                c.find('td[class=item_desc]').text(np['item']['desc']); 
                var itype = c.find('td[class=item_type] span').removeClass('danger').removeClass('success');            
                if (np['item']['type'] == 1){
                    itype.addClass('success'); itype.text('доход');  
                }else{
                    itype.addClass('danger');  itype.text('расход');    
                }                                
                $('#modalEventEdit').modal('hide');
                // 
            }
            //$('table').append(np['trh']); 
            
         },
         error: function(res){
           console.log(res);
         }
     });
 
    return false;
 //}
 });
JS;

$js3 = <<<JS
/* */
function editEvent(id){
	console.log('editEvent: '+id);
	var tid = id;
    $('form.changeEvent').trigger( 'reset' );
    $.ajax({
        url: '{$url_part}/web/site/get-post',
        type: 'GET',
        data: {'id':id},
        success: function(res){
            console.log(res);
            var np = $.parseJSON(res);
            if (np['success'] === 'yes'){
                $('#event-summ').val(np['event']['summ']);
                $('#event-desc').val(np['event']['desc']);
                if (np['event']['type'] === 1){
                    $('#changeEventModal_radioId').find('label').last().find('input').removeAttr('checked');
                    //$('#changeEventModal_radioId').find('label').first().find('input').attr('checked','checked');
                    $('#changeEventModal_radioId').find('label').first().find('input').prop("checked", true);
				} else{
                    $('#changeEventModal_radioId').find('label').first().find('input').removeAttr('checked');
					//$('#changeEventModal_radioId').find('label').last().find('input').attr('checked','checked');
                    $('#changeEventModal_radioId').find('label').last().find('input').prop("checked", true);
				}
                $('#changeEventModal_catId option[value="'+np['event']['i_cat']+'"]').prop('selected', true);
               	$('#changeEventModal_datePicker').val(np['event']['dtr']);
                //$('#changeEventModal_datePicker').val('16-11-2017');
                $('#evid').val(tid);
                $('.changeOkButton').hide();
                $('.changeSubmitButton').show();
                $('#modalEventEdit').modal();
                $('#modalEventEditTitle').text('Редактирование события');
            }
        },
        error: function(res){
            console.log(res);
        }
    });
	
};
JS;

$js4 = <<<JS
/* */
function delEvent(vclass,vid){
    //console.log('doEditAndDel-3');
    //e.preventDefault();
    var ch = vid;
    var cl = vclass;
    var act = 'update';
    if (/Delete/.test(cl) === true) {
        act = 'delete';
    }
    console.log('act:'+act+' uid:'+ch);
    if (act === 'delete') {
        var pr = confirm('Вы действительно хотите удалить событие?');
        if (!pr) {
            return false;
        }
    }
    $.ajax({
        url: '{$url_part}/web/site/event-del',
        type: 'POST',
        data: {'action':act,'id':ch},
        success: function(res){
            console.log(res);
            var np = $.parseJSON(res);
            if (np['success'] === 'yes'){
                if (act === 'delete') {
                    $('.actionId_'+ch).remove();
                }
            }
        },
        error: function(res){
            console.log(res);
        }
    });
    return false;
}
JS;

$js5 = <<<JS
/* */
$('.doFilter').on('click', function() {
    var params = {};
    // 1
    params['event_type'] = '';
    if ($('#simpleFilterModal_radioCheckBox').find('label input').first().is(':checked')){
        params['event_type'] = '1'
    }
    if ($('#simpleFilterModal_radioCheckBox').find('label input').last().is(':checked')){
        params['event_type'] = params['event_type'] + ' 2'
    }
    //2
    // .class-catsCheckBox
    // $('.class-catsCheckBox').find('label input').first().is(':checked')
    var c = $('.class-catsCheckBox').find('label input');
    var rc = '';
    $.each(c, function( index, value ) {
      if ($(this).is(':checked')){
        rc += $(this).val() + ' ';     
      }
      //console.log( index + ": " + $(this).val() );  
    });
    params['event_cats'] = rc;
    //3 
    var c1 = $('#mainfilter_dtrange1').val();
    var c2 = $('#mainfilter_dtrange2').val();
    params['range1'] = c1;
    params['range2'] = c2;
    
    console.log('doFilter: starting...'); 
    $.ajax({
        url: '{$url_part}/web/site/simple-filter',
        type: 'GET',
        data: params,
        success: function(res,status) {
            //console.log('status: '+status);
            var rs = $.parseJSON(res);            
            if (rs['success'] === 'yes'){
                  console.log('успешно, получили список');
                  if (rs['rs'].length) {
                      //console.log(rs['rs'].length);
                      $('table.gg-history > tbody tr').remove(); 
                      $('ul.pagination').remove();
                      $("table.gg-history").after(rs['pages']); 
                      for(var i=0;i<rs['rs'].length;i++){
                        $('table.gg-history').append(rs['rs'][i]); 
                        //console.log(rs['rs'][i]);
                      }   
                      // и добавляем строку с доходами и расходами 
                      $('table.gg-history').append(rs['trs'][0]);
                      $('table.gg-history').append(rs['trs'][1]);
                  }
              }else{
                alert('Ничего не найдено!');
                console.log('успешно, не нашли'); 
            }
        }
        ,error: function(res) {
            alert('we got error --- ' + res);
        }
        ,beforeSend: function(e) {
            //console.log('beforeSend');        
        }
        ,complete: function() {
            //console.log('complete');    
            $('#myModal').modal('hide');
        }
    });
});
JS;

$js6 = <<<JS
/* */

$('.evActionView').on('click', function showEvent(){
    var id = $(this).data('id');
	console.log('evActionView ID: '+id);	
    $('form.changeEvent').trigger( 'reset' );
    
    $.ajax({
        url: '{$url_part}/web/site/get-post',
        type: 'GET',
        data: {'id':id},
        success: function(res){
            console.log(res);
            var np = $.parseJSON(res);
            if (np['success'] === 'yes'){
                $('#event-summ').val(np['event']['summ']);
                $('#event-desc').val(np['event']['desc']);
                if (np['event']['type'] === 1){
                    $('#changeEventModal_radioId').find('label').last().find('input').removeAttr('checked');                    
                    $('#changeEventModal_radioId').find('label').first().find('input').prop("checked", true);
				} else{
                    $('#changeEventModal_radioId').find('label').first().find('input').removeAttr('checked');					
                    $('#changeEventModal_radioId').find('label').last().find('input').prop("checked", true);
				}
                $('#changeEventModal_catId option[value="'+np['event']['i_cat']+'"]').prop('selected', true);
               	$('#changeEventModal_datePicker').val(np['event']['dtr']);                
                $('#evid').val(id);
                $('#modalEventEdit').modal();
                $('.changeSubmitButton').hide();
                $('.changeOkButton').show();
                $('#modalEventEditTitle').text('Просмотр события');
            }
        },
        error: function(res){
            console.log(res);
        }
    });
	return false; 
});
JS;

$this->registerJs($js1);
//echo $js1;
$this->registerJs($js2);
$this->registerJs($js3,1);
$this->registerJs($js4,1);
$this->registerJs($js5);
$this->registerJs($js6);

?>