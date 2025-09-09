window.makeToast = function (app,message, title = 'Системное уведомление') {
    if(isString(message))
        app.$bvToast.toast(message, {
            title: title,
            autoHideDelay: 5000,
            appendToast: true
        })
    else if(Array.isArray(message))
        for(var i in message)
            app.$bvToast.toast(message[i], {
                title: title,
                autoHideDelay: 5000,
                appendToast: true
            })
}
