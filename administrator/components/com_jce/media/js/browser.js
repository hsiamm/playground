(function($){$.Browser={options:{element:null,plugin:{plugin:'browser',root:'',site:'',help:function(){window.parent.$jce.createDialog({src:'index.php?option=com_jce&view=help&tmpl=component&section=editor&category=browser',type:'help',options:{width:780,height:560,}})}},manager:{upload:{insert:false}}},init:function(options){var self=this,win=window.parent,doc=win.document;$.extend(true,this.options,options);$('<input type="hidden" id="src" value="" />').appendTo(document.body);$.Plugin.init(this.options.plugin);$('button#insert, button#cancel').hide();if(this.options.element){$('button#insert').show().click(function(e){self.insert();win.$jce.closeDialog('#'+self.options.element+'_browser');e.preventDefault()});$('button#cancel').show().click(function(e){win.$jce.closeDialog('#'+self.options.element+'_browser');e.preventDefault()});var src=doc.getElementById(this.options.element).value||'';$('#src').val(src)}WFFileBrowser.init($('#src'),this.options.manager||{})},insert:function(){if(this.options.element){var src=WFFileBrowser.getSelectedItems(0);window.parent.document.getElementById(this.options.element).value=$(src).data('url')}}}})(jQuery);var tinyMCE={addI18n:function(p,o){return $.Plugin.addI18n(p,o)}};