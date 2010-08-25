/* <source lang="css"> */

/* The following are Vector bugfixes */

/* Temporary fix until [[bugzilla:19567]] is addressed */
#siteNotice div { margin: 0; }
#siteNotice div.expanded table.siteNoticeUser { margin-bottom: 1em; }

/* [[bugzilla:20276]] */
input#wpSummary {
	width:100%;
}
/* end bugfixes */

/* Don't display some stuff on the main page */
body.page-Main_Page #deleteconfirm,
body.page-Main_Page #t-cite,
body.page-Main_Page #footer-info-lastmod,
body.page-Main_Page #siteSub,
body.page-Main_Page #contentSub,
body.page-Main_Page h1.firstHeading {
 display: none !important;
}

body.page-Main_Page #mp-topbanner {
margin-top: 0 !important;
}

#coordinates {
 position: absolute;
 top: 0em;
 right: 0em;
 float: right;
 margin: 0.0em;
 padding: 0.0em;
 line-height: 1.5em;
 text-align: right;
 text-indent: 0;
 font-size: 85%;
 text-transform: none;
 white-space: nowrap;
}

/* For positioning icons at top-right, used in Templates
   "Spoken Article" and "Featured Article" */
div.topicon {
 position: absolute;
 top: -2em;
 margin-right: -10px;
 display: block !important;
}

/* FR topicon position */
div.flaggedrevs_short {
 position: absolute;
 top: -3em;
 right: 55px;
 z-index: 1;
 margin-left: 0;
 /* Because this is not yet a topicon, we emulate it's behavior, this ensure compatibility with edit lead section gadget */
 margin-right: -10px;
}

/* Menu over FR box */
div.vectorMenu div {
 z-index: 2;
}

/* Display "From Wikipedia, the free encyclopedia" */
#siteSub {
    display: inline;
    font-size: 92%;
    font-weight: normal;
}

/* {{tl|Link GA}} */
#mw-panel div.portal div.body ul li.GA {
  background: url("http://upload.wikimedia.org/wikipedia/en/4/42/Monobook-bullet-ga.png") no-repeat 0% 0%;
  margin-left: -10px;
  padding-left: 10px;
}

/* {{tl|Link FA}} */
#mw-panel div.portal div.body ul li.FA {
  background: url("http://upload.wikimedia.org/wikipedia/commons/d/d4/Monobook-bullet-star.png") no-repeat 0% 0%;
  margin-left: -10px;
  padding-left: 10px;
}

#siteNotice .notice-all {
  margin-bottom: 1em !important;
  margin-right: 2px !important;
}

/* Blue instead of yellow padlock for secure links. */
#bodyContent a.external[href ^="https://"],
.link-https {
  background: url("http://upload.wikimedia.org/wikipedia/en/0/00/Lock_icon_blue.gif") center right no-repeat;
}

/* Adjust font-size for inline HTML generated TeX formulae */
.texhtml {
  font-size: 125%;
  line-height: 1.5em;
}

/* </source> */