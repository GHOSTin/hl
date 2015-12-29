$(document).ready(function($){

	$(document).on('click', '.get_documents', function(){
		$.get('get_documents',{
			 id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	});
	$(document).on('click tap', '.get_query_content', function(){
		$.get('get_query_content',{
			 id: $(this).attr('query_id')
			},function(r){
				init_content(r);
			});
	});
	$(document).on('click', '.get_query_title', function(){
		$.get('get_query_title',{
			 id: get_query_id($(this))
			},function(r){
				show_content(r);
			});
	});
	// выводит лицевые счета заявки
	$(document).on('click', '.query-numbers > .ibox-title', function(){
        var $this = $(this);
		if(!$this.parent().hasClass('collapsed')){
			$this.siblings('.ibox-content').empty();
            $this.parent().addClass('collapsed');
		} else
			$.get(
                'get_query_numbers',
                {
				 id: get_query_id($this)
				}
            ).done(function(res){
                $this.parent().removeClass('collapsed');
                init_content(res);
            });
	});
	// выводит пользователей заявки
	$(document).on('click', '.query-users > .ibox-title', function(){
        var $this = $(this);
        if(!$this.parent().hasClass('collapsed')) {
            $this.siblings('.ibox-content').empty();
            $this.parent().addClass('collapsed');
        } else
			$.get(
                'get_query_users',
                {
				 id: get_query_id($this)
				})
                .done(function(res){
                    $this.parent().removeClass('collapsed');
                    init_content(res);
                });
	});
	// выводит работы заявки
	$(document).on('click', '.query-works > .ibox-title', function(){
        var $this = $(this);
        if(!$this.parent().hasClass('collapsed')) {
            $this.siblings('.ibox-content').empty();
            $this.parent().addClass('collapsed');
        } else
			$.get(
                'get_query_works',
                {
                    id: get_query_id($(this))
				})
                .done(function(res){
                    $this.parent().removeClass('collapsed');
                    init_content(res);
                });
	});
		// выводит комментарии заявки
	$(document).on('click', '.query-comments > .ibox-title', function(){
        var $this = $(this);
        if(!$this.parent().hasClass('collapsed')) {
            $this.siblings('.ibox-content').empty();
            $this.parent().addClass('collapsed');
        } else
			$.get(
                'get_query_comments',
                {
                    id: get_query_id($(this))
            }).done(function(res){
                $this.parent().removeClass('collapsed');
                init_content(res);
            });
	});
  $(document).on('click', '.query-files > .ibox-title', function(){
      var $this = $(this);
      if(!$this.parent().hasClass('collapsed')) {
          $this.siblings('.ibox-content').empty();
          $this.parent().addClass('collapsed');
      } else {
      var id = get_query_id($(this));
      $.get('/queries/' + id +'/files/')
          .done(function(res){
              $this.parent().removeClass('collapsed');
              init_content(res);
          });
      }
  });
	$(document).on('click', '.get_query_title', function(){
		$.get('get_query_title',{
			 id: get_query_id($(this))
			},function(r){
				show_content(r);
			});
	});
	$(document).on('click', '.get_search', function(){
		$.get('get_search',{
			},function(r){
				$('.queries').html(r);
			});
	});
  $(document).on('click', '.selections', function(){
    $.get('/queries/selections/',{
      },function(r){
        $('.queries').html(r);
        $('.day_stats').empty();
      });
  });
  $(document).on('click', '.selection_noclose', function(){
    blank();
    $.getJSON('/queries/selections/noclose/',{
      time: $(this).attr('time')
      },function(res){
        $('.queries').html(res.data);
		var compiled = Twig.twig({
			href: '/templates/query/noclose_stats.tpl',
			async: false
		});
		$('.day_stats').html(compiled.render({stat: res.stats.stat}));
        var ctx = $("#chart").get(0).getContext("2d");
        var myNewChart = new Chart(ctx).Pie(res.stats.chart.data, res.stats.chart.options);
      });
  });
	$(document).on('click', '.get_search_result', function(){
		$.get('get_search_result',{
			param: $('.search_parameters').val()
			},function(r){
				$('.queries').html(r);
			});
	});
	$(document).on('click', '.clear_filters', function(){
		$.get('clear_filters',{
			},function(r){
				init_content(r);
        		get_day_stats();
			});
	});
	$('.filter-content-select-status').change(function(){
		$.get('set_status',{
			value: $('.filter-content-select-status :selected').val()
			},function(r){
				$('.queries').html(r);
        get_day_stats();
			});
	});
	$('.filter-content-select-street').change(function(){
		$.get('set_street',{
			value: $('.filter-content-select-street :selected').val()
			},function(r){
				init_content(r);
        get_day_stats();
			});
	});
	$('.filter-content-select-department').change(function(){
		$.get('set_department',{
			value: $('.filter-content-select-department :selected').val()
			},function(r){
				$('.queries').html(r);
        get_day_stats();
			});
	});
	$('.filter-content-select-work_type').change(function(){
		$.get('set_work_type',{
			value: $('.filter-content-select-work_type :selected').val()
			},function(r){
				$('.queries').html(r);
        get_day_stats();
			});
	});
  $('.filter-content-select-query_type').change(function(){
    $.get('set_query_type',{
      value: $('.filter-content-select-query_type :selected').val()
      },function(r){
        $('.queries').html(r);
        get_day_stats();
      });
  });
	$('.filter-content-select-house').change(function(){
		$.get('set_house',{
			value: $('.filter-content-select-house :selected').val()
			},function(r){
				$('.queries').html(r);
        get_day_stats();
			});
	});
	$(document).on('click', '.get_dialog_create_query', function(){
		$.get('get_dialog_create_query',{
			},function(r){
				show_content(r);
			});
	});
  $(document).on('click', '.get_dialog_create_query_from_request', function(){
    $.get('/queries/dialogs/create_query_from_request/',{
        time: $(this).parent().attr('time'),
        number: $(this).parent().attr('number')
      },function(r){
        init_content(r);
      });
  });

  $(document).on('click', '.get_dialog_abort_query_from_request', function(){
    $.get('/queries/dialogs/abort_query_from_request/',{
        time: $(this).parent().attr('time'),
        number: $(this).parent().attr('number')
      },function(r){
        init_content(r);
      });
  });
	$(document).on('click', '.get_dialog_edit_description', function(){
		$.get('get_dialog_edit_description',{
			id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	});
	$(document).on('click', '.get_dialog_edit_reason', function(){
		$.get('get_dialog_edit_reason',{
			id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	});
	$(document).on('click', '.get_dialog_edit_contact_information', function(){
		$.get('get_dialog_edit_contact_information',{
			id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	});
	$(document).on('click', '.get_dialog_change_query_type', function(){
		$.get('get_dialog_change_query_type',{
			id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	});
	$(document).on('click', '.get_dialog_edit_work_type', function(){
		$.get('get_dialog_edit_work_type',{
			id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	});
	$(document).on('click', '.get_dialog_add_comment', function(){
		$.get('get_dialog_add_comment',{
			id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	});
  $(document).on('click', '.get_dialog_edit_visible', function(){
    var id = get_query_id($(this));
    $.get('/queries/' + id + '/dialogs/edit_visible/'
      ,function(r){
        init_content(r);
      });
  });
	$(document).on('click', '.get_dialog_add_user', function(){
		$.get('get_dialog_add_user',{
			id: get_query_id($(this)),
			type: $(this).attr('type')
			},function(r){
				init_content(r);
			});
	})
  $(document).on('click', '.get_dialog_delete_file', function(){
    var id = get_query_id($(this));
    var path = $(this).parent().attr('path');
  	swal({
		title: "Вы уверены?",
		text: "Вы не сможете восстановить удаленный файл!",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Удалить",
		cancelButtonText: "Отмена",
		closeOnConfirm: false
  	}, function () {
		$.get('/queries/{{ query_id }}/files/' + path + '/delete/')
			.done(function (res) {
				$('.query[query_id = ' + id + ' ] .files').html(res);
				swal("Удалено!", "", "success");
			});
	});
  })
	$(document).on('click', '.get_dialog_remove_user', function(){
		$.get('get_dialog_remove_user',{
			id: get_query_id($(this)),
			user_id: $(this).parent().attr('user'),
			type: $(this).parent().attr('type')
			},function(r){
				init_content(r);
			});
	})
	$(document).on('click', '.get_dialog_add_work', function(){
		$.get('get_dialog_add_work',{
			id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	})
	$(document).on('click', '.get_dialog_remove_work', function(){
		$.get('get_dialog_remove_work',{
			id: get_query_id($(this)),
			work_id: $(this).parent().attr('work')
			},function(r){
				init_content(r);
			});
	})
	$(document).on('click', '.get_dialog_close_query', function(){
		$.get('get_dialog_close_query',{
			id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	})

	$(document).on('click', '.get_dialog_reopen_query', function(){
		$.get('get_dialog_reopen_query',{
			id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	})

	$(document).on('click', '.get_dialog_change_initiator', function(){
		$.get('get_dialog_change_initiator',{
			id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	})

	$(document).on('click', '.get_dialog_reclose_query', function(){
		$.get('get_dialog_reclose_query',{
			id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	})

	$(document).on('click', '.get_dialog_cancel_client_query', function(){
		$.get('get_dialog_cancel_client_query',{
			number_id: $(this).parent().parent().attr('number_id'),
			time: $(this).parent().parent().attr('time')
			},function(r){
				init_content(r);
			});
	})

	$(document).on('click', '.get_dialog_accept_client_query', function(){
		$.get('get_dialog_accept_client_query',{
			number_id: $(this).parent().parent().attr('number_id'),
			time: $(this).parent().parent().attr('time')
			},function(r){
				init_content(r);
			});
	})


	$(document).on('click', '.get_dialog_to_working_query', function(){
		$.get('get_dialog_to_working_query',{
			id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	})
    $('#queries-datetimepicker').datetimepicker({
        inline: true,
        format: 'DD.MM.YYYY',
        locale: moment.locale('ru'),
		defaultDate: moment.unix($('.calendar').find('.default-date').val())
    }).on("dp.change", function(e) {
		$.get('get_day', {
			time: e.date.format('X')
		})
		.done(function(res){
			$('.queries').html(res);
			get_day_stats();
		})
	});
  $.get('/queries/requests/count/',
  function(r){
    $('.requests').html(r);
  });
  $.getJSON('/queries/outages/',
  function(r){
    $('.outages').html(r['outages']);
  });
  $(document).on('click', '.get_outages', function(){
    $(this).hide();
    $('.outages .outages-content').show();
  }).on('click', '.outages .close', function(){
    $('.outages .get_outages').show();
    $('.outages .outages-content').hide();
  }).on('click', '.get_requests', function(){
    $.get('/queries/requests/',
      function(r){
        $('.requests').html(r);
      });
  }).on('click', '.close_request_view', function(){
    $.get('/queries/requests/count/',
    function(r){
      $('.requests').html(r);
    });
  });
  get_day_stats();
  get_all_noclose_queries();
});
function get_query_id(obj){
	return obj.closest('.query').attr('query_id');
}

function get_day_stats(){
  $.getJSON('/queries/day/stats/',
    function(res){
      var compiled = Twig.twig({
		  href: '/templates/query/stats.tpl',
		  async: false
	  });
      $('.day_stats').html(compiled.render({stat: res.stat}));
      var ctx = $("#chart").get(0).getContext("2d");
      var options = {
        segmentShowStroke : false,
        animation: false
      };
      var myNewChart = new Chart(ctx).Pie(res.chart, options);
    });
}

function get_all_noclose_queries(){
  $.get('/queries/stats/all/noslose/',
    function(r){
      $('.all_noclose').html('<span class="label label-info">' + r +' незакрытых заявок за весь период</span>');
    });
}

function blank(){
  var compiled = Twig.twig({
	  href: '/templates/spinner.tpl',
	  async: false
  });
  $('.queries').html(compiled.render());
}