<!-- begin filters -->
<div class="filters">  
	<!-- begin filter status -->
	<div class="filter">
	    <div class="filter-name">по статусу заявки</div>
	    <div class="filter-content">
	        <label class="view-select">
	        <select class="filter-content-select-status">
	            <option value="all">Все заявки</option>
	            {% set statuses = {'open' : 'Открытые заявки', 'close' : 'Закрытые заявки', 'reopen' : 'В работе', 'reopen' : 'Переоткрытые'} %}
	            {% for key, status in statuses %}
	                <option value="{{key}}" 
	                {% if key == component.filters.status %}
	                 selected
	                {% endif %}
	                >{{status}}</option>
	            {% endfor %}
	        </select></label>
	    </div>
	</div>
	<!-- end filter status, begin filter street -->
	<div class="filter">
	    <div class="filter-name">по улице и дому</div>
	    <div class="filter-content">
	    	<label class="view-select">
	    		<select class="filter-content-select-street">
	    			<option value="">Все улицы</option>
	       		</select>
	       	</label>
	   		<label class="view-select">
	   			<select class="filter-content-select-house" style="margin:5px 0px 0px 0px;">
	        		<option value="all">Все дома</option>
	   			</select>
	   		</label>
	    </div>
	</div>
	<!-- end filter street, begin filter department -->
	<div class="filter">
	    <div class="filter-name">по участку</div>
	    <div class="filter-content">
	        <label class="view-select">
	        	<select class="filter-content-select-department">
	        		<option value="">Все участки</option>
	        	</select>
	        </label>
	    </div>
	</div>
	<!-- end filter department, begin filter worktype -->
	<div class="filter">
	    <div class="filter-name">по типу работ</div>
	    <div class="filter-content">
	    	<label class="view-select">
	    		<select class="filter-content-select-workType">
	    			<option value="">Все типы</option>
	    		</select>
	    	</label>
	    </div>
	</div>
	<!-- end filter worktype, begin filter users -->
	<div class="filter">
	    <div class="filter-name">по пользователю</div>
	    <div class="filter-content">
	    	<label class="view-select">
	    		<select class="filter-content-select-user">
	            	<option value="all">Все пользователи</option>
	        	</select>
	        </label>
	    </div>
	</div>
	<!-- end filter users -->
</div>
<!-- end filters -->