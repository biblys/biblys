(window.webpackJsonp=window.webpackJsonp||[]).push([[12,19],{"0+Gh":function(t,a,e){"use strict";e.r(a),e.d(a,"default",(function(){return c}));var n=e("EVdn"),i=e.n(n),s=e("9xd5");var o=function(t,a){if("short"===a){var e=t.getDate().toString().padStart(2,"0"),n=(t.getMonth()+1).toString().padStart(2,0);return'<span title="'.concat(t.toLocaleString(),'">').concat(e,"/").concat(n,"</span>")}},d=function(t,a){return Math.floor((t-a)/864e5)},r=function(t){var a=t.split(/[- :]/);return new Date(Date.UTC(a[0],a[1]-1,a[2],a[3],a[4],a[5]))},c=function t(a){!function(t,a){if(!(t instanceof a))throw new TypeError("Cannot call a class as a function")}(this,t);var e=this;e.data=a,e.getRow=function(){var t='<button title="Marquer comme expédiée" data-action="shipped" class="btn btn-xs btn-success"><i class="fa fa-dropbox"></i></button>',a="";e.pick&&(t='<button title="Marquer comme à dispo. en magasin" data-action="shipped" class="btn btn-xs btn-success"><i class="fa fa-shopping-bag"></i></button>'),e.overdued&&(a=' <button title="Relancer" data-action="followup" class="btn btn-xs btn-warning"><i class="fa fa-warning"></i></button>');var n=i()('<tr id="order_'+e.data.id+'" class="text-center '+e.class+'"><td title="'+e.tooltip+'" class="va-middle"><i class="fa '+e.icon+'"></i></td><td><a href="/order/'+e.data.url+'">'+e.data.id+"</a><td>"+o(e.created_at,"short")+'</td><td class="text-left customer">'+e.data.customer+'</td><td class="text-right">'+e.data.total+'</td><td><a href="/invoice/'+e.data.url+'" class="btn btn-xs btn-default" title="Imprimer la facture" ><i class="fa fa-print"></i></a></td><td>'+(e.data.payment_mode?'<img src="/assets/icons/payment_'+e.data.payment_mode+'.png" alt="'+e.data.payment_mode+'" title="'+e.data.payment_mode+'" width=20 />':"")+"</td><td>"+(e.payed?o(e.payed_at,"short"):'<div class="btn-group"><button title="Marquer comme payée" type="button" class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-money"></i></button><ul class="dropdown-menu"><li><a class="pointer" data-action="payed" data-mode="card">Carte bancaire</a></li><li><a class="pointer" data-action="payed" data-mode="cheque">Chèque</a></li><li><a class="pointer" data-action="payed" data-mode="cash">Espèces</a></li><li><a class="pointer" data-action="payed" data-mode="paypal">Paypal</a></li><li><a class="pointer" data-action="payed" data-mode="payplug">Payplug</a></li><li><a class="pointer" data-action="payed" data-mode="transfer">Virement</a></li></ul></div>'+a)+"</td><td>"+(e.shipped?o(e.shipped_at,"short"):t)+'</td><td><button title="Annuler la commande" data-action="cancel" class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i></button></td></tr>');return n.on("click","[data-action]",(function(){var t=i()(this).data("action"),a=i()(this).find("i"),n=a.attr("class"),o=null,d=null,r=i()("#order_status").val();if("cancel"==t&&!confirm("Voulez-vous vraiment annuler la commande n° "+e.data.id+" de "+e.data.customer+" ?"))return!1;a.attr("class","fa fa-spinner fa-spin"),"suivi"==e.data.shipping_mode&&"shipped"==t&&(d=prompt("Numéro de suivi ?")),"payed"==t&&(o=i()(this).data("mode")),i.a.ajax({type:"POST",url:"/admin/orders/"+e.data.id+"/"+t,data:{payment_mode:o,tracking_number:d},dataType:"json",success:function(o){if(a.attr("class",n),o.error)window._alert(o.error);else{e.data=o.order,e.updateStatus();var d=e.getRow();i()("#buttons_"+e.data.id).remove(),i()("#order_"+e.data.id).replaceWith(d),o.notice&&new s.default(o.notice,{type:"success"}),("1"===r&&"payed"===t||"2"===r&&"shipped"===t||"3"===r&&"shipped"===t||"cancel"===t)&&setTimeout((function(){i()("#order_"+e.data.id).fadeOut()}),1e3),i()("[title]").tooltipster()}},error:function(t){a.attr("class",n),window._alert(t.responseJSON.error)}})})),n},e.updateStatus=function(){if(e.payed=!0,e.shipped=!0,e.overdued=!1,e.canceled=!1,e.icon="",e.class="",e.created_at=r(e.data.created),e.shipped_at=null,e.payed_at=null,null!==e.data.cancel_date)return e.canceled=!0,e.canceled_at=r(e.data.cancel_date),e.icon="fa-trash-o",e.class="canceled",e.tooltip="Annulée",e.shipped=!1,void(e.payed=!1);null===e.data.shipping_date?(e.shipped=!1,e.icon="fa-dropbox",e.class=" bg-success",e.tooltip="À expédier","magasin"==e.data.shipping_mode&&(e.icon="fa-shopping-bag",e.tooltip="À mettre à dispo. en magasin",e.pick=!0)):e.shipped_at=r(e.data.shipping_date),null===e.data.payment_date?(e.payed=!1,e.icon="fa-money",e.class=" bg-warning",e.tooltip="À payer"):e.payed_at=r(e.data.payment_date);var t=d(new Date,e.created_at);if(!e.payed&&t>4&&(e.overdued=!0,e.icon="fa-warning",e.class=" bg-danger",e.tooltip="À relancer",e.data.followup_date)){e.followed_up_at=r(e.data.followup_date);var a=d(new Date,e.followed_up_at);e.overdued=!1,e.icon="fa-clock-o",e.class="",e.tooltip="Relancée il y a "+a+" jours"}},e.updateStatus()}},"9xd5":function(t,a,e){"use strict";function n(t,a){for(var e=0;e<a.length;e++){var n=a[e];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}e.r(a),e.d(a,"default",(function(){return i}));var i=function(){function t(a,e){!function(t,a){if(!(t instanceof a))throw new TypeError("Cannot call a class as a function")}(this,t),this.text=a,e=e||{},this.params={type:e.type||"info",timeout:e.timeout||2500,sticky:e.sticky||!1,closeButton:e.closeButton||!1,loader:e.loader||!1,sound:e.sound||!1},this.init()}var a,e,i;return a=t,(e=[{key:"init",value:function(){var t=this.getNotificationsContainer(),a=this.renderNotification();t.appendChild(a),this.params.closeButton&&this.addCloseButton(),this.params.loader&&this.addLoader(),this.params.sound&&this.playSound(),this.params.sticky||window.setTimeout(function(){this.remove()}.bind(this),this.params.timeout)}},{key:"getNotificationsContainer",value:function(){var t=document.querySelector(".notifications");return t||(t=this.createNotificationsContainer()),t}},{key:"createNotificationsContainer",value:function(){var t=document.createElement("div");return t.classList.add("notifications"),document.body.appendChild(t),t}},{key:"renderNotification",value:function(){return this.element=document.createElement("div"),this.element.classList.add("notification"),this.element.classList.add("alert"),this.element.classList.add("alert-"+this.params.type),this.element.innerHTML=this.text,this.element}},{key:"addCloseButton",value:function(){var t=document.createElement("span");t.classList.add("fa"),t.classList.add("fa-close"),this.element.insertAdjacentElement("afterbegin",t),t.addEventListener("click",function(){this.remove()}.bind(this))}},{key:"addLoader",value:function(){var t=document.createElement("span");t.classList.add("fa"),t.classList.add("fa-spinner"),t.classList.add("fa-spin"),this.element.insertAdjacentElement("afterbegin",t)}},{key:"playSound",value:function(){var t=document.createElement("audio");t.style.display="none",t.autoplay=!0,t.innerHTML='<source src="/assets/sounds/'+this.params.sound+'.mp3" type="audio/mp3">',this.element.appendChild(t)}},{key:"remove",value:function(){this.element.style.opacity=0,window.setTimeout(function(){this.element.parentNode.removeChild(this.element)}.bind(this),400)}}])&&n(a.prototype,e),i&&n(a,i),t}()}}]);