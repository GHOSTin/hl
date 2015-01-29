<div class="alert {% if type == 'error' %}alert-danger{% else %}alert-success{% endif %}" role="alert">
  <button type="button" class="close" data-dismiss="alert">
    <span aria-hidden="true">&times;</span>
    <span class="sr-only">Close</span>
  </button>
  {{ description }}
</div>