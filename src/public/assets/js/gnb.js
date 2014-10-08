/**
 *
 * Simple integration with Glome Notification Broker
 *
 * ! Experimental !
 *
 */
$(document).ready(function() {
  // TODO: can we get rid of this?
  var uid = 'com.cashbackcatalog';
  var imtoken = $('#imtoken').html();

  if (typeof(uid) !== 'undefined' && typeof(imtoken) !== 'undefined')
  {
    var socket = io(window.location.protocol + '//' + window.location.host);

    // say hello
    socket.emit('gnb:connect', uid, imtoken);

    // chance to do something
    socket.on('gnb:connected', function(msg) {
      $('.gnb').addClass('active');
    });

    // received a broadcast from Glome
    socket.on('gnb:broadcast', function(msg) {
      $('.gnb').addClass('unread');
    });

    // received a direct message from Glome
    socket.on('gnb:message', function(msg) {
      $('.gnb').addClass('unread');
    });

    // received a notification from Glome
    socket.on('gnb:notification', function(msg) {
      // parse the notification
      switch (msg) {
        case "paired":
        case "locked":
        case "brother":
        case "unpaired":
        case "unlocked":
        case "unbrother":
          document.location.reload(true);
          break;
      }
    });
  }
});
