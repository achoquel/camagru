(function() {

var streaming = false,
video        = document.querySelector('#video'),
cover        = document.querySelector('#cover'),
canvas       = document.querySelector('#canvas'),
photo        = document.querySelector('#photo'),
startbutton  = document.querySelector('#startbutton'),
keepbutton  = document.querySelector('#keep'),
deletebutton  = document.querySelector('#reset'),
filter  = document.querySelector('#filter'),
filter_rainbow  = document.querySelector('#rainbow'),
filter_insta  = document.querySelector('#insta'),
filter_doggo  = document.querySelector('#doggo'),
filter_fortytwo  = document.querySelector('#fortytwo'),
filter_federation  = document.querySelector('#federation'),
filter_order  = document.querySelector('#order'),
filter_alliance  = document.querySelector('#alliance'),
filter_assembly  = document.querySelector('#assembly'),
filter_bw  = document.querySelector('#bw'),
filter_rgb  = document.querySelector('#rgb'),
filter_none  = document.querySelector('#none'),
width = 1920,
height = 0;

keepbutton.style.display = 'none';
deletebutton.style.display = 'none';
startbutton.style.display = 'none';
filter.style.display = 'none';

navigator.getMedia = ( navigator.getUserMedia ||
                   navigator.webkitGetUserMedia ||
                   navigator.mozGetUserMedia ||
                   navigator.msGetUserMedia);

navigator.getMedia(
{
video: true,
audio: false
},
function(stream) {
if (navigator.mozGetUserMedia) {
  video.mozSrcObject = stream;
} else {
  var vendorURL = window.URL || window.webkitURL;
  video.srcObject = stream;
}
video.play();
},
function(err) {
console.log("An error occured! " + err);
}
);

video.addEventListener('canplay', function(ev){
if (!streaming) {
height = video.videoHeight / (video.videoWidth/width);
video.setAttribute('width', width);
video.setAttribute('height', height);
canvas.setAttribute('width', width);
canvas.setAttribute('height', height);
streaming = true;
startbutton.style.display = 'inline-block';
filter.style.display = 'block';
}
}, false);

function takepicture() {
canvas.width = width;
canvas.height = height;
canvas.getContext('2d').drawImage(video, 0, 0, width, height);
var data = canvas.toDataURL('image/png');
photo.setAttribute('src', data);
}

filter_rainbow.addEventListener('click', function(){
  filter.setAttribute('src', 'img/filters/rainbow.png');
})

filter_insta.addEventListener('click', function(){
  filter.setAttribute('src', 'img/filters/bry.png');
})

filter_doggo.addEventListener('click', function(){
  filter.setAttribute('src', 'img/filters/dog.png');
})

filter_fortytwo.addEventListener('click', function(){
  filter.setAttribute('src', 'img/filters/42.png');
})

filter_federation.addEventListener('click', function(){
  filter.setAttribute('src', 'img/filters/federation.png');
})

filter_order.addEventListener('click', function(){
  filter.setAttribute('src', 'img/filters/order.png');
})

filter_assembly.addEventListener('click', function(){
  filter.setAttribute('src', 'img/filters/assembly.png');
})

filter_alliance.addEventListener('click', function(){
  filter.setAttribute('src', 'img/filters/alliance.png');
})

filter_bw.addEventListener('click', function(){
  video.setAttribute('class', 'bw');
  photo.setAttribute('class', 'bw');
})

filter_rgb.addEventListener('click', function(){
  video.setAttribute('class', '');
  photo.setAttribute('class', '');
})

filter_none.addEventListener('click', function(){
  filter.setAttribute('src', 'img/filters/empty.png');
})

startbutton.addEventListener('click', function(ev){
takepicture();
ev.preventDefault();
startbutton.style.display = 'none';
keepbutton.style.display = 'inline-block';
deletebutton.style.display = 'inline-block';
}, false);

deletebutton.addEventListener('click', function(){
  photo.setAttribute('src', 'img/filters/empty.png');
  keepbutton.style.display = 'none';
  startbutton.style.display = 'inline-block';
  deletebutton.style.display = 'none';
});

})();
