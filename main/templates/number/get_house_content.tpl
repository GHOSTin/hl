<ol class="breadcrumb">
  <li class="get_streets">
    <a>Город</a>
  </li>
  <li class="get_street_content" street="{{ house.get_street().get_id() }}">
    <a>{{ house.get_street().get_name() }}</a>
  </li>
  <li class="active">дом №{{ house.get_number() }}</li>
</ol>