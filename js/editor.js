(function() {

var streaming = false,
video        = document.querySelector('#video'),
cover        = document.querySelector('#cover'),
canvas       = document.querySelector('#canvas'),
photo        = document.querySelector('#photo'),
startbutton  = document.querySelector('#startbutton'),
saver  = document.querySelector('#saver'),
filter_saver  = document.querySelector('#filterr'),
bw_saver  = document.querySelector('#blackwhite'),
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
filter_list = document.querySelector('#flist'),
montage_list = document.querySelector('#mlist'),
width = 1920,
height = 1440;

keepbutton.style.display = 'none';
deletebutton.style.display = 'none';
startbutton.style.display = 'none';
filter.style.display = 'none';
montage_list.style.visibility = 'hidden';

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
//height = video.videoHeight / (video.videoWidth/width);
video.setAttribute('width', width);
video.setAttribute('height', height);
canvas.setAttribute('width', width);
canvas.setAttribute('height', height);
filter.setAttribute('width', width);
filter.setAttribute('height', height);
streaming = true;
startbutton.style.display = 'inline-block';
filter.style.display = 'block';
montage_list.style.visibility = 'visible';
}
}, false);

function takepicture() {
canvas.width = width;
canvas.height = height;
canvas.getContext('2d').drawImage(video, 0, 0, width, height);
var data = canvas.toDataURL('image/png');
photo.setAttribute('src', data);
saver.setAttribute('value', data);
filter_saver.setAttribute('value', filter.getAttribute('src'));
bw_saver.setAttribute('value', video.getAttribute('class'));
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
filter_list.style.visibility = 'hidden';
}, false);

deletebutton.addEventListener('click', function(){
  photo.setAttribute('src', 'img/filters/empty.png');
  keepbutton.style.display = 'none';
  startbutton.style.display = 'inline-block';
  deletebutton.style.display = 'none';
  filter_list.style.visibility = 'visible';
});

})();
