!function(e){var t={};function a(n){if(t[n])return t[n].exports;var l=t[n]={i:n,l:!1,exports:{}};return e[n].call(l.exports,l,l.exports,a),l.l=!0,l.exports}a.m=e,a.c=t,a.d=function(e,t,n){a.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},a.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.t=function(e,t){if(1&t&&(e=a(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(a.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var l in e)a.d(n,l,function(t){return e[t]}.bind(null,l));return n},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,"a",t),t},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.p="/",a(a.s=181)}({181:function(e,t,a){e.exports=a(182)},182:function(e,t,a){var n;pagination=a(183);var l="family_name",r="family_name",i="asc";function p(e){$("#filter-status").html("");var t=$("#results-table tbody");t.empty(),t.append($("<tr>").append($("<td>").text("Searching...").attr("colspan",13)));var a=$("#paginator");a.empty();var n=$("#paginator-info");n.empty(),$.post(filterUrl,{_token:csrfToken,family_name:$('#filter input[name="family_name"]').val(),name:$('#filter input[name="name"]').val(),date_of_birth:$('#filter input[name="date_of_birth"]').val(),nationality:$('#filter input[name="nationality"]').val(),police_no:$('#filter input[name="police_no"]').val(),languages:$('#filter input[name="languages"]').val(),remarks:$('#filter input[name="remarks"]').val(),page:e,orderByField:l,orderByDirection:i},(function(e){t.empty(),e.data.length>0?($.each(e.data,(function(e,a){t.append(function(e){var t="";"f"==e.gender&&(t="female");"m"==e.gender&&(t="male");bulk_select=$("#bulk_select_on").length>0,bulk_select_elem=null,bulk_select&&(bulk_select_elem=$("<td>").append($("<input>").attr("type","checkbox").attr("name","selected_people[]").val(e.id).on("change",(function(){$(this).prop("checked")?$(this).parents("tr").addClass("table-secondary"):$(this).parents("tr").removeClass("table-secondary"),num_selected=$("table").find('input[name="selected_people[]"]:checked').length,num_selected>0?($("#selected_actions_container").show(),$("#selected_count").text(num_selected),$("#selected_actions_container").find("button").each((function(){$(this).data("bulk-min")&&($(this).data("bulk-min")<=num_selected?$(this).show():$(this).hide())}))):$("#selected_actions_container").hide()}))));return $("<tr>").attr("id","person-"+e.id).append(bulk_select_elem).append($("<td>").html(""!=t?'<i class="fa fa-'+t+'"></i>':"")).append($("<td>").append($("<a>").attr("href","people/"+e.id).text(e.family_name))).append($("<td>").append($("<a>").attr("href","people/"+e.id).text(e.name))).append($("<td>").text(e.date_of_birth)).append($("<td>").text(e.nationality)).append($("<td>").text(e.police_no)).append($("<td>").text(e.languages?Array.isArray(e.languages)?e.languages.join(", "):e.languages:"")).append($("<td>").text(e.remarks)).append($("<td>").text(e.created_at))}(a))})),pagination.updatePagination(a,e,p),n.html(e.from+" - "+e.to+" of "+e.total)):t.append($("<tr>").addClass("warning").append($("<td>").text("No results").attr("colspan",13)))})).fail((function(e,a){t.empty(),t.append($("<tr>").addClass("danger").append($("<td>").text(a).attr("colspan",13)))}))}$((function(){$("#filter input").on("change keyup",(function(e){var t=e.keyCode;if(0==t||8==t||13==t||27==t||46==t||t>=48&&t<=90||t>=96&&t<=111){var a=$(this);$("#filter-status").html("");var l=$("#results-table tbody");l.empty(),l.append($("<tr>").append($("<td>").text("Searching...").attr("colspan",13))),clearTimeout(n),n=setTimeout((function(){27==t&&a.val("").focus(),13==t&&a.blur(),p(1)}),300)}})),$("a.sort").on("click",(function(){l=$(this).attr("data-field"),i=r==l&&"desc"!=i?"desc":"asc",r=l,p(1)})),$("#reset-filter").on("click",(function(){$('#filter input[name="family_name"]').val(""),$('#filter input[name="name"]').val(""),$('#filter input[name="date_of_birth"]').val(""),$('#filter input[name="nationality"]').val(""),$('#filter input[name="police_no"]').val(""),$('#filter input[name="languages"]').val(""),$('#filter input[name="remarks"]').val(""),p(1)})),p(1)}))},183:function(e,t){function a(e,t,a,n){var l=$("<li>").addClass("page-item");return null!=t?l.append($("<a>").addClass("page-link").attr("href","javascript:;").html(e).on("click",(function(){n(t)}))):l.append($("<span>").addClass("page-link").html(e)),null!=a&&l.addClass(a),l}e.exports={updatePagination:function(e,t,n){e.empty(),t.current_page>1?e.append(a("&laquo;",1,null,n)):e.append(a("&laquo;",null,"disabled",n));t.current_page>1?e.append(a("&lsaquo;",t.current_page-1,null,n)):e.append(a("&lsaquo;",null,"disabled",n));for(i=2+Math.max(2-(t.last_page-t.current_page),0);i>=1;i--)t.current_page>i&&e.append(a(t.current_page-i,t.current_page-i,null,n));for(e.append(a(t.current_page,null,"active",n)),i=1;i<=2+Math.max(0,3-t.current_page);i++)t.current_page+i-1<t.last_page&&e.append(a(t.current_page+i,t.current_page+i,null,n));t.current_page<t.last_page?e.append(a("&rsaquo;",t.current_page+1,null,n)):e.append(a("&rsaquo;",null,"disabled",n));t.current_page<t.last_page?e.append(a("&raquo;",t.last_page,null,n)):e.append(a("&raquo;",null,"disabled",n))}}}});
//# sourceMappingURL=people.js.map