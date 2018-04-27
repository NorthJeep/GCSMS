<?php if (@!$gbSkipHeaderFooter) { ?>
		<?php if (isset($grTimer)) $grTimer->Stop() ?>
		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->
	<!-- Main Footer -->
	<footer class="main-footer">
		<!-- To the right -->
		<div class="pull-right hidden-xs"></div>
		<!-- Default to the left --><!-- ** Note: Only licensed users are allowed to change the copyright statement. ** -->
		<div class="ewFooterText"><?php echo $ReportLanguage->ProjectPhrase("FooterText") ?></div>
	</footer>
</div>
<!-- ./wrapper -->
<?php } ?>
<script type="text/html" class="ewJsTemplate" data-name="menu" data-data="menu" data-target="#ewMenu">
<ul class="sidebar-menu" data-widget="tree" data-follow-link="{{:followLink}}" data-accordion="{{:accordion}}">
{{include tmpl="#menu"/}}
</ul>
</script>
<script type="text/html" id="menu">
{{if items}}
	{{for items}}
		<li id="{{:id}}" name="{{:name}}" class="{{if isHeader}}header{{else}}{{if items}}treeview{{/if}}{{if active}} active current{{/if}}{{if open}} menu-open{{/if}}{{/if}}">
			{{if isHeader}}
				{{if icon}}<i class="{{:icon}}"></i>{{/if}}
				<span>{{:text}}</span>
				{{if label}}
				<span class="pull-right-container">
					{{:label}}
				</span>
				{{/if}}
			{{else}}
			<a href="{{:href}}"{{if target}} target="{{:target}}"{{/if}}{{if attrs}}{{:attrs}}{{/if}}>
				{{if icon}}<i class="{{:icon}}"></i>{{/if}}
				<span>{{:text}}</span>
				{{if items}}
				<span class="pull-right-container">
					<i class="fa fa-angle-left pull-right"></i>
					{{if label}}
						<span>{{:label}}</span>
					{{/if}}
				</span>
				{{else}}
					{{if label}}
						<span class="pull-right-container">
							{{:label}}
						</span>
					{{/if}}
				{{/if}}
			</a>
			{{/if}}
			{{if items}}
			<ul class="treeview-menu"{{if open}} style="display: block;"{{/if}}>
				{{include tmpl="#menu"/}}
			</ul>
			{{/if}}
		</li>
	{{/for}}
{{/if}}
</script>
<script type="text/html" class="ewJsTemplate" data-name="languages" data-data="languages" data-method="<?php echo $ReportLanguage->Method ?>" data-target="<?php echo ewr_HtmlEncode($ReportLanguage->Target) ?>">
<?php echo $ReportLanguage->GetTemplate() ?>
</script>
<script type="text/javascript">
ewr_RenderJsTemplates();
</script>
<!-- modal dialog -->
<div id="ewrModalDialog" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title"></h4></div><div class="modal-body"></div><div class="modal-footer"></div></div></div></div>
<!-- message box -->
<div id="ewrMsgBox" class="modal"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton" data-dismiss="modal" aria-hidden="true"><?php echo $ReportLanguage->Phrase("MessageOK") ?></button></div></div></div></div>
<!-- prompt -->
<div id="ewrPrompt" class="modal"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton"><?php echo $ReportLanguage->Phrase("MessageOK") ?></button><button type="button" class="btn btn-default ewButton" data-dismiss="modal"><?php echo $ReportLanguage->Phrase("Cancel") ?></button></div></div></div></div>
<!-- session timer -->
<div id="ewrTimer" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton" data-dismiss="modal"><?php echo $ReportLanguage->Phrase("MessageOK") ?></button></div></div></div></div>
<!-- popup filter -->
<div id="ewrPopupFilterDialog"></div>
<!-- export chart -->
<div id="ewrExportDialog"></div>
<!-- drill down -->
<?php if (@!$grDrillDownInPanel) { ?>
<div id="ewrDrillDownPanel"></div>
<?php } ?>
<script type="text/javascript">ewr_GetScript("phprptjs/rusrevt11.js");</script>
<script type="text/javascript">

// Write your global startup script here
// console.log("page loaded");

</script>
</body>
</html>
