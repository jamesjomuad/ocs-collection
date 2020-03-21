setInterval(function(){
    $.request('collections::onRefresh');
    $.request('debt::onRefresh');
}, 9000);