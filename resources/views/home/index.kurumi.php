<?php $template->startContent('title'); ?>

waduh

<?php $template->stopContent(); ?>


<?php $template->startContent('layouts'); ?>

<h1>Hello World! {{ $nama }}</h1>

<?php $template->stopContent(); ?>

<?php $template->startContent('footer'); ?>

@copyright lutfi aulia sidik

<?php $template->stopContent(); ?>


<?php $template->extendContent('layout/main'); ?>
