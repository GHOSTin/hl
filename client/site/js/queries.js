$(document).ready(function(){

  $('.footable').footable();

    $('#showsimple').click(function (){
        // Display a success toast, with a title
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "progressBar": true,
            "preventDuplicates": false,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "400",
            "hideDuration": "1000",
            "timeOut": "7000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        toastr.warning('Можно создать только один запрос в 24 часа.','Внимание!')
    });

  $('#sidebar-nav li').removeClass('active');
  $('#queries').addClass('active');
});
