<?php

namespace PHPMaker2021\mandrake;

// Page object
$ErrorPage = &$Page;
?>
<?php
	$error = '<span class="glyphicon glyphicon-exclamation-sign"></span> Error de enlace; Click aqui para continuar.';
	echo '<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-warning" onclick="js:window.location.href = \'index.php\';">' . $error .'</button>
			</div>
		</div>';
?>

<?= GetDebugMessage() ?>
