var repeatable={fieldAdd:null,fieldRemove:null,init:function(){repeatable.fieldAdd=$(".repeater-add"),repeatable.fieldRemove=$(".repeater-remove"),repeatable.fieldAdd.click(function(){return repeatable.method.add($(this)),!1}),repeatable.fieldRemove.click(function(){return repeatable.method.remove($(this)),!1})},method:{add:function(e){var t=e.closest(".repeatable-wrap").find(".repeatable.list-group-item:last").clone(!0),a=e.closest(".repeatable-wrap").find(".repeatable.list-group-item:last");$("input, select, textarea",t).val("").attr("name",function(e,t){return t.replace(/(\d+)/,function(e,t){return Number(t)+1})}),t.insertAfter(a,$(this).closest("div.repeatable-wrap")),console.log(t)},remove:function(e){e.parent().remove()}}};$(function(){tinyMCE.init({selector:".mce-tinymce"}),repeatable.init(),$(".delete-recipe").on("click",function(e){e.preventDefault(),$this=$(this);var t=$this.data("id");$.ajax({url:"/recipes/"+t,type:"DELETE",success:function(e){console.log(e)}})}),$(".update-recipe").on("click",function(e){e.preventDefault(),$this=$(this);var t=$this.data("id"),a=$this.data("title");$.ajax({url:"/recipes/"+t,type:"PUT",data:{title:a},success:function(e){console.log(e)}})})});