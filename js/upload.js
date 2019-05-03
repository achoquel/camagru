var img = document.querySelector('#photo2'),
keepbutton  = document.querySelector('#keep'),
deletebutton  = document.querySelector('#reset'),
uploadbutton = document.querySelector('#startbutton'),
uploadform = document.querySelector('#form'),
saver  = document.querySelector('#saver'),
filter_saver  = document.querySelector('#filterr'),
bw_saver  = document.querySelector('#blackwhite'),
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
filter_list = document.querySelector('#flist'),
filter_none  = document.querySelector('#none');

if (img.getAttribute('src') == 'img/nia.jpg')
{
  filter_list.style.visibility = 'hidden';
  deletebutton.style.display = 'none';
  keepbutton.style.display = 'none';
}
else
{
  saver.setAttribute('value', img.getAttribute('src'));
  filter_saver.setAttribute('value', 'img/filters/empty.png');
  filter_list.style.visibility = 'visible';
  deletebutton.style.display = 'inline-block';
  keepbutton.style.display = 'inline-block';
  uploadform.style.display = 'none';
  uploadbutton.style.display = 'none';
  addEventListener('load', function(){
    style = window.getComputedStyle(img);
    width = style.getPropertyValue('width');
    height = style.getPropertyValue('height');
    filter.style.width = width;
    filter.style.height = height;
  })
  window.addEventListener('resize', function(){
    style = window.getComputedStyle(img);
    width = style.getPropertyValue('width');
    height = style.getPropertyValue('height');
    filter.style.width = width;
    filter.style.height = height;
  })
}

filter_rainbow.addEventListener('click', function(){
  filter.setAttribute('src', 'img/filters/rainbow.png');
  filter_saver.setAttribute('value', filter.getAttribute('src'));
})

filter_insta.addEventListener('click', function(){
  filter.setAttribute('src', 'img/filters/bry.png');
  filter_saver.setAttribute('value', filter.getAttribute('src'));
})

filter_doggo.addEventListener('click', function(){
  filter.setAttribute('src', 'img/filters/dog.png');
  filter_saver.setAttribute('value', filter.getAttribute('src'));
})

filter_fortytwo.addEventListener('click', function(){
  filter.setAttribute('src', 'img/filters/42.png');
  filter_saver.setAttribute('value', filter.getAttribute('src'));
})

filter_federation.addEventListener('click', function(){
  filter.setAttribute('src', 'img/filters/federation.png');
  filter_saver.setAttribute('value', filter.getAttribute('src'));
})

filter_order.addEventListener('click', function(){
  filter.setAttribute('src', 'img/filters/order.png');
  filter_saver.setAttribute('value', filter.getAttribute('src'));
})

filter_assembly.addEventListener('click', function(){
  filter.setAttribute('src', 'img/filters/assembly.png');
  filter_saver.setAttribute('value', filter.getAttribute('src'));
})

filter_alliance.addEventListener('click', function(){
  filter.setAttribute('src', 'img/filters/alliance.png');
  filter_saver.setAttribute('value', filter.getAttribute('src'));
})

filter_bw.addEventListener('click', function(){
  img.setAttribute('class', 'bw');
  bw_saver.setAttribute('value', img.getAttribute('class'));
})

filter_rgb.addEventListener('click', function(){
  img.setAttribute('class', '');
  bw_saver.setAttribute('value', img.getAttribute('class'));
})

filter_none.addEventListener('click', function(){
  filter.setAttribute('src', 'img/filters/empty.png');
  filter_saver.setAttribute('value', filter.getAttribute('src'));
})

deletebutton.addEventListener('click', function (){
  img.setAttribute('src', 'img/nia.jpg');
  filter_list.style.visibility = 'hidden';
  deletebutton.style.display = 'none';
  keepbutton.style.display = 'none';
  uploadbutton.style.display = 'inline-block';
  uploadform.style.display = 'block';
  document.location.reload(true);
})
