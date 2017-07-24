<!DOCTYPE html>
<html>
	<head>
        <meta charset="UTF-8">
        <meta http-equiv="refresh" content="30" />
		<title>Granny Display</title>
		<!-- CSS -->
		<link rel="stylesheet" href="css/style.css">
	</head>
	<body id="display-body">


            <div id="display-date-wrapper" class="display-wrapper text-center">
                <p id="display-date"><?=utf8_encode(ucfirst(strftime("%Aen den %d %B %Y", $date->getTimestamp())));?></p>
            </div>

            <div id="display-description-wrapper" class="display-wrapper alert-border">
                <p id="display-title"><?=$event->title?></p>
                <p id="display-description"><?=nl2br($event->description)?></p>
            </div>
            <div id="display-img-wrapper" class="display-wrapper text-center">
                <img id="display-img" src="<?=$event->filename?>">
            </div>

            <!-- JAVASCRIPT -->
        <script type="text/javascript" src="js/common.js"></script>
	</body>
</html>