(window.webpackJsonp=window.webpackJsonp||[]).push([[11,17],{q5cp:function(e,n,t){"use strict";t.r(n),t.d(n,"default",(function(){return l}));var o=t("rez4");function a(e,n){return function(e){if(Array.isArray(e))return e}(e)||function(e,n){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var t=[],o=!0,a=!1,r=void 0;try{for(var i,l=e[Symbol.iterator]();!(o=(i=l.next()).done)&&(t.push(i.value),!n||t.length!==n);o=!0);}catch(e){a=!0,r=e}finally{try{o||null==l.return||l.return()}finally{if(a)throw r}}return t}(e,n)||function(e,n){if(!e)return;if("string"==typeof e)return r(e,n);var t=Object.prototype.toString.call(e).slice(8,-1);"Object"===t&&e.constructor&&(t=e.constructor.name);if("Map"===t||"Set"===t)return Array.from(e);if("Arguments"===t||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t))return r(e,n)}(e,n)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function r(e,n){(null==n||n>e.length)&&(n=e.length);for(var t=0,o=new Array(n);t<n;t++)o[t]=e[t];return o}function i(e,n){for(var t=0;t<n.length;t++){var o=n[t];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}var l=function(){function e(){var n=this;!function(e,n){if(!(e instanceof n))throw new TypeError("Cannot call a class as a function")}(this,e),this.tableBody=document.querySelector("#shipping-table tbody"),this.addRangeButton=document.querySelector("#add-range"),this.addRangeButton.addEventListener("click",(function(){var e=document.querySelector("#new-range-form"),t=n.renderForm({},(function(o){o.preventDefault();var a=new Biblys.Notification("Ajout en cours…",{loader:!0,sticky:!0});n.send(t).then((function(t){a.remove(),e.innerHTML="",n.fees.push(t),n.render(),new Biblys.Notification("La tranche <strong>".concat(t.mode,"</strong> a bien été ajoutée."),{type:"success"})}))}));e.appendChild(t)})),this.getAll()}var n,t,r;return n=e,(t=[{key:"getAll",value:function(){var e=this;return this.setLoadingState(!0),fetch("/api/shipping",{headers:{Accept:"application/json"}}).then((function(e){return e.json()})).then((function(n){e.setLoadingState(!1),e.fees=n,e.render()})).catch(window._alert)}},{key:"send",value:function(e){var n=e.id.value?"/api/shipping/".concat(e.id.value):"/api/shipping",t=e.id.value?"PUT":"POST";return fetch(n,{method:t,credentials:"same-origin",headers:{Accept:"application/json"},body:JSON.stringify(Object.fromEntries(new FormData(e)))}).then((function(e){return e.json()})).then((function(e){if(e.error)throw e.error;return e})).catch(window._alert)}},{key:"setLoadingState",value:function(e){this.tableBody.innerHTML=e?'<tr><td colspan="1">Chargement…</tr></td>':""}},{key:"render",value:function(){var e=this;this.tableBody.innerHTML="",this.fees.map((function(n){return e.renderRow(n)})).forEach((function(n){return e.tableBody.appendChild(n)}))}},{key:"renderRow",value:function(e){var n=Object(o.default)("\n      <tr>\n        <td>".concat(e.mode,"</td>\n        <td>").concat(e.type,"</td>\n        <td>").concat(e.zone,'</td>\n        <td class="nowrap">').concat(this.renderConditions(e),'</td>\n        <td class="right">').concat(window.currency(parseInt(e.fee)/100),"</td>\n      </tr>\n    ")),t=Object(o.default)("<td></td>");return t.appendChild(this.renderEditIcon(e,n)),t.appendChild(this.renderDeleteIcon(e)),n.appendChild(t),n}},{key:"renderEditIcon",value:function(e,n){var t=this,a=Object(o.default)('<span class="btn btn-primary btn-sm">\n        <span class="fa fa-pencil pointer" title="modifier la tranche"></span>\n      </span>');return a.addEventListener("click",(function(){return t.showRangeEditForm(e,n)})),a}},{key:"renderDeleteIcon",value:function(e){var n=this,t=Object(o.default)('<span class="btn btn-primary btn-sm">\n        <span class="fa fa-trash pointer" title="Supprimer la tranche"></span>\n      </span>');return t.addEventListener("click",(function(){return n.deleteRange(e)})),t}},{key:"renderConditions",value:function(e){var n=a([e.max_weight,e.max_amount,e.max_articles].map((function(e){return null===e?null:parseInt(e)})),3),t=n[0],o=n[1],r=n[2],i=[];return null!==t&&i.push("≤ ".concat(t," g")),null!==o&&i.push("≤ ".concat(window.currency(o/100))),null!==r&&i.push("≤ ".concat(r," articles")),i.join("<br/>")}},{key:"renderForm",value:function(e,n){var t=e.id,o=void 0===t?"":t,a=e.mode,r=void 0===a?"":a,i=e.type,l=void 0===i?"":i,c=e.zone,s=void 0===c?"":c,u=e.max_weight,d=void 0===u?"":u,p=e.max_amount,f=void 0===p?"":p,m=e.max_articles,v=void 0===m?"":m,h=e.fee,b=void 0===h?"":h,y=e.info,g=void 0===y?"":y,w='\n      <input type="hidden" id="id" name="id" value="'.concat(o,'">\n\n      <div class="form-group">\n        <label class="control-label label-inline" for="mode">Intitulé :</label>\n        <input type="text" id="mode" name="mode" class="form-control" value="').concat(r,'" required>\n      </div>\n\n      <div class="form-group">\n        <label class="control-label label-inline" for="mode">Type d\'envoi :</label>\n        <select id="type" name="type" class="form-control" required>\n          <option value="normal" ').concat("normal"===l?"selected":"",'>Normal</option>\n          <option value="suivi" ').concat("suivi"===l?"selected":"",'>Suivi avec numéro</option>\n          <option value="magasin" ').concat("magasin"===l?"selected":"",'>Retrait en magasin</option>\n        </select>\n      </div>\n\n      <div class="form-group">\n        <label class="control-label label-inline" for="zone">Zone :</label>\n        <select id="zone" name="zone" class="form-control" required>\n          <option value="ALL" ').concat("ALL"===s?"selected":"",'>ALL : Monde</option>\n          <option value="F" ').concat("F"===s?"selected":"",'>F : France</option>\n          <option value="OM1" ').concat("OM1"===s?"selected":"",'>\n            OM1 : Guadeloupe, Martinique, Guyane, La Réunion, Mayotte, Saint-Pierre-et-Miquelon, Saint-Martin, Saint-Barthélemy.\n          </option>\n          <option value="OM2" ').concat("OM2"===s?"selected":"",'>\n            OM2 : Nouvelle-Calédonie, Polynésie Française, Wallis-et-Futuna, Terres australes et antarctiques françaises.\n          </option>\n          <option value="A" ').concat("A"===s?"selected":"",'>\n            A : Union Européenne et Suisse\n          </option>\n          <option value="B" ').concat("B"===s?"selected":"",'>\n            B : Europe de l’Est (hors UE, Suisse et Russie), Norvège et Maghreb\n          </option>\n          <option value="C" ').concat("C"===s?"selected":"",'>\n            C : Autres destinations (hors UE et Suisse)\n          </option>\n        </select>\n      </div>\n\n      <fieldset>\n        <legend>Conditions</legend>\n\n        <p>\n          La tranche tarifaire sera proposé au client si la commande :\n        </p>\n\n        <div class="form-group">\n          <label class="control-label label-inline" for="max_weight">… a un poids (en grammes) inférieur ou égal à :</label>\n          <input type="number" min="1" step="1" id="max_weight" name="max_weight" class="form-control" value="').concat(d,'">\n        </div>\n\n        <div class="form-group">\n          <label class="control-label label-inline" for="max_amount">… a un montant (en centimes) inférieur ou égal à :</label>\n          <input type="number" min="1" step="1" id="max_amount" name="max_amount" class="form-control" value="').concat(f,'">\n        </div>\n\n        <div class="form-group">\n          <label class="control-label label-inline" for="max_articles">… a un nombre d\'exemplaires inférieur ou égal à :</label>\n          <input type="number" min="1" step="1" id="max_articles" name="max_articles" class="form-control" value="').concat(v,'">\n        </div>\n\n      </fieldset>\n\n      <div class="form-group">\n        <label class="control-label label-inline" for="fee">Montant (en centimes) :</label>\n        <input type="number" min="0" step="1" id="fee" name="fee" class="form-control" value="').concat(b,'" required>\n      </div>\n\n      <div class="form-group">\n        <label class="control-label label-inline" for="info">Commentaire (affiché au client lorsqu\'il choisit ce mode) :</label>\n        <input type="text" id="info" name="info" class="form-control" value="').concat(g,'">\n      </div>\n\n      <div class="form-group text-center">\n        <button type="submit" class="btn btn-primary">').concat(""===o?"Ajouter":"Modifier","</button>\n      </div>\n    "),L=document.createElement("form");return L.classList.add("fieldset"),L.innerHTML=w,L.addEventListener("submit",n.bind(this)),L}},{key:"deleteRange",value:function(e){var n=this;if(window.confirm("Voulez-vous vraiment supprimer la tranche ".concat(e.mode,"?"))){var t=new Biblys.Notification("Suppression en cours…",{loader:!0,sticky:!0});fetch("/api/shipping/".concat(e.id),{method:"DELETE",credentials:"same-origin",headers:{Accept:"application/json"}}).then((function(){t.remove(),new Biblys.Notification("La tranche <strong>".concat(e.mode,"</strong> a bien été supprimée."),{type:"success"}),n.fees=n.fees.filter((function(n){return n.id!==e.id})),n.render()})).catch(window._alert)}}},{key:"showRangeEditForm",value:function(e,n){var t=this,a=Object(o.default)("<tr></tr>"),r=Object(o.default)("<td colspan=8></td>"),i=this.renderForm(e,(function(n){n.preventDefault();var o=new Biblys.Notification("Mise à jour de <strong>".concat(e.mode,"</strong> en cours…"),{loader:!0,sticky:!0});t.send(i).then((function(e){o.remove(),a.remove();var n=t.fees.findIndex((function(n){return e.id===n.id}));t.fees[n]=e,t.render(),new Biblys.Notification("La tranche <strong>".concat(e.mode,"</strong> a bien été mise à jour."),{type:"success"})}))}));a.appendChild(r),r.appendChild(i),n.after(a)}}])&&i(n.prototype,t),r&&i(n,r),e}()},rez4:function(e,n,t){"use strict";function o(e){var n=document.createElement("template");return e=e.trim(),n.innerHTML=e,n.content.firstChild}t.r(n),t.d(n,"default",(function(){return o}))}}]);