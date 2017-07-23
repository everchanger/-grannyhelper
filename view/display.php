<div class="col-xs-12">
    <div class="col-xs-6">
        <h1 id="display-title"><?=$event->title?></h1>
    </div>
    <div class="col-xs-6">
        <h1><?=$date->format('Y-m-d')?></h1>
    </div>
    <div class="col-xs-6">
        <h3 id="display-description"><?=$event->description?></h3>
    </div>
    <div class="col-xs-6">
        <img id="display-img" src="<?=$event->filename?>">
    </div>
</div>