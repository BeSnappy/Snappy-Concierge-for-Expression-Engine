<?=form_open('C=addons_extensions'.AMP.'M=save_extension_settings'.AMP.'file=snappy_concierge');?>

<p>
	Note: These settings are for your agency or software developer. 
	Generally, as a user of the site, you will not need to edit this screen.
</p>
<p>
	Paste the widget code from the <a href="https://app.besnappy.com/#widget">Snappy widget screen</a>.
</p>
	<textarea name="sc_widget_code" style="width:50%; height:200px;"><?php echo $sc_widget_code; ?></textarea>
	<p style="margin-top:10px;">
		You can find all the details on configuring the widgets title, colors, and more 
		here: <a href="https://help.besnappy.com/administrator-guide#widget-864">https://help.besnappy.com/administrator-guide#widget-864</a>
	</p>	
<p style="margin-top:10px;"><?=form_submit('submit', lang('submit'), 'class="submit"')?></p>
<?=form_close()?>

