<?php $site_page = getPage($nav_menu, $id) ?>

<h1 class="page__header"><?php echo $site_page->name ?></h1>

<?php if($site_page->description) :?>
	<div class="page__details"><?php echo $site_page->description ?></div>
<?php endif; ?>

