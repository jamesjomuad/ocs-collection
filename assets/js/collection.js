$(document).ready(function(){
    if(location.hash){
        $('[href="'+location.hash+'"]').click()
    }
});