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
                    type:type,
                    data:data
                }), "*");
            }
        }
    };

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


