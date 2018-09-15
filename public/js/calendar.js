!function(e){var t={};function o(r){if(t[r])return t[r].exports;var n=t[r]={i:r,l:!1,exports:{}};return e[r].call(n.exports,n,n.exports,o),n.l=!0,n.exports}o.m=e,o.c=t,o.d=function(e,t,r){o.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:r})},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="/",o(o.s=244)}({244:function(e,t,o){e.exports=o(245)},245:function(e,t){var o=null;$(document).ready(function(){var e=$("#calendar"),t=$("#eventModal"),r=$("#resourceModal"),n=$("#event_editor_title"),a=$("#event_editor_description"),s=$("#event_editor_date_start"),l=$("#event_editor_date_end"),i=$("#event_editor_resource_id"),d=$("#event_editor_delete"),u=t.find('button[type="submit"]'),c=$("#event_editor_credits"),f=$("#resource_editor_title"),m=$("#resource_editor_group"),v=$("#resource_editor_color"),p=$("#resource_editor_delete"),h=r.find('button[type="submit"]');function g(e,t,o){$.ajax(e.updateDateUrl,{method:"PUT",data:{_token:csrfToken,start:e.start.format(),end:e.end?e.end.format():null,resourceId:e.resourceId}}).fail(function(e,t){alert("Error: "+(e.responseJSON.message?e.responseJSON.message:e.responseText)),o()})}function x(e){var t="00:00:00"==e.format("HH:mm:ss");return e.format(t?"LL":"LLL")}function _(e,t){if(e.isSame(t,"day"))return" - "+t.format("LT");var o="00:00:00"==e.format("HH:mm:ss"),r="00:00:00"==t.format("HH:mm:ss");if(o&&r){var n=t.clone().subtract(1,"day");return e.isSame(n,"day")?"":" - "+n.format("LL")}return" - "+t.format("LLL")}function b(t,o){var r=e.fullCalendar("getResources");i.empty();var n={};$.each(r,function(e,t){i.append($("<option>",{value:t.id,text:t.title})),n[t.id]=t.eventColor}),console.log(n),i.val(t).change().prop("disabled",o);var a=function(){var e=n[i.val()];i.siblings().find("label").css("color",e||"inherit")};i.off("change").on("change",a),a()}e.fullCalendar({themeSystem:"bootstrap4",height:"auto",locale:locale,slotLabelFormat:"H:mm",minTime:"08:00",header:{left:manageResourcesAllowed?"prev,next today promptResource":"prev,next today",center:"title",right:"agendaDay,agendaWeek,month,listWeek,timelineDay"},customButtons:{promptResource:{text:"+ Resource",click:function(){r.find(".modal-title").text("Create Resource"),f.val(""),m.val(o||""),v.val(function(){for(var e="#",t=0;t<6;t++)e+="0123456789ABCDEF"[Math.floor(16*Math.random())];return e}()),p.hide(),h.show(),r.find("form").off().on("submit",function(){$.ajax(storeResourceUrl,{method:"POST",data:{_token:csrfToken,title:f.val(),group:m.val(),color:v.val()}}).done(function(t){o=m.val(),r.modal("hide"),e.fullCalendar("addResource",t,!0)}).fail(function(e,t){alert("Error: "+(e.responseJSON.message?e.responseJSON.message:e.responseText))})}),r.modal("show"),f.focus()}}},resourceLabelText:"Resources",resourceRender:function(t,o){manageResourcesAllowed&&o.on("click",function(){!function(t){r.find(".modal-title").text("Create Resource"),f.val(t.title),m.val(t.group),v.val(t.eventColor),p.show(),h.show(),r.find("form").off().on("submit",function(){$.ajax(t.url,{method:"PUT",data:{_token:csrfToken,title:f.val(),group:m.val(),color:v.val()}}).done(function(){r.modal("hide"),e.fullCalendar("refetchResources")}).fail(function(e,t){alert("Error: "+(e.responseJSON.message?e.responseJSON.message:e.responseText))})}),p.off().on("click",function(){confirm("Are you sure you want to delete thre resource '"+t.title+"'?")&&$.ajax(t.url,{method:"DELETE",data:{_token:csrfToken}}).done(function(){r.modal("hide"),e.fullCalendar("removeResource",t)}).fail(function(e,t){alert("Error: "+(e.responseJSON.message?e.responseJSON.message:e.responseText))})}),r.modal("show"),f.focus()}(t)})},views:{agendaDay:{buttonText:"Day"},agendaWeek:{buttonText:"Week"},month:{buttonText:"Month"},listWeek:{buttonText:"List"},timelineDay:{buttonText:"Timeline"}},defaultView:localStorage.getItem("calendar-view-name")?localStorage.getItem("calendar-view-name"):"agendaWeek",viewRender:function(e,t){localStorage.setItem("calendar-view-name",e.name)},firstDay:1,weekends:!0,weekNumbers:!0,weekNumbersWithinDays:!0,businessHours:{dow:[1,2,3,4,5,6],start:"10:00",end:"19:00"},navLinks:!0,eventLimit:!0,events:listEventsUrl,editable:!1,eventDrop:g,eventResize:g,selectable:createEventAllowed,selectHelper:!1,select:function(o,r,f,m,v){var p=e.fullCalendar("getResources");if(0==p.length)return alert("Please add a resource first before creating an event!"),void e.fullCalendar("unselect");t.find(".modal-title").text("Create Event"),s.text(x(o)),l.text(_(o,r)),n.val("").prop("readonly",!1),a.val("").prop("readonly",!1),b(v?v.id:p[0].id,!1),d.hide(),u.show(),c.hide(),t.on("hide.bs.modal",function(t){e.fullCalendar("unselect")}),t.find("form").off().on("submit",function(){$.ajax(storeEventUrl,{method:"POST",data:{_token:csrfToken,title:n.val(),description:a.val(),resourceId:i.val(),start:o.format(),end:r?r.format():null}}).done(function(o){t.modal("hide"),e.fullCalendar("renderEvent",o,!0)}).fail(function(e,t){alert("Error: "+(e.responseJSON.message?e.responseJSON.message:e.responseText))})}),t.modal("show"),n.focus()},unselectAuto:!1,eventClick:function(o,r,f){if(!o.editable)return function(e){t.find(".modal-title").text("View Event"),s.text(x(e.start)),l.text(_(e.start,e.end)),n.val(e.title).prop("readonly",!0),a.val(e.description).prop("readonly",!0),b(e.resourceId,!0),d.hide(),u.hide(),e.user.id!=currentUserId?(c.find('[rel="author"]').text(e.user.name),c.show()):c.hide();return t.modal("show"),!1}(o);t.find(".modal-title").text("Edit Event"),s.text(x(o.start)),l.text(_(o.start,o.end)),n.val(o.title).prop("readonly",!1),a.val(o.description).prop("readonly",!1),b(o.resourceId,!1),d.show(),u.show(),o.user.id!=currentUserId?(c.find('[rel="author"]').text(o.user.name),c.show()):c.hide();return t.find("form").off().on("submit",function(){$.ajax(o.url,{method:"PUT",data:{_token:csrfToken,title:n.val(),description:a.val(),resourceId:i.val()}}).done(function(){t.modal("hide"),o.title=n.val(),o.description=a.val(),o.resourceId=i.val(),e.fullCalendar("updateEvent",o)}).fail(function(e,t){alert("Error: "+(e.responseJSON.message?e.responseJSON.message:e.responseText))})}),d.off().on("click",function(){confirm("Really delete event '"+o.title+"'?")&&$.ajax(o.url,{method:"DELETE",data:{_token:csrfToken}}).done(function(){t.modal("hide"),e.fullCalendar("removeEvents",o.id)}).fail(function(e,t){alert("Error: "+(e.responseJSON.message?e.responseJSON.message:e.responseText))})}),t.modal("show"),n.select(),!1},schedulerLicenseKey:"CC-Attribution-NonCommercial-NoDerivatives",resources:listResourcesUrl,resourceOrder:"title",resourceGroupField:"group"})})}});