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
    //console.log('selectSearchColumn changed... to: ' + stext + ' option: ' + sval);
    //console.log(sval);
    //console.log(stext);
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
    params['event-summ'] = $('#event-summ').val();
    params['event-desc'] = $('#event-desc').val();

    //$('section.addEventModal input#event-summ').val(np['event']['summ']);
    //$('section.addEventModal input#event-desc').val(np['event']['desc']);

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
                c.find('td[class=item_cat]').text(np['item']['cat']);
                c.find('td[class=item_summ]').text(np['item']['summ']);
                c.find('td[class=item_dtr]').text(np['item']['dtr']);
                c.find('td[class=item_desc]').text(np['item']['desc']);
                c.find('td[class=item_type] span').text(np['item']['types']['name'])
                    .css('background-color', '#'+np['item']['types']['color']);
                $('#modalEventEdit').modal('hide');
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
            }
        },
        error: function (res) {
            console.log(res);
        }
    });
    return false;
});

/* */
$('.doFilter').on('click', function () {
    console.log('do simple filter');

    // старый код, делал аякс запрос с последующим получением данных и их обновлением на странице
    // данный код будет просто перебрасывать пользователя на страницу фильтра, с передачей приведенных параметров хД
    var new_params = $( '#simpleFilter' ).serializeArray();
    var http_build = '';
    for(var i = 0; i < new_params.length; i++) {
        if ( new_params[i]['name'] !== '_csrf' )
            http_build += "" + new_params[i]['name'] + "=" + new_params[i]['value'] + "&";
    }
    var new_path = window.document.location.origin + '/event/simple-filter?' + http_build;
    window.location.href = new_path;

    // console.log( params2 );
    // var params = {};
    // // 1
    // params['Event'] = [];
    // params['Event']['type'] = [];
    // var cd = $('.class-radioCheckBox').find('label input');
    // var tc = []; var i = 0;
    // $.each(cd, function (index, value) {
    //     if ($(this).is(':checked')) {
    //         tc[i] = $(this).val();
    //         i++;
    //     }
    // });
    // if (tc.length) {
    //     params['Event']['type'] = tc;
    // }
    // // 2
    // params['Event']['i_cat'] = [];
    // var c = $('.class-catsCheckBox').find('label input');
    // var rc = []; var i = 0;
    // $.each(c, function (index, value) {
    //     if ($(this).is(':checked')) {
    //         rc[i] = $(this).val();
    //         i++;
    //     }
    // });
    // if (rc.length) {
    //     params['Event']['i_cat'] = rc;
    // }
    //
    // //3
    // var c1 = $('#mainfilter_dtrange1').val();
    // var c2 = $('#mainfilter_dtrange2').val();
    // params['range1'] = c1;
    // params['range2'] = c2;
    // var zs = $("input[name='zero_summ']").prop("checked");
    // if (zs){
    //     params['zero_summ'] = 'yes';
    // }
    //
    // console.log(params);
    // console.log('doFilter: starting...');
    // $.ajax({
    //     url: '/event/simple-filter',
    //     type: 'GET',
    //     data: params2,
    //     success: function (res, status) {
    //         console.log('status: '+status);
    //         var rs = res;
    //         if (rs['success'] === 'yes') {
    //             if (rs['rs'].length) {
    //                 $('table.gg-history > tbody tr').remove();
    //                 $('ul.pagination').remove();
    //                 $("table.gg-history").after(rs['pages']);
    //                 for (var i = 0; i < rs['rs'].length; i++) {
    //                     $('table.gg-history').append(rs['rs'][i]);
    //                 }
    //                 var trs_count = rs['trs'].length;
    //                 for (var i = 0; i < trs_count; i++) {
    //                     $('table.gg-history').append(rs['trs'][i]);
    //                     //console.log(rs['rs'][i]);
    //                 }
    //             }
    //         } else {
    //             console.log('успешно, не нашли');
    //         }
    //     }
    //     , error: function (res) {
    //         alert('we got error --- ' + res);
    //     }
    //     , beforeSend: function (e) {
    //     }
    //     , complete: function () {
    //         $('#modalSimpleFilter').modal('hide');
    //     }
    // });

});

//});

$('form.addEvent').on('beforeSubmit', function(e){
//e.preventDefault();
var data = $(this).serialize();
//alert('add category...')
console.log('add event by modal form...');
$.ajax({
    url: '/event/add',
    type: 'POST',
    data: data,
    success: function(res){
        //console.log(res);
        var np = $.parseJSON(res);
        //alert(np['message']);
        $('#modalAddPost').modal('hide');
        if (np['success'] === 'yes'){
            $('form.addEvent').trigger( 'reset' );
        }
        $('table.gg-history').prepend(np['trh']);

    },
    error: function(res){
        console.log(res);
    }
});

return false;
});

$('.forSimpleFilter-ckeckAndUncheckAllTypes').find('label').first().find('span').on('click', function () {
console.log('check all-1');
// find('input').prop("checked")
if (!$(this).parent().find('input').prop("checked")){
    //console.log('check_all');
    $('#simpleFilterModal_radioCheckBox').find('input').prop("checked", true);
}else{
    //console.log('Uncheck_all');
    $('#simpleFilterModal_radioCheckBox').find('input').prop("checked", false);
}

});

$('.forSimpleFilter-ckeckAndUncheckAll').find('label').first().find('span').on('click', function () {
console.log('check all-2');
// find('input').prop("checked")
if (!$(this).parent().find('input').prop("checked")){
    //console.log('check_all');
    $('#simpleFilterModal_radioCheckBox2').find('input').prop("checked", true);
}else{
    //console.log('Uncheck_all');
    $('#simpleFilterModal_radioCheckBox2').find('input').prop("checked", false);
}

});

//$('#modalSimpleFilter').modal('show');

// <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
//     <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js1"></script>
//     <script>
//     google.charts.load("current", {packages:["corechart"]});
// google.charts.setOnLoadCallback(drawChart);
// function drawChart() {
//     var data = google.visualization.arrayToDataTable([
//         ['Task', 'Hours per Day'],
//         ['Машина',    5],
//         ['Еда',      2],
//         ['Дом',    3]
//     ]);
//
//     var options = {
//         title: 'Диаграмма состояния',
//         pieHole: 0.4,
//     };
//
//     var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
//     chart.draw(data, options);
// }
// </script>