!function(){"use strict";var e=window.wp.blocks,t=window.wp.element,n=window.wp.i18n,o=window.wp.blockEditor,r=window.wp.components;(0,e.registerBlockType)("pronamic-events/event-end-date",{edit:function(e){let{attributes:l,setAttributes:a}=e,i=(0,n.__)("Event End Date","pronamic-events");return(0,t.createElement)(t.Fragment,null,(0,t.createElement)(o.InspectorControls,null,(0,t.createElement)(r.PanelBody,null,(0,t.createElement)(r.TextControl,{label:(0,n.__)("Format","pronamic-events"),value:l.format,onChange:e=>a({format:e})}))),(0,t.createElement)("div",(0,o.useBlockProps)(),"❴ ",i," ❵"))}})}();