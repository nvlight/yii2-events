/**
 * Created by lght on 01.01.2018.
 */

//$(document).ready(function () {

    /* */
    $('#selectSearchColumn').on('change', function () {
        var sval = $(this).val(), stext = $('#selectSearchColumn').find(":selected").text();
        $('#searchColumn').attr('placeholder', stext);
        console.log('selectSearchColumn changed... to: ' + stext + ' option: ' + sval);
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
                url: '/web/site/search-by-colval',
                method: 'GET',
                data: {idCol: 5},
                success: function (res, status) {
                    var rs = $.parseJSON(res);
                    if (rs['success'] === 'yes') {
                        console.log('успешно, получили список');
                        if (rs['rs'].length) {
                            //console.log(rs['rs'].length);
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
                url: '/web/site/search-by-colval',
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
        if ($('#changeEventModal_radioId').find('label').last().find('input').prop("checked")) {
            params['event-type'] = 2;
        }

        //console.log('changeEvent by modal form...');
        $.ajax({
            url: '/web/site/change-post-modal',
            type: 'POST',
            data: params,
            success: function (res) {
                //console.log(res);
                var np = $.parseJSON(res);
                //alert(np['message']);
                $('#modalEventEdit').modal('hide');
                if (np['success'] === 'yes') {
                    //console.log('id: '+np['item']['id']);
                    // item['cat']  item['summ'] item['type']
                    // item['dtr']    item['desc'] item['id']
                    var c = $('.actionId_' + np['item']['id']);
                    c.find('td[class=item_cat]').text(np['item']['cat']);
                    c.find('td[class=item_summ]').text(np['item']['summ']);
                    c.find('td[class=item_dtr]').text(np['item']['dtr']);
                    c.find('td[class=item_desc]').text(np['item']['desc']);
                    var itype = c.find('td[class=item_type] span').removeClass('danger').removeClass('success');
                    if (np['item']['type'] == 1) {
                        itype.addClass('success');
                        itype.text('доход');
                    } else {
                        itype.addClass('danger');
                        itype.text('расход');
                    }
                    $('#modalEventEdit').modal('hide');
                    //
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
            url: '/web/site/get-post',
            type: 'GET',
            data: {'id': tid},
            success: function (res) {
                //console.log(res);
                var np = $.parseJSON(res);
                if (np['success'] === 'yes') {
                    $('#event-summ').val(np['event']['summ']);
                    $('#event-desc').val(np['event']['desc']);
                    if (np['event']['type'] === 1) {
                        $('#changeEventModal_radioId').find('label').last().find('input').removeAttr('checked');
                        $('#changeEventModal_radioId').find('label').first().find('input').prop("checked", true);
                    } else {
                        $('#changeEventModal_radioId').find('label').first().find('input').removeAttr('checked');
                        $('#changeEventModal_radioId').find('label').last().find('input').prop("checked", true);
                    }
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
    $('.table.gg-history').on('click', '.evActionDelete', function () {
        if (!confirm('Вы действительно хотите удалить событие?')){
            return false;
        }
        var id = $(this).data('id');
        $.ajax({
            url: '/web/site/event-del',
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
    $('.table.gg-history').on('click', 'span > a.evActionView', function showEvent() {
        var id = $(this).data('id');
        //console.log('evActionView ID: '+id);
        $('form.changeEvent').trigger('reset');

        $.ajax({
            url: '/web/site/get-post',
            type: 'GET',
            data: {'id': id},
            success: function (res) {
                //console.log(res);
                var np = $.parseJSON(res);
                if (np['success'] === 'yes') {
                    $('#event-summ').val(np['event']['summ']);
                    $('#event-desc').val(np['event']['desc']);
                    if (np['event']['type'] === 1) {
                        $('#changeEventModal_radioId').find('label').last().find('input').removeAttr('checked');
                        $('#changeEventModal_radioId').find('label').first().find('input').prop("checked", true);
                    } else {
                        $('#changeEventModal_radioId').find('label').first().find('input').removeAttr('checked');
                        $('#changeEventModal_radioId').find('label').last().find('input').prop("checked", true);
                    }
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
    $('.doFilter').on('click', function () {
        var params = {};
        // 1
        params['event_type'] = '';
        if ($('#simpleFilterModal_radioCheckBox').find('label input').first().is(':checked')) {
            params['event_type'] = '1'
        }
        if ($('#simpleFilterModal_radioCheckBox').find('label input').last().is(':checked')) {
            params['event_type'] = params['event_type'] + ' 2'
        }
        //2
        // .class-catsCheckBox
        // $('.class-catsCheckBox').find('label input').first().is(':checked')
        var c = $('.class-catsCheckBox').find('label input');
        var rc = '';
        $.each(c, function (index, value) {
            if ($(this).is(':checked')) {
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

        //console.log('doFilter: starting...');
        $.ajax({
            url: '/web/site/simple-filter',
            type: 'GET',
            data: params,
            success: function (res, status) {
                //console.log('status: '+status);
                var rs = $.parseJSON(res);
                if (rs['success'] === 'yes') {
                    //console.log('успешно, получили список');
                    if (rs['rs'].length) {
                        //console.log(rs['rs'].length);
                        $('table.gg-history > tbody tr').remove();
                        $('ul.pagination').remove();
                        $("table.gg-history").after(rs['pages']);
                        for (var i = 0; i < rs['rs'].length; i++) {
                            $('table.gg-history').append(rs['rs'][i]);
                            //console.log(rs['rs'][i]);
                        }
                        // и добавляем строку с доходами и расходами
                        $('table.gg-history').append(rs['trs'][0]);
                        $('table.gg-history').append(rs['trs'][1]);
                        $('table.gg-history').append(rs['trs'][2]);
                    }
                } else {
                    alert('Ничего не найдено!');
                    console.log('успешно, не нашли');
                }
            }
            , error: function (res) {
                alert('we got error --- ' + res);
            }
            , beforeSend: function (e) {
                //console.log('beforeSend');
            }
            , complete: function () {
                //console.log('complete');
                $('#modalSimpleFilter').modal('hide');
            }
        });
    });

//});


$('.forSimpleFilter-ckeckAndUncheckAll').find('label').first().find('span').on('click', function () {
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