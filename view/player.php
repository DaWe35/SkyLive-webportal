<head>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <link href="assets/vsg-skin.css" rel="stylesheet"> <?php
    if ($stream['visibility'] != 'public') { ?>
        <meta name="robots" content="noindex" /> <?php
    } ?>
    <link rel="stylesheet" href="https://cdn.plyr.io/3.6.3/plyr.css" />
</head>
<body class="player chatmode">
    
    <div id="portal-switcher">
        <input id="check01" type="checkbox" name="menu" />
        <label for="check01" class="noselect" style="opacity: 0.7;">
            <span id="portal-switch-text-long">Change Skynet portal server ▼</span>
            <span id="portal-switch-text-short">Portal ▼</span>
        </label>
        <ul class="submenu" id="portal_list">
            <li id="loading_portals"><a href="#">Loading portals...</a></li>
        </ul>
    </div>
    <a href="<?= URL ?>" class="logo"><button class="play mini-logo-play"></button> &nbsp;SkyLive</a>

    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-9 p-0">
                <div id="ios_warning">
                    Sorry, IOS  does not support HLS streaming.<br>
                    <a href="vlc-x-callback://x-callback-url/stream?url=<?= $stream_url ?>">Open in VLC</a><br>
                    If it's not working, open VLC and play this network file: <?= URL . $stream_url ?>
                </div>

                <!-- Video player -->
                <video id="my_video_1" crossorigin="anonymous" playsinline controls preload="auto" poster="<?= image_print($stream['streamid'], 1920) ?>" <?= $video_src ?> >Sorry, HTML5 video is not supported in your browser</video>

            </div>
            <div class="col-sm-8 col-md-3 p-0 minnit-chat-container">
                <iframe id="chat" src="https://skymessage.hns.siasky.net/#skylive" allowTransparency="true"></iframe>
            </div>
        </div>

        <div class="row player-title-bar">
            <div class="col-12 p-0">
                <h1 class="text-light"><?= $stream['title'] ?></h1>
                <a href="/channel?user=<?= $channel['id'] ?>" class="text-decoration-none">
                    <p class="text-secondary">
                    
                        <?= $channel['name'] ?>
                    </p>
                </a>
            </div>
        </div>
        <div class="row text-left text-secondary">
                <?= nl2br($stream['description']) ?>
        </div>
    </div>

    <!-- <button onclick="switchMode()" class="BT-OH-BR-R6-NF-FH-FP-PT" id="button">
        <canvas id="canvas"></canvas> 
        <hover></hover>
        <span>Toggle chat</span>
    </button> -->
    
    <script> <?php 
    if ($stream['format'] == 'hls') { ?>
        // Display warning on IOS
        var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        if (iOS && <?= $stream['finished'] ?> == 0) {
            $('#ios_warning').css('display', 'initial')
        }

        // Create HLS video element
        var video = document.getElementById('my_video_1');
        if (Hls.isSupported()) {
            var hls = new Hls();
            hls.loadSource('<?= $stream_url ?>');
            hls.attachMedia(video);
            hls.on(Hls.Events.MANIFEST_PARSED, function() {
                video.play();
            });
        }
        // hls.js is not supported on platforms that do not have Media Source Extensions (MSE) enabled.
        // When the browser has built-in HLS support (check using `canPlayType`), we can provide an HLS manifest (i.e. .m3u8 URL) directly to the video element through the `src` property.
        // This is using the built-in support of the plain video element, without using hls.js.
        // Note: it would be more normal to wait on the 'canplay' event below however on Safari (where you are most likely to find built-in HLS support) the video.src URL must be on the user-driven
        // white-list before a 'canplay' event will be emitted; the last video event that can be reliably listened-for when the URL is not on the white-list is 'loadedmetadata'.
        else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            video.src = '<?= $stream_url ?>';
            video.addEventListener('loadedmetadata', function() {
                video.play();
            });
        } <?php
    } ?>

    chatmode = 1
    function switchMode() {
        if (chatmode == 0) {
            $('body').addClass('chatmode')
            chatmode = 1
        } else {
            $('body').removeClass('chatmode')
            chatmode = 0
        }
    }

    // portal list
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("loading_portals").remove()

            url = "/player?s=<?= htmlspecialchars($_GET['s']) ?>"
            document.getElementById("portal_list").innerHTML += '<li><a href="' + url + '">Automatic</a></li>'

            let portals = JSON.parse(this.responseText);
            portals.forEach(portal => {
                portalSum = 0
                portal.files.forEach(filenumb => {
                    portalSum += filenumb
                })
                if (portalSum != 0) {
                    url = "/player?s=<?= htmlspecialchars($_GET['s']) ?>&portal=" + portal.link
                    document.getElementById("portal_list").innerHTML += '<li><a href="' + url + '">' + portal.name + '</a></li>'
                }
            })
            
            url = "/player?s=<?= htmlspecialchars($_GET['s']) ?>&portal=https://helsinki.siasky.net"
            document.getElementById("portal_list").innerHTML += '<li><a href="' + url + '">Helsinki.SiaSky.net</a></li>'
            
            url = "/player?s=<?= htmlspecialchars($_GET['s']) ?>&portal=https://germany.siasky.net/"
            document.getElementById("portal_list").innerHTML += '<li><a href="' + url + '">Germany.SiaSky.net</a></li>'
            
            url = "/player?s=<?= htmlspecialchars($_GET['s']) ?>&portal=https://siasky.dev/"
            document.getElementById("portal_list").innerHTML += '<li><a href="' + url + '">SiaSky.dev</a></li>'

            
        }
    }
    xhttp.open("GET", "https://siastats.info/dbs/skynet_current.json", true);
    xhttp.send();




    // AUDIO PLAYER
    window.requestAnimFrame = function(){
        return (
            window.requestAnimationFrame       || 
            window.webkitRequestAnimationFrame || 
            window.mozRequestAnimationFrame    || 
            window.oRequestAnimationFrame      || 
            window.msRequestAnimationFrame     || 
            function(/* function */ callback){
                window.setTimeout(callback, 1000 / 60);
            }
        );
    }();

    
    var audioCanvas, ctx, source, context, analyser, fbc_array, bars, bar_x, bar_width, bar_height;
    var loadedAudio = false
    function loadAudio() {
        // Create a new instance of an audio object and adjust some of its properties
        var audio = document.getElementById('my_video_1');
        // Establish all variables that your Analyser will use
        // Initialize the MP3 player after the page loads all of its HTML into the window
        // window.addEventListener("load", initMp3Player, false);
        initMp3Player(audio)
    }

    function initMp3Player(audio){
        document.getElementsByClassName('plyr__video-wrapper')[0].appendChild(audio)
        canv = document.createElement('canvas')
        canv.id = 'analyser_render'
        document.getElementsByClassName('plyr__video-wrapper')[0].appendChild(canv) // adds the canvas to #someBox
        audioCanvas = document.getElementById('analyser_render')

        /* text_render = document.createElement('p')
        text_render.id = 'text_render'
        text_render.innerHTML = '<?= $stream['title'] ?>'
        document.getElementsByClassName('plyr__video-wrapper')[0].appendChild(text_render) // adds the canvas to #someBox */

        context = new AudioContext(); // AudioContext object instance
        analyser = context.createAnalyser(); // AnalyserNode method
	    audioCanvas.style.background = 'rgba(0, 0, 0, 0.9)'
        ctx = audioCanvas.getContext('2d');
        // Re-route audio playback into the processing graph of the AudioContext
        source = context.createMediaElementSource(audio); 
        source.connect(analyser);
        analyser.connect(context.destination);
        frameLooper();
    }
    // frameLooper() animates any style of graphics you wish to the audio frequency
    // Looping at the default frame rate that the browser provides(approx. 60 FPS)
    function frameLooper(){
        window.requestAnimFrame(frameLooper);
        fbc_array = new Uint8Array(analyser.frequencyBinCount);
        analyser.getByteFrequencyData(fbc_array);
        ctx.clearRect(0, 0, audioCanvas.width, audioCanvas.height); // Clear the canvas
        ctx.fillStyle = '#00c0f0'; // Color of the bars
        bars = 37;
        for (var i = 0; i < bars; i++) {
            bar_x = i * 8;
            bar_width = 6;
            bar_height = -(fbc_array[i] / 2);
            //fillRect( x, y, width, height ) // Explanation of the parameters below
            ctx.fillRect(bar_x, audioCanvas.height, bar_width, bar_height *0.6);
        }
    } <?php 

    if ($stream['format'] == 'audio') { ?>
        var audio = document.getElementById("my_video_1");
        audio.onplay = function() {
            if (loadedAudio == false) {
                loadedAudio = true
                loadAudio()
            }
        }; <?php
    } ?>



/* 
    // Chat button
        (function () {
    var lastTime = 0;
    var vendors = ['ms', 'moz', 'webkit', 'o'];
    for (var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x] + 'RequestAnimationFrame'];
        window.cancelAnimationFrame = window[vendors[x] + 'CancelAnimationFrame'] ||
        window[vendors[x] + 'CancelRequestAnimationFrame'];
    }

    if (!window.requestAnimationFrame)
    window.requestAnimationFrame = function (callback, element) {
        var currTime = new Date().getTime();
        var timeToCall = Math.max(0, 16 - (currTime - lastTime));
        var id = window.setTimeout(function () {callback(currTime + timeToCall);},
        timeToCall);
        lastTime = currTime + timeToCall;
        return id;
    };

    if (!window.cancelAnimationFrame)
    window.cancelAnimationFrame = function (id) {
        clearTimeout(id);
    };
    })();


    (function () {

    // Get the buttons.
    var startBtn = document.getElementById('button');
    // var resetBtn = document.getElementById('resetBtn');
    // A variable to store the requestID.
    var requestID;
    // Canvas
    var canvas = document.getElementById('canvas');
    // 2d Drawing Context.
    var ctx = canvas.getContext('2d');

    // Variables to for the drawing position and object.
    var posX = 0;
    var W = 246;
    var H = 60;
    var circles = [];

    //Get canvas size
    canvas.width = 246;
    canvas.height = 60;

    // Animate.
    function animate() {
        requestID = requestAnimationFrame(animate);
        //Fill canvas with black color
        //ctx.globalCompositeOperation = "source-over";
        ctx.fillStyle = "rgba(0,0,0,0.15)";
        ctx.fillRect(0, 0, W, H);

        //Fill the canvas with circles
        for (var j = 0; j < circles.length; j++) {
        var c = circles[j];

        //Create the circles
        ctx.beginPath();
        ctx.arc(c.x, c.y, c.radius, 0, Math.PI * 2, false);
        ctx.fillStyle = "rgba(" + c.r + ", " + c.g + ", " + c.b + ", 0.5)";
        ctx.fill();

        c.x += c.vx;
        c.y += c.vy;
        c.radius -= .02;

        if (c.radius < 0)
        circles[j] = new create();
        }



    }

    //Random Circles creator
    function create() {

        //Place the circles at the center

        this.x = W / 2;
        this.y = H / 2;


        //Random radius between 2 and 6
        this.radius = 2 + Math.random() * 3;

        //Random velocities
        this.vx = -5 + Math.random() * 10;
        this.vy = -5 + Math.random() * 10;

        //Random colors
        this.r = Math.round(Math.random()) * 255;
        this.g = Math.round(Math.random()) * 255;
        this.b = Math.round(Math.random()) * 255;
    }

    for (var i = 0; i < 500; i++) {
        circles.push(new create());
    }

    // Event listener for the start button.
    startBtn.addEventListener('mouseover', function (e) {
        e.preventDefault();

        // Start the animation.
        requestID = requestAnimationFrame(animate);
    });


    // Event listener for the stop button.
    startBtn.addEventListener('mouseout', function (e) {
        e.preventDefault();

        // Stop the animation;
        cancelAnimationFrame(requestID);

        e.preventDefault();

        // Reset the X position to 0.
        posX = 0;

        // Clear the canvas.
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Draw the initial box on the canvas.
        // ctx.fillRect(posX, 0, canvas.width, canvas.height);

    });

    })(); */

    </script>
    <script src="https://cdn.plyr.io/3.6.3/plyr.polyfilled.js"></script>
    <script>
        const player = new Plyr('#my_video_1');
        </script>
</body>