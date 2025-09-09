window.isString = function(param) {
    return (typeof param === "string" || param instanceof String);
}

window.isObject = function(param) {
    return (typeof param === "object" || param instanceof Object);
}

window.isNumeric = function(param) {
    return (!isNaN(param) && typeof param === 'number' || param instanceof Number );
}

window.isEmptyObject = function(obj) {
    for (var i in obj) {
        if (obj[i]>0 || obj[i]!='') {
            return false;
        }
    }
    return true;
}
window.getConfig = function() {
    return {
        'content-type': 'multipart/form-data'
    }
}

window.errorsToStr = function(errors) {
    if(errors.response.status == 401)
        window.location.href = '/login'

    var mas = [];
    if(errors.response.data.errors != undefined) {
        var messages = errors.response.data.errors
        for(var i in messages) {
            mas.push(messages[i])
        }
    }else if(errors.response.data.message != undefined)
        mas.push(errors.response.data.message)
    return mas
}

window.phone_format = function(phone) {
    return phone.replace(/^(\d)(\d{3})(\d{3})(\d{2})(\d{2})$/, '+$1($2)$3-$4-$5')
}
