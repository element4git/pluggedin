/* Load this script using conditional IE comments if you need to support IE 7 and IE 6. */

window.onload = function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'PluggedIn\'">' + entity + '</span>' + html;
	}
	var icons = {
			'ico-facebook' : '&#x66;',
			'ico-twitter' : '&#x74;',
			'ico-search' : '&#x73;',
			'ico-checkmark-circle' : '&#x76;',
			'ico-plus-alt' : '&#x2b;',
			'ico-close' : '&#x78;',
			'ico-rdio' : '&#x72;&#x64;&#x69;&#x6f;',
			'ico-soundcloud' : '&#x73;&#x63;',
			'ico-add-to-calendar' : '&#x63;',
			'ico-rga' : '&#x72;'
		},
		els = document.getElementsByTagName('*'),
		i, attr, html, c, el;
	for (i = 0; i < els.length; i += 1) {
		el = els[i];
		attr = el.getAttribute('data-icon');
		if (attr) {
			addIcon(el, attr);
		}
		c = el.className;
		c = c.match(/ico-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
};