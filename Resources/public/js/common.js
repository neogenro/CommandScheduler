$(function(){
    $('a.command-scheduler-command-log').on('click', function(e){
        e.preventDefault();
        var selector = '.command-scheduler-command-last-log-container-'+$(this).data('id');
        if($(selector).hasClass('hidden')){
            $(selector).removeClass('hidden');
        }
        else{
            $(selector).addClass('hidden');
        }
    })
});