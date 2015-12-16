<nav class="row calendar">
  <div class="col-md-3 col-lg-3">
    <div class="ibox-content m-t m-b">
      <div id="events-datetimepicker"></div>
    </div>
  </div>
</nav>
<div class="row">
  <div class="col-md-2">
    <a class="get_dialog_create_event"><i class="fa fa-plus"></i></a>
  </div>
  <div class="col-md-10">
    <ul class="events list-unstyled">{% include "events/events.tpl" %}</ul>
  </div>
</div>