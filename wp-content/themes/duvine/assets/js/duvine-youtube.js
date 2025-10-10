
var tag = document.createElement('script'),
    firstScriptTag = document.getElementsByTagName('script')[0];

  tag.src = "https://www.youtube.com/iframe_api";

  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

  // 3. This function creates an <iframe> (and YouTube player)
  //    after the API code downloads.
  var player;
  function onYouTubeIframeAPIReady() {
    var youtube_url = document.getElementById('youtube-player').getAttribute('data-url'),
        youtubeID;

    if( youtube_url.length ) {
        if( -1 !== youtube_url.indexOf( 'youtube.com' ) ) {
            youtubeID = youtube_url.split('v=')[1];
        }
        else
        if( -1 !== youtube_url.indexOf( 'youtu.be' ) ) {
            youtubeID = youtube_url.split('youtu.be/')[1].split('?')[0];
        }
    }

    player = new YT.Player('youtube-player', {
      height: '405',
      width: '786',
      videoId: youtubeID,
      playerVars: {
          'autoplay': 1,
          'rel': 0,
          'modestbranding': 1,
          'showinfo': 0,
          'controls': 1
      },
      events: {
        'onReady': onPlayerReady,
        'onStateChange': onPlayerStateChange
      }
    });
  }

  // 4. The API will call this function when the video player is ready.
  function onPlayerReady(event) {
    jQuery('.video-container').fitVids();
    //event.target.playVideo();
  }

  // 5. The API calls this function when the player's state changes.
  //    The function indicates that when playing a video (state=1),
  //    the player should play for six seconds and then stop.
  var done = false;
  function onPlayerStateChange(event) {
    if (event.data == YT.PlayerState.PLAYING && !done) {
      //setTimeout(stopVideo, 6000);
      done = true;
    }
  }
  function stopVideo() {
    player.stopVideo();
  }
