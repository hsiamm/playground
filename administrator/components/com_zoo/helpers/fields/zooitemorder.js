/* Copyright (C) YOOtheme GmbH, http://www.gnu.org/licenses/gpl.html GNU/GPL */

(function(c){var d=function(){};c.extend(d.prototype,{name:"ZooItemOrder",initialize:function(b){var a=this;this.input=b;this.application=c("body").find(".zoo-application select.application");this.application.length&&(b.find("select.element, input:checkbox").each(function(){c(this).data("_name",c(this).attr("name"))}),this.application.bind("change",function(){a.refresh()}),this.refresh())},refresh:function(){var b=this.application.val();this.input.find(".apps .app").each(function(){var a=c(this);
a.find("select.element, input:checkbox").attr("name",function(){return b&&a.hasClass(b)?c(this).data("_name"):""});b&&a.hasClass(b)?a.show():a.hide()});this.application.val()?this.input.find(".select-message").hide():this.input.find(".select-message").show()}});c.fn[d.prototype.name]=function(){var b=arguments,a=b[0]?b[0]:null;return this.each(function(){var e=c(this);if(d.prototype[a]&&e.data(d.prototype.name)&&a!="initialize")e.data(d.prototype.name)[a].apply(e.data(d.prototype.name),Array.prototype.slice.call(b,
1));else if(!a||c.isPlainObject(a)){var f=new d;d.prototype.initialize&&f.initialize.apply(f,c.merge([e],b));e.data(d.prototype.name,f)}else c.error("Method "+a+" does not exist on jQuery."+d.name)})}})(jQuery);
