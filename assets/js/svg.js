'use strict';
!function($) {
    openSvg();
}(jQuery);

function openSvg() {
    let svg = document.querySelector('.svg');

    if (svg === null) {
        return;
    }

    svg.innerHTML = window.map;
    animate();
    document.addEventListener('animationend', animate);
}

function animate(event) {
    // get last animated element
    let domElement = event ? event.target : null;
    if (domElement != null) {
        domElement.removeAttribute('class');
    }

    // remove last style element
    let oldStyle = document.getElementById('css-style');
    if (oldStyle !== null) {
        oldStyle.parentNode.removeChild(oldStyle);
    }

    let rects = document.querySelectorAll('rect');
    let randomNumber = Math.floor(Math.random() * rects.length)
    let rect = rects[randomNumber];

    let width = rect.width.baseVal.value;
    let height = rect.height.baseVal.value;
    let x = rect.x.baseVal.value;
    let y = rect.y.baseVal.value;
    let rx = rect.rx.baseVal.value;
    let ry = rect.ry.baseVal.value;

    //let factor = 2; // 4
    //let factor = 4; // 3
    let factor = 8; // 2.5
    let widthNew = width * factor;
    let heightNew = height * factor;
    let xNew = x - widthNew / 2.5;
    let yNew = y - heightNew / 2.5;
    let rxNew = widthNew / 2;
    let ryNew = heightNew / 2;

    let svg = rect.parentNode;
    svg.removeChild(rect);
    svg.appendChild(rect);

    let css = '@keyframes blink {  0% {width:' + width + 'px; height:' + height + 'px; x: ' + x + 'px; y: ' + y + 'px; rx: ' + rx + 'px; ry: ' + ry + 'px;}' +
                                ' 40% {width:' + width + 'px; height:' + height + 'px; x: ' + x + 'px; y: ' + y + 'px; rx: ' + rx + 'px; ry: ' + ry + 'px;}' +
                                ' 50% {width:' + widthNew + 'px; height:' + heightNew + 'px; x: ' + xNew + 'px; y: ' + yNew + 'px; rx: ' + rxNew + 'px; ry: ' + ryNew + 'px;}' +
                                ' 60% {width:' + width + 'px; height:' + height + 'px; x: ' + x + 'px; y: ' + y + 'px; rx: ' + rx + 'px; ry: ' + ry + 'px;}' +
                                '100% {width:' + width + 'px; height:' + height + 'px; x: ' + x + 'px; y: ' + y + 'px; rx: ' + rx + 'px; ry: ' + ry + 'px;}}' +
              '.animate { animation-name: blink;' +
                         'animation-duration: 5s;}';

    let head = document.head || document.getElementsByTagName('head')[0];
    let style = document.createElement('style');

    style.id = 'css-style';
    style.type = 'text/css';
    if (style.styleSheet) {
        style.styleSheet.cssText = css;
    } else {
        style.appendChild(document.createTextNode(css));
    }
    head.appendChild(style);

    rect.setAttribute('class', 'animate');
}

