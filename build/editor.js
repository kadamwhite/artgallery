!function(e){var t={};function n(r){if(t[r])return t[r].exports;var a=t[r]={i:r,l:!1,exports:{}};return e[r].call(a.exports,a,a.exports,n),a.l=!0,a.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"===typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)n.d(r,a,function(t){return e[t]}.bind(null,a));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=38)}([function(e,t){e.exports=wp.i18n},function(e,t){e.exports=wp.element},function(e,t,n){"use strict";n.d(t,"d",(function(){return r})),n.d(t,"f",(function(){return a})),n.d(t,"g",(function(){return o})),n.d(t,"h",(function(){return l})),n.d(t,"e",(function(){return i})),n.d(t,"c",(function(){return c})),n.d(t,"b",(function(){return u})),n.d(t,"a",(function(){return s}));var r="ag_artwork_item",a="ag_artwork_availability",o="artwork_availability",l="ag_artwork_media",i="width",c="height",u="depth",s="creation_date"},function(e,t){e.exports=wp.data},function(e,t){e.exports=wp.components},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.autoloadPlugins=t.unregisterPlugin=t.registerPlugin=t.autoloadBlocks=t.afterUpdateBlocks=t.beforeUpdateBlocks=t.unregisterBlock=t.registerBlock=t.autoload=void 0;var r=window.wp,a=r.blocks,o=r.plugins,l=r.hooks,i=r.data,c=function(){},u=function(e){var t=e.getContext,n=e.register,r=e.unregister,a=e.before,o=void 0===a?c:a,l=e.after,i=void 0===l?c:l,u=arguments.length>1&&void 0!==arguments[1]?arguments[1]:c,s={},m=function(){o();var e=t(),a=[];return e.keys().forEach((function(t){var o=e(t);o!==s[t]&&(s[t]&&r(s[t]),n(o),a.push(o),s[t]=o)})),i(a),e},p=m();u(p,m)};t.autoload=u;var s=null,m=function(e){var t=e.name,n=e.settings,r=e.filters,o=e.styles;t&&n&&a.registerBlockType(t,n),r&&Array.isArray(r)&&r.forEach((function(e){var t=e.hook,n=e.namespace,r=e.callback;l.addFilter(t,n,r)})),o&&Array.isArray(o)&&o.forEach((function(e){return a.registerBlockStyle(t,e)}))};t.registerBlock=m;var p=function(e){var t=e.name,n=e.settings,r=e.filters,o=e.styles;t&&n&&a.unregisterBlockType(t),r&&Array.isArray(r)&&r.forEach((function(e){var t=e.hook,n=e.namespace;l.removeFilter(t,n)})),o&&Array.isArray(o)&&o.forEach((function(e){return a.unregisterBlockStyle(t,e.name)}))};t.unregisterBlock=p;var d=function(){s=i.select("core/editor").getSelectedBlockClientId(),i.dispatch("core/editor").clearSelectedBlock()};t.beforeUpdateBlocks=d;var f=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:[],t=e.map((function(e){return e.name}));t.length&&(i.select("core/editor").getBlocks().forEach((function(e){var n=e.name,r=e.clientId;t.includes(n)&&i.dispatch("core/editor").selectBlock(r)})),s?i.dispatch("core/editor").selectBlock(s):i.dispatch("core/editor").clearSelectedBlock(),s=null)};t.afterUpdateBlocks=f;t.autoloadBlocks=function(e,t){var n=e.getContext,r=e.register,a=void 0===r?m:r,o=e.unregister,l=void 0===o?p:o,i=e.before,c=void 0===i?d:i,s=e.after;u({getContext:n,register:a,unregister:l,before:c,after:void 0===s?f:s},t)};var g=function(e){var t=e.name,n=e.settings,r=e.filters;t&&n&&o.registerPlugin(t,n),r&&Array.isArray(r)&&r.forEach((function(e){var t=e.hook,n=e.namespace;l.removeFilter(t,n)}))};t.registerPlugin=g;var b=function(e){var t=e.name,n=e.settings,r=e.filters;t&&n&&o.unregisterPlugin(t),r&&Array.isArray(r)&&r.forEach((function(e){var t=e.hook,n=e.namespace;l.removeFilter(t,n)}))};t.unregisterPlugin=b;t.autoloadPlugins=function(e,t){var n=e.getContext,r=e.register,a=void 0===r?g:r,o=e.unregister,l=void 0===o?b:o,i=e.before,c=e.after;u({getContext:n,register:a,unregister:l,before:i,after:c},t)}},function(e,t){e.exports=wp.editor},function(e,t){e.exports=wp.compose},function(e,t,n){"use strict";n.d(t,"a",(function(){return r}));var r=function(e){return{element:function(t){return"".concat(e,"__").concat(t)},modifier:function(t,n){return t&&void 0===n?"".concat(e,"--").concat(t):"".concat(e,"__").concat(t,"--").concat(n)},toString:function(){return e}}}},function(e,t){e.exports=function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}},function(e,t){e.exports=function(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}},function(e,t){e.exports=wp.editPost},function(e,t,n){var r=n(24);e.exports=function(e,t){if(null==e)return{};var n,a,o=r(e,t);if(Object.getOwnPropertySymbols){var l=Object.getOwnPropertySymbols(e);for(a=0;a<l.length;a++)n=l[a],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(o[n]=e[n])}return o}},function(e,t){e.exports=function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}},function(e,t){function n(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}e.exports=function(e,t,r){return t&&n(e.prototype,t),r&&n(e,r),e}},function(e,t,n){var r=n(26),a=n(9);e.exports=function(e,t){return!t||"object"!==r(t)&&"function"!==typeof t?a(e):t}},function(e,t){function n(t){return e.exports=n=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)},n(t)}e.exports=n},function(e,t,n){var r=n(27);e.exports=function(e,t){if("function"!==typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&r(e,t)}},function(e,t,n){var r=n(10);e.exports=function(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{},a=Object.keys(n);"function"===typeof Object.getOwnPropertySymbols&&(a=a.concat(Object.getOwnPropertySymbols(n).filter((function(e){return Object.getOwnPropertyDescriptor(n,e).enumerable})))),a.forEach((function(t){r(e,t,n[t])}))}return e}},function(e,t){e.exports=wp.blocks},function(e,t){e.exports=wp.serverSideRender},function(e,t){e.exports=wp.blockEditor},,,function(e,t){e.exports=function(e,t){if(null==e)return{};var n,r,a={},o=Object.keys(e);for(r=0;r<o.length;r++)n=o[r],t.indexOf(n)>=0||(a[n]=e[n]);return a}},function(e,t,n){var r={"./artwork-grid/index.js":39,"./availability/index.js":42,"./core-image/index.js":29,"./metadata/index.js":41};function a(e){var t=o(e);return n(t)}function o(e){if(!n.o(r,e)){var t=new Error("Cannot find module '"+e+"'");throw t.code="MODULE_NOT_FOUND",t}return r[e]}a.keys=function(){return Object.keys(r)},a.resolve=o,e.exports=a,a.id=25},function(e,t){function n(e){return(n="function"===typeof Symbol&&"symbol"===typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"===typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function r(t){return"function"===typeof Symbol&&"symbol"===n(Symbol.iterator)?e.exports=r=function(e){return n(e)}:e.exports=r=function(e){return e&&"function"===typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":n(e)},r(t)}e.exports=r},function(e,t){function n(t,r){return e.exports=n=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e},n(t,r)}e.exports=n},function(e,t,n){},function(e,t,n){"use strict";n.r(t),n.d(t,"name",(function(){return l})),n.d(t,"filters",(function(){return i}));var r=n(7),a=n(3),o=n(2),l="core/image",i=[{hook:"editor.BlockEdit",namespace:"artgallery/".concat(l),callback:Object(r.createHigherOrderComponent)((function(e){return Object(a.withSelect)((function(e,t){if("core/image"===t.name&&t.attributes.id){var n=e("core/editor").getEditedPostAttribute;n("type")===o.d&&(n("featured_media")||Object(a.dispatch)("core/editor").editPost({featured_media:t.attributes.id}))}return null}))(e)}),"autoFeatureFirstSelectedImage")}]},function(e,t,n){},function(e,t,n){var r={"./missing-featured-image-warning/index.js":32};function a(e){var t=o(e);return n(t)}function o(e){if(!n.o(r,e)){var t=new Error("Cannot find module '"+e+"'");throw t.code="MODULE_NOT_FOUND",t}return r[e]}a.keys=function(){return Object.keys(r)},a.resolve=o,e.exports=a,a.id=31},function(e,t,n){"use strict";n.r(t),n.d(t,"name",(function(){return m})),n.d(t,"options",(function(){return d}));var r=n(1),a=n(11),o=n(0),l=n(4),i=n(7),c=n(3),u=n(6),s=n(2),m="artgallery-missing-featured-image-warning",p=function(){return wp.element.createElement("p",{style:{display:"flex",alignItems:"center"}},wp.element.createElement(l.Icon,{icon:"warning"}),"\xa0\xa0",Object(o.__)("No Featured Image has been set!","artgallery"))},d={icon:"warning",render:Object(i.compose)(Object(c.withSelect)((function(e){var t=e("core").getPostType,n=e("core/editor").getEditedPostAttribute,r=n("type"),a=t(r),o=a&&a.labels||{};return{isArtwork:r===s.d,featuredImageId:n("featured_media"),featuredImageLabel:o.featured_image,setFeaturedImageLabel:o.set_featured_image}})),Object(c.withDispatch)((function(e){return{onUpdateImage:function(t){e("core/editor").editPost({featured_media:t.id})}}})))((function(e){var t=e.isArtwork,n=e.featuredImageId,i=e.featuredImageLabel,c=e.setFeaturedImageLabel,s=e.onUpdateImage;if(!t||n)return null;var m=Object(o.__)("Featured Image"),d=Object(o.__)("Set Featured Image");return wp.element.createElement(r.Fragment,null,wp.element.createElement(a.PluginPostStatusInfo,null,wp.element.createElement(p,null)),wp.element.createElement(a.PluginPrePublishPanel,null,wp.element.createElement(p,null),wp.element.createElement("p",null,Object(o.__)("Please select a featured image below before publishing.","artgallery")),wp.element.createElement(u.MediaUploadCheck,{fallback:wp.element.createElement("p",null,Object(o.__)("To edit the featured image, you need permission to upload media."))},wp.element.createElement(u.MediaUpload,{title:i||m,onSelect:s,allowedTypes:["image"],modalClass:"editor-post-featured-image__media-modal",render:function(e){var t=e.open;return wp.element.createElement(l.Button,{className:"editor-post-featured-image__toggle",onClick:t},c||d)},value:null}))))}))}},,,,,,function(e,t,n){"use strict";n.r(t);var r=n(12),a=n.n(r),o=n(5),l=window.ARTGALLERY_CURRENT_SCREEN,i=l&&l.post_type,c=function(e){return function(t){var n=t.postTypes,r=a()(t,["postTypes"]);i&&Array.isArray(n)&&n.length?n.includes(i)&&e(r):e(r)}};Object(o.autoloadBlocks)({getContext:function(){return n(25)},register:c(o.registerBlock),unregister:c(o.unregisterBlock)},(function(e,t){0})),Object(o.autoload)({getContext:function(){return n(31)},register:o.registerPlugin,unregister:o.unregisterPlugin},(function(e,t){0}))},function(e,t,n){"use strict";n.r(t);var r=n(1),a=n(0),o=n(6),l=n(13),i=n.n(l),c=n(14),u=n.n(c),s=n(15),m=n.n(s),p=n(16),d=n.n(p),f=n(9),g=n.n(f),b=n(17),h=n.n(b),w=function(e){function t(e){var n;return i()(this,t),(n=m()(this,d()(t).call(this,e))).state={result:null},n.checkChildren=n.checkChildren.bind(g()(n)),n}return h()(t,e),u()(t,[{key:"componentDidMount",value:function(){this.interval=setInterval(this.checkChildren,100)}},{key:"componentWillUnmount",value:function(){clearInterval(this.interval)}},{key:"checkChildren",value:function(){if(this.el){var e=this.props.check(this.el);this.state.result!==e&&(this.props.onChange(e),this.setState({result:e}),this.props.once&&clearInterval(this.interval))}}},{key:"render",value:function(){var e=this,t=this.props.children;return wp.element.createElement("div",{ref:function(t){return e.el=t}},t)}}]),t}(r.Component);n.d(t,"name",(function(){return v})),n.d(t,"settings",(function(){return _}));var y=function(){window.agUpdateResponsiveContainers&&window.agUpdateResponsiveContainers()},v="artgallery/artwork-grid",_={title:Object(a.__)("Artwork Grid","artgallery"),description:Object(a.__)("Display a grid of recent artwork.","artgallery"),icon:function(){return wp.element.createElement("svg",{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20"},wp.element.createElement("g",null,wp.element.createElement("rect",{x:"0",y:"4",width:"6",height:"6"}),wp.element.createElement("rect",{x:"7",y:"4",width:"6",height:"6"}),wp.element.createElement("rect",{x:"14",y:"4",width:"6",height:"6"}),wp.element.createElement("rect",{x:"0",y:"11",width:"6",height:"6"}),wp.element.createElement("rect",{x:"7",y:"11",width:"6",height:"6"}),wp.element.createElement("rect",{x:"14",y:"11",width:"6",height:"6"})))},category:"artgallery",supports:{align:["full","wide"]},attributes:{message:{type:"string",default:"Contact artist for pricing."}},edit:function(){return wp.element.createElement(w,{onChange:y,check:function(e){return e.querySelector("[data-responsive-container]")},once:!0},wp.element.createElement(o.ServerSideRender,{block:v}))},save:function(){return null}}},,function(e,t,n){"use strict";n.r(t);var r=n(1),a=n(0),o=n(6),l=n(4),i=n(7),c=n(3),u=n(2),s=n(8);n(30);n.d(t,"name",(function(){return m})),n.d(t,"settings",(function(){return d})),n.d(t,"postTypes",(function(){return f}));var m="artgallery/metadata",p=Object(s.a)("artwork-metadata"),d={title:Object(a.__)("Artwork Metadata","artgallery"),description:Object(a.__)("List the date, size & materials for a given artwork.","artgallery"),icon:function(){return wp.element.createElement("svg",{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24",role:"img","aria-hidden":"true",focusable:"false"},wp.element.createElement("path",{d:"M 0,0 20,24 H 0 Z"}),wp.element.createElement("g",{fill:"white",stroke:"none"},wp.element.createElement("path",{d:"m 5,11 7,9 H 5 Z"})),wp.element.createElement("g",{stroke:"white",strokeWidth:"1.25"},wp.element.createElement("path",{d:"M 0,8 H 2 Z"}),wp.element.createElement("path",{d:"M 0,12 H 2 Z"}),wp.element.createElement("path",{d:"M 0,16 H 2 Z"}),wp.element.createElement("path",{d:"M 0,20 H 2 Z"})))},category:"artgallery",attributes:{width:{type:"number",source:"meta",meta:u.e},height:{type:"number",source:"meta",meta:u.c},depth:{type:"number",source:"meta",meta:u.b},date:{type:"string",source:"meta",meta:u.a,default:null}},edit:Object(i.compose)(Object(c.withSelect)((function(e){return{postId:e("core/editor").getEditedPostAttribute("id")}})),Object(c.withDispatch)((function(e,t,n){var r=n.select;return{openSidebar:function(){e("core/edit-post").openGeneralSidebar("edit-post/document");var t="taxonomy-panel-".concat(u.h);r("core/edit-post").isEditorPanelOpened(t)||e("core/edit-post").toggleEditorPanelOpened(t)}}})))((function(e){var t=e.attributes,n=e.isSelected,i=e.setAttributes,c=e.openSidebar,u=t.width||t.height||t.depth||t.date;return n||!u?wp.element.createElement(r.Fragment,null,wp.element.createElement("h2",{className:p.element("title")},Object(a.__)("Edit Artwork Metadata","artgallery")),wp.element.createElement(l.TextControl,{className:p.element("date"),label:Object(a.__)("When was this artwork completed?","artgallery"),value:t.date,onChange:function(e){return i({date:e})}}),wp.element.createElement("p",{className:p.element("message")},Object(a.__)("Specify artwork dimensions:","artgallery")),wp.element.createElement("div",{className:p.element("container")},wp.element.createElement(l.TextControl,{className:p.element("input"),label:Object(a.__)("inches width","artgallery"),value:t.width,type:"number",onChange:function(e){return i({width:e})}}),wp.element.createElement("span",null,"x"),wp.element.createElement(l.TextControl,{className:p.element("input"),label:Object(a.__)("inches tall","artgallery"),value:t.height,type:"number",onChange:function(e){return i({height:e})}}),wp.element.createElement("span",null,"x"),wp.element.createElement(l.TextControl,{className:p.element("input"),label:Object(a.__)("inches deep (optional)","artgallery"),value:t.depth,type:"number",onChange:function(e){return i({depth:e})}})),wp.element.createElement("p",{className:p.element("message")},Object(a.__)("To modify artwork media information, add or remove terms in the Document sidebar.","artgallery"),wp.element.createElement("button",{className:"components-button is-button is-default ".concat(p.element("button")),onClick:c},Object(a.__)("Open Document Sidebar","artgallery")))):wp.element.createElement(o.ServerSideRender,{block:m,attributes:t})})),save:function(){return null}},f=[u.d]},function(e,t,n){"use strict";n.r(t);var r=n(10),a=n.n(r),o=n(18),l=n.n(o),i=n(1),c=n(0),u=n(19),s=n(4),m=n(20),p=n.n(m),d=n(21),f=n(3),g=n(2),b=n(8);n(28);n.d(t,"name",(function(){return h})),n.d(t,"settings",(function(){return v})),n.d(t,"postTypes",(function(){return _}));var h="artgallery/availability",w=Object(b.a)("artwork-availability"),y=function(e){return!(!e||"available"!==e.slug)},v={title:Object(c.__)("Artwork Availability"),description:Object(c.__)("Mark an artwork as sold, not for sale, or available (with contact link)."),icon:function(){return wp.element.createElement("svg",{xmlns:"http://www.w3.org/2000/svg",x:"0px",y:"0px",viewBox:"0 0 48 48",enableBackground:"new 0 0 48 48",xmlSpace:"preserve"},wp.element.createElement("g",null,wp.element.createElement("path",{d:"M28.086,13.282c-1.09-0.693-2.438-0.794-4.361-0.52c-1.631,0.228-2.455,0.242-3.091-0.164   c-0.49-0.312-0.657-0.84,0.396-1.29c0.876-0.374,1.708-0.443,2.14-0.472l-0.463-0.662c-0.606,0.038-1.364,0.152-2.207,0.488   l-1.188-0.756l-0.849,0.363l1.237,0.787c-1.076,0.655-1.113,1.491-0.096,2.137c1.064,0.679,2.489,0.668,4.375,0.384   c1.382-0.199,2.331-0.162,3.004,0.267c0.697,0.444,0.543,1.045-0.429,1.461c-0.767,0.327-1.711,0.482-2.497,0.506l0.491,0.663   c0.784-0.01,1.827-0.22,2.634-0.552l1.236,0.788l0.862-0.368l-1.274-0.812C29.207,14.796,29.053,13.897,28.086,13.282z"}),wp.element.createElement("path",{d:"M0.606,15.666c-0.335,0.138-0.566,0.448-0.602,0.809c-0.036,0.36,0.13,0.711,0.43,0.912l13.804,9.216   c0.165,0.109,0.354,0.165,0.544,0.165c0.143,0,0.285-0.03,0.418-0.094l10.332-4.881v-0.158L10.24,11.701L0.606,15.666z    M10.855,18.405c0.611-0.27,1.549-0.245,2.094,0.057c0.546,0.304,0.493,0.768-0.117,1.039c-0.611,0.271-1.548,0.245-2.095-0.058   C10.192,19.139,10.245,18.676,10.855,18.405z"}),wp.element.createElement("path",{d:"M48,10.566c0.003-0.376-0.211-0.721-0.549-0.887L33.121,2.64c-0.252-0.124-0.545-0.133-0.805-0.027L23.675,6.17   l14.706,9.553l9.058-4.279C47.779,11.283,47.997,10.942,48,10.566z M37.152,8.722c-0.611,0.27-1.549,0.243-2.094-0.06   c-0.547-0.302-0.494-0.767,0.115-1.037c0.611-0.272,1.549-0.247,2.095,0.057C37.814,7.985,37.762,8.45,37.152,8.722z"}),wp.element.createElement("path",{d:"M14.784,28.481c-0.19,0-0.38-0.057-0.544-0.165L3.142,20.907l-2.535,1.043c-0.335,0.138-0.566,0.449-0.602,0.81   c-0.036,0.36,0.13,0.71,0.43,0.912l13.804,9.216c0.165,0.109,0.354,0.165,0.544,0.165c0.143,0,0.285-0.03,0.418-0.094l10.332-4.881   v-4.572l-10.332,4.882C15.069,28.45,14.926,28.481,14.784,28.481z"}),wp.element.createElement("path",{d:"M47.451,15.964l-2.919-1.434L38.5,17.38v4.572l8.938-4.222c0.341-0.161,0.559-0.502,0.562-0.878   S47.789,16.13,47.451,15.964z"}),wp.element.createElement("path",{d:"M14.784,34.685c-0.19,0-0.38-0.056-0.544-0.164L3.142,27.11l-2.535,1.045c-0.335,0.137-0.566,0.448-0.602,0.809   c-0.036,0.359,0.13,0.711,0.43,0.912l13.804,9.216c0.165,0.108,0.354,0.165,0.544,0.165c0.143,0,0.285-0.031,0.418-0.094   l10.332-4.881V29.71l-10.332,4.881C15.069,34.653,14.926,34.685,14.784,34.685z"}),wp.element.createElement("path",{d:"M47.451,22.168l-2.919-1.434l-6.032,2.85v4.571l8.938-4.222c0.341-0.161,0.559-0.502,0.562-0.878   S47.789,22.333,47.451,22.168z"}),wp.element.createElement("path",{d:"M14.784,40.888c-0.19,0-0.38-0.055-0.544-0.164L3.142,33.314l-2.535,1.044c-0.335,0.138-0.566,0.449-0.602,0.81   c-0.036,0.359,0.13,0.71,0.43,0.911l13.804,9.217c0.165,0.109,0.354,0.164,0.544,0.164c0.143,0,0.285-0.03,0.418-0.094   l10.332-4.881v-4.572l-10.332,4.881C15.069,40.858,14.926,40.888,14.784,40.888z"}),wp.element.createElement("path",{d:"M47.451,28.372l-2.919-1.434l-6.032,2.85v4.571l8.938-4.223c0.341-0.161,0.559-0.502,0.562-0.878   S47.789,28.537,47.451,28.372z"})))},category:"artgallery",attributes:{message:{type:"string",default:Object(c.__)("Contact artist for pricing.","artgallery")}},edit:Object(f.withDispatch)((function(e,t,n){n.select;return{setAvailability:function(t){e("core/editor").editPost(a()({},g.g,[+t]))}}}))(Object(f.withSelect)((function(e){var t=e("core").getEntityRecords("taxonomy",g.f),n=e("core/editor").getEditedPostAttribute(g.g);return{availability:+(Array.isArray(n)&&n.length?n[0]:null),availabilityTerms:t}}))((function(e){var t=e.attributes,n=e.availability,r=e.availabilityTerms,a=e.insertBlocksAfter,o=e.isSelected,m=e.setAttributes,f=e.setAvailability;if(!r||!r.length)return wp.element.createElement("p",{className:w.element("explanation")},Object(c.__)("Artwork availability status loading...","artgallery"));var g=r.find((function(e){return+e.id===+n}));if(!o&&!g)return wp.element.createElement("p",{className:w.element("explanation")},Object(c.__)("Click to configure whether the original for this artwork is available for purchase.","artgallery"));if(!o){var b=Object(c.sprintf)(Object(c.__)("Artwork is marked %s.","artgallery"),g.name);return y(g)?wp.element.createElement(i.Fragment,null,wp.element.createElement("p",{className:w.element("explanation")},b," ",Object(c.__)("This message will be displayed on the frontend:","artgallery")),wp.element.createElement(p.a,{block:h,attributes:l()({status:g.slug},t)})):wp.element.createElement("p",{className:w.element("explanation")},b," ",Object(c.__)("No message or indication of artwork availability will be displayed.","artgallery"))}return wp.element.createElement(i.Fragment,null,wp.element.createElement("h2",{className:w.element("title")},Object(c.__)("Manage Artwork Availability","artgallery")),wp.element.createElement("p",{className:w.element("message")},Object(c.__)("This block controls the messaging indicating whether or not the artwork is available for purchase.","artgallery")," ",Object(c.__)('(Defaults to "not for sale" on publish if no option is selected.)',"artgallery")),wp.element.createElement(s.RadioControl,{className:w.element("options"),label:Object(c.__)("Artwork Status","artgallery"),selected:"".concat(n),options:r.map((function(e){return{label:e.name,value:"".concat(e.id)}})),onChange:f}),y(g)?wp.element.createElement(i.Fragment,null,wp.element.createElement("label",{className:"".concat(w.element("help-text")," components-base-control")},Object(c.__)("Enter a sales message or link to display at the bottom of the artwork page.","artgallery")),wp.element.createElement(d.RichText,{tagName:"p",className:w.element("custom-message"),value:t.message,onChange:function(e){return m({message:e})},placeholder:Object(c.__)("Enter text...","custom-block"),keepPlaceholderOnFocus:!0})):null,wp.element.createElement("p",{className:w.element("message")},Object(c.__)("Insert a paragraph after this block to add links to reproductions or derivative products.","artgallery")),wp.element.createElement("button",{className:"components-button is-button is-default",onClick:function(){return a(Object(u.createBlock)("core/paragraph"))}},Object(c.__)("Add paragraph","artgallery")))}))),save:function(){return null}},_=[g.d]}]);