(function($){$.jce.Profiles={init:function(options){var self=this;if($.browser.msie){$('#jce').addClass('ie')}if(!$.support.cssFloat&&!!window.XMLHttpRequest&&!document.querySelector){$('#jce').addClass('ie7')}$('#tabs').tabs();$('button#user-group-add').button({icons:{primary:'icon-add'}}).click(function(e){e.preventDefault();$('select#types').children().attr('selected',true);return false});$('button#user-group-remove').button({icons:{primary:'icon-remove'}}).click(function(e){e.preventDefault();$('select#types').children(':selected').attr('selected',false);return false});$('button#users-add').button({icons:{primary:'icon-add'}});$('button#users-remove').button({icons:{primary:'icon-remove'}}).click(function(e){e.preventDefault();$('select#users').children(':selected').remove();return false});$('button#layout-legend').button({icons:{primary:'icon-legend'}});$("select.editable, select.combobox").combobox(options.combobox);$("#tabs-editor, #tabs-plugins").tabs().addClass('ui-tabs-vertical ui-helper-clearfix');$("#tabs-editor li, #tabs-plugins li").removeClass('ui-corner-top').addClass('ui-corner-left');$('input.color').colorpicker(options.colorpicker);$('select.extensions, input.extensions, textarea.extensions').extensionmapper(options.extensions);this.createLayout();$('input.browser').each(function(){var el=this;$('<span class="browser"></span>').click(function(){var src='index.php?option=com_jce&view=editor&layout=plugin&plugin=browser&standalone=1&element='+$(el).attr('id');if($(el).data('filter')){src+='&filter='+$(el).data('filter')}$.jce.createDialog($.extend(options.dialog,{src:src,options:{'width':785,'height':450},modal:true,type:'browser',id:$(el).attr('id')+'_browser',title:options.browser.title||'Browser'}))}).insertAfter(this)});$('select.checklist, input.checklist').checkList();$('input.autocomplete').each(function(){var el=this,v=$(el).attr('placeholder')||'';$(el).removeAttr('placeholder');$(el).autocomplete({source:v.split(',')||[]})});$('input[name="components-select"]').click(function(){$('select#components').attr('disabled',(this.value=='all')).children('option:selected').removeAttr('selected')});$('#paramseditorwidth').change(function(){var v=$(this).val();if(v&&/%/.test(v)){return}else{if(v){v=parseInt(v)}else{v=600}$('span.widthMarker','#profileLayoutTable').width(v).children('span').html(v+'px')}});$('ul#profileAdditionalFeatures input:checkbox').click(function(){self.setPlugins()})},onSubmit:function(){$('div#tabs-editor, div#tabs-plugins').find(':input[name]').each(function(){if($(this).val()===''||$(this).hasClass('placeholder')){$(this).attr('disabled','disabled')}})},createLayout:function(){var self=this;$("ul.sortableList").sortable({connectWith:'ul.sortableList',axis:'y',tolerance:'intersect',handle:'span.sortableHandle',update:function(event,ui){self.setRows();self.setPlugins()},start:function(event,ui){$(ui.placeholder).width($(ui.item).width())},placeholder:'sortableListItem ui-state-highlight'}).disableSelection();$('span.sortableOption','ul.sortableList li').hover(function(){$(this).append('<span role="button"/>')},function(){$(this).empty()}).click(function(){var $parent=$(this).parent();var $target=$('ul.sortableList','#profileLayoutTable').not($parent.parent());$parent.hide().appendTo($target).show('slow');$(this).empty()});$('ul.sortableRow').sortable({connectWith:'ul.sortableRow',tolerance:'intersect',update:function(event,ui){self.setRows();self.setPlugins()},start:function(event,ui){$(ui.placeholder).width($(ui.item).width())},placeholder:'sortableRowItem ui-state-highlight'}).disableSelection()},setRows:function(){var rows=[];$('ul.sortableRow:has(li)','#profileLayout').each(function(){rows.push($.map($('li.sortableRowItem',$(this)),function(el){if($(el).hasClass('spacer')){return'spacer'}return $(el).data('name')}).join(','))});$('input[name="rows"]').val(rows.join(';'))},setPlugins:function(){var self=this,plugins=[];$('ul.sortableRow li.plugin','#profileLayout').each(function(){plugins.push($(this).data('name'))});$('ul#profileAdditionalFeatures input:checkbox:checked').each(function(){plugins.push($(this).val())});$('input[name="plugins"]').val(plugins.join(','));self.setParams(plugins)},setParams:function(plugins){var $tabs=$('div#tabs-plugins');$('div.ui-tabs-panel','div#tabs-plugins').each(function(i){var name=$(this).data('name');var s=$.inArray(name,plugins)!=-1;$(':input[name]',$(this)).attr('disabled',!s);if(!s){if($tabs.tabs('option','selected')==i){var n=0,x=$tabs.tabs('option','disabled');while(i==n){n++;if($.inArray(n,x)!=-1){n++}}$tabs.tabs('select',n)}$tabs.tabs('disable',i)}else{$tabs.tabs('enable',i)}})}}})(jQuery);