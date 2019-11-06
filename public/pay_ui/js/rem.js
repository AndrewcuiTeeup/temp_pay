// !function (d) {
//     var c = d.document;
//     var a = c.documentElement;
//     var b = d.devicePixelRatio;
//     var f;

//     function e() {
//       var h = a.getBoundingClientRect().width, g;
//       if (b === 1) {
//         h = 750
//       }
//       if(h>750) h = 750;//设置基准值的极限值
//       g = h / 7.2;
//       a.style.fontSize = g + "px"
//     }

//     if (b > 2) {
//       b = 3
//     } else {
//       if (b > 1) {
//         b = 2
//       } else {
//         b = 1
//       }
//     }
//     a.setAttribute("data-dpr", b);
//     d.addEventListener("resize", function () {
//       clearTimeout(f);
//       f = setTimeout(e, 200)
//     }, false);
//     e()
//   }(window);
function rem (){
    let deviceWidth = document.documentElement.clientWidth;
    if(deviceWidth > 750) deviceWidth = 750;
    document.documentElement.style.fontSize = deviceWidth / 7.5 + 'px'; 
}

rem();

window.onresize=function(){
    rem();
}