<?php
/**
 * Generic error page for errors on web. Note that this is NOT a full View model, and the template
 * and other classes are not available.
 * This view should be as simple as humanly possible to avoid any potential cascade in errors,
 *
 * @var Exception $e
 * @var array     $trace
 * @var string    $file
 * @var string    $line
 * @var string    $message
 * @var string    $code
 */

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sorry, there was a problem</title>
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
	<!-- Begin page content -->
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-push-3">
				<div class="panel panel-danger">
					<div class="panel-heading">Sorry, there was an error processing your request</div>
					<div class="panel-body">
						<p>
							<strong><?=HTML::chars($message)?></strong>
							<small><?=HTML::chars($class)?></small>
						</p>
						<p>
							Please refresh your page to try again, or use the back button to go back.
							If this keeps happening please report it.
						</p>
					</div>
					<div class="panel-footer">
						<small>At <?=Debug::path($file)?> [ <?=HTML::chars($line);?> ] </small>
						<ul>
							<?php foreach($trace as $step): ?>
								<li>
									<small>
									<?php if (isset($step['file'])): ?>
										<?php echo Debug::path($step['file']) ?> [ <?php echo $step['line'] ?> ]
									<?php else: ?>
										{'PHP internal call'}
									<?php endif ?>
									</small>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>

				</div>
			</div>
		</div>
	</div>
	<!-- /container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
</body>
</html>

