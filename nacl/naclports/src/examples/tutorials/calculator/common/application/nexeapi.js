// Copyright 2010 The Native Client SDK Authors.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

/**
 * @fileoverview Loads either a .nexe when published on the web, or a develop
 * version when debugging.  The develop version of the Native Client module is
 * loaded when the URL ends with a location of '#develop'.
 */

var google = google || {};
google.nexe = {};
google.load = function() {};

/**
 * A class for loading and containing nexes.
 * @param {string} url The URL to load the nexe from.
 * @param {Object} opt_settings Optional settings hash.
 * @constructor
 */
google.nexe.Nexe = function(url, opt_settings) {
  var settings = opt_settings || {};
  this.onLoadProgressCallback_ = settings.onloadprogress;
  this.loadPercent_ = 0;
}

/**
 * The native module for the application.  This refers to the module loaded via
 * the <embed> tag.
 * @type {Element}
 * @private
 */
google.nexe.Nexe.prototype.module_ = null;

/**
 * Called by the module loading function once the module has been loaded.
 * @param {?Element} opt_nativeModule The instance of the native module.
 */
google.nexe.Nexe.prototype.moduleDidLoad = function(opt_nativeModule) {
  this.module_ = opt_nativeModule || {};
  // Signal that the module is all loaded.
  if (this.onLoadProgressCallback_) {
    this.onLoadProgressCallback_(100);
  }
}

/**
 * The run() method starts and 'runs' the application.  Throws an error
 * if any of the required DOM elements are missing.
 * @param {Object} opt_settings Optional settings hash.
 */
google.nexe.Nexe.prototype.run = function(opt_settings) {
  var settings = opt_settings || {};
  var contentDivName = settings.contentDivName || google.nexe.Defaults.DIV_NAME;
  var contentDiv = document.getElementById(contentDivName);
  if (!contentDiv) {
    alert('missing ' + contentDivName);
    throw new Error('Application.run(): missing element ' + "'" +
        contentDivName + "'");
  }
  // Conditionally load the develop or publish version of the module.
  var nexeModuleId = settings.nexeModuleId || google.nexe.Defaults.MODULE_ID;
  var width = (settings.width == undefined ? google.nexe.Defaults.WIDTH :
      settings.width);
  var height = (settings.height == undefined ? google.nexe.Defaults.HEIGHT :
      settings.height);
  var nexeModules = settings.nexeModules || google.nexe.Defaults.NEXE_MODULES;
  var embedHTML = '<embed id="' + nexeModuleId + '" '
                  + 'type="application/x-nacl-srpc" '
                  + 'width="' + width + '" '
                  + 'height="' + height + '" ';
  if (settings.dimensions) {
    embedHTML += 'dimensions="' + settings.dimensions + '" ';
  }
  if (settings.onload) {
    embedHTML += 'onload="' + settings.onload + '" ';
  }
  embedHTML += '/>';
  contentDiv.innerHTML = embedHTML;
  // Note: this code is here to work around a bug in Chromium build
  // #47357.  See also
  // http://code.google.com/p/nativeclient/issues/detail?id=500
  document.getElementById(nexeModuleId).nexes = nexeModules;
}

/**
 * Accessor for the internal module object.
 * @return {Object} The Native Client module object.
 */
google.nexe.Nexe.prototype.module = function() {
  return this.module_;
}

/**
 * Various default values for application settings.
 */
google.nexe.Defaults = {
  // A table of .nexe modules to load.
  NEXE_MODULES: 'x86-32: nexe_x86_32.nexe\nx86-64: nexe_x86_64.nexe',
  MODULE_ID: 'nexe',  // ID of the element containing the module.
  DIV_NAME: 'nexe_content',  // Name of the enclosing <div>.
  WIDTH: 200,  // Width of the element containing the module.
  HEIGHT: 200,  // Height of the element containing the module.
}
