/* Copyright (C) YOOtheme GmbH, http://www.gnu.org/licenses/gpl.html GNU/GPL */

jQuery(function(b){var f=location.href.match(/^(.+)administrator\/index\.php.*/i)[1];b("input.image-select").each(function(c){var a=b(this),d="image-select-"+c,c=b('<button type="button">').text("Select Image").insertAfter(a),e=b("<span>").addClass("image-cancel").insertAfter(a),g=b("<div>").addClass("image-preview").insertAfter(c);a.attr("id",d);a.val()&&b("<img>").attr("src",f+a.val()).appendTo(g);e.click(function(){a.val("");g.empty()});c.click(function(a){a.preventDefault();SqueezeBox.fromElement(this,
{handler:"iframe",url:"index.php?option=com_media&view=images&tmpl=component&e_name="+d,size:{x:600,y:415}})})});if(b.isFunction(window.jInsertEditorText))window.insertTextOld=window.jInsertEditorText;window.jInsertEditorText=function(c,a){if(a.match(/^image-select-/)){var d=b("#"+a),e=c.match(/src="([^\"]*)"/)[1];d.parent().find("div.image-preview").html(c).find("img").attr("src",f+e);d.val(e)}else b.isFunction(window.insertTextOld)&&window.insertTextOld(c,a)}});
