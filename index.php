<?php
	include($_SERVER['DOCUMENT_ROOT'] . '/functions.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Text Compare</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">

		<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
		<link rel="icon" href="favicon.svg" type="image/svg+xml">
		<link rel="alternate icon" href="favicon.ico">
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
		<link rel="manifest" href="/site.webmanifest">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="theme-color" content="#ffffff">

		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="assets/css/style.css">

		<meta property="og:type" content="product">
		<meta property="og:title" content="Text Compare">
		<meta property="og:url" content="<?= base_site_url() ?>">
		<meta property="og:description" content="Сравнение двух текстов и подсветка измененных/удаленных/добавленных предложений">
		<meta property="og:image" content="<?= base_site_url() ?>/assets/images/logo_og.jpg">
	</head>
	<body>
		<div class="page">
			<div class="section">
				<div class="container">
					<h2 class="pb-2">Исходные данные</h2>
					<div class="row mb-3">
						<div class="col-12 col-lg-6">
							<div class="form-group">
								<label for="textA">Исходный текст</label>
								<textarea id="textA" class="form-control textA" style="width: 100%; height: 200px;" placeholder="Введите здесь свой текст...">Предварительные выводы неутешительны: убеждённость некоторых оппонентов влечет за собой процесс внедрения и модернизации поставленных обществом задач. А также тщательные исследования конкурентов, которые представляют собой яркий пример континентально-европейского типа политической культуры, будут объективно рассмотрены соответствующими инстанциями. Равным образом, базовый вектор развития позволяет оценить значение дальнейших направлений развития. Картельные сговоры не допускают ситуации, при которой активно развивающиеся страны третьего мира лишь добавляют фракционных разногласий и объединены в целые кластеры себе подобных. С учётом сложившейся международной обстановки, постоянный количественный рост и сфера нашей активности напрямую зависит от первоочередных требований. Для современного мира социально-экономическое развитие в значительной степени обусловливает важность направлений прогрессивного развития.</textarea>
							</div>
						</div>
						<div class="col-12 col-lg-6">
							<div class="form-group">
							<label for="textB">Текст для сравнения</label>
								<textarea id="textB" class="form-control textB" style="width: 100%; height: 200px;" placeholder="Введите здесь свой текст...">И также тщательные исследования конкурентов, которые представляют собой яркий тому пример континентально-европейского типа политической культуры, будут рассмотрены объективно соответствующими инстанциями. Можно радоваться. Мы молодцы! ! ! Однако, равным образом, базовый вектор развития позволяет оценить значение дальнейших направлений развития. Картельные сговоры не допускают ситуации, при которой активно развивающиеся страны третьего мира лишь добавляют фракционных разногласий и объединены в целые кластеры себе подобных. Для современного мира социально-экономическое развитие в значительной степени обусловливает важность направлений прогрессивного развития.</textarea>
							</div>
						</div>
						<div class="col-12">
							<button id="btn_compare" class="btn btn-primary" type="button">Сравнить</button>
						</div>
					</div>
					<h2 class="pb-2">Тут будут показаны изменения</h2>
					<div class="row mb-3">
						<div class="col-12 col-lg-6">
							<div class="form-group">
								<div id="textResult" class="form-control textResult" style="width: 100%; height: 200px; overflow: auto;"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="float">Показан оригинальный текст</div>
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/scripts.js"></script>
	</body>
</html>