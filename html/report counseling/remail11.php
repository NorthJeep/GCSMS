<form id="ewrEmailForm" class="form-horizontal ewForm ewEmailForm" action="<?php echo ewr_CurrentPage() ?>">
<?php if ($Page->CheckToken) { ?>
<input type="hidden" name="<?php echo EWR_TOKEN_NAME ?>" value="<?php echo $Page->Token ?>">
<?php } ?>
<input type="hidden" name="export" value="email">
<input type="hidden" name="contenttype" value="html">
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="ewrSender"><?php echo $ReportLanguage->Phrase("EmailFormSender") ?></label>
		<div class="col-sm-9"><input type="text" class="form-control ewControl" name="sender" id="ewrSender"></div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="ewrRecipient"><?php echo $ReportLanguage->Phrase("EmailFormRecipient") ?></label>
		<div class="col-sm-9"><input type="text" class="form-control ewControl" name="recipient" id="ewrRecipient"></div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="ewrCc"><?php echo $ReportLanguage->Phrase("EmailFormCc") ?></label>
		<div class="col-sm-9"><input type="text" class="form-control ewControl" name="cc" id="ewrCc"></div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="ewrBcc"><?php echo $ReportLanguage->Phrase("EmailFormBcc") ?></label>
		<div class="col-sm-9"><input type="text" class="form-control ewControl" name="bcc" id="ewrBcc"></div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="ewrSubject"><?php echo $ReportLanguage->Phrase("EmailFormSubject") ?></label>
		<div class="col-sm-9"><input type="text" class="form-control ewControl" name="subject" id="ewrSubject"></div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="ewrMessage"><?php echo $ReportLanguage->Phrase("EmailFormMessage") ?></label>
		<div class="col-sm-9"><textarea class="form-control ewControl" rows="6" name="message" id="ewrMessage"></textarea></div>
	</div>
</form>
