!function(t){var e={};function n(a){if(e[a])return e[a].exports;var i=e[a]={i:a,l:!1,exports:{}};return t[a].call(i.exports,i,i.exports,n),i.l=!0,i.exports}n.m=t,n.c=e,n.d=function(t,e,a){n.o(t,e)||Object.defineProperty(t,e,{configurable:!1,enumerable:!0,get:a})},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/",n(n.s=248)}({248:function(t,e,n){t.exports=n(249)},249:function(t,e,n){var a=n(250);$(function(){$("#lendBookModal").on("shown.bs.modal",function(t){$('input[name="book_id"]').val("").trigger("change"),$('input[name="book_id_search"]').val("").focus(),$('input[name="person_id"]').val("").trigger("change"),$('input[name="person_id_search"]').val("").focus()}),$("#registerBookModal").on("shown.bs.modal",function(t){$('input[name="isbn"]').focus();var e=$('input[name="book_id_search"]').val().toUpperCase().replace(/[^+0-9X]/gi,"");a.Validate(e)&&$('input[name="isbn"]').val(e).trigger("propertychange")}),$('input[name="isbn"]').on("input propertychange",function(){$(this).removeClass("is-valid").removeClass("is-invalid");var t=$(this).val().toUpperCase().replace(/[^+0-9X]/gi,"");/^(97(8|9))?\d{9}(\d|X)$/.test(t)&&a.Validate(t)?($(this).addClass("is-valid"),$('input[name="title"]').val(""),$('input[name="author"]').val(""),$('input[name="language"]').val(""),$('input[name="title"]').attr("placeholder","Searching for title..."),$('input[name="author"]').attr("placeholder","Searching for author..."),$('input[name="language"]').attr("placeholder","Searching for language..."),$.get("/library/books/findIsbn/"+t,function(t){$('input[name="title"]').val(t.title),$('input[name="author"]').val(t.author),$('input[name="language"]').val(t.language)}).fail(function(){$('input[name="title"]').attr("placeholder","Title"),$('input[name="author"]').attr("placeholder","Author"),$('input[name="language"]').attr("placeholder","Language")})):$(this).val().length>0&&$(this).addClass("is-invalid")})})},250:function(t,e,n){const a=n(251),i=n(252);t.exports=class{static Validate(t){return t=t.replace(a.PREFIX,""),!!a.ISBN.test(t)&&i(t)}}},251:function(t,e){t.exports={PREFIX:/^ISBN(?:-1[03])?:?\x20+/i,ISBN:/^(?:\d{9}[\dXx]|\d{13})$/}},252:function(t,e){t.exports=(t=>{let e=(t=t.toString()).slice(0,-1);e=e.split("").map(Number);const n=t.slice(-1),a="X"!==n?parseInt(n,10):"X",i=(e=e.map((t,e)=>t*(e+1))).reduce((t,e)=>t+e,0)%11;return a===(10!==i?i:"X")})}});