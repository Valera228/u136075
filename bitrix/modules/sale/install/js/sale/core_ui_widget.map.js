{"version":3,"file":"core_ui_widget.min.js","sources":["core_ui_widget.js"],"names":["BX","ui","widget","opts","merge","this","scope","useSpawn","messages","controls","bindEvents","removeTemplates","initializeByGlobalEvent","globalEventScope","vars","ctrls","tmpls","sys","stack","init","code","initialized","pushFuncStack","isUIWidget","prototype","preInit","ctx","so","sc","document","Error","match","type","isNotEmptyString","isElementNode","outerHTML","templates","querySelectorAll","k","length","id","data","search","replace","innerHTML","remove","isFunction","bindEvent","isDomNode","unbindAll","getControlClassName","getControl","notRequired","getAll","searchWholeDoc","sScope","checkFound","result","node","e","setOption","name","value","getOption","getSysCode","getHTMLByTemplate","templateId","replacements","html","hasOwnProperty","replaceWith","toString","indexOf","substr","util","htmlspecialchars","placeHolder","toLowerCase","createNodesByTemplate","onlyTags","template","isTableRow","isTableCell","keeper","createElement","childNodes","children","push","Array","slice","call","replaceTemplate","parentConstruct","owner","c","superclass","constructor","apply","handleInitStack","nf","resolveFuncStack","i","fireEvent","isString","window","addCustomEvent","proxy","fName","f","disableInFuncStack","DoNothing","fire","eventName","args","onCustomEvent","callback","setCSSState","statName","changeCSSState","dropCSSState","way","spawn","onSpawn","clone","getRandom","Math","floor","random"],"mappings":"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"}