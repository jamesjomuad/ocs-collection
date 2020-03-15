$(document).ready(function(){
    if(location.hash){
        $('[href="'+location.hash+'"]').click()
    }
});


function print(){
    var printContents = $('#Debt-update-RelationController-payments-view').html();
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
