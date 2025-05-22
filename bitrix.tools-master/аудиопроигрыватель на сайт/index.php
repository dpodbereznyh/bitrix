<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="author" content="Script Tutorials" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

    <link rel="stylesheet" type="text/css" href="css/styles.css"/>
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="js/musicplayer.js"></script>


</head>

<body>

    <div class="example">
        <ul class="playlist">
            <li data-cover="/upload/resize_cache/iblock/251/270_270_1/wav7fs9dcc37ucdaysmju6oftzr16p82.jpg" data-artist="Шаванса" class="active"><a href="/upload/iblock/e0e/t084entshdvxtnr6l60j1q3cygxuc27x.mp3"><span class="t-name">Yoga savasana - Savasana</span><span>04:00</span></a></li>
            <li data-cover="/upload/resize_cache/iblock/251/270_270_1/wav7fs9dcc37ucdaysmju6oftzr16p82.jpg" data-artist="Шаванса"><a href="/upload/iblock/fdd/fzy6a2k2n74ji65sl0j0c0rdbxdditoq.mp3"><span class="t-name">Urmila Devi Goenka - Shiva Manas Puja</span><span>05:49</span></a></li>
            <li data-cover="/upload/resize_cache/iblock/251/270_270_1/wav7fs9dcc37ucdaysmju6oftzr16p82.jpg" data-artist="Шаванса"><a href="/upload/iblock/b2c/f8tiufgrxdsdiepjft9bxgkpee71hr5r.mp3"><span class="t-name">Shiva Rea - Yoga</span><span>04:24</span></a></li>
            <li data-cover="/upload/resize_cache/iblock/251/270_270_1/wav7fs9dcc37ucdaysmju6oftzr16p82.jpg" data-artist="Шаванса"><a href="/upload/iblock/1a3/g9640qoolq360v5d76avzuw01ncw0d0g.mp3"><span class="t-name">Sacred Chants of Devi - Devi Prayer</span><span>21:21</span></a></li>
            <li data-cover="/upload/resize_cache/iblock/251/270_270_1/wav7fs9dcc37ucdaysmju6oftzr16p82.jpg" data-artist="Шаванса"><a href="/upload/iblock/f7d/dsba48kkpzl2sswzsyb3he6zjb63aj9m.mp3"><span class="t-name">Robin Meloy Goldsby - A River Flows in You</span><span>04:58</span></a></li>
            <li data-cover="/upload/resize_cache/iblock/251/270_270_1/wav7fs9dcc37ucdaysmju6oftzr16p82.jpg" data-artist="Шаванса"><a href="/upload/iblock/0eb/xryyof3pv9vw9ci5tj8wnnq84b0601gv.mp3"><span class="t-name">Mantra - Om</span><span>04:50</span></a></li>
            <li data-cover="/upload/resize_cache/iblock/251/270_270_1/wav7fs9dcc37ucdaysmju6oftzr16p82.jpg" data-artist="Шаванса"><a href="/upload/iblock/2c6/cw0aec5azooljx5sq2orxco5x1gojdj7.mp3"><span class="t-name">Jia Peng Fang - Silent Moon</span><span>05:34</span></a></li>
            <li data-cover="/upload/resize_cache/iblock/251/270_270_1/wav7fs9dcc37ucdaysmju6oftzr16p82.jpg" data-artist="Шаванса"><a href="/upload/iblock/381/neqb65vs0e2qp6o6j78sds7oierxxmbg.mp3"><span class="t-name">Hariharan - Buddham Saranam Gacchami</span><span>11:16</span></a></li>

        </ul>
    </div>

    <script>
        var f_time=function(){
            if($('li.active .t-name').html()!=$('.player .title').html()){$('.player .title').html($('li.active .t-name').html())}

        }

        $(".example").musicPlayer({
            elements: ['artwork', 'information', 'controls', 'progress', 'time', 'volume'], // ==> This will display in  the order it is inserted
            //elements: [ 'controls' , 'information', 'artwork', 'progress', 'time', 'volume' ],
            controlElements: ['backward', 'play', 'forward', 'stop'],
            timeElements: ['current', 'duration'],
            //timeSeparator: " : ", // ==> Only used if two elements in timeElements option
            //infoElements: [  'title', 'artist' ],

            //volume: 10,
            autoPlay: true,
            loop: true,
            
            onLoad: function () {
                //Add Audio player
                if($('.pl').length==0){
                    plElem = "<div class='pl'></div>";
                    $('.example').find('.player').append(plElem);
                // show playlist
                    $('.example').on('click','.pl',function (e) {
                        e.preventDefault();
                        $('.example').find('.playlist').toggleClass("hidden");
                    });
                }
            },

             onPlay:function(){
            f_time();           
            }

            /*
            onPause: function () {
                $('body').css('background', 'green');
            },
            onStop: function () {
                $('body').css('background', '#141942');
            },
            onFwd: function () {
                $('body').css('background', 'white');
            },
            onRew: function () {
                $('body').css('background', 'blue');
            },
            volumeChanged: function () {
                $('body').css('background', 'red');
            },
            progressChanged: function () {
                $('body').css('background', 'orange');
            },
            trackClicked: function () {
                $('body').css('background', 'brown');
            },
            onMute: function () {
                $('body').css('background', 'grey');

            },*/
        });
    </script>


</body>

</html>