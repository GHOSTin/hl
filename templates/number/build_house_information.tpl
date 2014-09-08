<h5>Общая информация</h5>
<ul class="unstyled">
  <li>Номер: {{ house.get_number() }}</li>
  <li>Участок: {{ house.get_department().get_name() }} <a class="cm get_dialog_edit_department">изменить</a></li>
</ul>