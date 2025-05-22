<script src="https://api-maps.yandex.ru/2.1?apikey=5ae32c6f-745e-437d-a0b8-550e89a92e80&load=package.full&lang=ru_RU"></script>

<script>
    let sdek;
    let lpostmat = {type: '', address: '', code: '', lat: '', lng: ''}
    let coordStart=[55.76, 37.64];

    let myMap;
    let objectManager21;
    let objectManager22;
    let searchControl;

    let arrPoints=[];

    $.get('https://integration.cdek.ru/pvzlist/v1/json', '').done(function (result) {
        sdek = result;
    });

    function setlocateStart(name)
    {
        ymaps.ready(setlocateChild);
        function setlocateChild(){
            var myGeocoder = ymaps.geocode(name);
            myGeocoder.then(function(res) {
                console.log(res.geoObjects.get(0).geometry._coordinates);
                coordStart=res.geoObjects.get(0).geometry._coordinates;
                ymaps.ready(init);
            });}
    }

    function init() {
        myMap = new ymaps.Map('YMapsID', {
            center: coordStart,
            zoom: 12
        }, {
            //searchControlProvider: 'yandex#search'
        });

        objectManager21 = new ymaps.ObjectManager({
            // Чтобы метки начали кластеризоваться, выставляем опцию.
            clusterize: false,
            // ObjectManager принимает те же опции, что и кластеризатор.
            gridSize: 32,
            clusterDisableClickZoom: true,
            geoObjectOpenBalloonOnClick: false,
            clusterOpenBalloonOnClick: false
        })

        objectManager22 = new ymaps.ObjectManager({
            // Чтобы метки начали кластеризоваться, выставляем опцию.
            clusterize: false,
            // ObjectManager принимает те же опции, что и кластеризатор.
            gridSize: 32,
            clusterDisableClickZoom: true,
            geoObjectOpenBalloonOnClick: false,
            clusterOpenBalloonOnClick: false
        })

        objectManager21.objects.options.set('preset', 'islands#greenDotIcon');
        objectManager21.clusters.options.set('preset', 'islands#greenClusterIcons');
        objectManager22.objects.options.set('preset', 'islands#greenDotIcon');
        objectManager22.clusters.options.set('preset', 'islands#greenClusterIcons');

        myMap.geoObjects.add(objectManager21);
        objectManager21.add({"type": "FeatureCollection", "features": []});
        objectManager21.objects.events.add(['click'], onObjectEvent21);

        myMap.geoObjects.add(objectManager22);
        objectManager22.add({"type": "FeatureCollection", "features": []});
        objectManager22.objects.events.add(['click'], onObjectEvent22);

        function onObjectEvent21(e) {
            if (e.get('type') == 'click') {
                let objectId = e.get('objectId');
                lpostmat.code = objectId;
                //$("#sdec-points").scrollTop($("#sdec_"+objectId).closest('.item').position().top);
                //console.log($("#sdec_"+objectId).closest('.item').position().top);
            }
        }

        function onObjectEvent22(e) {
            if (e.get('type') == 'click') {
                let objectId = e.get('objectId');
                //$("#sdec-points").scrollTop($("#sdec_"+objectId).closest('.item').position().top);
               // console.log($("#sdec_"+objectId).closest('.item').position().top);
            }
        }

        addSdec();

        console.log(arrPoints);

        for (let i = 0; i < arrPoints.length; i++) {
            $('#sdec-points').append('<div class="item item'+i+'">'+arrPoints[i]+'</div>');
        }


        //$("#sdec-points").scrollTop(2000);
    }

    function setCenter(lat, lng) {
        let pixelCenter = [
            lat,
            lng
        ];
        myMap.setCenter(pixelCenter);
    }

    function addSdecToMap() {
        let js = {"type": "FeatureCollection", "features": []};
        console.log(sdek.pvz);
        for (let i = 0; i < 1000; i++) {
            if (sdek.pvz[i].coordY != '') js.features.push({
                "type": "Feature",
                "id": sdek.pvz[i].postalCode,
                "geometry": {"type": "Point", "coordinates": [sdek.pvz[i].coordY, sdek.pvz[i].coordX]},
                "properties": {"hintContent": '<div class="waddress">'+sdek.pvz[i].fullAddress+'</div><div>телефон: '+sdek.pvz[i].phone+'</div><div>время работы: '+sdek.pvz[i].workTime+'</div>'}
            })
          if(sdek.pvz[i].city=='<?=$arRegion['NAME']?>'){
              arrPoints.push('<div id="sdec_'+sdek.pvz[i].postalCode+'" class="id_sdeck" data-coordy="'+sdek.pvz[i].coordY+'" data-coordx="'+sdek.pvz[i].coordX+'"></div><div class="waddress"><div><i class="fa big-icon s45  fa-map-marker"></i></div><div>'+sdek.pvz[i].fullAddress+'</div></div><div class="sdec-phone"><div><i class="fa big-icon s45  fa-phone"></i></div>'+sdek.pvz[i].phone+'</div><div class="sdec-workTime"><div><i class="fa big-icon s45  fa-clock-o"></i></div>'+sdek.pvz[i].workTime+'</div>');
          }
        }
        objectManager21.add(js);
        for (let i = 1000; i < sdek.pvz.length; i++) {
            if (sdek.pvz[i].coordY != '') js.features.push({
                "type": "Feature",
                "id": sdek.pvz[i].postalCode,
                "geometry": {"type": "Point", "coordinates": [sdek.pvz[i].coordY, sdek.pvz[i].coordX]},
                "properties": {"hintContent": '<div class="waddress">'+sdek.pvz[i].fullAddress+'</div><div>телефон: '+sdek.pvz[i].phone+'</div><div>время работы: '+sdek.pvz[i].workTime+'</div>'}
            })
            if(sdek.pvz[i].city=='<?=$arRegion['NAME']?>'){
                arrPoints.push('<div id="sdec_'+sdek.pvz[i].postalCode+'" class="id_sdeck" data-coordy="'+sdek.pvz[i].coordY+'" data-coordx="'+sdek.pvz[i].coordX+'"></div><div class="waddress"><div><i class="fa big-icon s45  fa-map-marker"></i></div><div>'+sdek.pvz[i].fullAddress+'</div></div><div class="sdec-phone"><div><i class="fa big-icon s45  fa-phone"></i></div>'+sdek.pvz[i].phone+'</div><div class="sdec-workTime"><div><i class="fa big-icon s45  fa-clock-o"></i></div>'+sdek.pvz[i].workTime+'</div>');
            }
        }
        objectManager22.add(js);
    }

    function addSdec() {
        if ($.isEmptyObject(sdek)) {
            $.get('https://integration.cdek.ru/pvzlist/v1/json', '').done(function (result) {
                sdek = result;
                addSdecToMap();
            });
        } else {
            addSdecToMap();
        }
    }

    function setlocate(name,zoom=10)
    {
        ymaps.ready(setlocateChild);
        function setlocateChild(){
            var myGeocoder = ymaps.geocode(name);
            myGeocoder.then(function(res) {
                console.log(res.geoObjects.get(0).geometry._coordinates);
                myMap.setCenter(res.geoObjects.get(0).geometry._coordinates);
                myMap.setZoom( zoom );
            });}}

    setlocateStart('<?=($arRegion['NAME'])?$arRegion['NAME']:'Москва'?>');

function setlocatePoint(coordX,coordY,zoom=14)
    { arr=[coordY,coordX];
      myMap.setCenter(arr);
      myMap.setZoom( zoom ); 
    }

$('#sdec-points').on('click','.item',function(){
    coordX=$('.id_sdeck',this).data('coordx');
    coordY=$('.id_sdeck',this).data('coordy');
    $('.isem-select').removeClass('isem-select');
    $(this).addClass('isem-select');
    setlocatePoint(coordX,coordY,17);
})




</script>