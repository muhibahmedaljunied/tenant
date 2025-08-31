// noinspection JSCheckFunctionSignatures,ES6ConvertVarToLetConst

var transaction_date;
var period_start = $('#period_start');
var period_end = $('#period_end');
var period = $('#period');
var asset = $('#asset');
var asset_class = $('#asset_class');
function setLabels(label){
    var from_label = $('#from_label');
    var to_label = $('#to_label');
    var labels = {
        "weekly": "الأسبوع",
        "monthly": "الشهر"
    };
    var period_end_year = $('#to_year').parent();
    var period_start = $('#from_year').parent();
    if(label === "yearly") {
        from_label.parent().addClass('hide');
        to_label.parent().addClass('hide');
        period_end_year.removeClass('col-md-6').addClass('col-md-12')
        period_end_year.find(".select2").css({
            "width": "100%"
        })
        period_start.removeClass("col-md-6").addClass("col-md-12");
    } else {
        from_label.parent().removeClass('hide');
        to_label.parent().removeClass('hide');
        from_label.text(labels[label]);
        to_label.text(labels[label])
        period_end_year.removeClass('col-md-12').addClass('col-md-6')
        period_start.removeClass("col-md-12").addClass("col-md-6");
    }
}
function createMonthsList(start = 0){
    var i = 0;
    return moment.localeData('ar').months().slice(start).map((month) => {
        i++;
        return {
            id: i,
            text: month
        }
    });
}
function createMonthlyDropdown(){
    var months = createMonthsList();
    period_start.empty().select2({
        data: months
    }).val(null).trigger("change")
    period_end.empty().select2({
        data: months
    }).val(null).trigger("change")
}
function createYearWeeks(startWeek = 1, year=null){
    var format = 'DD MMM';
    var _moment;
    if(year){
        _moment = moment().year(year);
    }else {
        _moment = moment();
    }
    var weeks = [];
    for(var i = startWeek - 1; i <= _moment.weeksInYear(); i++){
        var week = _moment.week(i);
        var firstDay = week.days(1).format(format);
        var lastDay = week.days(7).format(format);
        var weekNumber = i+1;
        weeks.push({
            'id': weekNumber,
            'text': weekNumber + ': ' + firstDay + ' To ' + lastDay
        })
    }
    return weeks;
}
period_start.select2({data: createYearWeeks() })
period_end.select2({ data: createYearWeeks() })
period_start.on('change', function(e){
    // var data = e.target.children();
    var data = [];
    switch (period.val()){
        case 'weekly':
            data = createYearWeeks(e.target.value)
            break;
        case 'monthly':
            data = createMonthsList(e.target.value)
            break;
        case 'yearly':
            break;
    }
    console.log(period, data);
    var oldValue = period_end.val();
    var keys = Object.keys(data);
    var newValue = keys.includes(oldValue) ? oldValue : null;
    period_end.empty().select2({
        data: data
    }).val(newValue).trigger('change');
})
function createWeeklyDropdown(){
    var _period_start =  $('#period_start');
    var _period_end = $('#period_end');
    var data = createYearWeeks();
    _period_start.empty().select2({
        data: data
    }).val(null).trigger('change');
    _period_end.empty().select2({
        data: data
    }).val(null).trigger('change');
}
period.on('change', function(e){
    var value = e.target.value;
    console.log(value);
    setLabels(value);
    switch(value){
        case 'weekly':
            createWeeklyDropdown();
            break;
        case 'monthly':
            createMonthlyDropdown();
            break;
        case 'yearly':
            break;
    }
    console.log(e.target.value)
});
asset_class.on('change', function(){
    console.log();
    $.ajax({
        url: asset_class.data('url'),
        contentType: 'application/json',
        data: {
            notable: true,
            class_id: asset_class.val(),
            has_transaction: true,
        },
        success(data){
            if(data.length){
                // noinspection JSUnresolvedReference
                asset.select2({
                    data: data.map((item) => ({'id': item.id, 'text': item.asset_name_ar, asset: item})),
                })
            }
        }
    });
});
$('#depreciation_period').on('change', function(e){
    console.log(e.target.value)
});
asset.on('change', function(e){
    var _asset = asset.select2("data")[0].asset;
    var transaction = _asset.transaction;
    var data;
    if(transaction.hasOwnProperty("transaction_date")) {
        transaction_date = moment(transaction.transaction_date);
        switch (period.val()) {
            case 'weekly':
                data = createYearWeeks(transaction_date.week());
                break;
            case 'monthly':
                data = createMonthsList(e.target.value)
                break;
            case 'yearly':
                break;
        }
    }

})
$('.year-picker').yearpicker();
$('#from_year').on('change', function(e){
    $('#to_year').yearpicker({
        startYear: e.target.value
    })
})
