(window.webpackJsonp=window.webpackJsonp||[]).push([[15],{NGbs:function(e,n,t){"use strict";function s(e,n){for(var t=0;t<n.length;t++){var s=n[t];s.enumerable=s.enumerable||!1,s.configurable=!0,"value"in s&&(s.writable=!0),Object.defineProperty(e,s.key,s)}}t.r(n),t.d(n,"default",(function(){return i}));var i=function(){function e(n,t){!function(e,n){if(!(e instanceof n))throw new TypeError("Cannot call a class as a function")}(this,e),this.props={},this.props.name=n.name,this.props.url=n.url,this.props.icon=n.icon,this.props.class=n.class,this.props.subscription=n.subscription,this.manager=t}var n,t,i;return n=e,(t=[{key:"renderHtml",value:function(){this.element=document.createElement("div"),this.element.classList.add("shortcut"),this.element.innerHTML='<span class="icon fa fa-'+this.props.icon+'"></span> <p>'+this.props.name+"</p>";var e=document.createElement("p");return e.innerHTML='<button class="btn btn-xs btn-danger"><span class="fa fa-trash-o"></span></button>',this.element.appendChild(e),e.addEventListener("click",function(){this.manager.remove(this)}.bind(this)),this.element}},{key:"remove",value:function(){this.element.parentNode.removeChild(this.element)}}])&&s(n.prototype,t),i&&s(n,i),e}()}}]);