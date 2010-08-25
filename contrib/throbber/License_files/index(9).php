/* <source lang="javascript"><nowiki> */
// SVG images: adds links to rendered PNG images in different resolutions
function SVGThumbs() {
	var file = document.getElementById("file"); // might fail if MediaWiki can't render the SVG
	if (file && wgIsArticle && wgTitle.match(/\.svg$/i)) {
		var thumbu = file.getElementsByTagName('IMG')[0].src;
		if(!thumbu) return;

		function svgAltSize( w, title) {
			var path = thumbu.replace(/\/\d+(px-[^\/]+$)/, "/" + w + "$1");
			var a = document.createElement("A");
			a.setAttribute("href", path);
			a.appendChild(document.createTextNode(title));
			return a;
		}

		var p = document.createElement("p");
		p.className = "SVGThumbs";
		p.appendChild(document.createTextNode("This image rendered as PNG in other sizes"+": "));
		var l = new Array( 200, 500, 1000, 2000 )
                for( var i = 0; i < l.length; i++ ) {
			p.appendChild(svgAltSize( l[i], l[i] + "px"));
			if( i < l.length-1 ) p.appendChild(document.createTextNode(", "));
                }
		p.appendChild(document.createTextNode("."));
		var info = getElementsByClassName( file.parentNode, 'div', 'fullMedia' )[0];
		if( info ) info.appendChild(p);
	}
};
addOnloadHook( SVGThumbs )
/* </nowiki></source> */