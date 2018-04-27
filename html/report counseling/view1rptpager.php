<form action="<?php echo ewr_CurrentPage() ?>" name="ewPagerForm" class="ewForm form-horizontal">
<?php if (!isset($Pager)) $Pager = new crPrevNextPager($Page->StartGrp, $Page->DisplayGrps, $Page->TotalGrps) ?>
<?php if ($Pager->RecordCount > 0 && $Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $ReportLanguage->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $ReportLanguage->Phrase("PagerFirst") ?>" href="<?php echo ewr_CurrentPage() ?>?start=<?php echo $Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $ReportLanguage->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $ReportLanguage->Phrase("PagerPrevious") ?>" href="<?php echo ewr_CurrentPage() ?>?start=<?php echo $Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $ReportLanguage->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EWR_TABLE_PAGE_NO ?>" value="<?php echo $Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $ReportLanguage->Phrase("PagerNext") ?>" href="<?php echo ewr_CurrentPage() ?>?start=<?php echo $Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $ReportLanguage->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $ReportLanguage->Phrase("PagerLast") ?>" href="<?php echo ewr_CurrentPage() ?>?start=<?php echo $Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $ReportLanguage->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $ReportLanguage->Phrase("of") ?>&nbsp;<?php echo $Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
<span><?php echo $ReportLanguage->Phrase("Record") ?> <?php echo $Pager->FromIndex ?> <?php echo $ReportLanguage->Phrase("To") ?> <?php echo $Pager->ToIndex ?> <?php echo $ReportLanguage->Phrase("Of") ?> <?php echo $Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($Page->TotalGrps > 0 && !$Page->ShowReportInDashboard) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="view1">
<select name="<?php echo EWR_TABLE_GROUP_PER_PAGE; ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="1"<?php if ($Page->DisplayGrps == 1) echo " selected" ?>>1</option>
<option value="2"<?php if ($Page->DisplayGrps == 2) echo " selected" ?>>2</option>
<option value="3"<?php if ($Page->DisplayGrps == 3) echo " selected" ?>>3</option>
<option value="4"<?php if ($Page->DisplayGrps == 4) echo " selected" ?>>4</option>
<option value="5"<?php if ($Page->DisplayGrps == 5) echo " selected" ?>>5</option>
<option value="10"<?php if ($Page->DisplayGrps == 10) echo " selected" ?>>10</option>
<option value="20"<?php if ($Page->DisplayGrps == 20) echo " selected" ?>>20</option>
<option value="50"<?php if ($Page->DisplayGrps == 50) echo " selected" ?>>50</option>
<option value="ALL"<?php if ($Page->getGroupPerPage() == -1) echo " selected" ?>><?php echo $ReportLanguage->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
