function toast(message, type, title, time) {
    $.toast({
        heading: title,
        text: message,
        showHideTransition: 'slide',
        icon: type,
        hideAfter: time
    })
}