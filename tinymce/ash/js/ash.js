tinyMCEPopup.requireLangPack();
var AshDialog = {
	init : function() {
		this.resizeInputs();
	},
	
	insert : function() {
		var lang = document.getElementById('ash_lang').value;
		var h = tinyMCEPopup.dom.encode(document.getElementById('ashSource').value);
		if (h != ''){
			if (lang == ''){
				alert('You must select a language for your code.');
				return false;
			}
			h = "<pre class=\"brush:" + lang + "\">" + h + "</pre>";
			tinyMCEPopup.editor.execCommand("mceInsertContent", false, h);
			tinyMCEPopup.close();
		} else {
			tinyMCEPopup.close();
		}
	},

	resizeInputs : function () {
		var vp = tinyMCEPopup.dom.getViewPort(window), el;
		el = document.getElementById('ashSource');

		if (el) {
			el.style.width = (vp.w - 20) + 'px';
			el.style.height = (vp.h - 85) + 'px';
		}
	}
	
};
tinyMCEPopup.onInit.add(AshDialog.init, AshDialog);