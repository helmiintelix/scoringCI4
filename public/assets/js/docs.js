function reinit()
{
    $('.dropdown-menu').dropdown({effect: 'slide'});
}

$(function(){
    $("[data-load]").each(function(){
        $(this).load($(this).data("load"), function(){
            reinit();
        });
    });

    

    $(".history-back").on("click", function(e){
        e.preventDefault();
        history.back();
        return false;
    })
})

