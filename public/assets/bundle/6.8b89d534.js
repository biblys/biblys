(window.webpackJsonp=window.webpackJsonp||[]).push([[6,12,19],{"0+Gh":function(t,e,a){"use strict";a.r(e),a.d(e,"default",(function(){return f}));var n=a("EVdn"),o=a.n(n),r=a("9xd5");function i(t){return(i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function s(t,e){for(var a=0;a<e.length;a++){var n=e[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,l(n.key),n)}}function d(t,e,a){return e&&s(t.prototype,e),a&&s(t,a),Object.defineProperty(t,"prototype",{writable:!1}),t}function l(t){var e=function(t,e){if("object"!=i(t)||!t)return t;var a=t[Symbol.toPrimitive];if(void 0!==a){var n=a.call(t,e||"default");if("object"!=i(n))return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===e?String:Number)(t)}(t,"string");return"symbol"==i(e)?e:e+""}var c=function(t,e){if("short"===e){var a=t.getDate().toString().padStart(2,"0"),n=(t.getMonth()+1).toString().padStart(2,0);return'<span title="'.concat(t.toLocaleString(),'">').concat(a,"/").concat(n,"</span>")}},u=function(t,e){return Math.floor((t-e)/864e5)},p=function(t){var e=t.split(/[- :]/);return new Date(Date.UTC(e[0],e[1]-1,e[2],e[3],e[4],e[5]))},f=d((function t(e){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t);var a=this;a.data=e,a.getRow=function(){var t='<button title="Marquer comme expédiée" data-action="shipped" class="btn btn-xs btn-success"><i class="fa fa-dropbox"></i></button>',e="";a.pick&&(t='<button title="Marquer comme à dispo. en magasin" data-action="shipped" class="btn btn-xs btn-success"><i class="fa fa-shopping-bag"></i></button>'),a.overdued&&(e=' <button title="Relancer" data-action="followup" class="btn btn-xs btn-warning"><i class="fa fa-warning"></i></button>');var n=o()('<tr id="order_'+a.data.id+'" class="text-center '+a.class+'"><td title="'+a.tooltip+'" class="va-middle"><i class="fa '+a.icon+'"></i></td><td><a href="/order/'+a.data.url+'">'+a.data.id+"</a><td>"+c(a.created_at,"short")+'</td><td class="text-left customer">'+a.data.customer+'</td><td class="text-right">'+a.data.total+'</td><td><a href="/invoice/'+a.data.url+'" class="btn btn-xs btn-default" title="Imprimer la facture" ><i class="fa fa-print"></i></a></td><td>'+(a.data.payment_mode?'<img src="/assets/icons/payment_'+a.data.payment_mode+'.png" alt="'+a.data.payment_mode+'" title="'+a.data.payment_mode+'" width=20 />':"")+"</td><td>"+(a.payed?c(a.payed_at,"short"):'<div class="btn-group"><button title="Marquer comme payée" type="button" class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-money"></i></button><ul class="dropdown-menu"><li><a class="pointer" data-action="payed" data-mode="card">Carte bancaire</a></li><li><a class="pointer" data-action="payed" data-mode="cheque">Chèque</a></li><li><a class="pointer" data-action="payed" data-mode="cash">Espèces</a></li><li><a class="pointer" data-action="payed" data-mode="paypal">Paypal</a></li><li><a class="pointer" data-action="payed" data-mode="payplug">Payplug</a></li><li><a class="pointer" data-action="payed" data-mode="transfer">Virement</a></li><li><a class="pointer" data-action="payed" data-mode="exchange">Échange</a></li></ul></div>'+e)+"</td><td>"+(a.shipped?c(a.shipped_at,"short"):t)+'</td><td><button title="Annuler la commande" data-action="cancel" class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i></button></td></tr>');return n.on("click","[data-action]",(function(){var t=o()(this).data("action"),e=o()(this).find("i"),n=e.attr("class"),i=null,s=null,d=o()("#order_status").val();if("cancel"==t&&!confirm("Voulez-vous vraiment annuler la commande n° "+a.data.id+" de "+a.data.customer+" ?"))return!1;if(e.attr("class","fa fa-spinner fa-spin"),("suivi"===a.data.shipping_mode||"mondial-relay"===a.data.shipping_mode)&&"shipped"===t&&null===(s=window.prompt("Numéro de suivi ?")))return new r.default("La commande n'a pas été marquée comme expédiée.",{type:"warning"}),void e.attr("class",n);"payed"==t&&(i=o()(this).data("mode")),o.a.ajax({type:"POST",url:"/admin/orders/"+a.data.id+"/"+t,data:{payment_mode:i,tracking_number:s},dataType:"json",success:function(i){if(e.attr("class",n),i.error)window._alert(i.error);else{a.data=i.order,a.updateStatus();var s=a.getRow();o()("#buttons_"+a.data.id).remove(),o()("#order_"+a.data.id).replaceWith(s),i.notice&&new r.default(i.notice,{type:"success"}),("1"===d&&"payed"===t||"2"===d&&"shipped"===t||"3"===d&&"shipped"===t||"cancel"===t)&&setTimeout((function(){o()("#order_"+a.data.id).fadeOut()}),1e3),o()("[title]").tooltipster()}},error:function(t){e.attr("class",n),window._alert(t.responseJSON.error.message)}})})),n},a.updateStatus=function(){if(a.payed=!0,a.shipped=!0,a.overdued=!1,a.canceled=!1,a.icon="",a.class="",a.created_at=p(a.data.created),a.shipped_at=null,a.payed_at=null,null!==a.data.cancel_date)return a.canceled=!0,a.canceled_at=p(a.data.cancel_date),a.icon="fa-trash-o",a.class="canceled",a.tooltip="Annulée",a.shipped=!1,void(a.payed=!1);null===a.data.shipping_date?(a.shipped=!1,a.icon="fa-dropbox",a.class=" bg-success",a.tooltip="À expédier","magasin"==a.data.shipping_mode&&(a.icon="fa-shopping-bag",a.tooltip="À mettre à dispo. en magasin",a.pick=!0)):a.shipped_at=p(a.data.shipping_date),null===a.data.payment_date?(a.payed=!1,a.icon="fa-money",a.class=" bg-warning",a.tooltip="À payer"):a.payed_at=p(a.data.payment_date);var t=u(new Date,a.created_at);if(!a.payed&&t>4&&(a.overdued=!0,a.icon="fa-warning",a.class=" bg-danger",a.tooltip="À relancer",a.data.followup_date)){a.followed_up_at=p(a.data.followup_date);var e=u(new Date,a.followed_up_at);a.overdued=!1,a.icon="fa-clock-o",a.class="",a.tooltip="Relancée il y a "+e+" jours"}},a.updateStatus()}))},"1LNP":function(t,e,a){"use strict";a.r(e),a.d(e,"default",(function(){return l}));var n=a("EVdn"),o=a.n(n),r=a("0+Gh");function i(t){return(i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function s(t,e){for(var a=0;a<e.length;a++){var n=e[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,d(n.key),n)}}function d(t){var e=function(t,e){if("object"!=i(t)||!t)return t;var a=t[Symbol.toPrimitive];if(void 0!==a){var n=a.call(t,e||"default");if("object"!=i(n))return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===e?String:Number)(t)}(t,"string");return"symbol"==i(e)?e:e+""}var l=function(){return t=function t(){var e=this;!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.loadOrders(),o()("#showOrders").on("submit",(function(t){return e.loadOrders(t)})),document.getElementById("load-more-orders-button").addEventListener("click",(function(){return e.loadMoreOrders()}))},(e=[{key:"loadOrders",value:function(t,e){t&&t.preventDefault();var a={};a.status=o()("#order_status").val(),a.payment=o()("#order_payment_mode").val(),a.shipping=o()("#order_shipping_mode").val(),a.query=o()("#query").val(),a.offset=void 0===e?0:e;var n=document.getElementById("load-more-orders-button");0===a.offset&&o()("#orders").html(""),o()("#ordersLoading").show();var i=new URL(document.location);i.search=new URLSearchParams(a),n.style.opacity=0,fetch(i,{headers:{"X-Requested-With":"XMLHttpRequest"}}).then((function(t){return t.json()})).then((function(t){if(o()("#ordersLoading").hide(),t.error&&window._alert(t.error),t.results>0){var e=null;o.a.each(t.orders,(function(t,a){var n=new r.default(a);e=n.getRow(),o()("#orders").append(e)})),window.jQuery("[title]").tooltipster();var a=document.querySelectorAll("#orders tr"),i=document.getElementById("orders-count"),s=a.length;s<t.total&&(n.style.opacity=1,i.textContent=t.total-s)}else o()("#orders").html('<tr><td colspan="9" class="text-center alert-success">Aucune commande à afficher.</td></tr>')}))}},{key:"loadMoreOrders",value:function(){var t=document.querySelectorAll("#orders tr").length;this.loadOrders(null,t)}}])&&s(t.prototype,e),a&&s(t,a),Object.defineProperty(t,"prototype",{writable:!1}),t;var t,e,a}()},"9xd5":function(t,e,a){"use strict";function n(t){return(n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function o(t,e){for(var a=0;a<e.length;a++){var n=e[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,r(n.key),n)}}function r(t){var e=function(t,e){if("object"!=n(t)||!t)return t;var a=t[Symbol.toPrimitive];if(void 0!==a){var o=a.call(t,e||"default");if("object"!=n(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===e?String:Number)(t)}(t,"string");return"symbol"==n(e)?e:e+""}a.r(e),a.d(e,"default",(function(){return i}));var i=function(){return t=function t(e,a){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.text=e,a=a||{},this.params={type:a.type||"info",timeout:a.timeout||2500,sticky:a.sticky||!1,closeButton:a.closeButton||!1,loader:a.loader||!1,sound:a.sound||!1},this.init()},(e=[{key:"init",value:function(){var t=this.getNotificationsContainer(),e=this.renderNotification();t.appendChild(e),this.params.closeButton&&this.addCloseButton(),this.params.loader&&this.addLoader(),this.params.sound&&this.playSound(),this.params.sticky||window.setTimeout(function(){this.remove()}.bind(this),this.params.timeout)}},{key:"getNotificationsContainer",value:function(){var t=document.querySelector(".notifications");return t||(t=this.createNotificationsContainer()),t}},{key:"createNotificationsContainer",value:function(){var t=document.createElement("div");return t.classList.add("notifications"),document.body.appendChild(t),t}},{key:"renderNotification",value:function(){return this.element=document.createElement("div"),this.element.classList.add("notification"),this.element.classList.add("alert"),this.element.classList.add("alert-"+this.params.type),this.element.innerHTML=this.text,this.element}},{key:"addCloseButton",value:function(){var t=document.createElement("span");t.classList.add("fa"),t.classList.add("fa-close"),this.element.insertAdjacentElement("afterbegin",t),t.addEventListener("click",function(){this.remove()}.bind(this))}},{key:"addLoader",value:function(){var t=document.createElement("span");t.classList.add("fa"),t.classList.add("fa-spinner"),t.classList.add("fa-spin"),this.element.insertAdjacentElement("afterbegin",t)}},{key:"playSound",value:function(){var t=document.createElement("audio");t.style.display="none",t.autoplay=!0,t.innerHTML='<source src="/assets/sounds/'+this.params.sound+'.mp3" type="audio/mp3">',this.element.appendChild(t)}},{key:"remove",value:function(){this.element.style.opacity=0,window.setTimeout(function(){this.element.parentNode.removeChild(this.element)}.bind(this),400)}}])&&o(t.prototype,e),a&&o(t,a),Object.defineProperty(t,"prototype",{writable:!1}),t;var t,e,a}()}}]);