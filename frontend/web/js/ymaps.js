ymaps.ready(init);
function init() {
  var myMap = new ymaps.Map("map", {
    center: [coordinates[0], coordinates[1]],
    zoom: 15
  });
}
