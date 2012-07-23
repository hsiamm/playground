/* Copyright (C) YOOtheme GmbH, http://www.gnu.org/licenses/gpl.html GNU/GPL */

(function(b){var a=function(){};b.extend(a.prototype,{name:"finder",initialize:function(a,d){function e(d){d.preventDefault();var f=b(this).closest("li",a);f.length||(f=a);f.hasClass(c.options.open)?f.removeClass(c.options.open).children("ul").slideUp():(f.addClass(c.options.loading),b.post(c.options.url,{path:f.data("path")},function(a){f.removeClass(c.options.loading).addClass(c.options.open);a.length&&(f.children().remove("ul"),f.append("<ul>").children("ul").hide(),b.each(a,function(a,c){f.children("ul").append(b('<li><a href="#">'+
c.name+"</a></li>").addClass(c.type).data("path",c.path))}),f.find("ul a").bind("click",e),f.children("ul").slideDown())},"json"))}var c=this;this.options=b.extend({url:"",path:"",open:"open",loading:"loading"},d);a.data("path",this.options.path).bind("retrieve:finder",e).trigger("retrieve:finder")}});b.fn[a.prototype.name]=function(){var g=arguments,d=g[0]?g[0]:null;return this.each(function(){var e=b(this);if(a.prototype[d]&&e.data(a.prototype.name)&&d!="initialize")e.data(a.prototype.name)[d].apply(e.data(a.prototype.name),
Array.prototype.slice.call(g,1));else if(!d||b.isPlainObject(d)){var c=new a;a.prototype.initialize&&c.initialize.apply(c,b.merge([e],g));e.data(a.prototype.name,c)}else b.error("Method "+d+" does not exist on jQuery."+a.name)})}})(jQuery);
(function(b){var a=function(){};b.extend(a.prototype,{name:"Directories",initialize:function(a,d){this.options=b.extend({url:"",title:"Folders",mode:"folder",msgDelete:"Delete"},d);var e=this,c=b('<div class="finder" />').insertAfter(a).finder(this.options).delegate("a","click",function(){c.find("li").removeClass("selected");var d=b(this).parent().addClass("selected");(e.options.mode!="file"||d.hasClass("file"))&&a.focus().val(d.data("path")).blur()}),h=c.dialog(b.extend({autoOpen:false,resizable:false,
open:function(){h.position({of:f,my:"center top",at:"center bottom"})}},this.options)).dialog("widget"),f=b('<span title="'+this.options.title+'" class="'+this.options.mode+'s" />').insertAfter(a).bind("click",function(){c.dialog(c.dialog("isOpen")?"close":"open")});b('<span title="'+this.options.msgDelete+'" class="delete-file" />').insertAfter(f).bind("click",function(){a.val("")});b("body").bind("click",function(a){c.dialog("isOpen")&&!f.is(a.target)&&!h.find(a.target).length&&c.dialog("close")})}});
b.fn[a.prototype.name]=function(){var g=arguments,d=g[0]?g[0]:null;return this.each(function(){var e=b(this);if(a.prototype[d]&&e.data(a.prototype.name)&&d!="initialize")e.data(a.prototype.name)[d].apply(e.data(a.prototype.name),Array.prototype.slice.call(g,1));else if(!d||b.isPlainObject(d)){var c=new a;a.prototype.initialize&&c.initialize.apply(c,b.merge([e],g));e.data(a.prototype.name,c)}else b.error("Method "+d+" does not exist on jQuery."+a.name)})}})(jQuery);