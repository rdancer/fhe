// [[image:Erioll_world.svg|18px]] '''WikiMiniAtlas''' <br>
// Script to embed interactive maps into pages that have coordinate templates <br>
// also check my user page [[User:Dschwen]] for more tools<pre>
//
// Revision 12

var wikiminiatlas =
{
 config:
 {
  width  : 600,
  height : 400,
  timeout : 5000,
  zoom : -1,
  quicklink : false,
  quicklinkurl : 'http://maps.google.com/maps?ll={latdegdec},{londegdec}&spn={span},{span}&q={latdegdec},{londegdec}',
  enabled : true,
  onlytitle : false,
  iframeurl : 'http://toolserver.org/~dschwen/wma/iframe.html',
  imgbase   : 'http://toolserver.org/~dschwen/wma/tiles/',
  coordurls : [
                'http://stable.toolserver.org/geohack/geohack.php?',
                'http://stable.ts.wikimedia.org/geohack/geohack.php?',
                'http://toolserver.org/~magnus/geo/geohack.php?',
                'http://toolserver.org/~geohack/geohack.php?',
                'http://tools.wikimedia.de/~magnus/geo/geohack.php?',
                'http://www8.brinkster.com/erikbaas/wiki/maps.asp?',
                'http://www.nsesoftware.nl/wiki/maps.asp?' 
              ],
  buttonImage: 
'http://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Erioll_world.svg/18px-Erioll_world.svg.png'
 },

 strings:
 {
  buttonTooltip : {
   af:'Vertoon ligging op \'n interaktiwe kaart.',
   als:'Ort uf dr interaktivä Chartä zeigä',
   ar:'شاهد الموقع على الخريطة التفاعلية',
   'be-tarask':'паказаць месцазнаходжаньне на інтэрактыўнай мапе',
   'be-x-old':'паказаць месцазнаходжаньне на інтэрактыўнай мапе',
   bg:'покажи местоположението на интерактивната карта',
   bpy:'জীবন্ত মানচিত্রগর মা মাপাহান দেখাদিতই',
   br:'diskouez al lec\'hiadur war ur gartenn etrewezhiat',
   ca:'mostra la localització en un mapa interactiu',
   cs:'zobraz místo na interaktivní mapě',
   da:'vis beliggenhed på interaktivt kort',
   de:'Ort auf interaktiver Karte anzeigen',
   el:'εμφάνιση τοποθεσίας σε διαδραστικό χάρτη',
   en:'show location on an interactive map',
   eo:'Montru lokigon sur interaktiva karto',
   eu:'erakutsi kokalekua mapa interaktibo batean',
   es:'mostrar el lugar en un mapa interactivo',
   fr:'Montrer la localisation sur une carte interactive',
   fur:'mostre la localizazion suntune mape interative',
   fy:'it plak op in oanpasbere kaart oanjaan',
   gl:'Amosar o lugar nun mapa interactivo',
   he:'הראה מיקום במפה האינטראקטיבית',
   hr:'prikaži lokaciju na interaktivnom zemljovidu',
   hu:'Mutasd a helyet egy interaktív térképen!',
   hy:'ցուցադրել դիրքը ինտերակտիվ քարտեզի վրա',
   it:'mostra la località su una carta interattiva',
   is:'sýna staðsetningu á gagnvirku korti',
   id:'Tunjukkan letak di peta interaktif',
   ja:'インタラクティブ地図上に位置を表示',
   km:'បង្ហាញទីតាំងនៅលើផែនទីអន្តរកម្ម',
   ko:'인터랙티브 지도에 위치를 표시',
   lt:'Rodyti vietą interaktyviame žemėlapyje',
   mk:'прикажи положба на интерактивна карта',
   nl:'de locatie op een interactieve kaart tonen',
   no:'vis beliggenhet på interaktivt kart',
   pl:'Pokaż lokalizację na mapie interaktywnej',
   pt:'mostrar a localidade num mapa interactivo',
   ro:'arată locaţia pe o hartă interactivă',
   ru:'показать положение на интерактивной карте',
   sk:'zobraz miesto na interaktívnej mape',
   sl:'Prikaži lego na interaktivnem zemljevidu',
   sq:'trego vendndodhjen në hartë',
   fi:'näytä paikka interaktiivisella kartalla',
   sv:'visa platsen på en interaktiv karta',
   uk:'показати положення на інтерактивній карті',
   vi:'xem vị trí này trên bản đồ tương tác',
   vo:'Jonön topi su kaed itjäfidik',
   zh:'显示该地在地图上的位置',
   'zh-cn':'显示该地在地图上的位置',
   'zh-sg':'显示该地在地图上的位置',
   'zh-tw':'顯示該地在地圖上的位置',
   'zh-hk':'顯示該地在地圖上的位置'
  },
  close : {
   af:'Sluit',
   als:'Zuä machä',
   ar:'غلق',
   'be-tarask':'закрыць',
   'be-x-old':'закрыць',
   bg:'затвори',
   bpy:'জিপা',
   br:'serriñ',
   ca:'tanca',
   cs:'zavřít',
   da:'luk',
   de:'schließen',
   el:'έξοδος',
   en:'close',
   eo:'fermu', 
   eu:'itxi',
   es:'cerrar',
   fr:'Quitter',
   fur:'siere',
   fy:'ticht',
   gl:'pechar',
   he:'לסגור',
   hr:'zatvori',
   hu:'bezárás', 
   hy:'փակել',
   id:'tutup',
   is:'loka',
   it:'chiudi',
   ja:'閉じる',
   km:'បិទ',
   ko:'닫기',
   lt:'uždaryti',
   mk:'затвори',
   nl:'sluiten',
   no:'lukk',
   pl:'zamknij',
   pt:'fechar',
   ro:'închide',
   ru:'закрыть',
   sk:'zatvoriť',
   sl:'zapri',
   sq:'mbylle',
   fi:'sulje',
   sv:'stäng',
   uk:'закрити',
   vi:'đóng',
   vo:'färmükön',
   zh:'关闭',
   'zh-cn':'关闭',
   'zh-sg':'关闭',
   'zh-tw':'關閉',
   'zh-hk':'關閉'
  }
 },

 link : null,
 links : null,
 bodyc : null,

 language : '',
 site: '',
 iframe : { div: null, iframe: null, closebutton: null },
 mapbutton: null,
 marker : { lat:0, lon:0 },

 coordinates : null,
 coord_index: 0,
 coord_params: '',
 //coord_filter: null,
 coord_filter: /^([\d+-.]+)_([\d+-.]*)_?([\d+-.]*)_?([NS])_([\d+-.]+)_([\d+-.]*)_?([\d+-.]*)_?([EOW])/,

 quicklinkbox : null,
 quicklinkdest : null,

 region_index : 0,
 coordinate_region : '',

 WikiMiniAtlasHTML : '',

 hookUpMapbutton : function( mb )
 {
  var mapparam = mb.mapparam;
  var mapy     = wikiminiatlas.totalOffset( mb, 0 ) + 20;

  function doEvent()
  {
   wikiminiatlas.toggleIFrame( mapparam, mapy );
   return true;
  }
  mb.onclick = doEvent;
 },

 // vertikale position auf der Seite bestimmen
 totalOffset : function( obj, offset )
 {
  if( obj.offsetParent == null || 
      obj.offsetParent.id == 'content' )
   return offset + obj.offsetTop;
  else
   return wikiminiatlas.totalOffset(obj.offsetParent, offset + obj.offsetTop );
 },

 // move iframe around and toggle visibility
 toggleIFrame : function( mp, my )
 {
  with(wikiminiatlas)
  {
   var newurl = config.iframeurl + '?' + mp;

   if(iframe.div.style.visibility != "visible" ||
      ( ( iframe.iframe.src != newurl ) && ( my !== undefined ) ) )
   {
    if( iframe.iframe.src != newurl )
    {
     iframe.iframe.src = newurl;
    }
    iframe.div.style.top = my + 'px';
    iframe.div.style.visibility="visible";
    iframe.div.style.display="block";
   }
   else
   {
    iframe.div.style.visibility="hidden";
    iframe.div.style.display="none";
   }
  }
  return false;
 },

 // start the timer to fade in the quicklink tooltip
 qlStart : function()
 {
 },

 // abort the timer, hide the tooltip 
 qlStop : function()
 {
 },
 
 // show the tooltip menu
 qlShowMenu : function()
 {
 },
 
 // fill in the map-service templates 
 qlURL : function( lat, lon, zoom )
 {
  var url = wikiminiatlas.config.quicklinkurl;

  url = url.replace( /\{latdegdec\}/g, lat );
  url = url.replace( /\{londegdec\}/g, lon );

  var span = Math.pow( 2.0, zoom) / 150.0;
  url = url.replace( /\{span\}/g, span.toFixed(4) );

  return url;
 },
 
 // Check against coordinate urls
 isMaplink : function( url_orig )
 {
  if( typeof(url_orig) != 'string' ) return false;

  // needed for the russian WP
  var url, err;
  try { url = decodeURI( url_orig ) } catch( err ) { url = url_orig }

  with(wikiminiatlas)
  {
   // for( var key = 0; key < config.coordurls.length; key++ ) {
   for( var key in config.coordurls ) {
    if( url.substr(0,config.coordurls[key].length) == config.coordurls[key] )
     return true;
   }
  }
  
  return false;
 },

 // Insert the IFrame into the page.
 loader : function()
 {
  // apply settings
  if( typeof(wma_settings) == 'object' )
   for (var key in wma_settings)
   {
    if( typeof(wma_settings[key]) == typeof(wikiminiatlas.config[key]) )
     wikiminiatlas.config[key] = wma_settings[key];
   }

  if( wikiminiatlas.config.enabled == false ) return;

  with(wikiminiatlas)
  {
   site = window.location.host.substr(0,window.location.host.indexOf('.'));
   language = wgUserLanguage;

   var len; // cache array length for iterations

   // remove stupid icons from title coordinates
   var coord_title = document.getElementById('coordinates') || document.getElementById('coordinates-title');
   if( coord_title ) {
    var icons = coord_title.getElementsByTagName('a');
    len = icons.length;
    for( var key = 0; key < len; key++ ) {
     if( typeof(icons[key]) == 'object' &&
         icons[key] != null &&
         icons[key].className == 'image' ) 
      icons[key].parentNode.removeChild(icons[key]);
    }
   }


   if( config.onlytitle )
   {
    bodyc = document.getElementById('coordinates') || document.getElementById('coordinates-title');
    if( bodyc == null ) return;
   }
   else
   {
    // the french moved their title coordinates outside of bodyContent!
    if( site == 'fr' )
      bodyc = document.getElementById('content') || document;
    else
      bodyc = document.getElementById('bodyContent') || document;
   }


   var startTime = (new Date()).getTime();

   links = bodyc.getElementsByTagName('a');
   len = links.length;
   for( var key = 0; key < len; key++ )
   {
    link = links[key];

    // check for timeout (every 10 links only)
    if( key % 10 == 9 && (new Date()).getTime() > startTime+config.timeout ) break;
    
    if( link.className != 'external text'  || link.href.match(/_globe:(?!earth)/i) !== null ) continue;

    coordinates = link.href.replace( /−/g, '-' );
    coord_params = coordinates.match(/&params=([^&=<>|]{7,255})/);

    if(!coord_params) continue;

    coord_params = coord_params[1];
 
    if(coord_filter.test(coord_params)) {
     coord_filter.exec(coord_params);
     marker.lat=(1.0*RegExp.$1) + ((RegExp.$2||0)/60.0) + ((RegExp.$3||0)/3600.0);
     if(RegExp.$4=='S') marker.lat*=-1;
     marker.lon=(1.0*RegExp.$5) + ((RegExp.$6||0)/60.0) + ((RegExp.$7||0)/3600.0);
     if(RegExp.$8=='W') marker.lon*=-1;
    }

    // Find a sensible Zoom-level based on type
    var zoomlevel = 1;
    if( coord_params.indexOf('_type:landmark') >= 0 )
     zoomlevel = 8;
    else if( coord_params.indexOf('_type:city') >= 0 )
     zoomlevel = 4;

    // If given use dim or scale for a zoomlevel
    var ds_filter = /(dim|scale):([\d+-.]+)(km|)/;
    if( ds_filter.test(coord_params) )
    {
     ds_filter.exec(coord_params);
     // wma shows dim approx 4e7m at zoom 0 or 1.5e8 is the scale of zoomlevel 0
     zoomlevel = (RegExp.$1 == 'dim' ? 
      ( RegExp.$3 == 'km' ? Math.log( 4e4/RegExp.$2 ) : Math.log( 4e7/RegExp.$2 ) ) : 
      Math.log( 1.5e8/RegExp.$2 ) ) / Math.log(2);
     if( zoomlevel > 10 ) zoomlevel = 10;
    }

    if( config.zoom != -1 )
     var zoomlevel = config.zoom;

    // Test the unicode Symbol
    if( site == 'de' && link.parentNode.id != 'coordinates' )
    {
     mapbutton = document.createElement('SPAN');
     mapbutton.appendChild( document.createTextNode('♁') );
     mapbutton.style.color = 'blue';
    }
    else
    {
     mapbutton = document.createElement('img');
     mapbutton.src = config.buttonImage;
    }
    mapbutton.title = strings.buttonTooltip[language] || strings.buttonTooltip.en;
    mapbutton.alt = '';
    mapbutton.style.padding = '0px 3px 0px 0px';
    mapbutton.style.cursor = 'pointer';
    mapbutton.className = 'noprint';
    mapbutton.mapparam = 
    marker.lat + '_' + marker.lon + '_' + 
    config.width + '_' + config.height + '_' + 
    site + '_' + zoomlevel + '_' + language;

    // link.parentNode.insertBefore(mapbutton, link.nextSibling);
    link.parentNode.insertBefore(mapbutton,link);
    hookUpMapbutton( mapbutton );

    if ( config.quicklink ) {
      link.href = qlURL( marker.lat, marker.lon, zoomlevel );
      link.onmouseover = qlStart;
      link.onmouseout = qlStop;
    }

   } //for

   // prepare quicklink menu box
   if ( coordinates != null && config.quicklink ) {
    quicklinkbox = document.createElement('div');
    // more to come :-)
   }

   // prepare iframe to house the map 
   if ( coordinates != null ) {
    iframe.div = document.createElement('div');
    iframe.div.style.visibility = 'hidden';
    iframe.div.style.display = 'none';
    iframe.div.style.width = (config.width+2)+'px';
    iframe.div.style.height = (config.height+2)+'px';
    iframe.div.style.margin = '0px';
    iframe.div.style.padding = '0px';
    iframe.div.style.backgroundColor = 'white';
    iframe.div.style.position = 'absolute';
    iframe.div.style.right = '2em';
    iframe.div.style.top = '1em';
    iframe.div.style.border = '1px solid gray';
    iframe.div.style.zIndex = 13;

    iframe.closebutton = document.createElement('img');
    iframe.closebutton.title = strings.close[language] || strings.close.en;
    // was: config.imgbase + 'button_hide.png'
    iframe.closebutton.src = 'http://upload.wikimedia.org/wikipedia/commons/d/d4/Button_hide.png' 
    iframe.closebutton.style.zIndex = 15;
    iframe.closebutton.style.position = 'absolute';
    iframe.closebutton.style.right = '11px';
    iframe.closebutton.style.top = '9px';
    iframe.closebutton.style.width = '18px';
    iframe.closebutton.style.cursor = 'pointer';
    iframe.closebutton.mapparam = '';

    iframe.closebutton.onclick = toggleIFrame;

    iframe.iframe = document.createElement('iframe');
    iframe.iframe.scrolling = 'no';
    iframe.iframe.frameBorder = '0';
    iframe.iframe.style.zIndex = 14;
    iframe.iframe.style.position = 'absolute';
    iframe.iframe.style.right = '1px';
    iframe.iframe.style.top = '1px';
    iframe.iframe.style.width = (config.width)+'px';
    iframe.iframe.style.height = (config.height)+'px';
    iframe.iframe.style.margin = '0px';
    iframe.iframe.style.padding = '0px';

    iframe.div.appendChild(iframe.iframe);
    iframe.div.appendChild(iframe.closebutton);

    var content = document.getElementById('content') || document.getElementById('mw_content');
    if(content)
      content.insertBefore(iframe.div,content.childNodes[0]);
   }
  } //with
 }

}

//
// Hook up installation function
//
addOnloadHook(wikiminiatlas.loader);

//</pre>