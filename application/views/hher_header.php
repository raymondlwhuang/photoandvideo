<!DOCTYPE html>
<html>
<head>
<title>Responsive Expenses Recorder</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css" />
<?php echo link_tag('css/dialog.css'); ?>
<?php echo link_tag('css/normalize.css'); ?>	
<?php echo link_tag('css/jquery.keypad.css'); ?>
<?php echo link_tag('css/hher.css'); ?>
<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="/scripts/jquery.keypad.js"></script>
<script type="text/javascript">
var ItemResult = <?php echo json_encode($ItemResult); ?>,
optionItem=<?php echo json_encode($optionItem); ?>,
itemPayType=<?php echo json_encode($itemPayType); ?>,
optionSpender=<?php echo json_encode($optionSpender); ?>,
optionCategory=<?php echo json_encode($optionCategory); ?>,
itemPayType=<?php echo json_encode($itemPayType); ?>,
itemBank=<?php echo json_encode($itemBank); ?>,
optionItem=<?php echo json_encode($optionItem); ?>,
optionType=<?php echo json_encode($optionType); ?>,
optionfrequency=<?php echo json_encode($optionfrequency); ?>,
optionBank=<?php echo json_encode($optionBank); ?>,
optionBalance=<?php echo json_encode($optionBalance); ?>,
optionPayFrq=<?php echo json_encode($optionPayFrq); ?>,
optionName=<?php echo json_encode($optionName); ?>,
categoryPayType=<?php echo json_encode($categoryPayType); ?>,
categoryTotal=<?php echo json_encode($categoryTotal); ?>,
defaultPayType=<?php echo json_encode($defaultPayType); ?>,
itemTotal=<?php echo json_encode($itemTotal); ?>;
</script>
<script type="text/javascript" src="/scripts/hher.js"></script>	
	
</head>
