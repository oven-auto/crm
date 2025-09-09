window.storage = function(view, url, data, obj, message, urlId, routerpage) {
    view.loading = true
    axios.post(url, data, getConfig())
    .then(res => {
        if(res.data.status)
        {
            view[urlId] = res.data.data.id
            view.$router.push('/'+routerpage+'/list')
            view.$router.push('/'+routerpage+'/edit/'+view[urlId])
            view[obj] = res.data.data
            view[message] = res.data.message;
        } else {
            view[message] = res.data.message;
        }
    }).catch(errors => {
        view[message] = errorsToStr(errors)
    }).finally(()=>{
        view.loading = false
        makeToast(view,view[message])
    })
}

window.update = function(view, url, data, obj, message) {
    view.loading = true
    axios.post(url, data, getConfig())
    .then(res => {
        view[obj] = res.data.data
        view[message] = res.data.message;
    }).catch(errors => {
        view[message] = errorsToStr(errors)
    }).finally(()=>{
        makeToast(view,view[message])
        view.loading = false
    })
}

window.edit = function(view, url, obj, message ) {
    view.loading = true
    axios.get(url, getConfig())
    .then( response => {
        if(response.data.status == 1)
            view[obj] = response.data.data
        else
            view[message] = response.data.message
    }).catch(errors => {
        view[message] = errorsToStr(errors)
    }).finally(() => {
        makeToast(view, view[message])
        view.loading = false
    })
}

window.list = function(view, url, obj, message) {
    view.loading = true
    axios.get(url, getConfig())
    .then(response => {
        view[obj] = response.data.data;
        view[message] = response.data.message;
    }).catch(errors => {
        view[message] = errorsToStr(errors)
    }).finally(() => {
        view.loading = false
        makeToast(view,view[message])
    })
}

window.getConfig = function() {
    return {
        'content-type': 'multipart/form-data'
    }
}
