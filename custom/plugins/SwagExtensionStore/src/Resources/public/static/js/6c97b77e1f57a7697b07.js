(window["webpackJsonpPluginswag-extension-store"]=window["webpackJsonpPluginswag-extension-store"]||[]).push([[203],{6398:function(){},5203:function(e,n,t){"use strict";t.r(n),t.d(n,{default:function(){return r}}),t(8170);var r={template:'{% block sw_extension_label_display %}\n	<div class="sw-extension-store-label-display">\n        {% block sw_extension_label_display_label %}\n            <sw-extension-label v-for="item in labels"\n                                :key="item.label"\n                                :backgroundColor="item.color">\n                {{ item.label }}\n            </sw-extension-label>\n        {% endblock %}\n    </div>\n{% endblock %}\n',props:{labels:{type:Array,required:!0}}}},8170:function(e,n,t){var r=t(6398);r.__esModule&&(r=r.default),"string"==typeof r&&(r=[[e.id,r,""]]),r.locals&&(e.exports=r.locals),t(5346).Z("7f63b906",r,!0,{})},5346:function(e,n,t){"use strict";function r(e,n){for(var t=[],r={},s=0;s<n.length;s++){var o=n[s],a=o[0],i={id:e+":"+s,css:o[1],media:o[2],sourceMap:o[3]};r[a]?r[a].parts.push(i):t.push(r[a]={id:a,parts:[i]})}return t}t.d(n,{Z:function(){return v}});var s="undefined"!=typeof document;if("undefined"!=typeof DEBUG&&DEBUG&&!s)throw Error("vue-style-loader cannot be used in a non-browser environment. Use { target: 'node' } in your Webpack config to indicate a server-rendering environment.");var o={},a=s&&(document.head||document.getElementsByTagName("head")[0]),i=null,l=0,d=!1,u=function(){},c=null,p="data-vue-ssr-id",f="undefined"!=typeof navigator&&/msie [6-9]\b/.test(navigator.userAgent.toLowerCase());function v(e,n,t,s){d=t,c=s||{};var a=r(e,n);return h(a),function(n){for(var t=[],s=0;s<a.length;s++){var i=o[a[s].id];i.refs--,t.push(i)}n?h(a=r(e,n)):a=[];for(var s=0;s<t.length;s++){var i=t[s];if(0===i.refs){for(var l=0;l<i.parts.length;l++)i.parts[l]();delete o[i.id]}}}}function h(e){for(var n=0;n<e.length;n++){var t=e[n],r=o[t.id];if(r){r.refs++;for(var s=0;s<r.parts.length;s++)r.parts[s](t.parts[s]);for(;s<t.parts.length;s++)r.parts.push(g(t.parts[s]));r.parts.length>t.parts.length&&(r.parts.length=t.parts.length)}else{for(var a=[],s=0;s<t.parts.length;s++)a.push(g(t.parts[s]));o[t.id]={id:t.id,refs:1,parts:a}}}}function b(){var e=document.createElement("style");return e.type="text/css",a.appendChild(e),e}function g(e){var n,t,r=document.querySelector("style["+p+'~="'+e.id+'"]');if(r){if(d)return u;r.parentNode.removeChild(r)}if(f){var s=l++;n=y.bind(null,r=i||(i=b()),s,!1),t=y.bind(null,r,s,!0)}else n=w.bind(null,r=b()),t=function(){r.parentNode.removeChild(r)};return n(e),function(r){r?(r.css!==e.css||r.media!==e.media||r.sourceMap!==e.sourceMap)&&n(e=r):t()}}var m=function(){var e=[];return function(n,t){return e[n]=t,e.filter(Boolean).join("\n")}}();function y(e,n,t,r){var s=t?"":r.css;if(e.styleSheet)e.styleSheet.cssText=m(n,s);else{var o=document.createTextNode(s),a=e.childNodes;a[n]&&e.removeChild(a[n]),a.length?e.insertBefore(o,a[n]):e.appendChild(o)}}function w(e,n){var t=n.css,r=n.media,s=n.sourceMap;if(r&&e.setAttribute("media",r),c.ssrId&&e.setAttribute(p,n.id),s&&(t+="\n/*# sourceURL="+s.sources[0]+" */\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(s))))+" */"),e.styleSheet)e.styleSheet.cssText=t;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(t))}}}}]);