<!DOCTYPE html>
<html>
	<head>
        <meta charset="UTF-8">
        <title>Granny Display</title>
		<!-- CSS -->
		<link rel="stylesheet" href="css/display_style.css">
	</head>
	<body>
        <div id="display-date-wrapper" class="display-wrapper text-center">
            <p id="display-date"><?=utf8_encode(ucfirst(strftime("%A %d %B %Y", $date->getTimestamp())));?></p>
            <p id="display-date-period"><?=ucfirst($day_period);?></p>
        </div>

        <div id="display-description-wrapper" class="display-wrapper alert-border">
            <p id="display-description"><?=nl2br($event->description)?></p>
        </div>
        <div id="display-img-wrapper" style="background-image: url(<?=$event->filename?>)" class="display-wrapper text-center">
        </div>
        <input type="hidden" id="event_hash" value="<?=$event_hash?>" />
        <input type="hidden" id="display_id" value="<?=$display_id?>" />

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script type="text/javascript" src="js/display_common.js"></script>
	</body>
</html>