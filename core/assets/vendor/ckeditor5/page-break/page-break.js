!function(e){const t=e.en=e.en||{};t.dictionary=Object.assign(t.dictionary||{},{"Page break":"Page break"})}(window.CKEDITOR_TRANSLATIONS||(window.CKEDITOR_TRANSLATIONS={})),
/*!
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md.
 */(()=>{var e={835:(e,t,n)=>{"use strict";n.d(t,{A:()=>o});var r=n(935),a=n.n(r)()((function(e){return e[1]}));a.push([e.id,'.ck-content .page-break{align-items:center;clear:both;display:flex;justify-content:center;padding:5px 0;position:relative}.ck-content .page-break:after{border-bottom:2px dashed #c4c4c4;content:"";position:absolute;width:100%}.ck-content .page-break__label{background:#fff;border:1px solid #c4c4c4;border-radius:2px;box-shadow:2px 2px 1px rgba(0,0,0,.15);color:#333;display:block;font-family:Helvetica,Arial,Tahoma,Verdana,Sans-Serif;font-size:.75em;font-weight:700;padding:.3em .6em;position:relative;text-transform:uppercase;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;z-index:1}@media print{.ck-content .page-break{padding:0}.ck-content .page-break:after{display:none}.ck-content :has(+.page-break){margin-bottom:0}}',""]);const o=a},935:e=>{"use strict";e.exports=function(e){var t=[];return t.toString=function(){return this.map((function(t){var n=e(t);return t[2]?"@media ".concat(t[2]," {").concat(n,"}"):n})).join("")},t.i=function(e,n,r){"string"==typeof e&&(e=[[null,e,""]]);var a={};if(r)for(var o=0;o<this.length;o++){var i=this[o][0];null!=i&&(a[i]=!0)}for(var s=0;s<e.length;s++){var c=[].concat(e[s]);r&&a[c[0]]||(n&&(c[2]?c[2]="".concat(n," and ").concat(c[2]):c[2]=n),t.push(c))}},t}},591:(e,t,n)=>{"use strict";var r,a=function(){return void 0===r&&(r=Boolean(window&&document&&document.all&&!window.atob)),r},o=function(){var e={};return function(t){if(void 0===e[t]){var n=document.querySelector(t);if(window.HTMLIFrameElement&&n instanceof window.HTMLIFrameElement)try{n=n.contentDocument.head}catch(e){n=null}e[t]=n}return e[t]}}(),i=[];function s(e){for(var t=-1,n=0;n<i.length;n++)if(i[n].identifier===e){t=n;break}return t}function c(e,t){for(var n={},r=[],a=0;a<e.length;a++){var o=e[a],c=t.base?o[0]+t.base:o[0],l=n[c]||0,d="".concat(c," ").concat(l);n[c]=l+1;var u=s(d),p={css:o[1],media:o[2],sourceMap:o[3]};-1!==u?(i[u].references++,i[u].updater(p)):i.push({identifier:d,updater:h(p,t),references:1}),r.push(d)}return r}function l(e){var t=document.createElement("style"),r=e.attributes||{};if(void 0===r.nonce){var a=n.nc;a&&(r.nonce=a)}if(Object.keys(r).forEach((function(e){t.setAttribute(e,r[e])})),"function"==typeof e.insert)e.insert(t);else{var i=o(e.insert||"head");if(!i)throw new Error("Couldn't find a style target. This probably means that the value for the 'insert' parameter is invalid.");i.appendChild(t)}return t}var d,u=(d=[],function(e,t){return d[e]=t,d.filter(Boolean).join("\n")});function p(e,t,n,r){var a=n?"":r.media?"@media ".concat(r.media," {").concat(r.css,"}"):r.css;if(e.styleSheet)e.styleSheet.cssText=u(t,a);else{var o=document.createTextNode(a),i=e.childNodes;i[t]&&e.removeChild(i[t]),i.length?e.insertBefore(o,i[t]):e.appendChild(o)}}function f(e,t,n){var r=n.css,a=n.media,o=n.sourceMap;if(a?e.setAttribute("media",a):e.removeAttribute("media"),o&&"undefined"!=typeof btoa&&(r+="\n/*# sourceMappingURL=data:application/json;base64,".concat(btoa(unescape(encodeURIComponent(JSON.stringify(o))))," */")),e.styleSheet)e.styleSheet.cssText=r;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(r))}}var g=null,m=0;function h(e,t){var n,r,a;if(t.singleton){var o=m++;n=g||(g=l(t)),r=p.bind(null,n,o,!1),a=p.bind(null,n,o,!0)}else n=l(t),r=f.bind(null,n,t),a=function(){!function(e){if(null===e.parentNode)return!1;e.parentNode.removeChild(e)}(n)};return r(e),function(t){if(t){if(t.css===e.css&&t.media===e.media&&t.sourceMap===e.sourceMap)return;r(e=t)}else a()}}e.exports=function(e,t){(t=t||{}).singleton||"boolean"==typeof t.singleton||(t.singleton=a());var n=c(e=e||[],t);return function(e){if(e=e||[],"[object Array]"===Object.prototype.toString.call(e)){for(var r=0;r<n.length;r++){var a=s(n[r]);i[a].references--}for(var o=c(e,t),l=0;l<n.length;l++){var d=s(n[l]);0===i[d].references&&(i[d].updater(),i.splice(d,1))}n=o}}}},782:(e,t,n)=>{e.exports=n(237)("./src/core.js")},311:(e,t,n)=>{e.exports=n(237)("./src/ui.js")},901:(e,t,n)=>{e.exports=n(237)("./src/widget.js")},237:e=>{"use strict";e.exports=CKEditor5.dll}},t={};function n(r){var a=t[r];if(void 0!==a)return a.exports;var o=t[r]={id:r,exports:{}};return e[r](o,o.exports,n),o.exports}n.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return n.d(t,{a:t}),t},n.d=(e,t)=>{for(var r in t)n.o(t,r)&&!n.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},n.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),n.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.nc=void 0;var r={};(()=>{"use strict";n.r(r),n.d(r,{PageBreak:()=>p,PageBreakEditing:()=>l,PageBreakUI:()=>u});var e=n(782),t=n(901);class a extends e.Command{refresh(){const e=this.editor.model,n=e.schema,r=e.document.selection;this.isEnabled=function(e,n,r){const a=function(e,n){const r=(0,t.findOptimalInsertionRange)(e,n),a=r.start.parent;if(a.isEmpty&&!a.is("element","$root"))return a.parent;return a}(e,r);return n.checkChild(a,"pageBreak")}(r,n,e)}execute(){const e=this.editor.model;e.change((t=>{const n=t.createElement("pageBreak");e.insertObject(n,null,null,{setSelection:"after"})}))}}var o=n(591),i=n.n(o),s=n(835),c={injectType:"singletonStyleTag",attributes:{"data-cke":!0},insert:"head",singleton:!0};i()(s.A,c);s.A.locals;class l extends e.Plugin{static get pluginName(){return"PageBreakEditing"}init(){const e=this.editor,n=e.model.schema,r=e.t,o=e.conversion;n.register("pageBreak",{inheritAllFrom:"$blockObject"}),o.for("dataDowncast").elementToStructure({model:"pageBreak",view:(e,{writer:t})=>t.createContainerElement("div",{class:"page-break",style:"page-break-after: always"},t.createContainerElement("span",{style:"display: none"}))}),o.for("editingDowncast").elementToStructure({model:"pageBreak",view:(e,{writer:n})=>{const a=r("Page break"),o=n.createContainerElement("div"),i=n.createRawElement("span",{class:"page-break__label"},(function(e){e.innerText=r("Page break")}));return n.addClass("page-break",o),n.insert(n.createPositionAt(o,0),i),function(e,n,r){return n.setCustomProperty("pageBreak",!0,e),(0,t.toWidget)(e,n,{label:r})}(o,n,a)}}),o.for("upcast").elementToElement({view:e=>{const t="always"==e.getStyle("page-break-before"),n="always"==e.getStyle("page-break-after");if(!t&&!n)return null;if(1==e.childCount){const t=e.getChild(0);if(!t.is("element","span")||"none"!=t.getStyle("display"))return null}else if(e.childCount>1)return null;return{name:!0}},model:"pageBreak",converterPriority:"high"}),e.commands.add("pageBreak",new a(e))}}var d=n(311);class u extends e.Plugin{static get pluginName(){return"PageBreakUI"}init(){const e=this.editor;e.ui.componentFactory.add("pageBreak",(()=>{const e=this._createButton(d.ButtonView);return e.set({tooltip:!0}),e})),e.ui.componentFactory.add("menuBar:pageBreak",(()=>this._createButton(d.MenuBarMenuListItemButtonView)))}_createButton(e){const t=this.editor,n=t.locale,r=t.commands.get("pageBreak"),a=new e(t.locale),o=n.t;return a.set({label:o("Page break"),icon:'<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3.598.687h1.5v5h-1.5zm14.5 0h1.5v5h-1.5z"/><path d="M19.598 4.187v1.5h-16v-1.5zm-16 14.569h1.5v-5h-1.5zm14.5 0h1.5v-5h-1.5z"/><path d="M19.598 15.256v-1.5h-16v1.5zM5.081 9h6v2h-6zm8 0h6v2h-6zm-9.483 1L0 12.5v-5z"/></svg>'}),a.bind("isEnabled").to(r,"isEnabled"),this.listenTo(a,"execute",(()=>{t.execute("pageBreak"),t.editing.view.focus()})),a}}class p extends e.Plugin{static get requires(){return[l,u,t.Widget]}static get pluginName(){return"PageBreak"}}})(),(window.CKEditor5=window.CKEditor5||{}).pageBreak=r})();