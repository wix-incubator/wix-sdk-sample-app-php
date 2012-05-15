var Wix = (function () {
    var _w = {
        MessageTypes:{
            HEIGHT_CHANGED:"heightChanged",
            APP_SETTINGS_CHANGED:"appSettingsChanged",
            APP_IS_ALIVE:"appIsAlive"
        },

        sendMessageInternal:function (type, data) {
            var target = parent.postMessage ? parent : (parent.document.postMessage ? parent.document : undefined);
            if (target && typeof target != "undefined") {
                target.postMessage(JSON.stringify({
					intent: "TPA",
					compId: compId,
                    type:type,
                    data:data
                }), "*");
            }
        },
		
		getQueryParameter: function ( parameterName ) {
		  var queryString = location.search.substring(1);
		  var parameterName = parameterName + "=";
		  if ( queryString.length > 0 ) {
			begin = queryString.indexOf ( parameterName );
			if ( begin != -1 ) {
			  begin += parameterName.length;
			  end = queryString.indexOf ( "&" , begin );
				if ( end == -1 ) {
				end = queryString.length
			  }
			  return unescape ( queryString.substring ( begin, end ) );
			}
		  }
		  return "null";
		}
    };
	
	var compId = _w.getQueryParameter("compId");

    return {
        reportHeightChange:function (height) {
            _w.sendMessageInternal(_w.MessageTypes.HEIGHT_CHANGED, height);
        },

        refreshApp:function () {
            _w.sendMessageInternal(_w.MessageTypes.APP_SETTINGS_CHANGED);
        },

        isAlive:function () {
            _w.sendMessageInternal(_w.MessageTypes.APP_IS_ALIVE);
        }
    }
})();
Wix.isAlive();


