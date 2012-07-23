/* Copyright (C) YOOtheme GmbH, http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only */

(function(b){b.fn.matchHeight=function(c){var a=0;this.each(function(){a=Math.max(a,b(this).height())});c&&(a=Math.max(a,c));return this.each(function(){b(this).css("min-height",a)})}})(jQuery);
