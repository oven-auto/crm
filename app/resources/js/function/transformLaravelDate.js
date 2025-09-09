window.transformLaravelDate = function(str, delimeter='.') {
    var date = new Date(str)
    var dateMas = []
    dateMas.push(date.getDate())
    dateMas.push( (date.getMonth()+1 < 10) ? '0'+(date.getMonth()+1) : (date.getMonth()+1) )
    dateMas.push(date.getFullYear())
    return dateMas.join(delimeter)
}


