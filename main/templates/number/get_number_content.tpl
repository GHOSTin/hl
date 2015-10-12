<ol class="breadcrumb">
  <li class="get_streets">
    <a>Город</a>
  </li>
  <li class="get_street_content" street="{{ number.get_flat().get_house().get_street().get_id() }}">
    <a>{{ number.get_flat().get_house().get_street().get_name() }}</a>
  </li>
  <li class="get_house_content" house="{{ number.get_flat().get_house().get_id() }}">
    <a>дом №{{ number.get_flat().get_house().get_number() }}</a>
  </li>
  <li class="active">кв. №{{ number.get_flat().get_number() }} {{ number.get_fio() }} (л/с №{{ number.get_number() }})</li>
</ol>