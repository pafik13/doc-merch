ymaps.ready(init);

function init () {
    var map = new ymaps.Map("map", {
            center: [55.76, 37.64],
            zoom: 16,
            controls: []
        });
    map.controls.add('zoomControl', {
        position: {
            right: 10,
            top: 10
        }
    });
    var latitude= Number($('[name="latitude"]').html());
    var longitude = Number($('[name="longitude"]').html());
    var position = [latitude,longitude];

    var placemark = new ymaps.Placemark(position, {
        balloonContentBody: $('[name="address"]').html(),
        hintContent: $('[name="address"]').html()
    });

    map.geoObjects.add(placemark);

    //// Открываем балун на карте (без привязки к геообъекту).
    //map.balloon.open([51.85, 38.37], "Содержимое балуна", {
    //    // Опция: не показываем кнопку закрытия.
    //    closeButton: false
    //});
    map.setCenter(position, 16);

    //// Показываем хинт на карте (без привязки к геообъекту).
    //map.hint.show(map.getCenter(), "Содержимое хинта", {
    //    // Опция: задержка перед открытием.
    //    showTimeout: 1500
    //});
}