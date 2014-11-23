<div class="workgroup-content">
  <ul class="nav nav-pills">
    <li>
      <a href="#" class="get_dialog_add_work">Добавить работу</a>
    </li>
    <li>
      <a href="#" class="get_dialog_rename_workgroup">Переименовать</a>
    </li>
  </ul>
  <ul class="works">
  {% for work in workgroup.get_works() %}
    <li class="work" work_id="{{ work.get_id() }}">{{ work.get_name() }} <a href="#" class="get_dialog_exclude_work">исключить</a></li>
  {% endfor %}
  </ul>
</div>