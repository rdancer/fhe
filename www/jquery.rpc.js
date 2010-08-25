

window.jQuery = window.jQuery || {};

jQuery.rpc = function(url, dataType, onLoadCallback, version) {
	return new (function(url, dataType, onLoadCallback, version) {
		version = version || "1.0";
		dataType = dataType || "json";
		if(dataType != "json" && dataType != "xml") {
			new Error("IllegalArgument: Unsupported data type");
		}
		var _self = this;
		var serializeToXml = function(data) {
			switch (typeof data) {
			case 'boolean':
				return '<boolean>'+ ((data) ? '1' : '0') +'</boolean>';
			case 'number':
				var parsed = parseInt(data);
				if(parsed == data) {
					return '<int>'+ data +'</int>';
				}
				return '<double>'+ data +'</double>';
			case 'string':
				return '<string>'+ data +'</string>';
			case 'object':
				if(data instanceof Date) {
					return '<dateTime.iso8601>'+ data.getFullYear() + data.getMonth() + data.getDate() +'T'+ data.getHours() +':'+ data.getMinutes() +':'+ data.getSeconds() +'</dateTime.iso8601>';
				} else if(data instanceof Array) {
					var ret = '<array><data>'+"\n";
					for (var i=0; i < data.length; i++) {
						ret += '  <value>'+ serializeToXml(data[i]) +"</value>\n";
					}
					ret += '</data></array>';
					return ret;
				} else {
					var ret = '<struct>'+"\n";
					jQuery.each(data, function(key, value) {
						ret += "  <member><name>"+ key +"</name><value>";
						ret += serializeToXml(value) +"</value></member>\n";
					});
					ret += '</struct>';
					return ret;
				}
			}
		}
		var xmlRpc = function(method, params) {
			var ret = '<?xml version="'+version+'"?><methodCall><methodName>'+method+'</methodName><params>';
			for(var i=0; i<params.length; i++) {
				ret += "<param><value>"+serializeToXml(params[i])+"</value></param>";
			}
			ret += "</params></methodCall>";
			return ret;
		}
		var parseXmlValue = function(node) {
			childs = jQuery(node).children();
			for(var i=0; i < childs.length; i++) {
				switch(childs[i].tagName) {
				case 'boolean':
					return (jQuery(childs[i]).text() == 1);
				case 'int':
					return parseInt(jQuery(childs[i]).text());
				case 'double':
					return parseFloat(jQuery(childs[i]).text());
				case "string":
					return jQuery(childs[i]).text();
				case "array":
					var ret = [];
					jQuery("> data > value", childs[i]).each(
						function() {
							ret.push(parseXmlValue(this));
						}
					);
					return ret;
				case "struct":
					var ret = {};
					jQuery("> member", childs[i]).each(
						function() {
							ret[jQuery( "> name", this).text()] = parseXmlValue(jQuery("value", this));
						}
					);
					return ret;
				case "dateTime.iso8601":
					/* TODO: fill me :( */
					return NULL;
				}
			}
		}
		var parseXmlResponse = function(data) {
			var ret = {};
			ret.version = version;
			jQuery("methodResponse params param > value", data).each(
				function(index) {
					ret.result = parseXmlValue(this);
				}
			);
			jQuery("methodResponse fault > value", data).each(
				function(index) {
					ret.error = parseXmlValue(this);
				}
			);
			return ret;
		}
		var rpc_contents = {
			'xml':'text/xml'
			,'json':'application/json'
		};
		var _rpc = function(method, callback) {
			var params = [];
			for (var i=2; i<arguments.length; i++) {
				params.push(arguments[i]);
			}
			// console.log(params);
			var data;
			if(dataType == 'json') {
				data = {"version":version, "method":method, "params":params};
			} else {
				data = xmlRpc(method, params);
			}
			jQuery.ajax({
				"url": url,
				"dataType": dataType,
				"type": "POST",
				"data": data,
				"success": function(inp) {
					var json = inp;
					if(dataType == "xml") {
						json = parseXmlResponse(inp);
					}
					// console.log("json response:", json);
					callback(json);
				},
				"processData": false,
				"contentType": rpc_contents[dataType]
			});
		};
		_rpc("system.listMethods", 
			function(json) {
				// console.log(json);
				/* get the functions */
				if(!json.result) {
					return;
				}
				var proc = null;
				for(var i = 0; i<json.result.length; i++) {
					proc = json.result[i];
					var obj = _self;
					var objStack = proc.split(/\./);
					for(var j = 0; j < (objStack.length - 1); j++){
						obj[objStack[j]] = obj[objStack[j]] || {};
						obj = obj[objStack[j]];
					}
					/* add the new procedure */
					obj[objStack[j]] = (
						function(method, obj) {
							var _outer = {"method":method,"rpc":_rpc};
							return function(callback) {
								var params = [];
								params.push(_outer.method);
								params.push(callback);
								for (var i=1; i<arguments.length; i++) {
									params.push(arguments[i]);
								}
								_rpc.apply(_self, params);
							}
						}
					)(proc, _rpc);
				}
				// console.log('Load was performed.');
				if(onLoadCallback) {
					onLoadCallback(_self);
				}
			}
		);
	})(url, dataType, onLoadCallback, version);
};

