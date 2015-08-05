$(document).ready(function(){
  $('.streets').change(function(){
    $('.houses').prop('disabled', true).html('<option value="0">Ожидание...</option>');
    $('.flats').prop('disabled', true).html('<option value="0">Ожидание...</option>');
    $('.flat').empty();
    $('.top').empty();
    var id = $('.streets :selected').val();
    if(id > 0){
      $.get('streets/' + id + '/houses/',{
      },function(r){
        $('.houses').html(r).attr('disabled', false);
      });
    }
  });

  $('.houses').change(function(){
    $('.flats').prop('disabled', true).html('<option value="0">Ожидание...</option>');
    $('.flat').empty();
    $('.top').empty();
    var id = $('.houses :selected').val();
    if(id > 0){
      $.get('houses/' + id + '/flats/',{
      },function(r){
        $('.flats').html(r).attr('disabled', false);
      });
    }
  });

  $('.flats').change(function(){
    var house = $('.houses :selected').val();
    var id = $('.flats :selected').val();
    if(id > 0){
      $.get('flats/' + id + '/',{
      },function(r){
        $('.flat').html(r);
      });
    }
    $.get('houses/' + house + '/top/',{
    },function(r){
      $('.top').html(r);
    });
  });
});