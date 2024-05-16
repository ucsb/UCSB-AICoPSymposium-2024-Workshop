document.addEventListener('DOMContentLoaded', function () {
    console.log('document loaded');
    var eventBlocks = document.getElementsByClassName('participant-panel');
    var width = eventBlocks[0].offsetWidth;

    for (let i in eventBlocks) {
        eventBlocks[i].style.height = width + 'px';
    }

}, false);