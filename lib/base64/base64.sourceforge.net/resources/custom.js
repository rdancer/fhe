//UDMv3.0.1
//**DO NOT EDIT THIS *****
if (!exclude) { //********
//************************



// *** for more information about the script ******************************
// *** see http://www.brothercake.com/dropdown/ ***************************



// *** POSITIONING AND STYLES *********************************************


var menuALIGN = "left";		// alignment
var absLEFT = 	12;		// absolute left or right position (if not center)
var absTOP = 	 0; 		// absolute top position

var staticMENU = false;		// static positioning mode (ie5/6 and ns4 only)

var stretchMENU = true;		// show empty cells
var showBORDERS = true;		// show empty cell borders

var baseHREF =	"resources/";	// base path 
var zORDER = 	1000;		// base z-order of nav structure (not ns4)

var mCOLOR =    "#BFDFEF";      // main nav cell color
var rCOLOR =    "#5F7F9F";      // main nav cell rollover color
var keepLIT =   true;           // keep rollover color when browsing menu
var bSIZE =     0;              // main nav border size
var bCOLOR =    "#637bbc"       // main nav border color
var aLINK =     "#1F3F5F";      // main nav link color
var aHOVER = 	"#ffffff";	// main nav link hover-color (dual purpose)
var aDEC = 	"none";		// main nav link decoration
var fFONT = 	"arial,sans serif";	// main nav font face		
var fSIZE = 	13;		// main nav font size (pixels)	
var fWEIGHT = 	"bold"		// main nav font weight
var tINDENT = 	7;		// main nav text indent (if text is left or right aligned)
var vPADDING = 	 0;		// main nav vertical cell padding
var vtOFFSET = 	0;		// main nav vertical text offset (+/- pixels from middle)

var vOFFSET = 	0;		// shift the submenus vertically
var hOFFSET = 	0;		// shift the submenus horizontally

var smCOLOR =   "#BFDFEF";      // submenu cell color
var srCOLOR =   "#5F7F9F";      // submenu cell rollover color
var sbSIZE =    1;              // submenu border size
var sbCOLOR =   "#BFDFEF"       // submenu border color
var saLINK =    "#000000";      // submenu link color
var saHOVER = 	"#ffffff";	// submenu link hover-color (dual purpose)
var saDEC = 	"none";		// submenu link decoration
var sfFONT =    "arial";        // submenu font face
var sfSIZE =    13;             // submenu font size (pixels)
var sfWEIGHT =  "bold"          // submenu font weight
var stINDENT =  5;              // submenu text indent (if text is left or right aligned)
var svPADDING = 2;              // submenu vertical cell padding
var svtOFFSET = 0;              // submenu vertical text offset (+/- pixels from middle)

var shSIZE =    7;              // submenu drop shadow size
var shCOLOR =   "#000000";      // submenu drop shadow color
var shOPACITY = 55;             // submenu drop shadow opacity (not ns4 or Opera 5)

var keepSubLIT=	true;		// keep submenu rollover color when browsing child menu	
var chvOFFSET = 0;		// shift the child menus vertically 			
var chhOFFSET = 0;		// shift the child menus horizontally 		

var closeTIMER = 330;		// menu closing delay time

var cellCLICK = true;		// links activate on TD click
var aCURSOR = "hand";		// cursor for active links (ie only)

var altDISPLAY = "title";		// where to display alt text


//** LINKS ***********************************************************





// add main link item ("url","Link name",width,"text-alignment","target")

addMainItem("index.html","Home",80,"left","","Main pages",0,0); 


	// define submenu properties (width,"align to edge","text-alignment")

	defineSubmenuProperties(240,"left","left");

	
	// add submenu link items ("url","Link name","target")
	addSubmenuItem("index.html","base64 Home Page","");
	addSubmenuItem("https://sourceforge.net/projects/base64/","Base64 Project Page","");
	addSubmenuItem("https://sourceforge.net","SourceForge Home","");

addMainItem("","Source",80,"left","","Source code files on this site",0,0); 

        defineSubmenuProperties(135,"left","left");

	addSubmenuItem("http://base64.sourceforge.net/b64.c","b64.c source file","_blank");
	addSubmenuItem("http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/base64/","CVS","_blank");
	addSubmenuItem("aboutweb.html","This web site","");

addMainItem("","Project Links",120,"left","","Other Project Links",0,0); 
        defineSubmenuProperties(135,"left","left");
	addSubmenuItem("https://sourceforge.net/forum/forum.php?forum_id=103462","Discussion","_blank");

addMainItem("","Resources",100,"left","","Useful resources for web developers",0,0); 

	defineSubmenuProperties(170,"left","left",0,0);

	addSubmenuItem("http://www.dynamicdrive.com/","Dynamic Drive","","");
	addSubmenuItem("http://javascript.internet.com/","JavaScript Source","","");
	addSubmenuItem("http://www.w3c.org/","W3C","","");
	addSubmenuItem("http://msdn.microsoft.com/","MSDN","","");
	addSubmenuItem("http://www.upsdell.com/BrowserNews/","Browser News","","");
	addSubmenuItem("http://www.webmonkey.com/","Web Monkey","","");
	addSubmenuItem("http://www.htmlcenter.com/","HTML Center","","");
	addSubmenuItem("http://www.dotcom.com/","WhoIS","","");
	addSubmenuItem("http://www.experts-exchange.com/","Experts Exchange","","");
	addSubmenuItem("http://dgl.microsoft.com/","Design Gallery Live","","");
	addSubmenuItem("http://www.worldwidemart.com/scripts/","Matt's Script Archive","","");
	addSubmenuItem("http://www.ntk.net/","NTK","");
	addSubmenuItem("http://www.slashdot.org/","Slashdot","","");

addMainItem("","About",100,"left","","About the code,site,author",0,0); 

	defineSubmenuProperties(200,"left","left",0 ,0);

	addSubmenuItem("http://www.faqs.org/rfcs/rfc1113.html", "Specification RFC1113", "_blank");
	addSubmenuItem("http://www.hushdata.com/btrower","Author","_blank");
	addSubmenuItem("aboutweb.html","This web site","");
	addSubmenuItem("","----------------------","");
	addSubmenuItem("http://www.brothercake.com/dropdown/index.html","Ultimate Dropdown Menu",""); 
	

		// define child menu properties (width,"align to edge","text-alignment",v offset,h offset)
		defineChildmenuProperties(250,"left","left",0,-12);

		// add child menu link items ("url","Link name","_target","alt text")
		addChildmenuItem("http://www.brothercake.com/dropdown/index.html","Script home","","");
		addChildmenuItem("http://www.brothercake.com/dropdown/install.html","Installing","","");
		addChildmenuItem("http://www.brothercake.com/dropdown/custom.html","Customising","","");
		addChildmenuItem("http://www.brothercake.com/dropdown/advanced.html","Advanced customising","","");
		addChildmenuItem("http://www.brothercake.com/dropdown/sniffer.html","Variables from the sniffer script","","");
		addChildmenuItem("http://www.brothercake.com/dropdown/browsers.html","Browser support","","");
		addChildmenuItem("http://www.brothercake.com/dropdown/faq.html","FAQ","","");
		addChildmenuItem("http://www.brothercake.com/dropdown/feedback.shtml","Feedback","","");
		addChildmenuItem("http://www.brothercake.com/dropdown/contributors.html","Contributors","","");
		addChildmenuItem("http://www.brothercake.com/dropdown/versions.html","Version cross-compatibility","","");
		addChildmenuItem("http://www.brothercake.com/dropdown/archives.html","Version archives","","");
		addChildmenuItem("http://www.brothercake.com/dropdown/terms.html","Terms of use","","");


//**DO NOT EDIT THIS *****
}//***********************
//************************

