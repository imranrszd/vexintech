function iPhone(deviceID) {
    let t = '';
    if (deviceID === 'XIV') {
        t = 'iPhone 14';
    } if (deviceID === 'XIVPlus') {
        t = 'iPhone 14 Plus';
    }
    let title = document.getElementById('title');
    title.innerHTML = t;
}
