{% extends "private.tpl" %}

{% block content %}
  <div class="content row">
    <div class="page-header">
      <h4>Изменение пароля</h4>
    </div>
    <form id="changePasswordForm" class="form-horizontal" method="post" action="/settings/change_password/">
      <div class="form-group">
        <label for="old_password" class="col-sm-3 control-label required">Старый пароль</label>
        <div class="col-sm-4">
          <input type="password" class="form-control" id="old_password" placeholder="Старый пароль" required>
        </div>
      </div>
      <div class="form-group">
        <label for="new_password" class="col-sm-3 control-label required">Новый пароль</label>
        <div class="col-sm-4">
          <input type="password" class="form-control" id="new_password" placeholder="Новый пароль" required>
        </div>
      </div>
      <div class="form-group">
        <label for="confirm_password" class="col-sm-3 control-label required">Подтверждение пароля</label>
        <div class="col-sm-4">
          <input type="password" class="form-control" id="confirm_password" placeholder="Подтверждение пароля" required>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-3 col-sm-4">
          <button type="submit" class="btn btn-primary pull-right">Изменить</button>
        </div>
      </div>
    </form>
  </div>
{% endblock %}

{% block js %}
  <script>
    $(document).ready(function(){
      $('#sidebar-nav li').removeClass('active');
      $('#settings').addClass('active');
    });
    var frm = $('#changePasswordForm');
    frm.submit(function (ev) {
      $.ajax({
        type: frm.attr('method'),
        url: frm.attr('action'),
        data: {
          "old_password": $('#old_password').val(),
          "new_password": $('#new_password').val(),
          "confirm_password": $('#confirm_password').val()
        },
        success: function (r) {
          $('.alert').remove();
          $('input[id$="_password"]').val("");
          $(r).insertAfter($('.page-header'));
        }
      });
      ev.preventDefault();
    });
  </script>
{% endblock js %}