const divs_playlist = document.querySelectorAll("div.jp-playlist");
divs_playlist.forEach(function (div_playlist) {
  div_playlist.style.display = "none";
});

const divs_jplayer = document.querySelectorAll("div.jp-jplayer");
divs_jplayer.forEach(function (div_jplayer) {
  if (
    div_jplayer.hasAttribute("data-idArticle") &&
    div_jplayer.hasAttribute("data-pistes")
  ) {
    let idArticle = div_jplayer.getAttribute("data-idArticle");
    let pistes = JSON.parse(div_jplayer.getAttribute("data-pistes"));
    let myPlaylist = new jPlayerPlaylist(
      {
        cssSelectorAncestor: "#jp_container_" + idArticle,
        jPlayer: "#jquery_jplayer_" + idArticle,
      },
      pistes,
      {
        swfPath: "/js/jplayer-2.9.2/jplayer",
        supplied: "mp3",
        wmode: "window",
        useStateClassSkin: true,
        autoBlur: false,
        smoothPlayBar: true,
        keyEnabled: true,
      }
    );
    $("#jquery_jplayer_" + idArticle).bind($.jPlayer.event.play, function () {
      $("#jplayerInspector").jPlayerInspector({
        jPlayer: $("#jquery_jplayer_" + idArticle),
        visible: true,
      });
    });
  }
});
