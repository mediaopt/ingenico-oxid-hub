function setCookie(name, value, days) {
    var expires = '';
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + '=' + (value || '')  + expires + '; path=/';
}

function set3dsecureSessionCookie() {
    var expires = 7; // days
    var dateObject = new Date();
    setCookie( "moIngenicoScreenWidth", screen.width, expires);
    setCookie( "moIngenicoScreenHeight", screen.height, expires);
    setCookie( "moIngenicoColorDepth", screen.colorDepth, expires);
    setCookie( "moIngenicoJavaEnabled", navigator.javaEnabled() ? 'true' : 'false', expires);
    setCookie( "moIngenicoTimeZone", dateObject.getTimezoneOffset(), expires);

    return true;
}

set3dsecureSessionCookie();
