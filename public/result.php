<?php $template->startContent('title'); ?>

kurumi | home

<?php $template->stopContent(); ?>


<?php $template->startContent('layouts'); ?>

<h1>Hello World! <?= $nama ?></h1>

<?php echo htmlspecialchars($nama) ?>

<?php echo $nama ?>

<?php 1 + 1 ?>

<?php $template->stopContent(); ?>

<?php $template->startContent('footer'); ?>

@copyright lutfi aulia sidik

<?php $template->stopContent(); ?>


<?php $template->extendContent('layout/main'); ?>
