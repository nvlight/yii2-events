/**
* Created by lght on 01.01.2018.
*/


//
$('a.noLink-addEvent').on('click', function (e) {
    e.preventDefault();
    $('#modalAddPost').modal();
    return false;
});

//
$('a.noLink-doFilter').on('click', function (e) {
    e.preventDefault();
    $('#modalSimpleFilter').modal();
    return false;
});

/* */
$('#selectSearchColumn').on('change', function () {
    var sval = $(this).val(), stext = $('#selectSearchColumn').find(":selected").text();
    $('#searchColumn').attr('placeholder', stext);
    if (sval == '3') {
        $('#searchColumn').attr('placeholder', 'Дата: yyyy-mm-dd');
        //console.log('yes - goal, goal!');
    }
});

/* */
$('#searchColumn').on('input', function () {
    var val = $(this).val();
    var iv = val.length;
    if (iv === 0) {
        console.log('поле ввода очищено');
        $.ajax({
            url: '/event/search-by-colval',
            method: 'GET',
            data: {idCol: 6},
            success: function (res, status) {
                var rs = $.parseJSON(res);
                if (rs['success'] === 'yes') {
                    console.log('успешно, получили список');
                    if (rs['rs'].length) {
                        //console.log(rs['rs'].length);
                        //console.log('udalili pagintaion');
                        $('ul.pagination').remove();
                        $('table.gg-history > tbody tr').remove();
                        $("table.gg-history").after(rs['pages']);
                        for (var i = 0; i < rs['rs'].length; i++) {
                            $('table.gg-history').prepend(rs['rs'][i]);
                            //console.log(rs['rs'][i]);
                        }
                    }
                } else {
                    console.log('успешно, не нашли');
                }
            },
            error: function () {

            }
        });
    }
    if (iv >= 3) {
        //console.log('current: '+val);
        $.ajax({
            url: '/event/search-by-colval',
            method: 'GET',
            data: {text: val, idCol: $('#selectSearchColumn option:selected').val()},
            success: function (res, status) {
                var rs = $.parseJSON(res);
                if (rs['success'] === 'yes') {
                    console.log('успешно, что-то нашли');
                    if (rs['rs'].length) {
                        //console.log(rs['rs'].length);
                        $('table.gg-history > tbody tr').remove();
                        $('ul.pagination').remove();
                        for (var i = 0; i < rs['rs'].length; i++) {
                            $('table.gg-history').prepend(rs['rs'][i]);
                            //console.log(rs['rs'][i]);
                        }

                    }
                } else {
                    console.log('успешно, не нашли');
                }
            },
            error: function () {

            }
        });
    }
});

/* */
$('.changeSubmitButton').on('click', function (e) {
    var data = $('form.changeEvent').serialize();
    var params = {};
    params['evid'] = $('#evid').val();
    params['event-summ'] = $('section.changeEventModal input#event-summ').val();
    params['event-desc'] = $('section.changeEventModal input#event-desc').val();

    params['event-catid'] = $('#changeEventModal_catId option:selected').val();
    params['event-date'] = $('#changeEventModal_datePicker').val();
    params['event-typeid'] = $('#changeEventModal_typeId option:selected').val();
    $.ajax({
        url: '/event/update',
        type: 'POST',
        data: params,
        success: function (res) {
            var np = $.parseJSON(res);
            $('#modalEventEdit').modal('hide');
            if (np['success'] === 'yes') {
                var c = $('.actionId_' + np['item']['id']);
                c.find('td[class=item_cat]').text(np['item']['category']['name']);
                c.find('td[class=item_summ]').text(np['item']['summ']);
                c.find('td[class=item_dtr]').text(np['item']['dtr']);
                c.find('td[class=item_desc]').text(np['item']['desc']);
                c.find('td[class=item_type] span').text(np['item']['types']['name'])
                    .css('background-color', '#'+np['item']['types']['color']);
                $('#modalEventEdit').modal('hide');
            }else{
                console.log('Произошла ошибка');
            }
        },
        error: function (res) {
            console.log(res);
        }
    });
    return false;
});

/* */
$('.table.gg-history').on('click', '.evActionUpdate', function () {
    var tid = $(this).data('id');
    $('form.changeEvent').trigger('reset');
    $.ajax({
        url: '/event/get',
        type: 'GET',
        data: {'id': tid},
        success: function (res) {
            var np = $.parseJSON(res);
            if (np['success'] === 'yes') {
                $('section.changeEventModal input#event-summ').val(np['event']['summ']);
                $('section.changeEventModal input#event-desc').val(np['event']['desc']);

                $('#changeEventModal_typeId option[value="' + np['event']['types']['id'] + '"]').prop('selected', true);
                $('#changeEventModal_catId option[value="' + np['event']['i_cat'] + '"]').prop('selected', true);
                $('#changeEventModal_datePicker').val(np['event']['dtr']);
                $('#evid').val(tid);
                $('.changeOkButton').hide();
                $('.changeSubmitButton').show();
                $('#modalEventEdit').modal();
                $('#modalEventEditTitle').text('Редактирование события');
            }else{
                console.log('Произошла ошибка');
            }
        },
        error: function (res) {
            console.log(res);
        }
    });
    return false;
});

/* */
$('.table.gg-history').on('click', 'span > a.evActionView', function showEvent() {
    var id = $(this).data('id');
    //console.log('evActionView ID: '+id);
    $('form.changeEvent').trigger('reset');

    $.ajax({
        url: '/event/get',
        type: 'GET',
        data: {'id': id},
        success: function (res) {
            //console.log(res);
            var np = $.parseJSON(res);
            if (np['success'] === 'yes') {
                $('section.changeEventModal input#event-summ').val(np['event']['summ']);
                $('section.changeEventModal input#event-desc').val(np['event']['desc']);

                $('#changeEventModal_typeId option[value="' + np['event']['types']['id'] + '"]').prop('selected', true);
                $('#changeEventModal_catId option[value="' + np['event']['i_cat'] + '"]').prop('selected', true);
                $('#changeEventModal_datePicker').val(np['event']['dtr']);
                $('#evid').val(id);
                $('#modalEventEdit').modal();
                $('.changeSubmitButton').hide();
                $('.changeOkButton').show();
                $('#modalEventEditTitle').text('Просмотр события');
            }else{
                console.log('Произошла ошибка');
            }
        },
        error: function (res) {
            console.log(res);
        }
    });
    return false;
});

/* */
$('.table.gg-history').on('click', '.evActionDelete', function () {
    if (!confirm('Вы действительно хотите удалить событие?')){
        return false;
    }
    var id = $(this).data('id');
    $.ajax({
        url: '/event/delete',
        type: 'POST',
        data: {'id': id},
        success: function (res) {
            //console.log(res);
            var np = $.parseJSON(res);
            if (np['success'] === 'yes') {
                $('.actionId_' + id).remove();
            }else{
                console.log('Произошла ошибка');
            }
        },
        error: function (res) {
            console.log(res);
        }
    });
    return false;
});

/* simple filter - сбор данных и их отправке в УРЛ-е гет запроса удаленному скрипту */
$('.doFilter').on('click', function () {
    console.log('do simple filter');

    var new_params = $( '#simpleFilter' ).serializeArray();
    var http_build = '';
    for(var i = 0; i < new_params.length; i++) {
        if ( new_params[i]['name'] !== '_csrf' )
            http_build += "" + new_params[i]['name'] + "=" + new_params[i]['value'] + "&";
    }
    var new_path = window.document.location.origin + '/event/simple-filter?' + http_build;
    window.location.href = new_path;
});

//
$('form.addEvent').on('beforeSubmit', function(e){
var data = $(this).serialize();
// console.log('add event by modal form...');
$.ajax({
    url: '/event/add',
    type: 'POST',
    data: data,
    success: function(res){
        //console.log(res);
        var np = $.parseJSON(res);
        $('#modalAddPost').modal('hide');
        if (np['success'] === 'yes'){
            $('form.addEvent').trigger( 'reset' );
        }else{
            console.log('Произошла ошибка');
        }
        $('table.gg-history').prepend(np['trh']);
    },
    error: function(res){
        console.log(res);
    }
});

return false;
});

// check and unckeck all
// stage 1
$('.forSimpleFilter-ckeckAndUncheckAllTypes').find('label').first().find('span').on('click', function () {
//console.log('check all-1');
if (!$(this).parent().find('input').prop("checked")){
    $('#simpleFilterModal_radioCheckBox').find('input').prop("checked", true);
}else{
    $('#simpleFilterModal_radioCheckBox').find('input').prop("checked", false);
}

});
// stage 2
$('.forSimpleFilter-ckeckAndUncheckAll').find('label').first().find('span').on('click', function () {
//console.log('check all-2');
if (!$(this).parent().find('input').prop("checked")){
    $('#simpleFilterModal_radioCheckBox2').find('input').prop("checked", true);
}else{
    $('#simpleFilterModal_radioCheckBox2').find('input').prop("checked", false);
}
});
