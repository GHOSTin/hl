<div class="workgroup-content">
  <ul class="nav nav-pills">
    <li>
      <a href="#" class="get_dialog_add_work">Добавить работу</a>
    </li>
  </ul>
  <ul class="works">
  {% for work in response.workgroup.get_works() %}
    <li>{{ work.get_name() }}</li>
  {% endfor %}
  </ul>
</div>