// <pre><nowiki>

// version info
window.wikEdProgramVersion = window.wikEdProgramVersion || '0.9.91j';
window.wikEdProgramDate    = window.wikEdProgramDate    || 'July 22, 2010';

/*

Program description and Greasemonkey metadata

wikEd is a full-featured JavaScript in-browser editor for Wikipedia and other MediaWiki edit pages.
The program works currently ONLY for Mozilla, Firefox, SeaMonkey, Safari, and Chrome browsers.
The code has to be saved as UTF-8 in your editor to preserve Unicode characters like ♥ (heart)

// ==UserScript==
// @name        wikEd
// @namespace   http://en.wikipedia.org/wiki/User:Cacycle/
// @description A full-featured in-browser editor for Wikipedia and other MediaWiki edit pages
// @include     *
// @exclude
//
// @homepage    http://en.wikipedia.org/wiki/User:Cacycle/wikEd
// @source      http://en.wikipedia.org/wiki/User:Cacycle/wikEd.js
// @author      Cacycle (http://en.wikipedia.org/wiki/User:Cacycle)
// @license     Released into the public domain
// ==/UserScript==

== Installation on a MediaWiki wiki (using monobook.js) ==

1. PLEASE DO NOT COPY THE WHOLE PROGRAM (in order to get the frequent updates and bug fixes and to save disk space)
2. See http://en.wikipedia.org/wiki/User:Cacycle/wikEd for more detailed instructions
3. Copy the following short block of code to [[User:YOURUSERNAME/monobook.js]]
4. Click SHIFT-Reload to update to the newest version

// ---- START wikEd INSTALLATION CODE ----

// install [[User:Cacycle/wikEd]] in-browser text editor
document.write('<script type="text/javascript" src="'
+ 'http://en.wikipedia.org/w/index.php?title=User:Cacycle/wikEd.js'
+ '&action=raw&ctype=text/javascript"></script>');

// ---- END wikEd INSTALLATION CODE ----

== General installation for all MediaWiki wikis (using Greasemonkey) ==

1. Install Greasemonkey for Firefox from:
			https://addons.mozilla.org/en-US/firefox/addon/748
2. Install wikEd by opening this address:
			http://en.wikipedia.org/w/index.php?action=raw&ctype=text/javascript&title=User:Cacycle/wikEd.user.js

*/


//
// WikEdInitGlobalsConfigs: initialize user configurable variables
//

window.WikEdInitGlobalConfigs = function() {

// user readable texts, copy changes to http://en.wikipedia.org/wiki/User:Cacycle/wikEd_international_en.js, also defined in wikEdDiff.js
	if (typeof(wikEdText) == 'undefined') { window.wikEdText = {}; }

//
// WikEdInitText: define built-in user interface texts
//

	window.WikEdInitText = function() {
		WikEdInitObject(wikEdText, {

// logo
			'wikEdLogo alt':               'wikEd',
			'wikEdLogo title':             'wikEd {wikEdProgramVersion} ({wikEdProgramDate}) Click to disable',
			'wikEdLogo error alt':         'wikEd error',
			'wikEdLogo error title':       'Loading error - wikEd {wikEdProgramVersion} ({wikEdProgramDate}) Click to disable',
			'wikEdLogo browser alt':       '(wikEd)',
			'wikEdLogo browser title':     'Browser not supported - wikEd {wikEdProgramVersion} ({wikEdProgramDate})',
			'wikEdLogo incompatible alt':  '(wikEd)',
			'wikEdLogo incompatible title': 'Incompatible script or gadget "{wikEdParameter}" - wikEd {wikEdProgramVersion} ({wikEdProgramDate})',
			'wikEdLogo disabled alt':      '(wikEd)',
			'wikEdLogo disabled title':    'Disabled - wikEd {wikEdProgramVersion} ({wikEdProgramDate}) Click to enable',

// top jumper
			'wikEdScrollToEdit4 alt':      'Scroll to edit',
			'wikEdScrollToEdit4 title':    'Scroll to edit field',

// button bar grip titles
			'wikEdGripFormat title':       'Formatting buttons (click to hide or show)',
			'wikEdGripTextify title':      'Textify and wikify buttons (click to hide or show)',
			'wikEdGripCustom1 title':      'Custom buttons (click to hide or show)',
			'wikEdGripFind title':         'Find buttons (click to hide or show)',
			'wikEdGripFix title':          'Fixing buttons (click to hide or show)',
			'wikEdGripCustom2 title':      'Custom buttons (click to hide or show)',
			'wikEdGripControl title':      'wikEd control buttons (click to hide or show)',

// formatting buttons, top row
			'wikEdUndo alt':               'Undo',
			'wikEdUndo title':             'Undo',
			'wikEdRedo alt':               'Redo',
			'wikEdRedo title':             'Redo',
			'wikEdBold alt':               'Bold',
			'wikEdBold title':             'Bold text',
			'wikEdItalic alt':             'Italic',
			'wikEdItalic title':           'Italic text',
			'wikEdUnderline alt':          'Underline',
			'wikEdUnderline title':        'Underline text',
			'wikEdStrikethrough alt':      'Strikethrough',
			'wikEdStrikethrough title':    'Strikethrough text',
			'wikEdNowiki alt':             'Nowiki',
			'wikEdNowiki title':           'Nowiki markup text',
			'wikEdSuperscript alt':        'Superscript',
			'wikEdSuperscript title':      'Superscript text',
			'wikEdSubscript alt':          'Subscript',
			'wikEdSubscript title':        'Subscript text',
			'wikEdRef alt':                'Ref',
			'wikEdRef title':              'In-text reference (shift-click: named tag)',
			'wikEdCase alt':               'Case',
			'wikEdCase title':             'Toggle between lowercase, uppercase first, and uppercase',
			'wikEdSort alt':               'Sort',
			'wikEdSort title':             'Sort lines alphabetically',
			'wikEdRedirect alt':           'Redirect',
			'wikEdRedirect title':         'Create redirect, deletes whole text',
			'wikEdUndoAll alt':            'Undo all',
			'wikEdUndoAll title':          'Undo all changes',
			'wikEdRedoAll alt':            'Redo all',
			'wikEdRedoAll title':          'Redo all changes',

// formatting buttons, bottom row
			'wikEdWikiLink alt':           'Link',
			'wikEdWikiLink title':         'Wiki link',
			'wikEdWebLink alt':            'Weblink',
			'wikEdWebLink title':          'External weblink',
			'wikEdHeading alt':            'Heading',
			'wikEdHeading title':          'Increase heading levels (shift-click: decrease)',
			'wikEdBulletList alt':         'Bullet list',
			'wikEdBulletList title':       'Increase bulleted list level (shift-click: decrease)',
			'wikEdNumberList alt':         'Number list',
			'wikEdNumberList title':       'Increase numbered list level (shift-click: decrease)',
			'wikEdIndentList alt':         'Indent list',
			'wikEdIndentList title':       'Increase indention (shift-click: decrease)',
			'wikEdDefinitionList alt':     'Def list',
			'wikEdDefinitionList title':   'Definition list',
			'wikEdImage alt':              'Image',
			'wikEdImage title':            'Image',
			'wikEdTable alt':              'Table',
			'wikEdTable title':            'Table',
			'wikEdReferences alt':         'References',
			'wikEdReferences title':       'References location (shift-click: References section)',

// textify buttons
			'wikEdWikify alt':             'Wikify',
			'wikEdWikify title':           'Convert pasted content to wiki code, update highlighting',
			'wikEdTextify alt':            'Textify',
			'wikEdTextify title':          'Convert pasted content to plain text, update highlighting (shift-click: forced highlighting)',

// find and replace buttons, top row
			'wikEdFindAll alt':            'Find all',
			'wikEdFindAll title':          'Find all matches',
			'wikEdFindPrev alt':           'Find prev',
			'wikEdFindPrev title':         'Find previous match',
			'wikEdFindSelect title':       'Select a previous search or jump to a heading',
			'wikEdFindNext alt':           'Find next',
			'wikEdFindNext title':         'Find next match (shift-click: get selection)',
			'wikEdJumpPrev alt':           'Selected prev',
			'wikEdJumpPrev title':         'Find the selected text backwards',
			'wikEdJumpNext alt':           'Selected next',
			'wikEdJumpNext title':         'Find the selected text forwards',

// find and replace buttons, bottom row
			'wikEdReplaceAll alt':         'Replace all',
			'wikEdReplaceAll title':       'Replace all matches in whole text or selection',
			'wikEdReplacePrev alt':        'Replace prev',
			'wikEdReplacePrev title':      'Replace previous match',
			'wikEdReplaceSelect title':    'Select a previous replacement',
			'wikEdReplaceNext alt':        'Replace next (shift-click: get selection)',
			'wikEdReplaceNext title':      'Replace next match',
			'wikEdCaseSensitive alt':      'Case sensitive',
			'wikEdCaseSensitive title':    'Search is case sensitive',
			'wikEdRegExp alt':             'RegExp',
			'wikEdRegExp title':           'Search field is a regular expression',
			'wikEdFindAhead alt':          'Find ahead',
			'wikEdFindAhead title':        'Find ahead as you type (case-insensitive non-regexp search)',

// fix buttons, top row
			'wikEdFixBasic alt':           'Fix basic',
			'wikEdFixBasic title':         'Fix blanks and empty lines, also done by other fixing functions',
			'wikEdFixHtml alt':            'Fix html',
			'wikEdFixHtml title':          'Fix html to wikicode',
			'wikEdFixCaps alt':            'Fix caps',
			'wikEdFixCaps title':          'Fix caps in headers and lists',
			'wikEdFixUnicode alt':         'Fix Unicode',
			'wikEdFixUnicode title':       'Fix Unicode character representations',
			'wikEdFixAll alt':             'Fix all',
			'wikEdFixAll title':           'Fix basic, html, capitalization, and Unicode',
			'wikEdFixRedirect alt':        'Fix redirects',
			'wikEdFixRedirect title':      'Fix redirects',

// fix buttons, bottom row
			'wikEdFixDashes alt':          'Fix dashes',
			'wikEdFixDashes title':        'Fix dashes',
			'wikEdFixPunct alt':           'Fix punctuation',
			'wikEdFixPunct title':         'Fix spaces before punctuation',
			'wikEdFixMath alt':            'Fix math',
			'wikEdFixMath title':          'Fix math',
			'wikEdFixChem alt':            'Fix chem',
			'wikEdFixChem title':          'Fix chemical formulas',
			'wikEdFixUnits alt':           'Fix units',
			'wikEdFixUnits title':         'Fix units',
			'wikEdFixRegExTypo alt':       'Fix typos',
			'wikEdFixRegExTypo title':     'Fix typos using the AutoWikiBrowser RegExTypoFixer rules',

// wikEd control buttons, top row
			'wikEdRefHide alt':            '[REF, TEMPL]',
			'wikEdRefHide title':          'Toggle [REF] and [TEMPL] hiding',
			'wikEdRefButtonTooltip':       'Click to display hidden reference',
			'wikEdTemplButtonTooltip':     'Click to display hidden template',
			'wikEdCharEntityButtonTooltip': 'Click to display hidden character entity',
			'wikEdRefButtonShowTooltip':   'Click to hide reference',
			'wikEdTemplButtonShowTooltip': 'Click to hide template',
			'wikEdCharEntityButtonShowTooltip': 'Click to hide character entity',
			'wikEdTextZoom alt':           'Text zoom',
			'wikEdTextZoom title':         'Text zoom cycling (shift-click: reverse)',
			'wikEdClearHistory alt':       'Clear history',
			'wikEdClearHistory title':     'Clear the find, replace, and summary history',
			'wikEdScrollToPreview alt':    'Scroll to preview',
			'wikEdScrollToPreview title':  'Scroll to preview field',
			'wikEdScrollToEdit alt':       'Scroll to edit',
			'wikEdScrollToEdit title':     'Scroll to edit field',

// wikEd control buttons, bottom row
			'wikEdUseWikEd alt':           'Use wikEd',
			'wikEdUseWikEd title':         'Toggle between classic text area and wikEd',
			'wikEdHighlightSyntax alt':    'Syntax',
			'wikEdHighlightSyntax title':  'Toggle automatic syntax highlighting',
			'wikEdSource alt':             'Source',
			'wikEdCloseToolbar title':     'Close the standard non-wikEd toolbar',
			'wikEdCloseToolbar alt':       'Close toolbar',
			'wikEdSource title':           'Show the source code for testing purposes',
			'wikEdUsing alt':              'Using',
			'wikEdUsing title':            'Automatically add \'\'…using wikEd\'\' to summaries',
			'wikEdDiff alt':               'wikEdDiff',
			'wikEdDiff title':             'Toggle automatic improved diff view',
			'wikEdFullScreen alt':         'Fullscreen',
			'wikEdFullScreen title':       'Toggle the fullscreen mode',
			'wikEdTableMode alt':          'Table mode',
			'wikEdTableMode title':        'Toggle table edit mode',

// summary buttons
			'wikEdClearSummary alt':       'Clear summary',
			'wikEdClearSummary title':     'Clear the summary field',
			'wikEdSummarySelect title':    'Select a previous summary',
			'wikEdPresetSummary': [
				'/*  */ ', 'copyedit', 'reply', 'article created', 'intro rewrite',
				'linkfix', 'fixing typos', 'removing linkspam', 'reverting test',
				'reverting vandalism', 'formatting source text', '{wikEdUsing}'
			],
			'wikEdSummaryUsing':           '…using [[en:User:Cacycle/wikEd|wikEd]]',

// button title acceskey
			'alt-shift':                   'alt-shift-',

// submit buttons
			'wikEdLocalPreviewImg alt':    'Preview below',
			'wikEdLocalPreview title':     'Show preview below',
			'wikEdLocalDiffImg alt':       'Changes below',
			'wikEdLocalDiff title':        'Show current changes below',
			'wikEdHelpPageLink':           ' | <a href="{wikEdHomeBaseUrl}wiki/User:Cacycle/wikEd_help" target="helpwindow">wikEd help</a>', // use full link without {wikEdHomeBaseUrl} if the page is not on the English Wikipedia

// preview and changes buttons, top
			'wikEdClose alt':              'Close',
			'wikEdClose title':            'Close preview box',
			'wikEdClose2 alt':             'Close',
			'wikEdClose2 title':           'Close preview box',
			'wikEdScrollToPreview2 alt':   'Scroll to preview',
			'wikEdScrollToPreview2 title': 'Scroll to preview field',
			'wikEdScrollToEdit2 alt':      'Scroll to edit',
			'wikEdScrollToEdit2 title':    'Scroll to edit field',

// preview and changes buttons, bottom
			'wikEdScrollToPreview3 alt':   'Scroll to preview',
			'wikEdScrollToPreview3 title': 'Scroll to preview field',
			'wikEdScrollToEdit3 alt':      'Scroll to edit',
			'wikEdScrollToEdit3 title':    'Scroll to edit field',

// preview field
			'wikEdPreviewLoading':         '...',

// formatting functions
			'image filename':              'filename',
			'image width':                 'width',
			'table caption':               'caption',
			'table heading':               'heading',
			'table cell':                  'cell',
			'redirect article link':       'article link',

// fixing functions
			'External links':              'External links',
			'See also':                    'See also',
			'References':                  'References',

// language specific wiki code
			'wikicode Image':              'Image',
			'wikicode File':               'File',
			'wikicode Category':           'Category',
			'wikicode Template':           'Template',
			'wikEdReferencesSection':      '\n== References ==\n\n<references />\n',
			'talk page':                   'talk',
			'history page':                'history',
			'talk namespace':              'Talk',
			'talk namespace suffix':       '_talk', //// in French it is a prefix (Discussion_Utilisateur)

// hiding buttons
			'hideRef':                     'REF',
			'hideTempl':                   'TEMPL',

// shortened button texts
			'shortenedPreview':            'Preview',
			'shortenedChanges':            'Changes',

// follow link popup
			'followLink':                  '(ctrl-click)',
			'followLinkMac':               '(cmd-click)',

// error message popups
			'wikEdTableModeError':         'The table wikicode contains errors',

// auto updating
			'wikEdGreasemonkeyAutoUpdate': 'wikEd Update:\n\nA new version of the GreaseMonkey script "wikEd" is available.\n\n\nIt will be downloaded from:\n\n{updateURL}',

// highlighting popups
			'wikEdHyphenDash':             'Standard hyphen',
			'wikEdFigureDash':             'Figure dash',
			'wikEdEnDash':                 'En dash',
			'wikEdEmDash':                 'Em dash',
			'wikEdBarDash':                'Horizontal bar',
			'wikEdMinusDash':              'Minus sign',
			'wikEdSoftHyphen':             'Soft hyphen',
			'wikEdTab':                    'Tab',
			'wikEdEnSpace':                'En space',
			'wikEdEmSpace':                'Em space',
			'wikEdThinSpace':              'Thin space',
			'wikEdIdeographicSpace':       'Ideographic space',

// highlighting
			'wikEdSignature3':             'Sign with username only',
			'wikEdSignature4':             'Sign with user name and date',
			'wikEdSignature5':             'Sign with date only',

// highlighting errors
			'wikEdErrorHtmlUnknown':       'Unsupported HTML tag',
			'wikEdErrorBoldItalic':        'Invalid bold / italic',
			'wikEdErrorWrongClose':        'Close tag does not match',
			'wikEdErrorNoOpen':            'Close tag has no match',
			'wikEdErrorNoHandler':         'No handler',
			'wikEdErrorNoClose':           'Open tag has no match',
			'wikEdErrorNewline':           'Open tag closed by new line',
			'wikEdErrorTemplHeading':      'Headings in templates are ignored',
			'wikEdErrorTemplParam':        'Template/parameter tags do not match',
			'wikEdErrorTemplParamAmbig':   'Template/parameter tags are ambiguous',
			'wikEdErrorCodeInLinkName':    'Wikicode in link name',
			'wikEdErrorCodeInTemplName':   'Wikicode in template name',
			'wikEdErrorCodeInParamName':   'Wikicode in template parameter name',

// highlighting image preview
			'wikEdFilePreview':            'Image preview',

// location search string functions
			'iconPage':                    'All icons and images used by wikEd. Save page as <i>web page, complete</i> to download all files into one folder.<br><br>'

		}, wikEdShowMissingTranslations);
	};

// define built-in user interface texts
	WikEdInitText();

// use local copies of images for testing (set to true in local copy of edit page), also defined in wikEdDiff.js
	if (typeof(wikEdUseLocalImages) == 'undefined') { window.wikEdUseLocalImages = false; }

// path to local images for testing, also defined in wikEdDiff.js
	if (typeof(wikEdImagePathLocal) == 'undefined') { window.wikEdImagePathLocal = 'file:///D:/wikEd/images/'; }

// path to images, also defined in wikEdDiff.js
	if (typeof(wikEdImagePath) == 'undefined') { window.wikEdImagePath = 'http://upload.wikimedia.org/wikipedia/commons/'; }

// image filenames, also defined in wikEdDiff.js
	if (typeof(wikEdImage) == 'undefined') { window.wikEdImage = {}; }

// WikedInitImages: define built-in image URLs
	window.WikedInitImages = function() {
		WikEdInitImage(wikEdImage, {
			'barDash':             '5/52/WikEd_bar_dash.png',
			'bold':                '5/59/WikEd_bold.png',
			'browser':             '0/07/WikEd_disabled.png',
			'bulletList':          '6/62/WikEd_bullet_list.png',
			'case':                'a/aa/WikEd_case.png',
			'caseSensitive':       '0/0d/WikEd_case_sensitive.png',
			'clearHistory':        'c/c8/WikEd_clear_history.png',
			'clearSummary':        '2/2c/WikEd_clear_summary.png',
			'close':               '9/97/WikEd_close.png',
			'closeToolbar':        '1/1d/WikEd_close_toolbar.png',
			'ctrl':                '1/10/WikEd_ctrl.png',
			'definitionList':      'f/f5/WikEd_definition_list.png',
			'diff':                'd/db/WikEd_diff.png',
			'disabled':            '0/07/WikEd_disabled.png',
			'dummy':               'c/c5/WikEd_dummy.png',
			'emDash':              '5/58/WikEd_em_dash.png',
			'emSpace':             '3/3a/WikEd_em_space.png',
			'enDash':              'f/fc/WikEd_en_dash.png',
			'enSpace':             '0/04/WikEd_en_space.png',
			'error':               '3/3e/WikEd_error.png',
			'figureDash':          '2/25/WikEd_figure_dash.png',
			'findAhead':           '3/34/WikEd_find_ahead.png',
			'findAll':             '7/75/WikEd_find_all.png',
			'findNext':            'a/ad/WikEd_find_next.png',
			'findPrev':            'f/f5/WikEd_find_prev.png',
			'fixAll':              '8/86/WikEd_fix_all.png',
			'fixBasic':            '3/30/WikEd_fix_basic.png',
			'fixCaps':             '0/00/WikEd_fix_caps.png',
			'fixUnicode':          'd/d4/WikEd_fix_unicode.png',
			'fixRedirect':         'f/f8/WikEd_fix_redirect.png',
			'fixChem':             'e/e7/WikEd_fix_chem.png',
			'fixDash':             'e/e5/WikEd_fix_dash.png',
			'fixHtml':             '0/05/WikEd_fix_html.png',
			'fixMath':             '3/3f/WikEd_fix_math.png',
			'fixPunct':            'd/db/WikEd_fix_punct.png',
			'fixRegExTypo':        '9/94/WikEd_fix_reg-ex-typo.png',
			'fixUnits':            '6/69/WikEd_fix_units.png',
			'textZoom':            '7/71/WikEd_font_size.png',
			'fullScreen':          'd/d3/WikEd_fullscreen.png',
			'getFind':             '9/96/WikEd_get_selection.png',
			'grip':                'a/ad/WikEd_grip.png',
			'heading':             '0/07/WikEd_heading.png',
			'highlightSyntax':     '6/67/WikEd_syntax.png',
			'ideographicSpace':    'c/c6/WikEd_ideographic_space.png',
			'image':               '3/37/WikEd_image.png',
			'incompatible':        '0/07/WikEd_disabled.png',
			'indentList':          '7/7a/WikEd_indent_list.png',
			'italic':              'd/d4/WikEd_italic.png',
			'jumpNext':            '5/54/WikEd_jump_next.png',
			'logo':                '6/67/WikEd_logo.png',
			'minusDash':           'b/ba/WikEd_minus_dash.png',
			'noFile':              '8/88/WikEd_no_file.png',
			'nowiki':              '5/5a/WikEd_nowiki.png',
			'numberList':          '3/3b/WikEd_number_list.png',
			'jumpPrev':            'c/c7/WikEd_jump_prev.png',
			'preview':             '3/31/WikEd_preview.png',
			'redirect':            'f/fa/WikEd_redirect.png',
			'redo':                'd/d7/WikEd_redo.png',
			'ref':                 'b/ba/WikEd_ref.png',
			'refHide':             '0/0b/WikEd_ref_hide.png',
			'references':          '6/66/WikEd_references.png',
			'redoAll':             '2/2d/WikEd_redo_all.png',
			'resizeGrip':          'e/e1/WikEd_resize_grip.png',
			'regExp':              '6/6a/WikEd_regexp.png',
			'replaceAll':          '2/2a/WikEd_replace_all.png',
			'replaceNext':         'b/b0/WikEd_replace_next.png',
			'replacePrev':         'a/a1/WikEd_replace_prev.png',
			'scrollToEdit':        '1/13/WikEd_align_top.png',
			'scrollToPreview':     '3/37/WikEd_align_preview.png',
			'scrollToEditDown':    'a/a8/WikEd_align_down.png',
			'scrollToPreviewDown': '5/58/WikEd_align_preview_down.png',
			'softHyphen':          'c/c7/WikEd_soft_hyphen.png',
			'sort':                '7/7c/WikEd_sort.png',
			'source':              '0/02/WikEd_source.png',
			'strikethrough':       '0/06/WikEd_strikethrough.png',
			'subscript':           '9/9e/WikEd_subscript.png',
			'superscript':         'b/bf/WikEd_superscript.png',
			'tab':                 'e/e7/WikEd_tab.png',
			'table':               'b/bd/WikEd_table.png',
			'tableMode':           'e/ee/WikEd_table_edit.png',
			'tableBG':             '8/8a/WikEd_unknown.png',
			'textify':             'c/cd/WikEd_textify.png',
			'thinSpace':           '5/56/WikEd_thin_space.png',
			'underline':           '2/21/WikEd_underline.png',
			'undo':                'e/e6/WikEd_undo.png',
			'undoAll':             '0/08/WikEd_undo_all.png',
			'unknown':             '8/8a/WikEd_unknown.png',
			'useWikEd':            '6/67/WikEd_logo.png',
			'using':               'e/e0/WikEd_using.png',
			'webLink':             '1/16/WikEd_weblink.png',
			'wikEdDiff':           'c/c6/WikEdDiff.png',
			'wikify':              '9/9f/WikEd_wikify.png',
			'wikiLink':            '2/21/WikEd_wikilink.png'
		});
	};

// edit-frame css rules
	if (typeof(wikEdFrameCSS) == 'undefined') { window.wikEdFrameCSS = {}; }

// WikedInitFrameCSS: define built-in edit frame css
	window.WikedInitFrameCSS = function() {
		WikEdInitObject(wikEdFrameCSS, {

// frame
			'.wikEdFrameHtml':      'height: 100%; width: 100%; padding: 0; margin: 0; background: transparent; background-image: url({wikEdImage:resizeGrip}); background-attachment: fixed; background-position: right bottom; background-repeat: no-repeat;',
			'.wikEdFrameBodyPlain': 'height: auto; min-height: 100%; width: auto; background: transparent; margin: 0; padding: 0; padding-left: 0.25em; overflow: auto; font-family: monospace;',

			'.wikEdFrameBodySyntax': 'height: auto; min-height: 100%; width: auto; background: transparent; margin: 0; padding: 0; padding-left: 0.25em; overflow: auto; font-family: monospace; text-shadow: white -1px -1px 0, white -1px 0 0, white -1px 1px 0, white 0 -1px 0, white 0 1px 0, white 1px -1px 0, white 1px 0 0, white 1px 1px 0;',

			'.wikEdFrameBodyNewbie': 'height: auto; min-height: 100%; width: auto; background: transparent; margin: 0; padding: 0; padding-left: 0.25em; overflow: auto; font-family: monospace;',

// reselection / scroll to selection
			'.wikEdScrollBefore':   'vertical-align: top;',
			'.wikEdScrollAfter':    'vertical-align: top;',
			'.wikEdScrollLineHeight': 'position: absolute;',

// syntax highlighting
			'.wikEdError':          'background-image: url({wikEdImage:unknown}); color: black; font-weight: normal; font-style: normal; text-decoration: none; text-shadow: white -1px -1px 0, white -1px 0 0, white -1px 1px 0, white 0 -1px 0, white 0 1px 0, white 1px -1px 0, white 1px 0 0, white 1px 1px 0;',
			'.wikEdHighlightError': 'color: black; background: #faa;',

			'.wikEdHtml':           'background: #e8e8e8; text-shadow: none;',
			'.wikEdHtmlTag':        'color: #777;',
			'.wikEdHtmlTagButtons': 'color: #777;',
			'.wikEdHtmlUnknown':    'background-image: url({wikEdImage:unknown}); color: black; font-weight: normal; font-style: normal; text-shadow: white -1px -1px 0, white -1px 0 0, white -1px 1px 0, white 0 -1px 0, white 0 1px 0, white 1px -1px 0, white 1px 0 0, white 1px 1px 0;',
			'.wikEdParsingNote':    'border: 1px outset #fcc; padding: 0 0.5em 0 0.5em; margin: 0 0.25em 0 0.25em;  color: black; background: #fcc; text-shadow: none; font-weight: normal; font-size: smaller; font-style: normal; text-decoration: none; font-family: sans-serif;',

			'.wikEdSubscript':      'position: relative; top: 0.3em;',
			'.wikEdSuperscript':    'position: relative; top: -0.3em;',
			'.wikEdBold':           'font-weight: bold;',
			'.wikEdItalic':         'font-style: italic;',

			'.wikEdComment':        'background: #fff0d0; text-shadow: none; color: black; font-weight: normal; font-style: normal; text-decoration: none;',
			'.wikEdDel':            'text-decoration: line-through;',
			'.wikEdIns':            'text-decoration: underline;',

			'.wikEdPre':            'background: #e8e8e8; text-shadow: none;',
			'.wikEdMath':           'background: #e8e8e8; text-shadow: none;',
			'.wikEdNowiki':         'background: #e8e8e8; text-shadow: none;',

// horizontal rule
			'.wikEdHr':             'background: #666; text-shadow: none; color: #ffffff;',

// wiki code
			'.wikEdWiki':           'color: #777;',
			'.wikEdRedir':          'color: #c00; font-weight: bold;',
			'.wikEdSignature':      'color: #f00; font-weight: bold;',
			'.wikEdMagic':          'color: #666; font-weight: bold; background: #e8e8e8; text-shadow: none;',
			'.wikEdParserFunct':    'color: #f00;',

// headings
			'.wikEdFrameBodySyntax .wikEdHeading': 'color: #000; font-weight: bold;',
			'.wikEdFrameBodySyntax .wikEdHeadingWP': 'color: #000; font-weight: bold; background: #e8e8e8; text-shadow: none;',
			'.wikEdFrameBodyNewbie .wikEdHeading':
					'color: #000; font-weight: bold; color: #000; background: #eee; padding: 0 0.25em; border: 1px solid #ddd; font-size: larger; line-height: 1.5; text-shadow: white -1px -1px 0; ',
			'.wikEdFrameBodyNewbie .wikEdHeadingWP':
					'color: #000; font-weight: bold; color: #000; background: #ddd; padding: 0 0.25em; border: 1px solid #ccc; font-size: larger; line-height: 1.5; text-shadow: #eee -1px -1px 0; ',

// tables
			'.wikEdTable':          'color: #000; background: #e8e8e8; text-shadow: none;',
			'.wikEdTableTag':       'color: #777;',

// list
			'.wikEdList':           'color: #000; background: #e8e8e8; text-shadow: none;',
			'.wikEdListTag':        'font-weight: bold; font-family: monospace; vertical-align: text-bottom;',

// space-pre
			'.wikEdSpace':          'color: #000; background: #e8e8e8; text-shadow: none;',
			'.wikEdSpaceTag':       'background: #e8e8e8; text-shadow: none;',

// links
			'.wikEdLinkTag':        'color: #777;',

// wiki links
			'.wikEdLink':           '',
			'.wikEdLinkCrossNs':    'background: #ddd; text-shadow: none;',
			'.wikEdLinkInter':      'color: #000; background: #ddd; text-shadow: none;',
			'.wikEdLinkNs':         'color: #000; background: #ddd; text-shadow: none;',
			'.wikEdLinkName':       'color: #00e; font-weight: bold;',
			'.wikEdLinkTarget':     'color: #00e;',
			'.wikEdLinkText':       'color: #00e; font-weight: bold;',
			'.wikEdPMID':           'color: #00e;',
			'.wikEdISBN':           'color: #00e;',
			'.wikEdLinkInter span': 'font-weight: normal;',
			'span.wikEdLinkText:hover': 'text-decoration: underline;',
			'span.wikEdLinkName:hover': 'text-decoration: underline;',
			'span.wikEdPMID:hover': 'text-decoration: underline;',
			'span.wikEdISBN:hover': 'text-decoration: underline;',

// external links
			'.wikEdURL':            '',
			'.wikEdURLName':        'color: #00e; font-weight: bold;',
			'.wikEdURLTarget':      'color: #00e;',
			'.wikEdURLText':        'color: #00e; font-weight: bold;',
			'span.wikEdURLName:hover': 'text-decoration: underline;',
			'span.wikEdURLText:hover': 'text-decoration: underline;',

// images
			'.wikEdFile':           'background: rgb(213, 255, 176); background: rgba(199, 255, 149, 0.75); text-shadow: none;',
			'.wikEdFrameBodyNewbie .wikEdFile':
					'background: rgb(213, 255, 176); padding: 0.25em; margin-right: 0.25em; display: inline-block; border: 1px solid #082; margin: 1px;',
			'.wikEdFileTag':        'color: #444;',
			'.wikEdFileName':       'color: #00e;',
			'.wikEdFileParam':      'color: #666;',
			'.wikEdFileCaption':    'color: #000;',
			'.wikEdFilePreview':    'border: 1px solid #c0ffa0; background: rgb(192, 192, 192) no-repeat 50% 50%; background: rgba(192, 192, 192, 0.75); text-shadow: none; position: absolute; right: 0; margin: 0.1em 0.25em; z-index: -1; border: none; padding: 1px; display: block;',
			'.wikEdFrameBodyNewbie .wikEdFilePreview':
					'position: static; float: right; clear: both; background: transparent; padding: 0; ',

// categories
			'.wikEdCat':            'background: #ccc; text-shadow: none;',
			'.wikEdCatName':        'color: #00e;',
			'.wikEdCat .wikEdLinkInter': 'color: #000; background: #aaa; text-shadow: none;',
			'.wikEdCat .wikEdLinkNs': 'color: #000; background: #ccc; text-shadow: none;',
			'.wikEdCat .wikEdLinkText': 'color: #000; font-weight: normal;',
			'.wikEdCat span.wikEdLinkText:hover': 'text-decoration: none;',

// refs
			'.wikEdFrameBodySyntax .wikEdRefContainer': 'display: none;',

			'.wikEdRefContainer':   'position: relative; top: 1em;',
			'.wikEdRefContainer button': 'padding: 0.1em; position: relative; top: -1em;',

			'.wikEdRefButton':      'border: 1px solid; border-color: #e8e8e8 #444 #444 #e8e8e8; background: #d8d4d0; text-shadow: none;',
			'.wikEdRefButtonShow':  'border: 1px solid; border-color: #000 #e8e8e8 #e8e8e8 #000; background: #c8c4c0; text-shadow: none;',
			'.wikEdRef, .wikEdRefShow': 'background: #e8e8e8; text-shadow: none; color: #666;',

			'.wikEdReferences':     'background: #eee; text-shadow: none;',
			'.wikEdReferencesTag':  'color: #444;',
			'.wikEdFrameBodyNewbie .wikEdReferences':
					'background: #eee; padding: 0.25em; display: inline-block; border: 1px solid black; vertical-align: middle;',
			'.wikEdRefList':        'background: #eee; text-shadow: none;',
			'.wikEdFrameBodyNewbie .wikEdRefList':
					'background: #e8e8e8; padding: 0.25em; display: inline-block; border: 1px solid black; vertical-align: middle;',
			'.wikEdRefName':        'color: #000;',

// templates
			'.wikEdFrameBodySyntax .wikEdTemplContainer':  'display: none;',
			'.wikEdTemplContainer': 'position: relative; top: 1em;',
			'.wikEdTemplContainer button': 'padding: 0.1em; position: relative; top: -1em;',
			'.wikEdTemplButton':     'border: 1px solid; border-color: #e8e8e8 #444 #444 #e8e8e8; background: #d8d4d0; text-shadow: none;',
			'.wikEdTemplButtonShow': 'border: 1px solid; border-color: #000 #e8e8e8 #e8e8e8 #000; background: #c8c4c0; text-shadow: none;',

			'.wikEdTempl, .wikEdTemplShow': 'background: #e8e8e8; text-shadow: none; color: #666;',
			'.wikEdTemplNs, .wikEdTemplNsShow': 'background: #ccc; text-shadow: none;',

			'.wikEdTemplTag':       'color: #777;',
			'.wikEdTemplName':      'color: #509;',
			'.wikEdTemplParam':     '',
			'.wikEdTemplMod':       'color: #f00; font-weight: bold;',

			'.wikEdParam':          'background: #e8e8e8; text-shadow: none;',
			'.wikEdParamName':      'color: #900;',
			'.wikEdParamDefault':   'color: #000;',

// character entities
			'.wikEdFrameBodySyntax .wikEdCharEntityContainer':  'display: none;',

			'.wikEdCharEntityContainer': 'position: relative; right: -0.25em;',

			'.wikEdCharEntityContainer button':
					'padding: 0; color: #000; font-weight: normal; font-family: monospace; position: relative; right: 0.25em; ',

			'.wikEdCharEntityButton':
					'border: 1px solid; border-color: #e8e8e8 #444 #444 #e8e8e8; background: #d8d4d0; border-color: rgba(255, 255, 255, 0.75) rgba(64, 64, 64, 0.5) rgba(64, 64, 64, 0.5) rgba(255, 255, 255, 0.75); background: rgba(192, 192, 192, 0.3); text-shadow: none;',

			'.wikEdCharEntityButtonShow':
					'border: 1px solid; border-color: #000 #e8e8e8 #e8e8e8 #000; background: #c8c4c0; border-color: rgba(64, 64, 64, 0.5) rgba(255, 255, 255, 0.75) rgba(255, 255, 255, 0.75) rgba(64, 64, 64, 0.5); background: rgba(192, 192, 192, 0.3); text-shadow: none;',

			'.wikEdCharEntity, .wikEdCharEntityShow':
					'color: #000; background: #e8e8e8; text-shadow: none;',

// links in references and templates
			'.wikEdFrameBodySyntax .wikEdRef .wikEdURLName,  .wikEdFrameBodySyntax .wikEdTempl .wikEdURLName,  .wikEdFrameBodySyntax .wikEdRef .wikEdURLTarget,  .wikEdFrameBodySyntax .wikEdTempl .wikEdURLTarget,  .wikEdFrameBodySyntax .wikEdRef .wikEdURLText,  .wikEdFrameBodySyntax .wikEdTempl .wikEdURLText':  'color: #66f; font-weight: normal;',
			'.wikEdFrameBodySyntax .wikEdRef .wikEdLinkName, .wikEdFrameBodySyntax .wikEdTempl .wikEdLinkName, .wikEdFrameBodySyntax .wikEdRef .wikEdLinkTarget, .wikEdFrameBodySyntax .wikEdTempl .wikEdLinkTarget, .wikEdFrameBodySyntax .wikEdRef .wikEdLinkText, .wikEdFrameBodySyntax .wikEdTempl .wikEdLinkText': 'color: #66f; font-weight: normal;',

// wikEdFrameBodyNewbie ref and template hiding
			'.wikEdFrameBodyNewbie .wikEdRefContainer + .wikEdRef, .wikEdFrameBodyNewbie .wikEdTemplContainer + .wikEdTempl, .wikEdFrameBodyNewbie .wikEdTemplContainer .wikEdTemplNs, .wikEdFrameBodyNewbie wikEdRefContainer + .wikEdRefShow, .wikEdFrameBodyNewbie .wikEdTemplContainer + .wikEdTemplShow, .wikEdFrameBodyNewbie .wikEdTemplContainer +  .wikEdTemplNsShow':
					'display: none; color: #000; background: #f8f8f8; font-weight: normal; border: 1px solid; border-color: #444 #ccc #ccc #444; padding: 1em 0.25em 1em 0.25em; position: relative;',

			'.wikEdFrameBodyNewbie .wikEdRefButton:before, .wikEdFrameBodyNewbie .wikEdTemplButton:before, .wikEdFrameBodyNewbie .wikEdRefButtonShow:before, .wikEdFrameBodyNewbie .wikEdTemplButtonShow:before':
					'line-height: 0.75em; font-size: 65%; color: #000; font-family: sans-serif;',

			'.wikEdRefButton:before, .wikEdTemplButton:before, .wikEdRefButtonShow:before, .wikEdTemplButtonShow:before':
					'line-height: 0.75em; font-size: 65%; color: #000; font-family: sans-serif;',

			'.wikEdCharEntityButton:before, .wikEdCharEntityButtonShow:before':
					'',

			'.wikEdFrameBodyNewbie .wikEdRefButton:before, .wikEdFrameBodyNewbie .wikEdRefButtonShow:before':
					'content: "{wikEdText:hideRef}"',

			'.wikEdFrameBodyNewbie .wikEdCharEntity, .wikEdFrameBodyNewbie .wikEdCharEntityShow':
					'display: none; color: #000; border: 1px solid; border-color: #444 #ccc #ccc #444; background: #f8f8f8; border-color: rgba(64, 64, 64, 0.5) rgba(255, 255, 255, 0.75) rgba(255, 255, 255, 0.75) rgba(64, 64, 64, 0.5); background: rgba(192, 192, 192, 0.3); font-weight: normal;position: relative;',

			'.wikEdFrameBodyNewbie .wikEdTemplButton:before, .wikEdFrameBodyNewbie .wikEdTemplButtonShow:before':
					'content: "{wikEdText:hideTempl}";',

// table edit
			'.wikEdTableEdit':      'border: solid black; border-width: 1px 1px 0 0; background: red; text-shadow: none; background-image: url({wikEdImage:tableBG}); border-collapse: separate; border-spacing: 0;',
			'.wikEdTableEdit td':   'border: solid black; border-width: 0 0 1px 1px; background: white; text-shadow: none;',
			'.wikEdTableEdit th':   'border: solid black; border-width: 0 0 1px 1px; background: lightgrey; text-shadow: none; font-weight: bold;',
			'.wikEdTableEdit tr':   'background: lightgrey; text-shadow: none; font-weight: bold;',
			'.wikEdTableEdit caption': 'background: lightgrey; text-shadow: none; font-weight: normal;',////

// insert wikicode here
			'.wikEdInsertHere':     'background: orange; text-shadow: none; font-style: italic;',

// colors
			'.wikEdColorsLight':    'color: black; text-shadow: none;',
			'.wikEdColorsDark':     'color: white; text-shadow: none;',

// dashes
			'.wikEdFigureDash':     'background-image: url({wikEdImage:figureDash}); background-position: top right; background-repeat: no-repeat;',
			'.wikEdEmDash':         'background-image: url({wikEdImage:emDash}); background-position: top left; background-repeat: no-repeat;',
			'.wikEdEnDash':         'background-image: url({wikEdImage:enDash}); background-position: top left; background-repeat: no-repeat;',
			'.wikEdBarDash':        'background-image: url({wikEdImage:barDash}); background-position: top left; background-repeat: no-repeat;',
			'.wikEdMinusDash':      'background-image: url({wikEdImage:minusDash}); background-position: top left; background-repeat: no-repeat;',
			'.wikEdSoftHyphen':     'background-image: url({wikEdImage:softHyphen}); background-position: top left; background-repeat: no-repeat;',
			'.wikEdSoftHyphen:before': 'content: \'\xa0\'',
			'.wikEdHyphenDash':     '',

// dashes, invisibles, control chars, and strange spaces
			'.wikEdTab':            'white-space: pre; background-image: url({wikEdImage:tab}); background-position: bottom right; background-repeat: no-repeat;',
			'.wikEdTabPlain':       'white-space: pre;',
			'.wikEdCtrl':           'white-space: pre; background-image: url({wikEdImage:ctrl}); background-position: center center; background-repeat: no-repeat; margin: 0 1px;',
			'.wikEdCtrl:before':    'content: \'\xa0\'',

			'.wikEdEmSpace':        'background-image: url({wikEdImage:emSpace}); background-position: bottom left; background-repeat: no-repeat; margin: 0 1px; padding: 0 3px;',
			'.wikEdEnSpace':        'background-image: url({wikEdImage:enSpace}); background-position: bottom left; background-repeat: no-repeat; margin: 0 1px; padding: 0 3px;',
			'.wikEdThinSpace':      'background-image: url({wikEdImage:thinSpace}); background-position: bottom left; background-repeat: no-repeat; margin: 0 1px; padding: 0 3px;',
			'.wikEdIdeographicSpace': 'background-image: url({wikEdImage:ideographicSpace}); background-position: bottom left; background-repeat: no-repeat; margin: 0 1px; padding: 0 3px;'
		});
	};

// main window css rules
	if (typeof(wikEdMainCSS) == 'undefined') { window.wikEdMainCSS = {}; }

// WikedInitMainCSS: define built-in main window css
	window.WikedInitMainCSS = function() {
		WikEdInitObject(wikEdMainCSS, {

// logo
			'.wikEdLogoList':              'list-style-type: none;',
			'.wikEdLogo':                  'margin-left: 0.5em;',
			'.wikEdLogoFallBack':          'margin: 0.25em 0 0.25em 0.5em; float: right;'
		});
	};

// main window css rules for edit pages only
	if (typeof(wikEdMainEditCSS) == 'undefined') { window.wikEdMainEditCSS = {}; }

// WikedInitMainEditCSS: define built-in main window css for edit pages only
	window.WikedInitMainEditCSS = function() {
		WikEdInitObject(wikEdMainEditCSS, {

// combo input box
			'.wikEdCombo':                 'font-size: smaller; padding-left: 0.1em; padding-right: 0.1em; margin: 0 0.1em 0 0.1em; height: 1.6em; vertical-align: bottom;',

// wikEd button areas

// button bar margins
			'.wikEdButtonBarFormat':       'margin: 0 8px 3px 1px; float: left;',
			'.wikEdButtonBarTextify':      'margin: 0 8px 3px 1px; float: left;',
			'.wikEdButtonBarCustom1':      'margin: 0 8px 3px 1px; float: left;',
			'.wikEdButtonBarFind':         'margin: 0 8px 3px 1px; float: left;',
			'.wikEdButtonBarFix':          'margin: 0 8px 3px 1px; float: left;',
			'.wikEdButtonBarCustom2':      'margin: 0 8px 3px 1px; float: left;',
			'.wikEdButtonBarControl':      'margin: 0 1px 3px 0; float: right;',
			'.wikEdButtonBarPreview':      'margin: 0 0 0.15em 0.6em; float: right;',
			'.wikEdButtonBarPreviewFull':  'margin: -0.2em 0 0 0.6em; float: right;',
			'.wikEdButtonBarPreview2':     'margin: 0.2em 0 0.4em 0; float: right;',
			'.wikEdButtonBarJump':         'margin: 0 0 0 0.6em; float: right;',

// button bar inner wrapper: border (hidden: invisible)
			'.wikEdButtonBarInnerWrapperVisible':   'border: 1px solid; border-color: #e0e0e0 #808080 #808080 #e0e0e0;',
			'.wikEdButtonBarInnerWrapperHidden':    '',

// button bar grip wrapper: invisible (hidden: border)
			'.wikEdButtonBarGripWrapperVisible':    'float: left;',
			'.wikEdButtonBarGripWrapperHidden':     'float: left; border: 1px solid; border-color: #e0e0e0 #808080 #808080 #e0e0e0;',

// button bar buttons wrapper: invisible (hidden: border)
			'.wikEdButtonBarButtonsWrapperVisible': 'float: left; background: #d4d0cc; ',
			'.wikEdButtonBarButtonsWrapperHidden':  'float: left; background: #d4d0cc; border: 1px solid; border-color: #e0e0e0 #808080 #808080 #e0e0e0; z-index: 4;',

// button bar grip
			'.wikEdButtonBarGrip':         'background: #d4d0cc; padding: 0; background-image: url({wikEdImage:grip}); background-repeat: repeat-y; cursor: pointer;',

// button bar buttons
			'.wikEdButtonsFormat':         'background: #d4d0cc; padding: 2px 2px 0 0px;',
			'.wikEdButtonsTextify':        'background: #d4d0cc; padding: 2px 2px 0 0px;',
			'.wikEdButtonsCustom1':        'background: #d4d0cc; padding: 2px 2px 0 0px;',
			'.wikEdButtonsFind':           'background: #d4d0cc; padding: 0px 2px 0 0px;',
			'.wikEdButtonsFix':            'background: #d4d0cc; padding: 2px 2px 0 0px;',
			'.wikEdButtonsCustom2':        'background: #d4d0cc; padding: 2px 2px 0 0px;',
			'.wikEdButtonsControl':        'background: #d4d0cc; padding: 2px 2px 0 1px;',

			'.wikEdButtonsPreview':        'background: #d4d0cc; padding: 2px; border: 1px solid; border-color: #e0e0e0 #808080 #808080 #e0e0e0;',
			'.wikEdButtonsPreviewFull':    'background: #d4d0cc; padding: 2px; border: 1px solid; border-color: #e0e0e0 #808080 #808080 #e0e0e0;',
			'.wikEdButtonsPreview2':       'background: #d4d0cc; padding: 2px; border: 1px solid; border-color: #e0e0e0 #808080 #808080 #e0e0e0;',
			'.wikEdButtonsJump':           'background: #d4d0cc; padding: 2px; border: 1px solid; border-color: #e0e0e0 #808080 #808080 #e0e0e0;',

// wikEd buttons (!important for devmo skin)
			'.wikEdButton':                'vertical-align: text-top; font-size: small; text-decoration: underline; margin: 1px 2px; padding: 0; background: #d4d0cc; border: 1px #d4d0cc solid !important; cursor: pointer;',
			'.wikEdButton:hover':          'background: #e4e0dd; border: 1px outset !important; cursor: pointer;',
			'.wikEdButton:active':         'background: #e4e0dc; border: 1px inset !important;  cursor: pointer;',
			'.wikEdButtonSolo':            'vertical-align: text-top; font-size: small; text-decoration: underline; margin: 1px 2px; padding: 0; background: #d4d0cc; border: 1px #d4d0cc solid !important; cursor: pointer;',
			'.wikEdButtonSolo:hover':      'background: #e4e0dd; border: 1px outset !important; cursor: pointer;',
			'.wikEdButtonChecked':         'vertical-align: text-top; font-size: small; text-decoration: none; margin: 1px 2px; padding: 0; background: #ccc8c3; border: 1px solid !important; border-color: black white white black !important; cursor: pointer;',
			'.wikEdButtonUnchecked':       'vertical-align: text-top; font-size: small; text-decoration: none; margin: 1px 2px; padding: 0; background: #ddd8d3; border: 1px solid !important; border-color: white black black white !important; cursor: pointer;',
			'.wikEdButtonPressed':         'vertical-align: text-top; font-size: small; text-decoration: none; margin: 1px 2px; padding: 0; background: #ccc8c3; border: 1px solid !important; border-color: black white white black !important; cursor: wait;',
			'.wikEdButtonInactive':        'vertical-align: text-top; font-size: small; text-decoration: underline; margin: 1px 2px; padding: 0; background: #c0c0c0; border: 1px #b0b0b0 solid !important; cursor: not-allowed',
			'.wikEdLocalPreview':          'vertical-align: top; margin: 0 0.33em 0 0.15em; padding: 0;',
			'.wikEdLocalDiff':             'vertical-align: top; margin: 0 0.33em 0 0.15em; padding: 0;',
			'input#wpDiff, input#wpPreview': 'margin-right: 0;', // monobook fix
			'.wikEdButtonDummy':           'vertical-align: text-top; margin: 1px 2px; padding: 1px; background: #d4d0cc;',

// preview box
			'.wikEdPreviewBoxOuter':       'clear: both; margin: 0; border-width: 1px; border-style: solid; border-color: #808080 #d0d0d0 #d0d0d0 #808080;',
			'.wikEdPreviewBox':            'background: #faf8f6; padding: 5px; border-width: 1px; border-style: solid; border-color: #404040 #ffffff #ffffff #404040;',
			'.wikEdPreviewRefs':           'margin-top: 1.5em; padding-top: 1em;border-top: 1px solid #a0a0a0;',

// find field
			'.wikEdFindComboInput':        'position: relative; padding: 0; margin: 0 0.2em; white-space: nowrap; top: 0; vertical-align: bottom;',
			'#wikEdFindText':              'vertical-align: 0%; font-family: monospace; padding: 0; margin: 0; position: absolute; z-index: 2; -moz-box-sizing: content-box; left: 0; top: 1px; height: 14px; width: 170px;',
			'#wikEdFindSelect':            'vertical-align: 0%; font-family: monospace; padding: 0; margin: 0; position: relative; z-index: 1; -moz-box-sizing: content-box; left: 0; top: 0px; height: 18px; border: none;',

// replace field
			'.wikEdReplaceComboInput':     'position: relative; padding: 0; margin: 0 0.2em; white-space: nowrap; top: 0; vertical-align: bottom;',
			'#wikEdReplaceText':           'vertical-align: 0%; font-family: monospace; padding: 0; margin: 0; position: absolute; z-index: 2; -moz-box-sizing: content-box; left: 0; top: 1px; height: 14px; width: 170px;',
			'#wikEdReplaceSelect':         'vertical-align: 0%; font-family: monospace; padding: 0; margin: 0; position: relative; z-index: 1; -moz-box-sizing: content-box; left: 0; top: 0px; height: 18px; border: none; ',

// summary field
			'.wikEdSummaryComboInput':     'position: relative; padding: 0; margin: 0 0 0 0.1em; white-space: nowrap; top: 0; vertical-align: text-bottom;',
			'.wikEdSummaryText':           'vertical-align: 0%; padding: 0; margin: 0; position: absolute; z-index: 2; -moz-box-sizing: content-box; left: 0; top: 0px; height: 18px; width: auto;',
			'.wikEdSummarySelect':         'vertical-align: 0%; padding: 0; margin: 0; position: relative; z-index: 1; -moz-box-sizing: content-box; left: 0; top: 1px; height: 21px; border: none;',

// space around submit buttons
			'.editButtons':                '',

// frame (frame container border will be removed if textarea has none; frame must not have a border)
			'.wikEdFrameOuter':            'float: left; width: auto; border: 1px solid; border-color: #808080 #d0d0d0 #d0d0d0 #808080;',
			'.wikEdFrameInner':            'float: left; width: auto; background: white; border: 1px solid; border-color: #404040 #ffffff #ffffff #404040;',
			'.wikEdFrame':                 'border: 0;',

// summary
			'.wikEdSummaryWrapper':        'margin: 0 0 0.4em 0; width: 100%',
			'.wikEdSummaryWrapperTop':     'margin: 0.1em 0 0.4em 0; width: 100%',
			'#wpSummaryLabel':             'margin: 0 0.2em 0 0;',
			'.editOptions':                'position: relative; top: 0.1em;',
			'.wikEdClearSummaryForm':      'display: inline;',
			'.wikEdClearSummary':          'vertical-align: middle; margin: 0 0.1em 0 0.5em; padding: 0 0 0.2em 0;',

// input wrapper
			'.wikEdInputWrapper':          'z-index: 100; clear: both; margin-top: 0.5em;',
			'.wikEdInputWrapperFull':      'position: fixed; top: 0; left: 0; right: 0; padding: 4px; background: white; z-index: 100;',

// other wrappers
			'.wikEdEditorWrapper':         '',
			'.wikEdToolbarWrapper':        'margin: 0 0 0.25em 0;',
			'.wikEdCaptchaWrapper':        '',
			'.wikEdDebugWrapper':          'clear: both; margin: 0 0 0.25em 0;',
			'.wikEdEditWrapper':           'clear: both;',
			'.wikEdTextareaWrapper':       '',
			'.wikEdFrameWrapper':          '',
			'.wikEdConsoleWrapper':        'clear: both; padding-top: 0.25em;',
			'.wikEdButtonsWrapper':        '',
			'.wikEdSummaryInputWrapper':   'display: inline; white-space: nowrap;',
			'.wikEdSummaryOptions':        'display: inline;',
			'.wikEdSubmitWrapper':         ';',
			'.wikEdSubmitButtonsWrapper':  '',
			'.wikEdLocalPrevWrapper':      'margin: 0.5em 0 0 0;',
			'.wikEdInsertWrapper':         '',

// various
			'.wikEdEditOptions':           'display: inline; vertical-align: baseline; margin-right: 0.75em; white-space: nowrap;',
			'.wikEdEditHelp':              'vertical-align: baseline; margin-right: 0.5em; white-space: nowrap;',
			'#editpage-specialchars':      'clear: both;',

// wDiff
			'.wDiffParagraph:before':      'content: "¶";'

		});
	};

// buttons (id, class, popup title, image src, width, height, alt text, click code)
	if (typeof(wikEdButton) == 'undefined') { window.wikEdButton = {}; }

// WikedInitButton: define built-in buttons (id, class, popup title, image src, width, height, alt text, click handler code were obj is the button element)
	window.WikedInitButton = function() {
		WikEdInitObject(wikEdButton, {

// workaround for mozilla 3.0 bug 441087: objId = obj.id; eventShiftKey = event.shiftKey;

// format top
			 1: ['wikEdUndo',             'wikEdButtonInactive',  wikEdText['wikEdUndo title'],             wikEdImage['undo'],                '16', '16', wikEdText['wikEdUndo alt'],             'WikEdEditButton(obj, objId);' ],
			 2: ['wikEdRedo',             'wikEdButtonInactive',  wikEdText['wikEdRedo title'],             wikEdImage['redo'],                '16', '16', wikEdText['wikEdRedo alt'],             'WikEdEditButton(obj, objId);' ],
			 3: ['wikEdBold',             'wikEdButton',          wikEdText['wikEdBold title'],             wikEdImage['bold'],                '16', '16', wikEdText['wikEdBold alt'],             'WikEdEditButton(obj, objId);' ],
			 4: ['wikEdItalic',           'wikEdButton',          wikEdText['wikEdItalic title'],           wikEdImage['italic'],              '16', '16', wikEdText['wikEdItalic alt'],           'WikEdEditButton(obj, objId);' ],
			 5: ['wikEdUnderline',        'wikEdButton',          wikEdText['wikEdUnderline title'],        wikEdImage['underline'],           '16', '16', wikEdText['wikEdUnderline alt'],        'WikEdEditButton(obj, objId);' ],
			 6: ['wikEdStrikethrough',    'wikEdButton',          wikEdText['wikEdStrikethrough title'],    wikEdImage['strikethrough'],       '16', '16', wikEdText['wikEdStrikethrough alt'],    'WikEdEditButton(obj, objId);' ],
			 7: ['wikEdNowiki',           'wikEdButton',          wikEdText['wikEdNowiki title'],           wikEdImage['nowiki'],              '16', '16', wikEdText['wikEdNowiki alt'],           'WikEdEditButton(obj, objId);' ],
			 8: ['wikEdSuperscript',      'wikEdButton',          wikEdText['wikEdSuperscript title'],      wikEdImage['superscript'],         '16', '16', wikEdText['wikEdSuperscript alt'],      'WikEdEditButton(obj, objId);' ],
			 9: ['wikEdSubscript',        'wikEdButton',          wikEdText['wikEdSubscript title'],        wikEdImage['subscript'],           '16', '16', wikEdText['wikEdSubscript alt'],        'WikEdEditButton(obj, objId);' ],
			10: ['wikEdRef',              'wikEdButton',          wikEdText['wikEdRef title'],              wikEdImage['ref'],                 '16', '16', wikEdText['wikEdRef alt'],              'if (!eventShiftKey) { WikEdEditButton(obj, \'wikEdRef\'); } else { WikEdEditButton(obj, \'wikEdRefNamed\'); }' ],
			12: ['wikEdCase',             'wikEdButton',          wikEdText['wikEdCase title'],             wikEdImage['case'],                '16', '16', wikEdText['wikEdCase alt'],             'WikEdEditButton(obj, objId);' ],
			80: ['wikEdSort',             'wikEdButton',          wikEdText['wikEdSort title'],             wikEdImage['sort'],                '16', '16', wikEdText['wikEdSort alt'],             'WikEdEditButton(obj, objId);' ],
			25: ['wikEdRedirect',         'wikEdButton',          wikEdText['wikEdRedirect title'],         wikEdImage['redirect'],            '16', '16', wikEdText['wikEdRedirect alt'],         'WikEdEditButton(obj, objId);' ],
			13: ['wikEdUndoAll',          'wikEdButton',          wikEdText['wikEdUndoAll title'],          wikEdImage['undoAll'],             '16', '16', wikEdText['wikEdUndoAll alt'],          'WikEdEditButton(obj, objId);' ],
			14: ['wikEdRedoAll',          'wikEdButtonInactive',  wikEdText['wikEdRedoAll title'],          wikEdImage['redoAll'],             '16', '16', wikEdText['wikEdRedoAll alt'],          'WikEdEditButton(obj, objId);' ],

// format bottom
			15: ['wikEdWikiLink',         'wikEdButton',          wikEdText['wikEdWikiLink title'],         wikEdImage['wikiLink'],            '16', '16', wikEdText['wikEdWikiLink alt'],         'WikEdEditButton(obj, objId);' ],
			16: ['wikEdWebLink',          'wikEdButton',          wikEdText['wikEdWebLink title'],          wikEdImage['webLink'],             '16', '16', wikEdText['wikEdWebLink alt'],          'WikEdEditButton(obj, objId);' ],
			17: ['wikEdHeading',          'wikEdButton',          wikEdText['wikEdHeading title'],          wikEdImage['heading'],             '16', '16', wikEdText['wikEdHeading alt'],          'if (!eventShiftKey) { WikEdEditButton(obj, \'wikEdIncreaseHeading\'); } else { WikEdEditButton(obj, \'wikEdDecreaseHeading\'); }' ],
			19: ['wikEdBulletList',       'wikEdButton',          wikEdText['wikEdBulletList title'],       wikEdImage['bulletList'],          '16', '16', wikEdText['wikEdBulletList alt'],       'if (!eventShiftKey) { WikEdEditButton(obj, \'wikEdIncreaseBulletList\'); } else { WikEdEditButton(obj, \'wikEdDecreaseBulletList\'); }' ],
			20: ['wikEdNumberList',       'wikEdButton',          wikEdText['wikEdNumberList title'],       wikEdImage['numberList'],          '16', '16', wikEdText['wikEdNumberList alt'],       'if (!eventShiftKey) { WikEdEditButton(obj, \'wikEdIncreaseNumberList\'); } else { WikEdEditButton(obj, \'wikEdDecreaseNumberList\'); }' ],
			21: ['wikEdIndentList',       'wikEdButton',          wikEdText['wikEdIndentList title'],       wikEdImage['indentList'],          '16', '16', wikEdText['wikEdIndentList alt'],       'if (!eventShiftKey) { WikEdEditButton(obj, \'wikEdIncreaseIndentList\'); } else { WikEdEditButton(obj, \'wikEdDecreaseIndentList\'); }' ],
			22: ['wikEdDefinitionList',   'wikEdButton',          wikEdText['wikEdDefinitionList title'],   wikEdImage['definitionList'],      '16', '16', wikEdText['wikEdDefinitionList alt'],   'WikEdEditButton(obj, objId);' ],
			23: ['wikEdImage',            'wikEdButton',          wikEdText['wikEdImage title'],            wikEdImage['image'],               '16', '16', wikEdText['wikEdImage alt'],            'WikEdEditButton(obj, objId);' ],
			24: ['wikEdTable',            'wikEdButton',          wikEdText['wikEdTable title'],            wikEdImage['table'],               '16', '16', wikEdText['wikEdTable alt'],            'WikEdEditButton(obj, objId);' ],
			11: ['wikEdReferences',       'wikEdButton',          wikEdText['wikEdReferences title'],       wikEdImage['references'],          '16', '16', wikEdText['wikEdReferences alt'],       'if (!eventShiftKey) { WikEdEditButton(obj, objId); } else { WikEdEditButton(obj, \'wikEdReferencesSection\'); }' ],

// wikify, textify
			26: ['wikEdWikify',           'wikEdButton',          wikEdText['wikEdWikify title'],           wikEdImage['wikify'],              '16', '16', wikEdText['wikEdWikify alt'],           'WikEdEditButton(obj, objId);' ],
			27: ['wikEdTextify',          'wikEdButton',          wikEdText['wikEdTextify title'],          wikEdImage['textify'],             '16', '16', wikEdText['wikEdTextify alt'],          'if (eventShiftKey) { WikEdEditButton(obj, objId, \'shift\'); } else { WikEdEditButton(obj, objId); }' ],

// control top
			77: ['wikEdRefHide',          'wikEdButtonUnchecked', wikEdText['wikEdRefHide title'],          wikEdImage['refHide'],             '16', '16', wikEdText['wikEdRefHide alt'],          'WikEdButton(obj, objId, true);' ],
			29: ['wikEdTextZoom',         'wikEdButton',          wikEdText['wikEdTextZoom title'],         wikEdImage['textZoom'],            '16', '16', wikEdText['wikEdTextZoom alt'],         'if (!eventShiftKey) { WikEdButton(obj, \'wikEdTextZoomDown\'); } else { WikEdButton(obj, \'wikEdTextZoomUp\'); }' ],
			30: ['wikEdClearHistory',     'wikEdButton',          wikEdText['wikEdClearHistory title'],     wikEdImage['clearHistory'],        '16', '16', wikEdText['wikEdClearHistory alt'],     'WikEdButton(obj, objId);' ],
			31: ['wikEdScrollToPreview',  'wikEdButton',          wikEdText['wikEdScrollToPreview title'],  wikEdImage['scrollToPreviewDown'], '16', '16', wikEdText['wikEdScrollToPreview alt'],  'WikEdButton(obj, objId);' ],
			32: ['wikEdScrollToEdit',     'wikEdButton',          wikEdText['wikEdScrollToEdit title'],     wikEdImage['scrollToEditDown'],    '16', '16', wikEdText['wikEdScrollToEdit alt'],     'WikEdButton(obj, objId);' ],

// control bottom
			33: ['wikEdUseWikEd',         'wikEdButtonChecked',   wikEdText['wikEdUseWikEd title'],         wikEdImage['useWikEd'],            '16', '16', wikEdText['wikEdUseWikEd alt'],         'WikEdButton(obj, objId, true);' ],
			34: ['wikEdHighlightSyntax',  'wikEdButtonUnchecked', wikEdText['wikEdHighlightSyntax title'],  wikEdImage['highlightSyntax'],     '16', '16', wikEdText['wikEdHighlightSyntax alt'],  'WikEdButton(obj, objId, true);' ],
			35: ['wikEdSource',           'wikEdButton',          wikEdText['wikEdSource title'],           wikEdImage['source'],              '16', '16', wikEdText['wikEdSource alt'],           'WikEdEditButton(obj, objId);' ],
			75: ['wikEdCloseToolbar',     'wikEdButtonUnchecked', wikEdText['wikEdCloseToolbar title'],     wikEdImage['closeToolbar'],        '16', '16', wikEdText['wikEdCloseToolbar alt'],     'WikEdButton(obj, objId, true);' ],
			36: ['wikEdUsing',            'wikEdButtonUnchecked', wikEdText['wikEdUsing title'],            wikEdImage['using'],               '16', '16', wikEdText['wikEdUsing alt'],            'WikEdButton(obj, objId, true);' ],
			37: ['wikEdFullScreen',       'wikEdButtonUnchecked', wikEdText['wikEdFullScreen title'],       wikEdImage['fullScreen'],          '16', '16', wikEdText['wikEdFullScreen alt'],       'WikEdButton(obj, objId, true);' ],
			79: ['wikEdTableMode',        'wikEdButtonUnchecked', wikEdText['wikEdTableMode title'],        wikEdImage['tableMode'],           '16', '16', wikEdText['wikEdTableMode alt'],        'WikEdButton(obj, objId, true);' ],

// find top
			39: ['wikEdFindAll',          'wikEdButton',          wikEdText['wikEdFindAll title'],          wikEdImage['findAll'],             '16', '16', wikEdText['wikEdFindAll alt'],          'WikEdEditButton(obj, objId);' ],
			40: ['wikEdFindPrev',         'wikEdButton',          wikEdText['wikEdFindPrev title'],         wikEdImage['findPrev'],            '16', '16', wikEdText['wikEdFindPrev alt'],         'WikEdEditButton(obj, objId);' ],
			41: ['wikEdFindNext',         'wikEdButton',          wikEdText['wikEdFindNext title'],         wikEdImage['findNext'],            '16', '16', wikEdText['wikEdFindNext alt'],         'if (eventShiftKey) { WikEdEditButton(obj, objId, \'shift\'); } else { WikEdEditButton(obj, objId); }' ],
			43: ['wikEdJumpPrev',         'wikEdButton',          wikEdText['wikEdJumpPrev title'],         wikEdImage['jumpPrev'],            '16', '16', wikEdText['wikEdJumpPrev alt'],         'WikEdEditButton(obj, objId);' ],
			44: ['wikEdJumpNext',         'wikEdButton',          wikEdText['wikEdJumpNext title'],         wikEdImage['jumpNext'],            '16', '16', wikEdText['wikEdJumpNext alt'],         'WikEdEditButton(obj, objId);' ],

// find bottom
			46: ['wikEdReplaceAll',       'wikEdButton',          wikEdText['wikEdReplaceAll title'],       wikEdImage['replaceAll'],          '16', '16', wikEdText['wikEdReplaceAll alt'],       'WikEdEditButton(obj, objId);' ],
			47: ['wikEdReplacePrev',      'wikEdButton',          wikEdText['wikEdReplacePrev title'],      wikEdImage['replacePrev'],         '16', '16', wikEdText['wikEdReplacePrev alt'],      'WikEdEditButton(obj, objId);' ],
			48: ['wikEdReplaceNext',      'wikEdButton',          wikEdText['wikEdReplaceNext title'],      wikEdImage['replaceNext'],         '16', '16', wikEdText['wikEdReplaceNext alt'],      'if (eventShiftKey) { WikEdEditButton(obj, objId, \'shift\'); } else { WikEdEditButton(obj, objId); }' ],
			49: ['wikEdCaseSensitive',    'wikEdButtonUnchecked', wikEdText['wikEdCaseSensitive title'],    wikEdImage['caseSensitive'],       '16', '16', wikEdText['wikEdCaseSensitive alt'],    'WikEdButton(obj, objId, true);' ],
			50: ['wikEdRegExp',           'wikEdButtonUnchecked', wikEdText['wikEdRegExp title'],           wikEdImage['regExp'],              '16', '16', wikEdText['wikEdRegExp alt'],           'WikEdButton(obj, objId, true);' ],
			51: ['wikEdFindAhead',        'wikEdButtonUnchecked', wikEdText['wikEdFindAhead title'],        wikEdImage['findAhead'],           '16', '16', wikEdText['wikEdFindAhead alt'],        'WikEdButton(obj, objId, true);' ],

// fix top
			52: ['wikEdFixBasic',         'wikEdButton',          wikEdText['wikEdFixBasic title'],         wikEdImage['fixBasic'],            '16', '16', wikEdText['wikEdFixBasic alt'],         'WikEdEditButton(obj, objId);' ],
			53: ['wikEdFixHtml',          'wikEdButton',          wikEdText['wikEdFixHtml title'],          wikEdImage['fixHtml'],             '16', '16', wikEdText['wikEdFixHtml alt'],          'WikEdEditButton(obj, objId);' ],
			54: ['wikEdFixCaps',          'wikEdButton',          wikEdText['wikEdFixCaps title'],          wikEdImage['fixCaps'],             '16', '16', wikEdText['wikEdFixCaps alt'],          'WikEdEditButton(obj, objId);' ],
			55: ['wikEdFixUnicode',       'wikEdButton',          wikEdText['wikEdFixUnicode title'],       wikEdImage['fixUnicode'],          '16', '16', wikEdText['wikEdFixUnicode alt'],       'WikEdEditButton(obj, objId);' ],
			81: ['wikEdFixRedirect',      'wikEdButton',          wikEdText['wikEdFixRedirect title'],      wikEdImage['fixRedirect'],         '16', '16', wikEdText['wikEdFixRedirect alt'],      'WikEdEditButton(obj, objId);' ],
			56: ['wikEdFixAll',           'wikEdButton',          wikEdText['wikEdFixAll title'],           wikEdImage['fixAll'],              '16', '16', wikEdText['wikEdFixAll alt'],           'WikEdEditButton(obj, objId);' ],
			57: ['wikEdFixRegExTypo',     'wikEdButton',          wikEdText['wikEdFixRegExTypo title'],     wikEdImage['fixRegExTypo'],        '16', '16', wikEdText['wikEdFixRegExTypo alt'],     'WikEdEditButton(obj, objId);' ],

// fix bottom
			58: ['wikEdFixDashes',        'wikEdButton',          wikEdText['wikEdFixDashes title'],        wikEdImage['fixDash'],             '16', '16', wikEdText['wikEdFixDashes alt'],        'WikEdEditButton(obj, objId);' ],
			59: ['wikEdFixPunct',         'wikEdButton',          wikEdText['wikEdFixPunct title'],         wikEdImage['fixPunct'],            '16', '16', wikEdText['wikEdFixPunct alt'],         'WikEdEditButton(obj, objId);' ],
			60: ['wikEdFixMath',          'wikEdButton',          wikEdText['wikEdFixMath title'],          wikEdImage['fixMath'],             '16', '16', wikEdText['wikEdFixMath alt'],          'WikEdEditButton(obj, objId);' ],
			61: ['wikEdFixChem',          'wikEdButton',          wikEdText['wikEdFixChem title'],          wikEdImage['fixChem'],             '16', '16', wikEdText['wikEdFixChem alt'],          'WikEdEditButton(obj, objId);' ],
			62: ['wikEdFixUnits',         'wikEdButton',          wikEdText['wikEdFixUnits title'],         wikEdImage['fixUnits'],            '16', '16', wikEdText['wikEdFixUnits alt'],         'WikEdEditButton(obj, objId);' ],

// preview top
			65: ['wikEdClose',            'wikEdButton',          wikEdText['wikEdClose title'],            wikEdImage['close'],               '16', '16', wikEdText['wikEdClose alt'],            'WikEdButton(obj, objId);' ],
			66: ['wikEdScrollToPreview2', 'wikEdButton',          wikEdText['wikEdScrollToPreview2 title'], wikEdImage['scrollToPreviewDown'], '16', '16', wikEdText['wikEdScrollToPreview2 alt'], 'WikEdButton(obj, objId);' ],
			67: ['wikEdScrollToEdit2',    'wikEdButton',          wikEdText['wikEdScrollToEdit2 title'],    wikEdImage['scrollToEdit'],        '16', '16', wikEdText['wikEdScrollToEdit2 alt'],    'WikEdButton(obj, objId);' ],

// preview bottom
			70: ['wikEdClose2',           'wikEdButton',          wikEdText['wikEdClose2 title'],           wikEdImage['close'],               '16', '16', wikEdText['wikEdClose2 alt'],           'WikEdButton(obj, objId);' ],
			71: ['wikEdScrollToPreview3', 'wikEdButton',          wikEdText['wikEdScrollToPreview3 title'], wikEdImage['scrollToPreview'],     '16', '16', wikEdText['wikEdScrollToPreview3 alt'], 'WikEdButton(obj, objId);' ],
			72: ['wikEdScrollToEdit3',    'wikEdButton',          wikEdText['wikEdScrollToEdit3 title'],    wikEdImage['scrollToEdit'],        '16', '16', wikEdText['wikEdScrollToEdit3 alt'],    'WikEdButton(obj, objId);' ],

// jump
			78: ['wikEdDiff',             'wikEdButtonUnchecked', wikEdText['wikEdDiff title'],             wikEdImage['wikEdDiff'],           '16', '16', wikEdText['wikEdDiff alt'],             'WikEdButton(obj, objId, true);' ],
			74: ['wikEdScrollToEdit4',    'wikEdButtonSolo',      wikEdText['wikEdScrollToEdit4 title'],    wikEdImage['scrollToEditDown'],    '16', '16', wikEdText['wikEdScrollToEdit4 alt'],    'WikEdButton(obj, objId);' ],

// dummy
			76: ['wikEdDummy',            'wikEdButtonDummy',     '',                                       wikEdImage['dummy'],               '16', '16', '',                                     '' ]
		});
	};

// button access keys
	if (typeof(wikEdButtonKey) == 'undefined') { window.wikEdButtonKey = {}; }

// WikedInitButtonKey: define accesskeys for edit buttons (wikEd button number: [key string, JS key code])
	window.WikedInitButtonKey = function() {
		WikEdInitObject(wikEdButtonKey, {
			26: ['b', 66], // wikify
			27: ['o', 79], // textify
			67: ['g', 71], // scrolltoedit2
			72: ['g', 71], // scrolltoedit3
			74: ['g', 71], // scrolltoedit4
			32: ['g', 71]  // scrolltoedit, overwrites previous wikEd buttons for same key
		});
	};

// button bars (id, class, button numbers)
	if (typeof(wikEdButtonBar) == 'undefined') { window.wikEdButtonBar = {}; }

// WikedInitButtonBar: define built-in button bars (id outer, class outer, id inner, class inner, height, grip title, button numbers)
	window.WikedInitButtonBar = function() {
		WikEdInitObject(wikEdButtonBar, {
			'format':    ['wikEdButtonBarFormat',    'wikEdButtonBarFormat',    'wikEdButtonsFormat',    'wikEdButtonsFormat',    44, wikEdText['wikEdGripFormat title'],  [1,2,3,4,5,6,7,8,9,10,12,13,14,'br',15,16,17,19,20,21,22,23,24,11,80,25,76] ],
			'textify':   ['wikEdButtonBarTextify',   'wikEdButtonBarTextify',   'wikEdButtonsTextify',   'wikEdButtonsTextify',   44, wikEdText['wikEdGripTextify title'], [26,'br',27] ],
			'custom1':   ['wikEdButtonBarCustom1',   'wikEdButtonBarCustom1',   'wikEdButtonsCustom1',   'wikEdButtonsCustom1',   44, wikEdText['wikEdGripCustom1 title'], [ ] ],
			'find':      ['wikEdButtonBarFind',      'wikEdButtonBarFind',      'wikEdButtonsFind',      'wikEdButtonsFind',      44, wikEdText['wikEdGripFind title'],    [39,40,'find',41,76,43,44,'br',46,47,'replace',48,49,50,51] ],
			'fix':       ['wikEdButtonBarFix',       'wikEdButtonBarFix',       'wikEdButtonsFix',       'wikEdButtonsFix',       44, wikEdText['wikEdGripFix title'],     [52,53,54,55,56,81,'br',58,59,60,61,62,57] ],
			'custom2':   ['wikEdButtonBarCustom2',   'wikEdButtonBarCustom2',   'wikEdButtonsCustom2',   'wikEdButtonsCustom2',   44, wikEdText['wikEdGripCustom2 title'], [ ] ],
			'control':   ['wikEdButtonBarControl',   'wikEdButtonBarControl',   'wikEdButtonsControl',   'wikEdButtonsControl',   44, wikEdText['wikEdGripControl title'], [77,29,30,35,31,32,'br',33,34,79,75,36,78,37] ],
			'preview':   ['wikEdButtonBarPreview',   'wikEdButtonBarPreview',   'wikEdButtonsPreview',   'wikEdButtonsPreview',    0, null,                                [66,67,65] ],
			'preview2':  ['wikEdButtonBarPreview2',  'wikEdButtonBarPreview2',  'wikEdButtonsPreview2',  'wikEdButtonsPreview2',   0, null,                                [71,72,70] ],
			'jump':      ['wikEdButtonBarJump',      'wikEdButtonBarJump',      'wikEdButtonsJump',      'wikEdButtonsJump',       0, null,                                [74] ]
		});
	};

// history length for find, replace, and summary fields
	if (typeof(wikEdHistoryLength) == 'undefined') { window.wikEdHistoryLength = {}; }
	wikEdHistoryLength['find'] = 10;
	wikEdHistoryLength['replace'] = 10;
	wikEdHistoryLength['summary'] = 10;

// presets for combo input fields dropdown options, {wikEdUsing} appends a link to this script
	if (typeof(wikEdComboPresetOptions) == 'undefined') { window.wikEdComboPresetOptions = {}; }
	if (typeof(wikEdComboPresetOptions['summary']) == 'undefined') { window.wikEdComboPresetOptions['summary'] = wikEdText['wikEdPresetSummary']; }

// text for summary link to this script
	if (typeof(wikEdSummaryUsing) == 'undefined') { window.wikEdSummaryUsing = wikEdText['wikEdSummaryUsing']; }

// expiration time span for permanent cookies in seconds
	if (typeof(wikEdCookieExpireSec) == 'undefined') { window.wikEdCookieExpireSec = 1 * 30 * 24 * 60 * 60; }

// disable wikEd preset
	if (typeof(wikEdDisabledPreset) == 'undefined') { window.wikEdDisabledPreset = false; }

// find ahead as you type checkbox preset
	if (typeof(wikEdFindAheadSelected) == 'undefined') { window.wikEdFindAheadSelected = true; }

// highlight syntax preset
	if (typeof(wikEdHighlightSyntaxPreset) == 'undefined') { window.wikEdHighlightSyntaxPreset = true; }

// enable wikEd preset
	if (typeof(wikEdUseWikEdPreset) == 'undefined') { window.wikEdUseWikEdPreset = true; }

// add '...using wikEd' to summary preset
	if (typeof(wikEdUsingPreset) == 'undefined') { window.wikEdUsingPreset = false; }

// scroll to edit field on non-preview pages
	if (typeof(wikEdScrollToEdit) == 'undefined') { window.wikEdScrollToEdit = true; }

// focus the edit field on non-preview pages
	if (typeof(wikEdFocusEdit) == 'undefined') { window.wikEdFocusEdit = true; }

// wikEdDiff preset
	if (typeof(wikEdDiffPreset) == 'undefined') { window.wikEdDiffPreset = false; }

// fullscreen mode preset
	if (typeof(wikEdFullScreenModePreset) == 'undefined') { window.wikEdFullScreenModePreset = false; }

// show MediaWiki toolbar preset
	if (typeof(wikEdCloseToolbarPreset) == 'undefined') { window.wikEdCloseToolbarPreset = false; }

// hide ref tags preset
	if (typeof(wikEdRefHidePreset) == 'undefined') { window.wikEdRefHidePreset = false; }

// text size adjustment for edit window (percentage)
	if (typeof(wikEdTextSizeAdjust) == 'undefined') { window.wikEdTextSizeAdjust = 100; }

// remove invisible syntax highlighting comments after closing tag
	if (typeof(wikEdRemoveHighlightComments) == 'undefined') { window.wikEdRemoveHighlightComments = true; }

// show the text-to-source button for testing purposes
	if (typeof(wikEdShowSourceButton) == 'undefined') { window.wikEdShowSourceButton = false; }

// show the using-wikEd button
	if (typeof(wikEdShowUsingButton) == 'undefined') { window.wikEdShowUsingButton = false; }

// the wikEd help page link to be displayed after the editing help link, an empty string disables the link
	if (typeof(wikEdHelpPageLink) == 'undefined') { window.wikEdHelpPageLink = wikEdText['wikEdHelpPageLink']; }

// enable external diff script
	if (typeof(wikEdLoadDiffScript) == 'undefined') { window.wikEdLoadDiffScript = true; }

// enable external wikEdDiff script
	if (typeof(wikEdLoadDiff) == 'undefined') { window.wikEdLoadDiff = true; }

// enable external InstaView script
	if (typeof(wikEdLoadInstaView) == 'undefined') { window.wikEdLoadInstaView = true; }

// enable external IERange script
	if (typeof(wikEdLoadIERange) == 'undefined') { window.wikEdLoadIERange = true; }

// RegExTypoFix rules page, the address must have the exact same domain name as the used wiki
	if (typeof(wikEdRegExTypoFixURL) == 'undefined') { window.wikEdRegExTypoFixURL = wikEdHomeBaseUrl + 'w/index.php?title=Wikipedia:AutoWikiBrowser/Typos&action=raw'; }

// enable RegExTypoFix button (http://en.wikipedia.org/wiki/User:Mboverload/RegExTypoFix)
	if (typeof(wikEdRegExTypoFix) == 'undefined') { window.wikEdRegExTypoFix = false; }

// enable highlighting as links
	if (typeof(wikEdFollowHighlightedLinks) == 'undefined') { window.wikEdFollowHighlightedLinks = false; }

// skip the browser detection to run wikEd under IE and Opera
	if (typeof(wikEdSkipBrowserTest) == 'undefined') { window.wikEdSkipBrowserTest = false; }

// set the button bar grip width in px
	if (typeof(wikEdButtonBarGripWidth) == 'undefined') { window.wikEdButtonBarGripWidth = 8; }

// enable local preview (Pilaf's InstaView)
	if (typeof(wikEdUseLocalPreview) == 'undefined') { window.wikEdUseLocalPreview = true; }

// allow ajax requests from local copy for testing, also defined in wikEdDiff.js
	if (typeof(wikEdAllowLocalAjax) == 'undefined') { window.wikEdAllowLocalAjax = false; }

// enable server preview (Ajax)
	if (typeof(wikEdUseAjaxPreview) == 'undefined') { window.wikEdUseAjaxPreview = true; }

// enable appending '<references/> for Ajax section previews
	if (typeof(wikEdSectionPreviewRefs) == 'undefined') { window.wikEdSectionPreviewRefs = true; }

// enable auto update (Ajax)
	if (typeof(wikEdAutoUpdate) == 'undefined') { window.wikEdAutoUpdate = true; }

// hours between update check (monobook.js)
	if (typeof(wikEdAutoUpdateHours) == 'undefined') { window.wikEdAutoUpdateHours = 20; }

// hours between update check (Greasemonkey)
	if (typeof(wikEdAutoUpdateHoursGM) == 'undefined') { window.wikEdAutoUpdateHoursGM = 40; }

// auto update: version url (Ajax)
	if (typeof(wikEdAutoUpdateUrl) == 'undefined') { window.wikEdAutoUpdateUrl = wikEdHomeBaseUrl + 'w/index.php?title=User:Cacycle/wikEd_current_version&action=raw&maxage=0'; }

// auto update: script url for Greasemonkey update
	if (typeof(wikEdAutoUpdateScriptUrl) == 'undefined') { window.wikEdAutoUpdateScriptUrl = wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Cacycle/wikEd.user.js'; }

// show complete unshortened article text for local diff, also defined in wikEdDiff.js
	if (typeof(wikEdFullDiff) == 'undefined') { window.wikEdFullDiff = false; }

// make links ctrl-clickable
	if (typeof(wikEdLinkify) == 'undefined') { window.wikEdLinkify = true; }

// hide refs and templates in newbie mode
	if (typeof(wikEdHideContent) == 'undefined') { window.wikEdHideContent = true; }

// wikify table parameters, replaces original table parameters with this string
	if (typeof(wikEdWikifyTableParameters) == 'undefined') { window.wikEdWikifyTableParameters = ''; }

// do not rearrange page elements
	if (typeof(wikEdNoRearrange) == 'undefined') { window.wikEdNoRearrange = false; }

// use French rules for fix punctuation
	if (typeof(wikEdFixPunctFrench) == 'undefined') { window.wikEdFixPunctFrench = false; }

// wikEdSetupHook, executed after wikEd has been set up, usage: wikEdSetupHook.push(YourFunction);
	if (typeof(wikEdSetupHook) == 'undefined') { window.wikEdSetupHook = []; }

// wikEdOnHook, executed after wikEd has been re-enabled by logo click, usage: wikEdOnHook.push(YourFunction);
	if (typeof(wikEdOnHook) == 'undefined') { window.wikEdOnHook = []; }

// wikEdOffHook, executed after wikEd has been disabled by logo click, usage: wikEdOffHook.push(YourFunction);
	if (typeof(wikEdOffHook) == 'undefined') { window.wikEdOffHook = []; }

// wikEdTextareaHook, executed after classic textarea has been enabled by user, usage: wikEdTextareaHook.push(YourFunction);
	if (typeof(wikEdTextareaHook) == 'undefined') { window.wikEdTextareaHook = []; }

// wikEdFrameHook, executed after wikEd edit frame has been enabled by user, usage: wikEdFrameHook.push(YourFunction);
	if (typeof(wikEdFrameHook) == 'undefined') { window.wikEdFrameHook = []; }

// custom edit form id instead of 'editform'
	if (typeof(wikEdCustomEditFormId) == 'undefined') { window.wikEdCustomEditFormId = ''; }

// custom textarea id instead of 'wpTextbox1'
	if (typeof(wikEdCustomTextAreaId) == 'undefined') { window.wikEdCustomTextAreaId = ''; }

// custom save button id instead of 'wpSave'
	if (typeof(wikEdCustomSaveButtonId) == 'undefined') { window.wikEdCustomSaveButtonId = ''; }

// show table mode togle button
	if (typeof(wikEdShowTableModeButton) == 'undefined') { window.wikEdShowTableModeButton = false; }

// maximal time for syntax highlighting in ms
	if (typeof(wikEdMaxHighlightTime) == 'undefined') { window.wikEdMaxHighlightTime = 3000; }

// first char of article names is case sensitive (e.g. Wiktionary)
	if (typeof(wikEdArticlesCaseSensitive) == 'undefined') { window.wikEdArticlesCaseSensitive = false; }

// force immediate update if this version string is newer
	if (typeof(wikEdForcedUpdate) == 'undefined') { window.wikEdForcedUpdate = ''; }

// display highlighting error messages in text
	if (typeof(wikEdHighlightError) == 'undefined') { window.wikEdHighlightError = false; }

// display preview of files in text
	if (typeof(wikEdFilePreview) == 'undefined') { window.wikEdFilePreview = true; }

// file preview image size in pixels
	if (typeof(wikEdFilePreviewSize) == 'undefined') { window.wikEdFilePreviewSize = 75; }

// file preview image size in pixels
	if (typeof(wikEdAntiHighlightBleeding) == 'undefined') { window.wikEdAntiHighlightBleeding = true; }

// do not hide templates shorter than this number of chars
	if (typeof(wikEdTemplNoHideLength) == 'undefined') { window.wikEdTemplNoHideLength = 40; }

// debug window maximal length in chars
	if (typeof(wikEdDebugMaxLength) == 'undefined') { window.wikEdDebugMaxLength = 50000; }

// debug display of DOM nodes: maximal length of innerHTML in chars
	if (typeof(wikEdDebugInnerHtmlLength) == 'undefined') { window.wikEdDebugInnerHtmlLength = 150; }

	return;
};

// wikEd code home base URL for https compatibility
if (typeof(wikEdHomeBaseUrlStandard) == 'undefined') { window.wikEdHomeBaseUrlStandard = 'http://en.wikipedia.org/'; }
if (typeof(wikEdHomeBaseUrlSecure) == 'undefined') { window.wikEdHomeBaseUrlSecure = 'https://secure.wikimedia.org/wikipedia/en/'; }

// set wikEd home base url depending on current page address: standard (http:) or secure (https:)
if (window.location.protocol == 'https:') {
	window.wikEdHomeBaseUrl = wikEdHomeBaseUrlSecure;
}
else {
	window.wikEdHomeBaseUrl = wikEdHomeBaseUrlStandard;
}

// diff script URL
if (typeof(wikEdDiffScriptSrc) == 'undefined') { window.wikEdDiffScriptSrc = wikEdHomeBaseUrl + 'w/index.php?title=User:Cacycle/diff.js&action=raw&ctype=text/javascript'; }

// wikEdDiff script URL, also defined in wikEdDiff.js
if (typeof(wikEdDiffSrc) == 'undefined') { window.wikEdDiffSrc = wikEdHomeBaseUrl + 'w/index.php?title=User:Cacycle/wikEdDiff.js&action=raw&ctype=text/javascript'; }

// InstaView script URL
if (typeof(wikEdInstaViewSrc) == 'undefined') { window.wikEdInstaViewSrc = wikEdHomeBaseUrl + 'w/index.php?title=User:Pilaf/include/instaview.js&action=raw&ctype=text/javascript'; }

// IERange script URL (MS IE compatibility library)
if (typeof(wikEdIERangeSrc) == 'undefined') { window.wikEdIERangeSrc = wikEdHomeBaseUrl + 'w/index.php?title=User:Cacycle/ierange.js&action=raw&ctype=text/javascript'; }

// wikEd-as-gadget detection, set to true if gadget script name is not MediaWiki:Gadget-wikEd.js
if (typeof(wikEdGadget) == 'undefined') { window.wikEdGadget = null; }

// duplicate edit warnings from the top of the page to above the edit window
if (typeof(wikEdDoCloneWarnings) == 'undefined') { window.wikEdDoCloneWarnings = true; }


//
// end of user configurable variables
//


//
// WikEdInitGlobals: initialize non-configurable variables
//

window.WikEdInitGlobals = function() {

// global variables
	window.wikEdTurnedOn = false;
	window.wikEdDisabled = true;
	window.wikEdUploadEdit = false;
	window.wikEdLanguage = '';
	window.wikEdWatchlistEdit = false;
	window.wikEdDebugOpen = false;
	window.wikEdPageName = null;
	window.wikEdPageNamespace = null;

// history
	window.wikEdFieldHist = [];
	window.wikEdSavedName = [];
	window.wikEdInputElement = [];
	window.wikEdSelectElement = [];

	window.wikEdCheckMarker = [];
	window.wikEdCheckMarker[true] = '♦';
	window.wikEdCheckMarker[false] = '◊';

// undo all, redo all
	window.wikEdOrigVersion = '';
	window.wikEdLastVersion = null;

// global dom elements
	window.wikEdLogo = null;
	window.wikEdLogoList = null;

	window.wikEdDebug = null;
	window.wikEdWikiEditor = null;
	window.wikEdWikiEditorFrame = null;
	window.wikEdWikiEditorTop = null;
	window.wikEdWikiEditorBar = null;
	window.wikEdWikiEditorBottom = null;
	window.wikEdWikiEditorText = null;
	window.wikEdTextareaContainer = null;
	window.wikEdToolbar = null;
	window.wikEdTextarea = null;
	window.wikEdEditForm = null;
	window.wikEdFrameInner = null;
	window.wikEdFrameOuter = null;
	window.wikEdFrame = null;
	window.wikEdFrameBody = null;
	window.wikEdFrameDocument = null;
	window.wikEdFrameWindow = null;

	window.wikEdInputWrapper = null;
	window.wikEdEditorWrapper = null;
	window.wikEdToolbarWrapper = null;
	window.wikEdCaptchaWrapper = null;
	window.wikEdDebugWrapper = null;
	window.wikEdEditWrapper = null;
	window.wikEdTextareaWrapper = null;
	window.wikEdFrameWrapper = null;
	window.wikEdConsoleWrapper = null;
	window.wikEdButtonsWrapper = null;
	window.wikEdSummaryWrapper = null;
	window.wikEdSummaryInputWrapper = null;
	window.wikEdSummaryOptions = null;
	window.wikEdSubmitWrapper = null;
	window.wikEdSubmitButtonsWrapper = null;
	window.wikEdLocalPrevWrapper = null;
	window.wikEdInsertWrapper = null;

	window.wikEdButtonBarFormat = null;
	window.wikEdButtonBarTextify = null;
	window.wikEdButtonBarCustom1 = null;
	window.wikEdButtonBarFind = null;
	window.wikEdButtonBarFix = null;
	window.wikEdButtonBarCustom2 = null;
	window.wikEdButtonBarControl = null;
	window.wikEdButtonBarPreview = null;
	window.wikEdButtonBarPreview2 = null;
	window.wikEdButtonBarJump = null;
	window.wikEdPreviewBox = null;
	window.wikEdClearSummary = null;
	window.wikEdClearSummaryImg = null;

	window.wikEdCaseSensitive = null;
	window.wikEdRegExp = null;
	window.wikEdFindAhead = null;

	window.wikEdFindText = null;
	window.wikEdReplaceText = null;
	window.wikEdSummaryText = null;
	window.wikEdSummarySelect = null;
	window.wikEdSummaryTextWidth = null;

	window.wikEdEditOptions = null;
	window.wikEdEditHelp = null;

	window.wikEdSaveButton = null;
	window.wikEdPreviewButton = null;
	window.wikEdLDiffButton = null;
	window.wikEdLocalPreview = null;
	window.wikEdLocalDiff = null;
	window.wikEdDiffPreviewButton = null;
	window.wikEdSummaryLabel = null;

	window.wikEdGetGlobalNode = null;

	window.WikEdHighlightNamedHideButtonsStylesheet = null;

// frame resizing
	window.wikEdResizeGripWidth = 16;
	window.wikEdResizeGripHeight = 16;
	window.wikEdResizeFramePageYStart = 0;
	window.wikEdResizeFramePageXStart = 0;
	window.wikEdResizeFrameOffsetHeight = 0;
	window.wikEdResizeFrameOffsetWidth = 0;
	window.wikEdResizeFrameMouseOverGrip = false;
	window.wikEdResizeFrameActive = false;
	window.wikEdFrameHeight = '';
	window.wikEdFrameWidth = '';
	window.wikEdTextareaHeight = '';
	window.wikEdTextareaWidth = '';

// various
	window.wikEdInsertCounter = 0;
	window.wikEdEditButtonHandler = {};
	window.wikEdTextareaBorderHeight = 0;
	window.wikEdFrameBorderHeight = 0;
	window.wikEdFrameBorderWidth = 0;
	window.wikEdTextareaOffsetHeightInitial = 0;
	window.wikEdClearSummaryWidth = null;
	window.wikEdFullScreenMode = false;
	window.wikEdAddNewSection = null;
	window.wikEdBrowserNotSupported = false;
	window.wikEdFrameScrollTop = null;
	window.wikEdTextareaUpdated = null;
	window.wikEdPreviewIsAjax = null;
	window.wikEdButtonKeyCode = [];
	if (typeof(wikEdWikiGlobals) == 'undefined') { window.wikEdWikiGlobals = []; }
	window.wikEdDirection = null;
	window.wikEdTextSize = 0;
	window.wikEdTextSizeInit = 0;
	window.wikEdPreviewPage = false;
	window.wikEdClonedWarnings = false;
	window.wikEdGeSHiCSS = [];

// override site javascript functions
	window.WikEdInsertTagsOriginal = null;
	window.WikEdInsertAtCursorOriginal = null;

// wikEd settings
	window.wikEdRefHide = false;
	window.wikEdUsing = false;
	window.wikEdUseWikEd = false;
	window.wikEdCloseToolbar = false;
	window.wikEdHighlightSyntax = false;
	window.wikEdNoSpellcheck = false;
	window.wikEdDiff = false;
	window.wikEdTableMode = false;
	window.wikEdCleanNodes = false;

// unicode fixing and char highlighting
	window.wikEdSupportedChars = null;
	window.wikEdReservedChars = null;
	window.wikEdSpecialChars = null;
	window.wikEdProblemChars = null;

	window.wikEdCharEntitiesByName = {};

	window.wikEdControlCharHighlighting = null;
	window.wikEdControlCharHighlightingStr = '';
	window.wikEdCharHighlighting = null;
	window.wikEdCharHighlightingStr = '';

// linkification and hiding
	window.wikEdLinkifyIdNo = 0;
	window.wikEdLinkifyArray = [];
	window.wikEdReferenceArray = [];
	window.wikEdTemplateArray = [];
	window.wikEdCharEntityArray = [];

// RegExTypoFix rules
	window.wikEdTypoRulesFind = [];
	window.wikEdTypoRulesReplace = [];

// redirect fixing
	window.wikEdRedirectsCache = {};

// file preview
	window.wikEdFilePreviewCache = {};
	window.wikEdFilePreviewRequest = '';
	window.wikEdFilePreviewNo = 0;
	window.wikEdFilePreviewIds = [];

// debugging time measurement, usage: wikEdDebugTimer.push([1234, new Date]); WikEdDebugTimer();
	window.wikEdDebugTimer = [];

// syntax highlighting
	window.wikEdParseObj = {};

// MediaWiki file paths for use in regexps
	window.wikEdServer = '';
	window.wikEdArticlePath = '';
	window.wikEdScript = '';
	window.wikEdScriptPath = '';
	window.wikEdScriptName = '';
	window.wikEdScriptURL = '';

// magic words and parser functions, see http://www.mediawiki.org/wiki/Help:Magic_words
// __MAGICWORDS__
	window.wikEdMagicWords = 'NOTOC|FORCETOC|TOC|NOEDITSECTION|NEWSECTIONLINK|NOGALLERY|HIDDENCAT|NOCONTENTCONVERT|NOCC|NOTITLECONVERT|NOTC|END|START|NOINDEX|INDEX|STATICREDIRECT';

// template, parser function, and parser variable modifiers {{modifier:...}}
// see http://meta.wikimedia.org/wiki/Help:Magic_words#Template_modifiers
	window.wikEdTemplModifier = 'int|msg|msgnw|raw|subst';

// parser variables {{variable}}
	window.wikEdParserVariables = 'CURRENTYEAR|CURRENTMONTH|CURRENTMONTHNAME|CURRENTMONTHNAMEGEN|CURRENTMONTHABBREV|CURRENTDAY|CURRENTDAY2|CURRENTDOW|CURRENTDAYNAME|CURRENTTIME|CURRENTHOUR|CURRENTWEEK|CURRENTTIMESTAMP|LOCALYEAR|LOCALMONTH|LOCALMONTHNAME|LOCALMONTHNAMEGEN|LOCALMONTHABBREV|LOCALDAY|LOCALDAY2|LOCALDOW|LOCALDAYNAME|LOCALTIME|LOCALHOUR|LOCALWEEK|LOCALTIMESTAMP|SITENAME|CURRENTVERSION|CONTENTLANGUAGE|REVISIONID|REVISIONDAY|REVISIONDAY2|REVISIONMONTH|REVISIONYEAR|REVISIONTIMESTAMP|SERVER|SERVERNAME|SCRIPTPATH|FULLPAGENAME|PAGENAME|BASEPAGENAME|SUBPAGENAME|SUBJECTPAGENAME|TALKPAGENAME|FULLPAGENAMEE|PAGENAMEE|BASEPAGENAMEE|SUBPAGENAMEE|SUBJECTPAGENAMEE|TALKPAGENAMEE|NAMESPACE|SUBJECTSPACE|ARTICLESPACE|TALKSPACE|NAMESPACEE|SUBJECTSPACEE|TALKSPACEE|DIRMARK|DIRECTIONMARK|PAGENAME|PAGENAMEE';

// parser variables {{variable:R}}
	window.wikEdParserVariablesR = 'NUMBEROFPAGES|NUMBEROFARTICLES|NUMBEROFFILES|NUMBEROFEDITS|NUMBEROFUSERS|NUMBEROFADMINS|NUMBEROFVIEWS|NUMBEROFACTIVEUSERS|PROTECTIONLEVEL';

// parser functions {{FUNCTION:parameter|R}}
	window.wikEdParserFunctionsR = 'NUMBERINGROUP|PAGESINNS|PAGESINNAMESPACE|PAGESINCATEGORY|PAGESINCAT|PAGESIZE|DEFAULTSORT|DISPLAYTITLE';

// parser functions {{function:param|param}}
	window.wikEdParserFunctions = 'localurl|localurle|fullurl|filepath|fullurle|urlencode|urldecode|anchorencode|ns|lc|lcfirst|uc|ucfirst|formatnum|padleft|padright|padright|plural|grammar|gender|int';

// parser functions {{#function:param|param}}
	window.wikEdParserFunctionsHash = 'language|special|tag|tag|expr|if|ifeq|ifexist|ifexpr|switch|time|timel|rel2abs|titleparts|iferror|iferror|special|tag|categorytree|formatdate';

// define leaf elements for WikEdGetInnerHTML
	window.wikEdLeafElements = {
		'IMG':   true,
		'HR':    true,
		'BR':    true,
		'INPUT': true
	};

	return;
};

// variables needed during startup

// startup debugging
if (typeof(wikEdDebugStartUp) == 'undefined') { window.wikEdDebugStartUp = ''; }

// show missing translations
if (typeof(wikEdShowMissingTranslations) == 'undefined') { window.wikEdShowMissingTranslations = false; }

// hash of loaded scripts, also defined in wikEdDiff.js
if (typeof(wikEdExternalScripts) == 'undefined') { window.wikEdExternalScripts = null; }
if (typeof(wikEdStartup) == 'undefined') { window.wikEdStartup = false; }
if (typeof(wikEdPageLoaded) == 'undefined') { window.wikEdPageLoaded = false; }

// browser and os identification
if (typeof(wikEdBrowserName) == 'undefined') { window.wikEdBrowserName = ''; }
if (typeof(wikEdBrowserFlavor) == 'undefined') { window.wikEdBrowserFlavor = ''; }
if (typeof(wikEdBrowserVersion) == 'undefined') { window.wikEdBrowserVersion = 0; }
if (typeof(wikEdMSIE) == 'undefined') { window.wikEdMSIE = false; }
if (typeof(wikEdMozilla) == 'undefined') { window.wikEdMozilla = false; }
if (typeof(wikEdOpera) == 'undefined') { window.wikEdOpera = false; }
if (typeof(wikEdSafari) == 'undefined') { window.wikEdSafari = false; }
if (typeof(wikEdWebKit) == 'undefined') { window.wikEdWebKit = false; }
if (typeof(wikEdChrome) == 'undefined') { window.wikEdChrome = false; }
if (typeof(wikEdGreasemonkey) == 'undefined') { window.wikEdGreasemonkey = null; }
if (typeof(wikEdPlatform) == 'undefined') { window.wikEdPlatform = null; }

// content language default, also used for wikEd UI localization
if (typeof(wikEdLanguageDefault) == 'undefined') { window.wikEdLanguageDefault = ''; }

// load external translation
if (typeof(wikEdLoadTranslation) == 'undefined') { window.wikEdLoadTranslation = true; }

// translation javascript URLs
if (typeof(wikEdTranslations) == 'undefined') { window.wikEdTranslations = {}; }

// WikedInitTranslations: define translation javascript URLs ('': internal default)
window.WikedInitTranslations = function() {
	WikEdInitObject(wikEdTranslations, {
		'en':  '',
		'ar':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:ترجمان05/wikEd_international_ar.js',
		'zh-hans': wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Shibo77/wikEd_international_zh.js',
		'zh-hant': wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Quest_for_Truth/wikEd_international_zh-hant.js',
		'cs':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Sevela.p/wikEd_international_cs.js',
		'nl':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Jeronevw/wikEd_international_nl.js',
		'eo':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:ArnoLagrange/wikEd-eo.js',
		'fi':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Ejs-80/wikEd international fi.js',
		'fr':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Leag/wikEd-fr.js',
		'de':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Matthias_M./wikEd_international_de.js',
		'he':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:שמוליק/wikEd_international_he.js',
		'hu':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Csörföly D/wikEd-hu.js',
		'it':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Jalo/wikEd_international_it.js',
		'ja':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Hatukanezumi/wikEd_international_ja.js',
		'ko':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Ilovesabbath/wikEd_international_ko.js',
		'dsb': wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Michalwiki/wikEd_international_dsb.js',
		'ms':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Aviator/wikEd_international_ms.js',
		'no':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Dvyjones/wikEd_international_no.js',
		'nn':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Frokor/wikEd_international_nn.js',
		'pl':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Konradek/wikEd_international_pl.js',
		'pt':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Mosca/wikEd_international_pt.js',
		'ro':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Roamataa/wikEd_international_ro.js',
		'scn': wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Meloscn/wikEd_international_scn.js',
		'sk':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Helix84/wikEd_international_sk.js',
		'sl':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Eleassar/wikEd_international_sl.js',
		'es':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Axelei/wikEd_international_es.js',
		'sv':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Where_next_Columbus?/wikEd_international_sv.js',
		'hsb': wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Michalwiki/wikEd_international_hsb.js',
		'tr':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Vito_Genovese/wikEd_international_tr.js',
		'vi':  wikEdHomeBaseUrl + 'w/index.php?action=raw&ctype=text/javascript&title=User:Vinhtantran/wikEd_international_vi.js'
	});
};



// Mediawiki page and skin detection, logo placement
if (typeof(wikEdMediaWikiSkinIds) == 'undefined') { window.wikEdMediaWikiSkinIds = {}; }

// WikedInitMediaWikiSkinIds: define Mediawiki page and skin detection, logo placement
//   format: skin name: [ dom element to add logo to ('': top right), logo to this list or list contained in this parent element, rearrange page elements, [skin detection element id list] ],
	window.WikedInitMediaWikiSkinIds = function() {
		WikEdInitObject(wikEdMediaWikiSkinIds, {

// monobook, also detects simple and myskin
		monobook:    [ 'p-personal', true, true, ['column-content', 'content', 'bodyContent', 'siteSub', 'contentSub', 'column-one', 'p-cactions'] ],

// vector (see https://bugzilla.wikimedia.org/show_bug.cgi?id=19527)
		vector_old:  [ 'personal', true, true, ['content', 'bodyContent', 'siteSub', 'contentSub', 'left-navigation', 'namespaces'] ],
		vector_new:  [ 'p-personal', true, true, ['content', 'bodyContent', 'siteSub', 'contentSub', 'left-navigation', 'p-namespaces', 'p-cactions'] ],

// citizendium.org
		pinkwich5:   [ 'p-personal', true, true, ['column-content', 'content', 'bodycontent', 'sitesub', 'contentSub', 'column-one', 'p-cactions'] ],

// other MediaWiki skins
		standard:    [ 'quickbar', false, true, ['content', 'topbar', 'article', 'footer', 'pagestats'] ],
		nostalgia:   [ 'topbar', false, true, ['content', 'specialpages', 'article', 'footer'] ],
		cologneblue: [ 'quickbar', false, true, ['content', 'topbar', 'sitetitle', 'sitesub', 'article', 'footer'] ],
		modern:      [ 'p-personal', true, true, ['mw_header', 'mw_main', 'mw_contentwrapper'] ],

// wikia.com
		monaco:      [ 'userData', false, false, ['background_strip', 'siteSub', 'contentSub', 'monaco_footer'] ],
		quartz:      [ 'welcome', false, true, ['articleWrapper', 'bodyContent', 'siteSub', 'contentSub', 'sidebar'] ],
		searchwikia: [ 'header-li-buttons', false, true, ['header', 'header-container', 'header-go-button', 'article-container', 'article', 'article-text'] ],

// custom skins developed on wiki.mozilla.org and developer.mozilla.org
		cavendish:   [ '', false, true, ['internal', 'container', 'header', 'contentTop', 'mBody', 'side', 'nav', 'siteSub', 'contentSub'] ],
		devmo:       [ 'personal', false, true, ['developer-mozilla-org', 'container', 'header', 'navigation', 'bar', 'page', 'sidebar', 'sidebarslideup', 'contentTop', 'siteSub', 'contentSub'] ],

// custom skins
		gumax:       [ 'gumax-p-login', true, true, ['gumax-header', 'gumax-content-body'] ],

// custom MediaWiki identifier
		mediawiki:   [ '', false, false, ['mediawiki'] ]
	});
};

if (typeof(wikEdLogoContainerId) == 'undefined') { window.wikEdLogoContainerId = ''; }
if (typeof(wikEdRearrange) == 'undefined') { window.wikEdRearrange = false; }
if (typeof(wikEdLogoToList) == 'undefined') { window.wikEdLogoToList = false; }
if (typeof(wikEdSkin) == 'undefined') { window.wikEdSkin = ''; }

// non-configurable variables
window.wikEdGreasemonkeyToHead = false;
window.wikEdTranslationLoaded = false;


//
// WikEdInitObject: initialize object, keep pre-defined values
//

window.WikEdInitObject = function(array, preset, showMissing) {

	for (var key in preset) {
		if (array[key] == null) {
			array[key] = preset[key];

// show missing array entries
			if (showMissing == true)  {
				if (typeof(array[key]) == 'string') {
					wikEdDebugStartUp += '\t\t\t\'' + key + '\': \'' + array[key].replace(/\n/g, '\\n') + '\',\n';
				}
			}
		}
	}

	return;
};


//
// WikEdInitImage: initialize images, keep pre-defined values
//

window.WikEdInitImage = function(array, preset) {

	for (var key in preset) {
		if (array[key] == null) {

// remove MediaWiki path prefixes and add local path
			if (wikEdUseLocalImages == true) {
				array[key] = wikEdImagePathLocal + preset[key].replace(/^[0-9a-f]+\/[0-9a-f]+\//, '');
			}

// add path
			else {
				array[key] = wikEdImagePath + preset[key];
			}
		}
	}
	return;
};


//
// WikEdStartup: wikEd startup code, called during page load
//

window.WikEdStartup = function() {

// check if this has already been run
	if (wikEdStartup == true) {
		return;
	}
	wikEdStartup = true;

// redirect WED shortcut to WikEdDebug(objectName, object, popup)
	window.WED = WikEdDebug;

// check browser and version
	var agentMatch = navigator.userAgent.match(/(Firefox|Netscape|SeaMonkey|IceWeasel|IceCat|Fennec|Minefield|BonEcho|GranParadiso|Shiretoko)\W+(\d+\.\d+)/i);
	if (agentMatch != null) {
		wikEdBrowserName = 'Mozilla';
		wikEdBrowserFlavor = agentMatch[1];
		wikEdBrowserVersion = parseFloat(agentMatch[2]);
		wikEdMozilla = true;
	}

// check for MSIE
	else {
		agentMatch = navigator.userAgent.match(/(MSIE)\W+(\d+\.\d+)/i);
		if (agentMatch != null) {
			wikEdBrowserName = 'MSIE';
			wikEdBrowserVersion = parseFloat(agentMatch[2]);
			wikEdMSIE = true;
		}

// check for Opera
		else {
			agentMatch = navigator.userAgent.match(/(Opera)\W+(\d+\.\d+)/i);
			if (agentMatch != null) {
				wikEdBrowserName = 'Opera';
				wikEdBrowserVersion = parseFloat(agentMatch[2]);
				if (wikEdBrowserVersion == 9.80) {
					var versionMatch = navigator.userAgent.match(/(Version)\W+(\d+\.\d+)/i);
					if (versionMatch != null) {
						wikEdBrowserVersion = parseFloat(agentMatch[2]);
					}
				}
				wikEdOpera = true;
			}

// check for Google Chrome (AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.30 Safari/525.13)
			else {
				agentMatch = navigator.userAgent.match(/(Chrome)\W+(\d+\.\d+)/i);
				if (agentMatch != null) {
					wikEdBrowserName = 'Chrome';
					wikEdBrowserVersion = parseFloat(agentMatch[2]);
					wikEdChrome = true;
				}

// check for Safari
				else {
					agentMatch = navigator.userAgent.match(/(Safari)\W+(\d+\.\d+)/i);
					if (agentMatch != null) {
						wikEdBrowserName = 'Safari';
						wikEdBrowserVersion = parseFloat(agentMatch[2]);
						wikEdSafari = true;
					}

// check for other WebKit
					else {
						agentMatch = navigator.userAgent.match(/(WebKit)\W+(\d+\.\d+)/i);
						if (agentMatch != null) {
							wikEdBrowserName = 'WebKit';
							wikEdBrowserVersion = parseFloat(agentMatch[2]);
							wikEdWebKit = true;
						}
					}
				}
			}
		}
	}

// check OS
	var os = navigator.platform.match(/^(win|mac|unix|linux)/i);
	if (os != null) {
		wikEdPlatform = os[1].toLowerCase();
	}

// detect if run as a body script added by Greasemonkey installer
	if (document.getElementById('WikEdHeadScript') != null) {
		wikEdGreasemonkeyToHead = true;
	}

// load external wikEd translation and language settings
	if ( (wikEdLoadTranslation == true) && (wikEdTranslationLoaded == false) ) {
		var contentLang = WikEdGetGlobal('wgContentLanguage') || '';
		var userLang = WikEdGetGlobal('wgUserLanguage') || '';
		if ( (wikEdLanguageDefault != '') || (userLang != '') || (contentLang != '') ) {

// simplified Chinese
			if (contentLang == 'zh') {
				contentLang = 'zh-hans';
			}
			if ( (userLang == 'zh') || (userLang == 'zh-cn') || (userLang == 'zh-sg') ) {
				userLang = 'zh-hans';
			}

// traditional Chinese
			else if ( (userLang == 'zh-hk') || (userLang == 'zh-tw') ) {
				userLang = 'zh-hant';
			}

			WikedInitTranslations();
			var scriptUrl = wikEdTranslations[wikEdLanguageDefault] || '';
			if (scriptUrl == '') {
				scriptUrl = wikEdTranslations[userLang] || '';
				if (scriptUrl == '') {
					scriptUrl = wikEdTranslations[contentLang] || '';
				}
			}
			if (scriptUrl != '') {
				WikEdAppendScript(scriptUrl);
				wikEdTranslationLoaded = true;
			}
		}
	}

// schedule the setup routine
	WikEdAddEventListener(window, 'load', WikEdSetup, false);

	return;
};


//
// WikEdSetup: basic setup routine, scheduled after page load
//

window.WikEdSetup = function() {
	WikEdRemoveEventListener(window, 'load', WikEdSetup, false);

// check if this has already been run, either as a wiki or a Greasemonkey user script
	if (document.getElementById('wikEdSetupFlag') != null) {
		return;
	}

// detect already loaded external scripts
	if (wikEdExternalScripts == null) {
		wikEdExternalScripts = [];
		var pageScripts = document.getElementsByTagName('script');
		for (var i = 0; i < pageScripts.length; i ++) {
			var scriptSrc = pageScripts[i].src;
			var nameMatch = scriptSrc.match(/\btitle=([^&]*)/);
			if (nameMatch == null) {
				nameMatch = scriptSrc.match(/\/([^\/]*?)($|\?)/);
			}
			if (nameMatch != null) {
				var scriptName = nameMatch[1];
				if (scriptName != '') {

// ignore other diff.js scripts
					if ( (scriptName == 'diff.js') && (scriptSrc != wikEdDiffScriptSrc) ) {
						continue;
					}
					wikEdExternalScripts[scriptName] = true;
				}
			}
		}
	}

// exit if executed as Greasemonkey script if wiki user script is available
	if (typeof(GM_getValue) == 'function') {
		if (wikEdExternalScripts['wikEd.js'] == true) {
			wikEdGreasemonkey = false;
			return;
		}
		else {
			wikEdGreasemonkey = true;
		}
	}

// redirect Greasemonkey debugging function to WikEdDebug if run as a wiki user script
	else {
		window.GM_log = window.WikEdDebug;
	}

// detect wikEd running as a gadget
	if (wikEdGadget == null) {
		if (wikEdExternalScripts['MediaWiki:Gadget-wikEd.js'] == true) {
			wikEdGadget = true;
		}
	}

// set already run flag
	var flag = document.createElement('span');
	flag.id = 'wikEdSetupFlag';
	flag.className = 'wikEdSetupFlag';
	flag.style.display = 'none';
	flag.style.visibility = 'hidden';
	document.body.appendChild(flag);

// detect MediaWiki page and its skin
	WikedInitMediaWikiSkinIds();
	for (var skin in wikEdMediaWikiSkinIds) {
		if (wikEdMediaWikiSkinIds.hasOwnProperty(skin) == true) {
			var logoContainerId = wikEdMediaWikiSkinIds[skin][0];
			var logoToList = wikEdMediaWikiSkinIds[skin][1];
			var rearrange = wikEdMediaWikiSkinIds[skin][2];
			var skinIds = wikEdMediaWikiSkinIds[skin][3];
			for (var i = 0; i < skinIds.length; i ++) {
				if (document.getElementById(skinIds[i]) == null) {
					break;
				}
			}
			if (i == skinIds.length) {
				wikEdLogoContainerId = logoContainerId;
				wikEdSkin = skin;
				wikEdRearrange = rearrange;
				wikEdLogoToList = logoToList;
				break;
			}
		}
	}

// not a MediaWiki page
	if (wikEdSkin == '') {
		return;
	}

// initialize user configurable variables
	WikEdInitGlobalConfigs();

// do not rearrange page elements
	if (wikEdNoRearrange != false) {
		wikEdRearrange = false;
	}

// initialize non-configurable variables
	WikEdInitGlobals();

// parse global MediaWiki globals into hash
	var variable = ['wgServer', 'wgTitle', 'wgCanonicalNamespace', 'wgArticlePath', 'wgScript', 'wgScriptPath', 'wgUserName', 'wgCurRevisionId', 'wgScript', 'wgContentLanguage', 'wgUserLanguage', 'wgEnableAPI', 'wgPageName'];
	for (var i = 0; i < variable.length; i ++) {
		wikEdWikiGlobals[ variable[i] ] = WikEdGetGlobal(variable[i]);
	}

// check for updates
	WikEdAutoUpdate();

// initialize images (needed here for logo)
	WikedInitImages();

// load css for edit and non-edit pages
	WikedInitMainCSS();

// add stylesheet definitions
	WikEdApplyCSS(document, wikEdMainCSS);

// add image path to image filename
	if (wikEdLogo == null) {

// create logo
		wikEdLogo = document.createElement('img');
		wikEdLogo.id = 'wikEdLogoImg';

// insert logo into page
		var logoContainer;
		if (wikEdLogoContainerId != '') {
			logoContainer = document.getElementById(wikEdLogoContainerId);
		}
		if (logoContainer != null) {

// logo as last element of specified list (e.g. monobook, simple, myskin, gumax)
			if (wikEdLogoToList == true) {
				wikEdLogoList = document.createElement('li');
				wikEdLogoList.id = 'wikEdLogoList';
				wikEdLogoList.className = 'wikEdLogoList';
				wikEdLogoList.appendChild(wikEdLogo);
				var list;
				var logo;
				if (logoContainer.tagName == 'UL') {
					list = logoContainer;
				}
				else {
					list = logoContainer.getElementsByTagName('ul')[0];
				}
				if (list != null) {
					list.appendChild(wikEdLogoList);
					wikEdLogo.className = 'wikEdLogo';
				}
			}

// logo as last child of specified element
			else {
				logoContainer.appendChild(wikEdLogo);
				wikEdLogo.className = 'wikEdLogo';
			}
		}

// logo as first page element, fallback for undetected skin
		if (wikEdLogo.className == '') {
			document.body.insertBefore(wikEdLogo, document.body.firstChild);
			wikEdLogo.className = 'wikEdLogoFallBack';
		}

// add event listener to logo
		WikEdAddEventListener(wikEdLogo, 'click', WikEdMainSwitch, true);
	}

// page loaded flag for dynamically loaded scripts
	wikEdPageLoaded = true;

// load the external diff script if not already done
	if ( (wikEdLoadDiffScript == true) && (wikEdExternalScripts['diff.js'] == null) ) {
		if (typeof(WDiffString) == 'undefined') {
			WikEdAppendScript(wikEdDiffScriptSrc);
		}
		wikEdExternalScripts['diff.js'] = true;
	}

// load the external wikEdDiff script if not already done
	if ( (wikEdLoadDiff == true) && (wikEdExternalScripts['wikEdDiff.js'] == null) ) {
		if (typeof(WikEdDiff) == 'undefined') {
			WikEdAppendScript(wikEdDiffSrc);
		}
		wikEdExternalScripts['wikEdDiff.js'] = true;
	}

// load the MS IE selection/range compatibility library IERange
	if (wikEdMSIE == true) {
		if ( (wikEdLoadIERange == true) && (wikEdExternalScripts['ierange.js'] == null) ) {
			if (typeof(DOMUtils) == 'undefined') {
				WikEdAppendScript(wikEdIERangeSrc);
			}
			wikEdExternalScripts['ierange.js'] = true;
		}
	}

// check if disabled
	wikEdDisabled = WikEdGetSavedSetting('wikEdDisabled', wikEdDisabledPreset);
	if (wikEdDisabled == true) {
		wikEdUseWikEd = false;
		WikEdSetLogo();
		return;
	}

// get current page name
	wikEdPageName = wikEdWikiGlobals['wgPageName'];

// get current namespace
	if (wikEdPageName != null) {
		var colonPos = wikEdPageName.indexOf(':');
		if (colonPos == -1) {
			wikEdPageNamespace = '';
		}
		else {
			wikEdPageNamespace = wikEdPageName.substr(0, colonPos);
		}
	}

// location search string function: put all used images and icons on an empty page
	if (window.location.search.match(/(\?|&)wikEd=iconPage\b/i) != null) {
		var str = wikEdText['iconPage'];
		for (var imageKey in wikEdImage) {
			if (wikEdImage.hasOwnProperty(imageKey) == true) {
				var imageAddress = wikEdImage[imageKey];
				if (typeof(imageAddress) == 'string') {
					str += '<img src="' + imageAddress + '"> ';
				}
			}
		}
		document.body.innerHTML = str;
		return;
	}

// init syntax highlighting regExp object
	WikEdHighlightSyntaxInit();

// continue setup
	WikEdTurnOn(true);

	return;
};


//
// WikEdTurnOn: continue setup, can be called repeatedly
//

window.WikEdTurnOn = function(scrollToEditFocus) {

// check if setup was already run
	if (wikEdTurnedOn == true) {
		return;
	}

// set error logo
	WikEdSetLogo('error');

// no id, no wikEd
	if (navigator.appName == null) {
		wikEdBrowserNotSupported = true;
	}

// check the browser generation
	var generation = navigator.appVersion.match(/\d+(\.\d+)/);
	if ( (generation == null) || (generation[0] < 5.0) ) {
		wikEdBrowserNotSupported = true;
	}

// check browser versions
	switch (wikEdBrowserName) {

// check Mozilla version
		case 'Mozilla':
			if (
				(wikEdBrowserFlavor == 'Firefox') && (wikEdBrowserVersion < 1.5) ||
				(wikEdBrowserFlavor == 'Netscape') && (wikEdBrowserVersion < 8.0) ||
				(wikEdBrowserFlavor == 'SeaMonkey') && (wikEdBrowserVersion < 1.0)
			) {
				wikEdBrowserNotSupported = true;
			}
			break;

// check MSIE version
		case 'MSIE':
			wikEdBrowserNotSupported = true;
			break;

// check Opera version
		case 'Opera':
			if (wikEdBrowserVersion < 10.51) {
				wikEdBrowserNotSupported = true;
			}

// 10.50 beta, 10.51 are too buggy (inserthtml, parentNode...)
			wikEdBrowserNotSupported = true;

			break;

// check Google Chrome version
		case 'Chrome':
			if (wikEdBrowserVersion < 0.2) {
				wikEdBrowserNotSupported = true;
			}
			break;

// check Safari version
		case 'Safari':
			if (wikEdBrowserVersion < 500) {
				wikEdBrowserNotSupported = true;
			}
			break;
	}

// browser or version not supported, set error message and exit
	if ( (wikEdBrowserNotSupported == true) && (wikEdSkipBrowserTest == false) ) {
		WikEdSetLogo('browser');
		return;
	}

// get the textarea and other form elements

// custom form elements
	if (wikEdCustomEditFormId != '') {
		wikEdEditForm = document.getElementById(wikEdCustomEditFormId);
	}
	if (wikEdCustomTextAreaId != '') {
		wikEdTextarea = document.getElementById(wikEdCustomTextAreaId);
	}
	if (wikEdCustomSaveButtonId != '') {
		wikEdSaveButton = document.getElementById(wikEdCustomwikEdSaveButtonId);
	}

// standard form elements
	if (wikEdTextarea == null) {
		wikEdTextarea = document.getElementsByName('wpTextbox1')[0];
	}
	if (wikEdEditForm == null) {
		wikEdEditForm = document.getElementById('editform');
	}
	if (wikEdSaveButton == null) {
		wikEdSaveButton = document.getElementById('wpSave');
	}

// MediaWiki Semantic Forms extension support
	if (wikEdTextarea == null) {
		wikEdEditForm = document.getElementsByName('createbox')[0];
		wikEdTextarea = document.getElementsByName('free_text')[0];
	}

// edit raw watchlist page
	if (wikEdTextarea == null) {
		wikEdTextarea = document.getElementById('titles');
		if (wikEdTextarea != null) {
			wikEdRearrange = false;
			wikEdWatchlistEdit = true;

// get watchlist edit form
			var node = wikEdTextarea;
			while (node != null) {
				node = node.parentNode;
				if (node.tagName == 'FORM') {
					break;
				}
			}
			wikEdEditForm = node;
		}

// get watchlist submit button
		if (wikEdEditForm != null) {
			var submits = wikEdEditForm.getElementsByTagName('input');
			for (i = 0; i < submits.length; i ++) {
				if (submits[i].type == 'submit') {
					wikEdSaveButton = submits[i];
					break;
				}
			}
		}
	}

// check if it is an edit page
	if ( (wikEdTextarea == null) || (wikEdEditForm == null) || (wikEdSaveButton == null) ) {

// check if this is an upload page
		wikEdTextarea = document.getElementsByName('wpUploadDescription')[0];
		wikEdEditForm = document.getElementById('mw-upload-form');
		wikEdSaveButton = document.getElementsByName('wpUpload')[0];
		if ( (wikEdTextarea == null) || (wikEdEditForm == null) || (wikEdSaveButton == null) ) {

// set error indicator
			WikEdSetLogo();
			return;
		}
		wikEdUploadEdit = true;
		wikEdRearrange = false;
	}

// initialize frame css, main css, buttons, and button bars
	WikedInitFrameCSS();
	WikedInitMainEditCSS();
	WikedInitButton();
	WikedInitButtonKey();
	WikedInitButtonBar();

// define Unicode characters for fixing function
	WikEdInitUnicode();

// detect if we add a new section (+ tab)
	if (/(\?|&)section=new\b/.test(window.location.search) == true) {
		wikEdAddNewSection = true;
	}
	else {
		var section = document.getElementsByName('wpSection');
		if (section != null) {
			if (section.length > 0) {
				if (section[0].value == 'new') {
					wikEdAddNewSection = true;
				}
			}
		}
	}

// load the external InstaView script
	if ( (wikEdGreasemonkey == false) && (wikEdLoadInstaView == true) && (wikEdExternalScripts['instaview.js'] == null) ) {
		if (typeof(InstaView) == 'undefined') {
			WikEdAppendScript(wikEdInstaViewSrc);
		}
		wikEdExternalScripts['instaview.js'] = true;
	}
	else if ( (wikEdGreasemonkey == false) || (wikEdLoadInstaView != true) ) {
		wikEdUseLocalPreview = false;
	}

// get initial textarea dimensions
	wikEdTextareaBorderHeight = parseInt(WikEdGetStyle(wikEdTextarea, 'borderTopWidth'), 10) + parseInt(WikEdGetStyle(wikEdTextarea, 'borderBottomWidth'), 10);

	if (WikEdGetStyle(wikEdTextarea, 'display') != 'none') {
		wikEdTextareaOffsetHeightInitial = wikEdTextarea.offsetHeight;
	}
	else {
		wikEdTextareaOffsetHeightInitial = wikEdTextarea.parentNode.clientHeight;
	}
	wikEdTextareaOffsetHeightInitial = wikEdTextarea.offsetHeight;
	wikEdTextareaHeight = (wikEdTextarea.offsetHeight - wikEdTextareaBorderHeight) + 'px';
	wikEdTextareaWidth = '100%';

// remove frame border if textarea has none
	var styleFrameContainer = '';
	if (wikEdTextareaBorderHeight == 0) {
		styleFrameContainer = 'border-width: 0;';
	}

// setup the undo buffers and save the original text for local changes view
	wikEdOrigVersion = wikEdTextarea.value;

// Opera 0.9.51
	wikEdOrigVersion = wikEdOrigVersion.replace(/\r\n|\n\r|\r/g, '\n');

// add stylesheet definitions
	WikEdApplyCSS(document, wikEdMainEditCSS);

// get button settings from saved settings
	wikEdUsing = WikEdGetSavedSetting('wikEdSummaryUsing', wikEdUsingPreset);
	wikEdUseWikEd = ! WikEdGetSavedSetting('wikEdUseClassic', ! wikEdUseWikEdPreset);
	wikEdHighlightSyntax = ! WikEdGetSavedSetting('wikEdSyntaxOff', ! wikEdHighlightSyntaxPreset);
	wikEdFullScreenMode = WikEdGetSavedSetting('wikEdFullscreen', wikEdFullScreenModePreset);
	wikEdCloseToolbar = WikEdGetSavedSetting('wikEdCloseToolbar', wikEdCloseToolbarPreset);
	wikEdRefHide = WikEdGetSavedSetting('wikEdRefHide', wikEdRefHidePreset);
	wikEdDiff = WikEdGetSavedSetting('wikEdDiff', wikEdDiffPreset);
	wikEdTableMode = false;

// detect preview page
	if (window.location.search.match(/(\?|&)action=submit\b/) != null) {
		wikEdPreviewPage = true;
	}

// no fullscreen for preview and upload pages
	if ( (wikEdUploadEdit == true) || (wikEdPreviewPage == true) ) {
		wikEdFullScreenMode = false;
	}

// disable wikEd for Lupin's autoedit scripts
		if (window.location.search.match(/(\?|&)autoedit=/) != null) {
			wikEdUseWikEd = false;
		}

// disable wikEd for js pages
	if (/\.js$/.test(wikEdWikiGlobals['wgTitle']) == true) {
		if ( (wikEdWikiGlobals['wgCanonicalNamespace'] != 'User_talk') && (wikEdWikiGlobals['wgCanonicalNamespace'] != 'Talk') ) {
			wikEdNoSpellcheck = true;
			if (wikEdOrigVersion.length > 20000) {
				wikEdUseWikEd = false;
			}
			else {
				wikEdHighlightSyntax = false;
			}
		}
	}

// no highlighting for watchlist editing
	if (wikEdWatchlistEdit == true) {
		wikEdNoSpellcheck = true;
		wikEdHighlightSyntax = false;
	}

// disable spellchecker for textarea
	if (wikEdNoSpellcheck == true) {
		wikEdTextarea.setAttribute('spellcheck', false);
	}

// preset frame related styles to avoid browser crashes
	var styleFrameBody;
	var styleFrameWrapperPosition;
	var styleFrameWrapperVisibility;
	var styleTextareaWrapperPosition;
	var styleTextareaWrapperVisibility;
	if (wikEdUseWikEd == true) {
		styleFrameBody = 'display: block;';
		styleFrameWrapperPosition = 'static';
		styleFrameWrapperVisibility = 'visible';
		styleTextareaWrapperPosition = 'absolute';
		styleTextareaWrapperVisibility = 'hidden';
	}
	else {
		styleFrameBody = 'display: none;';
		styleFrameWrapperPosition = 'absolute';
		styleFrameWrapperVisibility = 'hidden';
		styleTextareaWrapperPosition = 'static';
		styleTextareaWrapperVisibility = 'visible';
	}
	var inputWrapperClass;
	if (wikEdFullScreenMode == true) {
		inputWrapperClass = 'wikEdInputWrapperFull';
	}
	else {
		inputWrapperClass = 'wikEdInputWrapper';
	}


// check if we use the wikEdEditor user interface of the vector skin

//	wikEdWikiEditor = document.getElementById('wikiEditor-ui');
	wikEdWikiEditor = WikEdGetElementsByClassName('wikiEditor-ui', 'div')[0];

	if (wikEdWikiEditor == null) {
		wikEdTextareaContainer = wikEdTextarea;
	}
	else {
		wikEdWikiEditorFrame = wikEdWikiEditor.getElementsByTagName('IFRAME')[0];
		wikEdWikiEditorTop = WikEdGetElementsByClassName('wikiEditor-ui-top', 'div')[0];
		wikEdWikiEditorBar = WikEdGetElementsByClassName('wikiEditor-ui-toolbar', 'div')[0];
		wikEdWikiEditorBottom = WikEdGetElementsByClassName('wikiEditor-ui-bottom', 'div')[0];
		wikEdWikiEditorText = WikEdGetElementsByClassName('wikiEditor-ui-text', 'div')[0];
		wikEdTextareaContainer = wikEdWikiEditor;
	}

// create input wrapper, contains the whole fullscreen content
	wikEdInputWrapper = document.createElement('div');
	wikEdInputWrapper.id = 'wikEdInputWrapper';
	wikEdInputWrapper.className = inputWrapperClass;
	wikEdTextareaContainer.parentNode.insertBefore(wikEdInputWrapper, wikEdTextareaContainer);

// create editor wrapper, contains toolbar, textarea, toc, but not the summary
	if (wikEdWikiEditor != null) {
		wikEdEditorWrapper = wikEdWikiEditor;
	}
	else {
		wikEdEditorWrapper = document.createElement('div');
		wikEdEditorWrapper.id = 'wikEdEditorWrapper';
		wikEdEditorWrapper.className = 'wikEdEditorWrapper';
	}
	wikEdInputWrapper.appendChild(wikEdEditorWrapper);

// create toolbar wrapper
	wikEdToolbarWrapper = document.createElement('div');
	wikEdToolbarWrapper.id = 'wikEdToolbarWrapper';
	wikEdToolbarWrapper.className = 'wikEdToolbarWrapper';
	wikEdEditorWrapper.appendChild(wikEdToolbarWrapper);

// create captcha wrapper
	var wikEdTextBoxTable;
	if (wikEdRearrange == true) {
		wikEdCaptchaWrapper = document.createElement('div');
		wikEdCaptchaWrapper.id = 'wikEdCaptchaWrapper';
		wikEdCaptchaWrapper.className = 'wikEdCaptchaWrapper';
		wikEdEditorWrapper.appendChild(wikEdCaptchaWrapper);

// fill captcha wrapper with elements between form and textarea (table)
		wikEdTextBoxTable = document.getElementById('textBoxTable');
		if ( (wikEdUploadEdit == false) && (wikEdWatchlistEdit == false) ) {
			var node = wikEdEditForm.firstChild;
			while (node != null) {
				if ( (node == wikEdInputWrapper) || (node == wikEdWikiEditor) ) {
					break;
				}
				var nextNode = node.nextSibling;
				wikEdCaptchaWrapper.appendChild(node);
				node = nextNode;
			}
		}
	}

// create debug textarea wrapper
	wikEdDebugWrapper = document.createElement('div');
	wikEdDebugWrapper.id = 'wikEdDebugWrapper';
	wikEdDebugWrapper.className = 'wikEdDebugWrapper';
	wikEdDebugWrapper.style.visibility = 'hidden';
	wikEdEditorWrapper.appendChild(wikEdDebugWrapper);

// create edit wrapper for textarea and frame wrapper
	wikEdEditWrapper = document.createElement('div');
	wikEdEditWrapper.id = 'wikEdEditWrapper';
	wikEdEditWrapper.className = 'wikEdEditWrapper';
	wikEdEditorWrapper.appendChild(wikEdEditWrapper);

// create textarea wrapper
	wikEdTextareaWrapper = document.createElement('div');
	wikEdTextareaWrapper.id = 'wikEdTextareaWrapper';
	wikEdTextareaWrapper.className = 'wikEdTextareaWrapper';
	wikEdTextareaWrapper.style.position = styleTextareaWrapperPosition;
	wikEdTextareaWrapper.style.visibility = styleTextareaWrapperVisibility;
	wikEdEditWrapper.appendChild(wikEdTextareaWrapper);

// create frame wrapper
	wikEdFrameWrapper = document.createElement('div');
	wikEdFrameWrapper.id = 'wikEdFrameWrapper';
	wikEdFrameWrapper.className = 'wikEdFrameWrapper';
	wikEdFrameWrapper.style.position = styleFrameWrapperPosition;
	wikEdFrameWrapper.style.visibility = styleFrameWrapperVisibility;
	wikEdEditWrapper.appendChild(wikEdFrameWrapper);

// create console wrapper for buttons, summary, and submit
	if (wikEdRearrange == true) {
		wikEdConsoleWrapper = document.createElement('div');
		wikEdConsoleWrapper.id = 'wikEdConsoleWrapper';
		wikEdConsoleWrapper.className = 'wikEdConsoleWrapper';
		wikEdInputWrapper.appendChild(wikEdConsoleWrapper);
	}

// create buttons wrapper for wikEd buttons
	if (wikEdWikiEditor != null) {
		wikEdButtonsWrapper = wikEdWikiEditorTop;
	}
	else {
		wikEdButtonsWrapper = document.createElement('div');
		wikEdButtonsWrapper.id = 'wikEdButtonsWrapper';
		wikEdButtonsWrapper.className = 'wikEdButtonsWrapper';
	}
	wikEdEditorWrapper.insertBefore(wikEdButtonsWrapper, wikEdEditWrapper);

// create summary wrapper for summary, minor edit, and watch this page
	if (wikEdRearrange == true) {
		wikEdSummaryWrapper = document.createElement('div');
		wikEdSummaryWrapper.id = 'wikEdSummaryWrapper';

// add summary above the edit field if we add a new section (+ tab)
		if (wikEdAddNewSection == true) {
			wikEdSummaryWrapper.className = 'wikEdSummaryWrapperTop';
			wikEdInputWrapper.insertBefore(wikEdSummaryWrapper, wikEdInputWrapper.firstChild);
		}
		else {
			wikEdSummaryWrapper.className = 'wikEdSummaryWrapper';
			wikEdConsoleWrapper.appendChild(wikEdSummaryWrapper);
		}

// create summary input wrapper
		wikEdSummaryInputWrapper = document.createElement('div');
		wikEdSummaryInputWrapper.id = 'wikEdSummaryInputWrapper';
		wikEdSummaryInputWrapper.className = 'wikEdSummaryInputWrapper';
		wikEdSummaryWrapper.appendChild(wikEdSummaryInputWrapper);

// create minor edit and watch page wrapper
		wikEdSummaryOptions = document.createElement('div');
		wikEdSummaryOptions.id = 'wikEdSummaryOptions';
		wikEdSummaryOptions.className = 'wikEdSummaryOptions';
		wikEdSummaryWrapper.appendChild(wikEdSummaryOptions);

// create submit wrapper for submit elements
		wikEdSubmitWrapper = document.createElement('div');
		wikEdSubmitWrapper.id = 'wikEdSubmitWrapper';
		wikEdSubmitWrapper.className = 'wikEdSubmitWrapper';
		wikEdConsoleWrapper.appendChild(wikEdSubmitWrapper);

// create submit buttons wrapper for submit buttons and help links
		wikEdSubmitButtonsWrapper = document.createElement('div');
		wikEdSubmitButtonsWrapper.id = 'wikEdSubmitButtonsWrapper';
		wikEdSubmitButtonsWrapper.className = 'wikEdSubmitButtonsWrapper';
		wikEdSubmitWrapper.appendChild(wikEdSubmitButtonsWrapper);
	}

// create preview wrapper for preview and diff box
	wikEdLocalPrevWrapper = document.createElement('div');
	wikEdLocalPrevWrapper.id = 'wikEdLocalPrevWrapper';
	wikEdLocalPrevWrapper.className = 'wikEdLocalPrevWrapper';
	wikEdLocalPrevWrapper.style.display = 'none';
	if (wikEdRearrange == true) {
		wikEdInputWrapper.appendChild(wikEdLocalPrevWrapper);
	}
	else {
		wikEdSaveButton.parentNode.appendChild(wikEdLocalPrevWrapper);
	}

// create insert wrapper for insert special chars links
	if (wikEdRearrange == true) {
		wikEdInsertWrapper = document.createElement('div');
		wikEdInsertWrapper.id = 'wikEdInsertWrapper';
		wikEdInsertWrapper.className = 'wikEdInsertWrapper';
		wikEdInputWrapper.appendChild(wikEdInsertWrapper);
	}

// append input wrapper to document
	if (wikEdRearrange == true) {
		wikEdEditForm.insertBefore(wikEdInputWrapper, wikEdEditForm.firstChild);
	}

// fill the wrappers

// create debug textarea and add to debug wrapper
	wikEdDebug = document.createElement('textarea');
	wikEdDebug.rows = 20;
	wikEdDebug.style.display = 'none';
	wikEdDebug.setAttribute('spellcheck', false);
	wikEdDebugWrapper.appendChild(wikEdDebug);

// display startup error messages
	if (wikEdDebugStartUp != '') {
		WikEdDebug(wikEdDebugStartUp);
	}

// wikEdDiff enhanced ajax diff
	if (typeof(wikEdDiffTable) == 'object') {
		if ( (wikEdDiffTable != null) && (wikEdDiff == true) ) {
			if (typeof(WikEdDiff) == 'function') {
				WikEdDiff();
			}
		}
	}

// add toolbar to toolbar wrapper
	wikEdToolbar = document.getElementById('toolbar');
	if (wikEdToolbar == null) {
		wikEdToolbar = wikEdWikiEditorBar;
	}
	if (wikEdCloseToolbar == true) {
		wikEdToolbarWrapper.style.display = 'none';
	}
	else {
		wikEdToolbarWrapper.style.display = 'block';
	}

// call wikibits:mwSetupToolbar() now because it would terminate with an error after setting textarea to display: none
	if (wikEdToolbar != null) {
		if (wikEdToolbar.getElementsByTagName('IMG').length == 0) {
			if (typeof(mwSetupToolbar) == 'function') {
				mwSetupToolbar();
				WikEdRemoveEventListener(window, 'load', mwSetupToolbar, false);
			}
		}
	}

// dropdowns from toolbar should go over wikEd toolbar
	if (wikEdWikiEditorBar != null) {
		wikEdWikiEditorBar.style.zIndex = '5';
	}

	var wpSummary = document.getElementsByName('wpSummary');
	if (wpSummary.length > 0) {
		wikEdEditOptions = wpSummary[0].parentNode;
		wikEdEditOptions.className = 'wikEdEditOptions';
	}

// add summary elements to summary input wrapper
	if (wikEdRearrange == true) {
		wikEdSummaryLabel = document.getElementById('wpSummaryLabel');
		if (wikEdSummaryLabel != null) {
			wikEdSummaryInputWrapper.appendChild(wikEdSummaryLabel);
		}
		wikEdSummaryText = document.getElementsByName('wpSummary')[0];
		wikEdSummaryInputWrapper.appendChild(wikEdSummaryText);
	}

// move editpage-copywarn out of summary wrapper
// needs to be done before appending editOptions to summary wrapper otherwise a linebreak stays (Mozilla bug)
	if (wikEdRearrange == true) {
		var copywarn = document.getElementById('editpage-copywarn');
		if (copywarn != null) {
			wikEdInputWrapper.parentNode.insertBefore(copywarn, wikEdInputWrapper.nextSibling);
		}
	}

// add submit buttons to submit wrapper
	if (wikEdRearrange == true) {
		var wpEditButtons = wikEdSaveButton.parentNode;
		wikEdSubmitWrapper.insertBefore(wpEditButtons, wikEdSubmitButtonsWrapper);
	}

// move edit options after submit buttons; crashes Mozilla when appended after filling the iframe
	wikEdDiffPreviewButton = document.getElementById('wpDiff');
	wikEdPreviewButton = document.getElementById('wpPreview');
	if (wikEdRearrange == true) {
		if (wikEdDiffPreviewButton != null) {
			wikEdDiffPreviewButton.parentNode.insertBefore(wikEdEditOptions, wikEdDiffPreviewButton.nextSibling);

// remove linebreak before minor edit checkbox
			var node = wikEdEditOptions.firstChild;
			while (node != null) {
				if (node.tagName != null) {
					if (node.tagName == 'BR') {
						node.parentNode.removeChild(node);
						break;
					}
				}
				node = node.nextSibling;
			}
		}
	}

// add textBoxTable or textarea to edit wrapper
	if (wikEdTextBoxTable != null) {
		wikEdTextareaWrapper.appendChild(wikEdTextBoxTable);
	}
	else if (wikEdWikiEditor != null) {
		wikEdTextareaWrapper.appendChild(wikEdWikiEditorBottom);
	}
	else {
		wikEdTextareaWrapper.appendChild(wikEdTextarea);
	}

// set frame font family
	var classFrameBody;
	if (wikEdHighlightSyntax == true) {
		if (wikEdRefHide == true) {
			classFrameBody = 'wikEdFrameBodyNewbie';
		}
		else {
			classFrameBody = 'wikEdFrameBodySyntax';
		}
	}
	else {
		classFrameBody = 'wikEdFrameBodyPlain';
	}

// add edit-frame to frame wrapper
// any DOM changes to a starting iframe in designmode may crash mozilla, including DOM move, display: none; and position: absolute;

// create the iframe
	var html = '';
	html += '<div id="wikEdFrameOuter" class="wikEdFrameOuter" style="' + styleFrameContainer + '">';
	html += '<div id="wikEdFrameInner" class="wikEdFrameInner" style="' + styleFrameContainer + '">';
	html += '<iframe id="wikEdFrame" class="wikEdFrame"></iframe>';
	html += '</div>';
	html += '</div>';
	wikEdFrameWrapper.innerHTML = html;

// old Mozilla versions crash when designmode is turned on before the frame has loaded completely
// but onload workaround has problems starting with Firefox 3.6 (bug 542727)
	var onloadWorkaround = false;
	if ( (wikEdMozilla == true) && (
		(wikEdBrowserFlavor == 'Firefox') && (wikEdBrowserVersion < 3.0) ||
		(wikEdBrowserFlavor == 'Netscape') && (wikEdBrowserVersion < 9.0) ||
		(wikEdBrowserFlavor == 'SeaMonkey') && (wikEdBrowserVersion < 2.0) ) ) {
		onloadWorkaround = true;
	}

// fill the frame with content
	html = '';
	html += '<html id="wikEdFrameHtml" class="wikEdFrameHtml"><head></head>';
	html += '<body id="wikEdFrameBody" class="' + classFrameBody + '" style="' + styleFrameBody + '"';

// disable spellchecker in iframe
	if (wikEdNoSpellcheck == true) {
		html += ' spellcheck="false"';
	}

	html += ' onload="var doc = window.document; doc.designMode = \'on\'; ';
	html += 'try { doc.execCommand(\'styleWithCSS\', 0, false); } catch (e) { ';
	html += 'try { doc.execCommand(\'useCSS\', 0, true); } catch (e) { ';
	html += 'try { doc.execCommand(\'styleWithCSS\', false, false); } catch (e) { } } }"';
	html += '></body></html>';

	wikEdFrameOuter = document.getElementById('wikEdFrameOuter');
	wikEdFrameInner = document.getElementById('wikEdFrameInner');
	wikEdFrame = document.getElementById('wikEdFrame');
	wikEdFrameWindow = wikEdFrame.contentWindow;
	wikEdFrameDocument = wikEdFrameWindow.document;

// set frame width, border divs shrink around
	wikEdFrameBorderHeight = parseInt(WikEdGetStyle(wikEdFrameOuter, 'borderTopWidth'), 10) + parseInt(WikEdGetStyle(wikEdFrameOuter, 'borderBottomWidth'), 10) + parseInt(WikEdGetStyle(wikEdFrameInner, 'borderTopWidth'), 10) + parseInt(WikEdGetStyle(wikEdFrameInner, 'borderBottomWidth'), 10);
	wikEdFrameBorderWidth = parseInt(WikEdGetStyle(wikEdFrameOuter, 'borderLeftWidth'), 10) + parseInt(WikEdGetStyle(wikEdFrameOuter, 'borderRightWidth'), 10) + parseInt(WikEdGetStyle(wikEdFrameInner, 'borderLeftWidth'), 10) + parseInt(WikEdGetStyle(wikEdFrameInner, 'borderRightWidth'), 10);

	wikEdFrameHeight = (wikEdTextareaOffsetHeightInitial - wikEdFrameBorderHeight) + 'px';
	wikEdFrameWidth = (wikEdEditorWrapper.clientWidth - wikEdFrameBorderWidth) + 'px';
	wikEdFrame.style.height = wikEdFrameHeight;
	wikEdFrame.style.width = wikEdFrameWidth;

// do not remember sie if started in fullscreen mode
	if (wikEdFullScreenMode == true) {
		wikEdFrameHeight = 0;
		wikEdFrameWidth = 0;
	}

// turn on designmode before adding content
	if (onloadWorkaround == false) {
		wikEdFrameDocument.designMode = 'on';
		try { wikEdFrameDocument.execCommand('styleWithCSS', 0, false); } catch (e) {
			try { wikEdFrameDocument.execCommand('useCSS', 0, true); } catch (e) {
				try { wikEdFrameDocument.execCommand('styleWithCSS', false, false); } catch (e) {
				}
			}
		}
	}

// MS-IE needs styling for full width frame
	if (wikEdMSIE == true) {
//////		wikEdFrame.style.width = wikEdTextareaWidth + 'px';// 100%
	}

// fill iframe with content
	wikEdFrameDocument.open();
	wikEdFrameDocument.write(html);
	wikEdFrameDocument.close();
	wikEdFrameBody = wikEdFrameDocument.body;

// generate button bars and add them to the buttons wrapper
// form wrapper has been added against summary input submit defaulting to this button
	wikEdButtonBarFormat = MakeButtonBar(wikEdButtonBar['format']);
	wikEdButtonsWrapper.appendChild(wikEdButtonBarFormat);

	wikEdButtonBarTextify = MakeButtonBar(wikEdButtonBar['textify']);
	wikEdButtonsWrapper.appendChild(wikEdButtonBarTextify);

	wikEdButtonBarControl = MakeButtonBar(wikEdButtonBar['control']);
	wikEdButtonsWrapper.appendChild(wikEdButtonBarControl);

	if (wikEdButtonBar['custom1'][6].length > 0) {
		wikEdButtonBarCustom1 = MakeButtonBar(wikEdButtonBar['custom1']);
		wikEdButtonsWrapper.appendChild(wikEdButtonBarCustom1);
	}

	wikEdButtonBarFind = MakeButtonBar(wikEdButtonBar['find']);
	wikEdButtonsWrapper.appendChild(wikEdButtonBarFind);

	wikEdButtonBarFix = MakeButtonBar(wikEdButtonBar['fix']);
	wikEdButtonsWrapper.appendChild(wikEdButtonBarFix);

	if (wikEdButtonBar['custom2'][6].length > 0) {
		wikEdButtonBarCustom2 = MakeButtonBar(wikEdButtonBar['custom2']);
		wikEdButtonsWrapper.appendChild(wikEdButtonBarCustom2);
	}

	var br = document.createElement('br');
	br.style.clear = 'both';
	wikEdButtonsWrapper.appendChild(br);

	wikEdCaseSensitive = document.getElementById('wikEdCaseSensitive');
	wikEdRegExp = document.getElementById('wikEdRegExp');
	wikEdFindAhead = document.getElementById('wikEdFindAhead');
	wikEdFindText = document.getElementById('wikEdFindText');
	wikEdReplaceText = document.getElementById('wikEdReplaceText');

// add preview box top bar to submit wrapper
	wikEdButtonBarPreview = MakeButtonBar(wikEdButtonBar['preview']);
	if (wikEdRearrange == true) {
		wikEdSubmitWrapper.insertBefore(wikEdButtonBarPreview, wikEdSubmitWrapper.firstChild);
	}

// add preview box and its bottom bar to preview wrapper
	if (wikEdLocalPrevWrapper != null) {
		var div = document.createElement('div');
		div.id = 'wikEdPreviewBoxOuter';
		div.className = 'wikEdPreviewBoxOuter';
		wikEdLocalPrevWrapper.appendChild(div);

		wikEdPreviewBox = document.createElement('div');
		wikEdPreviewBox.id = 'wikEdPreviewBox';
		wikEdPreviewBox.className = 'wikEdPreviewBox';
		div.appendChild(wikEdPreviewBox);

		wikEdButtonBarPreview2 = MakeButtonBar(wikEdButtonBar['preview2']);
		wikEdLocalPrevWrapper.appendChild(wikEdButtonBarPreview2);
	}

// add jump box to standard preview
	var wikiPreview = document.getElementById('wikiPreview');
	if (wikiPreview != null) {
		if (wikiPreview.firstChild != null) {
			wikEdButtonBarJump = MakeButtonBar(wikEdButtonBar['jump']);
			wikiPreview.insertBefore(wikEdButtonBarJump, wikiPreview.firstChild);
		}
	}

// add insert special chars to insert wrapper
	if (wikEdInsertWrapper != null) {
		var wpSpecialchars = document.getElementById('editpage-specialchars');
		if (wpSpecialchars != null) {
			wikEdInsertWrapper.appendChild(wpSpecialchars);
		}
	}

// wrappers filled

// add local preview button next to submit button
	wikEdLocalPreview = document.createElement('button');
	wikEdLocalPreview.id = 'wikEdLocalPreview';
	wikEdLocalPreview.title = wikEdText['wikEdLocalPreview title'];
	wikEdLocalPreview.className = 'wikEdLocalPreview';

	var localPreviewImg = document.createElement('img');
	localPreviewImg.id = 'wikEdLocalPreviewImg';
	localPreviewImg.src = wikEdImage['preview'];
	localPreviewImg.alt = wikEdText['wikEdLocalPreviewImg alt'];
	localPreviewImg.title = wikEdText['wikEdLocalPreview title'];
	wikEdLocalPreview.appendChild(localPreviewImg);

	if (wikEdPreviewButton != null) {
		wikEdPreviewButton.parentNode.insertBefore(wikEdLocalPreview, wikEdPreviewButton.nextSibling);
	}
	else {
		wikEdSaveButton.parentNode.insertBefore(wikEdLocalPreview, wikEdSaveButton.nextSibling);
	}

// add local diff button next to submit button
	if (wikEdDiffPreviewButton != null) {
		wikEdLocalDiff = document.createElement('button');
		wikEdLocalDiff.id = 'wikEdLocalDiff';
		wikEdLocalDiff.title = wikEdText['wikEdLocalDiff title'];
		wikEdLocalDiff.className = 'wikEdLocalDiff';

		var localDiffImg = document.createElement('img');
		localDiffImg.id = 'wikEdLocalDiffImg';
		localDiffImg.src = wikEdImage['diff'];
		localDiffImg.alt = wikEdText['wikEdLocalDiffImg alt'];
		localDiffImg.title = wikEdText['wikEdLocalDiff title'];

		wikEdLocalDiff.appendChild(localDiffImg);
		wikEdDiffPreviewButton.parentNode.insertBefore(wikEdLocalDiff, wikEdDiffPreviewButton.nextSibling);
	}

// correct tab order between check boxes and submits
	wikEdFrame.tabIndex = wikEdTextarea.tabIndex;

// initialize image buttons
	WikEdButton(document.getElementById('wikEdDiff'),            'wikEdDiff', null, wikEdDiff);
	WikEdButton(document.getElementById('wikEdRefHide'),         'wikEdRefHide', null, wikEdRefHide);
	WikEdButton(document.getElementById('wikEdHighlightSyntax'), 'wikEdHighlightSyntax', null, wikEdHighlightSyntax);
	WikEdButton(document.getElementById('wikEdUseWikEd'),        'wikEdUseWikEd', null, wikEdUseWikEd);
	WikEdButton(document.getElementById('wikEdCloseToolbar'),    'wikEdCloseToolbar', null, wikEdCloseToolbar);
	WikEdButton(document.getElementById('wikEdFullScreen'),      'wikEdFullScreen', null, wikEdFullScreenMode);
	WikEdButton(document.getElementById('wikEdUsing'),           'wikEdUsing', null, wikEdUsing);
	WikEdButton(document.getElementById('wikEdCaseSensitive'),   'wikEdCaseSensitive', null, false);
	WikEdButton(document.getElementById('wikEdRegExp'),          'wikEdRegExp', null, false);
	WikEdButton(document.getElementById('wikEdFindAhead'),       'wikEdFindAhead', null, wikEdFindAheadSelected);
	WikEdButton(document.getElementById('wikEdClose'),           'wikEdClose', null, false, 'wikEdButton');
	WikEdButton(document.getElementById('wikEdClose2'),          'wikEdClose2', null, false, 'wikEdButton');
	WikEdButton(document.getElementById('wikEdTableMode'),       'wikEdTableMode', null, wikEdTableMode);

// hide typo fix button until typo fix rules are loaded and parsed
	document.getElementById('wikEdFixRegExTypo').style.display = 'none';

// hide buttons if API is not available
	if (wikEdWikiGlobals['wgEnableAPI'] != 'true') {
		document.getElementById('wikEdFixRedirect').style.display = 'none';
	}

// add a clear summary button left to the summary input field
	if (wikEdSummaryText != null) {
		var clearSummaryForm = document.createElement('form');
		clearSummaryForm.id = 'wikEdClearSummaryForm';
		clearSummaryForm.className = 'wikEdClearSummaryForm';
		wikEdSummaryText.parentNode.insertBefore(clearSummaryForm, wikEdSummaryText);

		wikEdClearSummary = document.createElement('button');
		wikEdClearSummary.id = 'wikEdClearSummary';
		wikEdClearSummary.className = 'wikEdClearSummary';
		wikEdClearSummary.alt = wikEdText['wikEdClearSummary alt'];
		wikEdClearSummary.title = wikEdText['wikEdClearSummary title'];
		wikEdClearSummary.style.height = (wikEdSummaryText.clientHeight + 1) +'px';
		clearSummaryForm.appendChild(wikEdClearSummary);

		wikEdClearSummaryImg = document.createElement('img');
		wikEdClearSummaryImg.id = 'wikEdClearSummaryImg';
		wikEdClearSummaryImg.src = wikEdImage['clearSummary'];
		wikEdClearSummaryImg.alt = 'Clear summary';
		wikEdClearSummary.appendChild(wikEdClearSummaryImg);

// remember button width, might be without image
		wikEdClearSummaryWidth = wikEdClearSummary.offsetWidth;

// make the summary a combo box
		var summaryComboInput = document.createElement('span');
		summaryComboInput.id = 'wikEdSummaryComboInput';
		summaryComboInput.className = 'wikEdSummaryComboInput';
		summaryComboInput = wikEdSummaryText.parentNode.insertBefore(summaryComboInput, wikEdSummaryText);

		wikEdSummaryText = wikEdSummaryText.parentNode.removeChild(wikEdSummaryText);
		wikEdSummaryText.className = 'wikEdSummaryText';
		wikEdSummaryTextWidth = wikEdSummaryWrapper.offsetWidth - wikEdSummaryInputWrapper.offsetWidth;
		if (wikEdSummaryTextWidth < 150) {
			wikEdSummaryTextWidth = 150;
		}
		wikEdSummaryText.style.width = wikEdSummaryTextWidth + 'px';

		wikEdSummarySelect = document.createElement('select');
		wikEdSummarySelect.id = 'wikEdSummarySelect';
		wikEdSummarySelect.className = 'wikEdSummarySelect';

		summaryComboInput.appendChild(wikEdSummaryText);
		summaryComboInput.appendChild(wikEdSummarySelect);
	}

// shorten submit button texts
	if (wikEdPreviewButton != null) {
		wikEdPreviewButton.value = wikEdText['shortenedPreview'];
	}
	if (wikEdDiffPreviewButton != null) {
		wikEdDiffPreviewButton.value = wikEdText['shortenedChanges'];
	}

// set up combo input boxes with history
	wikEdFieldHist ['find'] = [];
	wikEdSavedName['find'] = 'wikEdFindHistory';
	wikEdInputElement['find'] = new Object(wikEdFindText);
	wikEdSelectElement['find'] = new Object(document.getElementById('wikEdFindSelect'));
	wikEdSelectElement['find'].title = wikEdText['wikEdFindSelect title'];

	wikEdFieldHist ['replace'] = [];
	wikEdSavedName['replace'] = 'wikEdReplaceHistory';
	wikEdInputElement['replace'] = new Object(wikEdReplaceText);
	wikEdSelectElement['replace'] = new Object(document.getElementById('wikEdReplaceSelect'));
	wikEdSelectElement['replace'].title = wikEdText['wikEdReplaceSelect title'];

	if (wikEdSummaryInputWrapper != null) {
		wikEdFieldHist ['summary'] = [];
		wikEdSavedName['summary'] = 'wikEdSummaryHistory';
		wikEdInputElement['summary'] = new Object(wikEdSummaryText);
		wikEdSelectElement['summary'] = new Object(document.getElementById('wikEdSummarySelect'));
		wikEdSelectElement['summary'].title = wikEdText['wikEdSummarySelect title'];
	}

// adjust the select field widths to that of the text input fields
	WikEdResizeComboInput('find');
	WikEdResizeComboInput('replace');
	WikEdResizeComboInput('summary');

// hide the button bars per saved setting
	WikEdButtonBarInit(wikEdButtonBarFormat);
	WikEdButtonBarInit(wikEdButtonBarTextify);
	WikEdButtonBarInit(wikEdButtonBarControl);
	if (wikEdButtonBarCustom1 != null) {
		WikEdButtonBarInit(wikEdButtonBarCustom1);
	}
	WikEdButtonBarInit(wikEdButtonBarFind);
	WikEdButtonBarInit(wikEdButtonBarFix);
	if (wikEdButtonBarCustom2 != null) {
		WikEdButtonBarInit(wikEdButtonBarCustom2);
	}

// display only the textarea or the iframe, dont change the frame
	WikEdSetEditArea(wikEdUseWikEd, true);

// add a link to the wikEd help page
	if (wikEdRearrange == true) {
		if ( (wikEdHelpPageLink != '') && (wikEdHelpPageLink != null) ) {
			var editHelpParent = wikEdDiffPreviewButton;
			while (editHelpParent != null) {
				if (editHelpParent.tagName == 'SPAN') {
					break;
				}
				editHelpParent = editHelpParent.nextSibling;
			}

			if (editHelpParent != null) {
				var editHelp = editHelpParent.lastChild;
				while (editHelp != null) {
					if (editHelp.tagName == 'A') {
						break;
					}
					editHelp = editHelp.previousSibling;
				}

				if (editHelp != null) {
					var wikEdHelpSpan = document.createElement('span');
					wikEdHelpSpan.id = 'wikEdHelpSpan';
					wikEdHelpSpan.className = 'wikEdHelpSpan';
					wikEdHelpSpan.innerHTML = wikEdHelpPageLink.replace(/\{wikEdHomeBaseUrl\}/g, wikEdHomeBaseUrl);
					editHelpParent.insertBefore(wikEdHelpSpan, editHelp.nextSibling);

					wikEdEditHelp = wikEdHelpSpan.parentNode;
					wikEdEditHelp.id = 'wikEdEditHelp';
					wikEdEditHelp.className = 'wikEdEditHelp';
				}
			}
		}
	}

// copy page warnings above edit window
	if (wikEdDoCloneWarnings == true) {
		if ( (wikEdClonedWarnings == false) && (wikEdPreviewPage == false) && (/(.*\n){2}/.test(wikEdOrigVersion) ) == true) {
			var divs = document.getElementsByTagName('div');
			var divWarnings = [];
			var editnoticeArea = false;
			for (var i = 0; i < divs.length; i ++) {
				var div = divs[i];
				if (/editnotice/.test(div.id) == true) {
					if (editnoticeArea == false) {
						divWarnings.push(div);
						editnoticeArea = true;;
					}
				}
				else if (/mw-.*?warning/.test(div.className) == true) {
					divWarnings.push(div);
				}
			}
			for (var i = 0; i < divWarnings.length; i ++) {
				var clone = divWarnings[i].cloneNode(true);
				wikEdEditForm.insertBefore(clone, wikEdEditForm.firstChild);
			}
			wikEdClonedWarnings = true;
		}
	}

// add frame stylesheet definition
	wikEdDirection = WikEdGetStyle(document.body, 'direction');
	wikEdFrameBody.style.direction = wikEdDirection;
	WikEdApplyCSS(wikEdFrameDocument, wikEdFrameCSS);
	WikEdHighlightNamedHideButtonsStylesheet = new WikEdStyleSheet(wikEdFrameDocument);

// copy textarea background
	if (WikEdGetStyle(wikEdTextarea, 'display') != 'none') {
		wikEdFrameInner.style.backgroundColor = WikEdGetStyle(wikEdTextarea, 'backgroundColor');
	}

// adjust font size (px)
	wikEdTextSizeInit = parseFloat(WikEdGetStyle(wikEdTextarea, 'fontSize')) * wikEdTextSizeAdjust / 100;
	wikEdTextSize = wikEdTextSizeInit;
	wikEdFrameBody.style.fontSize = wikEdTextSize + 'px';

// copy the textarea content to the iframe
	if (wikEdUseWikEd == true) {
		WikEdUpdateFrame();
	}

// initialize IERange DOM range compatibility library
	if (typeof(IERange) == 'function') {
		IERange(wikEdFrameWindow, wikEdFrameDocument);
	}

// scroll to edit window and focus if it is not a preview page
	if ( (scrollToEditFocus == true) && (wikEdPreviewPage == false) ) {

// focus the input field
		if (wikEdFocusEdit == true) {
			if (wikEdUseWikEd == true) {
				wikEdFrameWindow.focus();
			}
			else {
				if (wikEdMSIE == true) {
					wikEdTextarea.selection.empty();
				}
				else {
					wikEdTextarea.setSelectionRange(0, 0);
				}
				wikEdTextarea.focus();
			}
		}

// scroll
		if ( (wikEdFullScreenMode == false) && (wikEdScrollToEdit == true) ) {
			window.scroll(0, WikEdGetOffsetTop(wikEdEditForm) - 2);
		}
	}

// register edit button click events
	for (var buttonId in wikEdEditButtonHandler) {
		if (wikEdEditButtonHandler.hasOwnProperty(buttonId) == true) {
			var buttonObj = document.getElementById(buttonId);
			if (buttonObj != null) {
				WikEdAddEventListener(buttonObj, 'click', WikEdEditButtonHandler, true);
			}
		}
	}

// register summary shrinking event after loading the 'Clear summary' image handler
	WikEdAddEventListener(wikEdClearSummaryImg, 'load', WikEdShrinkSummaryHandler, true);

// register summary resize event for window resizing (MS IE bug: fires once always)
	WikEdAddEventListener(window, 'resize', WikEdResizeWindowHandler, true);

// register frame events
	WikEdAddEventListener(wikEdFrameDocument, 'keydown', WikEdKeyFrameHandler, true);
	WikEdAddEventListener(wikEdFrameDocument, 'keyup', WikEdKeyFrameHandler, true);
	WikEdAddEventListener(wikEdFrameDocument, 'keypress', WikEdKeyFrameHandler, true);
	WikEdAddEventListener(wikEdFrameDocument, 'mouseup', WikEdKeyFrameHandler, true);
	WikEdAddEventListener(wikEdFrameDocument, 'keydown', WikEdKeyHandler, true);
	WikEdAddEventListener(wikEdFrameDocument, 'mousemove', WikEdResizeGripHandler, true);
	WikEdAddEventListener(wikEdFrameDocument, 'dblclick', WikEdResizeFrameResetHandler, true);

// register document events
	WikEdAddEventListener(document, 'keydown', WikEdKeyHandler, true);

// dblclick on wrapper events
	WikEdAddEventListener(wikEdDebugWrapper, 'dblclick', WikEdDebugHandler, true);
	WikEdAddEventListener(wikEdLocalPrevWrapper, 'dblclick', WikEdPrevWrapperHandler, true);

// register find ahead events
	WikEdAddEventListener(wikEdFindText, 'keyup', WikEdFindAhead, true);

// register submit button events
	WikEdAddEventListener(wikEdSaveButton, 'click', WikEdSaveButtonHandler, true);
	WikEdAddEventListener(wikEdPreviewButton, 'click', WikEdPreviewButtonHandler, true);
	WikEdAddEventListener(wikEdDiffPreviewButton, 'click', wikEdDiffPreviewButtonHandler, true);
	WikEdAddEventListener(wikEdLocalPreview, 'click', WikEdLocalPreviewHandler, true);
	WikEdAddEventListener(wikEdLocalDiff, 'click', WikEdLocalDiffHandler, true);

// unload (leaving page) events
	WikEdAddEventListener(window, 'pagehide', WikEdUnloadHandler, false);

// set button bar grip area events
	WikEdAddEventListener(wikEdButtonBarFormat.firstChild.firstChild, 'click', WikEdButtonBarGripHandler, false);
	WikEdAddEventListener(wikEdButtonBarTextify.firstChild.firstChild, 'click', WikEdButtonBarGripHandler, false);
	WikEdAddEventListener(wikEdButtonBarControl.firstChild.firstChild, 'click', WikEdButtonBarGripHandler, false);
	if (wikEdButtonBarCustom1 != null) {
		if (wikEdButtonBarCustom1.firstChild.firstChild != null) {
			WikEdAddEventListener(wikEdButtonBarCustom1.firstChild.firstChild, 'click', WikEdButtonBarGripHandler, false);
		}
	}
	WikEdAddEventListener(wikEdButtonBarFind.firstChild.firstChild, 'click', WikEdButtonBarGripHandler, false);
	WikEdAddEventListener(wikEdButtonBarFix.firstChild.firstChild, 'click', WikEdButtonBarGripHandler, false);
	if (wikEdButtonBarCustom2 != null) {
		if (wikEdButtonBarCustom2.firstChild.firstChild != null) {
			WikEdAddEventListener(wikEdButtonBarCustom2.firstChild.firstChild, 'click', WikEdButtonBarGripHandler, false);
		}
	}

// register combo box events
	WikEdAddEventListener(wikEdSummarySelect, 'change', function() { WikEdChangeComboInput('summary'); }, false);
	WikEdAddEventListener(wikEdSummarySelect, 'focus', function() { WikEdSetComboOptions('summary'); }, false);

	WikEdAddEventListener(wikEdSelectElement['find'],'change', function() { WikEdChangeComboInput('find'); }, false);
	WikEdAddEventListener(wikEdSelectElement['find'],'focus', function() { WikEdSetComboOptions('find'); }, false);

	WikEdAddEventListener(wikEdSelectElement['replace'],'change', function() { WikEdChangeComboInput('replace'); }, false);
	WikEdAddEventListener(wikEdSelectElement['replace'],'focus', function() { WikEdSetComboOptions('replace'); }, false);

// register the clear summary click handler
	WikEdAddEventListener(wikEdClearSummary, 'click', WikEdClearSummaryHandler, true);

// select the text on focus for find and replace fields
	WikEdAddEventListener(wikEdFindText, 'focus', WikEdFindReplaceHandler, true);
	WikEdAddEventListener(wikEdReplaceText, 'focus', WikEdFindReplaceHandler, true);

// tab / shift-tab between find and replace fields
	WikEdAddEventListener(wikEdFindText, 'keydown', WikEdFindReplaceHandler, true);
	WikEdAddEventListener(wikEdReplaceText, 'keydown', WikEdFindReplaceHandler, true);

// init MediaWiki file paths for use in regexps
	if (wikEdWikiGlobals['wgServer'] != null) {
		wikEdServer = wikEdWikiGlobals['wgServer'];
	}
	if (wikEdWikiGlobals['wgArticlePath'] != null) {
		wikEdArticlePath = wikEdWikiGlobals['wgArticlePath'];
	}
	if (wikEdWikiGlobals['wgScriptPath'] != null) {
		wikEdScriptPath = wikEdWikiGlobals['wgScriptPath'];
	}
	if (wikEdWikiGlobals['wgScript'] != null) {
		wikEdScript = wikEdWikiGlobals['wgScript'];
	}

	wikEdArticlePath = wikEdArticlePath.replace(wikEdServer, '');
	wikEdScriptPath = wikEdScriptPath.replace(wikEdServer, '');
	wikEdArticlePath = wikEdArticlePath.replace(/\$1$/, '');
	wikEdScriptPath = wikEdScriptPath.replace(/\/?$/, '/');
	wikEdScriptName = wikEdScript.replace(wikEdScriptPath, '');
	wikEdScriptURL = wikEdServer + wikEdScriptPath;

// prepare for use in regexps
	wikEdServer = wikEdServer.replace(/(\W)/g, '\\$1');
	wikEdArticlePath = wikEdArticlePath.replace(/(\W)/g, '\\$1');
	wikEdScript = wikEdScript.replace(/(\W)/g, '\\$1');
	wikEdScriptPath = wikEdScriptPath.replace(/(\W)/g, '\\$1');
	wikEdScriptName = wikEdScriptName.replace(/(\W)/g, '\\$1');

// check if dynamically inserted addon tags have to be removed: Web of Trust (WOT)
	if (document.getElementById('wot-logo') != null) {
		wikEdCleanNodes = true;
	}

// fullscreen mode
	if (wikEdFullScreenMode == true) {
		WikEdFullScreen(wikEdFullScreenMode, true);
	}

// override the insertTags function in wikibits.js used by the standard button toolbar and the editpage special chars
	if (typeof(insertTags) == 'function') {
		if (WikEdInsertTagsOriginal == null) {
			WikEdInsertTagsOriginal = insertTags;
		}
		insertTags = window.WikEdInsertTags;
	}

// hook wikEd into the enhanced new edit toolbar
	if (typeof(jQuery) == 'function') {
		jQuery('#wpTextbox1').bind('encapsulateSelection', function(e, before, inside, after) {
			if (wikEdUseWikEd == true) {
				WikEdInsertTags(before, after, inside);
			}
		});
	}

// override insertAtCursor function in wikia.com MediaWiki:Functions.js
	if (typeof(insertAtCursor) == 'function') {
		if (WikEdInsertAtCursorOriginal == null) {
			WikEdInsertAtCursorOriginal = insertAtCursor;
		}
		insertAtCursor = window.WikEdInsertAtCursor;
	}

// reset error indicator
	WikEdSetLogo();
	wikEdTurnedOn = true;

// get frame resize grip image dimensions
	var resizeGripImage = document.createElement('img');
	resizeGripImage.id = 'wikEdResizeGrip';
	WikEdAddEventListener(resizeGripImage, 'load', WikEdResizeGripLoadHandler, true);
	resizeGripImage.src = wikEdImage['resizeGrip'];

// run scheduled custom functions
	WikEdExecuteHook(wikEdSetupHook);

// load and parse RegExTypoFix rules if the button is enabled
	WikEdLoadTypoFixRules();

// done with setup and turn-on
	return;
};


//
// WikEdAutoUpdate: check for the latest version and force-reload to update
//

window.WikEdAutoUpdate = function() {

// check only on non-interaction pages
	if (/(\?|&)action=/.test(window.location.search) == true) {
		return;
	}

// check if autoupdate is enabled
	if (wikEdAutoUpdate != true) {
		return;
	}

// check for forced update check
	var forcedUpdate = false;
	if (wikEdForcedUpdate != '') {

// get version numbers from strings
		var currentVersion = WikEdVersionToNumber(wikEdProgramVersion);
		var forcedVersion = WikEdVersionToNumber(wikEdForcedUpdate);

// schedule forced update check
		if ( (currentVersion != null) && (forcedVersion != null) ) {
			if (forcedVersion > currentVersion) {
				forcedUpdate = true;
			}
		}
	}

// check for regular update
	var regularUpdate = false;
	var currentDate = new Date();
	if (forcedUpdate == false) {

// get date of last update check
		var lastCheckStr = WikEdGetPersistent('wikEdAutoUpdate');
		var lastCheckDate = new Date(lastCheckStr);

// fix missing or corrupt saved setting
		if (isNaN(lastCheckDate.valueOf()) == true) {
			WikEdSetPersistent('wikEdAutoUpdate', 'January 1, 1970', 0, '/');
			return;
		}

// get the hours since last update check
		var diffHours = (currentDate - lastCheckDate) / 1000 / 60 / 60;
		if (wikEdGreasemonkey == true) {
			if (diffHours > wikEdAutoUpdateHoursGM) {
				regularUpdate = true;
			}
		}
		else if (diffHours > wikEdAutoUpdateHours) {
			regularUpdate = true;
		}
	}

// perform AJAX request to get latest version number
	if ( (forcedUpdate == true) || (regularUpdate == true) ) {

// save current update check date
		WikEdSetPersistent('wikEdAutoUpdate', currentDate.toUTCString(), 0, '/');

// make the ajax request
		WikEdAjaxRequest('GET', wikEdAutoUpdateUrl, null, null, null, null, function(ajax) {

// get response
			var html = ajax.responseText;

// get version numbers from strings
			var currentVersion = WikEdVersionToNumber(wikEdProgramVersion);
			var newVersion = WikEdVersionToNumber(html);

// check if downloaded version is newer and perform update
			if ( (currentVersion != null) && (newVersion != null) ) {
				if (newVersion > currentVersion) {
					WikEdDoUpdate();
				}
			}
		});
	}
	return;
};

//
// WikEdVersionToNumber: parse version string (1.22.333a) into number 122333097
//

window.WikEdVersionToNumber = function(versionStr) {

	var ver = versionStr.match(/(\d+)\.(\d+)\.(\d+)(\w?)/);
	if (ver == null) {
		return;
	}
	var versionNumber = Number(ver[1]) * 100000000 + Number(ver[2]) * 1000000 + Number(ver[3]) * 1000 + (ver[4] + '0').charCodeAt(0);

	return(versionNumber);
};


//
// WikEdDoUpdate: actually perform update
//

window.WikEdDoUpdate = function() {

// update Greasemonkey script by navigating to the script code page
	if (wikEdGreasemonkey == true) {
		var updatePopup = wikEdText['wikEdGreasemonkeyAutoUpdate'];
		updatePopup = updatePopup.replace(/\{updateURL\}/g, wikEdAutoUpdateUrl);
		alert(updatePopup);
		window.location.href = wikEdAutoUpdateScriptUrl;
	}

// update wikEd by reloading the page with cache bypassing (equivalent to Shift-Reload or Shift-F5)
	else {
		window.location.reload(true);
	}
	return;
};


//
// WikEdLoadTypoFixRules: load and parse RegExTypoFix rules if the button is enabled
//

window.WikEdLoadTypoFixRules = function() {

// load RegExTypoFix rules per Ajax if enabled
	if ( (wikEdRegExTypoFix == true) && (wikEdTypoRulesFind.length == 0) ) {

// make the ajax request
		WikEdAjaxRequest('GET', wikEdRegExTypoFixURL, null, null, null, null, function(ajax) {

// get response
			var rulesTxt = ajax.responseText;

// parse regexp rules
			var regExp = new RegExp('^<Typo +word="(.+?)" +find="(.+?)" +replace="(.+?)" +/>', 'gim');
			while ( (regExpMatch = regExp.exec(rulesTxt)) != null) {

// check if this is a valid regexp
				var regExpFind;
				try {
					regExpFind = new RegExp(regExpMatch[2], 'gm');
				}
				catch (err) {
					continue;
				}

// save regexp and replace
				wikEdTypoRulesFind.push(regExpFind);
				wikEdTypoRulesReplace.push(regExpMatch[3]);
			}

// display typo fix button
			if (wikEdTypoRulesFind.length > 0) {
				document.getElementById('wikEdFixRegExTypo').style.display = 'inline';
			}
			return;
		});
	}
	return;
};


//
// WikEdEditButtonHandler: handler for clicks on edit buttons
//

window.WikEdEditButtonHandler = function(event) {

// event compatibility fixes
	event = WikEdEvent(event, this);
	if (event == null) {
		return;
	}

// execute the button click handler code
	var obj;
	if (event.currentTarget != null) {
		obj = event.currentTarget;
	}

// MS IE compatibility
	else {
		obj = event.srcElement;
	}

// workaround for mozilla 3.0 bug 441087
	objId = obj.id;
	eventShiftKey = event.shiftKey;

	eval(wikEdEditButtonHandler[objId]);
	return;
};


//
// WikEdShrinkSummaryHandler: shrink the summary after loading the 'Clear summary' image
//

window.WikEdShrinkSummaryHandler = function(event) {

	var diffWidth = wikEdClearSummary.offsetWidth - wikEdClearSummaryWidth;

// Firefox < 3.0
	if ( typeof(wikEdInputElement['summary'].clientLeft) == 'undefined' ) {
		wikEdInputElement['summary'].style.width = (wikEdInputElement['summary'].clientWidth - diffWidth) + 'px';
		wikEdSelectElement['summary'].style.width = (wikEdSelectElement['summary'].clientWidth - diffWidth) + 'px';
	}

// Firefox >= 3.0
	else {
		wikEdInputElement['summary'].style.width = (wikEdInputElement['summary'].clientWidth - diffWidth) + 'px';
		wikEdSelectElement['summary'].style.width = (wikEdSelectElement['summary'].clientWidth - diffWidth + 3) + 'px';
	}
	wikEdClearSummaryWidth = wikEdClearSummary.offsetWidth;
	return;
};


//
// WikEdResizeWindowHandler: adjust the summary width after resizing the window
//

window.WikEdResizeWindowHandler = function(event) {

// adjust frame size
	wikEdFrameHeight = (wikEdTextareaOffsetHeightInitial - wikEdFrameBorderHeight) + 'px';
	wikEdFrameWidth = (wikEdEditorWrapper.clientWidth - wikEdFrameBorderWidth) + 'px';
	wikEdFrame.style.height = wikEdFrameHeight;
	wikEdFrame.style.width = wikEdFrameWidth;

	WikEdResizeSummary();
	return;
};


//
// WikEdUnloadHandler: save editing frame to cached textarea
//

window.WikEdUnloadHandler = function(event) {

// event compatibility fixes
	event = WikEdEvent(event, this);
	if (event == null) {
		return;
	}

// update textarea if not already done in submit handlers
	if (wikEdUseWikEd == true) {
		if (wikEdTextareaUpdated != true) {
			WikEdUpdateTextarea();
		}
	}
	return;
};


//
// WikEdSaveButtonHandler: 'Save page' onsubmit click handler for submit button
//

window.WikEdSaveButtonHandler = function(event) {

	WikEdRemoveEventListener(wikEdSaveButton, 'click', WikEdSaveButtonHandler, true);

// update textarea
	if (wikEdUseWikEd == true) {
		WikEdUpdateTextarea();
		wikEdTextareaUpdated = true;
	}

// check for interfering scripts or gadgets: mwEmbed for file uploads
	if ( (wikEdUploadEdit == true) && (typeof(MW_EMBED_VERSION) != 'undefined') ) {
		WikEdAddEventListener(wikEdSaveButton, 'click', WikEdSaveButtonHandler, true);
		return;
	}

// add "using wikEd" to summary, not for adding a new section (+ tab)
	if (wikEdSummaryText != null) {
		var text = wikEdSummaryText.value;
		text = text.replace(/^[, ]+/, '');
		text = text.replace(/[, ]+$/, '');
		WikEdAddToHistory('summary');

		if ( (wikEdUsing == true) && (text != '') ) {
			if (text.lastIndexOf(wikEdSummaryUsing) < 0) {
				if (wikEdAddNewSection != true) {
					text += ' ' + wikEdSummaryUsing;
				}
			}
		}
		wikEdSummaryText.value = text;
	}

// submit
	wikEdSaveButton.click();

// reinstate handler in case the browser back button will be used
	WikEdAddEventListener(wikEdSaveButton, 'click', WikEdSaveButtonHandler, true);

	return;
};


//
// WikEdPreviewButtonHandler: 'Show preview' click handler
//

window.WikEdPreviewButtonHandler = function(event) {

	if (wikEdUseWikEd == true) {
		WikEdUpdateTextarea();
		wikEdTextareaUpdated = true;
	}

	return;
};


//
// wikEdDiffPreviewButtonHandler: 'Show changes' click handler
//

window.wikEdDiffPreviewButtonHandler = function(event) {

	if (wikEdFullScreenMode == true) {
		WikEdFullScreen(false);
	}
	if (wikEdUseWikEd == true) {
		WikEdUpdateTextarea();
		wikEdTextareaUpdated = true;
	}

	return;
};


//
// WikEdLinkifyHandler: open innermost highlighted link in new window/tab on ctrl/meta-click
//

window.WikEdLinkifyHandler = function(event) {

// event compatibility fixes
	event = WikEdEvent(event, this);
	if (event == null) {
		return;
	}

	if ( (event.shiftKey == false) && ( (event.ctrlKey == true) || (event.metaKey == true) ) && (event.altKey == false) ) {
		var node = event.target;
		while (node != null) {
			var linkId = node.id;
			if (linkId != null) {
				if (linkId.indexOf('wikEdLinkify') == 0) {
					var linkIdNo = linkId.replace(/\D/g, '');
					var linkUrl = wikEdLinkifyArray[linkIdNo];
					if (linkUrl != null) {
						event.stopPropagation();
						window.open(linkUrl);
						window.focus();
						break;
					}
				}
			}
			node = node.parentNode;
		}
	}
	return;
};


//
// WikEdLocalPreviewHandler: local 'Show preview' image button click handler
//

window.WikEdLocalPreviewHandler = function(event) {

// event compatibility fixes
	event = WikEdEvent(event, this);
	if (event == null) {
		return;
	}

	event.preventDefault();
	WikEdButton(wikEdLocalPreview, 'wikEdLocalPreview');
	return;
};


//
// WikEdLocalDiffHandler: local 'Show changes' image button click handler
//

window.WikEdLocalDiffHandler = function(event) {

// event compatibility fixes
	event = WikEdEvent(event, this);
	if (event == null) {
		return;
	}

	event.preventDefault();
	WikEdButton(wikEdLocalDiff, 'wikEdLocalDiff');
	return;
};


//
// WikEdButtonBarGripHandler: click, mouseover handler, see also WikEdButtonBarInit()
//

window.WikEdButtonBarGripHandler = function(event) {

// event compatibility fixes
	event = WikEdEvent(event, this);
	if (event == null) {
		return;
	}

	event.stopPropagation();
	var grip = event.target;
	var gripWrapper = grip.parentNode;
	var buttonsWrapper = gripWrapper.nextSibling;
	var barInnerWrapper = gripWrapper.parentNode;
	var bar = barInnerWrapper.parentNode;

	if (event.type == 'click') {
		buttonsWrapper.style.position = 'static';

// hide the buttons bar
		if (buttonsWrapper.minimized != true) {
			barInnerWrapper.className = 'wikEdButtonBarInnerWrapperHidden';
			gripWrapper.className = 'wikEdButtonBarGripWrapperHidden';
			buttonsWrapper.className = 'wikEdButtonBarButtonsWrapperHidden';
			buttonsWrapper.widthOriginal = buttonsWrapper.offsetWidth;
			buttonsWrapper.style.display = 'none';
			buttonsWrapper.minimized = true;
			WikEdAddEventListener(grip, 'mouseover', WikEdButtonBarGripHandler, false);
			WikEdSetPersistent(bar.id + 'Hidden', '1', 0, '/');
		}

// unhide the buttons bar
		else {
			barInnerWrapper.className = 'wikEdButtonBarInnerWrapperVisible';
			gripWrapper.className = 'wikEdButtonBarGripWrapperVisible';
			buttonsWrapper.className = 'wikEdButtonBarButtonsWrapperVisible';
			buttonsWrapper.style.display = 'block';
			buttonsWrapper.minimized = false;
			WikEdRemoveEventListener(grip, 'mouseover', WikEdButtonBarGripHandler, false);
			WikEdSetPersistent(bar.id + 'Hidden', '0', 0, '/');
		}
	}

// show the buttons bar on mouseover
	else if (event.type == 'mouseover') {
		if (buttonsWrapper.minimized == true) {
			WikEdAddEventListener(bar, 'mouseout', WikEdButtonBarHandler, false);

// show buttons to the right
			if (bar.offsetParent.clientWidth > grip.offsetLeft + grip.offsetWidth + buttonsWrapper.widthOriginal) {
				buttonsWrapper.style.left = (grip.offsetLeft + grip.offsetWidth) + 'px';
			}

// show buttons to the left
			else {
				buttonsWrapper.style.left = (gripWrapper.offsetLeft - buttonsWrapper.widthOriginal) + 'px';
			}

// a mozilla bug sometimes gives offsetTop - 1 when the wikEdToolbarWrapper is hidden
			buttonsWrapper.style.top = gripWrapper.offsetTop + 'px';
			buttonsWrapper.style.position = 'absolute';
			buttonsWrapper.style.display = 'block';
		}
	}
	return;
};


//
// WikEdButtonBarHandler: mouseout handler
//

window.WikEdButtonBarHandler = function(event) {

// event compatibility fixes
	event = WikEdEvent(event, this);
	if (event == null) {
		return;
	}

	event.stopPropagation();

	var bar = event.currentTarget;
	var barInnerWrapper = bar.firstChild;
	var gripWrapper = barInnerWrapper.firstChild;
	var grip = gripWrapper.firstChild;
	var buttonsWrapper = gripWrapper.nextSibling;
	var buttons = buttonsWrapper.firstChild;

// hide the buttons
	if (event.type == 'mouseout') {
		if (buttonsWrapper.minimized == true) {

// filter the events for mouseouts actually leaving the bar
			if (
				(
					( (event.target == grip) || (event.target == gripWrapper) ) &&
					(event.safeRelatedTarget != gripWrapper) && (event.safeRelatedTarget != buttonsWrapper) && (event.safeRelatedTarget != buttons) && (event.safeRelatedTarget.parentNode != buttons)
				) ||
				(
					( (event.target.parentNode.parentNode == buttons) || (event.target.parentNode == buttons) || (event.target == buttons) || (event.target == buttonsWrapper) ) &&
					(event.safeRelatedTarget.parentNode.parentNode != buttons) && (event.safeRelatedTarget.parentNode != buttons) && (event.safeRelatedTarget != buttons) && (event.safeRelatedTarget != buttonsWrapper) && (event.safeRelatedTarget != gripWrapper) && (event.safeRelatedTarget != grip)
				)
			) {
				WikEdRemoveEventListener(bar, 'mouseout', WikEdButtonBarHandler, false);
				buttonsWrapper.style.display = 'none';
				buttonsWrapper.style.position = 'static';
			}
		}
	}
	return;
};


//
// clear the summary click handler
//

window.WikEdClearSummaryHandler = function(event) {

// event compatibility fixes
	event = WikEdEvent(event, this);
	if (event == null) {
		return;
	}

	event.preventDefault();

// clear the summary if it is only a paragraph name
	if ( /^\/\* .*? \*\/ *$/.test(wikEdSummaryText.value) == true) {
		wikEdSummaryText.value = '';
	}

// clear the summary but leave paragraph names
	else {
		wikEdSummaryText.value = wikEdSummaryText.value.replace(/^((\/\* .*? \*\/ *)?).*()/,
			function (p, p1, p2) {
				if (p1.length > 0) {
					p1 = p1 + ' ';
				}
				return(p1);
			}
		);
	}
	wikEdSummaryText.focus();
	return;
};


//
// WikEdFindReplaceHandler: find and replace: tab and shift-tab between fields, select on focus
//

window.WikEdFindReplaceHandler = function(event) {

// event compatibility fixes
	event = WikEdEvent(event, this);
	if (event == null) {
		return;
	}

// tab / shift-tab between fields
	if (event.type == 'keydown') {
		if (event.keyCode == 9) {
			if (event.target == wikEdFindText) {
				event.preventDefault();
				WikEdRemoveEventListener(wikEdReplaceText, 'focus', WikEdFindReplaceHandler, true);
				wikEdReplaceText.focus();
				WikEdAddEventListener(wikEdReplaceText, 'focus', WikEdFindReplaceHandler, true);
			}
			else if (event.target == wikEdReplaceText) {
				event.preventDefault();
				WikEdRemoveEventListener(wikEdFindText, 'focus', WikEdFindReplaceHandler, true);
				wikEdFindText.focus();
				WikEdAddEventListener(wikEdFindText, 'focus', WikEdFindReplaceHandler, true);
			}
		}
	}

// select on focus
	else if (event.type == 'focus') {
		if (wikEdMSIE == true) {

		}
		else {
			event.target.setSelectionRange(0, this.textLength);
		}
	}
	return;
};


//
// WikEdKeyFrameHandler: event handler for key and mouse events in the frame
//

window.WikEdKeyFrameHandler = function(event) {

// event compatibility fixes
	event = WikEdEvent(event, this);
	if (event == null) {
		return;
	}

	if (wikEdUseWikEd == true) {
		switch (event.type) {

// keypress event
			case 'keypress':

// actual characters and return
				if ( (event.charCode > 0) || (event.keyCode == 13) ) {

// set cursor position into closest highest text node so that highlighting does not bleed out
					var obj = {};
					WikEdAntiHighlightBleeding(obj);
				}
				break;

// keydown event
			case 'keydown':
				switch (event.keyCode) {

// tab key, switch between form elements instead of adding multiple spaces
					case 9:
						if ( (event.shiftKey == false) && (event.ctrlKey == false) && (event.altKey == false) && (event.metaKey == false) ) {
							event.preventDefault();

// focus the next form element
							if (wikEdAddNewSection == true) {
								document.getElementById('wpMinoredit').focus();
							}
							else {
								wikEdSummaryText.focus();
							}

// scroll to text input top
							if (wikEdFullScreenMode == false) {
								window.scroll(0, WikEdGetOffsetTop(wikEdInputWrapper));
							}
						}
						break;
				}
				break;

// trap any other frame event
			case 'keyup':
			case 'keypress':
			case 'mouseup':

// grey out inactive buttons
				WikEdInactiveButtons();
		}
	}
	return;
};


//
// set cursor position into closest highest text node so that highlighting does not bleed out
//   does not work under Google Chrome which forces the cursor into the previous node
//

window.WikEdAntiHighlightBleeding = function(obj) {

// check if disabled
	if (wikEdAntiHighlightBleeding != true) {
		return;
	}

// get selection object
	if (obj.sel == null) {
		obj.sel = WikEdGetSelection();
	}

// only if no text is selected
	var range = obj.sel.getRangeAt(obj.sel.rangeCount - 1);
	if ( (obj.sel.isCollapsed != true) || (range.collapsed != true) ) {
		return;
	}

// correct focusNode to actual (deepest) node
	var	focusNode = obj.sel.focusNode;
	var focusOffset = obj.sel.focusOffset;
	if (focusNode == null) {
		return;
	}
	if (focusNode.childNodes != null) {
		if ( (focusNode.childNodes.length > 0) && (focusOffset < focusNode.childNodes.length) ) {
			focusNode = focusNode.childNodes.item(focusOffset);
			focusOffset = 0;
		}
	}

// do not correct if focus is linebreak
	if (focusNode.tagName == 'BR') {
		return;
	}

// get next text-like node to the left if we are not in the middle of a text node
	var leftNode = focusNode;
	var leftLevel = 0;
	var objLeft = {
		'backwards': true
	};
	if ( (focusNode.nodeName != '#text') || (focusOffset == 0) ) {
		WikEdGetNextTextNode(objLeft, focusNode, 0);
	}
	if (objLeft.foundNode != null) {
		leftNode = objLeft.foundNode;
		leftLevel = objLeft.foundLevel;
	}

// get next text-like node to the right if we are not in the middle of a text node
	var objRight = {
		'backwards': false
	};
	var rightNode = focusNode;
	var rightLevel = 0;
	if ( (focusNode.nodeName != '#text') || (focusOffset == focusNode.textContent.length) ) {
		WikEdGetNextTextNode(objRight, focusNode, 0);
	}
	if (objRight.foundNode != null) {
		rightNode = objRight.foundNode;
		rightLevel = objRight.foundLevel;
	}

// check if we need to correct the focus node to higher text-like node
	var correctTo = '';
	if (leftNode != rightNode) {
		if ( (leftLevel > rightLevel) && (leftNode != focusNode) ) {
			correctTo = 'left';
		}
		else if ( (leftLevel < rightLevel) && (rightNode != focusNode) ) {
			correctTo = 'right';
		}

// same level, set focus outside tag markups [ [[ | || <
		else if (leftLevel == rightLevel) {

// get class names
			var leftClass = '';
			if (leftNode.nodeName == '#text') {
				leftClass = leftNode.parentNode.className;
			}
			else {
				leftClass = leftNode.className;
			}

			var rightClass = '';
			if (rightNode.nodeName == '#text') {
				rightClass = rightNode.parentNode.className;
			}
			else {
				rightClass = rightNode.className;
			}

			if ( (/Tag/.test(leftClass) != true) && (/Tag/.test(rightClass) == true) && (leftNode != focusNode) ) {
				correctTo = 'left';
			}
			else if ( (/Tag/.test(leftClass) == true) && (/Tag/.test(rightClass) != true) && (rightNode != focusNode) ) {
				correctTo = 'right';
			}
		}
	}

// set focus to the next left node
	if (correctTo == 'left') {
		var node = leftNode;
		if (node.nodeName == '#text') {
			range.setStart(node, node.textContent.length);
			range.setEnd(node, node.textContent.length);
		}
		else {
			range.setStartAfter(node);
			range.setEndAfter(node);
		}
	}

// set focus to the next right node
	else if (correctTo == 'right') {
		var node = rightNode;
		if (node.nodeName == '#text') {
			range.setStart(node, 0);
			range.setEnd(node, 0);
		}
		else {
			range.setStartBefore(node);
			range.setEndBefore(node);
		}
	}
	return;
}


//
// WikEdResizeGripLoadHandler: event handler to determine grip background image size
//

window.WikEdResizeGripLoadHandler = function(event) {

// event compatibility fixes
	event = WikEdEvent(event, this);
	if (event == null) {
		return;
	}

	wikEdResizeGripWidth = event.currentTarget.width;
	wikEdResizeGripHeight = event.currentTarget.height;
	return;
};


//
// WikEdResizeGripHandler: event handler for mouse over resize grip background image
//

window.WikEdResizeGripHandler = function(event) {

// prevent console errors about undefined functions and variables (Firefox bug)
	if (typeof(WikEdEvent) == 'undefined') {
		return;
	}

// Firefox bug during startup ("WikEdEvent is not defined")
	if (typeof(WikEdEvent) != 'function' ) {
		return;
	}

// event compatibility fixes
	event = WikEdEvent(event, this);
	if (event == null) {
		return;
	}

	if (wikEdUseWikEd == true) {
		if (event.type == 'mousemove') {
			if ( (event.shiftKey == false) && (event.ctrlKey == false) && (event.altKey == false) && (event.metaKey == false) ) {

// move into grip
				if (wikEdResizeFrameMouseOverGrip == false) {
					if (event.clientY >= wikEdFrameBody.clientHeight - wikEdResizeGripHeight) {
						if (event.clientX >= wikEdFrameBody.clientWidth - wikEdResizeGripWidth) {
							if ( (event.clientY < wikEdFrameBody.clientHeight) && (event.clientX < wikEdFrameBody.clientWidth) ) {
								wikEdResizeFrameMouseOverGrip = true;
								if (wikEdFullScreenMode == true) {
									wikEdFrameBody.style.cursor = 'alias';
								}
								else {
									WikEdAddEventListener(wikEdFrameDocument, 'mousedown', WikEdResizeStartHandler, true);
									wikEdFrameBody.style.cursor = 'move';
								}
							}
						}
					}
				}

// move out of grip
				else if (wikEdResizeFrameActive == false) {
					if (
						(event.clientY < wikEdFrameBody.clientHeight - wikEdResizeGripHeight) ||
						(event.clientX < wikEdFrameBody.clientWidth - wikEdResizeGripWidth)
					) {
						wikEdResizeFrameMouseOverGrip = false;
						WikEdRemoveEventListener(wikEdFrameDocument, 'mousedown', WikEdResizeStartHandler, true);
						wikEdFrameBody.style.cursor = 'auto';
					}
				}
			}
		}
	}
	return;
};


//
// WikEdResizeStartHandler: event handler to start the resizing of the editing frame
//

window.WikEdResizeStartHandler = function(event) {

// event compatibility fixes
	event = WikEdEvent(event, this);
	if (event == null) {
		return;
	}

	if (wikEdUseWikEd == true) {
		if ( (event.type == 'mousedown') && (event.button == 0) ) {
			if ( (event.shiftKey == false) && (event.ctrlKey == false) && (event.altKey == false) && (event.metaKey == false) ) {
				if (event.clientY >= wikEdFrameBody.clientHeight - wikEdResizeGripHeight) {
					if (event.clientX >= wikEdFrameBody.clientWidth - wikEdResizeGripWidth) {
						if ( (event.clientY < wikEdFrameBody.clientHeight) && (event.clientX < wikEdFrameBody.clientWidth) ) {
							event.preventDefault();
							wikEdResizeFrameActive = true;

							wikEdResizeFramePageYStart = event.pageY;
							wikEdResizeFramePageXStart = event.pageX;

							wikEdResizeFrameOffsetHeight = wikEdFrame.offsetHeight;
							wikEdResizeFrameOffsetWidth = wikEdFrame.offsetWidth;
							WikEdAddEventListener(wikEdFrameDocument, 'mouseup', WikEdResizeStopHandler, true);
							WikEdAddEventListener(document, 'mouseup', WikEdResizeStopHandler, true);
							WikEdAddEventListener(wikEdFrameDocument, 'mousemove', WikEdResizeDragHandlerFrame, true);
							WikEdAddEventListener(document, 'mousemove', WikEdResizeDragHandlerDocument, true);
						}
					}
				}
			}
		}
	}
	return;
};


//
// WikEdResizeStopHandler: event handler to stop the resizing of the editing frame
//

window.WikEdResizeStopHandler = function(event) {

// event compatibility fixes
	event = WikEdEvent(event, this);
	if (event == null) {
		return;
	}

	if (wikEdUseWikEd == true) {
		if (event.type == 'mouseup') {
			WikEdRemoveEventListener(wikEdFrameDocument, 'mouseup', WikEdResizeStopHandler, true);
			WikEdRemoveEventListener(document, 'mouseup', WikEdResizeStopHandler, true);
			WikEdRemoveEventListener(wikEdFrameDocument, 'mousemove', WikEdResizeDragHandlerFrame, true);
			WikEdRemoveEventListener(document, 'mousemove', WikEdResizeDragHandlerDocument, true);

			if (
				(event.clientY < wikEdFrameBody.clientHeight - wikEdResizeGripHeight) ||
				(event.clientX < wikEdFrameBody.clientWidth - wikEdResizeGripWidth)
			) {
				wikEdResizeFrameMouseOverGrip = false;
				WikEdRemoveEventListener(wikEdFrameDocument, 'mousedown', WikEdResizeStartHandler, true);
				wikEdFrameBody.style.cursor = 'auto';
			}
		}
		wikEdResizeFrameActive = false;
	}
	return;
};


//
// WikEdResizeDragHandlerFrame: event handler for editing frame resizing by mouse dragging (frame event)
//

window.WikEdResizeDragHandlerFrame = function(event) {

// event compatibility fixes
	event = WikEdEvent(event, this);
	if (event == null) {
		return;
	}

	if (event.type == 'mousemove') {
		var diffY = event.pageY - wikEdResizeFramePageYStart;
		var diffX = event.pageX - wikEdResizeFramePageXStart;

		var frameHeightNew = wikEdResizeFrameOffsetHeight + diffY;
		var frameWidthNew = wikEdResizeFrameOffsetWidth + diffX;

		if (frameHeightNew >=  100) {
			wikEdFrameHeight = frameHeightNew + 'px';
			wikEdFrame.style.height = wikEdFrameHeight;
		}
		if (frameWidthNew >=  100) {
			wikEdFrameWidth = frameWidthNew + 'px';
			wikEdFrame.style.width = wikEdFrameWidth;
		}
	}
	return;
};


//
// WikEdResizeDragHandlerDocument: event handler for editing frame resizing by mouse dragging (document event)
//

window.WikEdResizeDragHandlerDocument = function(event) {

// event compatibility fixes
	event = WikEdEvent(event, this);
	if (event == null) {
		return;
	}

	if (event.type == 'mousemove') {
		var diffY = event.pageY - wikEdResizeFramePageYStart - WikEdGetOffsetTop(wikEdFrame);
		var diffX = event.pageX - wikEdResizeFramePageXStart - WikEdGetOffsetLeft(wikEdFrame);

		var frameHeightNew = wikEdResizeFrameOffsetHeight + diffY;
		var frameWidthNew = wikEdResizeFrameOffsetWidth + diffX;

		if (frameHeightNew >=  100) {
			wikEdFrameHeight = frameHeightNew + 'px';
			wikEdFrame.style.height = wikEdFrameHeight;
		}
		if (frameWidthNew >=  100) {
			wikEdFrameWidth = frameWidthNew + 'px';
			wikEdFrame.style.width = wikEdFrameWidth;
		}
	}
	return;
};


//
// WikEdResizeFrameResetHandler: event handler to reset the editing frame size
//

window.WikEdResizeFrameResetHandler = function(event) {

// event compatibility fixes
	event = WikEdEvent(event, this);
	if (event == null) {
		return;
	}

	if (wikEdUseWikEd == true) {
		if (event.type == 'dblclick') {
			if ( (event.shiftKey == false) && (event.ctrlKey == false) && (event.altKey == false) && (event.metaKey == false) ) {
				if (event.clientY > wikEdFrameBody.clientHeight - wikEdResizeGripHeight) {
					if (event.clientX > wikEdFrameBody.clientWidth - wikEdResizeGripWidth) {
						if ( (event.clientY < wikEdFrameBody.clientHeight) && (event.clientX < wikEdFrameBody.clientWidth) ) {

// end fullscreen mode
							if (wikEdFullScreenMode == true) {
								WikEdFullScreen(false);
							}

// reset size to default
							wikEdFrameHeight = (wikEdTextareaOffsetHeightInitial - wikEdFrameBorderHeight) + 'px';
							wikEdFrameWidth = (wikEdEditorWrapper.clientWidth - wikEdFrameBorderWidth) + 'px';
							wikEdFrame.style.height = wikEdFrameHeight;
							wikEdFrame.style.width = wikEdFrameWidth;

// end resizing
							WikEdRemoveEventListener(wikEdFrameDocument, 'mouseup', WikEdResizeStopHandler, true);
							WikEdRemoveEventListener(document, 'mouseup', WikEdResizeStopHandler, true);
							WikEdRemoveEventListener(wikEdFrameDocument, 'mousemove', WikEdResizeDragHandlerFrame, true);
							WikEdRemoveEventListener(document, 'mousemove', WikEdResizeDragHandlerDocument, true);
							wikEdResizeFrameMouseOverGrip = false;
							WikEdRemoveEventListener(wikEdFrameDocument, 'mousedown', WikEdResizeStartHandler, true);
							wikEdFrameBody.style.cursor = 'auto';
							wikEdResizeFrameActive = false;
						}
					}
				}
			}
		}
	}
	return;
};


//
// WikEdDebugHandler: event handler to clear or hide the debug textarea on (shift/ctrl/alt) double click
//

window.WikEdDebugHandler = function(event) {

	if ( (event.shiftKey == true) || (event.ctrlKey == true) || (event.altKey == true) || (event.metaKey == true) ) {
		wikEdDebugWrapper.style.visibility = 'hidden';
		wikEdDebug.style.display = 'none';
		wikEdDebugOpen = false;
	}
	else {
		wikEdDebug.value = '';
	}
	return;
};


//
// WikEdPrevWrapperHandler: event handler to close preview / diff box on double click
//

window.WikEdPrevWrapperHandler = function(event) {

	wikEdLocalPrevWrapper.style.display = 'none';
	return;
};


//
// WikEdSetLogo: set the logo on top of the page
//

window.WikEdSetLogo = function(state, parameter) {

	if (state == 'error') {
		wikEdLogo.src = wikEdImage['error'];
		wikEdLogo.alt = wikEdText['wikEdLogo error alt'];
		wikEdLogo.title = wikEdText['wikEdLogo error title'];
	}
	else if (state == 'browser') {
		wikEdLogo.src = wikEdImage['browser'];
		wikEdLogo.alt = wikEdText['wikEdLogo browser alt'];
		wikEdLogo.title = wikEdText['wikEdLogo browser title'];
	}
	else if (state == 'incompatible') {
		wikEdLogo.src = wikEdImage['incompatible'];
		wikEdLogo.alt = wikEdText['wikEdLogo incompatible alt'];
		wikEdLogo.title = wikEdText['wikEdLogo incompatible title'];
	}
	else {
		if (wikEdDisabled == true) {
			wikEdLogo.src = wikEdImage['disabled'];
			wikEdLogo.alt = wikEdText['wikEdLogo disabled alt'];
			wikEdLogo.title = wikEdText['wikEdLogo disabled title'];
		}
		else {
			wikEdLogo.src = wikEdImage['logo'];
			wikEdLogo.alt = wikEdText['wikEdLogo alt'];
			wikEdLogo.title = wikEdText['wikEdLogo title'];
		}
	}
	var version = wikEdProgramVersion;
	if (wikEdGadget == true) {
		version += ' G';
	}
	else if (wikEdGreasemonkey == true) {
		version += ' GM';
	}
	else if (wikEdGreasemonkeyToHead == true) {
		version += ' GM';
	}
	wikEdLogo.title = wikEdLogo.title.replace(/\{wikEdParameter\}/g, parameter);
	wikEdLogo.title = wikEdLogo.title.replace(/\{wikEdProgramVersion\}/g, version);
	wikEdLogo.title = wikEdLogo.title.replace(/\{wikEdProgramDate\}/g, wikEdProgramDate);

	return;
};


//
// MakeButtonBar: generate button bar div element
//

window.MakeButtonBar = function(bar) {

// id outer, class outer, id inner, class inner, alt, button numbers
	var barId = bar[0];
	var barClass = bar[1];
	var buttonsId = bar[2];
	var buttonsClass = bar[3];
	var barHeight = bar[4];
	var gripTitle = bar[5];
	var buttonNumbers = bar[6];

// collect the buttons
	var buttons = '';
	for (var i = 0; i < buttonNumbers.length; i ++) {
		var buttonNo = buttonNumbers[i];
		switch (buttonNo) {
			case 'br':
				buttons += '<br>';
				break;
			case 'find':
				buttons += '<span class="wikEdFindComboInput" id="wikEdFindComboInput">';
				buttons += '<input class="wikEdCombo" id="wikEdFindText" type="text" value="">';
				buttons += '<select class="wikEdCombo" id="wikEdFindSelect">';
				buttons += '</select>';
				buttons += '</span>';
				break;
			case 'replace':
				buttons += '<span class="wikEdReplaceComboInput" id="wikEdReplaceComboInput">';
				buttons += '<input class="wikEdCombo" id="wikEdReplaceText" type="text" value="">';
				buttons += '<select class="wikEdCombo" id="wikEdReplaceSelect">';
				buttons += '</select>';
				buttons += '</span>';
				break;
			default:
				var currButton = wikEdButton[buttonNo];
				if (typeof(currButton) != 'object') {
					alert('Loading error: The button "' + buttonNumbers[i] + '" is not defined.');
				}
				if ( (currButton[0] == 'wikEdSource') && (wikEdShowSourceButton != true) ) {
					break;
				}
				else if ( (currButton[0] == 'wikEdUsing') && (wikEdShowUsingButton != true) ) {
					break;
				}
				else if ( (currButton[0] == 'wikEdTableMode') && (wikEdShowTableModeButton != true) ) {
					break;
				}

// add accesskey information to button title and
				var accessKey = '';
				if (wikEdButtonKey[buttonNo] != null) {
					accessKey = ' [' + wikEdText['alt-shift'] + wikEdButtonKey[buttonNo][0] + ']';

// initialize wikEdButtonKeyCode[keyCode] = id
					wikEdButtonKeyCode[ (wikEdButtonKey[buttonNo][1]) ] = currButton[0];
				}

// add button html code
				buttons += '<img id="' + currButton[0] + '" class="' + currButton[1] + '" title="' + currButton[2] + accessKey +'" src="' + currButton[3] + '" width="' + currButton[4] + '" height="' + currButton[5] + '" alt="' + currButton[6] + '">';

// collect click event info
				wikEdEditButtonHandler[ currButton[0] ] = currButton[7];
		}
	}

// create the button bar div
	var div = document.createElement('div');
	div.id = barId;
	div.className = barClass;

	var buttonsStyle = '';
	if (barHeight > 0) {
		buttonsStyle = ' style="height: ' + barHeight + 'px;"';
	}

// make a grip bar
	var html = '';
	if (gripTitle != null) {
		var gripStyle = 'width: ' + wikEdButtonBarGripWidth + 'px; ';
		if (barHeight > 0) {
			gripStyle += 'height: ' + barHeight + 'px; ';
		}
		if (gripStyle.length > 0){
			gripStyle = ' style="' + gripStyle + '"';
		}

		html += '<div class="wikEdButtonBarInnerWrapperVisible" style="height: ' + barHeight + 'px;">';

		html += '<div class="wikEdButtonBarGripWrapperVisible">';
		html += '<div class="wikEdButtonBarGrip"' + gripStyle + ' title="' + gripTitle + '">';
		html += '&nbsp;';
		html += '</div>';
		html += '</div>';

		html += '<div class="wikEdButtonBarButtonsWrapperVisible"' + buttonsStyle + '>';
		html += '<div id="' + buttonsId + '" class="' + buttonsClass + '" style="">';
		html += buttons;
		html += '</div>';
		html += '</div>';

		html += '</div>';
	}

// make a standard no-grip bar
	else {
		html += '<div id="' + buttonsId + '" class="' + buttonsClass + '"' + buttonsStyle + '">';
		html += buttons;
		html += '</div>';
	}
	div.innerHTML = html;

	return(div);
};


//
// WikEdButtonBarInit: hide buttons bar, see also WikEdButtonBarGripHandler()
//

window.WikEdButtonBarInit = function(bar) {

	if (WikEdGetPersistent(bar.id + 'Hidden') == '1') {
		var barInnerWrapper = bar.firstChild;
		var gripWrapper = barInnerWrapper.firstChild;
		var grip = gripWrapper.firstChild;
		var buttonsWrapper = gripWrapper.nextSibling;

		barInnerWrapper.className = 'wikEdButtonBarInnerWrapperHidden';
		gripWrapper.className = 'wikEdButtonBarGripWrapperHidden';
		buttonsWrapper.className = 'wikEdButtonBarButtonsWrapperHidden';
		buttonsWrapper.widthOriginal = buttonsWrapper.offsetWidth;
		buttonsWrapper.style.display = 'none';
		WikEdAddEventListener(grip, 'mouseover', WikEdButtonBarGripHandler, true);
	}
	return;
};


//
// WikEdSetEditArea: apply css changes to switch between classic textarea and rich text frame
//

window.WikEdSetEditArea = function(useFrame, notFrame) {

	var scrollRatio;

// turn rich text frame on
	if (useFrame == true) {
		scrollRatio = wikEdTextarea.scrollTop / wikEdTextarea.scrollHeight;

// remember resized textarea dimensions
		wikEdTextareaHeight = (wikEdTextarea.offsetHeight - wikEdTextareaBorderHeight) + 'px';
		wikEdTextareaWidth = '100%';

		wikEdTextareaWrapper.style.position = 'absolute';
		wikEdTextareaWrapper.style.visibility = 'hidden';
		wikEdTextarea.style.display = 'none';

		if (notFrame != true) {
			wikEdFrameWrapper.style.position = 'static';
			wikEdFrameWrapper.style.visibility = 'visible';
			wikEdFrameBody.style.display = 'block';
		}

		if (wikEdToolbar != null) {
			if (wikEdCloseToolbar == true) {
				wikEdToolbarWrapper.style.display = 'none';
			}
			else {
				wikEdToolbarWrapper.style.display = 'block';
			}
		}
		wikEdButtonBarFormat.style.display = 'block';
		wikEdButtonBarTextify.style.display = 'block';
		if (wikEdButtonBarCustom1 != null) {
			wikEdButtonBarCustom1.style.display = 'block';
		}
		wikEdButtonBarFind.style.display = 'block';
		wikEdButtonBarFix.style.display = 'block';
		if (wikEdButtonBarCustom2 != null) {
			wikEdButtonBarCustom2.style.display = 'block';
		}
		wikEdButtonBarControl.style.display = 'block';
		wikEdFrameBody.scrollTop = scrollRatio * wikEdFrameBody.scrollHeight;
	}

// turn classic textarea on
	else {
		scrollRatio = wikEdFrameBody.scrollTop / wikEdFrameBody.scrollHeight;
		if (notFrame != true) {

// get resized frame dimensions for textarea
			if (wikEdUseWikEd == true) {
				wikEdTextareaHeight = wikEdFrameHeight;
				wikEdTextareaWidth = '100%';
			}
			wikEdFrameWrapper.style.position = 'absolute';
			wikEdFrameWrapper.style.visibility = 'hidden';
// Mozilla or wikEd bug: <br> insertion before text a while after setting display to 'none', test with setTimeout('alert(wikEdFrameBody.innerHTML)', 1000);
//			wikEdFrameBody.style.display = 'none';
		}
		wikEdTextareaWrapper.style.position = 'static';
		wikEdTextareaWrapper.style.visibility = 'visible';

		wikEdTextarea.style.height = wikEdTextareaHeight;
		wikEdTextarea.style.width = wikEdTextareaWidth;
		wikEdTextarea.style.display = 'block';

		if (wikEdToolbar != null) {
			wikEdToolbarWrapper.style.display = 'block';
		}
		wikEdButtonBarFormat.style.display = 'none';
		wikEdButtonBarTextify.style.display = 'none';
		if (wikEdButtonBarCustom1 != null) {
			wikEdButtonBarCustom1.style.display = 'none';
		}
		wikEdButtonBarFind.style.display = 'none';
		wikEdButtonBarFix.style.display = 'none';
		if (wikEdButtonBarCustom2 != null) {
			wikEdButtonBarCustom2.style.display = 'none';
		}
		wikEdButtonBarControl.style.display = 'block';
		wikEdTextarea.scrollTop = scrollRatio * wikEdTextarea.scrollHeight;
	}

	return;
};


//
// WikEdButton: toggle or set button checked state
//   used for buttons that do not require nor change the text. Faster than WikEdEditButton()
//

window.WikEdButton = function(buttonObj, buttonId, toggleButton, setButton, classButton, doButton) {

	if (buttonObj != null) {

// check if the button is disabled
		if (buttonObj.className == 'wikEdButtonInactive') {
			return;
		}

// set button to pressed, set cursor to hourglass
		buttonObj.style.cursor = 'wait';

// init the button
		if (setButton != null) {
			if (setButton == false) {
				buttonObj.setAttribute('checked', false);
				if (classButton == null) {
					buttonObj.className = 'wikEdButtonUnchecked';
				}
			}
			else {
				buttonObj.setAttribute('checked', true);
				if (classButton == null) {
					buttonObj.className = 'wikEdButtonChecked';
				}
			}
		}
		else if (classButton != null) {
			buttonObj.className = classButton;
		}

// toggle the button
		if (toggleButton != null) {
			if (toggleButton == true) {
				if (WikEdGetAttribute(buttonObj, 'checked') == 'true') {
					buttonObj.setAttribute('checked', false);
					buttonObj.className = 'wikEdButtonUnchecked';
				}
				else {
					buttonObj.setAttribute('checked', true);
					buttonObj.className = 'wikEdButtonChecked';
				}
			}
		}
	}

// perform specific actions
	var focusFrame = false;
	if ( ( (setButton == null) && (classButton == null) ) || (doButton == true) ) {

// remove active content
		WikEdRemoveElements(['script', 'object', 'applet', 'embed']);

		switch (buttonId) {

// switch between syntax highlighting and plain text
			case 'wikEdHighlightSyntax':
				if (WikEdGetAttribute(buttonObj, 'checked') == 'true') {
					wikEdHighlightSyntax = true;
					WikEdSetPersistent('wikEdSyntaxOff', '0', 0, '/');
					if (wikEdRefHide == true) {
						wikEdFrameBody.className = 'wikEdFrameBodyNewbie';
					}
					else {
						wikEdFrameBody.className = 'wikEdFrameBodySyntax';
					}
				}
				else {
					wikEdHighlightSyntax = false;
					WikEdSetPersistent('wikEdSyntaxOff', '1', 0, '/');
					wikEdFrameBody.className = 'wikEdFrameBodyPlain';
				}

// do not keep whole text selected
				WikEdEditButton( null, 'wikEdUpdateAll', {'keepSel': false} );
				break;

// toggle table mode // {{TABLE}}
			case 'wikEdTableMode':
				if (WikEdGetAttribute(buttonObj, 'checked') != 'true') {
					wikEdTableMode = false;
					WikEdEditButton(null, 'wikEdUpdateAll');
				}
				else {
					wikEdTableMode = true;
					WikEdEditButton(null, 'wikEdTablify');
				}
				break;

// align textbox with display top
			case 'wikEdScrollToPreview':
			case 'wikEdScrollToPreview2':
			case 'wikEdScrollToPreview3':
				window.scroll(0, WikEdGetOffsetTop(wikEdSaveButton));
				focusFrame = true;
				break;

// align edit buttons with display top
			case 'wikEdScrollToEdit':
			case 'wikEdScrollToEdit2':
			case 'wikEdScrollToEdit3':
			case 'wikEdScrollToEdit4':
				window.scroll(0, WikEdGetOffsetTop(wikEdInputWrapper));
				focusFrame = true;
				break;

// cycle through different font sizes
			case 'wikEdTextZoomDown':
				wikEdTextSize = wikEdTextSize / 1.2;
				if (wikEdTextSize < wikEdTextSizeInit / 1.2 / 1.2) {
					wikEdTextSize = wikEdTextSizeInit * 1.2 * 1.2;
				}
				wikEdFrameBody.style.fontSize = wikEdTextSize + 'px';
				focusFrame = true;
				break;

// cycle through different font sizes
			case 'wikEdTextZoomUp':
				wikEdTextSize = wikEdTextSize * 1.2;
				if (wikEdTextSize > wikEdTextSizeInit * 1.2 * 1.2) {
					wikEdTextSize = wikEdTextSizeInit / 1.2 / 1.2;
				}
				wikEdFrameBody.style.fontSize = wikEdTextSize + 'px';
				focusFrame = true;
				break;

// display local preview box
			case 'wikEdLocalPreview':
				if (wikEdFullScreenMode == true) {
					WikEdFullScreen(false);
				}
				if (wikEdUseWikEd == true) {
					WikEdUpdateTextarea();
				}

// clear box to display loading indicator, keep wrapper height to prevent scrolling
				var previewHeight = wikEdPreviewBox.offsetHeight;
				if ( (wikEdPreviewBox.innerHTML != '') && (previewHeight > 0) ) {
					wikEdPreviewBox.style.height = previewHeight + 'px';
				}
				wikEdPreviewBox.innerHTML = wikEdText['wikEdPreviewLoading'];
				wikEdLocalPrevWrapper.style.display = 'inline';

// prepare ajax preview
				wikEdPreviewIsAjax = false;
				var postData = wikEdTextarea.value;

// Opera 0.9.51
				postData = postData.replace(/\r\n|\n\r|\r/g, '\n');

				if (wikEdUseAjaxPreview == true) {
					var livePreview = true;

// articles on watchlist preview page
					if (wikEdWatchlistEdit == true) {
						postData = postData.replace(/\n{1}/g, '\n\n');
						postData = postData.replace(/(.+)/g,
							function (p, p1) {
								if (/[\#<>\[\]\|\{\}]/.test(p1) == true) {
									return(p1);
								}
								var article = p1;
								var talk;
								if (/:/.test(article) == true) {

// postfix (User_talk) or prefix (Discussion_Utilisateur), test for xxx_ vs. _xxx (all ASCII non-letters as separator)
// Firefox 3.6.7 + Greasemonkey 0.8.20100408.06: invalid range with \{-‰ and \x8f-™
									if (/[ -\/\:-\@\[-\`\{-\x88‰‹\x8d\x8f-\x98™›\x9d\xa0-»¿×÷]/.test(wikEdText['talk namespace suffix']) == true) {
										talk = article.replace(/([^:]*)/, wikEdText['talk namespace suffix'] + '$1');
									}
									else {
										talk = article.replace(/([^:]*)/, '$1' + wikEdText['talk namespace suffix']);
									}
								}
								else {
									talk = wikEdText['talk namespace'] + ':' + article;
								}
								var uriArticle = article.replace(/ /g, '_');
								uriArticle = encodeURI(uriArticle);
								uriArticle = uriArticle.replace(/%25(\d\d)/g, '%$1');
								uriArticle = uriArticle.replace(/\'/g, '%27');
								var hist = wikEdWikiGlobals['wgServer'] + wikEdWikiGlobals['wgScript'] + '?title=' + uriArticle + '&action=history';
								return('[[:' + p1 + ']]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;([[:' + talk + '|' + wikEdText['talk page'] + ']], [' + hist + ' ' + wikEdText['history page'] + '])');
							}
						);
					}

// normal article edit page
					else {

// append references section for section edits
						var section = document.getElementsByName('wpSection');
						if (section != null) {
							if (section.length > 0) {
								if (/\d+/.test(section[0].value) == true) {
									if (/<ref[^>\/]*>.*?<\/ref[^>]*>/i.test(postData) == true) {
										if (/<references\b[^>]*>/i.test(postData) == false) {
											postData += '<div class="wikEdPreviewRefs"><references/></div>';
										}
									}
								}
							}
						}

// GesHI syntax highlighting support, GeSHi css is only provided dynamically and not for &live
// so request a full preview and attach css to page, remember already loaded GeSHi languages
						while ( (regExpMatch = /<(source|syntaxhighlight)\b[^>]*?lang\s*=\s*(\"|\')(\w+)\2/gi.exec(postData)) != null) {
							var lang = regExpMatch[3];
							if (wikEdGeSHiCSS['wikEd' + lang] == null) {
								livePreview = false;
								wikEdGeSHiCSS['wikEd' + lang] = true;
								break;
							}
						}
					}

// make the ajax request
					WikEdAjaxPreview(postData, WikEdLocalPreviewAjaxHandler, livePreview);
				}

// prepare a local preview (Pilaf's InstaView), will be overwritten by Ajax version
				if ( (wikEdUseLocalPreview == true) && (typeof(InstaView) == 'object') ) {
					InstaView.conf.user.name = wikEdWikiGlobals['wgUserName'];
					var text = wikEdTextarea.value;

// Opera 0.9.51
					text = text.replace(/\r\n|\n\r|\r/g, '\n');

					var instaView = InstaView.convert(text);
					if (wikEdPreviewIsAjax != true) {
						wikEdPreviewBox.innerHTML = instaView;

// init sortable tables (wikibits.js)
						if (typeof(sortables_init) == 'function') {
							sortables_init();
						}

// init collapsible tables (common.js)
						if (typeof(createCollapseButtons) == 'function') {
							createCollapseButtons();
						}
					}
				}
				focusFrame = true;
				break;

// display local diff box
			case 'wikEdLocalDiff':
				if (typeof(WDiffString) != 'function') {
					break;
				}
				if (wikEdFullScreenMode == true) {
					WikEdFullScreen(false);
				}
				if (wikEdUseWikEd == true) {
					WikEdUpdateTextarea();
				}

// add trailing newline
				var currentVersion = wikEdTextarea.value;

// Opera 0.9.51
				currentVersion = currentVersion.replace(/\r\n|\n\r|\r/g, '\n');

				if (currentVersion.substr(currentVersion.length - 1, 1) != '\n') {
					currentVersion += '\n';
				}
				if (wikEdOrigVersion.substr(wikEdOrigVersion.length - 1, 1) != '\n') {
					wikEdOrigVersion += '\n';
				}

// call external diff program
				var diffText = WDiffString(wikEdOrigVersion, currentVersion);
				if (wikEdFullDiff != true) {
					diffText = WDiffShortenOutput(diffText);
				}

// display diff, keep wrapper height to prevent scrolling
				var previewHeight = wikEdPreviewBox.offsetHeight;
				if ( (wikEdPreviewBox.innerHTML != '') && (previewHeight > 0) ) {
					wikEdPreviewBox.style.height = previewHeight + 'px';
				}
				wikEdPreviewBox.innerHTML = diffText;
				wikEdLocalPrevWrapper.style.display = 'block';

// scroll to button, textarea, or preview field
				WikEdScrollToPreview();

				break;

// toggle wikEdDiff
			case 'wikEdDiff':

// turn wikEdDiff off
				if (WikEdGetAttribute(buttonObj, 'checked') != 'true') {
					wikEdDiff = false;
					WikEdSetPersistent('wikEdDiff', '0', 0, '/');
					if (typeof(wikEdDiffDiv) == 'object') {
						if (wikEdDiffDiv != null) {
							wikEdDiffDiv.style.display = 'none';
						}
					}
					window.scroll(0, WikEdGetOffsetTop(wikEdInputWrapper));
				}

// turn wikEdDiff on
				else {
					wikEdDiff = true;
					WikEdSetPersistent('wikEdDiff', '1', 0, '/');
					if (typeof(wikEdDiffDiv) == 'object') {
						if (wikEdDiffDiv != null) {
							wikEdDiffDiv.style.display = 'block';
							window.scroll(0, WikEdGetOffsetTop(wikEdDiffDiv));
							WikEdDiff();
						}
					}
				}
				focusFrame = true;
				break;

// close the preview / diff box
			case 'wikEdClose':
			case 'wikEdClose2':
				window.scroll(0, WikEdGetOffsetTop(wikEdInputWrapper));
				wikEdLocalPrevWrapper.style.display = 'none';
				wikEdPreviewBox.style.height = 'auto';
				focusFrame = true;
				break;

// switch between textarea and frame display
//   switching an iframe in design mode immediately after initialization between absolute/static may crash mozilla
			case 'wikEdUseWikEd':

// enble wikEd
				if (WikEdGetAttribute(buttonObj, 'checked') == 'true') {
					WikEdUpdateFrame();

// turn rich text frame on
					WikEdSetEditArea(true);
					wikEdUseWikEd = true;
					WikEdSetPersistent('wikEdUseClassic', '0', 0, '/');

// run scheduled custom functions
					WikEdExecuteHook(wikEdFrameHook);
				}

// turn classic textarea on, disable wikEd
				else {
					WikEdUpdateTextarea();
					WikEdSetEditArea(false);
					wikEdUseWikEd = false;
					WikEdSetPersistent('wikEdUseClassic', '1', 0, '/');

// run scheduled custom functions
					WikEdExecuteHook(wikEdTextareaHook);
				}
				break;

// add "using wikEd" to summaries
			case 'wikEdUsing':
				if (WikEdGetAttribute(buttonObj, 'checked') == 'true') {
					wikEdUsing = true;
					WikEdSetPersistent('wikEdSummaryUsing', '1', 0, '/');
				}
				else {
					wikEdUsing = false;
					WikEdSetPersistent('wikEdSummaryUsing', '0', 0, '/');
				}
				break;

// hide ref tags
			case 'wikEdRefHide':
				if (WikEdGetAttribute(buttonObj, 'checked') == 'true') {
					wikEdRefHide = true;
					WikEdSetPersistent('wikEdRefHide', '1', 0, '/');
				}
				else {
					wikEdRefHide = false;
					WikEdSetPersistent('wikEdRefHide', '0', 0, '/');
				}
				if (wikEdUseWikEd == true) {
					if (wikEdRefHide == true) {
						wikEdFrameBody.className = 'wikEdFrameBodyNewbie';
					}
					else {
						wikEdFrameBody.className = 'wikEdFrameBodySyntax';
					}
					WikEdEditButton(null, 'wikEdWikify', 'whole');
				}
				break;

// close the toolbar
			case 'wikEdCloseToolbar':
				if (WikEdGetAttribute(buttonObj, 'checked') == 'true') {
					wikEdCloseToolbar = true;
					if (wikEdToolbar != null) {
						wikEdToolbarWrapper.style.display = 'none';
					}
					WikEdSetPersistent('wikEdCloseToolbar', '1', 0, '/');
				}
				else {
					wikEdCloseToolbar = false;
					if (wikEdToolbar != null) {
						wikEdToolbarWrapper.style.display = 'block';
					}
					WikEdSetPersistent('wikEdCloseToolbar', '0', 0, '/');
				}
				if (wikEdFullScreenMode == true) {
					WikEdFullScreen(wikEdFullScreenMode);
				}
				break;

// just toggle the case sensitive search button
			case 'wikEdCaseSensitive':
				break;

// just toggle the regexp search button
			case 'wikEdRegExp':
				break;

// just toggle the find-ahead-as-you-type search button
			case 'wikEdFindAhead':
				break;

// switch to fullscreen edit area
			case 'wikEdFullScreen':
				if (wikEdRearrange == true) {
					if (WikEdGetAttribute(buttonObj, 'checked') == 'true') {
						WikEdFullScreen(true);
						WikEdSetPersistent('wikEdFullscreen', '1', 0, '/');
					}
					else {
						WikEdFullScreen(false);
						WikEdSetPersistent('wikEdFullscreen', '0', 0, '/');
					}
				}
				break;

// clear the saved settings for find, replace, and summary history
			case 'wikEdClearHistory':
				WikEdClearHistory('find');
				WikEdClearHistory('replace');
				WikEdClearHistory('summary');
				focusFrame = true;
				break;

// for testing
			case 'wikEdPlaceholder':
				break;
		}
	}

// reset cursor to normal
	if (buttonObj != null) {
		buttonObj.style.cursor = 'pointer';
	}

// focus the frame
	if ( (wikEdUseWikEd == true) && (focusFrame == true) ) {
		wikEdFrameWindow.focus();
	}

	return;
};


//
// WikEdEditButton: editing functions
//   used for buttons that require or change the text, more time consuming than WikEdButton()
//

window.WikEdEditButton = function(buttonObj, buttonId, parameters, CustomHandler) {

// check if button is disabled
	if (buttonObj != null) {
		if (buttonObj.className == 'wikEdButtonInactive') {
			return;
		}
	}

// remove active and non-text content
	WikEdRemoveElements(['script', 'object', 'applet', 'embed', 'textarea']);

// select the appropriate text change targets (whole, selection, cursor, focusWord, focusLine, selectionWord, or selectionLine)
	var obj = {};
	obj.changed = {};
	var highlightNoTimeOut = false;

// set cursor position into closest highest text node so that highlighting does not bleed out
	WikEdAntiHighlightBleeding(obj);

// switch the button
	switch (buttonId) {

// undo, redo: whole
		case 'wikEdUndo':
		case 'wikEdRedo':
		case 'wikEdUndoAll':
		case 'wikEdRedoAll':
			WikEdGetText(obj, 'whole');
			obj.changed = obj.whole;
			break;

// basic wiki character formatting: selection / focusWord / cursor
		case 'wikEdBold':
		case 'wikEdItalic':
		case 'wikEdUnderline':
		case 'wikEdStrikethrough':
		case 'wikEdNowiki':
		case 'wikEdSuperscript':
		case 'wikEdSubscript':
		case 'wikEdWikiLink':
		case 'wikEdWebLink':
			WikEdGetText(obj, 'selection, cursor');
			if (obj.selection.plain != '') {
				obj.changed = obj.selection;
			}
			else {
				WikEdGetText(obj, 'focusWord');
				if (obj.focusWord.plain != '') {
					obj.changed = obj.focusWord;
				}
				else  {
					obj.changed = obj.cursor;
				}
			}
			break;

// reference: selection / cursor
		case 'wikEdRef':
		case 'wikEdRefNamed':
			WikEdGetText(obj, 'selection, cursor');
			if (obj.selection.plain != '') {
				obj.changed = obj.selection;
			}
			else {
				obj.changed = obj.cursor;
			}
			break;

// references and small references: selection / cursor
		case 'wikEdReferences':
		case 'wikEdReferencesSection':
			WikEdGetText(obj, 'selection, cursor');
			if (obj.selection.plain != '') {
				obj.changed = obj.selection;
			}
			else {
				obj.changed = obj.cursor;
			}
			break;

// character formatting: selection / focusWord / cursor
		case 'wikEdCase':
			WikEdGetText(obj, 'selection, cursor');
			if (obj.selection.plain != '') {
				obj.changed = obj.selection;
			}
			else {
				WikEdGetText(obj, 'focusWord');
				if (obj.focusWord.plain != '') {
					obj.changed = obj.focusWord;
				}
				else {
					obj.changed = obj.cursor;
				}
			}
			break;

// multiple line changes: selectionLine / focusLine / cursor
		case 'wikEdDecreaseHeading':
		case 'wikEdIncreaseHeading':
		case 'wikEdIncreaseBulletList':
		case 'wikEdDecreaseBulletList':
		case 'wikEdIncreaseNumberList':
		case 'wikEdDecreaseNumberList':
		case 'wikEdIncreaseIndentList':
		case 'wikEdDecreaseIndentList':
		case 'wikEdDefinitionList':
			WikEdGetText(obj, 'selection, cursor');
			if (obj.selection.plain != '') {
				WikEdGetText(obj, 'selectionLine');
				obj.changed = obj.selectionLine;
			}
			else {
				WikEdGetText(obj, 'focusLine');
				if (obj.focusLine.plain != '') {
					obj.changed = obj.focusLine;
				}
				else {
					obj.changed = obj.cursor;
				}
			}
			break;

// sort: selectionLine / focusLine
		case 'wikEdSort':
			WikEdGetText(obj, 'selection, cursor');
			if (obj.selection.plain != '') {
				WikEdGetText(obj, 'selectionLine');
				obj.changed = obj.selectionLine;
			}
			else {
				WikEdGetText(obj, 'focusPara');
				if (obj.focusPara.plain != '') {
					obj.changed = obj.focusPara;
				}
			}
			break;

// image: selectionWord (if text is selected) / cursor
		case 'wikEdImage':
			WikEdGetText(obj, 'selection, cursor');
			if (obj.selection.plain != '') {
				WikEdGetText(obj, 'selectionWord');
				obj.changed = obj.selectionWord;
			}
			else  {
				obj.changed = obj.cursor;
			}
			break;

// table: selectionLine / cursor
		case 'wikEdTable':
			WikEdGetText(obj, 'selection, cursor');
			if (obj.selection.plain != '') {
				WikEdGetText(obj, 'selectionLine');
				obj.changed = obj.selectionLine;
			}
			else  {
				WikEdGetText(obj, 'focusLine');
				obj.changed = obj.cursor;
			}
			break;

// wikify: selection / whole
		case 'wikEdWikify':
			if (parameters == 'whole') {
				WikEdGetText(obj, 'whole');
				obj.changed = obj.whole;
			}
			else {
				WikEdGetText(obj, 'selection');
				if (obj.selection.plain != '') {
					obj.changed = obj.selection;
				}
				else {
					WikEdGetText(obj, 'whole');
					obj.changed = obj.whole;
				}
			}
			break;

// textify: selection / whole, without wikifying
		case 'wikEdTextify':
			WikEdGetText(obj, 'selection', false);
			if (obj.selection.plain != '') {
				obj.changed = obj.selection;
			}
			else {
				WikEdGetText(obj, 'whole', false);
				obj.changed = obj.whole;
			}
			break;

// redirect: whole
		case 'wikEdRedirect':
			WikEdGetText(obj, 'whole, selection, cursor');
			if (obj.selection.plain == '') {
				WikEdGetText(obj, 'selectionWord');
			}
			obj.changed = obj.whole;
			break;

// find and replace: selection / focusWord / cursor
		case 'wikEdFindPrev':
		case 'wikEdFindNext':
		case 'wikEdJumpPrev':
		case 'wikEdJumpNext':
		case 'wikEdReplacePrev':
		case 'wikEdReplaceNext':
		case 'wikEdFindAll':
			WikEdGetText(obj, 'selection');
			if (obj.selection.plain != '') {
				obj.changed = obj.selection;
			}
			else {
				WikEdGetText(obj, 'focusWord');
				if (obj.focusWord.plain != '') {
					obj.changed = obj.focusWord;
				}
				else {
					obj.changed = obj.cursor;
				}
			}
			break;

// replace all: selection / whole
		case 'wikEdReplaceAll':
			WikEdGetText(obj, 'selection');
			if (obj.selection.plain != '') {
				obj.changed = obj.selection;
			}
			else {
				WikEdGetText(obj, 'whole');
				obj.changed = obj.whole;
			}
			break;

// fixing buttons: selection / whole
		case 'wikEdFixBasic':
		case 'wikEdFixUnicode':
		case 'wikEdFixAll':
		case 'wikEdFixHtml':
		case 'wikEdFixRegExTypo':
		case 'wikEdFixRedirect':
		case 'wikEdFixRedirectReplace':
			WikEdGetText(obj, 'selection, cursor');
			if (obj.selection.plain != '') {
				obj.changed = obj.selection;
			}
			else {
				WikEdGetText(obj, 'whole');
				obj.changed = obj.whole;
			}
			break;

// fixing buttons: selection / focusPara / cursor
		case 'wikEdFixPunct':
		case 'wikEdFixMath':
		case 'wikEdFixUnits':
		case 'wikEdFixDashes':
		case 'wikEdFixCaps':
		case 'wikEdFixChem':
			WikEdGetText(obj, 'selection, cursor');
			if (obj.selection.plain != '') {
				obj.changed = obj.selection;
			}
			else {
				WikEdGetText(obj, 'focusPara');
				if (obj.focusPara.plain != '') {
					obj.changed = obj.focusPara;
				}
				else {
					obj.changed = obj.cursor;
				}
			}
			break;

// fixing buttons: selection / focusLine / cursor
		case 'wikEdFixChem':
			WikEdGetText(obj, 'selection, cursor');
			if (obj.selection.plain != '') {
				obj.changed = obj.selection;
			}
			else {
				WikEdGetText(obj, 'focusLine');
				if (obj.focusPara.plain != '') {
					obj.changed = obj.focusLine;
				}
				else {
					obj.changed = obj.cursor;
				}
			}
			break;

// source: selection / whole
		case 'wikEdSource':
			WikEdGetText(obj, 'selection');
			if (obj.selection.plain != '') {
				obj.changed = obj.selection;
			}
			else {
				WikEdGetText(obj, 'whole');
				obj.changed = obj.whole;
			}
			break;

// insert tags: selection / focusWord / cursor
		case 'wikEdInsertTags':
			WikEdGetText(obj, 'selection, cursor');
			if (obj.selection.plain != '') {
				obj.changed = obj.selection;
			}
			else {
				WikEdGetText(obj, 'focusWord');
				if (obj.focusWord.plain != '') {
					obj.changed = obj.focusWord;
				}
				else {
					obj.changed = obj.selection;
				}
			}
			break;

// convert wiki tables to html
		case 'wikEdTablify':
			WikEdGetText(obj, 'whole');
			obj.changed = obj.whole;
			break;

// update text view using current control button settings
		case 'wikEdUpdateAll':
			WikEdGetText(obj, 'whole');
			obj.changed = obj.whole;
			break;

// custom edit functions have to call WikEdGetText() themselves
		default:
			WikEdGetText(obj, 'cursor');
			obj.changed = obj.cursor;
			break;
	}

// exit
	if (obj.changed == null) {
		wikEdFrameWindow.focus();

// reset button to active, reset cursor
		if (buttonObj != null) {
			if (buttonObj.className != 'wikEdButtonInactive') {
				buttonObj.className = 'wikEdButton';
			}
		}
		return;
	}

// set local syntax highlighting flag
	var highlightSyntax = wikEdHighlightSyntax;

// manipulate the text
	var selectChanged = true;
	var selectChangedText = '';
	switch (buttonId) {

// undo
		case 'wikEdUndo':
			if (wikEdLastVersion == null) {
				wikEdLastVersion = obj.changed.plain;
			}
			WikEdFrameExecCommand('undo');
			if (obj.sel.rangeCount == 0) {
				obj.sel.collapse(wikEdFrameBody, 0);
			}
			obj.changed.range = obj.sel.getRangeAt(obj.sel.rangeCount - 1);
			obj.changed.plain = null;
			obj.changed.keepSel = true;
			break;

// redo
		case 'wikEdRedo':
			WikEdFrameExecCommand('redo');
			if (obj.sel.rangeCount == 0) {
				obj.sel.collapse(wikEdFrameBody, 0);
			}
			obj.changed.range = obj.sel.getRangeAt(obj.sel.rangeCount - 1);
			obj.changed.plain = null;
			obj.changed.keepSel = true;
			break;

// bold
		case 'wikEdBold':
			if ( /\'\'\'([^\'](.|\n)*?)\'\'\'/.test(obj.changed.plain) ) {
				obj.changed.plain = obj.changed.plain.replace(/\'\'\'([^\'](.|\n)*?)\'\'\'/g, '$1');
			}
			else {
				obj.changed.plain = '\'\'\'' + obj.changed.plain + '\'\'\'';
				obj.changed.plain = obj.changed.plain.replace(/(\'\'\')( *)((.|\n)*?)( *)(\'\'\')/, '$2$1$3$6$5');
			}
			obj.changed.plain = obj.changed.plain.replace(/\'{6,}/g, '\'\'\'\'\'');
			obj.changed.keepSel = true;
			break;

// italic
		case 'wikEdItalic':
			if ( /(\'{3,})\'\'([^\'](.|\n)*?)\'\'(\'{3,})/.test(obj.changed.plain) ) {
				obj.changed.plain = obj.changed.plain.replace(/(\'{3,})\'\'([^\'](.|\n)*?)\'\'(\'{3,})/g, '$1$2$4');
			}
			else if ( /(^|[^\'])\'\'([^\'](.|\n)*?)\'\'([^\']|$)/.test(obj.changed.plain) ) {
				obj.changed.plain = obj.changed.plain.replace(/(^|[^\'])\'\'([^\'](.|\n)*?)\'\'([^\']|$)/g, '$1$2$4');
			}
			else {
				obj.changed.plain = '\'\'' + obj.changed.plain + '\'\'';
				obj.changed.plain = obj.changed.plain.replace(/(\'\')( *)((.|\n)*?)( *)(\'\')/, '$2$1$3$6$5');
			}
			obj.changed.plain = obj.changed.plain.replace(/\'{6,}/g, '\'\'\'\'\'');
			obj.changed.keepSel = true;
			break;

// underline
		case 'wikEdUnderline':
			if ( /&lt;u&gt;((.|\n)*?)&lt;\/u&gt;/i.test(obj.changed.plain) ) {
				obj.changed.plain = obj.changed.plain.replace(/&lt;u&gt;((.|\n)*?)&lt;\/u&gt;/gi, '$1');
			}
			else {
				obj.changed.plain = '&lt;u&gt;' + obj.changed.plain + '&lt;\/u&gt;';
				obj.changed.plain = obj.changed.plain.replace(/(&lt;u&gt;)( *)((.|\n)*?)( *)(&lt;\/u&gt;)/, '$2$1$3$6$5');
			}
			obj.changed.keepSel = true;
			break;

// strikethrough
		case 'wikEdStrikethrough':
			if ( /&lt;s&gt;((.|\n)*?)&lt;\/s&gt;/i.test(obj.changed.plain) ) {
				obj.changed.plain = obj.changed.plain.replace(/&lt;s&gt;((.|\n)*?)&lt;\/s&gt;/gi, '$1');
			}
			else {
				obj.changed.plain = '&lt;s&gt;' + obj.changed.plain + '&lt;\/s&gt;';
				obj.changed.plain = obj.changed.plain.replace(/(&lt;s&gt;)( *)((.|\n)*?)( *)(&lt;\/s&gt;)/, '$2$1$3$6$5');
			}
			obj.changed.keepSel = true;
			break;

// nowiki
		case 'wikEdNowiki':
			if ( /&lt;nowiki&gt;((.|\n)*?)&lt;\/nowiki&gt;/i.test(obj.changed.plain) ) {
				obj.changed.plain = obj.changed.plain.replace(/&lt;nowiki&gt;((.|\n)*?)&lt;\/nowiki&gt;/gi, '$1');
			}
			else {
				obj.changed.plain = '&lt;nowiki&gt;' + obj.changed.plain + '&lt;\/nowiki&gt;';
				obj.changed.plain = obj.changed.plain.replace(/(&lt;nowiki&gt;)( *)((.|\n)*?)( *)(&lt;\/nowiki&gt;)/, '$2$1$3$6$5');
			}
			obj.changed.keepSel = true;
			break;

// superscript
		case 'wikEdSuperscript':
			obj.changed.plain = obj.changed.plain.replace(/^(\s*)&lt;sub&gt;((.|\n)*?)&lt;\/sub&gt;(\s*)$/, '$1$2$4');
			if ( /&lt;sup&gt;((.|\n)*?)&lt;\/sup&gt;/i.test(obj.changed.plain) ) {
				obj.changed.plain = obj.changed.plain.replace(/&lt;sup&gt;((.|\n)*?)&lt;\/sup&gt;/gi, '$1');
			}
			else {
				obj.changed.plain = '&lt;sup&gt;' + obj.changed.plain + '&lt;/sup&gt;';
				obj.changed.plain = obj.changed.plain.replace(/(&lt;sup&gt;)( *)((.|\n)*?)( *)(&lt;\/sup&gt;)/, '$2$1$3$6$5');
			}
			obj.changed.keepSel = true;
			break;

// subscript
		case 'wikEdSubscript':
			obj.changed.plain = obj.changed.plain.replace(/^(\s*)&lt;sup&gt;((.|\n)*?)&lt;\/sup&gt;(\s*)$/, '$1$2$4');
			if ( /&lt;sub&gt;((.|\n)*?)&lt;\/sub&gt;/i.test(obj.changed.plain) ) {
				obj.changed.plain = obj.changed.plain.replace(/&lt;sub&gt;((.|\n)*?)&lt;\/sub&gt;/gi, '$1');
			}
			else {
				obj.changed.plain = '&lt;sub&gt;' + obj.changed.plain + '&lt;/sub&gt;';
				obj.changed.plain = obj.changed.plain.replace(/(&lt;sub&gt;)( *)((.|\n)*?)( *)(&lt;\/sub&gt;)/, '$2$1$3$6$5');
			}
			obj.changed.keepSel = true;
			break;

// in-text reference
		case 'wikEdRef':
		case 'wikEdRefNamed':
			if (obj.changed.plain == '') {
				if (buttonId == 'wikEdRef') {
					obj.changed.plain = '&lt;ref&gt;&lt;\/ref&gt;';
				}
				else {
					obj.changed.plain = '&lt;ref name=\"\" \/&gt;';
				}
			}
			else if ( /&lt;ref( name=\"\")? ?\/&gt;/i.test(obj.changed.plain) ) {
				obj.changed.plain = '';
			}
			else if ( /&lt;ref( name=\"\")?&gt;((.|\n)*?)&lt;\/ref&gt;/i.test(obj.changed.plain) ) {
				obj.changed.plain = obj.changed.plain.replace(/&lt;ref( name=\"\")?&gt;((.|\n)*?)&lt;\/ref&gt;/gi, '$2');
			}
			else {
				if (buttonId == 'wikEdRef') {
					obj.changed.plain = '&lt;ref&gt;' + obj.changed.plain + '&lt;/ref&gt;';
				}
				else {
					obj.changed.plain = '&lt;ref name=\"\"&gt;' + obj.changed.plain + '&lt;/ref&gt;';
				}
				obj.changed.plain = obj.changed.plain.replace(/(&lt;ref( name=\"\")?&gt;)( *)((.|\n)*?)( *)(&lt;\/ref&gt;)/, '$3$1$4$7$6');
			}
			obj.changed.keepSel = true;
			break;

// references location
		case 'wikEdReferences':
		case 'wikEdReferencesSection':
			var ref = wikEdText['wikEdReferencesSection'];
			ref = ref.replace(/</g, '&lt;');
			ref = ref.replace(/>/g, '&gt;');
			var refEscaped = ref;
			refEscaped = refEscaped.replace(/([^\w\s\;\&])/g, '\\$1');
			refEscaped = refEscaped.replace(/^\n|\n$/g, '\\n*');
			refEscaped = refEscaped.replace(/(\n)/g, '\\n');
			var	regExp = new RegExp(refEscaped, 'gi');

// plain references tag
			if (buttonId == 'wikEdReferences') {
				if (obj.changed.plain == '') {
					obj.changed.plain = '&lt;references/&gt;';
				}
				else if (regExp.test(obj.changed.plain) == true) {
					obj.changed.plain = obj.changed.plain.replace(regExp, '');
				}
				else if (/&lt;references ?\/&gt;/i.test(obj.changed.plain) ) {
					obj.changed.plain = obj.changed.plain.replace(/&lt;references ?\/&gt;/gi, '');
				}
				else {
					obj.changed = obj.cursor;
					obj.changed.plain = '&lt;references/&gt;';
				}
			}

// complete references code
			else {
				if (obj.changed.plain == '') {
					obj.changed.plain = ref;
				}
				else if (regExp.test(obj.changed.plain) == true) {
					obj.changed.plain = obj.changed.plain.replace(regExp, '');
				}
				else if ( /&lt;references ?\/&gt;/i.test(obj.changed.plain) ) {
					obj.changed.plain = obj.changed.plain.replace(/&lt;references ?\/&gt;/gi, '');
				}
				else {
					obj.changed = obj.cursor;
					obj.changed.plain = ref;
				}
			}
			obj.changed.keepSel = true;
			break;

// toggle lowercase / uppercase
		case 'wikEdCase':
			if (obj.changed.plain == '') {
				obj.changed.plain = null;
			}

// lowercase all uppercased text
			else {

// html character entities to chars
				var plain = obj.changed.plain;
				plain = plain.replace(/&gt;/g, '>');
				plain = plain.replace(/&lt;/g, '<');
				plain = plain.replace(/&amp;/g, '&');

				if (plain.toUpperCase() == plain) {
					plain = plain.toLowerCase();
				}

// first-letter-uppercase all lowercased text
				else if (plain.toLowerCase() == plain) {
					plain = plain.replace(/(^|[^\wŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9])([\wŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9])([\wŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9\']*)/g,
						function (p, p1, p2, p3) {
							return(p1 + p2.toUpperCase() + p3.toLowerCase());
						}
					);
				}

// uppercase mixed upper and lowercased text
				else {
					plain = plain.toUpperCase();
				}

// chars back to html character entities
				plain = plain.replace(/&/g, '&amp;');
				plain = plain.replace(/</g, '&lt;');
				plain = plain.replace(/>/g, '&gt;');
				obj.changed.plain = plain;
			}
			obj.changed.keepSel = true;
			break;

// sort alphabetically by visible words, case insensitive, and numerically
		case 'wikEdSort':

// fix unicode and character entities
			WikEdFixUnicode(obj.changed);

// keep leading and trailing empty lines and table syntax
			var pre = '';
			var main = '';
			var post = '';
			var regExpMatch = /^(((\{\|.*|!.*|\|\+.*|\|\-.*|)\n)*)((.|\n)*?)(((\|\}.*|\|\-.*|)\n)*)$/.exec(obj.changed.plain);
			if (regExpMatch != null) {
				pre = regExpMatch[1];
				main = regExpMatch[4];
				post = regExpMatch[6];
			}
			else {
				main = obj.changed.plain;
			}

// join cells in table rows
			main = main.replace(/(^|\n)(\|[^\-\+\}](.|\n)*?(?=(\|\-|\{\||\|\}|$)|$))/g,
				function(p, p1, p2) {
					p2 = p2.replace(/\n/g, '\x00');
					return(p1 + p2);
				}
			);

// cycle through lines
			var lines = main.split('\n');
			var sortArray = [];
			for (var i = 0; i < lines.length; i ++) {
				var line = lines[i];
				var sortKey = line;

// remove empty lines
				if (line == '') {
					continue;
				}
				sortKey = sortKey.replace(/\x00/g, '\n');

// remove html
				sortKey = sortKey.replace(/&lt;.*&gt;/g, '');

// lowercase
				sortKey = sortKey.toLowerCase();

// keep visible text of wikilinks only
				sortKey = sortKey.replace(/\[\[[^\|\[\]]*\|/g, '');
				sortKey = sortKey.replace(/\[\[|\]\]/g, '');

// keep visible text of external links only
				sortKey = sortKey.replace(/\[(https?|ftp|irc|gopher):\S+/g, '');

// keep visible cell content only
				sortKey = sortKey.replace(/((^|\n)(\||\!))(?![\+\-\}\|\!])[^\|\!]*(\||\!)(?!\4)/g, '$1');
				sortKey = sortKey.replace(/(^|\n)\|-.*?(\n|$)/g, '$2');

// keep single ' only
				sortKey = sortKey.replace(/\'{2,}/g, '');

// remove decimal commas
				sortKey = sortKey.replace(/(\d)\,(?=\d\d\d(\D|$))/g, '$1');

// sort numerically by adding preceeding 0s to numbers
				sortKey = sortKey.replace(/0*(\d+)(\.\d*)?/g,
					function(p, p1, p2) {
						return('000000000000000'.substr(p1.length) + p1 + p2);
					}
				);

// non-breaking and other spaces
				sortKey = sortKey.replace(/&nbsp;|\s/g, ' ');

// remove umlauts (just guessing, but probably better than not doing it)
				sortKey = sortKey.replace(/[à-æ]/g, 'a');
				sortKey = sortKey.replace(/[ç]/g, 'c');
				sortKey = sortKey.replace(/[ð]/g, 'd');
				sortKey = sortKey.replace(/[è-ë]/g, 'e');
				sortKey = sortKey.replace(/[ì-ï]/g, 'i');
				sortKey = sortKey.replace(/[ñ]/g, 'n');
				sortKey = sortKey.replace(/[ò-öø]/g, 'o');
				sortKey = sortKey.replace(/[ß]/g, 'ss');
				sortKey = sortKey.replace(/[ù-ü]/g, 'u');
				sortKey = sortKey.replace(/[ýÿ]/g, 'y');

// remove non-chars
				sortKey = sortKey.replace(/[^\$\@\.\,\:\;\-\w\s\'\u007f-\uffff]/g, '');

// join multiple spaces
				sortKey = sortKey.replace(/ +/g, ' ');

// remove leading and trailing spaces
				sortKey = sortKey.replace(/^ +| +$/g, '');

				sortArray.push( [line, sortKey] );
			}

// sort lines
			sortArray = sortArray.sort(
				function(a, b) {
					if (a[1] <= b[1]) {
						return(-1);
					}
					else {
						return(1);
					}
				}
			);

// join lines
			var joined = '';
			for (var i = 0; i < sortArray.length; i ++) {
				joined += sortArray[i][0];
				if (i < sortArray.length - 1) {
					joined += '\n';
				}
			}
			joined = joined.replace(/\x00/g, '\n');
			obj.changed.plain = pre + joined + post;

			obj.changed.keepSel = true;
			break;

// undo all
		case 'wikEdUndoAll':
			if (wikEdLastVersion == null) {
				wikEdLastVersion = obj.changed.plain;
			}
			obj.changed.plain = wikEdOrigVersion;
			obj.changed.plain = obj.changed.plain.replace(/&/g, '&amp;');
			obj.changed.plain = obj.changed.plain.replace(/>/g, '&gt;');
			obj.changed.plain = obj.changed.plain.replace(/</g, '&lt;');
			break;

// redo all
		case 'wikEdRedoAll':
			if (wikEdLastVersion != null) {
				obj.changed.plain = wikEdLastVersion;
			}
			break;

// create wikilink
		case 'wikEdWikiLink':
			if ( /\[\[((.|\n)*?)\]\]/.test(obj.changed.plain) ) {
				obj.changed.plain = obj.changed.plain.replace(/\[\[(.*?)\|\s*(.*?)\s*\]\]/g, '$2');
				obj.changed.plain = obj.changed.plain.replace(/\[\[((.|\n)*?)\]\]/g, '$1');
			}
			else {
				obj.changed.plain = '[[' + obj.changed.plain + ']]';
				obj.changed.plain = obj.changed.plain.replace(/(\[\[)( *)((.|\n)*?)( *)(\]\])/, '$2$1$3$6$5');
			}
			obj.changed.keepSel = true;
			break;

// create weblink
		case 'wikEdWebLink':
			if ( /\[((.|\n)*?)\]/.test(obj.changed.plain) ) {
				obj.changed.plain = obj.changed.plain.replace(/\[((.|\n)*?)\]/g, '$1');
			}
			else {
				obj.changed.plain = '[' + obj.changed.plain + ']';
				obj.changed.plain = obj.changed.plain.replace(/(\[)( *)((.|\n)*?)( *)(\])/, '$2$1$3$6$5');
			}
			obj.changed.keepSel = true;
			break;

// decrease heading level
		case 'wikEdDecreaseHeading':

// decrease heading
			obj.changed.plain = obj.changed.plain.replace(/(^|\n)=(=+) *(.*?) *=+(?=\n|$)/g, '$1$2 $3 $2');

// remove heading
			obj.changed.plain = obj.changed.plain.replace(/(^|\n)=(?!=) *(.*?) *=+(?=\n|$)/g, '$1$2');

// adjust closing tags
			obj.changed.plain = obj.changed.plain.replace(/(^|\n)(=+) *(.*?) *=+(?=\n|$)/g, '$1$2 $3 $2');
			obj.changed.keepSel = true;
			break;

// increase heading level
		case 'wikEdIncreaseHeading':

// increase heading
			obj.changed.plain = obj.changed.plain.replace(/(^|\n)(=+) *(.*?) *=+(?=\n|$)/g, '$1=$2 $3 $2=');

// create new heading
			if (/\n/.test(obj.changed.plain) == false) {
				obj.changed.plain = obj.changed.plain.replace(/(^|\n)([^=\s].*?)(?=\n|$)/g, '$1== $2 ==');
			}

// adjust closing tags
			obj.changed.plain = obj.changed.plain.replace(/(^|\n)(=+) *(.*?) *=+(?=\n|$)/g, '$1$2 $3 $2');
			obj.changed.keepSel = true;
			break;

// increase bullet list
		case 'wikEdIncreaseBulletList':
			obj.changed.plain = obj.changed.plain.replace(/(.+)/g,
				function (p, p1) {
					p1 = p1.replace(/^ *([\*\#\:\;]*) *()/g, '*$1 ');
					return(p1);
				}
			);
			obj.changed.keepSel = true;
			break;

// decrease bullet list
		case 'wikEdDecreaseBulletList':
			obj.changed.plain = obj.changed.plain.replace(/(.+)/g,
				function (p, p1) {
					p1 = p1.replace(/^[\*\#\:\;] *()/g, '');
					return(p1);
				}
			);
			obj.changed.keepSel = true;
			break;

// increase numbered list
		case 'wikEdIncreaseNumberList':
			obj.changed.plain = obj.changed.plain.replace(/(.+)/g,
				function (p, p1) {
					p1 = p1.replace(/^ *([\*\#\:\;]*) *()/g, '#$1 ');
					return(p1);
				}
			);
			obj.changed.keepSel = true;
			break;

// decrease numbered list
		case 'wikEdDecreaseNumberList':
			obj.changed.plain = obj.changed.plain.replace(/(.+)/g,
				function (p, p1) {
					p1 = p1.replace(/^[\*\#\:\;] *()/g, '');
					return(p1);
				}
			);
			obj.changed.keepSel = true;
			break;

// increase indented list
		case 'wikEdIncreaseIndentList':
			obj.changed.plain = obj.changed.plain.replace(/(.+)/g,
				function (p, p1) {
					p1 = p1.replace(/^ *([\*\#\:\;]*) *()/g, ':$1 ');
					return(p1);
				}
			);
			obj.changed.keepSel = true;
			break;

// decrease indented list
		case 'wikEdDecreaseIndentList':
			obj.changed.plain = obj.changed.plain.replace(/(.+)/g,
				function (p, p1) {
					p1 = p1.replace(/^[\*\#\:\;] *()/g, '');
					return(p1);
				}
			);
			obj.changed.keepSel = true;
			break;

// create definition list
		case 'wikEdDefinitionList':
			obj.changed.plain = obj.changed.plain.replace(/(.+)/g,
				function (p, p1) {
					p1 = p1.replace(/^ *([^\s\;]+) *()/g, '; $1 : ');
					return(p1);
				}
			);
			break;

// create image
		case 'wikEdImage':
			if (obj.changed.plain != '') {
				obj.changed.plain = '[[Image:<span class="wikEdInsertHere">' + wikEdText['image filename'] + '</span>|thumb|<span class="wikEdInsertHere">' + wikEdText['image width'] + '</span>px|' + obj.changed.plain + ']]';
			}
			else {
				obj.changed.plain = '[[Image:<span class="wikEdInsertHere">' + wikEdText['image filename'] + '</span>|thumb|<span class="wikEdInsertHere">' + wikEdText['image width'] + '</span>px|<span class="wikEdInsertHere"> </span>]]';
				if (obj.focusWord != null) {
					if (obj.focusWord.plain != '') {
						obj.changed.plain = ' ' + obj.changed.plain + ' ';
					}
				}
			}
			break;

// create table
		case 'wikEdTable':
			if (obj.changed.plain != '') {
				obj.changed.plain = obj.changed.plain.replace(/(^|\n) *()/g, '\n|-\n| ');
				obj.changed.plain = obj.changed.plain.replace(/^\n\|\-\n/, '\n{| class="wikitable" border="1"\n');
				obj.changed.plain = obj.changed.plain.replace(/$/g, '\n|}\n');
			}
			else if (wikEdTableMode == true) {
				obj.changed.plain = '\n<table class="wikitable" border="1"><caption><span class="wikEdInsertHere">' + wikEdText['table caption'] + '</span></caption><tr><th><span class="wikEdinserthere">' + wikEdText['table heading'] + '</span></th><th><span class="wikEdinserthere">' + wikEdText['table heading'] + '</span></th></tr><tr><td><span class="wikEdInsertHere">' + wikEdText['table cell'] + '</span></td><td><span class="wikEdInsertHere">' + wikEdText['table cell'] + '</span></td></tr><tr><td><span class="wikEdInsertHere">' + wikEdText['table cell'] + '</span></td><td><span class="wikEdInsertHere">' + wikEdText['table cell'] + '</span></td></tr></table>\n';
				if (obj.focusLine.plain != '') {
					obj.changed.plain = '\n' + obj.changed.plain + '\n';
				}
			}
			else {
				obj.changed.plain = '\n{| class="wikitable" border="1"\n|+ <span class="wikEdInsertHere">' + wikEdText['table caption'] + '</span>\n! <span class="wikEdinserthere">' + wikEdText['table heading'] + '</span> !! <span class="wikEdInsertHere">' + wikEdText['table heading'] + '</span>\n|-\n| <span class="wikEdInsertHere">' + wikEdText['table cell'] + '</span> || <span class="wikEdInsertHere">' + wikEdText['table cell'] + '</span>\n|-\n| <span class="wikEdInsertHere">' + wikEdText['table cell'] + '</span> || <span class="wikEdInsertHere">' + wikEdText['table cell'] + '</span>\n|}\n';
				if (obj.focusLine.plain != '') {
					obj.changed.plain = '\n' + obj.changed.plain + '\n';
				}
			}
			break;

// wikify: always done above
		case 'wikEdWikify':
			break;

// textify: strip html from pasted content
		case 'wikEdTextify':
			WikEdTextify(obj.changed);
			if (parameters == 'shift') {
				highlightNoTimeOut = true;
			}
			break;

// redirect
		case 'wikEdRedirect':
			var linkTarget;
			if (obj.selection.plain != '') {
				linkTarget = obj.selection.plain;
			}
			else if (obj.selectionWord.plain != '') {
				linkTarget = obj.selectionWord.plain;
			}
			else {
				linkTarget = '<span class="wikEdInsertHere">' + wikEdText['redirect article link'] + '</span>';
			}

// remove link text after |
			linkTarget = linkTarget.replace(/\|(.|\n)*()/, '');

// remove formatting and spaces
			linkTarget = linkTarget.replace(/^(=+|\'+|<[^>]*>|\s+|\[)+((.|\n)*?)(=+|\'+|<[^>]*>|\s+|\])+$/g, '$2');
			linkTarget = linkTarget.replace(/&lt;/g, '<');
			linkTarget = linkTarget.replace(/&gt;/g, '>');
			linkTarget = linkTarget.replace(/\s+/g, ' ');
			linkTarget = linkTarget.replace(/^\s+|\s+$/g, '');

			obj.changed.plain = '#REDIRECT [[' + linkTarget + ']]';

// append to summary
			if (wikEdInputElement['summary'] != null) {
				if ( (obj.selection.plain != '') || (obj.selectionWord.plain != '') ) {
					wikEdInputElement['summary'].value = wikEdInputElement['summary'].value.replace(/#REDIRECT( \[\[[^\]]*\]\])?(, *)?/g, '');
					wikEdInputElement['summary'].value = WikEdAppendToSummary(wikEdInputElement['summary'].value, '#REDIRECT [[' + linkTarget + ']]');
				}
				else {
					wikEdInputElement['summary'].value = WikEdAppendToSummary(wikEdInputElement['summary'].value, '#REDIRECT');
				}
			}
			selectChanged = false;
			break;

// find and replace
		case 'wikEdFindPrev':
		case 'wikEdFindNext':
		case 'wikEdJumpPrev':
		case 'wikEdJumpNext':
		case 'wikEdReplacePrev':
		case 'wikEdReplaceNext':
		case 'wikEdFindAll':
		case 'wikEdReplaceAll':

// get the find text
			var findText;

// unescape <, >, and &
			obj.changed.plain = obj.changed.plain.replace(/&lt;/g, '<');
			obj.changed.plain = obj.changed.plain.replace(/&gt;/g, '>');
			obj.changed.plain = obj.changed.plain.replace(/&amp;/g, '&');

// copy selection/word under cursor to find field
			if ( (parameters == 'shift') && ( (buttonId == 'wikEdFindNext') || (buttonId == 'wikEdReplaceNext') ) ) {
				if (/\n/.test(obj.changed.plain) == false) {
					if (buttonId == 'wikEdFindNext') {
						wikEdInputElement['find'].value = obj.changed.plain;
					}
					else {
						wikEdInputElement['replace'].value = obj.changed.plain;
					}
					obj.changed.keepSel = true;
					obj.changed.plain = null;
					break;
				}
			}

// get the find text from the selection
			if ( (buttonId == 'wikEdJumpPrev') || (buttonId == 'wikEdJumpNext') ) {
				findText = obj.changed.plain;
				if (obj.selection.plain == '') {
					obj.changed.keepSel = true;
					obj.changed.plain = null;
					break;
				}
			}

// get the find text from the find field
			else {
				if (wikEdInputElement['find'].value != '') {
					findText = wikEdInputElement['find'].value;
				}
				else {
					obj.changed.plain = null;
					break;
				}
			}

// get button status
			var regExpChecked = WikEdGetAttribute(wikEdRegExp, 'checked');
			var caseSensitiveChecked = WikEdGetAttribute(wikEdCaseSensitive, 'checked');

// get the replace text
			var replaceText = wikEdInputElement['replace'].value;

// format the find and replace texts for a plain text search
			var useRegExp = true;
			if ( (regExpChecked == 'false') || (buttonId == 'wikEdJumpPrev') || (buttonId == 'wikEdJumpNext') ) {
				useRegExp = false;
			}

// format the replace text for a regular expression search
			if ( (buttonId == 'wikEdReplacePrev') || (buttonId == 'wikEdReplaceNext') || (buttonId == 'wikEdReplaceAll') ) {
				if (useRegExp == true) {

// substitute \\ \n \r \t \' \" \127 \x1f \u12ef
					replaceText = replaceText.replace(/\\\\/g, '\x00');
					replaceText = replaceText.replace(/\\n/g, '\n');
					replaceText = replaceText.replace(/\\r/g, '\r');
					replaceText = replaceText.replace(/\\t/g, '\t');
					replaceText = replaceText.replace(/\\\'/g, '\'');
					replaceText = replaceText.replace(/\\\"/g, '\"');

					replaceText = replaceText.replace(/\\([0-7]{3})/g,
						function(p, p1) {
							return(String.fromCharCode(parseInt(p1, 8)));
						}
					);
					replaceText = replaceText.replace(/\\x([0-9a-fA-F]{2})/g,
						function(p, p1) {
							return(String.fromCharCode(parseInt(p1, 16)));
						}
					);
					replaceText = replaceText.replace(/\\u([0-9a-fA-F]{4})/g,
						function(p, p1) {
							return(String.fromCharCode(parseInt(p1, 16)));
						}
					);
					replaceText = replaceText.replace(/\x00/g, '\\');
				}
			}

// check the regexp
			var replacedFlag = false;
			var regExpFind;
			if (
				(buttonId == 'wikEdReplacePrev') || (buttonId == 'wikEdReplaceNext') || (buttonId == 'wikEdReplaceAll') ||
				(buttonId == 'wikEdFindPrev') || (buttonId == 'wikEdFindNext') || (buttonId == 'wikEdFindAll')
			) {
				var regExpFindText = findText;
				if (useRegExp != true){
					regExpFindText = regExpFindText.replace(/([\\\^\$\*\+\?\.\(\)\[\]\{\}\:\=\!\|\,\-])/g, '\\$1');
				}
				var regExpFlags = 'gm';
				if (caseSensitive != true) {
					regExpFlags += 'i';
				}
				try {
					regExpFind = new RegExp(regExpFindText, regExpFlags);
				}
				catch (err) {
					return;
				}
			}

// replace all
			if (buttonId == 'wikEdReplaceAll') {
				if (regExpFind.test(obj.changed.plain)) {
					obj.changed.plain = obj.changed.plain.replace(regExpFind, replaceText);
					replacedFlag = true;
				}
				else {
					obj.changed.plain = null;
				}
			}

// replace an existing selection
			else if ( (buttonId == 'wikEdReplacePrev') || (buttonId == 'wikEdReplaceNext') ) {
				if (regExpFind.test(obj.selection.plain)) {
					var replaced = obj.selection.plain.replace(regExpFind, replaceText);
					if (obj.changed.plain != replaced) {
						obj.changed.plain = replaced;
						replacedFlag = true;
					}
					else {
						obj.changed.plain = null;
					}
				}
				else {
					obj.changed.plain = null;
				}
			}

			else if (
				(buttonId == 'wikEdFindPrev') || (buttonId == 'wikEdFindNext') ||
				(buttonId == 'wikEdJumpPrev') || (buttonId == 'wikEdJumpNext')
			) {
				obj.changed.plain = null;
			}

			if (
				(buttonId == 'wikEdFindPrev') || (buttonId == 'wikEdFindNext') ||
				(buttonId == 'wikEdJumpPrev') || (buttonId == 'wikEdJumpNext') ||
				(buttonId == 'wikEdReplacePrev') || (buttonId == 'wikEdReplaceNext') ||
				(buttonId == 'wikEdFindAll')
			) {
				if (replacedFlag == false) {

// get direction
					var backwards = false;
					if ( (buttonId == 'wikEdFindPrev') || (buttonId == 'wikEdJumpPrev') || (buttonId == 'wikEdReplacePrev') ) {
						backwards = true;
					}

// get case sensitive
					var caseSensitive = false;
					if (caseSensitiveChecked == 'true') {
						caseSensitive = true;
					}

// find all
					if (buttonId == 'wikEdFindAll') {
						var found;
						var foundRanges = [];

// start at top of text
						WikEdRemoveAllRanges(obj.sel);
						var range = wikEdFrameDocument.createRange();
						if (wikEdFrameBody.firstChild != null) {
							range.setStartBefore(wikEdFrameBody.firstChild);
						}
						range.collapse(true);
						range = obj.sel.addRange(range);

// cycle through matches
						var scrollTop = wikEdFrameBody.scrollTop;
						do {

// WikEdFind(obj, findText, caseSensitive, backwards, wrap, useRegExp)
							found = WikEdFind(obj, findText, caseSensitive, false, false, useRegExp);
							if (found == true) {
								foundRanges.push(obj.changed.range.cloneRange());
							}
						} while (found == true);

// scroll back
						if (regExpChecked == 'false') {
							wikEdFrameBody.scrollTop = scrollTop;
						}

// add the found ranges, Webkit does not support multiple selections
						WikEdRemoveAllRanges(obj.sel);
						for (var i = 0; i < foundRanges.length; i ++) {
							obj.sel.addRange(foundRanges[i]);
						}
						obj.changed.plain = null;
						selectChanged = false;
					}

// normal find
					else {
						obj.selectChanged = selectChanged;
						WikEdFind(obj, findText, caseSensitive, backwards, true, useRegExp);
						selectChanged = obj.selectChanged;
					}
				}
			}

// escape <, >, and &
			if (obj.changed.plain != null) {
				obj.changed.plain = obj.changed.plain.replace(/&/g, '&amp;');
				obj.changed.plain = obj.changed.plain.replace(/</g, '&lt;');
				obj.changed.plain = obj.changed.plain.replace(/>/g, '&gt;');
			}

// save search history to settings
			if ( (buttonId == 'wikEdFindPrev') || (buttonId == 'wikEdFindNext') || (buttonId == 'wikEdFindAll') ) {
				WikEdAddToHistory('find');
			}
			if ( (buttonId == 'wikEdReplacePrev') || (buttonId == 'wikEdReplaceNext') || (buttonId == 'wikEdReplaceAll') ) {
				WikEdAddToHistory('find');
				WikEdAddToHistory('replace');
			}
			obj.changed.keepSel = true;
			break;

// fixbasic: fix characters, spaces, empty lines, certain headings, needed for all fixing functions
// to do: only certain changes in multiline tags: comments, tables, subst
		case 'wikEdFixBasic':
			WikEdFixBasic(obj.changed);
			obj.changed.keepSel = true;
			break;
		case 'wikEdFixPunct':
			WikEdFixPunct(obj.changed);
			obj.changed.keepSel = true;
			break;
		case 'wikEdFixMath':
			WikEdFixMath(obj.changed);
			obj.changed.keepSel = true;
			break;
		case 'wikEdFixChem':
			WikEdFixChem(obj.changed);
			obj.changed.keepSel = true;
			break;
		case 'wikEdFixUnicode':
			WikEdFixUnicode(obj.changed);
			obj.changed.keepSel = true;
			break;
		case 'wikEdFixRedirect':
			WikEdFixRedirectCall(obj.changed);
			return;
		case 'wikEdFixRedirectReplace':
			WikEdFixRedirectReplace(obj.changed);
			obj.changed.keepSel = true;
			break;
		case 'wikEdFixUnits':
			WikEdFixUnits(obj.changed);
			obj.changed.keepSel = true;
			break;
		case 'wikEdFixDashes':
			WikEdFixDashes(obj.changed);
			obj.changed.keepSel = true;
			break;
		case 'wikEdFixHtml':
			WikEdFixHTML(obj.changed);
			obj.changed.keepSel = true;
			break;
		case 'wikEdFixRegExTypo':
			if ( (wikEdRegExTypoFix == true) && (wikEdTypoRulesFind.length > 0) ) {
				WikEdFixTypos(obj.changed);
			}
			else {
				obj.changed.plain = null;
			}
			obj.changed.keepSel = true;
			break;
		case 'wikEdFixCaps':
			WikEdFixCaps(obj.changed);
			obj.changed.keepSel = true;
			break;
		case 'wikEdFixAll':
			WikEdFixAll(obj.changed);
			obj.changed.keepSel = true;
			break;

// source on
		case 'wikEdSource':
			obj.changed.plain = obj.changed.code;
			obj.changed.plain = obj.changed.plain.replace(/(<(br|p)\b.*?>)/g, '$1\n\n');
			obj.changed.plain = obj.changed.plain.replace(/&/g, '&amp;');
			obj.changed.plain = obj.changed.plain.replace(/</g, '&lt;');
			obj.changed.plain = obj.changed.plain.replace(/>/g, '&gt;');
			highlightSyntax = false;
			break;

// insert tags
		case 'wikEdInsertTags':
			var tagOpen = parameters[0] || '';
			var tagClose = parameters[1] || '';
			var sampleText = parameters[2] || '';
			tagOpen = tagOpen.replace(/&/g, '&amp;');
			tagOpen = tagOpen.replace(/</g, '&lt;');
			tagOpen = tagOpen.replace(/>/g, '&gt;');
			tagClose = tagClose.replace(/&/g, '&amp;');
			tagClose = tagClose.replace(/</g, '&lt;');
			tagClose = tagClose.replace(/>/g, '&gt;');
			tagsampleText = sampleText.replace(/&/g, '&amp;');
			tagsampleText = sampleText.replace(/</g, '&lt;');
			tagsampleText = sampleText.replace(/>/g, '&gt;');

// single string to insert
			if ( (tagOpen.length > 0) && (tagClose.length == 0) && (sampleText.length == 0) ) {
				obj.changed = obj.cursor;
				obj.changed.plain = tagOpen;
			}

// opening and closing strings
			else if ( (obj.changed.plain == '') && (sampleText.length > 0) ) {
				obj.changed.plain = tagOpen + sampleText + tagClose;

// select sample text
				selectChangedText = sampleText;
				obj.changed.keepSel = true;
			}
			else {
				obj.changed.plain = tagOpen + obj.changed.plain + tagClose;
			}
			break;

// convert wiki tables to html // {{TABLE}}
		case 'wikEdTablify':
			obj.changed.keepSel = true;
			if (wikEdTableMode == true) {
				WikEdWikiTableToHtml(obj.changed);
			}
			break;

// update text view using current control button settings // {{TABLE}}
		case 'wikEdUpdateAll':
			obj.changed.keepSel = true;
			if (parameters != null) {
				if (parameters.keepSel == false) {
					obj.changed.keepSel = false;
				}
			}
			break;

// custom edit functions
		default:
			if (CustomHandler != null) {
				CustomHandler(obj);
			}
			else {
				alert('Unknown edit function \'' + buttonId + '\'');
			}
			break;
	}

// pause frame spellchecking
	var pauseFrameSpellchecking = false;
	var frameSpellchecking = wikEdFrameBody.spellcheck;
	if (frameSpellchecking == true) {
		var wholeLength = 0;
		var changedLength = 0;
		if (obj.whole != null) {
			if (obj.whole.plain != null) {
				wholeLength = obj.whole.plain.length;
			}
		}
		if (obj.changed.plain != null) {
			changedLength = obj.changed.plain.length;
		}
		if ( (changedLength > 10000) || (wholeLength > 10000) ) {
			pauseFrameSpellchecking = true;
			wikEdFrameBody.spellcheck = false;
		}
	}

// get the scroll position
	var frameScrollTop = wikEdFrameBody.scrollTop;
	var frameScrollLeft = wikEdFrameBody.scrollLeft;

// update the selection ranges, do not add any text changes
	if (obj.changed.plain == null) {
		if (buttonId != 'wikEdFindAll') {
			WikEdRemoveAllRanges(obj.sel);
			obj.sel.addRange(obj.changed.range);

// scroll the selected text into the viewport
			if (selectChanged != false) {
				WikEdScrollToSelection();
			}
		}
	}

// apply text changes
	else {

// a text change erases the last version for redo all
		if ( (buttonId != 'wikEdUndo') && (buttonId != 'wikEdRedo') && (buttonId != 'wikEdUndoAll') ) {
			wikEdLastVersion = null;
		}

// highlight the syntax
		obj.html = obj.changed.plain;
		if (highlightSyntax == true) {
			if (obj.changed.from == 'whole') {
				obj.whole = true;
			}
			WikEdHighlightSyntax(obj, highlightNoTimeOut);
		}

// at least highlight tab characters
		else {
			obj.html = obj.html.replace(/(\t)/g, '<span class="wikEdTabPlain">$1</span><!--wikEdTabPlain-->');
		}

// display multiple blanks as blank-&nbsp;
		obj.html = obj.html.replace(/(^|\n) /g, '$1&nbsp;');
		obj.html = obj.html.replace(/ (\n|$)/g, '&nbsp;$1');
		obj.html = obj.html.replace(/ {2}/g, '&nbsp; ');
		obj.html = obj.html.replace(/ {2}/g, '&nbsp; ');

// newlines to <br>
		obj.html = obj.html.replace(/\n/g, '<br>');

// make changed range text the current selection
		WikEdRemoveAllRanges(obj.sel);
		var range = obj.changed.range;
		obj.sel.addRange(range);

// replace the selection with changed text
// Opera 9.50beta bug: inserthtml removes blanks and generates consecutive text nodes
		if (obj.html != '') {
			var reselectBefore = '';
			var reselectAfter = '';
			if (obj.changed.from != 'whole') {
				wikEdInsertCounter ++;
				reselectBefore = '<span class="wikEdScrollBefore" id="wikEdScrollBefore' + wikEdInsertCounter + '"></span>';
				reselectAfter = '<span class="wikEdScrollAfter" id="wikEdScrollAfter' + wikEdInsertCounter + '"></span>';
			}
			WikEdFrameExecCommand('inserthtml', reselectBefore + obj.html + reselectAfter);
		}
		else if (obj.sel.isCollapsed == false) {
			WikEdFrameExecCommand('delete');
		}

// select the whole text after replacing the whole text and scroll to same height
		if (obj.changed.from == 'whole') {
			WikEdRemoveAllRanges(obj.sel);
			wikEdFrameBody.scrollTop = frameScrollTop;
			var range = wikEdFrameDocument.createRange();
			range.setStartBefore(wikEdFrameBody.firstChild);
			range.setEndAfter(wikEdFrameBody.lastChild);
			obj.sel.addRange(range);
			selectChanged = false;
		}

// select the changed text and scroll it into the viewport
		else if (selectChanged != false) {
			WikEdRemoveAllRanges(obj.sel);
			var range = wikEdFrameDocument.createRange();
			var startNodeReselect = wikEdFrameDocument.getElementById('wikEdScrollBefore' + wikEdInsertCounter);
			var endNodeReselect = wikEdFrameDocument.getElementById('wikEdScrollAfter' + wikEdInsertCounter);
			range.setStartBefore(startNodeReselect);
			range.setEndAfter(endNodeReselect);
			obj.sel.addRange(range);
			WikEdScrollToNodes(startNodeReselect, endNodeReselect);
		}
	}

// remove selection, keep whole text auto-selection as warning
	if (
		( (obj.changed.keepSel != true) && (obj.changed.from != 'whole') ) ||
		(obj.changed.keepSel == false) ||
		(buttonId == 'wikEdRedirect') ||
		( (buttonId == 'wikEdWikify') && (parameters == 'whole') )
	) {
		if (obj.sel.rangeCount == 0) {
			obj.sel.collapse(wikEdFrameBody, 0);
		}
		else {
			obj.sel.collapseToEnd();
		}

// focus edit area to continue editing as there is no selection that would be overwritten
		wikEdFrameWindow.focus();
	}

// reset button to active, reset cursor
	if (buttonObj != null) {
		if (buttonObj.className != 'wikEdButtonInactive') {
			buttonObj.className = 'wikEdButton';
		}
		buttonObj.style.cursor = 'auto';
	}

// grey out inactive buttons
	WikEdInactiveButtons();

// add event handlers to unhide refs and templates
	if ( (highlightSyntax == true) && (obj.changed.plain != null) ) {

// add ref and template names to hide buttons
		WikEdHighlightNamedHideButtons();

// add event handlers to unhide refs and templates
		WikEdHideAddHandlers();

// add event handlers to make highlighted frame links ctrl-clickable
		WikEdLinkifyAddHandlers();
	}

// resume frame spellchecking
	if (pauseFrameSpellchecking == true) {
		wikEdFrameBody.spellcheck = true;
	}

	return;
};


//
// WikEdLocalPreviewAjaxHandler: process the returned article preview
//

window.WikEdLocalPreviewAjaxHandler = function(ajax) {

	wikEdPreviewIsAjax = true;

// get response
	var html = ajax.responseText;

// livepreview
	if (html.indexOf("<livepreview>") != -1) {
		html = html.replace(/\s*<\/livepreview>\s*()/, '');
		html = html.replace(/\s*<\/preview>\s*()/, '');
		html = html.replace(/&lt;/g, '<');
		html = html.replace(/&gt;/g, '>');
		html = html.replace(/&quot;/g, '"');
		html = html.replace(/&apos;/g, '\'');
		html = html.replace(/&amp;/g, '&');
		html = html.replace(/(.|\n)*<div class=\'previewnote\'>(.|\n)*?<\/div>/, '');
	}

// full preview page
	else {

// attach <style> stylesheet declarations to document (GeSHi)
		var regExpMatch;
		while ( (regExpMatch = /<()style\b[^>]*?type=\"text\/css\">((.|\n)*?)<\/style>/gi.exec(html)) != null) {
			var css = regExpMatch[2];
			var stylesheet = new WikEdStyleSheet(document);
			stylesheet.WikEdAddRules(css);
		}

// get preview html
		html = StringGetInnerHTML(html, 'div', 'id', 'wikiPreview');
		html = StringGetInnerHTML(html, 'div', 'class', 'previewnote', true, false, true);
		html = html.replace(/<!--(.|\n)*?-->/g, '');
		html = html.replace(/\s+$/g, '');
	}

// clean form elements
	html = html.replace(/<\/?form\b[^>]*>/g, '');
	html = html.replace(/(<\/?input\b[^>]*?)\bname="search"([^>]*>)/g, '$1$2');

// remove cite errors for automatic section preview refs
	html = html.replace(/(<div\b[^>]*?\bclass=\"wikEdPreviewRefs\"[^>]*>(.|\s)*$)/,
		function (p, p1, p2) {
			p1 = p1.replace(/<strong\b[^>]*?\bclass=\"error\"[^>]*>(.|\s)*?<\/strong>/g, '');
			return(p1);
		}
	);
	wikEdPreviewBox.innerHTML = html;

// init sortable tables (wikibits.js)
	if (typeof(sortables_init) == 'function') {
		sortables_init();
	}

// init collapsible tables (common.js)
	if (typeof(createCollapseButtons) == 'function') {
		createCollapseButtons();
	}

// scroll to button, textarea, or preview field
	WikEdScrollToPreview();
	return;
};


//
// WikEdFilePreviewAjaxHandler: process the returned image addresses
//

window.WikEdFilePreviewAjaxHandler = function(ajax) {

// get response
	var html = ajax.responseText;

// html-ize
	html = html.replace(/\s*<\/preview>\s*()/, '');
	html = html.replace(/\s*<\/livepreview>\s*()/, '');
	html = html.replace(/&lt;/g, '<');
	html = html.replace(/&gt;/g, '>');
	html = html.replace(/&amp;/g, '&');
	html = html.replace(/&quot;/g, '"');
	html = html.replace(/&apos;/g, '\'');
	html = html.replace(/<\/?(br|p)\b.*?>/g, '\n');

// parse response into file url cache
	var regExpFile = new RegExp('\\n((Image|File|' + wikEdText['wikicode Image'] + '|' + wikEdText['wikicode File'] + '):[^ ]+) +(\\d+) +(.*)', 'ig');
	var regExpMatch;
	while ( (regExpMatch = regExpFile.exec(html)) != null) {
		var file = regExpMatch[1];
		var filePreviewSize = regExpMatch[3];
		var links = regExpMatch[4];
		var fileObj = {};
		var regExpMatch;
		if ( (regExpMatch = /\bsrc=\"(.+?)\"/.exec(links)) != null) {
			fileObj.url = regExpMatch[1];
			if ( (regExpMatch = /\bwidth=\"(\d+)\"/.exec(links)) != null) {
				fileObj.width = parseInt(regExpMatch[1]);
			}
			if ( (regExpMatch = /\bheight=\"(\d+)\"/.exec(links)) != null) {
				fileObj.height = parseInt(regExpMatch[1]);
			}
		}
		else {
			fileObj.url = wikEdImage['noFile'];
			fileObj.width = 16;
			fileObj.height = 16;
		}
		wikEdFilePreviewCache['wikEd' + file + filePreviewSize] = fileObj;
	}

// cycle through file preview spans and add missing images as background
	for (var i = 0; i < wikEdFilePreviewNo; i ++) {
		if (wikEdFilePreviewIds[i] != '') {
			var span = wikEdFrameDocument.getElementById('wikEdFilePreview' + i);
			var fileNameSize = wikEdFilePreviewIds[i];
			var fileObj = wikEdFilePreviewCache['wikEd' + fileNameSize];
			if (fileObj != null) {
				span.style.backgroundImage = 'url(' + fileObj.url + ')';
				if (fileObj.height != null) {
					span.style.height = fileObj.height + 'px';
				}
				if (fileObj.width != null) {
					span.style.width = fileObj.width + 'px';
				}
				span.style.display = 'block';
			}
			wikEdFilePreviewIds[i] = '';
		}
	}

	return;
};


//
// StringGetInnerHTML: get innerHTML of element from html in a string; can also get text before or after node
//

window.StringGetInnerHTML = function(html, tag, attrib, value, defaultToWholeHTML, getBeforeHTML, getAfterHTML) {

	var startPos;
	var startLength;
	var endPos;
	var endLength;
	var level = 0;
	var string = '';
	var regExpMatch;

	var attribValue = '';
	if (attrib != '') {
		attribValue = '[^>]*?' + attrib + '\\s*=\\s*(\\"|\\\')?' + value + '\\1';
	}
	var regExpStart = new RegExp('<' + tag + '\\b' + attribValue + '[^>]*?>', 'gi');
	if ( (regExpMatch = regExpStart.exec(html)) != null) {
		startPos = regExpMatch.index;
		startLength = regExpMatch[0].length;
		var regExpParse = new RegExp('<(\\/?)' + tag + '\\b.*?>', 'g');
		regExpParse.lastIndex = startPos;
		while ( (regExpMatch = regExpParse.exec(html)) != null) {
			if (regExpMatch[1] == '') {
				level ++;
			}
			else {
				level --;
				if (level == 0) {
					endPos = regExpMatch.index;
					endLength = regExpMatch[0].length;
					break;
				}
			}
		}
	}

// return whole html if node does not exist
	if (endPos == null) {
		if (defaultToWholeHTML == true) {
			string = html;
		}
	}

// return text before node
	else if (getBeforeHTML == true) {
		string = html.substr(0, startPos);
	}

// return text after node
	else if (getAfterHTML == true) {
		string = html.substr(endPos + endLength);
	}

// return innerHTML of node
	else {
		string = html.substring(startPos + startLength, endPos);
	}

	return(string);
};


//
// WikEdScrollToPreview: scroll to edit buttons, textarea, or preview field depending on current position
//

window.WikEdScrollToPreview = function() {

// reset fixed height to auto
	wikEdPreviewBox.style.height = 'auto';

	var scrollOffset = window.pageYOffset || document.body.scrollTop;
	var inputOffset = WikEdGetOffsetTop(wikEdInputWrapper);
	var editOffset = WikEdGetOffsetTop(wikEdEditWrapper);
	var submitOffset = WikEdGetOffsetTop(wikEdSaveButton);
	var editHeight = wikEdEditWrapper.clientHeight;

	if (scrollOffset > submitOffset) {
		window.scroll(0, submitOffset);
	}
	else if (scrollOffset > (editHeight / 2 + editOffset) ) {
		window.scroll(0, submitOffset);
	}
	else if (scrollOffset > editOffset) {
		window.scroll(0, editOffset);
	}
	else {
		window.scroll(0, inputOffset);
	}
	return;
};


//
// WikEdLinkifyAddHandlers: register click handlers to make highlighted frame links ctrl-clickable (linkify)
//

window.WikEdLinkifyAddHandlers = function() {

// reset frame timeout
//	window.clearTimeout(wikEdLinkifyTimeoutId);
//	wikEdLinkifyTimeoutId = null;
	if (wikEdLinkify != true) {
		return;
	}

// much faster than individual getElementById in SeaMonkey 1.1.18
	var spans = wikEdFrameDocument.getElementsByTagName('span');
	for (var i = 0; i < spans.length; i ++) {
		var spanId = spans[i].id;
		if (spanId != null) {
			if (spanId.indexOf('wikEdLinkify') == 0) {
				WikEdAddEventListener(spans[i], 'click', WikEdLinkifyHandler, true);
			}
		}
	}
	return;
};


//
// WikEdHighlightNamedHideButtons: register :before text for named hiding buttons
//

window.WikEdHighlightNamedHideButtons = function() {

	if (wikEdRefHide != true) {
		return;
	}

	var rules = '';

// references
	for (var i = 0; i < wikEdReferenceArray.length; i ++) {
		if (wikEdReferenceArray[i].added == true) {
			continue;
		}
		rules += '.wikEdRefButton' + i + ' { border: 1px solid; border-color: #e8e8e8 #444 #444 #e8e8e8; background: #d8d4d0; }\n';

		rules += '.wikEdRefButtonShow' + i + ' { border: 1px solid; border-color: #000 #e8e8e8 #e8e8e8 #000; background: #c8c4c0; }\n';

		rules += '.wikEdRefButton' + i + ':before, .wikEdRefButtonShow' + i + ':before { content: "' + wikEdText['hideRef'] + ' ' + wikEdReferenceArray[i].text + '"; line-height: 0.75em; font-size: 65%; color: #000; font-family: sans-serif; }\n';

		wikEdReferenceArray[i].added = true;
	}

// templates
	for (var i = 0; i < wikEdTemplateArray.length; i ++) {
		if (wikEdTemplateArray[i].added == true) {
			continue;
		}
		rules += '.wikEdTemplButton' + i + ' { border: 1px solid; border-color: #e8e8e8 #444 #444 #e8e8e8; background: #d8d4d0; }\n';

		rules += '.wikEdTemplButtonShow' + i + ' { border: 1px solid; border-color: #000 #e8e8e8 #e8e8e8 #000; background: #c8c4c0; }\n';

		rules += '.wikEdTemplButton' + i + ':before, .wikEdTemplButtonShow' + i + ':before { content: "' + wikEdText['hideTempl'] + ' ' + wikEdTemplateArray[i].text + '"; line-height: 0.75em; font-size: 65%; color: #000; font-family: sans-serif; }\n';

		wikEdTemplateArray[i].added = true;
	}

// character entities
	for (var i = 0; i < wikEdCharEntityArray.length; i ++) {
		if (wikEdCharEntityArray[i].added == true) {
			continue;
		}
		var character = wikEdCharEntityArray[i].text;
		if (character == '"') {
			character = '\\' + character;
		}
		rules += '.wikEdCharEntityButton' + i + ' { border: 1px solid; border-color: #e8e8e8 #444 #444 #e8e8e8; background: #d8d4d0; border-color: rgba(255, 255, 255, 0.75)  rgba(64, 64, 64, 0.5)  rgba(64, 64, 64, 0.5) rgba(255, 255, 255, 0.75); background: rgba(192, 192, 192, 0.3); }\n';

		rules += '.wikEdCharEntityButtonShow' + i + ' { border: 1px solid; border-color: #000 #e8e8e8 #e8e8e8 #000; background: #c8c4c0; border-color: rgba(64, 64, 64, 0.5) rgba(255, 255, 255, 0.75) rgba(255, 255, 255, 0.75) rgba(64, 64, 64, 0.5); background: rgba(192, 192, 192, 0.3); }\n';

		rules += '.wikEdCharEntityButton' + i + ':before, .wikEdCharEntityButtonShow' + i + ':before { content: "' + character + '"; }\n';

		wikEdCharEntityArray[i].added = true;
	}

// add or replace existing css rules
	if (rules != '') {
		WikEdHighlightNamedHideButtonsStylesheet.WikEdAddRules(rules);
	}
	return;
};


//
// WikEdHideAddHandlers: register mouseover handlers for tabs to unhide refs, templates, and character entities
//

window.WikEdHideAddHandlers = function() {

	if ( (wikEdHideContent != true) || (wikEdRefHide != true) ) {
		return;
	}
	var hideButton = wikEdFrameDocument.getElementsByTagName('button');
	for (var i = 0; i < hideButton.length; i ++) {
		var tabClass = hideButton[i].className;
		if (
			(tabClass.indexOf('wikEdRefButton') == 0) ||
			(tabClass.indexOf('wikEdTemplButton') == 0) ||
			(tabClass.indexOf('wikEdCharEntityButton') == 0)
		) {
			WikEdAddEventListener(hideButton[i], 'click', WikEdHideShowHandler, true);
			if (
				(tabClass.indexOf('wikEdRefButtonShow') == -1) &&
				(tabClass.indexOf('wikEdTemplButtonShow') == -1) &&
				(tabClass.indexOf('wikEdCharEntityButtonShow') == -1)
			) {
				WikEdAddEventListener(hideButton[i], 'mouseover', WikEdHideShowHandler, true);
			}
		}
	}
	return;
};


//
// WikEdHideShowHandler: display hidden ref or template on mouse over hide tab
//

window.WikEdHideShowHandler = function(event) {

// event compatibility fixes
	event = WikEdEvent(event, this);
	if (event == null) {
		return;
	}
	event.preventDefault();

// find hidden content node
	var hideTarget = event.currentTarget;
	var hideInto = event.safeRelatedTarget;
	var hideButton;
	var hideContainer;
	var hide;

// <container><button></button></container><hide> text </hide>

// target == button
	if (/^wikEd(Ref|Templ|CharEntity)Button(Show)?\d*$/.test(hideTarget.className) == true) {
		hideButton = hideTarget;
		hideContainer = hideButton.parentNode;
		if (hideContainer != null) {
			if (/^wikEd(Ref|Templ|CharEntity)Container$/.test(hideContainer.className) == false) {
				hideContainer = null;
			}
			else {

// get hide text
				hide = WikEdGetNextSiblingNode(hideContainer);
				if (hide != null) {
					if (/^wikEd(Ref|Templ|TemplNs|CharEntity)(Show)?$/.test(hide.className) == false) {
						hide = null;
					}
				}
			}
		}
	}

// target == hide text
	else if (/^wikEd(Ref|Templ|TemplNs|CharEntity)(Show)?$/.test(hideTarget.className) == true) {

		hide = hideTarget;
		hideContainer = WikEdGetPreviousSiblingNode(hideTarget);
		if (hideContainer != null) {
			if (/^wikEd(Ref|Templ|CharEntity)Container$/.test(hideContainer.className) == false) {
				hideContainer = null;
			}
			else {

// get button
				hideButton = WikEdGetFirstChildNode(hideContainer);
				if (hideButton != null) {
					if (/^wikEd(Ref|Templ|CharEntity)Button(Show)?\d*$/.test(hideButton.className) == false) {
						hideButton = null;
					}
				}
			}
		}
	}

	if ( (hideContainer == null) || (hideButton == null) || (hide == null) ) {
		return;
	}

// get classes
	var hideButtonClass = hideButton.className;
	var hideClass = hide.className;

// open on hover
	if (event.type == 'mouseover') {
		if (hideButtonClass.indexOf('wikEdRefButton') == 0) {
			hide.style.display = 'block';
		}
		else if (hideButtonClass.indexOf('wikEdTemplButton') == 0) {
			hide.style.display = 'block';
		}
		else if (hideButtonClass.indexOf('wikEdCharEntity') == 0) {
			hide.style.display = 'inline';
		}
		WikEdRemoveEventListener(hideButton, 'mouseover', WikEdHideShowHandler, true);
		WikEdAddEventListener(hide, 'mouseout', WikEdHideShowHandler, true);
		WikEdAddEventListener(hideButton, 'mouseout', WikEdHideShowHandler, true);
	}

// close after hover
	else if (event.type == 'mouseout') {
		if ( (hideInto != hideContainer) && (hideInto != hideButton) && (hideInto != hide) ) {
			if (/^wikEd(Ref|Templ|CharEntity)Button\d*$/.test(hideButton.className) == true) {
				var hideOut = false;
				var node = hideInto;
				while (node != null) {
					if (node == wikEdFrameBody) {
						hideOut = true;
						break;
					}
					if ( (node == hideContainer) || (node == hide) ) {
						break;
					}
					node = node.parentNode;
				}
				if (hideOut == true) {
					hide.style.display = 'none';
					WikEdRemoveEventListener(hide, 'mouseout', WikEdHideShowHandler, true);
					WikEdRemoveEventListener(hideButton, 'mouseout', WikEdHideShowHandler, true);
					WikEdAddEventListener(hideButton, 'mouseover', WikEdHideShowHandler, true);

// move cursor out of hidden text
					WikEdUnhideCursor(hideContainer, hide);
				}
			}
		}
	}

// hide on click
	else if (event.type == 'click') {
		if (/^wikEd(Ref|Templ|CharEntity)ButtonShow\d*$/.test(hideButtonClass) == true) {
			hide.style.display = 'none';

			hideButtonClass = hideButtonClass.replace(/Show/, '');
			hideClass = hideClass.replace(/Show/, '');

			hideButton.className = hideButtonClass;
			hideButton.title = wikEdText[hideButtonClass.replace(/\d+$/g, '') + 'Tooltip'];

			WikEdAddEventListener(hideButton, 'mouseover', WikEdHideShowHandler, true);

// move cursor out of hidden text
			WikEdUnhideCursor(hideContainer, hide);
		}

// open on click
		else if (/^wikEd(Ref|Templ|CharEntity)Button\d*$/.test(hideButtonClass) == true) {
			if (hideButtonClass.indexOf('wikEdCharEntityButton') == 0) {
				hide.style.display = 'inline';
			}
			else {
				hide.style.display = 'block';
			}
			hideButtonClass = hideButtonClass.replace(/Button(Show)?/, 'ButtonShow');
			hideClass += 'Show';
			hideClass = hideClass.replace(/Show/, '') + 'Show';

			hideButton.className = hideButtonClass;
			hideButton.title = wikEdText[hideButtonClass.replace(/\d+$/g, '') + 'Tooltip'];

			WikEdRemoveEventListener(hideButton, 'mouseover', WikEdHideShowHandler, true);
			WikEdRemoveEventListener(hide, 'mouseout', WikEdHideShowHandler, true);
			WikEdRemoveEventListener(hideButton, 'mouseout', WikEdHideShowHandler, true);
		}
	}
	return;
};


//
// WikEdUnhideCursor: move cursor out of hidden element for WikEdHideShowHandler
//

window.WikEdUnhideCursor = function(firstHiddenParent, lastHiddenParent) {

// get selection and clone range
	var sel = WikEdGetSelection();
	var range = sel.getRangeAt(sel.rangeCount - 1);
	if (range != null) {

// check if selected text is hidden
		var startHidden = false;
		var node = range.startContainer;
		while (node != null) {
			if (node == wikEdFrameBody) {
				break;
			}
			if ( (node == lastHiddenParent) || (node == firstHiddenParent) ) {
				startHidden = true;
				break;
			}
			node = node.parentNode;
		}
		var endHidden = false;
		var node = range.endContainer;
		while (node != null) {
			if (node == wikEdFrameBody) {
				break;
			}
			if ( (node == lastHiddenParent) || (node == firstHiddenParent) ) {
				endHidden = true;
				break;
			}
			node = node.parentNode;
		}

// unselect hidden text
		if ( (startHidden == false) && (endHidden == true) ) {
			range.setEndBefore(firstHiddenParent);
		}
		else if ( (startHidden == true) && (endHidden == false) ) {
			range.setStartAfter(lastHiddenParent);
		}
		else if ( (startHidden == true) && (endHidden == true) ) {
			range.setEndAfter(lastHiddenParent);
			range.collapse(false);
		}
	}
	return;
};


//
// WikEdGetText: get the text fragments to manipulate
//

window.WikEdGetText = function(obj, whichFragment, wikify) {

// remove dynamically inserted nodes by other scripts
	WikEdCleanNodes(wikEdFrameDocument);

// get selection object
	if (obj.sel == null) {
		obj.sel = WikEdGetSelection();
	}

// cursor for the cursor position (always done)
	if (obj.cursor == null) {
		obj.cursor = {
			'from': 'cursor',
			'keepSel': null,
			'plain': ''
		};

// set cursor range
		obj.cursor.range = wikEdFrameDocument.createRange();
		WikEdSetRangeStart(obj.cursor.range, obj.sel.focusNode, obj.sel.focusOffset);
		obj.cursor.range.collapse(true);
	}

// whole for the whole text
	if (obj.whole == null) {
		if (/whole|selectionWord|selectionLine|selectionPara|focusWord|focusLine|focusPara/.test(whichFragment) == true) {
			obj.whole = {
				'plainArray': [],
				'plainNode': [],
				'plainStart': [],
				'from': 'whole',
				'keepSel': null
			};

// set whole range
			obj.whole.range = wikEdFrameDocument.createRange();
			if (wikEdFrameBody.firstChild != null) {
				obj.whole.range.setStartBefore(wikEdFrameBody.firstChild);
				obj.whole.range.setEndAfter(wikEdFrameBody.lastChild);
			}

// get whole plain text
			WikEdGetInnerHTML(obj.whole, wikEdFrameBody);
			obj.whole.code = obj.whole.html;
			WikEdRemoveHighlightingWikify(obj.whole, wikify);
			obj.whole.plain = obj.whole.html;
			obj.whole.plain = obj.whole.plain.replace(/[\n ]*<br\b[^>]*>[\n ]*()/g, '\n');
			obj.whole.plain = obj.whole.plain.replace(/\xa0/g, ' ');
		}
	}

// selection for the selected text
	if (obj.selection == null) {
		if (/selection\b|selectionWord|selectionLine|selectionPara/.test(whichFragment) == true) {
			obj.selection = {
				'from': 'selection',
				'keepSel': null
			};

// copy range to document fragment
			if (obj.sel.rangeCount == 0) {
				obj.sel.collapse(wikEdFrameBody, 0);
			}
			obj.selection.range = obj.sel.getRangeAt(obj.sel.rangeCount - 1);
			var documentFragment = obj.selection.range.cloneContents();

// get selected text
			WikEdGetInnerHTML(obj.selection, documentFragment);
			obj.selection.code = obj.selection.html;
			WikEdRemoveHighlightingWikify(obj.selection, wikify);
			obj.selection.plain = obj.selection.html;
			obj.selection.plain = obj.selection.plain.replace(/[\n ]*<br\b[^>]*>[\n ]*()/g, '\n');
			obj.selection.plain = obj.selection.plain.replace(/\xa0/g, ' ');
		}
	}

// focusWord, focusLine, and focusPara for the word, line, and paragraph under the cursor
	if (obj.focusWord == null) {
		if (/focusWord|focusLine|focusPara/.test(whichFragment) == true) {
			obj.focusWord = {
				'from': 'focusWord',
				'keepSel': false,
				'range': wikEdFrameDocument.createRange(),
				'tableEdit': obj.tableEdit
			};

// setup focusLine object for the line under the cursor
			obj.focusLine = {
				'from': 'focusLine',
				'keepSel': false,
				'range': wikEdFrameDocument.createRange(),
				'tableEdit': obj.tableEdit
			};

// setup focusPara object for the paragraph under the cursor
			obj.focusPara = {
				'from': 'focusPara',
				'keepSel': false,
				'range': wikEdFrameDocument.createRange(),
				'tableEdit': obj.tableEdit
			};

// find the word and line boundaries
			WikEdFindBoundaries(obj.focusWord, obj.focusLine, obj.focusPara, obj.whole, obj.cursor);

// get the wikified plain text for the word under the cursor
			var documentFragment = obj.focusWord.range.cloneContents();
			WikEdGetInnerHTML(obj.focusWord, documentFragment);
			obj.focusWord.code = obj.focusWord.html;
			WikEdRemoveHighlightingWikify(obj.focusWord, wikify);
			obj.focusWord.plain = obj.focusWord.html;
			obj.focusWord.plain = obj.focusWord.plain.replace(/[\n ]*<br\b[^>]*>[\n ]*()/g, '\n');
			obj.focusWord.plain = obj.focusWord.plain.replace(/\xa0/g, ' ');

// get the wikified plain text for the line under the cursor
			var documentFragment = obj.focusLine.range.cloneContents();
			WikEdGetInnerHTML(obj.focusLine, documentFragment);
			obj.focusLine.code = obj.focusLine.html;
			WikEdRemoveHighlightingWikify(obj.focusLine, wikify);
			obj.focusLine.plain = obj.focusLine.html;
			obj.focusLine.plain = obj.focusLine.plain.replace(/[\n ]*<br\b[^>]*>[\n ]*()/g, '\n');
			obj.focusLine.plain = obj.focusLine.plain.replace(/\xa0/g, ' ');

// get the wikified plain text for the paragraph under the cursor
			var documentFragment = obj.focusPara.range.cloneContents();
			WikEdGetInnerHTML(obj.focusPara, documentFragment);
			obj.focusPara.code = obj.focusPara.html;
			WikEdRemoveHighlightingWikify(obj.focusPara, wikify);
			obj.focusPara.plain = obj.focusPara.html;
			obj.focusPara.plain = obj.focusPara.plain.replace(/[\n ]*<br\b[^>]*>[\n ]*()/g, '\n');
			obj.focusPara.plain = obj.focusPara.plain.replace(/\xa0/g, ' ');
		}
	}

// selectionWord and selectionLine for the complete words and lines under the selection
	if (obj.selectionWord == null) {
		if (/selectionWord|selectionLine|selectionPara/.test(whichFragment) == true) {

// setup selectionWord object for the words under the selection
			obj.selectionWord = {
				'from': 'selectionWord',
				'keepSel': false,
				'range': wikEdFrameDocument.createRange(),
				'tableEdit': obj.tableEdit
			};

// setup selectionLine object for the lines under the selection
			obj.selectionLine = {
				'from': 'selectionLine',
				'keepSel': false,
				'range': wikEdFrameDocument.createRange(),
				'tableEdit': obj.tableEdit
			};

// setup focusPara object for the paragraph under the selection
			obj.selectionPara = {
				'from': 'selectionPara',
				'keepSel': false,
				'range': wikEdFrameDocument.createRange(),
				'tableEdit': obj.tableEdit
			};

// find the word and line boundaries
			WikEdFindBoundaries(obj.selectionWord, obj.selectionLine, obj.selectionPara, obj.whole, obj.selection);

// get the wikified plain text for the words under the selection
			var documentFragment = obj.selectionWord.range.cloneContents();
			WikEdGetInnerHTML(obj.selectionWord, documentFragment);
			obj.selectionWord.code = obj.selectionWord.html;
			WikEdRemoveHighlightingWikify(obj.selectionWord, wikify);
			obj.selectionWord.plain = obj.selectionWord.html;
			obj.selectionWord.plain = obj.selectionWord.plain.replace(/[\n ]*<br\b[^>]*>[\n ]*()/g, '\n');
			obj.selectionWord.plain = obj.selectionWord.plain.replace(/\xa0/g, ' ');

// get the wikified plain text for the lines under the selection
			var documentFragment = obj.selectionLine.range.cloneContents();
			WikEdGetInnerHTML(obj.selectionLine, documentFragment);
			obj.selectionLine.code = obj.selectionLine.html;
			WikEdRemoveHighlightingWikify(obj.selectionLine, wikify);
			obj.selectionLine.plain = obj.selectionLine.html;
			obj.selectionLine.plain = obj.selectionLine.plain.replace(/[\n ]*<br\b[^>]*>[\n ]*()/g, '\n');
			obj.selectionLine.plain = obj.selectionLine.plain.replace(/\xa0/g, ' ');

// get the wikified plain text for the paragraph under the selection
			var documentFragment = obj.selectionPara.range.cloneContents();
			WikEdGetInnerHTML(obj.selectionPara, documentFragment);
			obj.selectionPara.code = obj.selectionPara.html;
			WikEdRemoveHighlightingWikify(obj.selectionPara, wikify);
			obj.selectionPara.plain = obj.selectionPara.html;
			obj.selectionPara.plain = obj.selectionPara.plain.replace(/[\n ]*<br\b[^>]*>[\n ]*()/g, '\n');
			obj.selectionPara.plain = obj.selectionPara.plain.replace(/\xa0/g, ' ');
		}
	}
	return;
};


//
// WikEdFind: custom find function with regexp properties, sets obj.changed.range, uses obj ranges
//

window.WikEdFind = function(obj, findText, caseSensitive, backwards, wrap, useRegExp) {

	var found = false;

// get selection
	if (obj.sel == null) {
		obj.sel = WikEdGetSelection();
	}
	if (obj.sel.rangeCount == 0) {
		obj.sel.collapse(wikEdFrameBody, 0);
	}
	var range = obj.sel.getRangeAt(obj.sel.rangeCount - 1);

	if (obj.changed == null) {
		obj.changed = {};
	}
	obj.selectChanged = false;

// empty the range to avoid error messages for reverse direction ranges
	obj.changed.range = wikEdFrameDocument.createRange();

// regexp instead of plain text search for browser lacking .find (Opera), built in .find() ignores newlines
	if (useRegExp != true) {
		if (typeof(wikEdFrameWindow.find) != 'function') {
			useRegExp = true;
			findText = findText.replace(/([\\\^\$\*\+\?\.\(\)\[\]\{\}\:\=\!\|\,\-])/g, '\\$1');
		}
	}

// create the regexp
	var regExpFind;
	if (useRegExp == true) {
		var regExpFlags = 'gm';
		if (caseSensitive != true) {
			regExpFlags += 'i';
		}
		try {
			regExpFind = new RegExp(findText, regExpFlags);
		}
		catch (err) {
			return(false);
		}
	}

// use the fast built-in find function for non-regexp searches; Opera does not have .find
	if (useRegExp != true) {

// parameters: window.find(string, caseSensitive, backwards, wrapAround, wholeWord, searchInFrames, showDialog)
		found = wikEdFrameWindow.find(findText, caseSensitive, backwards, wrap, false, true, false);
		if (found == true) {
			range = obj.sel.getRangeAt(obj.sel.rangeCount - 1);
		}
		obj.changed.range = range;
	}

// slow javascript regexp find and replace
	else {

// perform find
		if (obj.plainArray === undefined) {
			WikEdParseDOM(obj, wikEdFrameBody);
		}
		var regExpMatch = [];

// find next, search to the right
		if (backwards == false) {

// set start position for search to right
			regExpFind.lastIndex = obj.plainFocus;

// execute the regexp search to the right
			regExpMatch = regExpFind.exec(obj.plain);

// remember position for repeated searches
			obj.plainFocus = regExpFind.lastIndex;

// wrap around, start at beginning
			if ( (wrap == true) && (regExpMatch == null) ) {
				regExpFind.lastIndex = 0;
				regExpMatch = regExpFind.exec(obj.plain);
			}
		}

// find previous, search to the left
		else {

// cycle through the matches to the left
			var regExpMatchNext;
			do {
				regExpMatch = regExpMatchNext;
				regExpMatchNext = regExpFind.exec(obj.plain);
				if (regExpMatchNext == null) {
					break;
				}
			} while (regExpMatchNext.index < obj.plainAnchor);

// wrap around, find last occurrence
			if ( (wrap == true) && (regExpMatch == null) ) {
				do {
					regExpMatch = regExpMatchNext;
					regExpMatchNext = regExpFind.exec(obj.plain);
				} while (regExpMatchNext != null);
			}
		}

// select the find
		if (regExpMatch != null) {
			found = true;

			var i = 0;
			while ( (obj.plainStart[i + 1] <= regExpMatch.index) && (obj.plainStart[i + 1] != null) ) {
				i ++;
			}

			var j = i;
			while ( (obj.plainStart[j + 1] <= regExpMatch.index + regExpMatch[0].length) && (obj.plainStart[j + 1] != null) ) {
				j ++;
			}

			var startNode = obj.plainNode[i];
			var startOffset = regExpMatch.index - obj.plainStart[i];
			var endNode = obj.plainNode[j];
			var endOffset = regExpMatch.index + regExpMatch[0].length - obj.plainStart[j];
			WikEdSetRange(obj.changed.range, startNode, startOffset, endNode, endOffset);
			obj.selectChanged = true;
		}
	}
	return(found);
};


//
// WikEdScrollToSelection: scroll iframe range into viewport
//   for MSIE see http://www.webmasterworld.com/javascript/3820483.htm
//   removig helper nodes gives Error: Node was not found = NS_ERROR_DOM_NOT_FOUND_ERR for certain undo actions
//   adding nodes breaks the undo history in Chrome and Opera

window.WikEdScrollToSelection = function(frameScrollTop, frameScrollLeft, removeHelperNodes) {

// get selection and clone range
	var obj = {};
	obj.sel = WikEdGetSelection();
	if (obj.sel.rangeCount == 0) {
		return;
	}

// get selection plain text
	range = obj.sel.getRangeAt(obj.sel.rangeCount - 1);
	var documentFragment = range.cloneContents();
	WikEdGetInnerHTML(obj, documentFragment);
	var plainText = obj.plain;
	plainText = plainText.replace(/&lt;/g, '<');
	plainText = plainText.replace(/&gt;/g, '>');
	plainText = plainText.replace(/&amp;/g, '&');

// select using backwards built-in find
	if ( (typeof(wikEdFrameWindow.find) == 'function') && (plainText.length > 0) ) {
		obj.sel.collapseToEnd();

// Chrome; parameters: WikEdFind(obj, findText, caseSensitive, backwards, wrap, useRegExp)
		var found = WikEdFind(obj, plainText, true, true, false, false);

// Firefox (\n removed, \xa0 as blank)
		if ( (found == false) && (/\n/.test(plainText) == true) ) {
			plainText = range.toString();
			WikEdFind(obj, plainText, true, true, false, false);
		}
	}

// select empty range using backwards built-in find for previous character
	else if ( (typeof(wikEdFrameWindow.find) == 'function') && (plainText.length == 0) ) {
		var backwards = true;

// get plain text from start to selection
		var rangeClone = range.cloneRange();
		rangeClone.setStartBefore(wikEdFrameBody.firstChild);
		var documentFragment = rangeClone.cloneContents();
		WikEdGetInnerHTML(obj, documentFragment);
		var plainText = obj.plain;
		plainText = plainText.replace(/&lt;/g, '<');
		plainText = plainText.replace(/&gt;/g, '>');
		plainText = plainText.replace(/&amp;/g, '&');
		plainText = plainText.replace(/^([\s\S]*?)([^\n]\n*)$/, '$2');

// get plain text from selection to end for potentially less newlines
		if (plainText.length > 1) {
			var plainTextBack = plainText;
			var obj = {};

			var rangeClone = range.cloneRange();
			rangeClone.setEndAfter(wikEdFrameBody.lastChild);
			var documentFragment = rangeClone.cloneContents();
			WikEdGetInnerHTML(obj, documentFragment);
			var plainText = obj.plain;
			plainText = plainText.replace(/&lt;/g, '<');
			plainText = plainText.replace(/&gt;/g, '>');
			plainText = plainText.replace(/&amp;/g, '&');
			plainText = plainText.replace(/^(\n*[^\n])([\s\S]*?)$/, '$1');

// backward or forward find
			if (plainTextBack.length > plainText.length) {
				backwards = false;
			}
			else {
				plainText = plainTextBack;
			}
		}

// Chrome; parameters: WikEdFind(obj, findText, caseSensitive, backwards, wrap, useRegExp)
		var found = WikEdFind(obj, plainText, true, backwards, false, false);

// Firefox
		if ( (found == false) && (/\n/.test(plainText) == true) ) {
			plainText = plainText.replace(/\n/g, '');
			plainText = plainText.replace(/\xa0/g, ' ');
			WikEdFind(obj, plainText, true, backwards, false, false);
		}
		if (backwards == true) {
			obj.sel.collapseToEnd();
		}
		else {
			obj.sel.collapseToStart();
		}
	}

// use inserted spans as scroll marker, breaks undo history in Chrome and Opera
	else {
		var rangeStart = range.cloneRange();
		var rangeEnd = range.cloneRange();

// spans to be temporarily inserted before and after selection range to get range position
		wikEdInsertCounter ++;
		var scrollStartNode = wikEdFrameDocument.createElement('span');
		scrollStartNode.className = 'wikEdScrollBefore';
		scrollStartNode.id = 'wikEdScrollBefore' + wikEdInsertCounter;
		var scrollEndNode = wikEdFrameDocument.createElement('span');
		scrollEndNode.className = 'wikEdScrollAfter';
		scrollEndNode.id = 'wikEdScrollAfter' + wikEdInsertCounter;

// get the range border nodes and offsets
		var startNode = range.startContainer;
		var startOffset = range.startOffset;
		var endNode = range.endContainer;
		var endOffset = range.endOffset;

		var startLength;
		if (startNode.nodeName == '#text') {
			startLength = startNode.nodeValue.length;
		}
		var endLength;
		if (endNode.nodeName == '#text') {
			endLength = endNode.nodeValue.length;
		}

// insert end node
		if (endNode.nodeName == '#text') {
			if (endOffset == 0) {
				endNode.parentNode.insertBefore(scrollEndNode, endNode);
			}
			else if (endOffset == endLength - 1) {
				endNode.parentNode.insertBefore(scrollEndNode, endNode.nextSibling);
			}
			else {
				rangeEnd.collapse(false);
				rangeEnd.insertNode(scrollEndNode);
			}
		}
		else {
			var refNode = endNode.childNodes.item(endOffset);
			endNode.insertBefore(scrollEndNode, refNode);
		}

// insert start node
		if (startNode.nodeName == '#text') {
			if (startOffset == 0) {
				startNode.parentNode.insertBefore(scrollStartNode, startNode);
			}
			else if (startOffset == startLength - 1) {
				startNode.parentNode.insertBefore(scrollStartNode, startNode.nextSibling);
			}
			else {

// collapse as a Firefox bug work around; http://stackoverflow.com/questions/665676
				rangeStart.collapse(true);
				rangeStart.insertNode(scrollStartNode);
			}
		}
		else {
			var refNode = startNode.childNodes.item(startOffset);
			startNode.insertBefore(scrollStartNode, refNode);
		}

		WikEdScrollToNodes(scrollStartNode, scrollEndNode);

// set selection
		range.setStartBefore(scrollStartNode);
		range.setEndAfter(scrollEndNode);
		WikEdRemoveAllRanges(sel);
		sel.addRange(range);
	}

	return;
};


//
// WikEdScrollToNodes: scroll iframe range into viewport
//

window.WikEdScrollToNodes = function(scrollStartNode, scrollEndNode) {

// absolute span for line height detection (Opera and Chrome do not vertically align empty span at bottom)
	var lineHeightNode = wikEdFrameDocument.createElement('span');
	lineHeightNode.innerHTML = '&nbsp;';
	lineHeightNode.className = 'wikEdScrollLineHeight';
	scrollEndNode.appendChild(lineHeightNode);
	lineHeight = lineHeightNode.clientHeight;
	lineHeightNode.innerHTML = '';
	scrollEndNode.removeChild(lineHeightNode);

// scroll to node coordinates
	var startOffsetLeft = WikEdGetOffsetLeft(scrollStartNode);
	var startOffsetTop  = WikEdGetOffsetTop(scrollStartNode);
	var endOffsetRight  = WikEdGetOffsetLeft(scrollEndNode);
	var endOffsetBottom = WikEdGetOffsetTop(scrollEndNode);
	var frameScrollTop  = wikEdFrameBody.scrollTop;
	var frameScrollLeft = wikEdFrameBody.scrollLeft;
	var x = frameScrollLeft;
	var y = frameScrollTop;

// current scroll position

// selection above viewport
	if (endOffsetBottom < frameScrollTop) {
		y = startOffsetTop;
	}

// selection below viewport
	else if (startOffsetTop > frameScrollTop + wikEdFrameBody.clientHeight) {
		y = endOffsetBottom - wikEdFrameBody.clientHeight + lineHeight;
	}

// selection left of viewport
	if (endOffsetRight < frameScrollLeft) {
		if (endOffsetRight <= wikEdFrameBody.clientWidth) {
			x = 0;
		}
		else {
			x = startOffsetLeft;
		}
	}

// selection right of viewport
	else if (startOffsetLeft > frameScrollLeft + wikEdFrameBody.clientWidth) {
		x = endOffsetRight - wikEdFrameBody.clientWidth;
	}

// do scroll
	wikEdFrameWindow.scrollTo(x, y);

	return;
};


//
// WikEdWikiTableToHtml: convert wiki tables to html // {{TABLE}}
//

window.WikEdWikiTableToHtml = function(obj) {

////
	return;
};


//
// WikEdTextify: strip html off of text
//

window.WikEdTextify = function(obj) {

// convert html to plain
	obj.plain = obj.html;
	obj.plain = obj.plain.replace(/ \n|\n /g, ' ');
	obj.plain = obj.plain.replace(/\n/g, ' ');

// delete tags
	obj.plain = obj.plain.replace(/<(style|script|object|applet|embed)\b[^>]*>.*?<\/\1>/g, '');

// newlines
	obj.plain = obj.plain.replace(/[\n ]*<br\b[^>]*>[\n ]*()/g, '\n');

// remove empty lines from block tags
	obj.plain = obj.plain.replace(/(<(blockquote|center|div|p|pre|gallery)\b[^>]*>)[\s\x00]+/gi, '$1');
	obj.plain = obj.plain.replace(/[\s\x00]+(<\/(blockquote|center|div|p|pre|gallery|source|poem|categorytree|hiero|imagemap|inputbox|timeline|references)>)/gi, '$1');

// remove highlighting pre tags
	var isRemove = [];
	obj.plain = obj.plain.replace(/(<(\/?)pre\b([^>]*)>)/g,
		function (p, p1, p2, p3) {
			if (p2 == '') {
				if (/\bclass=\"wikEd[\w\/]+\"/.test(p3) == true) {
					isRemove.push(true);
					return('');
				}
				isRemove.push(false);
				return(p1);
			}
			if (isRemove.pop() == true) {
				return('');
			}
			return(p1);
		}
	);

// blocks
	obj.plain = obj.plain.replace(/<\/?(address|blockquote|center|div|hr|isindex|p|pre)\b.*?>/g, '\x00\x00');

// keep headings only if starting with a newline
	obj.plain = obj.plain.replace(/[\s|\x00]*(^|\n|\x00)[\s|\x00]*<h[1-6]\b[^>]*>(.*?)<\/h[1-6]>[\s|\x00]*()/g, '\x00\x00$2\x00\x00');

// lists
	obj.plain = obj.plain.replace(/<\/?(dir|dl|menu|ol|ul)\b.*?>/g, '\x00');
	obj.plain = obj.plain.replace(/<\/(dd|dt|li)>/g, '\x00');

// forms
	obj.plain = obj.plain.replace(/<\/?(select|textarea)\b.*?>/g, '\x00');
	obj.plain = obj.plain.replace(/<\/(option|legend|optgroup)>/g, '\x00');

// tables
	if (wikEdTableMode == true) {

// override pasted table class // {{TABLE}}
		obj.plain = obj.plain.replace(/(<table\b)([^>]*)(>)/gi,
			function (p, p1, p2, p3) {
				if (p2.match(/\bclass=/) != null) {
					p2 = p2.replace(/\bclass\s*=\s*([\'\"]?)[^<>\'\"\n]*?\1/g, 'class="wikEdTableEdit"');
				}
				else {
					p2 = ' class="wikEdTableEdit"';
				}
				return(p1 + p2 + p3);
			}
		);

// keep table html markup // {{TABLE}}
		obj.plain = obj.plain.replace(/[\s\x00]*(<table\b.*?>)/g, '\x00\x00$1');
		obj.plain = obj.plain.replace(/(<\/table>)[\s\x00]*()/g, '$1\x00');

		obj.plain = obj.plain.replace(/<(\/?(table|caption|tr|th|td)\b[^>]*)>/g, '\x01$1\x02');
	}

// textify table
	else if (wikEdTableMode == false) {
		obj.plain = obj.plain.replace(/<\/?(table|caption)\b.*?>/g, '\x00');
		obj.plain = obj.plain.replace(/<\/(tr|th|td)>/g, '\x00');
	}

// finish html to plain conversion
	obj.plain = obj.plain.replace(/<.*?>/g, '');

// recover table html
	obj.plain = obj.plain.replace(/\x01/g, '<');
	obj.plain = obj.plain.replace(/\x02/g, '>');

// remove spaces
	obj.plain = obj.plain.replace(/[ \t\xa0]+(\x00)/g, '$1');
	obj.plain = obj.plain.replace(/(\x00)[ \t\xa0]+/g, '$1');

// trim down \x00 and \n
	obj.plain = obj.plain.replace(/\x00+\n/g, '\n');
	obj.plain = obj.plain.replace(/\n\x00+/g, '\n');
	obj.plain = obj.plain.replace(/\n*\x00(\x00|\n)+/g, '\n\n');
	obj.plain = obj.plain.replace(/\x00/g, '\n');
	obj.plain = obj.plain.replace(/(<\/table>\n)\n+/g, '$1');

// remove empty lines and spaces from article start and end
	if (obj.from == 'whole') {
		obj.plain = obj.plain.replace(/^\s+|\s+$/g, '');
	}

	return;
};


//
// WikEdInactiveButtons: grey out inactive buttons, called after every change and click
//

window.WikEdInactiveButtons = function() {

// undo
	if (wikEdFrameDocument.queryCommandEnabled('undo') == true ) {
		document.getElementById('wikEdUndo').className = 'wikEdButton';
		document.getElementById('wikEdUndoAll').className = 'wikEdButton';
	}
	else {
		document.getElementById('wikEdUndo').className = 'wikEdButtonInactive';
		document.getElementById('wikEdUndoAll').className = 'wikEdButtonInactive';
	}

// redo
	if (wikEdFrameDocument.queryCommandEnabled('redo') == true ) {
		document.getElementById('wikEdRedo').className = 'wikEdButton';
	}
	else {
		document.getElementById('wikEdRedo').className = 'wikEdButtonInactive';
	}

// redo all
	if (wikEdLastVersion != null) {
		document.getElementById('wikEdRedoAll').className = 'wikEdButton';
	}
	else {
		document.getElementById('wikEdRedoAll').className = 'wikEdButtonInactive';
	}
	return;
};


//
// WikEdFixBasic: fix characters, spaces, empty lines, certain headings, needed for all fixing functions
//

//// change: double spaces ok after dot

window.WikEdFixBasic = function(obj) {

// non-breaking space character to normal space
	obj.plain = obj.plain.replace(/\xa0/g, ' ');

// tab to space
	obj.plain = obj.plain.replace(/ *\t[ \t]*()/g, ' ');

// remove trailing spaces
	obj.plain = obj.plain.replace(/(\t| |&nbsp;)+\n/g, '\n');

// remove spaces in empty lines
	obj.plain = obj.plain.replace(/\n( |&nbsp;|\t)+\n/g, '\n\n');

// empty line before and after headings, spaces around word (lookahead), remove bold, italics, and extra =
	obj.plain = obj.plain.replace(/(^|\n)+(=+) *(.*?) *(=+)(?=(\n|$))/g,
		function(p, p1, p2, p3, p4) {
			p3 = p3.replace(/\'{2,}/g, '');
			return('\n\n' + p2 + ' ' + p3 + ' ' + p2 + '\n\n');
		}
	);

// uppercase well known headings
	var regExp = new RegExp('\\n=+ ' + wikEdText['External links'] + '? =+\\n', 'gi');
	obj.plain = obj.plain.replace(regExp, '\n== ' + wikEdText['External links'] + ' ==\n');
	regExp = new RegExp('\\n=+ ' + wikEdText['See also'] + ' =+\\n', 'gi');
	obj.plain = obj.plain.replace(regExp, '\n== ' + wikEdText['See also'] + ' ==\n');
	regExp = new RegExp('\\n=+ ' + wikEdText['References'] + '? =+\\n', 'gi');
	obj.plain = obj.plain.replace(regExp, '\n== ' + wikEdText['References'] + ' ==\n');

// add space after * # : ; (list) and after {| |- |+ | (table), spare #REDIRECT
	obj.plain = obj.plain.replace(/(^|\n)([\*\#\:\;]+|\{\||\|\-|\|\+|\|(?!\})) *()/g, '$1$2 ');
	obj.plain = obj.plain.replace(/(^|\n)# REDIRECT\b/gi, '$1#REDIRECT');
	obj.plain = obj.plain.replace(/ +(?=\n)/g, '');

// empty line before and after tables
	obj.plain = obj.plain.replace(/\n+(\{\|)/g, '\n\n$1');
	obj.plain = obj.plain.replace(/(\n\|\}([^\}]|$)) *(.*)[\n|$]+/g, '$1\n\n$3\n\n');

// empty line before and after lists
	obj.plain = obj.plain.replace(/(^|\n)([^\*\#\:\;\n].*)(?=\n[\*\#\:\;])/g, '$1$2\n\n');
	obj.plain = obj.plain.replace(/(^|\n)([\*\#\:\;].*?)(?=\n[^\*\#\:\;\n])/g, '$1$2\n\n');

// split into lines and change single lines, used to handle tables
	var lines = obj.plain.split('\n');
	obj.plain = '';
	var tableflag = false;

	for (var i = 0; i < lines.length; i++) {
		var line = lines[i];

// do not change lines starting with a blank
		if (/^ /.test(line) == false) {

// detect table
			if (line.match(/^(\{\||\!|\|[^}])/) != null) {
				tableflag = true;
			}
			else if (line.match(/^\|\}/) != null) {
				tableflag = false;
			}

// changes only to be done in tables
			if (tableflag == true) {

// add spaces around ||
				line = line.replace(/ *\|\| *()/g, ' || ');
			}

// changes not to be done in tables
			if (! tableflag) {

// empty line before and after images
				var regExp = new RegExp('^(\\[\\[(Image|File|' + wikEdText['wikicode Image'] + '|' + wikEdText['wikicode File'] + '):.*?\\]\\])', 'ig');
				line = line.replace(regExp, '\n$1');

				regExp = new RegExp('(\\[\\[(Image|File|' + wikEdText['wikicode Image'] + '|' + wikEdText['wikicode File'] + '):.*?(\\[\\[.*?\\]\\].*?)*\\]\\])$', 'ig');
				line = line.replace(regExp, '$1\n');

// empty line before and after includes
				line = line.replace(/^(\{\{.*?\}\})/g, '\n$1');
				line = line.replace(/(\{\{.*?\}\})$/g, '$1\n');
			}
		}

// concatenate the lines
		obj.plain += line;
		if (i < lines.length - 1) {
			obj.plain += '\n';
		}
	}

// remove underscores in wikilinks
	obj.plain = obj.plain.replace(/\[\[(.*?)((\|.*?)|)\]\]/g,
		function (p, p1, p2) {
			p1 = p1.replace(/_/g, ' ');
			return('[[' + p1 + p2 + ']]');
		}
	);

// remove spaces in wikilinks, protect [[xxx| ]]
	obj.plain = obj.plain.replace(/\[\[ *([^\|\[\]]*?) *\| +\]\]/g, '[[$1|\x00]]');
	obj.plain = obj.plain.replace(/\[\[ *([^\|\[\]]*?) *\| *([^\[\][]*?) *\]\]/g, '[[$1|$2]]');
	obj.plain = obj.plain.replace(/\[\[ *([^\|\[\]]*) *\]\]/g, '[[$1]]');
	obj.plain = obj.plain.replace(/\x00/g, ' ');

// remove spaces in external links
	obj.plain = obj.plain.replace(/\[ *(.*?) *\](?!\])/g, '[$1]');

// no space around pipes before curly brackets
	obj.plain = obj.plain.replace(/ +\| +\}\}/g, '|}}');

// no empty line between headings and includes
	obj.plain = obj.plain.replace(/\n(=+ .*? =+\n)\n+(\{\{.*?\}\})/g, '\n$1$2');

// spaces in comments
	obj.plain = obj.plain.replace(/(&lt;!--) *(.*?) *(--&gt;)/g, '$1 $2 $3');

// empty line before and after categories
	var regExp = new RegExp('( |\\n)*(\\[\\[(Category|' + wikEdText['wikicode Category'] + ')\\s*:[^\\n]*?\\]\\])( |\\n)*', 'gi');
	obj.plain = obj.plain.replace(regExp, '\n\n$2\n\n');

// categories not separated by empty lines (lookahead)
	regExp = new RegExp('(\\[\\[(Category|' + wikEdText['wikicode Category'] + ')\\s*:[^\\n]*?\\]\\])\\n*(?=\\[\\[(Category|' + wikEdText['wikicode Category'] + ')\\s*:[^\\n]*?\\]\\])', 'gi');
	obj.plain = obj.plain.replace(regExp, '$1\n');

// single empty lines only
	obj.plain = obj.plain.replace(/\n{3,}/g, '\n\n');

// remove leading and trailing newlines
	obj.plain = obj.plain.replace(/^\n+/, '');
	obj.plain = obj.plain.replace(/\n{2,}$/, '\n');

	return;
};


//
// WikEdFixPunct: remove (or add) space before .,:;
//

window.WikEdFixPunct = function(obj) {

	WikEdFixBasic(obj);

// protect punctuation in charents
	obj.plain = obj.plain.replace(/(&([a-zA-Z0-9]{2,10}|#[0-9]{2,7}))(;)/g, '$1\x00$3');

// protect punctuation in URLs
	obj.plain = obj.plain.replace(/(\b(http:\/\/|https:\/\/|ftp:\/\/|irc:\/\/|gopher:\/\/|news:|mailto:|file:\/\/)[\!\#\%\&\(\)\+-\/\:\;\=\?\@\w\~ŠŒŽœžŸŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9]*)/g,
		function(p, p1, p2) {
			p = p.replace(/([\.\,\:\;\?\!](?!$))/g, '\x00$1');
			return(p);
		}
	);

// protect punctuation in filenames
	obj.plain = obj.plain.replace(/([a-zA-Z_ŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9\-])([\.\,\:\;\?\!])(?=([a-zA-Z]{3,4})([\s\:\;\?\!\.\,\(\)\[\]\{\}\|]|$))/g, '$1\x00$2');

// protect punctuation in article names
	obj.plain = obj.plain.replace(/(\[\[|\{\{)([^\]\}\|\n]*)/g,
		function(p, p1, p2) {
			p = p.replace(/([\.\,\:\;\?\!])/g, '\x00$1');
			return(p);
		}
	);

	if (wikEdFixPunctFrench == true) {
		obj.plain = obj.plain.replace(/(«) *()/g, '$1 ');
		obj.plain = obj.plain.replace(/ *(»)/g, ' $1');
		obj.plain = obj.plain.replace(/([a-zA-Z_ŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9\'\"”\]\}\)]) *([\.\,])(?=([a-zA-ZŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9\'\"”\[\{\(\s]|$))/g, '$1$2 ');
		obj.plain = obj.plain.replace(/([a-zA-Z_ŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9\'\"”\]\}\)]) *([\:\;\?\!])/g, '$1 $2 ');
	}
	else {
		obj.plain = obj.plain.replace(/([a-zA-Z_ŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9\'\"”\]\}\)]) *([\.\,\:\;])(?=([a-zA-ZŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9\'\"”\[\{\(\s]|$))/g, '$1$2 ');
	}
	obj.plain = obj.plain.replace(/\x00/g, '');
	obj.plain = obj.plain.replace(/ +$/g, '');
	obj.plain = obj.plain.replace(/ +\n/g, '\n');
	obj.plain = obj.plain.replace(/ {2,}/g, ' ');

	return;
};


//
// WikEdFixUnicode: fix unicode character representations
//

window.WikEdFixUnicode = function(obj) {

// replace supported chars: change decimal, hex, and character entities into actual char
	for (var i = 0; i < wikEdSupportedChars.length; i ++) {
		var replaceChar = String.fromCharCode(parseInt(wikEdSupportedChars[i][0], 16));

// decimal representation
		var regExpStr = '&amp;#0*' + parseInt(wikEdSupportedChars[i][0], 16) + ';|';

// hex representation
		regExpStr += '&amp;#x0*' + wikEdSupportedChars[i][0] + ';';

// case insensitive replace
		var regExp = new RegExp(regExpStr, 'gi');
		obj.plain = obj.plain.replace(regExp, replaceChar);

// character entity representation
		regExpStr = '&amp;' + wikEdSupportedChars[i][1] + ';';

// case sensitive replace
		var regExp = new RegExp(regExpStr, 'g');
		obj.plain = obj.plain.replace(regExp, replaceChar);
	}

// replace unsupported chars in IE6: change decimal, hex, and chars into character entities
	for (var i = 0; i < wikEdProblemChars.length; i ++) {
		var replaceChar = '&amp;' + wikEdProblemChars[i][1] + ';';

// decimal representation
		var regExpStr = '&amp;#0*' + parseInt(wikEdProblemChars[i][0], 16) + ';|';

// hex representation
		regExpStr += '&amp;#x0*' + wikEdProblemChars[i][0] + ';';

// case insensitive replace
		var regExp = new RegExp(regExpStr, 'gi');
		obj.plain = obj.plain.replace(regExp, replaceChar);

// actual character representation
		regExpStr = '\\u' + wikEdProblemChars[i][0];

// case sensitive replace
		var regExp = new RegExp(regExpStr, 'g');
		obj.plain = obj.plain.replace(regExp, replaceChar);
	}

// replace special chars (spaces and invisible characters): change decimal, hex, and chars into character entities
	for (var i = 0; i < wikEdSpecialChars.length; i ++) {
		var replaceChar = '&amp;' + wikEdSpecialChars[i][1] + ';';

// decimal representation
		var regExpStr = '&amp;#0*' + parseInt(wikEdSpecialChars[i][0], 16) + ';|';

// hex representation
		regExpStr += '&amp;#x0*' + wikEdSpecialChars[i][0] + ';';

// case insensitive replace
		var regExp = new RegExp(regExpStr, 'gi');
		obj.plain = obj.plain.replace(regExp, replaceChar);

// actual character representation
		regExpStr = '\\u' + wikEdSpecialChars[i][0];

// case sensitive replace
		var regExp = new RegExp(regExpStr, 'g');
		obj.plain = obj.plain.replace(regExp, replaceChar);
	}

// unicode line separator and paragraph separator
	obj.plain = obj.plain.replace(/\u2028/g, '\n');
	obj.plain = obj.plain.replace(/\u2029/g, '\n\n');

	return;
};


//
// WikEdFixRedirectCall: parse link targets into wikEdRedirectsCache object using AJAX API call
//

window.WikEdFixRedirectCall = function(obj) {

// check if api is enabled
	if ( (wikEdWikiGlobals['wgEnableAPI'] != 'true') || (wikEdScriptURL == '') ) {
		return;
	}

// reset redirects object
	wikEdRedirectsCache = {};

// get wiki links
	var url = '';

//              1 [[    2  2   3                   34#                4 5     6    6  5  ]]    1
	var regExpLink = /(\[\[\s*(:?)\s*([^\n#<>\[\]\{\}\|]+)(\s*#[^\n\[\]\|]*?)?(\s*\|(.|\s)*?)?\]\]\s*)/g;
	while ( (regExpMatch = regExpLink.exec(obj.plain)) != null) {
		url += encodeURIComponent(regExpMatch[3] + '|');
	}

// no wikilinks found
	if (url == '') {
		return;
	}

// make the ajax request
	url = wikEdScriptURL + 'api.php?action=query&redirects&format=xml&titles=' + url;
	WikEdAjaxRequest('GET', url, null, null, null, null, function(ajax, obj) {

// get response
		var txt = ajax.responseText;

		if ( (regExpMatch = txt.match(/<redirects>((.|\s)*?)<\/redirects>/)) != null) {
			var redirects = regExpMatch[1];

			if ( (regExpMatch = txt.match(/<normalized>((.|\s)*?)<\/normalized>/)) != null) {
				redirects = regExpMatch[1] + redirects;
			}

// parse redirects
			var i = 0;
			wikEdRedirectsCache.from = [];
			wikEdRedirectsCache.to = [];
			wikEdRedirectsCache.allFrom = '';

			var regExpRedir = /<(r|n) .*?\bfrom=\"([^\">]*)\".*?\bto=\"([^\"]*)\".*?>/g;
			while ( (regExpMatch = regExpRedir.exec(txt)) != null) {
				wikEdRedirectsCache.from[i] = regExpMatch[2];
				wikEdRedirectsCache.allFrom += i + '="' + regExpMatch[2] + '"';
				wikEdRedirectsCache.to[i] = regExpMatch[3];
				i ++;
			}

// recurse through chained normalizations and redirects
			wikEdRedirectsCache.toIndex = [];
			for (var i = 0; i < wikEdRedirectsCache.to.length; i ++) {
				wikEdRedirectsCache.toIndex[i] = WikEdResolveRedirects(i);
			}

		}

// replace links
		WikEdEditButton(null, 'wikEdFixRedirectReplace');

		return;
	});
// end Ajax handler

	return;
};


//
// WikEdResolveRedirects: recursively follow redirects, called from WikEdFixRedirectCall Ajax handler
//   uses wikEdRedirectsCache.allFrom as a regExp hash

window.WikEdResolveRedirects = function(i) {
	var toRegExp = wikEdRedirectsCache.to[i].replace(/(\W)/g, '\\$1');
	var regExp = new RegExp('(\\d+)=\\"' + toRegExp + '\\"');
	if ( (regExpMatch = wikEdRedirectsCache.allFrom.match(regExp)) != null) {
		i = WikEdResolveRedirects( parseInt(regExpMatch[1], 10) );
	}
	return(i);
};


//
// WikEdFixRedirectReplace: replace redirects using wikEdRedirectsCache object prepared in WikEdFixRedirectCall()
//

window.WikEdFixRedirectReplace = function(obj) {

	if (wikEdRedirectsCache.from == null) {
		return;
	}

// cycle through parsed redirects
	if (wikEdRedirectsCache.from != null) {
		for (var i = 0; i < wikEdRedirectsCache.from.length; i ++) {

//                                       1  1    2                              23    #                 3 4      |56     6  54
			var regExp = new RegExp('\\[\\[\\s*(:?)\\s*(' + wikEdRedirectsCache.from[i] + ')(\\s*#[^\\n\\[\\]\\|]*?)?(\\s*\\|((.|\\s)*?))?\\s*\\]\\]', 'g');
			obj.plain = obj.plain.replace(regExp,
				function(p, p1, p2, p3, p4, p5) {
					var prefix = p1;
					var article = p2;
					var redirect = wikEdRedirectsCache.to[ wikEdRedirectsCache.toIndex[i] ];
					var fragmentId = p3 || '';
					var linkText = p5 || '';

// use normalized target
					var linkTarget = redirect;

// lowercase link target if link text starts with lowercase (main space only)
					if (wikEdArticlesCaseSensitive == false) {
						if (/:/.test(linkTarget) != true) {
							if (article.charAt(0).toLowerCase() == article.charAt(0)) {
								linkTarget = linkTarget.charAt(0).toLowerCase() + linkTarget.substr(1);
							}
						}
					}

// remove text if identical to new target
					if (linkText != '') {
						if ( linkText.replace(/_/g, ' ') == linkTarget ) {
							linkText = '';
						}
					}

// keep replaced link as link text
					else if (linkText == '') {
						if (linkTarget != article) {
							linkText = article;
						}
					}

// return fixed link
					var wikiLink;
					if (linkText == '') {
						wikiLink = '[[' + prefix + linkTarget + fragmentId + ']]';
					}
					else {
						wikiLink = '[[' + prefix + linkTarget + fragmentId + '|' + linkText + ']]';
					}
					return(wikiLink);
				}
			);
		}
	}
	return;
};


//
// WikEdFixMath: math character fixer, originally from User:Omegatron
//

window.WikEdFixMath = function(obj) {

	WikEdFixBasic(obj);

// change only outside <math> </math> wikicode
	obj.plain = obj.plain.replace(/(.*?)((&lt;math(\b.*?)&gt;.*?&lt;\/math&gt;)|$)/gi,
		function (p, p1, p2) {

// convert html entities into actual dash characters
			p1 = p1.replace(/&plus;/g, '+');
			p1 = p1.replace(/&minus;/g, '\u2212');
			p1 = p1.replace(/&middot;/g, '·');

// convert dash next to a number into a minus sign character
			p1 = p1.replace(/([^\wŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9\,\{])-(\d)/g, '$1\u2212$2');

// changes 2x3 to 2×3
			p1 = p1.replace(/(\d *)x( *\d)/g, '$1\xd7$2');

// changes 10^3 to 10<sup>3</sup>
			p1 = p1.replace(/(\d*\.?\d+)\^(\u2212?\d+\.?\d*)/g, '$1&lt;sup&gt;$2&lt;/sup&gt;');

// change x^3 to x<sup>3</sup>
			p1 = p1.replace(/([\wŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9])\^(\u2212?\d+\.?\d*) /g, '$1&lt;sup&gt;$2&lt;/sup&gt;');

// change +/- to ±
			p1 = p1.replace(/( |\d)\+\/(-|\u2212)( |\d)/g, '$1\xb1$3');

// htmlize single char superscripts
			p1 = p1.replace(/(\xb9|&sup1;)/g, '&lt;sup&gt;1&lt;/sup&gt;');
			p1 = p1.replace(/(\xb2|&sup2;)/g, '&lt;sup&gt;2&lt;/sup&gt;');
			p1 = p1.replace(/(\xb3|&sup3;)/g, '&lt;sup&gt;3&lt;/sup&gt;');

			return(p1 + p2);
		}
	);
	return;
};


//
// WikEdFixChem: fix chemical formulas
//

window.WikEdFixChem = function(obj) {

	WikEdFixBasic(obj);

	var realElements = 'H|He|Li|Be|B|C|N|O|F|Ne|Na|Mg|Al|Si|P|S|Cl|Ar|K|Ca|Sc|Ti|V|Cr|Mn|Fe|Co|Ni|Cu|Zn|Ga|Ge|As|Se|Br|Kr|Rb|Sr|Y|Zr|Nb|Mo|Tc|Ru|Rh|Pd|Ag|Cd|In|Sn|Sb|Te|I|Xe|Cs|Ba|Hf|Ta|W|Re|Os|Ir|Pt|Au|Hg|Tl|Pb|Bi|Po|At|Rn|Fr|Ra|Rf|Db|Sg|Bh|Hs|Mt|Ds|Rg|La|Ce|Pr|Nd|Pm|Sm|Eu|Gd|Tb|Dy|Ho|Er|Tm|Yb|Lu|Ac|Th|Pa|U|Np|Pu|Am|Cm|Bk|Cf|Es|Fm|Md|No|Lr';
	var pseudoElements = '|Me|Et|Pr|Bu|e';

// fix common typos
	obj.plain = obj.plain.replace(/\bh2o\b/g, 'H2O');
	obj.plain = obj.plain.replace(/\bh3o+/g, 'H3O+');
	obj.plain = obj.plain.replace(/\boh-/g, 'OH-');

// uppercase lowercased elements
	var regExp = new RegExp('(^|[^a-zA-Z])(' + realElements.toLowerCase() + pseudoElements.toLowerCase() + ')([^a-zA-Z]|$)', 'g');
	obj.plain = obj.plain.replace(regExp,
		function (p, p1, p2, p3) {
			if (p2 != 'e') {
				p2 = p2.charAt(0).toUpperCase() + p2.substr(1).toLowerCase();
			}
			return(p1 + p2 + p3);
		}
	);

// fix superscripts
	obj.plain = obj.plain.replace(/&plus;/g, '+');
	obj.plain = obj.plain.replace(/&minus;/g, '\u2212');
	obj.plain = obj.plain.replace(/&middot;/g, '·');
	regExp = new RegExp('(' + realElements + pseudoElements + '|\\))(\\d*(\\+|-|\\u2212))', 'g');
	obj.plain = obj.plain.replace(regExp,
		function (p, p1, p2, p3) {
			p2 = p2.replace(/-/g, '\u2212');
			return(p1 + '&lt;sup&gt;' + p2 + '&lt;/sup&gt;');
		}
	);

// fix indices
	regExp = new RegExp('(' + realElements + pseudoElements + '|\\))(\\d+)', 'g');
	obj.plain = obj.plain.replace(regExp, '$1&lt;sub&gt;$2&lt;/sub&gt;');

// fix prefixes
	regExp = new RegExp('(\\d+) *(\\(|' + realElements + pseudoElements + ')', 'g');
	obj.plain = obj.plain.replace(regExp, '$1$2');

// fix arrows
	obj.plain = obj.plain.replace(/ *-+&gt; *()/g, ' \u2192 ');
	obj.plain = obj.plain.replace(/ *&lt;-+ *()/g, ' \u2190 ');

// &hdarr; and "leftwards harpoon over rightwards harpoon" not supported in IE6
//	obj.plain = obj.plain.replace(/ *(&lt;=+&gt;|&hdarr;|&harr;|\u2190 *\u2192) *()/g, ' \u21cc ');
	obj.plain = obj.plain.replace(/ *(&lt;==+&gt;|&hdarr;|&harr;|\u21cc|\u2190 *\u2192) *()/g, ' <=> ');

// fix -
	obj.plain = obj.plain.replace(/([\wŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9]|\)|&gt;) +(-|\u2212) +([\wŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9]|\()/g, '$1 \u2212 $3');

	return;
};


//
// WikEdFixUnits: unit formatter
//

window.WikEdFixUnits = function(obj) {

	WikEdFixBasic(obj);

// convert into actual characters
	obj.plain = obj.plain.replace(/&amp;deg;|&amp;#00b0;/g, '°');
	obj.plain = obj.plain.replace(/&amp;#00b5;|&amp;mu;|&amp;micro;/g, 'µ');
	obj.plain = obj.plain.replace(/&amp;Omega;|&amp;#8486;/g, '\u03a9');

// add space before units, remove space around /, and use abreviations
	obj.plain = obj.plain.replace(/( *\/ *|\d *)(Y|yotta|Z|zetta|E|exa|P|peta|T|tera|G|giga|M|mega|k|kilo|K|h|hecto|da|deca|d|deci|c|centi|m|mill?i|micro|u|µ|n|nano|p|pico|f|femto|a|atto|z|zepto|y|yocto|mibi|mebi|)(gramm?s?|g|metres?|meters?|m|amperes?|Amperes?|amps?|Amps?|A|Angstroms?|Angströms?|Å|Kelvins?|kelvins?|K|moles?|Moles?|mol|candelas?|cd|rad|Ci|sr|Hert?z|hert?z|Hz|newtons?|Newtons?|N|Joules?|joules?|J|watts?|Watts?|W|pascals?|Pascals?|Pa|lm|lx|C|volts?|Volts?|V|O|Farads?|F|Wb|T|H|S|bequerels?|Bequerels?|Bq|Gy|Sv|kat|centigrades?|°C|decibels?|db|dB|M|ohms?|Ohms?|\u03a9|sec|seconds?|s|minutes?|min|hour?|h|bits?|Bits?|bit|bytes?|Bytes?|B|bps|Bps)(?=[^\wŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9]|$)/g,
		function (p, p1, p2, p3) {

			p1 = p1.replace(/ *\/ *()/g, '/');
			p1 = p1.replace(/(\d) *()/g, '$1 ');

			p2 = p2.replace(/yotta/g, 'Y');
			p2 = p2.replace(/zetta/g, 'Z');
			p2 = p2.replace(/exa/g, 'E');
			p2 = p2.replace(/peta/g, 'P');
			p2 = p2.replace(/tera/g, 'T');
			p2 = p2.replace(/giga/g, 'G');
			p2 = p2.replace(/mega/g, 'M');
			p2 = p2.replace(/kilo/g, 'k');
			p2 = p2.replace(/K/g, 'k');
			p2 = p2.replace(/hecto/g, 'h');
			p2 = p2.replace(/deca/g, 'da');
			p2 = p2.replace(/deci/g, 'd');
			p2 = p2.replace(/centi/g, 'c');
			p2 = p2.replace(/mill?i/g, 'm');
			p2 = p2.replace(/micro|u/g, 'µ');
			p2 = p2.replace(/nano/g, 'n');
			p2 = p2.replace(/pico/g, 'p');
			p2 = p2.replace(/femto/g, 'f');
			p2 = p2.replace(/atto/g, 'a');
			p2 = p2.replace(/zepto/g, 'z');
			p2 = p2.replace(/yocto/g, 'y');
			p2 = p2.replace(/mibi/g, 'mebi');

			p3 = p3.replace(/gramm?s?/g, 'g');
			p3 = p3.replace(/metres?|meters?/g, 'm');
			p3 = p3.replace(/amperes?|Amperes?|amps?|Amps?/g, 'A');
			p3 = p3.replace(/Angstroms?|Angströms?/g, 'Å');
			p3 = p3.replace(/Kelvins?|kelvins?/g, 'K');
			p3 = p3.replace(/moles?|Moles?/g, 'mol');
			p3 = p3.replace(/candelas?/g, 'cd');
			p3 = p3.replace(/Hert?z|hert?z/g, 'Hz');
			p3 = p3.replace(/newtons?|Newtons?/g, 'N');
			p3 = p3.replace(/Joules?|joules?/g, 'J');
			p3 = p3.replace(/watts?|Watts?/g, 'W');
			p3 = p3.replace(/pascals?|Pascals?/g, 'Pa');
			p3 = p3.replace(/volts?|Volts?/g, 'V');
			p3 = p3.replace(/ohms?|Ohms?/g, '\u03a9');
			p3 = p3.replace(/bequerels?|Bequerels?/g, 'Bq');
			p3 = p3.replace(/Farads?/g, 'F');
			p3 = p3.replace(/bits?|Bits?/g, 'bit');
			p3 = p3.replace(/bytes?|Bytes?/g, 'B');
			p3 = p3.replace(/sec|seconds?/g, 's');
			p3 = p3.replace(/minutes?/g, 'min');
			p3 = p3.replace(/hours?/g, 'h');
			p3 = p3.replace(/sec|seconds?/g, 's');
			p3 = p3.replace(/bps/g, 'bit/s');
			p3 = p3.replace(/Bps/g, 'B/s');

			return(p1 + p2 + p3);
		}
	);

// fix prefix casing
	obj.plain = obj.plain.replace(/ K(bit\/s|B\/s)([^\wŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9]|$)/g, ' k$1$2');
	obj.plain = obj.plain.replace(/ m(bit\/s|B\/s)([^\wŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9]|$)/g, ' M$1$2');
	obj.plain = obj.plain.replace(/ g(bit\/s|B\/s)([^\wŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9]|$)/g, ' G$1$2');
	obj.plain = obj.plain.replace(/ t(bit\/s|B\/s)([^\wŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9]|$)/g, ' T$1$2');
	obj.plain = obj.plain.replace(/ e(bit\/s|B\/s)([^\wŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9]|$)/g, ' E$1$2');

	return;
};


//
// WikEdFixDashes: fixes dashes and minus signs
//

window.WikEdFixDashes = function(obj) {

	WikEdFixBasic(obj);

// convert html character entities into actual dash characters
	obj.plain = obj.plain.replace(/&amp;mdash;/g, '—');
	obj.plain = obj.plain.replace(/&amp;ndash;/g, '–');
	obj.plain = obj.plain.replace(/&amp;minus;/g, '\u2212');

// remove spaces around em dashes
	obj.plain = obj.plain.replace(/([a-zA-Z\'\"”\]\}\)])( |&amp;nbsp;)*—( |&amp;nbsp;)*([a-zA-Z\'\"“\[\{\(])/g, '$1—$4');

// convert -- to em dashes
	obj.plain = obj.plain.replace(/([a-zA-Z\'\"”\]\}\)])( |&amp;nbsp;)*--( |&amp;nbsp;)*([a-zA-Z\'\"“\[\{\(])/g, '$1—$4');

// convert hyphen next to lone number into a minus sign character
	obj.plain = obj.plain.replace(/([a-zA-Z\'\"”\]>] ) *(\u2212|–)(\d)/g, '$1\u2212$3');

// convert minus or en dashes to dashes with spaces
	obj.plain = obj.plain.replace(/([a-zA-Z\'\"”\]\}])( |&amp;nbsp;)*(\u2212|–)( |&amp;nbsp;)*([a-zA-Z\'\"“\[\{])/g, '$1 – $5');

// convert dashes to en dashes in dates
	obj.plain = obj.plain.replace(/(^|[ \(\|])(\d\d(\d\d)?)(\u2212|-|–)(\d\d)(\u2212|-|–)(\d\d(\d\d)?)([ \)\}\|,.;—]|$)/gm, '$1$2–$5–$7$9');

	return;
};


//
// WikEdFixHTML: fix html to wikicode
//

window.WikEdFixHTML = function(obj) {

	WikEdFixBasic(obj);

// remove syntax highlighting
	obj.html = obj.plain;
	obj.html = obj.html.replace(/\n/g, '<br>');
	WikEdRemoveHighlighting(obj);

// keep <br> in blockquote
	obj.html = obj.html.replace(/(&lt;blockquote\b.*?&gt;)([\S\s]*?)(&lt;\/blockquote&gt;)/gi,
		function (p, p1, p2, p3) {
			p2 = p2.replace(/&lt;(br\b.*?)&gt;<br\b.*?>/g, '\x00$1\x01\n');
			return(p1 + p2 + p3);
		}
	);

// keep <br> in tables (and certain templates!?)
	obj.html = obj.html.replace(/(<br\b[^>]*>\|)([^\}][\S\s]*?)(?=<br\b[^>]*>\|)/gi,
		function (p, p1, p2) {
			p2 = p2.replace(/&lt;(br\b.*?)&gt;/g, '\x00$1\x01');
			return(p1 + p2);
		}
	);

// detect outermost template tags
	var depth = 0;
	obj.html = obj.html.replace(/((\{\{)|\}\})/g,
		function (p, p1, p2) {
			if (p2 != '') {
				depth ++;
				if (depth == 1) {
					return('<!--wikEdOuterTemplateStart-->' + p1);
				}
				return(p1);
			}
			depth --;
			if (depth == 0) {
				return(p1 + '<!--wikEdOuterTemplateEnd-->');
			}
			return(p1);
		}
	);

// keep <br> in templates
	obj.html = obj.html.replace(/<!--wikEdOuterTemplateStart-->([\S\s]*?)<!--wikEdOuterTemplateEnd-->/g,
		function (p, p1) {
			return(p1.replace(/&lt;(br\b.*?)&gt;/g, '\x00$1\x01'));
		}
	);

// detect outermost table tags
	var depth = 0;
	obj.html = obj.html.replace(/(((^|<br\b[^>]*>)\{\|)|<br\b[^>]*>\|\})/g,
		function (p, p1, p2, p3) {
			if (p2 != '') {
				depth ++;
				if (depth == 1) {
					return('<!--wikEdOuterTableStart-->' + p1);
				}
				return(p1);
			}
			depth --;
			if (depth == 0) {
				return(p1 + '<!--wikEdOuterTableEnd-->');
			}
			return(p1);
		}
	);

// keep <br> in tables
	obj.html = obj.html.replace(/<!--wikEdOuterTableStart-->([\S\s]*?)<!--wikEdOuterTableEnd-->/g,
		function (p, p1) {
			return(p1.replace(/&lt;(br\b.*?)&gt;/g, '\x00$1\x01'));
		}
	);

// turn visible html code into real html, exclude comments
	obj.html = obj.html.replace(/&lt;(\/?\w.*?)&gt;/g, '<$1>');

// restore valid <br>s
	obj.html = obj.html.replace(/\x00(.*?)\x01/g, '&lt;$1&gt;');

// wikify, keep user added attribute
	WikEdWikifyHTML(obj, true);

// turn real html into visible html code
	obj.html = obj.html.replace(/<br\b[^>]*>[\n ]*()/g, '\n');
	obj.html = obj.html.replace(/</g, '&lt;');
	obj.html = obj.html.replace(/>/g, '&gt;');
	obj.plain = obj.html;

	return;
};


//
// WikEdFixCaps: fix capitalizing of lists, linklists, images, headings
//

window.WikEdFixCaps = function(obj) {

	WikEdFixBasic(obj);

// uppercase lists
// start (listcode (char-ent|tag|category..|digit|non-word,non-ret))(word,non-digit..) end
	obj.plain = obj.plain.replace(/^((\||[\*\#\:\;]+)[ \'\"]*(\'+|\&\w+\;|&lt;.*?&gt;|\{\{.*?\}\}.*|\d|[^\wŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9\n])*)([^\wŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9\d\n].*?)?$/gm,
		function (p, p1, p2, p3, p4) {
			if (p4.match(/^(http|ftp|alpha|beta|gamma|delta|epsilon|kappa|lambda|$)/) == null) {

// spaces cannot be added to p1 in above regExp !?
				p4 = p4.replace(/^(\s*)(.*?)$/,
					function (p, p1, p2) {
						p2 = p2.charAt(0).toUpperCase() + p2.substr(1);
						return(p1 + p2);
					}
				);
			}
			return(p1 + p4);
		}
	);

// uppercase link lists (link)
//                                12 table list   2            13   34    4
	obj.plain = obj.plain.replace(/^((\||[\*\#\:\;]+)[ \'\"]*\[\[)(.*?)(\]\])/gm,
		function (p, p1, p2, p3,p4) {

// uppercase link
			p3 = p3.replace(/^((\&\w+\;|[^\wŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9]|\d)*)([a-zA-ZŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9_].*)$/,
				function (p, p1, p2, p3) {
					if (p3.match(/^(http|ftp|alpha|beta|gamma|delta|epsilon|kappa|lambda)/) == null) {
						p3 = p3.charAt(0).toUpperCase() + p3.substr(1);
					}
					return(p1 + p3);
				}
			);

// uppercase comment
			p3 = p3.replace(/(\| *(\&\w+\;|&lt;.*?&gt;|[^\wŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9\|]|\d)*)([a-zA-ZŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9_][^\|]*)$/,
				function (p, p1, p2, p3) {
					if (p3.match(/^(http|ftp|alpha|beta|gamma|delta|epsilon|kappa|lambda)/) == null) {
						p3 = p3.charAt(0).toUpperCase() + p3.substr(1);
					}
					return(p1 + p3);
				}
			);
			return(p1 + p3 + p4);
		}
	);

// uppercase headings
	obj.plain = obj.plain.replace(/^(=+ (\&\w+\;|&lt;.*?&gt;|\d|[^\wŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9\n])*)([a-zA-ZŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9_].*? =+)$/gm,
		function (p, p1, p2, p3) {
			if (p3.match(/^(http|ftp|alpha|beta|gamma|delta|epsilon|kappa|lambda)/) == null) {
				p3 = p3.charAt(0).toUpperCase() + p3.substr(1);
			}
			return(p1 + p3);
		}
	);

// uppercase images
	var regExp = new RegExp('(\\[\\[)(Image|File|' + wikEdText['wikicode Image'] + '|' + wikEdText['wikicode File'] + '):([\\wŠŒŽšœžŸÀ-ÖØ-öø-\\u0220\\u0222-\\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\\u0400-\\u0481\\u048a-\\u04ce\\u04d0-\\u04f5\\u04f8\\u04f9])([^\\n]*\\]\\])', 'igm');
	obj.plain = obj.plain.replace(regExp,
		function (p, p1, p2, p3, p4) {
			p2 = p2.charAt(0).toUpperCase() + p2.substr(1).toLowerCase();
			p3 = p3.toUpperCase();
			return(p1 + p2 + ':' + p3 + p4);
		}
	);

	return;
};


//
// WikEdFixTypos: fix typos using the AutoWikiBrowser/RegExTypoFix list (.test() is not faster)
//

window.WikEdFixTypos = function(obj) {

	WikEdFixBasic(obj);

//	split into alternating plain text and {{lang}} template fragments (does not support nested templates)
	var fragment = [];
	var nextPos = 0;
	var regExp = new RegExp('{{\\s*lang\\s*\\|(.|\\n)*?}}', 'gi');
	while ( (regExpMatch = regExp.exec(obj.plain)) != null) {
		fragment.push(obj.plain.substring(nextPos, regExpMatch.index));
		fragment.push(regExpMatch[0]);
		nextPos = regExp.lastIndex;
	}
	fragment.push(obj.plain.substring(nextPos));

// cycle through the RegExTypoFix rules
	for (var i = 0; i < wikEdTypoRulesFind.length; i ++) {

// cycle through the fragments, jump over {{lang}} templates
		for (var j = 0; j < fragment.length; j = j + 2) {
			fragment[j] = fragment[j].replace(wikEdTypoRulesFind[i], wikEdTypoRulesReplace[i]);
		}
	}

// re-assemble text
	obj.plain = fragment.join('');

	return;
};


//
// WikEdFixAll:
//

window.WikEdFixAll = function(obj) {
	WikEdFixBasic(obj);
	WikEdFixUnicode(obj);
	WikEdFixHTML(obj);
	WikEdFixCaps(obj);
	return;
};


//
// WikEdRemoveElements: remove elements by tag name
//

window.WikEdRemoveElements = function(tagNameArray) {

// cycle through the element names
	for (var i = 0; i < tagNameArray.length; i ++) {
		var elementArray = wikEdFrameDocument.getElementsByTagName(tagNameArray[i]);
		for (var j = 0; j < elementArray.length; j ++) {
			elementArray[j].parentNode.removeChild(elementArray[j]);
		}
	}
	return;
};


//
// WikEdFindBoundaries: find word boundaries and line boundaries starting from selection.range
//

window.WikEdFindBoundaries = function(word, line, para, whole, selection) {

// get the start node and offset
	var startNode = selection.range.startContainer;
	var startOffset = selection.range.startOffset;

// get the end node and offset
	var endNode = selection.range.endContainer;
	var endOffset = selection.range.endOffset;

	if (startNode.childNodes != null) {
		if (startNode.childNodes.length > 0) {
			startNode = startNode.childNodes.item(startOffset);
			startOffset = 0;
		}
	}
	if (endNode.childNodes != null) {
		if (endNode.childNodes.length > 0) {
			endNode = endNode.childNodes.item(endOffset);
			endOffset = 0;
		}
	}

// find the start and end nodes in the whole plain text arrays
	var startNodeIndex;
	var endNodeIndex;
	for (var i = 0; i < whole.plainNode.length; i ++) {
		if (startNode == whole.plainNode[i]) {
			startNodeIndex = i;
		}
		if (endNode == whole.plainNode[i]) {
			endNodeIndex = i;
			break;
		}
	}

// find last previous word and line boundary
	var foundWord = false;
	var foundLine = false;
	var foundPara = false;
	var regExp = new RegExp('.*[^\\w\\-ŠŒŽšœžŸÀ-ÖØ-öø-\\u0220\\u0222-\\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\\u0400-\\u0481\\u048a-\\u04ce\\u04d0-\\u04f5\\u04f8\\u04f9]', 'g');
	var plainPrev = '';

// check text nodes left-wise for a boundary
	var plain = '';
	for (var i = startNodeIndex; i >= 0; i --) {
		plainPrev = plain;
		plain = whole.plainArray[i];
		plain = plain.replace(/&lt;/g, '<');
		plain = plain.replace(/&gt;/g, '>');
		plain = plain.replace(/&amp;/g, '&');

// boundary is a new paragraph
		if ( (plainPrev == '\n') && (plain == '\n') ) {
			para.range.setStartAfter(whole.plainNode[i + 1]);
			foundPara = true;
			break;
		}

// boundary is a newline
		else if (plain == '\n') {
			if (foundWord == false) {
				word.range.setStartAfter(whole.plainNode[i]);
				foundWord = true;
			}
			if (foundLine == false) {
				line.range.setStartAfter(whole.plainNode[i]);
				foundLine = true;
			}
		}

// check text node for a word boundary
		else if (foundWord == false) {
			if (i == startNodeIndex) {
				plain = plain.substr(0, startOffset);
			}
			regExp.lastIndex = 0;
			if (regExp.exec(plain) != null) {
				WikEdSetRangeStart(word.range, whole.plainNode[i], regExp.lastIndex);
				foundWord = true;
			}
		}
	}

// boundary is start of text
	if (foundPara == false) {
		para.range.setStartBefore(whole.plainNode[0]);
	}
	if (foundLine == false) {
		line.range.setStartBefore(whole.plainNode[0]);
	}
	if (foundWord == false) {
		word.range.setStartBefore(whole.plainNode[0]);
	}

// find next word and line boundary
	regExp = new RegExp('[^\\w\\-ŠŒŽšœžŸÀ-ÖØ-öø-\\u0220\\u0222-\\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\\u0400-\\u0481\\u048a-\\u04ce\\u04d0-\\u04f5\\u04f8\\u04f9]', 'g');
	foundWord = false;
	foundLine = false;
	foundPara = false;

// check text nodes right-wise for a boundary
	plain = '';
	for (var i = endNodeIndex; i < whole.plainArray.length; i ++) {
		plainPrev = plain;
		plain = whole.plainArray[i];
		plain = plain.replace(/&lt;/g, '<');
		plain = plain.replace(/&gt;/g, '>');
		plain = plain.replace(/&amp;/g, '&');

// boundary is a double newline
		if ( (plainPrev == '\n') && (plain == '\n') ) {
			para.range.setEndBefore(whole.plainNode[i]);
			foundPara = true;
			break;
		}

// boundary is a newline
		else if (plain == '\n') {
			if (foundWord == false) {
				word.range.setEndBefore(whole.plainNode[i]);
				foundWord = true;
			}
			if (foundLine == false) {
				line.range.setEndBefore(whole.plainNode[i]); //// crashes for empty selection
				foundLine = true;
			}
		}

// check text node for a word boundary
		else if (foundWord == false) {
			if (i == endNodeIndex) {
				regExp.lastIndex = endOffset;
			}
			else {
				regExp.lastIndex = 0;
			}
			var regExpArray = regExp.exec(plain);
			if (regExpArray != null) {
				WikEdSetRangeEnd(word.range, whole.plainNode[i], regExp.lastIndex - 1);
				foundWord = true;
			}
		}
	}

// boundary is end of text
	if (foundPara == false) {
		para.range.setEndAfter(whole.plainNode[whole.plainArray.length - 1]);
	}
	if (foundLine == false) {
		line.range.setEndAfter(whole.plainNode[whole.plainArray.length - 1]);
	}
	if (foundWord == false) {
		word.range.setEndAfter(whole.plainNode[whole.plainArray.length - 1]);
	}

	return;
};


//
// remove syntax highlighting and wikify
//

window.WikEdRemoveHighlightingWikify = function(obj, wikify) {

	if ( (obj.html != '') || (wikify == true) ) {

// <div>...</div> to <br> for older Safari, Chrome, WebKit
		if ( (wikEdSafari == true) || (wikEdChrome == true) || (wikEdWebKit == true) ) {
			var isRemove = [];
			obj.html = obj.html.replace(/(<(\/?)div\b([^>]*)>)/g,
				function (p, p1, p2, p3) {
					if (p2 == '') {
						if (p3 == '') {
							isRemove.push(true);
							return('\x00');
						}
						isRemove.push(false);
						return(p1);
					}
					if (isRemove.pop() == true) {
						return('\x01');
					}
					return(p1);
				}
			);
			obj.html = obj.html.replace(/\x01\x00/g, '\x01');
			obj.html = obj.html.replace(/[\x00\x01]/g, '<br>');
		}

// remove syntax highlighting
		WikEdRemoveHighlighting(obj);

// wikify, don't allow many attributes
		if ( (obj.htmlCode == true) && (wikify != false) ) {
			WikEdWikifyHTML(obj, false);
		}
	}
	return;
};


//
// WikEdWikifyHTML:
//   obj.html contains the text to be wikified
//   expects < > &lt; &gt; &amp;  spaces instead of &nbsp; <br> (not \n)
//   returns <br> (not \n)

// allowed and converted tags:
//   br|p
//   h1|h2|h3|h4|h5|h6
//   hr
//   i|dfn|cite|em|var
//   b|strong
//   table|caption|col|thead|tfoot|tbody|tr|td|th
//   dl|dt|dd|li|ol|ul
//   a
// not allowed yet:
//   bdo|q|kbd|samp|abbr|acronym|label
// other allowed tags:
//   big|blockquote|colgroup|center|code|del|div|font|ins|pre|s|small|span|strike|sub|sup|tt|u|rb|rp|rt|ruby
// mediawiki tags (inline/block):
//   nowiki|math|noinclude|includeonly|ref|charinsert|fundraising|fundraisinglogo
//   gallery|source|poem|categorytree|hiero|imagemap|inputbox|timeline|references

window.WikEdWikifyHTML = function(obj, relaxed) {

	var regExpStr;
	var regExp;
	var regExpMatch;

// delete tags: <style>
	obj.html = obj.html.replace(/<(style)\b[^>]*>.*?<\/\1>/gi, '');

// remove MediaWiki section edit spans
	obj.html = obj.html.replace(/<span[^>]*class=\"editsection\"[^>]*>.*?<\/span>\s*()/gi, '');

// remove MediaWiki heading spans
	obj.html = obj.html.replace(/<span\b[^>]*\bclass=\"mw-headline\"[^>]*>(.*?)<\/span>\s*()/g, '$1');

// remove MediaWiki divs from article top
	obj.html = obj.html.replace(/<h3\b[^>]*\bid=\"siteSub\"[^>]*>.*?<\/h3>\s*()/g, '');
	obj.html = obj.html.replace(/<div\b[^>]*\bid=\"contentSub\"[^>]*>.*?<\/div>\s*()/g, '');
	obj.html = obj.html.replace(/<div\b[^>]*\bid=\"jump-to-nav\"[^>]*>.*?<\/div>\s*()/g, '');

// remove MediaWiki table of contents
	obj.html = obj.html.replace(/<table\b[^>]*?\bid=\"toc\"[^>]*>.*?<\/table>\s*()/g, '');

// remove MediaWiki print footer
	obj.html = obj.html.replace(/<div\b[^>]*?\bclass=\"printfooter\"[^>]*>[^<>\"]+\"<a\b[^>]*>[^<]+<\/a>\"<\/div>\s*()/g, '');

// remove MediaWiki category list tags
	while(/<div\b[^>]*\bid=\"catlinks\"[^>]*>(.*?)<\/div>\s*()/g.test(obj.html) == true) {
		obj.html = obj.html.replace(regExp, '$1');
	}
	while(/<p\b[^>]*?\bclass=\"catlinks\"[^>]*>(.*?)<a\b[^>]*>[^<>]+<\/a>: (.*?)<\/p>/g.test(obj.html) == true) {
		obj.html = obj.html.replace(regExp, '$1$2');
	}

// convert MS-Word non-standard lists: *
	obj.html = obj.html.replace(/\s*<p [^>]*>\s*<!--\[if !supportLists\]-->.*?<!--\[endif\]-->\s*(.*?)\s*<\/p>\s*()/g, '* $1\n');

// collect MS-Word footnote texts
	var footnotes = {};
	obj.html = obj.html.replace(/<div\b[^>]* id="ftn(\d+)"[^>]*>\s*<p class="MsoFootnoteText">\s*<a(.|\n)*?<\/a>((.|\n)*?)<\/p>\s*<\/div>/g,
		function(p, p1, p2, p3) {
			footnotes[p1] = p3.replace(/^(\s|<br\b[^>]*>)|(\s|<br\b[^>]*>)$/g, '');
			return('');
		}
	);

// add footnotes as <ref> tags
	obj.html = obj.html.replace(/<a\b[^>]* name="_ftnref(\d+)"[^>]*>(.|\n)*?<!--\[endif\]-->\s*<\/span>\s*<\/span>\s*<\/a>/g,
		function(p, p1) {
			var ref = '&lt;ref name="footnote_' + p1 + '"&gt;' + footnotes[p1] + '&lt;/ref&gt;';
			return(ref);
		}
	);

// remove MS-Word footnote separator
	obj.html = obj.html.replace(/<!--\[if !supportFootnotes\]-->(\s|<br\b[^>]*>)*<hr\b[^>]*>\s*<!--\[endif\]-->(\s|<br\b[^>]*>)*()/g, '');

// correct name for MS-Word images
//                             1                                                    2    2                  3      3       4    4                                 1             5            5
	obj.html = obj.html.replace(/(<v:imagedata\b[^>]*? src="[^">]*?[\\\/]clip_image\d+(\.\w+)"[^>]*? o:title="([^">]*)"[^>]*>(.|\s)*?<img\b[^>]*? src="[^">]*?[\\\/])clip_image\d+\.\w+("[^>]*>)/g, '$1$3$2$5');

// convert <div class="poem">...</div> to <poem>...</poem>
	var isPoem = [];
	obj.html = obj.html.replace(/(<(\/?)div\b([^>]*)>)/gi,
		function (p, p1, p2, p3) {
			if (p2 == '') {
				if (/\bclass=\"poem\"/.test(p3) == true) {
					isPoem.push(true);
					return('<poem>');
				}
				isPoem.push(false);
				return(p1);
			}
			if (isPoem.pop() == true) {
				return('<poem>');
			}
			return(p1);
		}
	);

// sanitize <span> <div> <p>
	obj.html = obj.html.replace(/<(span|div|p)\b *(.*?) *\/?>/gi,
		function (p, p1, p2) {
			return('<' + p1 + WikEdSanitizeAttributes(p1, p2, relaxed) +  '>');
		}
	);

// remove <span> ... </span> pairs withhout attributes
	var isRemove = [];
	obj.html = obj.html.replace(/(<(\/?)span\b([^>]*)>)/gi,
		function (p, p1, p2, p3) {
			if (p2 == '') {
				if (p3 == '') {
					isRemove.push(true);
					return('');
				}
				isRemove.push(false);
				return(p1);
			}
			if (isRemove.pop() == true) {
				return('');
			}
			return(p1);
		}
	);

// remove <p> ... </p> pairs withhout attributes
	var isRemove = [];
	obj.html = obj.html.replace(/(<(\/?)p\b([^>]*)>)/gi,
		function (p, p1, p2, p3) {
			if (p2 == '') {
				if (p3 == '') {
					isRemove.push(true);
					return('\x00\x00');
				}
				isRemove.push(false);
				return(p1);
			}
			if (isRemove.pop() == true) {
				return('\x00\x00');
			}
			return(p1);
		}
	);

// escape character entities
	obj.html = obj.html.replace(/&(?!(amp;|lt;|gt;))/g, '&amp;');

// remove comments
	obj.html = obj.html.replace(/<!--.*?-->/g, '');

// <hr> horizontal rule
	obj.html = obj.html.replace(/(\s|<br\b[^>]*>|\x00)*<hr\b[^>]*>(\s|<br\b[^>]*>|\x00)*()/gi, '\x00\x00----\x00\x00');

// <i> <em> <dfn> <var> <cite> italic
	obj.html = obj.html.replace(/<(i|em|dfn|var|cite)\b.*?>/gi, '\'\'');
	obj.html = obj.html.replace(/<\/(i|em|dfn|var|cite)\b.*?>/gi, '\'\'');

// <b> <strong> bold
	obj.html = obj.html.replace(/<(b|strong)\b.*?>/gi, '\'\'\'');
	obj.html = obj.html.replace(/<\/(b|strong)\b.*?>/gi, '\'\'\'');

// <h1> .. <h6> headings
	obj.html = obj.html.replace(/(\s|<br\b[^>]*>|\x00)*(^|\n|<br\b[^>]*>|\x00)(\s|<br\b[^>]*>|\x00)*<h1\b[^>]*>(.*?)<\/h1>(\s|<br\b[^>]*>|\x00)*()/gi, '\x00\x00= $4 =\x00\x00');
	obj.html = obj.html.replace(/(\s|<br\b[^>]*>|\x00)*(^|\n|<br\b[^>]*>|\x00)(\s|<br\b[^>]*>|\x00)*<h2\b[^>]*>(.*?)<\/h2>(\s|<br\b[^>]*>|\x00)*()/gi, '\x00\x00== $4 ==\x00\x00');
	obj.html = obj.html.replace(/(\s|<br\b[^>]*>|\x00)*(^|\n|<br\b[^>]*>|\x00)(\s|<br\b[^>]*>|\x00)*<h3\b[^>]*>(.*?)<\/h3>(\s|<br\b[^>]*>|\x00)*()/gi, '\x00\x00=== $4 ===\x00\x00');
	obj.html = obj.html.replace(/(\s|<br\b[^>]*>|\x00)*(^|\n|<br\b[^>]*>|\x00)(\s|<br\b[^>]*>|\x00)*<h4\b[^>]*>(.*?)<\/h4>(\s|<br\b[^>]*>|\x00)*()/gi, '\x00\x00==== $4 ====\x00\x00');
	obj.html = obj.html.replace(/(\s|<br\b[^>]*>|\x00)*(^|\n|<br\b[^>]*>|\x00)(\s|<br\b[^>]*>|\x00)*<h5\b[^>]*>(.*?)<\/h5>(\s|<br\b[^>]*>|\x00)*()/gi, '\x00\x00===== $4 =====\x00\x00');
	obj.html = obj.html.replace(/(\s|<br\b[^>]*>|\x00)*(^|\n|<br\b[^>]*>|\x00)(\s|<br\b[^>]*>|\x00)*<h6\b[^>]*>(.*?)<\/h6>(\s|<br\b[^>]*>|\x00)*()/gi, '\x00\x00====== $4 ======\x00\x00');

	obj.html = obj.html.replace(/<(h[0-6])\b[^>]*>(.*?)<\/\1>/gi, '$2');

// {{TABLE}}
// convert html tables to wikicode
	if (wikEdTableMode == false) {

// remove <thead> <tbody> <tfoot>
		obj.html = obj.html.replace(/(\s|\x00|<br\b[^>]*>)<\/?(thead|tbody|tfoot)\b[^>]*>(\s|\x00|<br\b[^>]*>)*()/gi, '$1');

// remove <col></col> and <colgroup></colgroup>\s
		obj.html = obj.html.replace(/(\s|<br\b[^>]*>|\x00)*<(col)\b[^>]*>.*?<\/\2>(|<br\b[^>]*>|\x00)*()/gi, '');
		obj.html = obj.html.replace(/(\s|<br\b[^>]*>|\x00)*<(colgroup)\b[^>]*>.*?<\/\2>(|<br\b[^>]*>|\x00)*()/gi, '');

// line breaks in table cells
		obj.html = obj.html.replace(/(<(td|th|caption)\b[^>]*>)(.*?)(<\/\2>)/gi,
			function(p, p1, p2, p3, p4) {
				p3 = p3.replace(/^(\s|<br\b[^>]*>|\x00>)+/gi, '');
				p3 = p3.replace(/(\s|<br\b[^>]*>|\x00>)+$/gi, '');
				p3 = p3.replace(/<br\b[^>]*> *()/gi, '&lt;br /&gt;');
				return(p1 + p3 + p4);
			}
		);

// remove table closing tags
		obj.html = obj.html.replace(/(\s|<br\b[^>]*>|\x00)*<\/(tr|thead|tbody|tfoot)>(\s|<br\b[^>]*>|\x00)*()/gi, '');

// <td> table cells
		obj.html = obj.html.replace(/(\s|<br\b[^>]*>|\x00)*<td>(\s|<br\b[^>]*>|\x00)*()/gi, '\x00| ');
		obj.html = obj.html.replace(/(\s|<br\b[^>]*>|\x00)*<(td) +([^>]*)>(\s|<br\b[^>]*>|\x00)*()/gi,
			function (p, p1, p2, p3, p4) {
				p3 = WikEdSanitizeAttributes(p2, p3, relaxed);
				if (p3 == '') {
					return('\x00| ');
				}
				else {
					return('\x00|' + p3 + ' | ');
				}
			}
		);

// <th> table cells
		obj.html = obj.html.replace(/(\s|<br\b[^>]*>|\x00)*<th>(\s|<br\b[^>]*>|\x00)*()/gi, '\x00| ');
		obj.html = obj.html.replace(/(\s|<br\b[^>]*>|\x00)*<(th) +([^>]*)>(\s|<br\b[^>]*>|\x00)*()/gi,
			function (p, p1, p2, p3, p4) {
				p3 = WikEdSanitizeAttributes(p2, p3, relaxed);
				if (p3 == '') {
					return('\x00| ');
				}
				else {
					return('\x00|' + p3 + ' | ');
				}
			}
		);

// <tr> table rows
		obj.html = obj.html.replace(/(\s|<br\b[^>]*>|\x00)*<tr>(\s|<br\b[^>]*>|\x00)*()/gi, '\x00|-\x00');
		obj.html = obj.html.replace(/(\s|<br\b[^>]*>|\x00)*<(tr) +([^>]*)>(\s|<br\b[^>]*>|\x00)*()/gi,
			function (p, p1, p2, p3, p4) {
				return('\x00|-' + WikEdSanitizeAttributes(p2, p3, relaxed) + '\x00');
			}
		);

// <caption> table caption
		obj.html = obj.html.replace(/(\s|<br\b[^>]*>|\x00)*<caption>(\s|<br\b[^>]*>|\x00)*()/gi, '\x00|+ ');
		obj.html = obj.html.replace(/(\s|<br\b[^>]*>|\x00)*<(caption) +([^>]*)>(\s|<br\b[^>]*>|\x00)*()/gi,
			function (p, p1, p2, p3, p4) {
				p3 = WikEdSanitizeAttributes(p2, p3, relaxed);
				if (p3 == '') {
					return('\x00|+ ');
				}
				else {
					return('\x00|+' + p3 + ' | ');
				}
			}
		);

// remove closing tags
		obj.html = obj.html.replace(/\s*<\/(td|th|caption)>\s*()/gi, '');

// line breaks, also in table cells (continued)
		obj.html = obj.html.replace(/<br\b[^>]*>[\n ]*()/gi, '\x00');

// <table>
		obj.html = obj.html.replace(/[\s\x00]*<table>[\s\x00]*(\|-(?=[\n\x00]))?/gi, '\x00\x00{|\x00');
		obj.html = obj.html.replace(/[\s\x00]*<(table) +([^>]*)>[\s\x00]*(\|-(?=[\n\x00]))?/gi,
			function (p, p1, p2) {
				var table = '\x00\x00{|';
				if (wikEdWikifyTableParameters != '') {
					table += ' ' + wikEdWikifyTableParameters;
				}
				else {
					table += WikEdSanitizeAttributes(p1, p2);
				}
				return(table);
			}
		);
		obj.html = obj.html.replace(/[\s\x00]*<\/table>[\s\x00]*()/gi, '\x00|}\x00\x00');

	}

// for table mode override pasted table class // {{TABLE}}
	else if (wikEdTableMode == true) {
		obj.html = obj.html.replace(/(<table\b)([^>]*)(>)/gi,
			function (p, p1, p2, p3) {
				if (p2.match(/\bclass=/)) {
					p2 = p2.replace(/\bclass\s*=\s*([\'\"]?)[^<>\'\"\n]*?\1/g, 'class="wikEdTableEdit"');
				}
				else {
					p2 = ' class="wikEdTableEdit"';
				}
				return(p1 + p2 + p3);
			}
		);

// table block element needs only one newline
		obj.html = obj.html.replace(/(\s|<br\b[^>]*>|\x00)*(<table\b.*?>)/gi, '\x00\x00$2');
		obj.html = obj.html.replace(/(<\/table>)(\s|<br\b[^>]*>|\x00)*()/gi, '$1\x00');
	}

// line breaks (continued)
	if (wikEdTableMode == true) {
		obj.html = obj.html.replace(/<br\b[^>]*>[\n ]*()/gi, '\x00');
	}

// convert links
	var regExpMatch = [];
	var regExpStr = '(<a(\\b[^>]*)>(.*?)</a>)';
	var regExp = new RegExp(regExpStr, 'gi');
	obj.html = obj.html.replace(regExp,
		function (p, p1, p2, p3) {
			var linkParam = p2;
			var linkText = p3;
			var hrefUrlParam = null;
			var hrefUrlArticle = null;
			var imgWidth = '';
			var hrefParamTitle = null;
			var hrefParamISBN = null;
			var hrefParamAction = null;
			var hrefParamSpecial = false;
			var linkArticleAnchor = '';
			var linkArticle = '';
			var linkTitle = '';

// get href value
			var hrefValue;
			regExpMatch = linkParam.match(/ href=\"([^\">]*)\"/);
			if (regExpMatch != null) {
				hrefValue = regExpMatch[1];

// get absolute path from ./index.php and ../../index.php
				hrefValue = WikEdRelativeToAbsolutePath(hrefValue);

// check for wiki article link and get parameters
//                                 1                        2 article   2                       3article 314 anchor 4                          6                       7   8 urlpar 87539 anchor 9
				regExpStr = wikEdServer + '(' + wikEdArticlePath + '([^\\"\\?#]+)|' + wikEdScript + '\\?([^\\"#]*))(#[^\\"]*)?';
				regExp = new RegExp(regExpStr);
				regExpMatch = regExp.exec(hrefValue);
				if (regExpMatch != null) {

// article name from url path <a href="../wiki/ hrefUrlArticle ">
					if (regExpMatch[2] != null) {
						hrefUrlArticle = regExpMatch[2];
					}

// article name from url parameters <a href="url? hrefUrlParam ">
					else if (regExpMatch[3] != null) {
						hrefUrlParam = regExpMatch[3];
					}

// link anchor <a href="link #anchor">
					if (regExpMatch[4] != null) {
						linkArticleAnchor = regExpMatch[4];
						linkArticleAnchor = linkArticleAnchor.replace(/\.([0-9A-F]{2})/g, '%$1');
						linkArticleAnchor = decodeURIComponent(linkArticleAnchor);
						linkArticleAnchor = linkArticleAnchor.replace(/_\d+$/g, '');
					}

// parse hrefUrlParam and check for special parameters
					if (hrefUrlParam != null) {
						regExp = new RegExp('(^|&amp;)(\\w+)=([^\\"\\&]+)', 'g');
						while ( (regExpMatch = regExp.exec(hrefUrlParam)) != null) {
							var param = regExpMatch[2];
							var value = regExpMatch[3];
							switch (param) {
								case 'title':
									hrefParamTitle = value;
									break;
								case 'isbn':
									hrefParamISBN = value;
									break;
								case 'redlink':
									break;
								case 'action':
									hrefParamAction = value;
									break;
								default:
									hrefParamSpecial = true;
							}
							if (hrefParamAction != null) {
								break;
							}
						}
					}

// ISBN links
					if (hrefParamAction == null) {
						if ( (hrefParamISBN != null) && (hrefParamSpecial != true) ) {
							var isbn = hrefParamISBN;
							regExpMatch = /((\d\-?){13}|(\d\-?){10})/.exec(linkText);
							if (regExpMatch != null) {
								isbn = regExpMatch[1];
							}
							return('ISBN ' + isbn);
						}

// get article from href parameters
						else if ( (hrefParamTitle != null) && (hrefParamSpecial != true) ) {
							linkArticle = hrefParamTitle;
							linkArticle = linkArticle.replace(/_/g, ' ');
							linkArticle = decodeURIComponent(linkArticle);
						}

// get article name from url path
						else if (hrefUrlArticle != null) {
							linkArticle = hrefUrlArticle;
							linkArticle = linkArticle.replace(/_/g, ' ');
							linkArticle = decodeURIComponent(linkArticle);
						}

// get article name from <a title="">
						else {
							regExpMatch = / title=\"([^\">]+)\"/.exec(linkParam);
							if (regExpMatch != null) {
								linkArticle = regExpMatch[1];
							}
						}
					}
				}

// format wiki link
				if (linkArticle != '') {

// check for wiki image
					regExpStr = '^<img\\b[^>]*?\\bwidth=\\"(\\d+)\\"[^>]*?>$';
					regExp = new RegExp(regExpStr);
					regExpMatch = regExp.exec(linkText);
					if (regExpMatch != null) {
						imgWidth = regExpMatch[1];
						imgWidth = '|' + imgWidth + 'px';
						if ( (linkTitle != '') && (linkTitle != 'Enlarge') ) {
							linkTitle = '|' + linkTitle;
							return('[[' + linkArticle + imgWidth + linkTitle + ']]');
						}
						else {
							return('[[' + linkArticle + imgWidth + ']]');
						}
					}

// category link
					var regExp = new RegExp('^(Category|' + wikEdText['wikicode Category'] + ')\\s*:(.*)', 'i');
					regExpMatch = regExp.exec(linkArticle);
					if (regExpMatch != null) {
						return('[[' + wikEdText['wikicode Category'] + ':' + regExpMatch[1].charAt(0).toUpperCase() + linkText.substr(1) + ']]');
					}

// wiki link
					if (linkArticle == linkText.charAt(0).toUpperCase() + linkText.substr(1)) {
						return('[[' + linkText + linkArticleAnchor + ']]');
					}

// date link (English only)
					regExpMatch = /^(January|February|March|April|May|June|July|August|September|October|November|December) (\d{1,2})$/.exec(linkArticle);
					if (regExpMatch != null) {
						var month = regExpMatch[1];
						var day = regExpMatch[2];
						if (linkText == (day + ' ' + month) ) {
							return('[[' + linkArticle + linkArticleAnchor + ']]');
						}
					}

// lowercase the article name if the first char of the link text can exist in lower/uppercase and is lowercase
					if ( linkText.charAt(0).toLowerCase() != linkText.charAt(0).toUpperCase() ) {
						if ( linkText.charAt(0) == linkText.charAt(0).toLowerCase() ) {
							linkArticle = linkArticle.charAt(0).toLowerCase() + linkArticle.substr(1);
						}
					}

// suffix links
					regExpStr = '^' + linkArticle.replace(/(\W)/g, '\\$1') + '([\\wŠŒŽšœžŸÀ-ÖØ-öø-\\u0220\\u0222-\\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\\u0400-\\u0481\\u048a-\\u04ce\\u04d0-\\u04f5\\u04f8\\u04f9]+)$';
					regExp = new RegExp(regExpStr);
					regExpMatch = regExp.exec(linkText);
					if (regExpMatch != null) {
						return('[[' + linkArticle + linkArticleAnchor + ']]' + regExpMatch[1]);
					}
					return('[[' + linkArticle + linkArticleAnchor + '|' + linkText + ']]');
				}

// external link
				if (hrefValue != '') {

// PubMed link
					regExpMatch = /^http:\/\/www\.ncbi\.nlm\.nih\.gov\/entrez\/query\.fcgi\?cmd=Retrieve&amp;db=pubmed&amp;.*?&amp;list_uids=(\d+)/.exec(hrefValue);
					if (regExpMatch != null) {
						return('PMID ' + regExpMatch[1]);
					}

// DOI link
					regExpMatch = /^http:\/\/dx\.doi\.org\/(.*)/.exec(hrefValue);
					if (regExpMatch != null) {
						return('{{doi|' + regExpMatch[1] + '}}');
					}

// other external link
					return('[' + hrefValue + ' ' + linkText + ']');
				}
			}

// return unchanged text
			return(p1);
		}
	);

// clean up MediaWiki category list
	var regExp = new RegExp('<span\\b[^>]*>(\\[\\[(Category|' + wikEdText['wikicode Category'] + ')\\s*:[^\\]]+\\]\\])<\\/span>[\\s\\x00\\|]*', 'gi');
	obj.html = obj.html.replace(regExp, '$1\x00');

// clean up DOI
	obj.html = obj.html.replace(/\[\[Digital object identifier\|DOI\]\]:(\{\{doi\|[^\}\s]+\}\})/gi, '$1');

// convert images
	obj.html = obj.html.replace(/<img\b([^>]*)>/gi,
		function (p, p1) {

// get and format parameters
			var address = '';
			var regExpMatch = /\bsrc\s*=\s*(\'|\")([^\'\"]*)(\'|\")/i.exec(p1);
			if (regExpMatch != null) {
				address = regExpMatch[2].replace(/^ +| +$/g, '');
			}

			var imgAlt = '';
			regExpMatch = /\balt\s*=\s*(\'|\")([^\'\"]*)(\'|\")/i.exec(p1);
			if (regExpMatch != null) {
				imgAlt = regExpMatch[2].replace(/^ +| +$/g, '');
				imgAlt = imgAlt.replace(/&amp;nbsp;|[\n\x00]/g, ' ');
				imgAlt = imgAlt.replace(/ {2,}/g, ' ');
				imgAlt = imgAlt.replace(/^ | $/g, '');
				if (imgAlt != '') {
					imgAlt = '|' + imgAlt;
				}
			}

			var imgWidth = '';
			regExpMatch = /\bwidth\s*=\s*(\'|\")([^\'\"]*)(\'|\")/i.exec(p1);
			if (regExpMatch != null) {
				imgWidth = '|' + regExpMatch[2].replace(/^ +| +$/g, '') + 'px';
			}

			var imgLink = '';
			regExpMatch = /([^\/]+)$/.exec(address);
			if (regExpMatch != null) {
				imgLink = regExpMatch[1];
				if (imgLink != '') {
					return('[[' + wikEdText['wikicode Image'] + ':' + imgLink + imgWidth + imgAlt + ']]');
				}
			}
			return('');
		}
	);

// convert lists: * # : ;
	var listObj = {};
	listObj.prefix = '';
	obj.html = obj.html.replace(/[\s\x00]*<(\/?(ol|ul|li|dl|dd|dt))\b[^>]*>[\s\x00]*()/gi,
		function (p, p1, p2, p3, p4) {
			switch (p1.toLowerCase()) {
				case 'ol':
					listObj.prefix += '#';
					return('\x00');
				case 'ul':
					listObj.prefix += '*';
					return('\x00');
				case 'dl':
					listObj.prefix += ':';
					return('\x00');
				case '/ol':
				case '/ul':
				case '/dl':
					listObj.prefix = listObj.prefix.substr(0, listObj.prefix.length - 1);
					return('\x00\x00');
				case 'li':
				case 'dd':
					return('\x00' + listObj.prefix + ' ');
				case 'dt':
					return('\x00' + listObj.prefix.replace(/:$/, ';') + ' ');
				case '/li':
				case '/dt':
				case '/dd':
					return('');
			}
			return('');
		}
	);
	obj.html = obj.html.replace(/[\n|\x00]+[#\*:\;]+ (?=[\n|\x00])/g, '');

// <> remove not allowed tags
	obj.html = obj.html.replace(/(<\/?)(\/?)(\w+)(.*?>)/g,
		function (p, p1, p2, p3, p4) {
			if (wikEdTableMode == true) {
				if ( /^(table|tr|td|th|thead|tbody|tfoot|col|colgroup|caption)$/i.test(p3) == true) {
					var tag = p1 + p2 + p3 + p4;
					tag = tag.replace(/</g, '\x01');
					tag = tag.replace(/>/g, '\x02');
					return(tag);
				}
				else {
					return('');
				}
			}
			else if ( /^(big|blockquote|colgroup|center|code|del|div|font|ins|p|pre|s|small|span|strike|sub|sup|tt|u|rb|rp|rt|ruby|nowiki|math|noinclude|includeonly|ref|charinsert|fundraising|fundraisinglogo|gallery|source|poem|categorytree|hiero|imagemap|inputbox|timeline|references)$/i.test(p3) == true) {
				return(p1 + p2 + p3 + p4);
			}
			else {
				return('');
			}
		}
	);

// sanitize attributes in opening html tags
	obj.html = obj.html.replace(/<(\w+) +(.*?) *(\/?)>/g,
		function (p, p1, p2, p3) {
			if (p3 != '') {
				p3 = ' ' + p3;
			}
			return('<' + p1 + WikEdSanitizeAttributes(p1, p2, relaxed) + p3 + '>');
		}
	);

// unformat underlined, italic or bold blanks
// corrupts existing text
//	obj.html = obj.html.replace(/<u>(\'\'\'|\'\'|\s|\x00)*([\s\x00]+)(\'\'\'|\'\'|\s|\x00)*<\/u>/g, '$2');
//	obj.html = obj.html.replace(/\'\'\'(\'\'|\s|\x00)*([\s\x00]+)(\'\'|\s|\x00)*\'\'\'/g, '$2');
//	obj.html = obj.html.replace(/\'\'([\s\x00]+)\'\'/g, '$1');

// fix MS Word non-style heading formatting
	obj.html = obj.html.replace(/(\x00(={1,6}) *)(<u>|\'\'\'|\'\')+(.*?)(<\/u>|\'\'\'|\'\')+( *\2\x00)/gi, '$1$4$6');

// remove empty headings
	obj.html = obj.html.replace(/\x00(={1,6})\s+\1\x00/g, '\x00');

// remove space-only lines
	obj.html = obj.html.replace(/([\s\x00]*\x00[\s\x00]*)/g,
		function (p, p1) {
			return(p1.replace(/\n/g, '\x00'));
		}
	);

// remove trailing linebreaks from table cells
	obj.html = obj.html.replace(/\x00{2,}(\|)/g, '\x00$1');

// remove leading and trailing spaces
	obj.html = obj.html.replace(/>\s+\x00/g, '>\x00');
	obj.html = obj.html.replace(/\x00\s+</g, '\x00<');

// remove empty inline and block tag pairs
	obj.html = obj.html.replace(/( *)<(big|colgroup|code|del|font|ins|pre|s|small|span|strike|sub|sup|tt|u|rb|rp|rt|ruby|nowiki|math|noinclude|includeonly|ref|charinsert|fundraising|fundraisinglogo)\b[^>]*><\/\1> *()/gi, '$1');
	obj.html = obj.html.replace(/[\s\x00]*<(blockquote|center|div|gallery|source|poem|categorytree|hiero|imagemap|inputbox|timeline|references)\b[^>]*><\/\1>[\s\x00]*()/gi, '\x00\x00');

// remove empty lines from block tags
	obj.html = obj.html.replace(/(<(blockquote|center|div|p|pre|gallery|source|poem|categorytree|hiero|imagemap|inputbox|timeline|references)\b[^>]*>[\s\x00])[\s\x00]+/gi, '$1');
	obj.html = obj.html.replace(/[\s\x00]+([\s\x00]<\/(blockquote|center|div|p|pre|gallery|source|poem|categorytree|hiero|imagemap|inputbox|timeline|references)>)/gi, '$1');

// blockquote
	obj.html = obj.html.replace(/(<blockquote\b[^>]*>[\s\x00]+)([\S\s]*?)([\s\x00]+<\/blockquote>)/gi,
		function (p, p1, p2, p3) {
			p2 = p2.replace(/\x00/g, '<br>\n');
			return(p1 + p2 + p3);
		}
	);

// escape < >
	obj.html = obj.html.replace(/</g, '&lt;');
	obj.html = obj.html.replace(/>/g, '&gt;');

// preserved table tags
	obj.html = obj.html.replace(/\x01/g, '<');
	obj.html = obj.html.replace(/\x02/g, '>');

// newlines to <br>
	obj.html = obj.html.replace(/\x00+\n/g, '\n');
	obj.html = obj.html.replace(/\n\x00+/g, '\n');
	obj.html = obj.html.replace(/\n*\x00(\x00|\n)+/g, '\n\n');
	obj.html = obj.html.replace(/\x00/g, '\n');
	obj.html = obj.html.replace(/\n/g, '<br>');

// table block element needs only one newline
	obj.html = obj.html.replace(/(<\/table><br\b[^>]*>)(<br\b[^>]*>)+/g, '$1');

// remove empty lines from article start and end
	if (obj.from == 'whole') {
		obj.html = obj.html.replace(/^(<br\b[^>]*>)+/gi, '');
		obj.html = obj.html.replace(/(<br\b[^>]*>)+$/gi, '');
	}

	return;
};


//
// WikEdRelativeToAbsolutePath
//   broken for editing article names containing "/", https://bugzilla.mozilla.org/show_bug.cgi?id=430910

window.WikEdRelativeToAbsolutePath = function(relativePath, fullPath) {

	var absolutePath = '';

// get current url
	if (fullPath == null) {
		fullPath = window.location.href;
		fullPath = fullPath.replace(/#.*()/, '');
		fullPath = fullPath.replace(/\?.*()/, '');
	}

// ./index.php
	if (/^\.\/()/.test(relativePath) == true) {
		relativePath = relativePath.replace(/^\.\/()/, '');
		fullPath = fullPath.replace(/\/[^\/]*$/, '');
		absolutePath = fullPath + '/' + relativePath;
	}

// ../../index.php
	else if (/^\.\.\/()/.test(relativePath) == true) {
		while (/^\.\.\/()/.test(relativePath) == true) {
			relativePath = relativePath.replace(/^\.\.\/()/, '');
			fullPath = fullPath.replace(/\/[^\/]*$/, '');
		}
		absolutePath = fullPath + '/' + relativePath;
	}

// full path
	else {
		absolutePath = relativePath;
	}
	return(absolutePath);
};


//
// WikEdSanitizeAttributes: see Sanitizer.php
//

window.WikEdSanitizeAttributes = function(tag, attributes, relaxed) {
	var common;
	var tablealign;
	var tablecell;
	var table;
	if (relaxed == true) {
		common = 'dir|style|class'; // not needed: lang|id|title
		tablealign = '|align|char|charoff|valign';
		table = '|summary|width|border|frame|rules|cellspacing|cellpadding|align|bgcolor';
		tablecell = '|abbr|axis|headers|scope|rowspan|colspan|nowrap|width|height|bgcolor';
	}
	else {
		common = 'dir';
		table = '|border|cellspacing|cellpadding|align|bgcolor';
		tablealign = '|align|valign';
		tablecell = '|rowspan|colspan|nowrap|bgcolor';
	}
	tag = tag.toLowerCase();
	var sanitized = '';
	var regExpMatch;
	while ( (regExpMatch = /(\w+)\s*=\s*((\'|\")(.*?)\3|(\w+))/g.exec(attributes)) != null) {
		var attrib = regExpMatch[1];
		var attribValue = regExpMatch[4] || regExpMatch[5];
		if (attribValue == '') {
			continue;
		}
		var valid = false;

// relaxed, for existing text tags
		if (relaxed == true) {
			if ('center|em|strong|cite|code|var|sub|supdl|dd|dt|tt|b|i|big|small|strike|s|u|rb|rp|ruby'.indexOf(tag) >= 0) {
				if (common.indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('div|span|h1|h2|h3|h4|h5|h6|p'.indexOf(tag) >= 0) {
				if ((common + '|align').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('blockquote'.indexOf(tag) >= 0) {
				if ((common + '|cite').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('br'.indexOf(tag) >= 0) {
				if ('style|clear'.indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('pre'.indexOf(tag) >= 0) {
				if ((common + '|width').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('ins|del'.indexOf(tag) >= 0) {
				if ((common + '|cite|datetime').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('ul'.indexOf(tag) >= 0) {
				if ((common + '|type').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('ol'.indexOf(tag) >= 0) {
				if ((common + '|type|start').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('li'.indexOf(tag) >= 0) {
				if ((common + '|type|value').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('table'.indexOf(tag) >= 0) {
				if ((common + table).indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('caption'.indexOf(tag) >= 0) {
				if ((common + '|align').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('thead|tfoot|tbody'.indexOf(tag) >= 0) {
				if ((common + tablealign).indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('colgroup|col'.indexOf(tag) >= 0) {
				if ((common + '|span|width' + tablealign).indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('tr'.indexOf(tag) >= 0) {
				if ((common + '|bgcolor' + tablealign).indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('td|th'.indexOf(tag) >= 0) {
				if ((common + tablecell + tablealign).indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('font'.indexOf(tag) >= 0) {
				if ((common + '|size|color|face').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('hr'.indexOf(tag) >= 0) {
				if ((common + '|noshade|size|width').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('rt'.indexOf(tag) >= 0) {
				if ((common + '|rbspan').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('ref'.indexOf(tag) >= 0) {
				if (('name').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('references'.indexOf(tag) >= 0) {
			}
			else if ('source'.indexOf(tag) >= 0) {
				if (('lang').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('poem'.indexOf(tag) >= 0) {
				if (common.indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('categorytree'.indexOf(tag) >= 0) {
				if ((common + '|mode|depth|onlyroot|hideroot|hideprefix|showcount|namespaces').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('charinsert'.indexOf(tag) >= 0) {
			}
			else if ('fundraising'.indexOf(tag) >= 0) {
			}
			else if ('fundraisinglogo'.indexOf(tag) >= 0) {
			}
			else if ('hiero'.indexOf(tag) >= 0) {
			}
			else if ('imagemap'.indexOf(tag) >= 0) {
			}
			else if ('inputbox'.indexOf(tag) >= 0) {
			}
			else if ('timeline'.indexOf(tag) >= 0) {
			}
			else if ('gallery'.indexOf(tag) >= 0) {
				if ((common + '|perrow|widths|heights').indexOf(attrib) >= 0) { valid = true; }
			}
		}

// strict, for html code to be wikified from external sources (websites, Word)
		else {
			if ('center|em|strong|cite|code|var|sub|supdl|dd|dt|tt|b|i|big|small|strike|s|u|rb|rp|ruby|blockquote|pre|ins|del'.indexOf(tag) >= 0) {
				if (common.indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('div|span|h1|h2|h3|h4|h5|h6|p'.indexOf(tag) >= 0) {
				if ((common + '|align').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('br'.indexOf(tag) >= 0) {
				if ('clear'.indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('ul'.indexOf(tag) >= 0) {
				if ((common + '|type').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('ol'.indexOf(tag) >= 0) {
				if ((common + '|type|start').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('li'.indexOf(tag) >= 0) {
				if ((common + '|type|value').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('table'.indexOf(tag) >= 0) {
				if ((common + table).indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('caption'.indexOf(tag) >= 0) {
				if ((common + '|align').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('thead|tfoot|tbody'.indexOf(tag) >= 0) {
				if ((common + tablealign).indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('colgroup|col'.indexOf(tag) >= 0) {
				if ((common + '|span' + tablealign).indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('tr'.indexOf(tag) >= 0) {
				if ((common + '|bgcolor' + tablealign).indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('td|th'.indexOf(tag) >= 0) {
				if ((common + tablecell + tablealign).indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('font'.indexOf(tag) >= 0) {
				if ((common + '|color').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('hr'.indexOf(tag) >= 0) {
				if ((common + '|noshade|size').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('rt'.indexOf(tag) >= 0) {
				if ((common + '|rbspan').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('ref'.indexOf(tag) >= 0) {
				if (('name').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('references'.indexOf(tag) >= 0) {
			}
			else if ('source'.indexOf(tag) >= 0) {
				if (('lang').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('poem'.indexOf(tag) >= 0) {
				if (common.indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('categorytree'.indexOf(tag) >= 0) {
				if ((common + '|mode|depth|onlyroot|hideroot|hideprefix|showcount|namespaces').indexOf(attrib) >= 0) { valid = true; }
			}
			else if ('charinsert'.indexOf(tag) >= 0) {
			}
			else if ('fundraising'.indexOf(tag) >= 0) {
			}
			else if ('fundraisinglogo'.indexOf(tag) >= 0) {
			}
			else if ('hiero'.indexOf(tag) >= 0) {
			}
			else if ('imagemap'.indexOf(tag) >= 0) {
			}
			else if ('inputbox'.indexOf(tag) >= 0) {
			}
			else if ('timeline'.indexOf(tag) >= 0) {
			}
			else if ('gallery'.indexOf(tag) >= 0) {
			}
		}

// clean up
		if (valid == true) {

// clean up defaults for align
			if (attrib == 'align') {
				if ('tr|td|th'.indexOf(tag) >= 0) {
					if (attribValue == 'left') {
						attribValue = '';
					}
				}
			}

// clean up defaults for valign
			else if (attrib == 'valign') {
				if ('tr|td|th'.indexOf(tag) >= 0) {
					if (attribValue == 'top') {
						attribValue = '';
					}
				}
			}

// clean up style
			else if (attrib == 'style') {

// remove non-standard Mozilla styles
				attribValue = attribValue.replace(/(^| )(-moz-[\w\-]+): [\w\-]+; *()/g, '$1');
				attribValue = attribValue.replace(/(^| )([\w\-]+): [^;]*(-moz-[\w\-]+|windowtext)[^;]*; *()/g, '$1');

// remove dimensions from null values
				attribValue = attribValue.replace(/\b0(%|in|cm|mm|em|ex|pt|pc|px)\b/g, '0');

// remove empty definitions and spaces
				attribValue = attribValue.replace(/[\w\-]+ *\: *\; *()/g, '');
				attribValue = attribValue.replace(/ *(;|:) *()/g, '$1 ');
				attribValue = attribValue.replace(/( |;)+$/g, ';');
			}

// clean up class
			else if (attrib == 'class') {

// remove MS Word classes
				attribValue = attribValue.replace(/^Ms.*$/g, '');
			}

			if (attribValue != '') {
				sanitized += ' ' + attrib + '="' + attribValue + '"';
			}
		}
	}
	return(sanitized);
};


//
// WikEdRemoveHighlighting: remove syntax highlighting in obj.html; sets obj.htmlCode if text contains html code
//    expects <br> instead of \n

window.WikEdRemoveHighlighting = function(obj) {

// remove highlighting error messages
	if (wikEdHighlightError == true) {
		obj.html = obj.html.replace(/<span\b[^>]*?\bclass=\"wikEdHighlightError\"[^>]*>.*?<\/span><!--wikEdHighlightError-->/g, '');
	}

// remove highlighting and atttribute-free span tags
	var isRemove = [];
	obj.html = obj.html.replace(/(<(\/?)span\b([^>]*)>)/g,
		function (p, p1, p2, p3) {
			if (p2 == '') {
				if (/\bclass=\"wikEd[\w\/]+\"/.test(p3) == true) {
					isRemove.push(true);
					return('');
				}
				isRemove.push(false);
				return(p1);
			}
			if (isRemove.pop() == true) {
				return('');
			}
			return(p1);
		}
	);

// remove highlighting div tags
	var isRemove = [];
	obj.html = obj.html.replace(/(<(\/?)div\b([^>]*)>)/g,
		function (p, p1, p2, p3) {
			if (p2 == '') {
				if (/\bclass=\"wikEd[\w\/]+\"/.test(p3) == true) {
					isRemove.push(true);
					return('');
				}
				isRemove.push(false);
				return(p1);
			}
			if (isRemove.pop() == true) {
				return('');
			}
			return(p1);
		}
	);

// remove span tags from WebKit https://bugs.webkit.org/show_bug.cgi?id=13490
// filtering these tags does not help, they accumulate anyway
	var isRemove = [];
	obj.html = obj.html.replace(/(<(\/?)span\b([^>]*)>)/g,
		function (p, p1, p2, p3) {
			if (p2 == '') {
				if (/\bclass=\"(Apple-style-span|Apple-.*?)\"/.test(p3) == true) {
					isRemove.push(true);
					return('\x00');
				}
				isRemove.push(false);
				return(p1);
			}
			if (isRemove.pop() == true) {
				return('\x01');
			}
			return(p1);
		}
	);

// remove highlighting font tags from WebKit
	var isRemove = [];
	obj.html = obj.html.replace(/(<(\/?)font\b([^>]*)>)/g,
		function (p, p1, p2, p3) {
			if (p2 == '') {
				if (/\bclass=\"(Apple-style-span|Apple-.*?)\"/.test(p3) == true) {
					isRemove.push(true);
					return('\x00');
				}
				isRemove.push(false);
				return(p1);
			}
			if (isRemove.pop() == true) {
				return('\x01');
			}
			return(p1);
		}
	);

// remove highlighting div tags from WebKit
	var isRemove = [];

//                             12             2 3   3     4     4 5             51
	obj.html = obj.html.replace(/(([\x00\x01]\s*)?<(\/?)div\b([^>]*)>(\s*[\x00\x01])?)/g,
		function (p, p1, p2, p3, p4, p5) {
			if (p3 == '') {
				if ( (p2 != '') || (p5 != '') ) {
					if (/\bstyle=\"/.test(p4) == true) {
						if (/\bclass=\"/.test(p4) == false) {
							isRemove.push(true);
							return('');
						}
					}
				}
				isRemove.push(false);
				return(p1);
			}
			if (isRemove.pop() == true) {
				return('');
			}
			return(p1);
		}
	);
	obj.html = obj.html.replace(/[\x00\x01]/g, '');

// comments
	obj.html = obj.html.replace(/<!--wikEd[\w\/]+-->/g, '');

// newlines
	obj.html = obj.html.replace(/[\n ]+/g, ' ');

// non-breaking spaces
	obj.html = obj.html.replace(/&nbsp;/g, '\xa0');

// check for pasted html content
	if (obj.html.match(/<(?!br\b)/) != null) {
		obj.htmlCode = true;
	}
	else {
		obj.htmlCode = false;
	}

	return;
};


//
// WikEdHighlightSyntaxInit: initialize regExp for syntax highlighting and regExp-to-number array, called during start up
//

window.WikEdHighlightSyntaxInit = function() {

	wikEdParseObj.matchToTag = [''];
	wikEdParseObj.regExpTags = null;

// main regular expression search definitions
// [regular expression fragment, tag, tagClass, tagStart (regexp starts with newline)]
	var tagArray = [
		['\\b(((https?|ftp|irc|gopher):\\/\\/)|news:|mailto:)[^\\x00-\\x20\\s\\\"\\[\\]\\x7f]+', 'inlineURL', 'block'], // inline link

//		['[^\\{\\}\\[\\]\x00\x01_\\|\\!\\=\\*\\#\\:\\;\\\"\\\'\\n\\\\~\\-]+', 'text', 'ignore'], // chew-up fragment to ignore plain text, triples regExp speed, check later if chewed into start of inlineLink; start-with-text tags (PMID,...) have to be tested for separately to benefit from his

		['\x00(nowiki)\\b[^\x00\x01]*\x01(.|\\n)*?\x00\\/nowiki\\s*\x01', 'nowiki', 'block'], // <nowiki>...</nowiki>
		['\x00(pre)\\b[^\x00\x01]*\x01(.|\\n)*?\x00\\/pre\\s*\x01',       'pre',    'block'], // <pre>...</pre>
		['\x00(math)\\b[^\x00\x01]*\x01(.|\\n)*?\x00\\/math\\s*\x01',     'math',   'block'], // <math>...</math>

		['(^|\\n)([ \xa0]+)(\\S[^\\n]*)',        'preform',            'block'], // "preformatted" text line (leading space)
		['(^|\\n)([\\*\\#\\:\\;]+)([^\\n]*)',    'list',               'block'], // list line

		['\x00(br\\b)[^\x00\x01]*\x01',     'br',                 'block'], // <br>
		['\x00(\\w+)[^\x00\x01]*?\\/\x01',  'htmlEmpty',          'block'], // <html />

		['\x00(\\w+)[^\x00\x01]*\x01',      'html',                'open'], // <html>
		['\x00\\/(\\w+)[^\x00\x01]*\x01',   'html',               'close'], // </html>

		['(^|\\n)(\\{\\|)',                 'table',               'open'], // table start
		['(^|\\n)(\\|\\}\\})',              'pipeTemplateEnd',    'multi'], // empty template parameter + template end
		['(^|\\n)(\\|\\})',                 'table',              'close'], // table end

		['(^|\\n)(\\|\\+)',                 'caption',            'block'], // table caption
		['(^|\\n)(\\|\\-)',                 'row',                'block'], // table row

		['(^|\\n)(\\|)',                    'newlinePipe',        'block'], // table cell, wikilink separator, file parameter separator, empty template parameter
		['\\|\\|',                          'doublePipe',         'block'], // table cell separator, empty file parameter separator, empty template parameters
		['\\|',                             'pipe',               'block'], // table cell parameter separator, table caption parameter separator, wikilink separator, file parameter separator, redirect separator, template parameter parameter

		['(^|\\n)(!+)',                     'header',             'block'], // table header cell
		['!!',                              'headerSep',          'block'], // table header cell separator
		['!',                               'headerParam',        'block'], // table header cell parameter separator

		['\\{{2,}',                         'paramTempl',          'open'], // template or parameter start
		['\\}{2,}',                         'paramTempl',         'close'], // template parameter end

		['(^\\s*)#REDIRECT(?=\\s*\\[\\[)',  'redirect',           'block'], // redirect

		['\\[\\[(?=(Image|File|' + wikEdText['wikicode Image'] + '|' + wikEdText['wikicode File'] + ')\\s*:\\s*)', 'file', 'open'], // file link start /// add translation
		['\\[\\[',                          'link', 'open'],                // wikilink, category start with interlink detection
		['\\]\\]',                          'doubleCloseBracket', 'close'], // wikilink, category, file link, redirect end

		['\\[((((https?|ftp|irc|gopher):\\/\\/)|news:|mailto:)[^\\x00-\\x20\\s\\\"\\[\\]\\x7f]+)(\\s*)', 'external',  'open'], // external link start; up?? [[url]] detected as ext link!
		['\\]',                             'external',           'close'], // external link end

		['(^|\\n)={1,6}',                   'heading',             'open'], // heading start - heading can contain multi-line templates and <tag>s, all single-line
		['={1,6}[ \xa0\\t]*(?=(\\n|$))',    'heading',            'close'], // heading end

		['\\\'{2,}',                        'boldItalic',         'multi'], // bold, italic

		['__(' + wikEdMagicWords + ')__',   'magic',              'block'], // magic words
		['~{3,5}',                          'signature',          'block'], // signature
		['(^|\\n)\\-{4,}',                  'hr',                 'block'], // hr
		['(\\n|$)',                         'newline',            'block']  // breaks: heading, lists, external link, wikilink before

	];

// parse tag array into regular expression string and parenthesized substring match-to-tag info array
	var regExpStrings = [];
	for (var i = 0; i < tagArray.length; i ++) {
		var regExpSub = tagArray[i][0];
		var tag = tagArray[i][1];
		var tagClass = tagArray[i][2];

// add parenthesized sub regExp to regexp array
		regExpStrings.push('(' + regExpSub + ')');

// detect if a fragment starts with (^|\\n) to handle the leading newlines
		var tagStart = false;
		if (/^\(\^\|\\n\)/.test(regExpSub) == true) {
			tagStart = true;
		}

// save tag information for matched parenthesis
		wikEdParseObj.matchToTag.push( [tag, tagClass, tagStart] );

// add empty entry for all sub parentheses, ignore (? and \(
		var pos = 0;
		while ( (pos = regExpSub.indexOf('(', pos) + 1) > 0) {
			if (regExpSub.charAt(pos) != '?') {
				if (regExpSub.charAt(pos - 2) != '\\') {
					wikEdParseObj.matchToTag.push( [] );
				}
			}
		}
	}

// create regExp from or-joined parenthesized sub regExps
	wikEdParseObj.regExpTags = new RegExp(regExpStrings.join('|'), 'gi');

	return;
};


//
// WikEdHighlightSyntax: highlight syntax in obj.html;
//   existing highlighting must have been removed using WikEdRemoveHighlighting
//   expects < > &lt; &gt; &amp;  \xa0 instead of &nbsp;  \n instead of <br>
// Known bugs:
// - templates inside elements
// - fragment highlighting misses surrounding html

// this is a real wikicode parser that works as follows:
//   cycle through the text with a complex regexp search for wikicode and highlighting fragments
//     build an array based tree structure of text elements
//       tag info: text pos, text length, tag type (open, close, block, error)
//       connectivity info: parent, firstChild, nextSibling, paired opening/closing (all array indexes)
//   add actual highlighting html code to parse tree elements
//
/* TO DO:
<table> closes <td>, <tr>
<tr> closes <td>
heading closes links
*/


window.WikEdHighlightSyntax = function(obj, noTimeOut) {

// start timer to cancel after wikEdMaxHighlightTime ms
	var highlightStartDate = new Date();

// &lt; &gt; &amp; to \x00 \x01
	obj.html = obj.html.replace(/&lt;/g, '\x00');
	obj.html = obj.html.replace(/&gt;/g, '\x01');
	obj.html = obj.html.replace(/&amp;/g, '&');

// trailing, leading, and multi spaces to nbsp
	obj.html = obj.html.replace(/^ | $/gm, '\xa0');
	obj.html = obj.html.replace(/(\n|\xa0 | ) /g, '$1\xa0');

// define parse object
	var parseObj = {

// tree object that holds nodes to be sorted and joined for final text:
// { 'tag': , 'parent': , 'firstChild': , 'nextSibling': , 'start': , 'tagLength': , 'type': , 'pairedTagPos': , 'left': , 'right': , 'index':, 'noHide': }
		'tree': [],

// main regular expression for syntactic elements
		'regExp': null,

// last match
		'regExpMatch': null,

// highlight whole text or fragment
		'whole': false,

// ignore leading closing tags for fragment highlighting
		'addedOpenTag': false,

// quick references
		'lastOpenTag': null,
		'lastOpenNode': 0,

// filtered ignore p tags
		'lastOpenNodeFiltered': null,
		'lastOpenTagFiltered': null,

		'secondlastOpenNodeFiltered': null,
		'secondLastOpenTagFiltered': null,

		'tableMode': wikEdTableMode
	};

// add root node
	parseObj.tree[0] = { 'type': 'root' };

// clear array of link addresses and preview image ids
	if (obj.whole == true) {
		parseObj.whole = true;
		wikEdLinkifyArray = [];
		wikEdReferenceArray = [];
		wikEdTemplateArray = [];
		wikEdCharEntityArray = [];
		WikEdHighlightNamedHideButtonsStylesheet = new WikEdStyleSheet(wikEdFrameDocument);
		wikEdFilePreviewNo = 0;
		wikEdFilePreviewIds = [];
	}

// remove comments
	var content = '';
	var from = 0;
	var commentsLength = 0;
	var regExpMatch;
	var regExpComments = /(\x00!--)(.|\n)*?(--\x01)/g;
	while ( (regExpMatch = regExpComments.exec(obj.html)) != null) {
		parseObj.tree.push( { 'tag': 'comment', 'start': regExpMatch.index - commentsLength, 'tagLength': 0, 'type': 'comment', 'left': regExpMatch[0] } );
		content += obj.html.substring(from, regExpMatch.index);
		commentsLength += regExpMatch[0].length;
		from = regExpComments.lastIndex;
	}
	if (parseObj.tree.length > 0) {
		content += obj.html.substring(from);
		obj.html = content;
	}
//// opening block tags and templates break link?

// show main parsing regExp:
// WED('regExp', wikEdParseObj.regExpTags.toString().replace(/\x00/g, '<').replace(/\x01/g, '>').replace(/\n/g, '\\n'));

// cycle through text and find tags with a regexp search
	wikEdParseObj.regExpTags.lastIndex = 0;
	while ( (parseObj.regExpMatch = wikEdParseObj.regExpTags.exec(obj.html)) != null) {

// cancel highlighting after wikEdMaxHighlightTime ms
		if (noTimeOut != true) {
			var currentDate = new Date();
			if ( (currentDate - highlightStartDate) > wikEdMaxHighlightTime) {
				break;
			}
		}

		var tagMatch  = parseObj.regExpMatch[0];
		var tagFrom = parseObj.regExpMatch.index;
		var tagLength = tagMatch.length;
		var tagTo = tagFrom + tagLength;

		var tagMatchParenth = 0;

// get regexp index number from first defined parenthesized submatch
		var tag = '';
		var tagClass = '';
		var tagStart = '';
		for (var i = 1; i < wikEdParseObj.matchToTag.length; i ++) {
			if (typeof(parseObj.regExpMatch[i]) != 'undefined') {

// get tag information
				tag = wikEdParseObj.matchToTag[i][0];
				tagClass = wikEdParseObj.matchToTag[i][1];
				tagStart = wikEdParseObj.matchToTag[i][2];
				tagMatchParenth = i;
				break;
			}
		}

// handle chew-up regExp matches that massively speed up regexp search
		if (tagClass == 'ignore') {

// move regExp pointer back if chew-up regExp fragment has eaten into the start of an inline link
			if (obj.html.charAt(wikEdParseObj.regExpTags.lastIndex) == ':') {
				var regExpMatch = /(https?|ftp|irc|gopher)$/.exec(tagMatch);
				if (regExpMatch != null) {
					wikEdParseObj.regExpTags.lastIndex = wikEdParseObj.regExpTags.lastIndex - regExpMatch[0].length;
				}
			}
			continue;
		}

// detect and remove newline from leading (^|\n) in sub-regexp
		var leadingNewline = false;
		if (tagStart == true) {
			if (parseObj.regExpMatch[tagMatchParenth + 1] == '\n') {
				tagFrom ++;
				tagLength --;
				leadingNewline = true;
			}
		}

// newlines close or end certain tags
		if (leadingNewline == true) {
			WikEdHighlightBuildTree('newline', 'close', tagFrom, 0, parseObj);
		}

// no wikicode in link target or template or parameter name, only after pipe in linkPiped, or parameterPiped
		if ( (parseObj.lastOpenTag == 'link') || (parseObj.lastOpenTag == 'template') || (parseObj.lastOpenTag == 'parameter') ) {

			var openNode = parseObj.tree[parseObj.lastOpenNodeFiltered];
			if ( (openNode != null) && (openNode.firstChild == null) ) {

				if (
					( (tagClass == 'open') && (tag != 'template') ) ||
					( (tagClass == 'block') && (tag != 'newlinePipe') && (tag != 'doublePipe') && (tag != 'pipe') && (tag != 'headerSep') && (tag != 'headerParam') && (tag != 'newline') && (tag != 'preform') ) //// preform ok?
				) {

// convert opening tag to error and continue
					var errorText;
					if (parseObj.lastOpenTag == 'link') {
						errorText = wikEdText['wikEdErrorCodeInLinkName'];
					}
					else if (parseObj.lastOpenTag == 'template') {
						errorText = wikEdText['wikEdErrorCodeInTemplName'];
					}
					else if (parseObj.lastOpenTag == 'parameter') {
						errorText = wikEdText['wikEdErrorCodeInParamName'];
					}
					WikEdHighlightMarkLastOpenNode(errorText, parseObj);
					WikEdHighlightGetLevel(parseObj);
				}
			}
		}

// handle current tag by dispatching infos to stack manager that builds a hierarchical tree
		switch (tag) {

// non-ambiguous tags
			case 'nowiki':
			case 'pre':
			case 'math':
			case 'br':
			case 'table':
			case 'file':
			case 'heading':
			case 'redirect':
			case 'magic':
			case 'signature':
			case 'hr':
				WikEdHighlightBuildTree(tag, tagClass, tagFrom, tagLength, parseObj);
				break;

// bold and italic
			case 'boldItalic':
				switch (tagLength) {
					case 2:
						switch(parseObj.lastOpenTagFiltered) {
							case 'italic':
								WikEdHighlightBuildTree('italic', 'close', tagFrom, tagLength, parseObj);
								break;
							case 'boldItalic':
								WikEdHighlightTreeRedefine(parseObj.lastOpenNodeFiltered, 'italic', 3, 2, parseObj);
								WikEdHighlightTreeRedefine(parseObj.secondlastOpenNodeFiltered, 'bold', 0, 3, parseObj);
								WikEdHighlightGetLevel(parseObj);
								WikEdHighlightBuildTree('italic', 'close', tagFrom, tagLength, parseObj);
								break;
							default:
								WikEdHighlightBuildTree('italic', 'open', tagFrom, tagLength, parseObj);
						}
						break;
					case 3:
						switch(parseObj.lastOpenTagFiltered) {
							case 'bold':
								WikEdHighlightBuildTree('bold', 'close', tagFrom, tagLength, parseObj);
								break;
							case 'boldItalic':
								WikEdHighlightTreeRedefine(parseObj.lastOpenNodeFiltered, 'bold', 2, 3, parseObj);
								WikEdHighlightTreeRedefine(parseObj.secondlastOpenNodeFiltered, 'italic', 0, 2, parseObj);
								WikEdHighlightGetLevel(parseObj);
								WikEdHighlightBuildTree('bold', 'close', tagFrom, tagLength, parseObj);
								break;
							default:
								WikEdHighlightBuildTree('bold', 'open', tagFrom, tagLength, parseObj);
						}
						break;
					case 5:
						switch(parseObj.lastOpenTagFiltered) {
							case 'bold':
								if (parseObj.secondLastOpenTagFiltered == 'italic') {
									WikEdHighlightBuildTree('bold', 'close', tagFrom, 3, parseObj);
									WikEdHighlightBuildTree('italic', 'close', tagFrom + 3, 2, parseObj);
								}
								else {
									WikEdHighlightBuildTree('bold', 'close', tagFrom, 3, parseObj);
									WikEdHighlightBuildTree('italic', 'open', tagFrom + 3, 2, parseObj);
								}
								break;
							case 'italic':
								if (parseObj.secondLastOpenTagFiltered == 'bold') {
									WikEdHighlightBuildTree('italic', 'close', tagFrom, 2, parseObj);
									WikEdHighlightBuildTree('bold', 'close', tagFrom + 2, 3, parseObj);
								}
								else {
									WikEdHighlightBuildTree('italic', 'close', tagFrom, 2, parseObj);
									WikEdHighlightBuildTree('bold', 'open', tagFrom + 2, 3, parseObj);
								}
								break;
							case 'boldItalic':
								WikEdHighlightTreeRedefine(parseObj.secondlastOpenNodeFiltered, 'bold', 0, 3, parseObj);
								WikEdHighlightTreeRedefine(parseObj.lastOpenNodeFiltered, 'italic', 3, 2, parseObj);
								WikEdHighlightGetLevel(parseObj);
								parseObj.lastOpenTag == 'italic'
								WikEdHighlightBuildTree('italic', 'close', tagFrom, 2, parseObj);
								WikEdHighlightBuildTree('bold', 'close', tagFrom + 2, 3, parseObj);
								break;
							default:
								WikEdHighlightBuildTree('boldItalic', 'open', tagFrom, tagLength, parseObj);
								WikEdHighlightBuildTree('boldItalic', 'open', tagFrom, tagLength, parseObj);
						}
						break;
					default:
						parseObj.tree.push( { 'start': tagFrom, 'tagLength': tagLength, 'type': 'error', 'left': wikEdText['wikEdErrorBoldItalic'] } );
						break;
				}
				break;

// templParam: template or template parameter
			case 'paramTempl':

// template or parameter
				var paramTemplTag = tag;
				if (tagLength == 2) {
					paramTemplTag = 'template';
				}
				else if (tagLength == 3) {
					paramTemplTag = 'parameter';
				}

// open paramTempl
				if (tagClass == 'open') {
					WikEdHighlightBuildTree(paramTemplTag, tagClass, tagFrom, tagLength, parseObj);

// add spare elements for later disambiguation
					if (paramTemplTag == 'paramTempl') {
						for (var pos = 2; pos < tagLength - 1; pos = pos + 2) {
							WikEdHighlightBuildTree(paramTemplTag, tagClass, tagFrom, tagLength, parseObj);
						}
					}
				}

// close paramTempl
				else {

// no opening tag, delegate error handling
					if ( (parseObj.lastOpenNode == 0) || (parseObj.lastOpenNode == null) ) {
						WikEdHighlightBuildTree(paramTemplTag, tagClass, tagFrom, tagLength, parseObj);
						break;
					}
					var openNode = parseObj.tree[parseObj.lastOpenNodeFiltered];
					if (openNode == null) {
						WikEdHighlightBuildTree(paramTemplTag, tagClass, tagFrom, tagLength, parseObj);
						break;
					}

// close template or parameter, open and close defined
					if (
						( (paramTemplTag == 'template') && (parseObj.lastOpenTagFiltered == 'template') ) ||
						( (paramTemplTag == 'parameter') && (parseObj.lastOpenTagFiltered == 'parameter') ) ||
						( (paramTemplTag == 'parameter') && (parseObj.lastOpenTagFiltered == 'parameterPiped') )
					) {
						WikEdHighlightBuildTree(paramTemplTag, tagClass, tagFrom, tagLength, parseObj);
					}

// closing defines ambiguous opening
					else if (
						( (paramTemplTag == 'template') || (paramTemplTag == 'parameter') ) &&
						(parseObj.lastOpenTagFiltered == 'paramTempl') &&
						(openNode.tagLength >= tagLength)
					) {

// redefine ambiguous opening
						WikEdHighlightTreeRedefine(parseObj.lastOpenNodeFiltered, paramTemplTag, openNode.tagLength - tagLength, tagLength, parseObj);

// adjust all ambiguous parents
						var redefinedTag;
						var redefinedLength;
						var nodeNo = openNode.parent;
						while ( (nodeNo != 0) && (nodeNo != null) ) {
							var node = parseObj.tree[nodeNo];
							if (node.tag != 'paramTempl') {
								break;
							}

							if (nodeNo == openNode.parent) {
								redefinedTag = node.tag;
								redefinedLength = node.tagLength - tagLength;
							}

// ignore spare paramTempl opening tags like p tags
							if (redefinedLength == 0) {
								redefinedTag = 'spare';
							}

// mark remaining single { as error
							else if (redefinedLength == 1) {
								parseObj.tree.push( {
									'start': node.start,
									'tagLength': node.tagLength,
									'type': 'error',
									'left': wikEdText['wikEdErrorTemplParam']
								} );
								redefinedTag = 'spare';
							}

// this is a template
							else if (redefinedLength == 2) {
								node.tag = 'template';
							}

// this is a parameter
							else if (redefinedLength == 3) {
								node.tag = 'parameter';
							}

// redefine parent
							WikEdHighlightTreeRedefine(nodeNo, redefinedTag, null, redefinedLength, parseObj);

// all further opening paramTempl tags are spare
							if (redefinedLength <= 3) {
								redefinedTag = 'spare';
								redefinedLength = 0
							}

// up one level
							nodeNo = node.parent;
						}
						WikEdHighlightGetLevel(parseObj);

// and close innermost tag
						WikEdHighlightBuildTree(paramTemplTag, tagClass, tagFrom, tagLength, parseObj);
					}

// opening defines ambiguous closing
					else if ( (
						(openNode.tag == 'template') ||
						(openNode.tag == 'parameter') ||
						(openNode.tag == 'parameterPiped') ) && (tagLength >= openNode.tagLength)
					) {
						WikEdHighlightBuildTree(openNode.tag, tagClass, tagFrom, openNode.tagLength, parseObj);
						wikEdParseObj.regExpTags.lastIndex = wikEdParseObj.regExpTags.lastIndex - tagLength + openNode.tagLength;
					}

// both ambiguous
					else if (
						(paramTemplTag == 'paramTempl') &&
						(openNode.tag == 'paramTempl') &&
						( (openNode.tagLength > 3) && (tagLength > 3) )
 					) {
						parseObj.tree.push( {
							'start': openNode.start,
							'tagLength': openNode.tagLength,
							'type': 'error',
							'left': wikEdText['wikEdErrorTemplParamAmbig']
						} );
						parseObj.tree.push( {
							'start': tagFrom,
							'tagLength': tagLength,
							'type': 'error',
							'left': wikEdText['wikEdErrorTemplParamAmbig']
						} );
					}

// opening and closing do not match
					else {
						parseObj.tree.push( {
							'start': openNode.start,
							'tagLength': openNode.tagLength,
							'type': 'error',
							'left': wikEdText['wikEdErrorTemplParam']
						} );
						parseObj.tree.push( {
							'start': tagFrom,
							'tagLength': tagLength,
							'type': 'error',
							'left': wikEdText['wikEdErrorTemplParam']
						} );
					}
				}
				break;

// table single elements
			case 'header':
			case 'headerSep':
			case 'headerParam':
			case 'row':
			case 'caption':
				if (parseObj.lastOpenTagFiltered == 'table') {
					WikEdHighlightBuildTree(tag, tagClass, tagFrom, tagLength, parseObj);
				}
				break;

// wikilink
			case 'link':
				WikEdHighlightBuildTree(tag, tagClass, tagFrom, 2, parseObj);
				break;

// inline link block and external link
			case 'inlineURL':
			case 'external':

// trailing punctuation not part of inline links
				if (tag == 'inlineURL') {
					var regExpMatch;
					if (/\(/.test(tagMatch) == true) {
						regExpMatch = /^(.*?)([\.\,\:\;\\\!\?\)]+)$/.exec(tagMatch);
					}
					else {
						regExpMatch = /^(.*?)([\.\,\:\;\\\!\?]+)$/.exec(tagMatch);
					}
					if (regExpMatch != null) {
						wikEdParseObj.regExpTags.lastIndex = tagFrom + regExpMatch[1].length;
						tagMatch = regExpMatch[1];
						tagLength = tagMatch.length;
						tagTo = tagFrom + tagLength;
					}
				}

// urls in templates or tables are interrupted by tag strings
				if (tag == 'inlineURL') {
					var node = parseObj.tree[parseObj.lastOpenNode];
					while (node != null) {

// urls in templates are interrupted by }} and |
						if ( (node.tag == 'template') || (node.tag == 'paramTempl') || (node.tag == 'parameter') || (node.tag == 'parameterPiped') ) {
							var regExpMatch;
							if ( (regExpMatch = /^(.*?)(\}\}|\|)(.*?)$/.exec(tagMatch)) != null) {
								wikEdParseObj.regExpTags.lastIndex = tagFrom + tagMatch[1].length;
								tagMatch = regExpMatch[1];
								tagLength = tagMatch.length;
								tagTo = tagFrom + tagLength;
							}
							break;
						}

// urls in tables are interrupted by ||
						else if (node.tag == 'table') {
							var regExpMatch;
							if ( (regExpMatch = /^(.*?)(\}\}|\|)(.*?)$/.exec(tagMatch)) != null) {
								wikEdParseObj.regExpTags.lastIndex = tagFrom + tagMatch[1].length;
								tagMatch = regExpMatch[1];
								tagLength = tagMatch.length;
								tagTo = tagFrom + tagLength;
							}
							break;
						}
						node = parseObj.tree[node.parent];
					}
				}

// dissect external [url text
				if (tag == 'external') {
					if (tagClass == 'open') {
						var url = parseObj.regExpMatch[tagMatchParenth + 1];
						var spaces = parseObj.regExpMatch[tagMatchParenth + 5];
						WikEdHighlightBuildTree(tag, tagClass, tagFrom, 1, parseObj);
						WikEdHighlightBuildTree('externalURL', 'block', tagFrom + 1, url.length, parseObj);
						WikEdHighlightBuildTree('externalText', tagClass, tagFrom + 1 + url.length + spaces.length, 0, parseObj);
					}

// close ], ignore false positive non-tags that have no opening [
					else {
						var node = parseObj.tree[parseObj.lastOpenNode];
						while (node != null) {
							if (node.tag == tag) {
								break;
							}
							node = parseObj.tree[node.parent];
						}
						if (node != null) {
							if (node.parent != null) {
								WikEdHighlightBuildTree('externalText', tagClass, tagFrom, 0, parseObj);
								WikEdHighlightBuildTree(tag, tagClass, tagFrom, tagLength, parseObj);
							}
						}
					}
					break;
				}

				WikEdHighlightBuildTree(tag, tagClass, tagFrom, tagLength, parseObj);
				break;

// <html>
			case 'html':
				var htmlTag = parseObj.regExpMatch[tagMatchParenth + 1].toLowerCase();
				if (/^(ref|references|sub|sup|u|s|p)$/.test(htmlTag) == true) {
					WikEdHighlightBuildTree(htmlTag, tagClass, tagFrom, tagLength, parseObj);
				}
				else if (/^(table|tr|td|th|col|thead|tfoot|tbody|colgroup|caption|big|blockquote|center|code|del|div|font|ins|small|span|strike|tt|rb|rp|rt|ruby|nowiki|math|noinclude|includeonly|gallery|categorytree|charinsert|hiero|imagemap|inputbox|poem|source|syntaxhighlight|timeline)$/.test(htmlTag) == true) {
					WikEdHighlightBuildTree(htmlTag, tagClass, tagFrom, tagLength, parseObj);
				}
				else {
					WikEdHighlightBuildTree('htmlUnknown', 'block', tagFrom, tagLength, parseObj);
				}
				break;

// <html />
			case 'htmlEmpty':
				var htmlTag = parseObj.regExpMatch[tagMatchParenth + 1];
				if (/^(references|ref|br|p)$/i.test(htmlTag) == true) {
					WikEdHighlightBuildTree(htmlTag, tagClass, tagFrom, tagLength, parseObj);
				}
				else {
					WikEdHighlightBuildTree(tag, tagClass, tagFrom, tagLength, parseObj);
				}
				break;

// |}}: table end or empty template parameter + template end
			case 'pipeTemplateEnd':
				if (parseObj.lastOpenTagFiltered == 'table') {
					WikEdHighlightBuildTree('table', 'close', tagFrom, 2, parseObj);
				}
				else {
					WikEdHighlightBuildTree('templateParam', 'block', tagFrom, 1, parseObj);
					WikEdHighlightBuildTree('template', 'close', tagFrom + 1, 2, parseObj);
				}
				break;

// ]]: wikilink, file link, redirect
			case 'doubleCloseBracket':
				if (parseObj.lastOpenTagFiltered == 'file') {
					WikEdHighlightBuildTree(parseObj.lastOpenTagFiltered, tagClass, tagFrom, tagLength, parseObj);
				}
				else {
					WikEdHighlightBuildTree('link', tagClass, tagFrom, tagLength, parseObj);
				}
				break;

// \n|: table cell, wikilink separator, file parameter separator, redirect separator, empty template parameter
			case 'newlinePipe':
				switch (parseObj.lastOpenTagFiltered) {
					case 'table':
						WikEdHighlightBuildTree('cell', tagClass, tagFrom, tagLength, parseObj);
						break;
					case 'link':
						WikEdHighlightBuildTree('linkParam', tagClass, tagFrom, tagLength, parseObj);
						break;
					case 'file':
						WikEdHighlightBuildTree('fileParam', tagClass, tagFrom, tagLength, parseObj);
						break;
					case 'template':
					case 'paramTempl':
						WikEdHighlightBuildTree('templateParam', tagClass, tagFrom, tagLength, parseObj);
						break;
				}
				break;

// ||: table cell separator, empty file parameter separator, empty template parameters
			case 'doublePipe':
				switch (parseObj.lastOpenTagFiltered) {
					case 'table':
						WikEdHighlightBuildTree('cellSep', tagClass, tagFrom, tagLength, parseObj);
						break;
					case 'link':
						WikEdHighlightBuildTree('linkParam', tagClass, tagFrom, 1, parseObj);
						break;
					case 'file':
						WikEdHighlightBuildTree('fileParam', tagClass, tagFrom, 1, parseObj);
						WikEdHighlightBuildTree('fileParam', tagClass, tagFrom + 1, 1, parseObj);
						break;
					case 'template':
					case 'paramTempl':
						WikEdHighlightBuildTree('templateParam', tagClass, tagFrom, 1, parseObj);
						WikEdHighlightBuildTree('templateParam', tagClass, tagFrom + 1, 1, parseObj);
						break;
				}
				break;

// pipe |: table cell parameter separator, table caption parameter separator, wikilink separator, file parameter separator, template parameter, parameter default
			case 'pipe':
				switch (parseObj.lastOpenTagFiltered) {
					case 'table':
						WikEdHighlightBuildTree('cellParam', tagClass, tagFrom, tagLength, parseObj);
						break;
					case 'link':
						WikEdHighlightBuildTree('linkParam', tagClass, tagFrom, tagLength, parseObj);
						break;
					case 'file':
						WikEdHighlightBuildTree('fileParam', tagClass, tagFrom, tagLength, parseObj);
						break;
					case 'template':
					case 'paramTempl':///// check later for parameterPiped
						WikEdHighlightBuildTree('templateParam', tagClass, tagFrom, tagLength, parseObj);
						break;
					case 'parameter':
						WikEdHighlightBuildTree('parameterDefault', tagClass, tagFrom, tagLength, parseObj);
						break;
				}
				break;

// list and preformatted (leading space) lines
			case 'preform':

// ignore template parameters preceeded with newline-spaces
				if (parseObj.lastOpenTagFiltered == 'template') {
					wikEdParseObj.regExpTags.lastIndex = tagFrom + tagLength - parseObj.regExpMatch[tagMatchParenth + 3].length;
					break;
				}
			case 'list':

// highlight line
				WikEdHighlightBuildTree(tag, tagClass, tagFrom, tagLength, parseObj);

// highlight tag
				WikEdHighlightBuildTree(tag + 'Tag', tagClass, tagFrom, parseObj.regExpMatch[tagMatchParenth + 2].length, parseObj);

// move text pointer after tag
				wikEdParseObj.regExpTags.lastIndex = tagFrom + tagLength - parseObj.regExpMatch[tagMatchParenth + 3].length;
				break;

// newline, old
			case 'newline':
				WikEdHighlightBuildTree(tag, 'close', tagFrom, 0, parseObj);
				break;

// unrecognized tag
			default:
				parseObj.tree.push( { 'start': tagFrom, 'tagLength': tagLength, 'type': 'error', 'left': wikEdText['wikEdErrorNoHandler'] } );
		}

// quit after reaching $ 'newline'
		if (tagMatch == '') {
			break;
		}
	}

// do not tolerate trailing opening tags for whole text highlighting
	if (parseObj.whole == true)  {

// mark remaining unmatched opening tags
		while ( (parseObj.lastOpenNode != 0) && (parseObj.lastOpenNode != null) ) {
			WikEdHighlightMarkLastOpenNode(wikEdText['wikEdErrorNoClose'], parseObj);
		}
	}

// show parsing tree before additional block highlighting:
// WED('parseObj.tree', parseObj.tree);

// wiki autolinking (case sensitive, newlines are actually allowed!)
	var regExpMatch;
	var regExpAutoLink = /((PMID)[ \xa0\t]+(\d+))|((RFC)[ \xa0\t]+(\d+))|((RFC)[ \xa0\t]+(\d+))|((ISBN)[ \xa0\t]+((97(8|9)( |-)?)?(\d( |-)?){9}(\d|x)))/g;
	while ( (regExpMatch = regExpAutoLink.exec(obj.html) ) != null) {
		WikEdHighlightBuildTree(regExpMatch[2] || regExpMatch[5] || regExpMatch[8] || regExpMatch[11], 'block', regExpMatch.index, regExpMatch[0].length, parseObj);
	}

// named html colors in quotation marks
	var regExpColorLight = /(\'|\")(aliceblue|antiquewhite|aqua|aquamarine|azure|beige|bisque|blanchedalmond|burlywood|chartreuse|coral|cornsilk|cyan|darkgray|darkgrey|darkkhaki|darkorange|darksalmon|darkseagreen|floralwhite|fuchsia|gainsboro|ghostwhite|gold|goldenrod|greenyellow|honeydew|hotpink|ivory|khaki|lavender|lavenderblush|lawngreen|lemonchiffon|lightblue|lightcoral|lightcyan|lightgoldenrodyellow|lightgray|lightgreen|lightgrey|lightpink|lightsalmon|lightskyblue|lightsteelblue|lightyellow|lime|linen|magenta|mediumaquamarine|mediumspringgreen|mediumturquoise|mintcream|mistyrose|moccasin|navajowhite|oldlace|orange|palegoldenrod|palegreen|paleturquoise|papayawhip|peachpuff|peru|pink|plum|powderblue|salmon|sandybrown|seashell|silver|skyblue|snow|springgreen|tan|thistle|turquoise|violet|wheat|white|whitesmoke|yellow|yellowgreen)(\1)/gi;
	while ( (regExpMatch = regExpColorLight.exec(obj.html) ) != null) {
		WikEdHighlightBuildTree('colorLight', 'block', regExpMatch.index + regExpMatch[1].length, regExpMatch[2].length, parseObj);
	}
	var regExpColorDark = /(\'|\")(black|blue|blueviolet|brown|cadetblue|chocolate|cornflowerblue|crimson|darkblue|darkcyan|darkgoldenrod|darkgreen|darkmagenta|darkolivegreen|darkorchid|darkred|darkslateblue|darkslategray|darkslategrey|darkturquoise|darkviolet|deeppink|deepskyblue|dimgray|dimgrey|dodgerblue|firebrick|forestgreen|gray|green|grey|indianred|indigo|lightseagreen|lightslategray|lightslategrey|limegreen|maroon|mediumblue|mediumorchid|mediumpurple|mediumseagreen|mediumslateblue|mediumvioletred|midnightblue|navy|olive|olivedrab|orangered|orchid|palevioletred|purple|red|rosybrown|royalblue|saddlebrown|seagreen|sienna|slateblue|slategray|slategrey|steelblue|teal|tomato)(\1)/g;
	while ( (regExpMatch = regExpColorDark.exec(obj.html) ) != null) {
		WikEdHighlightBuildTree('colorDark', 'block', regExpMatch.index + regExpMatch[1].length, regExpMatch[2].length, parseObj);
	}

// RGB hex colors #ddc, exclude links and character entities starting with &
	var regExpColor3 = /(^|[^\/\w&])(#[0-9a-f]{3})(?=([^\d\w]|$))/gi;
	while ( (regExpMatch = regExpColor3.exec(obj.html) ) != null) {
		WikEdHighlightBuildTree('colorHex3', 'block', regExpMatch.index + regExpMatch[1].length, regExpMatch[2].length, parseObj);
	}

// RGB hex colors #d4d0cc, exclude links and character entities starting with &
	var regExpColor6 = /(^|[^\/\w&])(#[0-9a-f]{6})(?=([^\d\w]|$))/gi;
	while ( (regExpMatch = regExpColor6.exec(obj.html) ) != null) {
		WikEdHighlightBuildTree('colorHex6', 'block', regExpMatch.index + regExpMatch[1].length, regExpMatch[2].length, parseObj);
	}

// RGB decimal colors rgb(128,64,265)
	var regExpColorDec = /\brgb\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}\s*\)/gi;
	while ( (regExpMatch = regExpColorDec.exec(obj.html) ) != null) {
		WikEdHighlightBuildTree('colorDec', 'block', regExpMatch.index, regExpMatch[0].length, parseObj);
	}

// single character highlighting: spaces, dashes
	var regExpCharSpaceDash = new RegExp('[' + wikEdCharHighlightingStr + ']', 'g');
	while ( (regExpMatch = regExpCharSpaceDash.exec(obj.html) ) != null) {
		WikEdHighlightBuildTree('char', 'block', regExpMatch.index, regExpMatch[0].length, parseObj);
	}

// control character highlighting
	var regExpCharCtrl = new RegExp('[' + wikEdControlCharHighlightingStr + ']', 'g');
	while ( (regExpMatch = regExpCharCtrl.exec(obj.html) ) != null) {
		if (regExpMatch[0].charCodeAt(0) > 2) {
			WikEdHighlightBuildTree('ctrl', 'block', regExpMatch.index, regExpMatch[0].length, parseObj);
		}
	}

// character entities
	var regExpCharEntities = /&(\w+);/g;
	while ( (regExpMatch = regExpCharEntities.exec(obj.html) ) != null) {
		if (wikEdCharEntitiesByName[ regExpMatch[1] ] != null) {
			WikEdHighlightBuildTree('charEntity', 'block', regExpMatch.index, regExpMatch[0].length, parseObj);
		}
	}

// merge wiki syntax in
	WikEdHighlightAddHtml(parseObj, obj);

// get file previews
	if ( (wikEdFilePreview == true) && (wikEdFilePreviewRequest != '') ) {
		WikEdAjaxPreview(wikEdFilePreviewRequest, WikEdFilePreviewAjaxHandler);
		wikEdFilePreviewRequest = '';
	}

// merge html and plain text
	WikEdHighlightMergeHtml(parseObj, obj);

// free up array
	parseObj.tree = [];

// \x00 and \x01 back to &lt; and &gt;
	obj.html = obj.html.replace(/&/g, '&amp;');
	obj.html = obj.html.replace(/\x00/g, '&lt;');
	obj.html = obj.html.replace(/\x01/g, '&gt;');

// remove comments
	if (wikEdRemoveHighlightComments == true) {
		obj.html = obj.html.replace(/<!--wikEd[\w\/]+-->/g, '');
	}

	return;
};


//
// WikEdHighlightTreeRedefine: redefine opening tag, for bold / italic and template / parameter
//

window.WikEdHighlightTreeRedefine = function(openNodeIndex, tag, tagFromDiff, tagLength, parseObj) {

	if (tag != null) {
		parseObj.tree[openNodeIndex].tag = tag;
	}
	if (tagFromDiff != null) {
		parseObj.tree[openNodeIndex].start += tagFromDiff;
	}
	if (tagLength != null) {
		parseObj.tree[openNodeIndex].tagLength = tagLength;
	}

	return;
};


//
// WikEdHighlightBuildTree: build an array based tree structure of text elements
//   tag info: text pos, text length, tag type (root, open, close, block, error)
//   connectivity info: parent, firstChild, nextSibling, paired opening/closing (all array indexes)

window.WikEdHighlightBuildTree = function(tag, tagClass, tagFrom, tagLength, parseObj) {

// show parameters:
// WED('tag, tagClass, tagFrom, tagLength', tag + ' ,' + tagClass + ', ' + tagFrom + ', ' + tagLength);

// single-element tags (block)
	if (tagClass == 'block') {
		if ( (parseObj.lastOpenNode != 0) && (parseObj.lastOpenNode != null) ) {
			var redefinedParentTag;

// change parent link to linkPiped, only one valid separator per link
			if ( (tag == 'linkParam') && (parseObj.lastOpenTag == 'link') ) {
				redefinedParentTag = 'linkPiped';
			}

// change parent link to parameterPiped, only one valid separator per link
			else if ( (tag == 'parameterDefault') && (parseObj.lastOpenTag == 'parameter') ) {
				redefinedParentTag = 'parameterPiped';
			}

// redefine parent tag
			if (redefinedParentTag != null) {
				parseObj.tree[parseObj.lastOpenNode].tag = redefinedParentTag;
				parseObj.lastOpenTagFiltered = redefinedParentTag;
			}

// chain blocks
			var newNode = parseObj.tree.length;
			var previousSibling = null;

// first node
			if (parseObj.tree[parseObj.lastOpenNode].firstChild == null) {
				parseObj.tree[parseObj.lastOpenNode].firstChild = newNode;
			}

// chain to previous blocks
			else {
				previousSibling = parseObj.tree[parseObj.lastOpenNode].lastChild;
				var previousSiblingNode = parseObj.tree[previousSibling];
				if (previousSiblingNode != null) {
					previousSiblingNode.nextSibling = newNode;
				}
			}
			parseObj.tree[parseObj.lastOpenNode].lastChild = newNode;
		}

// add new block to tree
		parseObj.tree.push( {
			'tag': tag,
			'start': tagFrom,
			'tagLength': tagLength,
			'type': 'block',
			'parent': parseObj.lastOpenNode,
			'previousSibling': previousSibling
		} );
	}

// opening tags
	else if (tagClass == 'open') {

// push new open element onto tree
		var openNode = {
			'tag': tag,
			'start': tagFrom,
			'tagLength': tagLength,
			'type': 'open',
			'parent': parseObj.lastOpenNode
		};
		parseObj.lastOpenNode = parseObj.tree.push(openNode) - 1;

// get new top and second-to-top nodes, ignoring unpaired p tags
		WikEdHighlightGetLevel(parseObj);
	}

// closing tags
	else if (tagClass == 'close') {

// try until we find the correct opening tag after fixing the tree
		while (true) {

// no opening tag on stack
			if (parseObj.lastOpenNode == 0) {

// ignore unmatched =
				if (tag == 'heading') {
					break;
				}

// ignore breaking newlines
				if (tag != 'newline') {

// tolerate leading closing tags for fragment highlighting
					if ( (parseObj.whole == false) && (parseObj.addedOpenTag == false) )  {

// add new closing element to tree
						parseObj.tree.push( {
							'tag': tag,
							'start': tagFrom,
							'tagLength': tagLength,
							'type': 'close',
							'pairedTagPos': parseObj.tree[parseObj.lastOpenNode].start + parseObj.tree[parseObj.lastOpenNode].tagLength
						} );
					}

// add no open tag error to tree
					else {
						parseObj.tree.push( {
							'start': tagFrom,
							'tagLength': tagLength,
							'type': 'error',
							'left': wikEdText['wikEdErrorNoOpen']
						} );
					}
					break;
				}
			}

// ignore unpaired <p> and spare nodes and try again with parent
			if ( (tag != 'p') && ( (parseObj.lastOpenTag == 'p') || (parseObj.lastOpenTag == 'spare') ) ) {
				if (parseObj.lastOpenNode != null) {
					parseObj.lastOpenNode = parseObj.tree[parseObj.lastOpenNode].parent;
					parseObj.lastOpenTag = parseObj.lastOpenNode.tag;
				}
				continue;
			}

// newline brakes heading or external link, remove corresponding opening tag from stack
			if (tag == 'newline') {

// mark broken opening tags
				var nodeNo = parseObj.lastOpenNode;
				var node = null;
				while ( (nodeNo != 0) && (nodeNo != null) ) {
					node = parseObj.tree[nodeNo];
					if (
						(node.tag == 'heading') ||
						(node.tag == 'link') ||
						(node.tag == 'linkPiped') ||
						(node.tag == 'externalText') ||
						(node.tag == 'bold') ||
						(node.tag == 'italic') ||
						(node.tag == 'boldItalic')
					) {
						WikEdHighlightMarkLastOpenNode(wikEdText['wikEdErrorNewline'], parseObj);
						WikEdHighlightGetLevel(parseObj);
					}
					nodeNo = node.parent;
				}
				break;
			}

// correct piped link
			switch (tag) {
				case 'link':
					if (parseObj.lastOpenTag == 'linkPiped') {
						tag = 'linkPiped';
					}
					break;

// correct piped parameter
				case 'parameter':
					if (parseObj.lastOpenTag == 'parameterPiped') {
						tag = 'parameterPiped';
					}
					break;
			}

// wrong closing element
			if (tag != parseObj.lastOpenTag) {

// ignore common unmatched false positive non-tags: = and ]
				if ( (tag == 'heading') ) {
					break;
				}

// check if there is an open tag for this close tag
				var nodeNo = parseObj.lastOpenNode;
				while ( (nodeNo != 0) && (nodeNo != null) ) {
					if (parseObj.tree[nodeNo].tag == tag) {
						break;
					}
					nodeNo = parseObj.tree[nodeNo].parent;
				}
				if ( (nodeNo != 0) && (nodeNo != null) ) {

// treat open tags as wrong, close tag as correct
					if (parseObj.tree[nodeNo].tag == tag) {

// mark remaining unmatched opening tags
						var nodeNo = parseObj.lastOpenNode;
						while ( (nodeNo != 0) && (nodeNo != null) ) {
							var node = parseObj.tree[nodeNo];
							if (node.tag == tag) {
								parseObj.lastOpenNode = nodeNo;
								break;
							}
							nodeNo = node.parent;
							node.type = 'error';
							node.left = wikEdText['wikEdErrorNoClose'];
							node.parent = null;
						}
						WikEdHighlightGetLevel(parseObj);
					}

// treat open tags as correct, treat close tag as wrong
					else {

// add wrong close tag error to tree
						parseObj.tree.push( {
							'start': tagFrom,
							'tagLength': tagLength,
							'type': 'error',
							'left': wikEdText['wikEdErrorNoOpen']
						} );
						break;
					}
				}
			}

// headings in templates are ignored but we do not want to hide that template
			if (tag == 'heading') {

// check for heading in template or ref
				var ignoreHeading = false;
				var nodeNo = parseObj.tree[parseObj.lastOpenNode].parent;
				while ( (nodeNo != 0) && (nodeNo != null) ) {
					var node = parseObj.tree[nodeNo];
					if (node.tag == 'template') {
						node.noHide = true;
						ignoreHeading = true;
					}
					else if (node.tag == 'ref') {
						node.noHide = true;
						ignoreHeading = true;
					}
					nodeNo = node.parent;
				}

// clean out opening heading
				if (ignoreHeading == true) {

// add headings in template errors to tree

// convert opening tag to error
					WikEdHighlightMarkLastOpenNode(wikEdText['wikEdErrorTemplHeading'], parseObj);

					parseObj.tree.push( {
						'start': tagFrom,
						'tagLength': tagLength,
						'type': 'error',
						'left': wikEdText['wikEdErrorTemplHeading']
					} );
					break;
				}
			}

// it is the correct closing element

// save element last text position to opening tag entry
			var pairedTagPos;
			parseObj.tree[parseObj.lastOpenNode].pairedTagPos = tagFrom;
			pairedTagPos = parseObj.tree[parseObj.lastOpenNode].start + parseObj.tree[parseObj.lastOpenNode].tagLength

// add new closing element to tree
			parseObj.tree.push( {
				'tag': tag,
				'start': tagFrom,
				'tagLength': tagLength,
				'type': 'close',
				'pairedTagPos': pairedTagPos
			} );

// up one level
			if ( (parseObj.lastOpenNode != 0) && (parseObj.lastOpenNode != null) ) {
				parseObj.lastOpenNode = parseObj.tree[parseObj.lastOpenNode].parent;
			}

			break;
		}

// get new top and second-to-top nodes, ignoring unpaired p tags
		WikEdHighlightGetLevel(parseObj);
	}
	return;
};


//
// WikEdHighlightMarkLastOpenNode: redefine last open node as an error, ignore p and spare, handle pipe subnodes
//

window.WikEdHighlightMarkLastOpenNode = function(errorText, parseObj) {

	var lastOpenNode = parseObj.lastOpenNode;
	var openNode = parseObj.tree[lastOpenNode];
	parseObj.lastOpenNode = openNode.parent;
	if ( (openNode.tag != 'p') && (openNode.tag != 'spare') ) {

// mark pipes
		if ( (openNode.tag == 'linkPiped') || (openNode.tag = 'parameterPiped') || (openNode.tag = 'template') || (openNode.tag = 'paramTempl') ) {
			var childNode = parseObj.tree[openNode.firstChild];
			if (childNode != null) {
				parseObj.tree[openNode.firstChild] = {
					'start': childNode.start,
					'tagLength': childNode.tagLength,
					'type': 'error',
					'left': errorText
				};
			}
		}

// mark unmatched opening tags
		parseObj.tree[lastOpenNode] = {
			'start': openNode.start,
			'tagLength': openNode.tagLength,
			'type': 'error',
			'left': errorText
		};
	}
	return;
}


//
// WikEdHighlightGetLevel: get current innermost (top) element name from parse stack, ignoring unpaired p tags
//

window.WikEdHighlightGetLevel = function(parseObj) {

	parseObj.lastOpenTag = null;
	parseObj.lastOpenNodeFiltered = null;
	parseObj.lastOpenTagFiltered = null;
	parseObj.secondlastOpenNodeFiltered = null;
	parseObj.secondLastOpenTagFiltered = null;
	if ( (parseObj.lastOpenNode == 0) || (parseObj.lastOpenNode == null) ) {
		return;
	}

	parseObj.lastOpenTag = parseObj.tree[parseObj.lastOpenNode].tag;
	var nodeNo = parseObj.lastOpenNode;
	while ( (nodeNo != 0) && (nodeNo != null) ) {
		var node = parseObj.tree[nodeNo];
		if ( (node.tag != 'p') && (node.tag != 'spare') ) {
			parseObj.lastOpenNodeFiltered = nodeNo;
			parseObj.lastOpenTagFiltered = parseObj.tree[nodeNo].tag;
			break;
		}
		nodeNo = parseObj.tree[nodeNo].parent;
	}

	if ( (nodeNo != 0) && (nodeNo != null) ) {
		nodeNo = parseObj.tree[nodeNo].parent;
		while ( (nodeNo != 0) && (nodeNo != null) ) {
			var node = parseObj.tree[nodeNo];
			if ( (node.tag != 'p') && (node.tag != 'spare') ) {
				parseObj.secondlastOpenNodeFiltered = nodeNo;
				parseObj.secondLastOpenTagFiltered = parseObj.tree[nodeNo].tag;
				break;
			}
			nodeNo = parseObj.tree[nodeNo].parent;
		}
	}

	return;
};


//
// WikEdHighlightAddCode: add actual highlighting html code to parse tree elements
//

window.WikEdHighlightAddHtml = function(parseObj, obj) {

// cycle through currently existing parse array
	var from = 0;
	var i = 0;
	while (i < parseObj.tree.length) {
		var node = parseObj.tree[i];
		var tag = node.tag;
		var tagFrom = node.start;
		var tagLength = node.tagLength;
		var tagType = node.type;
		var pairedTagPos = node.pairedTagPos;
		var tagTo = tagFrom + tagLength;
		var tagMatch = '';
		if (tagLength > 0) {
			tagMatch = obj.html.substr(tagFrom, tagLength);
		}

		var insertLeft = '';
		var insertRight = '';
		var pushRight = '';
		var pushRight2 = '';
		var pushRightPos2;
		var pushLeft = '';

		switch (tagType) {
			case 'open':
				var innerPlain = '';
				if (pairedTagPos != null) {
					innerPlain = obj.html.substring(tagTo, pairedTagPos);
				}
				switch (tag) {
					case 'italic':
						insertLeft = '<span class="wikEdItalic"><span class="wikEdWiki">';
						insertRight = '</span><!--wikEdWiki-->';
						break;
					case 'bold':
						insertLeft = '<span class="wikEdBold"><span class="wikEdWiki">';
						insertRight = '</span><!--wikEdWiki-->';
						break;
					case 'link':
					case 'linkPiped':
						var inter = '';
						var interClean = '';
						var ns = '';
						var nsClean = '';
						var linkClass = 'wikEdLink';
						var article = '';
						var param = '';
						var follow = '';

// detect interlink and namespace
//                                12 inter:2     1 34  :  4 5        namespace      53    6template 6   7  89param 87
						var regExpLink = /^\s*(([\w\- ]+)\:\s*)?((\:\s*)?([^\:\|\[\]\{\}\n\t]*\s*\:))?\s*([^\|\n]+?)\s*(\|((.|\n)*))?\s*$/gi;
						regExpLink.lastIndex = 0;
						var regExpMatch;
						if ( (regExpMatch = regExpLink.exec(innerPlain)) != null) {

// get interwiki, namespace, article, paramters
							if (regExpMatch[1] != null) {
								inter = regExpMatch[1];
								interClean = inter.replace(/\s+$/g, '');
							}

							if (regExpMatch[3] != null) {
								ns = regExpMatch[3];
								nsClean = ns.replace(/^\s+|\s+$/g, '');
								nsClean = nsClean.replace(/\s*\:\s*()/g, ':');
								nsClean = nsClean.replace(/\s\s+/g, ' ');
							}

// change interwiki into more common namespace if ambiguous
							if ( (interClean != '') && (nsClean == '') ) {
								nsClean = interClean;
								ns = inter;
								inter = '';
								interClean = '';
							}

// detect cross-namespace links
							linkClass = 'wikEdLink';
							if (wikEdPageNamespace != null) {
								if (ns != wikEdPageNamespace) {
									linkClass = 'wikEdLinkCrossNs';
								}
							}

							article = regExpMatch[6];

							if (regExpMatch[8] != null) {
								param = regExpMatch[8];
							}

// highlight interwiki and namespace
							if (article != '') {

// highlight interwiki
								if (inter != '') {
									WikEdHighlightBuildTree('linkInter', 'block', tagFrom + 2, inter.length, parseObj);
								}

// highlight namespace
								if (ns != '') {
									WikEdHighlightBuildTree('linkNamespace', 'block', tagFrom + 2 + inter.length, ns.length, parseObj);
								}

// linkify
								var regExpCasing = new RegExp('(^|\\:)' + wikEdText['wikicode Category'] + '(\\:|$)', 'i');
								nsClean = nsClean.replace(regExpCasing, '$1' + wikEdText['wikicode Category'] + '$2');
								if (nsClean == ':') {
									nsClean = '';
								}
								follow = ' ' + WikEdHighlightLinkify(interClean + nsClean, article);
							}
						}
						if (nsClean.toLowerCase() == wikEdText['wikicode Category'].toLowerCase() + ':') {
							insertLeft = '<span class="wikEdCat"' + follow + '><span class="wikEdLinkTag">';
							insertRight = '</span><!--wikEdLinkTag--><span class="wikEdCatName">';
						}
						else if (tag == 'linkPiped') {
							insertLeft = '<span class="' + linkClass + '"' + follow + '><span class="wikEdLinkTag">';
							insertRight = '</span><!--wikEdLinkTag--><span class="wikEdLinkTarget">';
						}
						else {
							insertLeft = '<span class="' + linkClass + '"' + follow + '><span class="wikEdLinkTag">';
							insertRight = '</span><!--wikEdLinkTag--><span class="wikEdLinkName">';
						}
						break;
					case 'file':
						var previewCode = '';
						var regExpFile = new RegExp('^\\s*(Image|File|' + wikEdText['wikicode Image'] + '|' + wikEdText['wikicode File'] + ')\\s*:\\s*([^\\|\\n]*)', 'i');
						var regExpMatch = regExpFile.exec(innerPlain);
						if (regExpMatch == null) {
							insertLeft = '<span class="wikEdFile"><span class="wikEdFileTag">';
						}

// linkify and preview
						else {
							var file = regExpMatch[1] + ':' + regExpMatch[2];
							file = file.replace(/ /g,'_');

// add file preview box
							if (wikEdFilePreview == true) {

// get image size
								var filePreviewSize = wikEdFilePreviewSize;
								var regExpMatch;
								if ( (regExpMatch = /\|(\d+)px(\||$)/.exec(innerPlain)) != null) {
									var size = parseInt(regExpMatch[1]);
									if ( (size > 0) && (size < wikEdFilePreviewSize) ) {
										filePreviewSize = size;
									}
								}

// get image url and size from cache
								var style = '';
								var fileObj = wikEdFilePreviewCache['wikEd' + file + filePreviewSize];
								if (fileObj != null) {
									var filePreviewHeight = filePreviewSize;
									if (fileObj.height != null) {
										filePreviewHeight = fileObj.height;
									}
									var filePreviewWidth = filePreviewSize;
									if (fileObj.width != null) {
										filePreviewWidth = fileObj.width;
									}
									style = 'background-image: url(' + fileObj.url + '); height: ' + filePreviewHeight + 'px; width: ' + filePreviewWidth + 'px;';
								}

// get image url and size through an ajax request
								else {
									style = 'display: none; height: ' + filePreviewSize + 'px; width: ' + filePreviewSize + 'px;';
									wikEdFilePreviewRequest += '\n' + file + ' ' + filePreviewSize + ' [[' + file + '|' + filePreviewSize + 'px|' + filePreviewSize + 'x' + filePreviewSize + 'px]]\n';
									wikEdFilePreviewIds[wikEdFilePreviewNo] = file + filePreviewSize;
								}
								previewCode = '<span class="wikEdFilePreview" id="wikEdFilePreview' + wikEdFilePreviewNo + '" style="' + style + '" title="' + wikEdText['wikEdFilePreview'] + '(' + file + ')"></span><!--wikEdFilePreview-->';
								wikEdFilePreviewNo ++;
							}
							insertLeft += '<span class="wikEdFile" ' + WikEdHighlightLinkify('', file) + '><span class="wikEdFileTag">';
						}
						insertRight = previewCode + '</span><!--wikEdLinkTag--><span class="wikEdFileName">';
						break;
					case 'external':
						var url = '';
						var regExpMatch;
						if ( (regExpMatch = /\w\S+/.exec(innerPlain)) != null) {
							url = regExpMatch[0];
						}
						insertLeft = '<span class="wikEdURL" ' + WikEdHighlightLinkify('', '', url) + '><span class="wikEdLinkTag">';
						insertRight = '</span><!--wikEdLinkTag-->';
						break;
					case 'externalText':
						insertLeft = '<span class="wikEdURLText">';
						break;
					case 'template':

// do not hide templates if it has no parameters or if it shorter than wikEdTemplNoHideLength chars
						if ( (innerPlain.indexOf('|') == -1) || (innerPlain.length < wikEdTemplNoHideLength) ) {
							node.noHide = true;
						}
						var mod = '';
						var inter = '';
						var interClean = '';
						var ns = '';
						var nsClean = '';
						var template = '';
						var param = '';
						var follow = '';

//                                             12          mod                :      34  :      5        namespace                         53     6 template                  6    7   89 param
						var regExpTempl = new RegExp('^\\s*((' + wikEdTemplModifier + ')\\:\\s*)?((\\:\\s*)?([^\\:\\|\\[\\]\\{\\}\\s\\x00\\x01]*\\s*\\:))?\\s*([^\\:\\n\\x00\\x01\\{\\}]+?)\\s*(\\|((.|\\n)*?))?\\s*$', 'gi');

// detect parser variables and functions, might slow main regexp down
						var regExpMatch;
						var parserVar = false;
						if ( (regExpMatch = regExpTempl.exec(innerPlain)) != null) {

// get modifier, namespace, template, paramters
							if (regExpMatch[1] != null) {
								mod = regExpMatch[1];
								interClean = inter.replace(/\s+$/g, '');
								interClean = inter.replace(/:$/g, '');
							}

							if (regExpMatch[3] != null) {
								ns = regExpMatch[3];
								nsClean = ns.replace(/^\s+|\s+$/g, '');
								nsClean = nsClean.replace(/\s*\:\s*()/g, ':');
								nsClean = nsClean.replace(/\s\s+/g, ' ');
								nsClean = nsClean.replace(/(.):$/g, '$1');
							}

							template = regExpMatch[6];

							if (regExpMatch[8] != null) {
								param = regExpMatch[8];
							}

// {{VARIABLE}}
							if (parserVar == false) {
								if ( (template != '') && (ns == '') && (param == '') ) {
									var regExpParserVar = new RegExp('^(' + wikEdParserVariables + wikEdParserVariablesR + ')$', '');
									if ( (regExpMatch = regExpParserVar.exec(template)) != null) {
										parserVar = true;
										WikEdHighlightBuildTree('templateParserFunct', 'block', tagFrom + 2, innerPlain.length, parseObj);
									}
								}
							}

// {{VARIABLE:R}}
							if (parserVar == false) {
								if ( (ns != '') && (template == 'R') ) {
									var regExpParserVar = new RegExp('^(' + wikEdParserVariablesR + ')$', '');
									if ( (regExpMatch = regExpParserVar.exec(ns.substr(0, ns.length - 1))) != null) {
										parserVar = true;
										WikEdHighlightBuildTree('templateParserFunct', 'block', tagFrom + 2, innerPlain.indexOf(':') + 1, parseObj);
									}
								}
							}

// {{FUNCTION:param|R}}
							if (parserVar == false) {
								if ( (ns != '') && ( (param == '') || (param == 'R') ) ) {
									var regExpParserVar = new RegExp('^(' + wikEdParserFunctionsR + ')$', '');
									if ( (regExpMatch = regExpParserVar.exec(ns.substr(0, ns.length - 1))) != null) {
										parserVar = true;
										WikEdHighlightBuildTree('templateParserFunct', 'block', tagFrom + 2, innerPlain.indexOf(':') + 1, parseObj);
									}
								}
							}

// {{function:param|param}}
							if (parserVar == false) {
								if (ns != '') {
									var regExpParserVar = new RegExp('^(' + wikEdParserFunctions + ')$', 'i');
									if ( (regExpMatch = regExpParserVar.exec(ns.substr(0, ns.length - 1))) != null) {
										parserVar = true;
										WikEdHighlightBuildTree('templateParserFunct', 'block', tagFrom + 2, innerPlain.indexOf(':') + 1, parseObj);
									}
								}
							}

// {{#function:param|param}}
							if (parserVar == false) {
								if (ns != '') {
									var regExpParserVar = new RegExp('^(#(' + wikEdParserFunctionsHash + '))$', 'i');
									if ( (regExpMatch = regExpParserVar.exec(ns.substr(0, ns.length - 1))) != null) {
										parserVar = true;
										WikEdHighlightBuildTree('templateParserFunct', 'block', tagFrom + 2, innerPlain.indexOf(':') + 1, parseObj);
									}
								}
							}

// highlight template
							if (parserVar == false) {

// highlight modifier
								if (mod != '') {
									WikEdHighlightBuildTree('templateModifier', 'block', tagFrom + 2, mod.length, parseObj);
								}

// highlight namespace
								if (ns != '') {
									WikEdHighlightBuildTree('templateNamespace', 'block', tagFrom + 2 + mod.length, ns.length, parseObj);
								}

// add missing template namespace and linkify
								if (ns == ':') {
									ns = '';
								}
								else if (ns == '') {
									ns = wikEdText['wikicode Template'] + ':';
								}
								follow = ' ' + WikEdHighlightLinkify(ns, template);
							}
						}
						var hideStyle = '';
						var hideClass = 'wikEdTempl';
						if ( (template != '') && (parserVar == false) && (node.noHide != true) ) {
							if (wikEdRefHide == true) {

// show first template immediately following a template or reference
								var hideButtonClass = 'wikEdTemplButton';
								if ( (node.parent != null) && (node.parent > 0) ) {
									var parentNode = parseObj.tree[node.parent];
									if (parentNode != null) {
										if ( (parentNode.tag == 'template') || (parentNode.tag == 'ref') )  {
											if (/^\s*$/.test(obj.html.substring(parentNode.start + parentNode.tagLength, tagFrom)) == true) {
												hideButtonClass = hideButtonClass.replace(/Button(Show)?/, 'ButtonShow');
												hideClass = 'wikEdTemplShow';
												hideButtonStyle = ' style="display: block"';
												hideStyle = ' style="display: block"';
											}
										}
									}
								}
								insertLeft = '<span class="wikEdTemplContainer"><button class="' + hideButtonClass + wikEdTemplateArray.length + '" title="' + wikEdText['wikEdTemplButtonShowTooltip'] + '"></button><!--wikEdTemplButton--></span><!--wikEdTemplContainer-->';
								wikEdTemplateArray.push( {'text': template, 'added': false} );
							}
						}
						insertLeft += '<span class="' + hideClass + '"' + hideStyle + '><span class="wikEdTemplTag">';
						insertRight = '</span><!--wikEdTemplTag--><span class="wikEdTemplName"' + follow + '>';
						break;
					case 'parameter':
					case 'parameterPiped':
						insertLeft = '<span class="wikEdParam"><span class="wikEdTemplTag">';
						pushRight = '</span><!--wikEdTemplTag--><span class="wikEdParamName">';
						break;
					case 'html':
					case 'tr':
					case 'td':
					case 'th':
					case 'col':
					case 'thead':
					case 'tfoot':
					case 'tbody':
					case 'colgroup':
					case 'caption':
					case 'big':
					case 'blockquote':
					case 'center':
					case 'code':
					case 'del':
					case 'div':
					case 'font':
					case 'ins':
					case 'small':
					case 'span':
					case 'strike':
					case 'tt':
					case 'rb':
					case 'rp':
					case 'rt':
					case 'ruby':
					case 'nowiki':
					case 'math':
					case 'noinclude':
					case 'includeonly':
					case 'gallery':
					case 'categorytree':
					case 'charinsert':
					case 'hiero':
					case 'imagemap':
					case 'inputbox':
					case 'poem':
					case 'source':
					case 'syntaxhighlight':
					case 'timeline':
						insertLeft = '<span class="wikEdHtml"><span class="wikEdHtmlTag">';
						pushRight = '</span><!--wikEdHtmlTag-->';
						break;
					case 'u':
						insertLeft = '<span class="wikEdHtmlTagButtons">';
						pushRight = '</span><!--wikEdHtmlTag--><span class="wikEdIns">';
						break;
					case 's':
						insertLeft = '<span class="wikEdHtmlTagButtons">';
						pushRight = '</span><!--wikEdHtmlTag--><span class="wikEdDel">';
						break;
					case 'sub':
						insertLeft = '<span class="wikEdSubscript"><span class="wikEdHtmlTagButtons">';
						pushRight = '</span><!--wikEdHtmlTag-->';
						break;
					case 'sup':
						insertLeft = '<span class="wikEdSuperscript"><span class="wikEdHtmlTagButtons">';
						pushRight = '</span><!--wikEdHtmlTag-->';
						break;
					case 'p':
						insertLeft = '<span class="wikEdHtmlUnknown" title="' + wikEdText['wikEdErrorHtmlUnknown'] + '">';
						pushRight = '</span><!--wikEdHtmlUnknown-->';
						break;
					case 'spare':
						break;
					case 'ref':

// ref no hide
						if (node.noHide == true) {
							insertLeft = '<span class="wikEdRef">';
						}

// ref hide
						else {
							var refName = '';
							var regExpMatch;
							if ( (regExpMatch = /(\bname\s*=\s*(\'|\"))([^\x01]+?)\2/i.exec(tagMatch)) != null) {
								refName = regExpMatch[3];
								WikEdHighlightBuildTree('refName', 'block', tagFrom + regExpMatch.index + regExpMatch[1].length, regExpMatch[3].length, parseObj);
							}
							else if ( (regExpMatch = /(\bname\s*=\s*)(\w+)/i.exec(tagMatch)) != null) {
								refName = regExpMatch[2];
								WikEdHighlightBuildTree('refName', 'block', tagFrom + regExpMatch.index + regExpMatch[1].length, regExpMatch[2].length, parseObj);
							}
							if (wikEdRefHide == true) {
								if (refName != '') {
									insertLeft = '<span class="wikEdRefContainer"><button class="wikEdRefButton' + wikEdReferenceArray.length + '" title="' + wikEdText['wikEdRefButtonTooltip'] + '"></button><!--wikEdRefButton--></span><!--wikEdRefContainer-->';
									wikEdReferenceArray.push( {'text': refName, 'added': false} );
								}
								else {
									insertLeft = '<span class="wikEdRefContainer"><button class="wikEdRefButton" title="' + wikEdText['wikEdRefButtonTooltip'] + '"></button><!--wikEdRefButton--></span><!--wikEdRefContainer-->';
								}
							}
							insertLeft += '<span class="wikEdRef"><span class="wikEdHtmlTag">';
							pushRight = '</span><!--wikEdHtmlTag-->';
						}
						break;
					case 'references':
						insertLeft = '<span class="wikEdRefList"><span class="wikEdReferencesTag">';
						pushRight = '</span><!--wikEdReferencesTag-->';
						break;
					case 'heading':
						var heading = innerPlain.replace(/^\s+|\s+$/g, '');
						if ( (heading == wikEdText['See also']) || (heading == wikEdText['References']) || (heading == wikEdText['External links']) ) {
							insertLeft = '<span class="wikEdHeadingWP">';
						}
						else {
							insertLeft = '<span class="wikEdHeading">';
						}
						break;
					case 'table':/// \n| = </td><td>, \n|- = </t></tr><tr> not blocks but td, th, tr
						if (parseObj.tableMode == true) {

// wikitable
							var regExpTable = /\{\| *((\w+ *= *(\'|\")[^\n\'\"]*\3 *)*)(\n|$)/gi;
							regExpTable.lastIndex = tagFrom;
							var regExpMatch = regExpTable.exec(obj.html);
							if (regExpMatch == null) {

// html table
								regExpTable = /<table\b\s*((\w+\s*=\s*(\'|\")[^>\'\"]*\3\s*)*)\s*>/gi;
								regExpTable.lastIndex = tagFrom;
								regExpMatch = regExpTable.exec(obj.html)
							}

							if (regExpMatch != null) {
								if (regExpMatch.index == tagFrom) {
									var params = regExpMatch[1];
									if (params != '') {
										params += ' ';
									}
									insertLeft = '<table ' + params + ' border="1" class="wikEdTableEdit"><!--wikEdTableMode';
									pushRight2 = '-->';
									pushRightPos2 = regExpMatch.index + regExpMatch[0].length;
								}
								else {
									parseObj.tableMode = false;
								}
							}
							else {
								parseObj.tableMode = false;
							}
						}
						if (parseObj.tableMode == false) {
							insertLeft = '<span class="wikEdTable"><span class="wikEdTableTag">';
							insertRight = '</span><!--wikEdTableTag-->';
						}
						break;
				}
				break;
			case 'close':
				switch (tag) {
					case 'italic':
						insertLeft = '<span class="wikEdWiki">';
						pushRight = '</span><!--wikEdWiki--></span><!--wikEdItalic-->';
						break;
					case 'bold':
						insertLeft = '<span class="wikEdWiki">';
						pushRight = '</span><!--wikEdWiki--></span><!--wikEdBold-->';
						break;
					case 'link':
						insertLeft = '</span><!--wikEdLinkName/CatName--><span class="wikEdLinkTag">';
						insertRight = '</span><!--wikEdLinkTag--></span><!--wikEdLink/Cat-->';
						break;
					case 'linkPiped':
						insertLeft = '</span><!--wikEdLinkText--><span class="wikEdLinkTag">';
						insertRight = '</span><!--wikEdLinkTag--></span><!--wikEdLink/Cat/LinkCross-->';
						break;
					case 'file':
						insertLeft = '</span><!--wikEdFileName/Param/Caption--><span class="wikEdFileTag">';
						insertRight = '</span><!--wikEdFileTag--></span><!--wikEdFile-->';
						break;
					case 'externalText':
						insertRight = '</span><!--wikEdURLText-->';
						break;
					case 'external':
						insertLeft = '<span class="wikEdLinkTag">';
						insertRight = '</span><!--wikEdLinkTag--></span><!--wikEdURL-->';
						break;
					case 'template':
						insertLeft = '</span><!--wikEdTemplName/Param--><span class="wikEdTemplTag">';
						insertRight = '</span><!--wikEdTemplTag--></span><!--wikEdTempl-->';
						break;
					case 'parameter':
					case 'parameterPiped':
						insertLeft = '</span><!--wikEdParamName/Default--><span class="wikEdTemplTag">';
						insertRight = '</span><!--wikEdTemplTag--></span><!--wikEdParam-->';
						break;
					case 'html':
					case 'tr':
					case 'td':
					case 'th':
					case 'col':
					case 'thead':
					case 'tfoot':
					case 'tbody':
					case 'colgroup':
					case 'caption':
					case 'big':
					case 'blockquote':
					case 'center':
					case 'code':
					case 'del':
					case 'div':
					case 'font':
					case 'ins':
					case 'small':
					case 'span':
					case 'strike':
					case 'tt':
					case 'rb':
					case 'rp':
					case 'rt':
					case 'ruby':
					case 'nowiki':
					case 'math':
					case 'noinclude':
					case 'includeonly':
					case 'gallery':
					case 'categorytree':
					case 'charinsert':
					case 'hiero':
					case 'imagemap':
					case 'inputbox':
					case 'poem':
					case 'source':
					case 'syntaxhighlight':
					case 'timeline':
						insertLeft = '<span class="wikEdHtmlTag">';
						pushRight = '</span><!--wikEdHtmlTag--></span><!--wikEdHtml-->';
						break;
					case 'u':
						insertLeft = '</span><!--wikEdIns--><span class="wikEdHtmlTagButtons">';
						pushRight = '</span><!--wikEdHtmlTagButtons-->';
						break;
					case 's':
						insertLeft = '</span><!--wikEdDel--><span class="wikEdHtmlTagButtons">';
						pushRight = '</span><!--wikEdHtmlTagButtons-->';
						break;
					case 'sub':
						insertLeft = '<span class="wikEdHtmlTagButtons">';
						pushRight = '</span><!--wikEdHtmlTagButtons--></span><!--wikEdSubscript-->';
						break;
					case 'sup':
						insertLeft = '<span class="wikEdHtmlTagButtons">';
						pushRight = '</span><!--wikEdHtmlTagButtons--></span><!--wikEdSuperscript-->';
						break;
					case 'p':
						insertLeft = '<span class="wikEdHtmlUnknown" title="' + wikEdText['wikEdErrorHtmlUnknown'] + '">';
						pushRight = '</span><!--wikEdHtmlUnknown-->';
						break;
					case 'ref':
						insertLeft = '<span class="wikEdHtmlTag">';
						pushRight = '</span><!--wikEdHtmlTag--></span><!--wikEdRef-->';
						break;
					case 'references':
						insertLeft = '<span class="wikEdReferencesTag">';
						pushRight = '</span><!--wikEdReferencesTag--></span><!--wikEdRefList-->';
						break;
					case 'heading':
						insertRight = '</span><!--wikEdHeading/WP-->';
						break;
					case 'table':
						if (parseObj.tableMode == true) {
							insertLeft = '</table><!--wikEdTableMode';
							pushRight = '-->';
						}
						else {
							insertLeft = '<span class="wikEdTableTag">';
							insertRight = '</span><!--wikEdTableTag--></span><!--wikEdTable-->';
						}
						break;
				}
				break;
			case 'block':
				switch (tag) {

// pushRight instead of insertRight if enclosed text can contain other highlighting, e.g. single character highlighting
					case 'linkNamespace':
						insertLeft = '<span class="wikEdLinkNs">';
						pushRight = '</span><!--wikEdLinkNs-->';
						break;
					case 'linkInter':
						insertLeft = '<span class="wikEdLinkInter">';
						pushRight = '</span><!--wikEdLinkInter-->';
						break;
					case 'inlineURL':
						var url = '';
						var regExpMatch;
						if ( (regExpMatch = /\w\S+/.exec(tagMatch)) != null) {
							url = regExpMatch[0];
						}
						insertLeft = '<span class="wikEdURLName" ' + WikEdHighlightLinkify('', '', url) + '>';
						pushRight = '</span><!--wikEdURLName-->';
						break;
					case 'externalURL':
						insertLeft = '<span class="wikEdURLTarget">';
						pushRight = '</span><!--wikEdURLTarget-->';
						break;
					case 'templateModifier':
						insertLeft = '<span class="wikEdTemplMod">';
						pushRight = '</span><!--wikEdTemplMod-->';
						break;
					case 'templateNamespace':
						insertLeft = '<span class="wikEdTemplNs">';
						pushRight = '</span><!--wikEdTemplNs-->';
						break;
					case 'templateParserFunct':
						insertLeft = '<span class="wikEdParserFunct">';
						pushRight = '</span><!--wikEdParserFunct-->';
						break;
					case 'PMID':
						var idNumber = '';
						var regExpMatch;
						if ( (regExpMatch = /\d+/.exec(tagMatch)) != null) {
							idNumber = regExpMatch[0];
						}
						insertLeft = '<span class="wikEdPMID" ' + WikEdHighlightLinkify('', '', 'http://www.ncbi.nlm.nih.gov/pubmed/' + idNumber) + '>';
						insertRight = '</span><!--wikEdPMID-->';
						break;
					case 'ISBN':
						var idNumber = '';
						var regExpMatch;
						if ( (regExpMatch = /\d[\s\d\-]+x?/.exec(tagMatch)) != null) {
							idNumber = regExpMatch[0].replace(/\D/g, '');
						}
						insertLeft = '<span class="wikEdISBN" ' + WikEdHighlightLinkify('', 'Special:BookSources/' + idNumber) + '>';
						pushRight = '</span><!--wikEdISBN-->';
						break;
					case 'RFC':
						var idNumber = '';
						var regExpMatch;
						if ( (regExpMatch = /\d[\s\d\-]+x?/.exec(tagMatch)) != null) {
							idNumber = regExpMatch[0].replace(/\D/g, '');
						}
						insertLeft = '<span class="wikEdISBN" ' + WikEdHighlightLinkify('', '', 'http://tools.ietf.org/html/rfc' + idNumber) + '>';
						pushRight = '</span><!--wikEdISBN-->';
						break;
					case 'magic':
						insertLeft = '<span class="wikEdMagic">';
						insertRight = '</span><!--wikEdMagic-->';
						break;
					case 'signature':
						var title = wikEdText['wikEdSignature' + tagLength];
						insertLeft = '<span class="wikEdSignature" title="' + title + '">';
						insertRight = '</span><!--wikEdSignature-->';
						break;
					case 'hr':
						pushLeft = '<span class="wikEdHr">';
						pushRight = '</span><!--wikEdHr-->';
						break;
					case 'linkParam':
						insertLeft = '</span><!--wikEdLinkTarget/CatName--><span class="wikEdLinkTag">';
						insertRight = '</span><!--wikEdLinkTag--><span class="wikEdLinkText">';
						break;
					case 'fileParam':

// make text parameters a caption
						var params = '';
						if (pairedTagPos != null) {
							params = obj.html.substring(tagFrom + 1, parseObj.tree[node.parent].pairedTagPos - 1);
						}
						if (/^\s*(thumb|thumbnail|frame|right|left|center|none|\d+px|\d+x\d+px|link\=.*?|upright|border)\s*(\||$)/.test(params) == true) {
							insertLeft = '</span><!--wikEdFileName/Param--><span class="wikEdFileTag">';
							insertRight = '</span><!--wikEdFileTag--><span class="wikEdFileParam">';
						}
						else {
							insertLeft = '</span><!--wikEdFileName/Param--><span class="wikEdFileTag">';
							insertRight = '</span><!--wikEdFileTag--><span class="wikEdFileCaption">';
						}
						break;
					case 'redirect':
						insertLeft = '<span class="wikEdRedir">';
						pushRight = '</span><!--wikEdRedir-->';
						break;
					case 'templateParam':
						insertLeft = '</span><!--wikEdTemplateName/Param--><span class="wikEdTemplTag">';
						pushRight = '</span><!--wikEdTemplTag--><span class="wikEdTemplParam">';
						break;
					case 'parameterDefault':
						insertLeft = '</span><!--wikEdParamName--><span class="wikEdTemplTag">';
						insertRight = '</span><!--wikEdTemplTag--><span class="wikEdParamDefault">';
						break;
					case 'br'://inlineTag class
					case 'html':
					case 'htmlEmpty':
						insertLeft = '<span class="wikEdHtml"><span class="wikEdHtmlTag">';
						pushRight = '</span><!--wikEdHtmlTag--></span><!--wikEdHtml-->';
						break;
					case 'htmlUnknown':
						insertLeft = '<span class="wikEdHtmlUnknown" title="' + wikEdText['wikEdErrorHtmlUnknown'] + '">';
						pushRight = '</span><!--wikEdHtmlUnknown-->';
						break;
					case 'ref':
						var refName = '';
						var regExpMatch;
						if ( (regExpMatch = /(\bname\s*=\s*(\'|\"))([^\x01]+?)\2/i.exec(tagMatch)) != null) {
							refName = regExpMatch[3];
							WikEdHighlightBuildTree('refName', 'block', tagFrom + regExpMatch.index + regExpMatch[1].length, regExpMatch[3].length, parseObj);
						}
						else if ( (regExpMatch = /(\bname\s*=\s*)(\w+)/i.exec(tagMatch)) != null) {
							refName = regExpMatch[2];
							WikEdHighlightBuildTree('refName', 'block', tagFrom + regExpMatch.index + regExpMatch[1].length, regExpMatch[2].length, parseObj);
						}
						if (wikEdRefHide == true) {
							if (refName != '') {
								insertLeft = '<span class="wikEdRefContainer"><button class="wikEdRefButton' + wikEdReferenceArray.length + '" title="' + wikEdText['wikEdRefButtonTooltip'] + '"></button><!--wikEdRefButton--></span><!--wikEdRefContainer-->';
								wikEdReferenceArray.push( {'text': refName + ' ↑', 'added': false} );
							}
							else {
								insertLeft = '<span class="wikEdRefContainer"><button class="wikEdRefButton" title="' + wikEdText['wikEdRefButtonTooltip'] + '"></button><!--wikEdRefButton--></span><!--wikEdRefContainer-->';
							}
						}
						insertLeft += '<span class="wikEdRef">';
						pushRight = '</span><!--wikEdRef-->';
						break;
					case 'references':
						insertLeft = '<span class="wikEdReferences"><span class="wikEdReferencesTag">';
						pushRight = '</span><!--wikEdReferencesTag--></span><!--wikEdReferences-->';
						break;
					case 'pre':
						insertLeft = '<span class="wikEdPre">';
						pushRight = '</span><!--wikEdPre-->';
						break;
					case 'math':
						insertLeft = '<span class="wikEdMath">';
						pushRight = '</span><!--wikEdMath-->';
						break;
					case 'nowiki':
						insertLeft = '<span class="wikEdNowiki">';
						pushRight = '</span><!--wikEdNowiki-->';
						break;
					case 'listTag':
						insertLeft = '<span class="wikEdListTag">';
						insertRight = '</span><!--wikEdListTag-->';
						break;
					case 'preformTag':
						insertLeft = '<span class="wikEdSpaceTag">';
						insertRight = '</span><!--wikEdSpaceTag-->';
						break;
					case 'refName':
						insertLeft = '<span class="wikEdRefName">';
						pushRight = '</span><!--wikEdRefName-->';
						break;
					case 'list':
						pushLeft = '<span class="wikEdList">';
						pushRight = '</span><!--wikEdList-->';
						break;
					case 'preform':
						pushLeft = '<span class="wikEdSpace">';
						pushRight = '</span><!--wikEdSpace-->';
						break;
					case 'caption':
					case 'row':
					case 'header':
					case 'headerParam':
					case 'headerSep':
					case 'cell':
/*
						if (parseObj.tableMode == true) {
							var regExpTable = /\| *((\w+ *= *(\'|\")[^\n\'\"]*\3 *)*)\|\|/gi;
							regExpTable.lastIndex = tagFrom;
							var regExpMatch;
							if ( (regExpMatch = regExpTable.exec(obj.html) ) != null) {
								if (regExpMatch.index == tagFrom) {
									var params = regExpMatch[1];
									if (params != '') {
										params += ' ';
									}
									insertLeft = '<table ' + params + ' border="1" class="wikEdTableEdit"><!--wikEdTableMode';
									pushRight2 = '-->';
									pushRightPos2 = regExpMatch.index + regExpMatch[0].length;
								}
								else {
									parseObj.tableMode = false;
								}
							}
							else {
								parseObj.tableMode = false;
							}
						}
						if (parseObj.tableMode == false) {
							insertLeft = '<span class="wikEdTable"><span class="wikEdTableTag">';
							insertRight = '</span><!--wikEdTableTag-->';
						}
*/
						break;
					case 'cellParam':
					case 'cellSep':
						insertLeft = '<span class="wikEdTableTag">';
						insertRight = '</span><!--wikEdTableTag-->';
						break;
					case 'colorLight':
						insertLeft = '<span style="background: ' + tagMatch + '" class="wikEdColorsLight">';
						insertRight = '</span><!--wikEdColorsLight-->';
						break;
					case 'colorDark':
						insertLeft = '<span style="background: ' + tagMatch + '" class="wikEdColorsDark">';
						insertRight = '</span><!--wikEdColorsDark-->';
						break;
					case 'colorHex3':
						var regExpMatch = /([0-9a-f])([0-9a-f])([0-9a-f])/i.exec(tagMatch);
						if ( (regExpMatch[1] > 255) || (regExpMatch[2] > 255) || (regExpMatch[3] > 255) ) {
							break;
						}
						var luminance = parseInt(regExpMatch[1], 16) * 16 * 0.299 + parseInt(regExpMatch[2], 16) * 16 * 0.587 + parseInt(regExpMatch[3], 16) * 16  * 0.114;
						if (luminance > 128) {
							insertLeft = '<span style="background: ' + tagMatch + '" class="wikEdColorsLight">';
							insertRight = '</span><!--wikEdColorsLight-->';
						}
						else {
							insertLeft = '<span style="background: ' + tagMatch + '" class="wikEdColorsDark">';
							insertRight = '</span><!--wikEdColorsDark-->';
						}
						break;
					case 'colorHex6':
						var regExpMatch = /([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})/i.exec(tagMatch);
						if ( (regExpMatch[1] > 255) || (regExpMatch[2] > 255) || (regExpMatch[3] > 255) ) {
							break;
						}
						var luminance = parseInt(regExpMatch[1], 16) * 0.299 + parseInt(regExpMatch[2], 16) * 0.587 + parseInt(regExpMatch[3], 16) * 0.114;
						if (luminance > 128) {
							insertLeft = '<span style="background: ' + tagMatch + '" class="wikEdColorsLight">';
							insertRight = '</span><!--wikEdColorsLight-->';
						}
						else {
							insertLeft = '<span style="background: ' + tagMatch + '" class="wikEdColorsDark">';
							insertRight = '</span><!--wikEdColorsDark-->';
						}
						break;
					case 'colorDec':
						var regExpMatch = /(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/i.exec(tagMatch);
						if ( (regExpMatch[1] > 255) || (regExpMatch[2] > 255) || (regExpMatch[3] > 255) ) {
							break;
						}
						var luminance = regExpMatch[1] * 0.299 + regExpMatch[2] * 0.587 + regExpMatch[3] * 0.114;
						if (luminance > 128) {
							insertLeft = '<span style="background: ' + tagMatch + '" class="wikEdColorsLight">';
							insertRight = '</span><!--wikEdColorsLight-->';
						}
						else {
							insertLeft = '<span style="background: ' + tagMatch + '" class="wikEdColorsDark">';
							insertRight = '</span><!--wikEdColorsDark-->';
						}
						break;
					case 'ctrl':
						insertLeft = '<span class="wikEdCtrl" title="' + wikEdControlCharHighlighting[tagMatch.charCodeAt(0).toString()] + '">';
						insertRight = '</span><!--wikEdCtrl-->';
						break;
					case 'char':
						var titleClass = wikEdCharHighlighting[tagMatch.charCodeAt(0).toString()];
						insertLeft = '<span class="' + titleClass + '" title="' + titleClass + '">';
						insertRight = '</span><!--' + titleClass + '-->';
						break;
					case 'charEntity':
						var regExpMatch = /&(\w+);/i.exec(tagMatch);
						var character = wikEdCharEntitiesByName[ regExpMatch[1] ];
						if (character != null) {
							if (wikEdRefHide == true) {
								insertLeft = '<span class="wikEdCharEntityContainer"><button class="wikEdCharEntityButton' + wikEdCharEntityArray.length + '" title="' + wikEdText['wikEdCharEntityButtonTooltip'] + '"></button><!--wikEdCharEntityButton--></span><!--wikEdCharEntityContainer-->';
								wikEdCharEntityArray.push( {'text': character, 'added': false} );
							}
							insertLeft += '<span class="wikEdCharEntity">';
							insertRight = '</span><!--wikEdCharEntity-->';
						}
						break;
				}
				break;
			case 'comment':
				insertLeft = '<span class="wikEdComment">' + node.left + '</span><!--wikEdComment-->';
				break;
			case 'error':
				insertLeft = '<span class="wikEdError" title="' + node.left + '">';
				if (wikEdHighlightError == true) {
					insertLeft += '<span class="wikEdHighlightError">' + node.left + '</span><!--wikEdHighlightError-->';
				}
				pushRight = '</span><!--wikEdError-->';
				break;
			case 'note': // for debugging
				insertLeft = '<span class="wikEdParsingNote">' + node.tagLength + '</span><!--wikEdParsingNote-->';
				break;
			case 'root':
				break;
		}

// add left html into existing entry
		if (insertLeft != '') {
			node.left = insertLeft;
			node.index = i;
		}

// add left html as new array element to allow for overlapping highlighting as in hr
		else if (pushLeft != '') {
			parseObj.tree.push( { 'start': tagFrom, 'tagLength': 0, 'left': pushLeft, 'index': i - 0.5 } );
		}

// add right html into existing entry
		if (insertRight != '') {
			node.right = insertRight;
			node.index = i;
		}

// add right html as new array element to allow for overlapping highlighting as in html-like tags and urls
		else if (pushRight != '') {
			parseObj.tree.push( { 'start': tagTo, 'tagLength': 0, 'right': pushRight, 'index': i + 0.5 } );
		}
		if (pushRight2 != '') {
			parseObj.tree.push( { 'start': pushRightPos2, 'tagLength': 0, 'right': pushRight2, 'index': i + 0.5 } );
		}


		from = tagTo;
		i ++;
	}

	return;
};


//
// WikEdHighlightMergeHtml: merge parse tree highlighting html code with article text
//

window.WikEdHighlightMergeHtml = function(parseObj, obj) {

	if (parseObj.tree.length <= 1) {
		return;
	}

// sort parse array by position, length, and index
	parseObj.tree.sort(
		function(a, b) {

// by start position
			if (a.start != b.start) {
				return(a.start - b.start);
			}

// by length
			if (a.tagLength != b.tagLength) {
				return(a.tagLength - b.tagLength);
			}

// by index
			return(a.index - b.index);
		}
	);

// add comments and highlighting
	var from = 0;
	var htmlArray = [];

// cycle through parse array and assemble html array
	for (var i = 0; i < parseObj.tree.length; i ++) {
		var node = parseObj.tree[i];
		var tagFrom = node.start;
		var tagLength = node.tagLength;
		var htmlLeft = node.left;
		var htmlRight = node.right;
		var tagTo = tagFrom + tagLength;

// drop overlapping highlighting //// |- in tables?!
		if (tagFrom < from) {
			continue;
		}

// ignore root
		if (tagFrom == null) {
			continue;
		}

// push leading plain text
		htmlArray.push(obj.html.substring(from, tagFrom));

// push left html
		if (htmlLeft != null) {
			htmlArray.push(htmlLeft);
		}

// push right html
		if (htmlRight != null) {
			htmlArray.push(obj.html.substring(tagFrom, tagTo));
			htmlArray.push(htmlRight);
			from = tagTo;
		}
		else {
			from = tagFrom;
		}
	}
	htmlArray.push(obj.html.substring(from));

// join html array
	obj.html = htmlArray.join('');

// display highlighted html:
// WED(obj.html.replace(/\x00/g, '&lt;').replace(/\x01/g, '&gt;'));

	return;
};


//
// WikEdHighlightLinkify: prepare the span tag parameters for ctrl-click opening of highlighted links
//

window.WikEdHighlightLinkify = function(linkPrefix, linkTitle, linkUrl) {

	if (wikEdLinkify != true) {
		return('');
	}
	var linkName = '';
	var subpage = false;

// generate url from interlanguage or namespace prefix and title
	if (linkUrl == null) {

// test for illegal characters
		if ( (/[\{\|\}\[\]<>#]/.test(linkPrefix) == true) || (/[<>]/.test(linkUrl) == true) ) {
			return('');
		}

// remove highlighting code
		linkPrefix = linkPrefix.replace(/<.*?>/g, '');
		linkTitle = linkTitle.replace(/<.*?>/g, '');

// remove control chars
		var regExp = new RegExp('[' + wikEdControlCharHighlightingStr + '\t\n\r]', 'g');
		linkPrefix = linkPrefix.replace(regExp, '');
		linkTitle = linkTitle.replace(regExp, '');

// fix strange white spaces, leading colons
		linkPrefix = linkPrefix.replace(/\s/g, ' ');
		linkPrefix = linkPrefix.replace(/^ +| +$/, '');
		linkPrefix = linkPrefix.replace(/^:+ *()/, '');
		linkPrefix = linkPrefix.replace(/ +/, '_');

		linkTitle = linkTitle.replace(/\s/g, ' ');
		linkTitle = linkTitle.replace(/^ +| +$/, '');
		linkTitle = linkTitle.replace(/^:+ *()/, '');
		linkTitle = linkTitle.replace(/ +/g, '_');

// [[/subpage]] refers to a subpage of the current page
		if ( (linkPrefix == '') && (linkTitle.indexOf('/') == 0) ) {
			subpage = true;
		}

// Wiktionary differentiates between lower and uppercased titles, interwiki should not be uppercased
		if (subpage == true) {
			linkUrl = linkPrefix + wikEdPageName + linkTitle;
		}
		else {
			linkUrl = linkPrefix + linkTitle;
		}

		linkUrl = encodeURI(linkUrl);
		linkUrl = linkUrl.replace(/%25(\d\d)/g, '%$1');
		linkUrl = linkUrl.replace(/\'/g, '%27');
		linkUrl = linkUrl.replace(/#/g, '%23');
		if (wikEdWikiGlobals['wgArticlePath'] == null) {
			linkUrl = '';
		}
		else {
			linkUrl = wikEdWikiGlobals['wgArticlePath'].replace(/\$1/, linkUrl);
			linkName = linkPrefix + linkTitle;
		}
	}

// url provided
	else {

// test for templates
		if (/\{|\}/.test(linkUrl) == true) {
			return('');
		}
		linkName = linkUrl;
		linkUrl = encodeURI(linkUrl);
		linkUrl = linkUrl.replace(/%25(\d\d)/g, '%$1');
		linkUrl = linkUrl.replace(/\'/g, '%27');
	}
	var linkPopup = linkName;
	if (subpage == true) {
		linkPopup = wikEdPageName + linkPopup;
	}
	linkPopup = linkPopup.replace(/</g, '&lt;');
	linkPopup = linkPopup.replace(/>/g, '&gt;');
	linkPopup = linkPopup.replace(/\"/g, '&quot;');
	var linkParam = '';
	if (linkUrl != '') {
		var titleClick;
		if (wikEdPlatform == 'mac') {
			titleClick = wikEdText['followLinkMac'];
		}
		else {
			titleClick = wikEdText['followLink'];
		}
		linkParam += 'id="wikEdLinkify' + wikEdLinkifyArray.length + '" title="' + linkPopup + ' ' + titleClick + '"';
		wikEdLinkifyArray.push(linkUrl);
	}
	return(linkParam);
};


//
// WikEdUpdateTextarea: copy frame content to textarea
//

window.WikEdUpdateTextarea = function() {

// remove dynamically inserted nodes by other scripts
	WikEdCleanNodes(wikEdFrameDocument);

// get frame content
	var obj = {};
	obj.html = wikEdFrameBody.innerHTML;

// remove trailing blanks and newlines at end of text
	obj.html = obj.html.replace(/((<br\b[^>]*>)|\s)+$/g, '');

// remove leading spaces in lines
	obj.html = obj.html.replace(/(<br\b[^>]*>)\n* *()/g, '$1');

// textify so that no html formatting is submitted
	WikEdTextify(obj);
	obj.plain = obj.plain.replace(/&nbsp;|&#160;|\xa0/g, ' ');
	obj.plain = obj.plain.replace(/&lt;/g, '<');
	obj.plain = obj.plain.replace(/&gt;/g, '>');
	obj.plain = obj.plain.replace(/&amp;/g, '&');

// copy to textarea
	wikEdTextarea.value = obj.plain;

// remember frame scroll position
	wikEdFrameScrollTop = wikEdFrameBody.scrollTop;

	return;
};


//
// WikEdUpdateFrame: copy textarea content to frame
//

window.WikEdUpdateFrame = function() {

// get textarea content
	var obj = {};
	obj.html = wikEdTextarea.value;
	obj.html = obj.html.replace(/&/g, '&amp;');
	obj.html = obj.html.replace(/>/g, '&gt;');
	obj.html = obj.html.replace(/</g, '&lt;');

// Opera 0.9.51
	obj.html = obj.html.replace(/\r\n|\n\r|\r/g, '\n');

// highlight the syntax
	if (wikEdHighlightSyntax == true) {
		obj.whole = true;
		WikEdHighlightSyntax(obj);
	}

// at least display tabs
	else {
		obj.html = obj.html.replace(/(\t)/g, '<span class="wikEdTabPlain">$1</span><!--wikEdTabPlain-->');
	}

// multiple blanks to blank-&nbsp;
	obj.html = obj.html.replace(/(^|\n) /g, '$1&nbsp;');
	obj.html = obj.html.replace(/ (\n|$)/g, '&nbsp;$1');
	obj.html = obj.html.replace(/ {2}/g, '&nbsp; ');
	obj.html = obj.html.replace(/ {2}/g, '&nbsp; ');

// newlines to <br>
	obj.html = obj.html.replace(/\n/g, '<br>');

// select the whole text after replacing the whole text and scroll to same height
	if (wikEdMSIE == true) {

	}
	else {
		obj.sel = WikEdGetSelection();
		WikEdRemoveAllRanges(obj.sel);
	}

// insert content into empty frame
	if ( (wikEdFrameBody.firstChild == null) || (/^<br[^>]*>\s*$/.test(wikEdFrameBody.innerHTML) == true) ) {
		wikEdFrameBody.innerHTML = obj.html;
	}

// insert content into frame, preserve history
	else {
		var range = wikEdFrameDocument.createRange();
		range.setStartBefore(wikEdFrameBody.firstChild);
		range.setEndAfter(wikEdFrameBody.lastChild);
		obj.sel.addRange(range);

// replace the frame content with the new text, do not scroll
		var scrollOffset = window.pageYOffset || document.body.scrollTop;
		if (obj.html != '') {
			WikEdFrameExecCommand('inserthtml', obj.html);
		}
		else {
			WikEdFrameExecCommand('delete');
		}
		window.scroll(0, scrollOffset);
		WikEdRemoveAllRanges(obj.sel);

// scroll to previous position
		if (wikEdFrameScrollTop != null) {
			wikEdFrameBody.scrollTop = wikEdFrameScrollTop;
		}
	}
	wikEdFrameScrollTop = null;

// add event handlers and labels
	if (wikEdHighlightSyntax == true) {

// name ref and template buttons
		WikEdHighlightNamedHideButtons();

// add event handlers to unhide refs and templates
		WikEdHideAddHandlers();

// add event handlers to make highlighted frame links ctrl-clickable
		WikEdLinkifyAddHandlers();
	}

	return;
};


//
// WikEdKeyHandler: event handler for keydown events in main document and frame
//   detects emulated accesskey and traps enter in find/replace input elements
//

window.WikEdKeyHandler = function(event) {

// event compatibility fixes
	event = WikEdEvent(event, this);
	if (event == null) {
		return;
	}

	if (wikEdUseWikEd == true) {

// trap enter in find/replace input elements
		if ( (event.type == 'keydown') && (event.keyCode == 13) ) {
			if (event.target.id == 'wikEdFindText') {
				event.preventDefault();
				event.stopPropagation();
				if (event.shiftKey == true) {
					WikEdEditButton(null, 'wikEdFindPrev');
				}
				else if (event.ctrlKey == true) {
					WikEdEditButton(null, 'wikEdFindAll');
				}
				else {
					WikEdEditButton(null, 'wikEdFindNext');
				}
			}
			else if (event.target.id == 'wikEdReplaceText') {
				event.preventDefault();
				event.stopPropagation();
				if (event.shiftKey == true) {
					WikEdEditButton(null, 'wikEdReplacePrev');
				}
				else if (event.ctrlKey == true) {
					WikEdEditButton(null, 'wikEdReplaceAll');
				}
				else {
					WikEdEditButton(null, 'wikEdReplaceNext');
				}
			}
		}

// detect emulated accesskeys
		else if ( (event.shiftKey == true) && (event.ctrlKey == false) && (event.altKey == true) && (event.metaKey == false) ) {

// get wikEd button id from keycode
			var buttonId = wikEdButtonKeyCode[event.keyCode];
			if (buttonId != null) {
				event.preventDefault();
				event.stopPropagation();

// execute the button click handler code
				var obj = document.getElementById(buttonId);
				objId = obj.id;
				eval(wikEdEditButtonHandler[buttonId]);
			}
		}
	}
	return;
};


//
// WikEdFrameExecCommand: wrapper for execCommand method
//

window.WikEdFrameExecCommand = function(command, option) {

	if (typeof(wikEdFrameDocument.execCommand) == 'function') {
		wikEdFrameDocument.execCommand(command, false, option);
	}

// MSIE workaround, breaks the undo history
	else if (command == 'inserthtml') {
		wikEdFrameDocument.selection.createRange().pasteHTML(option);
	}
	return;
};


//
// WikEdFindAhead: find-as-you-type, event handler for find field, supports insensitive and regexp settings
//

window.WikEdFindAhead = function() {

	if (WikEdGetAttribute(wikEdFindAhead, 'checked') == 'true') {

// get the find text
		var findText = wikEdFindText.value;
		if (findText == '') {
			return;
		}

// remember input field selection
		var findTextSelectionStart = wikEdFindText.selectionStart;
		var findTextSelectionEnd = wikEdFindText.selectionEnd;

// remember frame selection
		var sel = WikEdGetSelection();
		var range = sel.getRangeAt(sel.rangeCount - 1).cloneRange();
		var rangeClone = range.cloneRange();
		var scrollTop = wikEdFrameBody.scrollTop;

// collapse selection to the left
		WikEdRemoveAllRanges(sel);
		range.collapse(true);
		range = sel.addRange(range);

// create obj for regexp search
		var obj = {};

// get the replace text
		var replaceText = wikEdInputElement['replace'].value;

// get insensitive and regexp button states
		var regExpChecked = WikEdGetAttribute(wikEdRegExp, 'checked');
		var caseSensitiveChecked = WikEdGetAttribute(wikEdCaseSensitive, 'checked');

// get case sensitive setting
		var caseSensitive = false;
		if (caseSensitiveChecked == 'true') {
			caseSensitive = true;
		}

// get regexp setting
		var useRegExp = false;
		if (regExpChecked == 'true') {
			useRegExp = true;
		}

// parameters: obj, findText, caseSensitive, backwards, wrap, useRegExp
		found = WikEdFind(obj, findText, caseSensitive, false, true, useRegExp);

// restore original frame selection
		if (found == false) {
			wikEdFrameBody.scrollTop = scrollTop;
			WikEdRemoveAllRanges(sel);
			sel.addRange(rangeClone);
		}
		else {
			WikEdRemoveAllRanges(obj.sel);
			obj.sel.addRange(obj.changed.range);

// scroll to selection
			WikEdScrollToSelection();
		}
	}

// restore input field selection (needed for FF 3.6)
	wikEdFindText.select();
	wikEdFindText.setSelectionRange(findTextSelectionStart, findTextSelectionEnd);

	return;
};


//
// WikEdMainSwitch: click handler for program logo
//

window.WikEdMainSwitch = function() {

// disable function if browser is incompatible
	if (wikEdBrowserNotSupported == true) {
		return;
	}

// enable wikEd
	if (wikEdDisabled == true) {
		wikEdDisabled = false;
		WikEdSetPersistent('wikEdDisabled', '0', 0, '/');

// turn rich text frame on
		if (wikEdTurnedOn == false) {

// setup wikEd
			WikEdTurnOn(false);
		}
		else {
			WikEdSetLogo();
			var useWikEd = false;
			if (WikEdGetAttribute(document.getElementById('wikEdUseWikEd'), 'checked') == 'true') {
				useWikEd = true;
			}
			WikEdSetEditArea(useWikEd);
			wikEdUseWikEd = useWikEd;
			if (wikEdUseWikEd == true) {
				WikEdUpdateFrame();
			}
			wikEdButtonsWrapper.style.display = 'block';
			wikEdButtonBarPreview.style.display = 'block';
			if (wikEdButtonBarJump != null) {
				wikEdButtonBarJump.style.display = 'block';
			}

// run scheduled custom functions
			WikEdExecuteHook(wikEdOnHook);
		}
	}

// disable wikEd
	else {
		WikEdSetPersistent('wikEdDisabled', '1', 0, '/');
		if (wikEdTurnedOn == false) {
			wikEdUseWikEd = false;
			wikEdDisabled = true;
		}
		else {
			if (wikEdFullScreenMode == true) {
				WikEdFullScreen(false);
			}

// turn classic textarea on
			if (wikEdUseWikEd == true) {
				WikEdUpdateTextarea();
			}
			WikEdSetEditArea(false);

// reset textarea dimensions
			wikEdTextarea.style.height = (wikEdTextareaOffsetHeightInitial - wikEdFrameBorderHeight) + 'px';
			wikEdTextarea.style.width = '100%';

			wikEdFrameHeight = (wikEdTextareaOffsetHeightInitial - wikEdFrameBorderHeight) + 'px';
			wikEdFrameWidth = (wikEdEditorWrapper.clientWidth - wikEdFrameBorderWidth) + 'px';
			wikEdFrame.style.height = wikEdFrameHeight;
			wikEdFrame.style.width = wikEdFrameWidth;

			wikEdButtonsWrapper.style.display = 'none';
			wikEdButtonBarPreview.style.display = 'none';
			wikEdLocalPrevWrapper.style.display = 'none';
			wikEdPreviewBox.style.height = 'auto';
			if (wikEdButtonBarJump != null) {
				wikEdButtonBarJump.style.display = 'none';
			}

			wikEdUseWikEd = false;
			wikEdDisabled = true;
			WikEdSetLogo();

// run scheduled custom functions
			WikEdExecuteHook(wikEdOffHook);
		}
	}
	return;
};


//
// WikEdFullScreen: change to fullscreen edit area or back to normal view
//

window.WikEdFullScreen = function(fullscreen, notFrame) {

// hide or show elements
	var displayStyle;
	if (fullscreen == true) {
		displayStyle = 'none';
	}
	else {
		displayStyle = 'block';
	}

// elements above input wrapper
	var node = document.getElementById('editform').previousSibling;
	while (node != null) {
		if ( (node.nodeName == 'DIV') || (node.nodeName == 'H3') ) {
			node.style.display = displayStyle;
		}
		node = node.previousSibling;
	}
	document.getElementsByTagName('H1')[0].style.display = displayStyle;

// divs below input wrapper
	var node = wikEdInputWrapper.nextSibling;
	while (node != null) {
		if (node.nodeName == 'DIV') {
			node.style.display = displayStyle;
		}
		node = node.nextSibling;
	}

// divs below input wrapper, some levels up
	var node = document.getElementById('column-one');
	while (node != null) {
		if (node.nodeName == 'DIV') {
			node.style.display = displayStyle;
		}
		node = node.nextSibling;
	}

// insert wrapper
	document.getElementById('wikEdInsertWrapper').style.display = displayStyle;

// change styles
	if (fullscreen == true) {
		if (notFrame != true) {
			wikEdInputWrapper.className = 'wikEdInputWrapperFull';
		}
		wikEdButtonBarPreview.className = 'wikEdButtonBarPreviewFull';
	}
	else {
		if (notFrame != true) {
			wikEdInputWrapper.className = 'wikEdInputWrapper';
		}
		wikEdButtonBarPreview.className = 'wikEdButtonBarPreview';
	}

// resize the frame
	if (fullscreen == true) {

// end frame resizing
		WikEdRemoveEventListener(wikEdFrameDocument, 'mouseup', WikEdResizeStopHandler, true);
		WikEdRemoveEventListener(document, 'mouseup', WikEdResizeStopHandler, true);
		WikEdRemoveEventListener(wikEdFrameDocument, 'mousemove', WikEdResizeDragHandlerFrame, true);
		WikEdRemoveEventListener(document, 'mousemove', WikEdResizeDragHandlerDocument, true);
		wikEdResizeFrameMouseOverGrip = false;
		WikEdRemoveEventListener(wikEdFrameDocument, 'mousedown', WikEdResizeStartHandler, true);
		wikEdFrameBody.style.cursor = 'auto';
		wikEdResizeFrameActive = false;

		var consoleTop = WikEdGetOffsetTop(wikEdConsoleWrapper);
		var consoleHeight = wikEdConsoleWrapper.offsetHeight;
		var frameHeight = wikEdFrame.offsetHeight;
		var windowHeight = WikEdGetWindowInnerHeight();

		wikEdFrame.style.height = (frameHeight + (windowHeight - (consoleTop + consoleHeight) ) - 2) + 'px';
		wikEdFrame.style.width = (wikEdEditorWrapper.clientWidth - wikEdFrameBorderWidth) + 'px';
	}
	else {
		if (wikEdFrameHeight == 0) {
			wikEdFrameHeight = (wikEdTextareaOffsetHeightInitial - wikEdFrameBorderHeight) + 'px';
			wikEdFrameWidth = (wikEdEditorWrapper.clientWidth - wikEdFrameBorderWidth) + 'px';
		}
		wikEdFrame.style.height = wikEdFrameHeight;
		wikEdFrame.style.width = wikEdFrameWidth;
	}

// scroll to edit-frame
	if (fullscreen == false) {
		window.scroll(0, WikEdGetOffsetTop(wikEdInputWrapper) - 2);
	}

// set the fullscreen button state
	WikEdButton(document.getElementById('wikEdFullScreen'), 'wikEdFullScreen', null, fullscreen);

// grey out or re-activate scroll-to buttons
	var hideButtonClass;
	if (fullscreen == true) {
		hideButtonClass = 'wikEdButtonInactive';
	}
	else {
		hideButtonClass = 'wikEdButton';
	}
	document.getElementById('wikEdScrollToPreview').className = hideButtonClass;
	document.getElementById('wikEdScrollToPreview2').className = hideButtonClass;
	document.getElementById('wikEdScrollToEdit').className = hideButtonClass;
	document.getElementById('wikEdScrollToEdit2').className = hideButtonClass;

// resize the summary field
	WikEdResizeSummary();

	wikEdFullScreenMode = fullscreen;

	return;
};


//
// WikEdResizeSummary: recalculate the summary width after resizing the window
//

window.WikEdResizeSummary = function() {

// check if combo field exists
	if (wikEdSummarySelect == null) {
		return;
	}

	wikEdSummaryText.style.width = '';
	wikEdSummarySelect.style.width = '';

	wikEdSummaryTextWidth = wikEdSummaryWrapper.clientWidth - ( WikEdGetOffsetLeft(wikEdSummaryText) - WikEdGetOffsetLeft(wikEdSummaryWrapper) );
	if (wikEdSummaryTextWidth < 150) {
		wikEdSummaryTextWidth = 150;
	}
	wikEdSummaryText.style.width = wikEdSummaryTextWidth + 'px';
	WikEdResizeComboInput('summary');
	return;
};


//
// WikEdResizeComboInput: set the size of the background select boxes so that the button is visible
//   calculates the select button width as the difference between select and option width
//   adjusts widths so that only the select button is visible behind the input field
//

window.WikEdResizeComboInput = function(field) {

// check if combo field exists
	if (wikEdSelectElement[field] == null) {
		return;
	}

// detect browser for MS IE fixes
	var standardBrowser = true;
	if (wikEdSelectElement[field].options.offsetWidth != null ) {
		standardBrowser = false;
	}

// set select height and top
	if (standardBrowser == false) {
		wikEdSelectElement[field].style.height = (wikEdInputElement[field].clientHeight + 6) + 'px';
		wikEdSelectElement[field].style.top = '3px';
		wikEdInputElement[field].style.top = '3px';
	}

// add a dummy option if no option exists yet
	var dummy;
	var testOption = 1;
	if (standardBrowser == true) {
		if (wikEdSelectElement[field].options.length == 0) {
			testOption = 0;
			wikEdSelectElement[field].options[0] = new Option('');
			dummy = true;
		}
	}

// set option widths to 0
	if (standardBrowser == true) {
		for (var i = 0; i < wikEdSelectElement[field].options.length; i ++) {
			wikEdSelectElement[field].options[i].style.width = '0';
		}
	}

// get input width
	var inputBorder = (wikEdInputElement[field].offsetWidth - wikEdInputElement[field].clientWidth);
	var inputWidthInner = wikEdInputElement[field].clientWidth;
	var inputWidthOuter = wikEdInputElement[field].offsetWidth;

// get select width
	var selectWidthInner = wikEdSelectElement[field].clientWidth;
	var selectWidthOuter = wikEdSelectElement[field].offsetWidth;

// get option width and calculate button width
	var optionWidthInner;
	var buttonWidth;
	if (standardBrowser == true) {

// Firefox < 3.0
		if ( typeof(wikEdSelectElement[field].options[testOption].clientLeft) == 'undefined' ) {
			optionWidthInner = wikEdSelectElement[field].options[testOption].clientWidth;
			buttonWidth = selectWidthInner - optionWidthInner - 6;
		}

// Firefox >= 3.0
		else {
			optionWidthInner = wikEdSelectElement[field].options[testOption].clientWidth;
			buttonWidth = selectWidthInner - optionWidthInner;
		}
	}
	else {
		buttonWidth = selectWidthOuter - selectWidthInner - 4;
	}

// for long fields shorten input
	if (inputWidthOuter + buttonWidth > 150) {
		wikEdInputElement[field].style.width = (inputWidthInner - inputBorder - buttonWidth) + 'px';
		wikEdSelectElement[field].style.width = (inputWidthInner) + 'px';
	}

// otherwise increase select width
	else {
		wikEdSelectElement[field].style.width = (inputWidthOuter + buttonWidth) + 'px';
	}

// delete dummy option
	if (dummy == true) {
		wikEdSelectElement[field].options[0] = null;
	}

// set option widths to auto
	if (standardBrowser == true) {
		for (var i = 0; i < wikEdSelectElement[field].options.length; i ++) {
			wikEdSelectElement[field].options[i].style.width = 'auto';
		}
	}
	return;
};


//
// WikEdChangeComboInput: sets the input value to selected option; onchange event handler for select boxes
//

window.WikEdChangeComboInput = function(field) {

// get selection index (-1 for unselected)
	var selected = wikEdSelectElement[field].selectedIndex;
	if (selected >= 0) {
		wikEdSelectElement[field].selectedIndex = -1;

// get selected option
		var option = wikEdSelectElement[field].options[selected];
		if (option.text != '') {

// jump to heading
			if ( (field == 'find') && (/^=.*?=$/.test(option.value) == true) ) {
				var obj = {};
				var findText = option.value.replace(/([\\\^\$\*\+\?\.\(\)\[\]\{\}\:\=\!\|\,\-])/g, '\\$1');
				findText = '^' + findText + '$';

// find and select heading text
				var found = WikEdFind(obj, findText, true, false, true, true);
				WikEdRemoveAllRanges(obj.sel);
				obj.sel.addRange(obj.changed.range);

// and scroll it into the viewport
				WikEdScrollToSelection();
				return;
			}

// update input field
			else {

// add a tag to the summary box
				if (field == 'summary') {
					wikEdInputElement[field].value = WikEdAppendToSummary(wikEdInputElement[field].value, option.text);
				}

// add case and regexp checkboxes to find / replace fields
				else if (option.value == 'setcheck') {
					WikEdButton(document.getElementById('wikEdCaseSensitive'), 'wikEdCaseSensitive', null, (option.text.charAt(0) == wikEdCheckMarker[true]) );
					WikEdButton(document.getElementById('wikEdRegExp'), 'wikEdRegExp', null, (option.text.charAt(1) == wikEdCheckMarker[true]) );
					wikEdInputElement[field].value = option.text.substr(3);
				}

// add option text
				else {
					wikEdInputElement[field].value = option.text;
				}

// find the new text
				if ( (field == 'find') && (WikEdGetAttribute(wikEdFindAhead, 'checked') == 'true') ) {
					WikEdFindAhead();
				}
			}
		}
	}
	wikEdInputElement[field].focus();

	return;
};


//
// WikEdAppendToSummary: append a phrase to the summary text
//

window.WikEdAppendToSummary = function(summary, append) {

	summary = summary.replace(/^[, ]+/, '');
	summary = summary.replace(/[, ]+$/, '');
	if (summary != '') {
		if (summary.match(/ \*\/$/) != null) {
			summary += ' ';
		}
		else if (summary.match(/[\.\;\:]$/) != null) {
			summary += ' ';
		}
		else if (summary.match(/^[\wŠŒŽšœžŸÀ-ÖØ-öø-\u0220\u0222-\u0233ΆΈΉΊΌΎΏΑ-ΡΣ-ώ\u0400-\u0481\u048a-\u04ce\u04d0-\u04f5\u04f8\u04f9\(\)\"\'\+\-]/) == null) {
			summary += ' ';
		}
		else {
			summary += ', ';
		}
	}
	summary += append;

	return(summary);
};


//
// WikEdAddToHistory: add an input value to the saved history
//

window.WikEdAddToHistory = function(field) {

	if (wikEdInputElement[field].value != '') {

// load history from saved settings
		WikEdLoadHistoryFromSettings(field);

// add current value to history
		wikEdFieldHist[field].unshift(wikEdInputElement[field].value);

// add case and regexp checkboxes to find / replace value
		if ( (field == 'find') || (field == 'replace') ) {
			wikEdFieldHist[field][0] =
				wikEdCheckMarker[ (WikEdGetAttribute(wikEdCaseSensitive, 'checked') == 'true') ] +
				wikEdCheckMarker[ (WikEdGetAttribute(wikEdRegExp, 'checked') == 'true') ] +
				' ' + wikEdFieldHist[field][0];
		}

// remove paragraph names from summary
		if (field == 'summary') {
			wikEdFieldHist[field][0] = wikEdFieldHist[field][0].replace(/^\/\* .*? \*\/ *()/, '');
		}

// remove multiple old copies from history
		var i = 1;
		while (i < wikEdFieldHist[field].length) {
			if (wikEdFieldHist[field][i] == wikEdFieldHist[field][0]) {
				wikEdFieldHist[field].splice(i, 1);
			}
			else {
				i ++;
			}
		}

// remove new value if it is a preset value
		if (wikEdComboPresetOptions[field] != null) {
			var i = 0;
			while (i < wikEdComboPresetOptions[field].length) {
				if (wikEdComboPresetOptions[field][i] == wikEdFieldHist[field][0]) {
					wikEdFieldHist[field].shift();
					break;
				}
				else {
					i ++;
				}
			}
		}

// cut history number to maximal history length
		wikEdFieldHist[field] = wikEdFieldHist[field].slice(0, wikEdHistoryLength[field]);

// save history to settings
		if (wikEdFieldHist[field][0] != '') {
			WikEdSaveHistoryToSetting(field);
		}
	}
	return;
};


//
// WikEdSetComboOptions: generate the select options from saved history; onfocus handler for select box
//

window.WikEdSetComboOptions = function(field) {

// load history from saved settings
	WikEdLoadHistoryFromSettings(field);

	var option = {};
	var selectedOption = null;

// delete options
	var options = wikEdSelectElement[field].options;
	for (var i = 0; i < options.length; i ++) {
		wikEdSelectElement[field].remove(i);
	}

// delete optgroup
	option = document.getElementById(field + 'Optgroup');
	if (option != null) {
		wikEdSelectElement[field].removeChild(option);
	}

// workaround for onchange not firing when selecting first option from unselected dropdown
	option = document.createElement('option');
	option.style.display = 'none';
	j = 0;
	wikEdSelectElement[field].options[j++] = option;

// add history entries
	for (var i = 0; i < wikEdFieldHist[field].length; i ++) {
		if (wikEdFieldHist[field][i] != null) {
			if (wikEdFieldHist[field][i] == wikEdInputElement[field].value) {
				selectedOption = j;
			}
			option = document.createElement('option');

// replace spaces with nbsp to allow for multiple, leading, and trailing spaces
			option.text = wikEdFieldHist[field][i].replace(/ /g, '\xa0');
			if ( (field == 'find') || (field == 'replace') ) {
				option.value = 'setcheck';
			}
			wikEdSelectElement[field].options[j++] = option;
		}
	}

// add preset entries
	var startPreset = 0;
	if (wikEdComboPresetOptions[field] != null) {
		startPreset = j;
		for (var i = 0; i < wikEdComboPresetOptions[field].length; i ++) {
			if (wikEdComboPresetOptions[field][i] != null) {

// replace spaces with nbsp to allow for multiple, leading, and trailing spaces
				wikEdComboPresetOptions[field][i] = wikEdComboPresetOptions[field][i].replace(/ /g, '\xa0');

// select a dropdown value
				if (wikEdComboPresetOptions[field][i] == wikEdInputElement[field].value) {
					selectedOption = j;
				}

				option = document.createElement('option');
				option.text = wikEdComboPresetOptions[field][i].replace(/ /g, '\xa0');
				if (field == 'summary') {
					option.text = option.text.replace(/\{wikEdUsing\}/g, wikEdSummaryUsing);
				}
				wikEdSelectElement[field].options[j++] = option;
			}
		}
	}

// set the selection
	wikEdSelectElement[field].selectedIndex = selectedOption;

// add a blank preset separator
	if ( (startPreset > 1) && (startPreset < j) ) {
		option = document.createElement('optgroup');
		option.label = '\xa0';
		option.id = field + 'Optgroup';
		wikEdSelectElement[field].insertBefore(option, wikEdSelectElement[field].options[startPreset]);
	}

// add the TOC jumper to the find field
	var startTOC = 0;
	if (field == 'find') {
		startTOC = j;

// get the whole plain text
		var plain = wikEdFrameBody.innerHTML;
		plain = plain.replace(/<br\b[^>]*>/g, '\n');
		plain = plain.replace(/<.*?>/g, '');
		plain = plain.replace(/&nbsp;/g, '\xa0');
		plain = plain.replace(/&gt;/g, '>');
		plain = plain.replace(/&lt;/g, '<');
		plain = plain.replace(/&amp;/g, '&');

// cycle through the headings
		var heading = plain.match(/(^|\n)=+.+?=+[^\n=]*[ =\t]*(?=(\n|$))/g);
		if (heading != null) {
			for (var i = 0; i < heading.length; i ++) {
				var headingMatch = heading[i].match(/\n?((=+) *(.+?)( *\2))/);
				var headingIndent = headingMatch[2];
				headingIndent = headingIndent.replace(/^=/g, '');
				headingIndent = headingIndent.replace(/\=/g, '\xa0');

// add headings to the select element
				option = document.createElement('option');
				option.text = '\u21d2' + headingIndent + headingMatch[3];
				option.value = headingMatch[1];
				wikEdSelectElement[field].options[j++] = option;
			}
		}
	}

// add a blank TOC separator
	if ( (startTOC > 1) && (startTOC < j) ) {
		option = document.createElement('optgroup');
		option.label = '\xa0';
		option.id = field + 'Optgroup';
		wikEdSelectElement[field].insertBefore(option, wikEdSelectElement[field].options[startTOC]);
	}

	return;
};


//
// WikEdClearHistory: clear the history of combo input fields
//

window.WikEdClearHistory = function(field) {
	WikEdSetPersistent(wikEdSavedName[field], '', 0, '/');
	WikEdSetComboOptions(field);
	return;
};


//
// WikEdLoadHistoryFromSettings: get the input box history from the respective saved settings
//

window.WikEdLoadHistoryFromSettings = function(field) {
	var setting = WikEdGetPersistent(wikEdSavedName[field]);
	if (setting != '') {
		setting = decodeURIComponent(setting);
		wikEdFieldHist[field] = setting.split('\n');
	}
	else {
		wikEdFieldHist[field] = [];
	}
	return;
};


//
// WikEdSaveHistoryToSetting: save the input box history to the respective saved settings
//

window.WikEdSaveHistoryToSetting = function(field) {

	var setting = '';
	setting = wikEdFieldHist[field].join('\n');
	setting = setting.replace(/\n$/, '');
	setting = encodeURIComponent(setting);
	WikEdSetPersistent(wikEdSavedName[field], setting, 0, '/');
	return;
};


//
// WikEdGetSelection: cross-browser method to get the current iframe selection
//

window.WikEdGetSelection = function() {

// standard
	var sel;
	if (typeof(wikEdFrameWindow.getSelection) == 'function') {
		sel = wikEdFrameWindow.getSelection();
	}

// MS IE compatibility
	else if (typeof(wikEdFrameDocument.selection) == 'object') {
		sel = wikEdFrameDocument.selection;
	}

// make sure there is at least an empty range
	if (sel.rangeCount == 0) {
		sel.collapse(wikEdFrameBody, 0);
	}

	return(sel);
};


//
// WikEdClearSelection: cross-browser method to clear the currently selected text
//

window.WikEdRemoveAllRanges = function(sel) {

	if (typeof(sel.removeAllRanges) == 'function') {
		sel.removeAllRanges();
	}

// MS IE compatibility
	else if (typeof(sel.empty) == 'function') {
		sel.empty();
	}
	return;
};


//
// WikEdSetRange: set a range, control for non-text nodes (Opera 10.50 beta bug)
//

window.WikEdSetRange = function(range, startNode, startOffset, endNode, endOffset) {

	WikEdSetRangeStart(range, startNode, startOffset);
	WikEdSetRangeEnd(range, endNode, endOffset);
	return;
};


//
// WikEdSetRangeStart: set range start
//

window.WikEdSetRangeStart = function(range, startNode, startOffset) {

	if ( (startNode.childNodes.length > 0) && (startOffset < startNode.childNodes.length) ) {
		startNode = startNode.childNodes.item(startOffset);
		startOffset = 0;
	}
	if (startNode.nodeName == '#text') {
		range.setStart(startNode, startOffset);
	}
	else {
		range.setStartAfter(startNode);
	}
	return;
};


//
// WikEdSetRangeEnd: set range end
//

window.WikEdSetRangeEnd = function(range, endNode, endOffset) {

	if ( (endNode.childNodes.length > 0) && (endOffset < endNode.childNodes.length) ) {
		endNode = endNode.childNodes.item(endOffset);
		endOffset = 0;
	}
	if (endNode.nodeName == '#text') {
		range.setEnd(endNode, endOffset);
	}
	else {
		range.setEndBefore(endNode);
	}
	return;
};


//
// WikEdGetSavedSetting: get a wikEd setting
//

window.WikEdGetSavedSetting = function(settingName, preset) {

	var setting = WikEdGetPersistent(settingName);
	if (setting == '') {
		setting = preset;
	}
	else if (setting == '1') {
		setting = true;
	}
	else {
		setting = false;
	}
	return(setting);
};


//
// WikEdGetPersistent: get a cookie or a Greasemonkey persistent value (code copied to wikEdDiff.js)
//

window.WikEdGetPersistent = function(name) {

	var getStr = '';

// get a Greasemonkey persistent value
	if (wikEdGreasemonkey == true) {
		getStr = GM_getValue(name, '');
	}

// get a cookie value
	else {
		getStr = WikEdGetCookie(name);
	}
	return(getStr);
};


//
// WikEdSetPersistent: set a cookie or a Greasemonkey persistent value, deletes the value for expire = -1
//

window.WikEdSetPersistent = function(name, value, expires, path, domain, secure) {

// set a Greasemonkey persistent value
	if (wikEdGreasemonkey == true) {
		if (expires == -1) {
			GM_setValue(name, '');
		}
		else {
			GM_setValue(name, value);
		}
	}

// set a cookie value
	else {
		WikEdSetCookie(name, value, expires, path, domain, secure);
	}
	return;
};


//
// WikEdGetCookie: get a cookie (code copied to wikEdDiff.js)
//

window.WikEdGetCookie = function(cookieName) {

	var cookie = ' ' + document.cookie;
	var search = ' ' + cookieName + '=';
	var cookieValue = '';
	var offset = 0;
	var end = 0;
	offset = cookie.indexOf(search);
	if (offset != -1) {
		offset += search.length;
		end = cookie.indexOf(';', offset);
		if (end == -1) {
			end = cookie.length;
		}
		cookieValue = cookie.substring(offset, end);
		cookieValue = cookieValue.replace(/\\+/g, ' ');
		cookieValue = decodeURIComponent(cookieValue);
	}
	return(cookieValue);
};


//
// WikEdSetCookie: set a cookie, deletes a cookie for expire = -1
//

window.WikEdSetCookie = function(name, value, expires, path, domain, secure) {

	var cookie = name + '=' + encodeURIComponent(value);

	if (expires != null) {

// generate a date 1 hour ago to delete the cookie
		if (expires == -1) {
			var cookieExpire = new Date();
			expires = cookieExpire.setTime(cookieExpire.getTime() - 60 * 60 * 1000);
			expires = cookieExpire.toUTCString();
		}

// get date from expiration preset
		else if (expires == 0) {
			var cookieExpire = new Date();
			expires = cookieExpire.setTime(cookieExpire.getTime() + wikEdCookieExpireSec * 1000);
			expires = cookieExpire.toUTCString();
		}
		cookie += '; expires=' + expires;
	}
	if (path != null) {
		cookie += '; path=' + path;
	}
	if (domain != null)  {
		cookie += '; domain=' + domain;
	}
	if (secure != null) {
		cookie += '; secure';
	}
	document.cookie = cookie;
	return;
};


//
// WikEdGetOffsetTop: get element offset relative to window top (code copied to wikEdDiff.js)
//

window.WikEdGetOffsetTop = function(element) {
	var offset = 0;
	do {
		offset += element.offsetTop;
	} while ( (element = element.offsetParent) != null );
	return(offset);
};


//
// WikEdGetOffsetLeft: get element offset relative to left window border
//

window.WikEdGetOffsetLeft = function(element) {
	var offset = 0;
	do {
		offset += element.offsetLeft;
	} while ( (element = element.offsetParent) != null );
	return(offset);
};


//
// WikEdAppendScript: append script to head
//

window.WikEdAppendScript = function(scriptUrl) {

	var head = document.getElementsByTagName('head')[0];
	var script = document.createElement('script');
	script.type = 'text/javascript';
	script.src = scriptUrl;
	head.appendChild(script);
	return;
};


//
// WikEdCleanNodes: remove DOM elements dynamically inserted by other scripts
//

window.WikEdCleanNodes = function(node) {

	if (wikEdCleanNodes == false) {
		return;
	}

// remove Web of Trust (WOT) tags
	var divs = node.getElementsByTagName('div');
	for (var i = 0; i < divs.length; i ++) {
		var div = divs[i];

// test for WOT class names
		var divClass = div.className;
		if (/^wot-/.test(divClass) == true) {
			var divParent = div.parentNode;
			if (divParent != null) {
				divParent.removeChild(div);
			}
			continue;
		}

// test for WOT attributes
		var divAttrs = div.attributes;
		for (var j = 0; j < divAttrs.length; ++ j) {
			var attr = divAttrs.item(j);
			if ( (attr.nodeName == 'wottarget') || (/^link[0-9a-f]{30,}/.test(attr.nodeName) == true) ) {
				var divParent = div.parentNode;
				if (divParent != null) {
					divParent.removeChild(div);
				}
				break;
			}
		}
	}
	return;
};


//
// WikEdParseDOM: parses a DOM subtree into a linear array of plain text fragments
//

window.WikEdParseDOM = function(obj, topNode) {

	obj.plainLength = 0;
	obj.plainArray = [];
	obj.plainNode = [];
	obj.plainStart = [];
	obj.plainPos = [];

	var anchorNode = obj.sel.anchorNode;
	var	focusNode = obj.sel.focusNode;
	var anchorOffset = obj.sel.anchorOffset;
	var focusOffset = obj.sel.focusOffset;

	WikEdParseDOMRecursive(obj, topNode, anchorNode, anchorOffset, focusNode, focusOffset);
	obj.plain = obj.plainArray.join('');
	return;
};


//
// WikEdParseDOMRecursive: parses a DOM subtree into a linear array of plain text fragments
//

window.WikEdParseDOMRecursive = function(obj, currentNode, anchorNode, anchorOffset, focusNode, focusOffset) {

// cycle through the child nodes of currentNode
	var childNodes = currentNode.childNodes;
  for (var i = 0; i < childNodes.length; i ++) {
		var childNode = childNodes.item(i);

// check for selection, non-text nodes
		if ( (currentNode == anchorNode) && (i == anchorOffset) ) {
			obj.plainAnchor = obj.plainLength;
		}
		if ( (currentNode == focusNode) && (i == focusOffset) ) {
			obj.plainFocus = obj.plainLength;
		}

// check for selection, text nodes
		if (childNode == obj.sel.anchorNode) {
			obj.plainAnchor = obj.plainLength + obj.sel.anchorOffset;
		}
		if (childNode == obj.sel.focusNode) {
			obj.plainFocus = obj.plainLength + obj.sel.focusOffset;
		}

// get text of child node
		var value = null;
		switch (childNode.nodeType) {
			case childNode.ELEMENT_NODE:

// skip hidden elements
				if (WikEdGetStyle(childNode, 'display') == 'none') {
					continue;
				}
				if ( (childNode.childNodes.length == 0) && (wikEdLeafElements[childNode.nodeName] == true) ) {
					if (childNode.nodeName == 'BR') {
						value = '\n';
					}
				}
				else {
					WikEdParseDOMRecursive(obj, childNode, anchorNode, anchorOffset, focusNode, focusOffset);
				}
				break;
			case childNode.TEXT_NODE:
				value = childNode.nodeValue;
				value = value.replace(/\n/g, ' ');
				break;
			case childNode.ENTITY_REFERENCE_NODE:
				value = '&' + childNode.nodeName + ';';
				break;
		}

// add text to text object
		if (value != null) {

// array of text fragments
			obj.plainArray.push(value);

// array of text fragment node references
			obj.plainNode.push(childNode);

// array of text fragment text positions
			obj.plainStart.push(obj.plainLength);

// node references containing text positions
			obj.plainPos[childNode] = obj.plainLength;

// current text length
			obj.plainLength += value.length;
		}
	}
	return;
};


//
// WikEdGetInnerHTML: get the innerHTML of a document fragment
//

window.WikEdGetInnerHTML = function(obj, currentNode) {

// initialize string
	if (obj.html == null) {
		obj.html = '';
	}
	if (obj.plain == null) {
		obj.plain = '';
	}
	if (obj.plainArray == null) {
		obj.plainArray = [];
		obj.plainNode = [];
		obj.plainStart = [];
	}

	var childNodes = currentNode.childNodes;
  for (var i = 0; i < childNodes.length; i ++) {
		var childNode = childNodes.item(i);
		switch (childNode.nodeType) {
			case childNode.ELEMENT_NODE:
				obj.html += '<' + childNode.nodeName.toLowerCase();
				for (var j = 0; j < childNode.attributes.length; j ++) {
					if (childNode.attributes.item(j).nodeValue != null) {
						obj.html += ' ' + childNode.attributes.item(j).nodeName + '="' + childNode.attributes.item(j).nodeValue.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '"';
					}
				}
				if ( (childNode.childNodes.length == 0) && (wikEdLeafElements[childNode.nodeName] == true) ) {
					obj.html += '>';
					if (childNode.nodeName == 'BR') {
						obj.plainArray.push('\n');
						obj.plainNode.push(childNode);
						obj.plainStart.push(obj.plain.length);
						obj.plain += '\n';
					}
				}
				else {
					obj.html += '>';
					WikEdGetInnerHTML(obj, childNode);
					obj.html += '</' + childNode.nodeName.toLowerCase() + '>';
				}
				break;
			case childNode.TEXT_NODE:
				var value = childNode.nodeValue;
				value = value.replace(/\n/g, ' '); // important for pasted page content
				obj.plainArray.push(value);        // plain array contains & < > instead of &amp; &lt; &gt;
				obj.plainNode.push(childNode);
				obj.plainStart.push(obj.plain.length);
				value = value.replace(/&/g, '&amp;');
				value = value.replace(/</g, '&lt;');
				value = value.replace(/>/g, '&gt;');
				obj.html += value;
				obj.plain += value;
				break;
			case childNode.CDATA_SECTION_NODE:
				obj.html += '<![CDATA[' + childNode.nodeValue + ']]>';
				break;
			case childNode.ENTITY_REFERENCE_NODE:
				var value = '&' + childNode.nodeName + ';';
				obj.plainArray.push(value);
				obj.plainNode.push(childNode);
				obj.plainStart.push(obj.plain.length);
				value = value.replace(/&/g, '&amp;');
				obj.html += value;
				obj.plain += value;
				break;
			case childNode.COMMENT_NODE:
				obj.html += '<!--' + childNode.nodeValue + '-->';
				break;
		}
	}
	return;
};


//
// WikEdGetNextNode: recurse through DOM to next text-like node for anti-highlight bleeding
//

window.WikEdGetNextTextNode = function(obj, currentNode, currentLevel) {

// ascend until there is a sibling
	while (currentNode != wikEdFrameBody) {

// check for sibling
		var nextNode = null;
		if ( (obj.backwards == true) && (currentNode.previousSibling != null) ) {
			nextNode = currentNode.previousSibling;
		}
		else if ( (obj.backwards != true) && (currentNode.nextSibling != null) ) {
			nextNode = currentNode.nextSibling
		}

// found sibling
		if (nextNode != null) {
			currentNode = nextNode;

// found text-like node
			if (
				(currentNode.nodeName == '#text') ||
				(currentNode.nodeType == currentNode.ENTITY_REFERENCE_NODE) ||
				(wikEdLeafElements[currentNode.nodeName] == true)
		 	) {
				obj.foundNode = currentNode;
				obj.foundLevel = currentLevel;
				return;
			}

// recurse into child nodes
			if (currentNode.nodeType == currentNode.ELEMENT_NODE) {
				WikEdGetNextTextNodeChilds(obj, currentNode, currentLevel - 1);
				if (obj.foundNode != null) {
					return;
				}
			}
		}

// no sibling, ascend to parent
		else {
			currentNode = currentNode.parentNode;
			currentLevel ++;
		}
	}
	return;
};


// WikEdGetNextTextNodeChilds: recurse through child nodes to next text-like node for anti-highlight bleeding

window.WikEdGetNextTextNodeChilds = function(obj, currentNode, currentLevel) {

// set direction
	var childNodes = currentNode.childNodes;
	if (childNodes.length == 0) {
		return;
	}
	var start = 0;
	var add = 1;
	if (obj.backwards == true) {
		start = childNodes.length - 1;
		add = -1;
	}

// cycle through child nodes (left or right)
  for (var i = start; ( (obj.backwards == true) && (i >= 0) ) || ( (obj.backwards != true) && (i < childNodes.length) ); i = i + add) {
		var currentNode = childNodes.item(i);

// found text-like node
		if (
			(currentNode.nodeName == '#text') ||
			(currentNode.nodeType == currentNode.ENTITY_REFERENCE_NODE) ||
			(wikEdLeafElements[currentNode.nodeName] == true)
	 	) {
			obj.foundNode = currentNode;
			obj.foundLevel = currentLevel;
			return;
		}

// recurse into child nodes
		if (currentNode.nodeType == currentNode.ELEMENT_NODE) {
			WikEdGetNextTextNodeChilds(obj, currentNode, currentLevel - 1);
			if (obj.foundNode != null) {
				return;
			}
		}
	}
	return;
};


//
// WikEdApplyCSS: Attach css rules to document
//

window.WikEdApplyCSS = function(cssDocument, cssRules) {

	var stylesheet = new WikEdStyleSheet(cssDocument);
	var rules = '';
	for (var ruleName in cssRules) {
		if (cssRules.hasOwnProperty(ruleName) == true) {
			var ruleStyle = cssRules[ruleName];

// replace {wikedImage:image} in css rules with image path
			ruleStyle = ruleStyle.replace(/\{wikEdImage:(\w+)\}/g,
				function (p, p1) {
					return(wikEdImage[p1]);
				}
			);

// replace {wikedText:text} in css rules with translation
			ruleStyle = ruleStyle.replace(/\{wikEdText:(\w+)\}/g,
				function (p, p1) {
					return(wikEdText[p1]);
				}
			);

			rules += ruleName + ' {' + ruleStyle + '}\n';
		}
	}
	stylesheet.WikEdAddRules(rules);
	return;
};


//
// WikEdStyleSheet: create a new style sheet object (code copied to wikEdDiff.js)
//

window.WikEdStyleSheet = function(contextObj) {

	if (contextObj == null) {
		contextObj = document;
	}
	this.styleElement = null;

// MS IE compatibility
	if (contextObj.createStyleSheet) {
		this.styleElement = contextObj.createStyleSheet();
	}

// standards compliant browsers
	else {
		this.styleElement = contextObj.createElement('style');
		this.styleElement.from = 'text/css';
		var insert = contextObj.getElementsByTagName('head')[0];
		if (insert != null) {
			this.styleElement.appendChild(contextObj.createTextNode('')); // Safari 3 fix
			insert.appendChild(this.styleElement);
		}
	}

//
// WikEdStyleSheet.WikEdAddRule: add one rule at the time using DOM method, very slow
//

	this.WikEdAddRule = function(selector, declaration) {

// MS IE compatibility
		if (this.styleElement.WikEdAddRule != null) {
			if (declaration.length > 0) {
				this.styleElement.WikEdAddRule(selector, declaration);
			}
		}

// standards compliant browsers
		else {
			if (this.styleElement.sheet != null) {
				if (this.styleElement.sheet.insertRule != null) {
					this.styleElement.sheet.insertRule(selector + ' { ' + declaration + ' } ', 0);
				}
			}
		}
	};


//
// WikEdStyleSheet.WikEdAddRules: add or replace all rules at once, much faster
//

	this.WikEdAddRules = function(rules) {

// MS IE compatibility
		if (this.styleElement.innerHTML == null) {
			this.styleElement.cssText = rules;
		}

// Safari, Chrome, WebKit
		else if ( (wikEdSafari == true) || (wikEdChrome == true) || (wikEdWebKit == true) ) {
			if (this.styleElement.firstChild != null) {
				this.styleElement.removeChild(this.styleElement.firstChild);
			}
			this.styleElement.appendChild(contextObj.createTextNode(rules));
		}

// via innerHTML
		else {
			this.styleElement.innerHTML = rules;
		}
		return;
	};
};


//
// WikEdGetStyle: get computed style properties for non-inline css definitions
//

window.WikEdGetStyle = function(element, styleProperty) {

	var styleDocument = element.ownerDocument;

	var style;
	if (element != null) {
		if ( (styleDocument.defaultView != null) && (styleDocument.defaultView.getComputedStyle != null) ) {
			style = styleDocument.defaultView.getComputedStyle(element, null)[styleProperty];
		}

// MS IE compatibility
		else if (element.currentStyle != null) {
			style = element.currentStyle[styleProperty];

// recurse up trough the DOM tree
			if (style == 'inherit') {
				style = WikEdGetStyle(element.parentNode, styleProperty);
			}
		}
		else {
			style = element.style[styleProperty];
		}
	}
	return(style);
};


//
// WikEdAjaxPreview: get rendered page text using an Ajax non-api POST call
//

window.WikEdAjaxPreview = function(postData, ResponseHandler, livePreview) {

// prepare the request
	var boundary = '--(fR*3briuStOum6#v)--';
	postData = '--' + boundary + '\nContent-Disposition: form-data; name="wpTextbox1"\n\n' + postData + '\n--' + boundary;

	var formAction;
	if ( (wikEdUploadEdit == true) || (wikEdWatchlistEdit == true) ) {
		formAction = wikEdWikiGlobals['wgServer'] + wikEdWikiGlobals['wgScript'] + '?title=wikEdPreview&action=submit';
	}
	else {
		formAction = wikEdEditForm.action;
	}
	if (wikEdEditForm.wpEdittime != null) {
		formAction += '&wpEdittime=' + wikEdEditForm.wpEdittime.value;
	}
	if (wikEdEditForm.wpEditToken != null) {
		formAction += '&wpEditToken=' + encodeURIComponent(wikEdEditForm.wpEditToken.value);
	}
	formAction += '&wpPreview=true';
	if (livePreview != false) {
		formAction += '&live';
	}

// make the ajax request
	WikEdAjaxRequest('POST', formAction, 'Content-Type', 'multipart/form-data; boundary=' + boundary, postData, 'text/html', ResponseHandler);

	return;
};



//
// WikEdAjaxRequest: cross browser wrapper for Ajax requests (code copied to wikEdDiff.js)
//

window.WikEdAjaxRequest = function(requestMethod, requestUrl, headerName, headerValue, bodyData, overrideMimeType, responseHandler) {

	var request;

// use Greasemonkey GM_xmlhttpRequest
	if (wikEdGreasemonkey == true) {

		var headerArray = { 'User-Agent': navigator.userAgent };
		if (headerName != null) {
			headerArray[headerName] = headerValue;
		}
		request = new GM_xmlhttpRequest({
			'method':  requestMethod,
			'url':     requestUrl,
			'headers': headerArray,
			'data':    bodyData,
			'onreadystatechange':
				function(ajax) {
					if (ajax.readyState != 4) {
						return;
					}
					responseHandler(ajax);
					return;
				}
		});
	}

// use standard XMLHttpRequest
	else {

// allow ajax request from local copy for testing
		if (wikEdAllowLocalAjax == true) {
			if (typeof(netscape) == 'object') {
				netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
			}
		}

// new ajax request object
		try {
			request = new XMLHttpRequest();
		}

// IE 6
		catch(err) {
			try {
				request = new ActiveXObject('Microsoft.XMLHTTP');
			}

// IE 5.5
			catch(err) {
				try {
					request = new ActiveXObject('Msxml2.XMLHTTP');
				}
				catch(err) {
					return;
				}
			}
		}
		request.open(requestMethod, requestUrl, true);
		if (headerName != null) {
			request.setRequestHeader(headerName, headerValue);
		}
		if ( (request.overrideMimeType != null) && (overrideMimeType != null) ) {
			request.overrideMimeType(overrideMimeType);
		}

// catch security violations Opera 0.9.51
		try {
			request.send(bodyData);
		}
		catch(err) {
			return;
		}

		request.onreadystatechange = function() {
			if (request.readyState != 4) {
				return;
			}
			responseHandler(request);
			return;
		};
	}
	return;
};


//
// WikEdGetGlobal: access values of global variables from Greasemonkey scripts using the 'location hack' (code copied to wikEdDiff.js)
//

window.WikEdGetGlobal = function(globalName) {
	var globalValue;
	if (wikEdGreasemonkey == true) {
		if (wikEdGetGlobalNode == null) {
			wikEdGetGlobalNode = document.getElementById('wikEdGetGlobalNode');
		}
		if (wikEdGetGlobalNode == null) {
			wikEdGetGlobalNode = document.createElement('textarea');
			wikEdGetGlobalNode.id = 'wikEdGetGlobalNode';
			wikEdGetGlobalNode.style.display = 'none';
			wikEdGetGlobalNode.style.visibility = 'hidden';
			document.body.appendChild(wikEdGetGlobalNode);
		}
		location.href = 'javascript:void(typeof(' + globalName + ')!=\'undefined\'?(' + globalName + '!=null?(document.getElementById(\'wikEdGetGlobalNode\').value=' + globalName + '.toString()):null):null)';
		globalValue = wikEdGetGlobalNode.value;
	}
	else {
		try {
			globalValue = eval(globalName + '.toString();');
		}
		catch(err) { }
	}
	return(globalValue);
};


//
// WikEdGetAttribute: MS IE compatibility wrapper for element.getAttribute()
//

window.WikEdGetAttribute = function(element, attribName) {

	var attribValue = element.getAttribute(attribName);

// MS IE compatibility for checked
	if (attribName == 'checked') {
		if ( typeof(attribValue) == 'boolean' ) {
			if (attribValue == true) {
				attribValue = 'true';
			}
			else {
				attribValue = 'false';
			}
		}
	}
	return(attribValue);
};


//
// WikEdGetWindowInnerHeight: MS IE compatibility wrapper for window.innerHeight
//

window.WikEdGetWindowInnerHeight = function() {

	var value = window.innerHeight;
	if (value == null) {
		if (document.documentElement != null) {
			value = document.documentElement.clientHeight;
		}
		if ( (value == null) || (value == 0) ) {
			value = document.body.clientHeight;
		}
	}
	return(value);
};


//
// WikEdGetWindowInnerWidth: MS IE compatibility wrapper for window.innerWidth
//

window.WikEdGetWindowInnerWidth = function() {

	var value = window.innerWidth;
	if (value == null) {
		if (document.documentElement != null) {
			value = document.documentElement.clientWidth;
		}
		if ( (value == null) || (value == 0) ) {
			value = document.body.clientWidth;
		}
	}
	return(value);
};


//
// WikEdAddEventListener: wrapper for addEventListener (http://ejohn.org/projects/flexible-javascript-events/)
//

window.WikEdAddEventListener = function(domElement, eventType, eventHandler, useCapture) {

	if (domElement != null) {
		if (typeof(domElement.addEventListener) == 'function') {
			domElement.addEventListener(eventType, eventHandler, useCapture);
		}
		else {
			domElement['wikEd' + eventType + eventHandler] = eventHandler;
			domElement[eventType + eventHandler] = function() {
				var eventRootElement = document;
				if (document.addEventListener == null) {
					eventRootElement = window;
				}
				domElement['wikEd' + eventType + eventHandler](eventRootElement.event);
			};
			domElement.attachEvent('on' + eventType, domElement[eventType + eventHandler] );
		}
	}
	return;
};


//
// WikEdRemoveEventListener: wrapper for removeEventListener
//

window.WikEdRemoveEventListener = function(domElement, eventType, eventHandler, useCapture) {

	if (typeof(domElement.removeEventListener) == 'function') {
		domElement.removeEventListener(eventType, eventHandler, useCapture);
	}
	else {
		domElement.detachEvent('on' + eventType, domElement[eventType + eventHandler]);
		domElement[eventType + eventHandler] = null;
	}
	return;
};


//
// WikEdEvent: MS IE and Mozilla compatibility fix for event object
//

window.WikEdEvent = function(event, thisElement) {

	var eventAlt;
	if (window.event != null) {
		eventAlt = window.event;
	}
	else if (wikEdFrameWindow != null) {
		if (typeof(wikEdFrameWindow.event) != 'undefined') {
			eventAlt = wikEdFrameWindow.event;
		}
	}
	if (eventAlt != null) {
		event = eventAlt;
		event.stopPropagation = function() {
			event.cancelBubble = true;
		};
		event.preventDefault = function() {
			event.returnValue = false;
		};
		if (event.target == null) {
			event.target = event.srcElement;
		}
		if (event.currentTarget == null) {
			event.currentTarget = thisElement;
		}
		if (event.relatedTarget == null) {
			if (event.type == 'mouseout') {
				event.relatedTarget = event.toElement;
			}
			else if (event.type == 'mouseover') {
				event.relatedTarget = event.fromElement;
			}
		}
	}

// avoid strange Mozilla security error https://bugzilla.mozilla.org/show_bug.cgi?id=101197, fixed in FF3.6
	if ( (event.type == 'mouseout') || (event.type == 'mouseover') ) {
		event.safeRelatedTarget = event.relatedTarget;
		if (wikEdMozilla == true) {
			try {
				event.safeRelatedTarget.toString();
			}
			catch(error) {
				event.safeRelatedTarget = null;
			}
		}
	}
	return(event);
};


//
// WikEdGetElementsByClassName: cross browser / backwards compatibility wrapper
//

window.WikEdGetElementsByClassName = function(className, tagName, parent) {

	if (parent == null) {
		parent = document.body;
	}

// new browsers
	var nodeList = [];
	if (document.getElementsByClassName != null) {
		nodeList = parent.getElementsByClassName(className);
	}

// querySelector (IE8 standards mode)
	else if (parent.querySelectorAll != null) {
		nodeList = parent.querySelectorAll(tagName + '.' + className);
	}

// all others (slow)
	else {
		var tags = parent.getElementsByTagName(tagName);
		for (var i = 0; i < tags.length; i ++) {
			if (tags[i].className == className) {
				nodeList.push(tags[i]);
			}
		}
	}
	return(nodeList);
};


//
// WikEdGetPreviousSiblingNode: getPreviousSibling, ignore non-element nodes such as comments
//

window.WikEdGetPreviousSiblingNode = function(node) {

	while (node != null) {
		node = node.previousSibling;
		if (node == null) {
			break;
		}
		if (node.nodeType == node.ELEMENT_NODE) {
			break;
		}
	}
	return(node);
};


//
// WikEdGetNextSiblingNode: getNextSibling, ignore non-element nodes such as comments
//

window.WikEdGetNextSiblingNode = function(node) {

	while (node != null) {
		node = node.nextSibling;
		if (node == null) {
			break;
		}
		if (node.nodeType == node.ELEMENT_NODE) {
			break;
		}
	}
	return(node);
}


//
// WikEdGetFirstChildNode: getFirstChild, ignore non-element nodes such as comments
//

window.WikEdGetFirstChildNode = function(node) {

	if (node != null) {
		node = node.firstChild;
		WikEdGetNextSiblingNode(node);
	}
	return(node);
};


//
// WikEdGetLastChildNode: getLastChild, ignore non-element nodes such as comments
//

window.WikEdGetLastChildNode = function(node) {

	if (node != null) {
		node = node.lastChild;
		WikEdGetPreviousSiblingNode(node);
		return(node);
	}
};


//
// WikEdDebug: print the value of variables
//   use either a single value or a description followed by a value
//   popup = true: use alert popup if debug textarea is not yet setup
//

window.WikEdDebug = function(objectName, object, usePopup) {

// string
	var value = '';
	if (typeof(object) == 'string') {
		value = ': ' + '"' + object + '"';
	}

// objects
	else if (typeof(object) == 'object') {

// null
		if (object == null) {
			value = ': [null]';
		}

// whole parse tree array
// { 'tag': , 'parent': , 'firstChild': , 'nextSibling': , 'start': , 'tagLength': , 'type': , 'pairedTagPos': , 'left': , 'right': , 'index': }
		else if ( (typeof(object[0]) == 'object') && (typeof(object[0].type) == 'string') ) {
			value = ': Parse tree full:\n';
			for (var i = 0; i < object.length; i ++) {
				value += i + ': ';
				var node = object[i];
				if (node == null) {
					value += '(null)\n';
				}
				else {
					if (node.type == 'root') {
						value += '[type: "' + node.type + '"]\n';
					}
					else {
						value += '[type: "' + node.type + '", tag: "' + node.tag + '", start: ' + node.start + ', tagLength: ' + node.tagLength + ', parent: ' + node.parent;
						if (typeof(node.left) == 'string') {
							value += ', left: "' + node.left + '", right: "' + node.right + '"';
						}
						value += '],\n';
					}
				}
			}
		}

// whole parse tree up
		else if ( (typeof(object.tree) == 'object') && (typeof(object.lastOpenNode) == 'number') ) {
			value = ': Parse tree upwards:\n';
			var parseTreeIndex = object.lastOpenNode;
			var node = object.tree[parseTreeIndex];
			while (node != null) {
				if (node.type == 'root') {
					value += parseTreeIndex + ': [type: "' + node.type + '"]\n';
				}
				else {
					value += parseTreeIndex + ': [type: "' + node.type + '", tag: "' + node.tag + '", start: ' + node.start + ', tagLength: ' + node.tagLength + ', parent: ' + node.parent;
					if (typeof(node.left) == 'string') {
						value += ', left: "' + node.left + '", right: "' + node.right + '"';
					}
					value += '],\n';
				}
				if (node.parent == parseTreeIndex) {
					value += '(circular reference, break)';
					break;
				}
				parseTreeIndex = node.parent;
				node = object.tree[node.parent];
			}
		}

// parse tree node
// { 'tag': , 'parent': , 'firstChild': , 'nextSibling': , 'start': , 'tagLength': , 'type': , 'pairedTagPos': , 'left': , 'right': , 'index': }
		else if (typeof(object.type) == 'string') {
			var node = object;
			if (node.type == 'root') {
				value = ': [type: "' + node.type + '"]';
			}
			else {
				value = ': [tag: "' + node.tag + '", type: "' + node.type + '", start: ' + node.start + ', tagLength: ' + node.tagLength + ', parent: ' + node.parent + ']';
			}
		}

// DOM nodes
		else if (typeof(object.nodeName) == 'string') {
			value = ': [node; nodeName: ' + object.nodeName;
			if (typeof(object.id) == 'string') {
				if (object.id != '') {
					value += ', id: "' + object.id + '"';
				}
			}
			if (typeof(object.className) == 'string') {
				if (object.className != '') {
					value += ', class: "' + object.className + '"';
				}
			}
			if (typeof(object.nodeValue) == 'string') {
				value += ', nodeValue: "' + object.nodeValue + '"';
			}
			if ( (object.innerHTML != null) && (object.innerHTML != '') ) {
				var html = object.innerHTML;
				if (html.length > wikEdDebugInnerHtmlLength) {
					html = html.substr(0, wikEdDebugInnerHtmlLength - 3) + '...';
				}
				value += ', innerHTML: "' + html + '"';
			}
			value += ']';
		}

// default
		else {
			value = ': [' + object + ']';
		}
	}

// undefined
	else if (typeof(object) == 'undefined') {
		value = '';
	}

// default
	else {
		value = ': ' + object;
	}

// use debug textarea
	var useDebug = false;
	if (typeof(wikEdDebug) != 'undefined') {
		if (wikEdDebug != null) {
			useDebug = true;
		}
	}
	if (useDebug == true) {
		if (wikEdDebugOpen == false) {
			wikEdDebugWrapper.style.visibility = 'visible';
			wikEdDebug.style.display = 'block';
			window.scroll(0, WikEdGetOffsetTop(wikEdDebug));
			wikEdDebugOpen = true;
		}
		if (objectName == null) {
			wikEdDebug.value = '';
		}
		else {

// cut text if having reached maximum length
			value = objectName + value + '\n';
			if (wikEdDebug.value.length > wikEdDebugMaxLength) {
				wikEdDebug.value = value + wikEdDebug.value.substr(0, wikEdDebugMaxLength * 2 / 3);
			}
			else {
				wikEdDebug.value = value + wikEdDebug.value;
			}
		}
	}

// use popup alert
	else if (usePopup == true) {
		if (object == null) {
			alert(objectName);
		}
		else {
			alert(objectName + ': ' + value);
		}
	}

// use error console
	else {
		var msg;
		if (object == null) {
			msg = objectName + '';
		}
		else {
			msg = objectName + ': ' + value;
		}
		msg = msg.replace(/\'/g, '\\\'');
		setTimeout('throw new Error(\'WikEdDebug: ' + msg + '\')', 0);
	}
	return;
};


//
// WikEdDebugTimer: show all measured timepoints
//   add a new time measurement: wikEdDebugTimer.push([1234, new Date]);

window.WikEdDebugTimer = function() {
	var times = '';
	var start = wikEdDebugTimer[0][1].getTime();
	var prev = 0;
	for (var i = 0; i < wikEdDebugTimer.length; i ++) {
		var curr = wikEdDebugTimer[i][1].getTime() - start;
		var diff = curr - prev;
		prev = curr;
		times += wikEdDebugTimer[i][0] + ': ' + curr + ' ms (+ ' + diff + ' ms)\n';
	}
	WikEdDebug(times);
	wikEdDebugTimer = [];
};


//
// WikEdInsertTags: overrides the insertTags function in wikibits.js used by the standard button toolbar and the editpage special chars
//

window.WikEdInsertTags = function(openTag, closeTag, sampleText) {

	if (wikEdUseWikEd == true) {
		WikEdEditButton(document.getElementById('wikEdInsertTags'), 'wikEdInsertTags', [openTag, closeTag, sampleText]);
	}
	else if (WikEdInsertTagsOriginal != null) {
		WikEdInsertTagsOriginal(openTag, closeTag, sampleText);
	}
	return;
};


//
// WikEdInsertAtCursor: overrides the insertAtCursor function in wikia.com MediaWiki:Functions.js
//

window.WikEdInsertAtCursor = function(myField, myValue) {

	if (wikEdUseWikEd == true) {
		if (myField == wikEdTextarea) {
			WikEdEditButton(document.getElementById('wikEdInsertTags'), 'wikEdInsertTags', [ myValue ]);
		}
	}
	else if (WikEdInsertAtCursorOriginal != null) {
		WikEdInsertAtCursorOriginal(myField, myValue);
	}
	return;
};


//
// WikEdExecuteHook: executes scheduled custom functions from functionsHook array
//

window.WikEdExecuteHook = function(functionsHook) {

	for (var i = 0; i < functionsHook.length; i++) {
		functionsHook[i]();
	}
	return;
};


//
// WikEdInitUnicode: define character tables used in WikedFixUnicode()
//   see http://kmi.open.ac.uk/projects/ceryle/doc/docs/NOTE-charents.html

window.WikEdInitUnicode = function() {

// define only once
	if (wikEdSupportedChars != null) {
		return;
	}

// supported chars in Mozilla and IE
	wikEdSupportedChars = [
		[  'a1', 'iexcl'],  // ¡
		[  'a2', 'cent'],   // ¢
		[  'a3', 'pound'],  // £
		[  'a4', 'curren'], // ¤
		[  'a5', 'yen'],    // ¥
		[  'a6', 'brvbar'], // ¦
		[  'a7', 'sect'],   // §
		[  'a8', 'uml'],    // ¨
		[  'a9', 'copy'],   // ©
		[  'aa', 'ordf'],   // ª
		[  'ab', 'laquo'],  // «
		[  'ac', 'not'],    // ¬
		[  'ae', 'reg'],    // ®
		[  'af', 'macr'],   // ¯
		[  'b0', 'deg'],    // °
		[  'b1', 'plusmn'], // ±
		[  'b2', 'sup2'],   // ²
		[  'b3', 'sup3'],   // ³
		[  'b4', 'acute'],  // ´
		[  'b5', 'micro'],  // µ
		[  'b6', 'para'],   // ¶
		[  'b7', 'middot'], // ·
		[  'b8', 'cedil'],  // ¸
		[  'b9', 'sup1'],   // ¹
		[  'ba', 'ordm'],   // º
		[  'bb', 'raquo'],  // »
		[  'bc', 'frac14'], // ¼
		[  'bd', 'frac12'], // ½
		[  'be', 'frac34'], // ¾
		[  'bf', 'iquest'], // ¿
		[  'c0', 'Agrave'], // À
		[  'c1', 'Aacute'], // Á
		[  'c2', 'Acirc'],  // Â
		[  'c3', 'Atilde'], // Ã
		[  'c4', 'Auml'],   // Ä
		[  'c5', 'Aring'],  // Å
		[  'c6', 'AElig'],  // Æ
		[  'c7', 'Ccedil'], // Ç
		[  'c8', 'Egrave'], // È
		[  'c9', 'Eacute'], // É
		[  'ca', 'Ecirc'],  // Ê
		[  'cb', 'Euml'],   // Ë
		[  'cc', 'Igrave'], // Ì
		[  'cd', 'Iacute'], // Í
		[  'ce', 'Icirc'],  // Î
		[  'cf', 'Iuml'],   // Ï
		[  'd0', 'ETH'],    // Ð
		[  'd1', 'Ntilde'], // Ñ
		[  'd2', 'Ograve'], // Ò
		[  'd3', 'Oacute'], // Ó
		[  'd4', 'Ocirc'],  // Ô
		[  'd5', 'Otilde'], // Õ
		[  'd6', 'Ouml'],   // Ö
		[  'd7', 'times'],  // ×
		[  'd8', 'Oslash'], // Ø
		[  'd9', 'Ugrave'], // Ù
		[  'da', 'Uacute'], // Ú
		[  'db', 'Ucirc'],  // Û
		[  'dc', 'Uuml'],   // Ü
		[  'dd', 'Yacute'], // Ý
		[  'de', 'THORN'],  // Þ
		[  'df', 'szlig'],  // ß
		[  'e0', 'agrave'], // à
		[  'e1', 'aacute'], // á
		[  'e2', 'acirc'],  // â
		[  'e3', 'atilde'], // ã
		[  'e4', 'auml'],   // ä
		[  'e5', 'aring'],  // å
		[  'e6', 'aelig'],  // æ
		[  'e7', 'ccedil'], // ç
		[  'e8', 'egrave'], // è
		[  'e9', 'eacute'], // é
		[  'ea', 'ecirc'],  // ê
		[  'eb', 'euml'],   // ë
		[  'ec', 'igrave'], // ì
		[  'ed', 'iacute'], // í
		[  'ee', 'icirc'],  // î
		[  'ef', 'iuml'],   // ï
		[  'f0', 'eth'],    // ð
		[  'f1', 'ntilde'], // ñ
		[  'f2', 'ograve'], // ò
		[  'f3', 'oacute'], // ó
		[  'f4', 'ocirc'],  // ô
		[  'f5', 'otilde'], // õ
		[  'f6', 'ouml'],   // ö
		[  'f7', 'divide'], // ÷
		[  'f8', 'oslash'], // ø
		[  'f9', 'ugrave'], // ù
		[  'fa', 'uacute'], // ú
		[  'fb', 'ucirc'],  // û
		[  'fc', 'uuml'],   // ü
		[  'fd', 'yacute'], // ý
		[  'fe', 'thorn'],  // þ
		[  'ff', 'yuml'],   // ÿ
		[  '27', 'apos'],   // '
		[  '22', 'quot'],   // "
		[ '152', 'OElig'],  // Œ
		[ '153', 'oelig'],  // œ
		[ '160', 'Scaron'], // Š
		[ '161', 'scaron'], // š
		[ '178', 'Yuml'],   // Ÿ
		[ '2c6', 'circ'],   // ˆ
		[ '2dc', 'tilde'],  // ˜
		['2013', 'ndash'],  // –
		['2014', 'mdash'],  // —
		['2018', 'lsquo'],  // ‘
		['2019', 'rsquo'],  // ’
		['201a', 'sbquo'],  // ‚
		['201c', 'ldquo'],  // “
		['201d', 'rdquo'],  // ”
		['201e', 'bdquo'],  // „
		['2020', 'dagger'], // †
		['2021', 'Dagger'], // ‡
		['2030', 'permil'], // ‰
		['2039', 'lsaquo'], // ‹
		['203a', 'rsaquo'], // ›
		['20ac', 'euro'],   // €
		[ '192', 'fnof'],   // ƒ
		[ '391', 'Alpha'],  // Α
		[ '392', 'Beta'],   // Β
		[ '393', 'Gamma'],  // Γ
		[ '394', 'Delta'],  // Δ
		[ '395', 'Epsilon'],// Ε
		[ '396', 'Zeta'],   // Ζ
		[ '397', 'Eta'],    // Η
		[ '398', 'Theta'],  // Θ
		[ '399', 'Iota'],   // Ι
		[ '39a', 'Kappa'],  // Κ
		[ '39b', 'Lambda'], // Λ
		[ '39c', 'Mu'],     // Μ
		[ '39d', 'Nu'],     // Ν
		[ '39e', 'Xi'],     // Ξ
		[ '39f', 'Omicron'],// Ο
		[ '3a0', 'Pi'],     // Π
		[ '3a1', 'Rho'],    // Ρ
		[ '3a3', 'Sigma'],  // Σ
		[ '3a4', 'Tau'],    // Τ
		[ '3a5', 'Upsilon'],// Υ
		[ '3a6', 'Phi'],    // Φ
		[ '3a7', 'Chi'],    // Χ
		[ '3a8', 'Psi'],    // Ψ
		[ '3a9', 'Omega'],  // Ω
		[ '3b1', 'alpha'],  // α
		[ '3b2', 'beta'],   // β
		[ '3b3', 'gamma'],  // γ
		[ '3b4', 'delta'],  // δ
		[ '3b5', 'epsilon'],// ε
		[ '3b6', 'zeta'],   // ζ
		[ '3b7', 'eta'],    // η
		[ '3b8', 'theta'],  // θ
		[ '3b9', 'iota'],   // ι
		[ '3ba', 'kappa'],  // κ
		[ '3bb', 'lambda'], // λ
		[ '3bc', 'mu'],     // μ
		[ '3bd', 'nu'],     // ν
		[ '3be', 'xi'],     // ξ
		[ '3bf', 'omicron'],// ο
		[ '3c0', 'pi'],     // π
		[ '3c1', 'rho'],    // ρ
		[ '3c2', 'sigmaf'], // ς
		[ '3c3', 'sigma'],  // σ
		[ '3c4', 'tau'],    // τ
		[ '3c5', 'upsilon'],// υ
		[ '3c6', 'phi'],    // φ
		[ '3c7', 'chi'],    // χ
		[ '3c8', 'psi'],    // ψ
		[ '3c9', 'omega'],  // ω
		['2022', 'bull'],   // •
		['2026', 'hellip'], // …
		['2032', 'prime'],  // ′
		['2033', 'Prime'],  // ″
		['203e', 'oline'],  // ‾
		['2044', 'frasl'],  // ⁄
		['2122', 'trade'],  // ™
		['2190', 'larr'],   // ←
		['2191', 'uarr'],   // ↑
		['2192', 'rarr'],   // →
		['2193', 'darr'],   // ↓
		['2194', 'harr'],   // ↔
		['21d2', 'rArr'],   // ⇒
		['21d4', 'hArr'],   // ⇔
		['2200', 'forall'], // ∀
		['2202', 'part'],   // ∂
		['2203', 'exist'],  // ∃
		['2207', 'nabla'],  // ∇
		['2208', 'isin'],   // ∈
		['220b', 'ni'],     // ∋
		['220f', 'prod'],   // ∏
		['2211', 'sum'],    // ∑
		['2212', 'minus'],  // −
		['221a', 'radic'],  // √
		['221d', 'prop'],   // ∝
		['221e', 'infin'],  // ∞
		['2220', 'ang'],    // ∠
		['2227', 'and'],    // ∧
		['2228', 'or'],     // ∨
		['2229', 'cap'],    // ∩
		['222a', 'cup'],    // ∪
		['222b', 'int'],    // ∫
		['2234', 'there4'], // ∴
		['223c', 'sim'],    // ∼
		['2248', 'asymp'],  // ≈
		['2260', 'ne'],     // ≠
		['2261', 'equiv'],  // ≡
		['2264', 'le'],     // ≤
		['2265', 'ge'],     // ≥
		['2282', 'sub'],    // ⊂
		['2283', 'sup'],    // ⊃
		['2286', 'sube'],   // ⊆
		['2287', 'supe'],   // ⊇
		['2295', 'oplus'],  // ⊕
		['25ca', 'loz'],    // ◊
		['2660', 'spades'], // ♠
		['2663', 'clubs'],  // ♣
		['2665', 'hearts'], // ♥
		['2666', 'diams']   // ♦
	];

// reserved for internal wikEd use
	wikEdReservedChars = [
		[  '26', 'amp'],    // &
		[  '3c', 'lt'],     // <
		[  '3e', 'gt'],     // >
		[  'a0', 'nbsp']    //
	];

// special chars (spaces and invisible characters)
	wikEdSpecialChars = [
		['2002', 'ensp'],   //   en space
		[  'ad', 'shy'],    // ­ soft hyphen
		['2003', 'emsp'],   //   em space
		['2009', 'thinsp'], //   thin space
		['200c', 'zwnj'],   // ‌ zero width non-joiner
		['200d', 'zwj'],    // ‍ zero width joiner
		['200e', 'lrm'],    // ‎ left-to-right mark
		['200f', 'rlm']     // ‏ right-to-left mark
	];

// unsupported chars in IE6
	wikEdProblemChars = [
		[ '3d1', 'thetasym'], // ϑ
		[ '3d2', 'upsih'],    // ϒ
		[ '3d6', 'piv'],      // ϖ
		['2118', 'weierp'],   // ℘
		['2111', 'image'],    // ℑ
		['211c', 'real'],     // ℜ
		['2135', 'alefsym'],  // ℵ
		['21b5', 'crarr'],    // ↵
		['21d0', 'lArr'],     // ⇐
		['21d1', 'uArr'],     // ⇑
		['21d3', 'dArr'],     // ⇓
		['2205', 'empty'],    // ∅
		['2209', 'notin'],    // ∉
		['2217', 'lowast'],   // ∗
		['2245', 'cong'],     // ≅
		['2284', 'nsub'],     // ⊄
		['22a5', 'perp'],     // ⊥
		['2297', 'otimes'],   // ⊗
		['22c5', 'sdot'],     // ⋅
		['2308', 'lceil'],    // ⌈
		['2309', 'rceil'],    // ⌉
		['230a', 'lfloor'],   // ⌊
		['230b', 'rfloor'],   // ⌋
		['2329', 'lang'],     // 〈
		['232a', 'rang']      // 〉
	];


// index to all existing 253 HTML/XHTML character entities
	var allCharEntities = wikEdSupportedChars.concat(wikEdReservedChars, wikEdSpecialChars, wikEdProblemChars);
	for (var i = 0; i < allCharEntities.length; i ++) {
		wikEdCharEntitiesByName[ allCharEntities[i][1] ] = String.fromCharCode(parseInt(allCharEntities[i][0], 16));
	}

// syntax highlighting of ASCII control characters and invisibles (decimal value, title)
	wikEdControlCharHighlighting = {
		'0': 'null',
		'1': 'start of heading',
		'2': 'start of text',
		'3': 'end of text',
		'4': 'end of transmission',
		'5': 'enquiry',
		'6': 'acknowledge',
		'7': 'bell',
		'8': 'backspace',
		'11': 'vertical tab',
		'12': 'form feed, new page',
		'14': 'shift out',
		'15': 'shift in',
		'16': 'data link escape',
		'17': 'device control 1',
		'18': 'device control 2',
		'19': 'device control 3',
		'20': 'device control 4',
		'21': 'negative acknowledge',
		'22': 'synchronous idle',
		'23': 'end of trans. block',
		'24': 'cancel',
		'25': 'end of medium',
		'26': 'substitute',
		'27': 'escape',
		'28': 'file separator',
		'29': 'group separator',
		'30': 'record separator',
		'31': 'unit separator',
		'8204': 'zero width non-joiner', // \u200c
		'8205': 'zero width joiner',     // \u200d
		'8206': 'left-to-right mark',    // \u200e
		'8207': 'right-to-left mark',    // \u200f
		'8232': 'line separator',        // \u2028
		'8233': 'paragraph separator'    // \u2028
	};
	for (var decimalValue in wikEdControlCharHighlighting) {
		if (wikEdControlCharHighlighting.hasOwnProperty(decimalValue) == true) {
			wikEdControlCharHighlightingStr += '\\' + String.fromCharCode(decimalValue);
		}
	}

// character syntax highlighting: strange spaces, hyphens, and dashes (decimal value, class = title)
	wikEdCharHighlighting = {
		'9':     'wikEdTab',        // \u0009 '	'
		'8194':  'wikEdEnSpace',    // \u2002 ' '
		'8195':  'wikEdEmSpace',    // \u2003 ' '
		'8201':  'wikEdThinSpace',  // \u2009 ' '
		'12288': 'wikEdIdeographicSpace', // \u3000 '　'
		'45':    'wikEdHyphenDash', // \u00a0 '-'
		'173':   'wikEdSoftHyphen', // \u00ad '­'
		'8210':  'wikEdFigureDash', // \u2012 '‒'
		'8211':  'wikEdEnDash',     // \u2013 '–'
		'8212':  'wikEdEmDash',     // \u2014 '—'
		'8213':  'wikEdBarDash',    // \u2015 '―'
		'8722':  'wikEdMinusDash'   // \u2212 '−'
	};
	for (var decimalValue in wikEdCharHighlighting) {
		if (wikEdCharHighlighting.hasOwnProperty(decimalValue) == true) {
			wikEdCharHighlightingStr += '\\' + String.fromCharCode(decimalValue);
		}
	}

	return;
};

// call wikEd startup
WikEdStartup();

// </nowiki></pre>