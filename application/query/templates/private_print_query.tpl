{% if component.queries != false %}
	{% set query = component.queries[0] %}
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>Печатная форма заявки №{{query.number}} от {{query.time_open|date('H:i d.m.Y')}}</title>
			<style>
				body {font-size:10pt; margin: 0mm 0mm 0mm 0mm; padding: 0mm 0mm 0mm 0mm;}
				.main-block table {border-collapse: collapse; border-spacing: 0mm;}
				.ttle {font-size:14pt; font-weight:900;}
				.main {border:1px solid grey; width:200mm; padding: 0mm 0mm 5mm 0mm;}
				.main-block {width:194mm; padding:1mm 2mm 2mm 2mm; };
			</style>
		</head>
		<body>
		<div class="main">
			<!-- begin 1 block -->
			<div class="main-block">
				<div class="ttle">Заявка №{{query.number}} от {{query.time_open|date('H:i d.m.Y')}}</div>
				<div class="ttle">
						{{query.street_name}}, дом №{{query.house_number}}
				</div>
				<div class="ttle">{{query.description}}</div>
				<table style="padding:5mm 0mm 0mm 0mm;">
				{% for i in 1..6 %}
					<tr>
								<td>{{i}}</td>
								<td style="border-bottom: 1px solid black; width:70%; height:8mm;"></td>
								<td style="height:8mm;">с</td>
								<td style="border-bottom: 1px solid black; width:4%; height:8mm;"></td>
								<td style="border-bottom: 1px solid black; width:4%; height:8mm;"></td>		
								<td style="height:8mm;">до</td>
								<td style="border-bottom: 1px solid black; width:4%; height:8mm;"></td>
								<td style="border-bottom: 1px solid black; width:4%; height:8mm;"></td>		
								<td style="height:8mm;">-</td>
								<td style="border-bottom: 1px solid black; width:15%; height:8mm;"></td>		
								<td style="height:8mm;">г.</td>													
							</tr>
				{% endfor %}
				</table>
				<div style="font-size:6pt; margin: 5mm 0mm 0mm 0mm;">Все необходимые работы выполнены в срок, качественно и в полном объеме. Оплата за выполненые работы с Заявителя не взималась. Заявитель не имеет претензий, связанных с исполнением заявки. С правилами и условиями эффективного и безопасного использования результатов оказанных услуг ( выполненных работ ) заявитель ознакомлен.</div>
				<table style="margin: 2mm 0mm 0mm 0mm;">
					<tr>
						<td>Работы </td>
						<td> принял</td>
						<td style="border-bottom: 1px solid black; width:60%;"></td>
						<td>Подпись</td>
						<td style="border-bottom: 1px solid black; width:20%;"></td>
						<td>Телефон</td>
						<td style="border-bottom: 1px solid black; width:20%;"></td>
					</tr>
				</table>		
		</div>
		<!-- end 1 block, begin 2 block -->
		<div class="main-block">
			<!--
		if($ctl->data->self->initiator === 'number') $pr .= '
		<div>Владелец '.$query->numbers->data->self['default'][0]->fio.'</div>';

		if(!empty($query->contactName) OR !empty($query->contactTelephone) OR !empty($query->contactCellphone)){

			$pr .= '<div>Заявку подал ';

			if(!empty($query->contactName)) $pr .= ' '.iconv_substr($query->contactName,0,20,'utf-8');
			if(!empty($query->contactTelephone)) $pr .= ' тел. '.$query->contactTelephone;
			if(!empty($query->contactCellphone)) $pr .= ' сот. '.$query->contactCellphone;

			$pr .= '</div>';
		}
		-->
			<table style="margin:2mm 0mm 0mm 0mm; width:194mm">
				<tr>
					<td style="border-bottom: 1px solid black; width:49%; height:8mm;">Сотрудники: диспетчер</td>
					<td style="width:1%; height:8mm;"></td>
					<td style="border-bottom: 1px solid black; width:50%; height:8mm;">Отметки:</td>
				</tr>
				<tr>
					<td style="border-bottom: 1px solid black; width:49%; height:8mm;"></td>
					<td style="width:1%; height:8mm;"></td>
					<td style="border-bottom: 1px solid black; width:50%; height:8mm;"></td>
				</tr>
				<tr>
					<td style="border-bottom: 1px solid black; width:49%; height:8mm;"></td>
					<td style="width:1%; height:8mm;"></td>
					<td style="border-bottom: 1px solid black; width:50%; height:8mm;"></td>
				</tr>			
			</table>
		</div>
	<!-- end 2 block -->
	</div></body></html>
{% endif %}