$(document).ready(function(){
    getActualRequests()();
});

function getActualRequests(){
    var template = Twig.twig({
        href: '/templates/registrations/requests.tpl',
        async: false
    });
    $.get('/system/registrations/requests/open/',{
        },function(r){
            $('.requests').html(template.render({'requests': r}));
        });
}