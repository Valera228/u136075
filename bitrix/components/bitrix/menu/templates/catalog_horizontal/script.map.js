{"version":3,"file":"script.min.js","sources":["script.js"],"names":["window","BX","CatalogMenu","items","idCnt","currentItem","overItem","outItem","timeoutOver","timeoutOut","getItem","item","type","isDomNode","id","isNotEmptyString","this","CatalogMenuItem","itemOver","menuItem","clearTimeout","setTimeout","itemOut","element","popup","findChild","className","isLastItem","lastChild","parentNode","prototype","hasClass","addClass","alignPopup","removeClass","style","cssText","ulContainer","offsetRightPopup","offsetLeft","offsetWidth","offsetRightMenu","right","namespace","Main","Menu","CatalogHorizontal","menuBlockId","catalogMenuFirstWidth","resizeMenu","setAlign","setPadding","bind","proxy","widthSum","wpa","firstLevelLi","findChildren","i","length","firstChild","clientWidth","removeAttribute","wp","coefWidth","numFirstLevelLi","percentWidth","width","padding","changeSectionPicure","descrSpan","nextSibling","curDescr","innerHTML","curImgWrapObj","curImgObj","clone","parentObj","findParent","sectionImgObj","appendChild","sectionDescrObj","previousSibling","href"],"mappings":"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"}