"use strict";

(function ($, window, document, undefined) {
  $(document).ready(function () {
    // Load overlay.
    xbeeLoadOverlay(); // Activate cookie plugin.

    $('#xbee-settings-cookie #xbee-activate-cookie').on('click', function (e) {
      var data = {
        'action': 'xbee_cookie_activate'
      }; // Response message.

      var message = '';
      $.ajax({
        url: xbeeCookieAdminParams.ajaxURL,
        method: 'post',
        data: data,
        beforeSend: function beforeSend() {
          xbeeShowOverlay();
        },
        success: function success(response) {
          xbeeHideOverlay();
          response = JSON.parse(response);

          if (response.result) {
            message = xbeeCookieAdminParams.messages.successCookieActivate; // Hide activation button, and display deactivation button.

            $('#xbee-settings-cookie .activate-cookie #xbee-activate-cookie').addClass('hide');
            $('#xbee-settings-cookie .activate-cookie #xbee-deactivate-cookie').removeClass('hide');
          } else if (response.errors.length > 0) {
            message = response.errors.join('. ');
          } else {
            message = xbeeCookieAdminParams.messages.errorCookieActivate;
          } // Response notification.


          xbeeNotification('activate-cookie', 'success', message);
        },
        error: function error() {
          xbeeHideOverlay(); // Response notification.

          xbeeNotification('activate-cookie', 'error', xbeeCookieAdminParams.messages.errorCookieActivate);
        }
      });
    }); // Dectivate cookie plugin.

    $('#xbee-settings-cookie #xbee-deactivate-cookie').on('click', function (e) {
      var data = {
        'action': 'xbee_cookie_deactivate'
      }; // Response message.

      var message = '';
      $.ajax({
        url: xbeeCookieAdminParams.ajaxURL,
        method: 'post',
        data: data,
        beforeSend: function beforeSend() {
          xbeeShowOverlay();
        },
        success: function success(response) {
          xbeeHideOverlay();
          response = JSON.parse(response);

          if (response.result) {
            message = xbeeCookieAdminParams.messages.successCookieDectivate; // Hide deactivation button, and display activation button.

            $('#xbee-settings-cookie .activate-cookie #xbee-deactivate-cookie').addClass('hide');
            $('#xbee-settings-cookie .activate-cookie #xbee-activate-cookie').removeClass('hide');
          } else if (response.errors.length > 0) {
            message = response.errors.join('. ');
          } else {
            message = xbeeCookieAdminParams.messages.errorCookieDeactivate;
          } // Response notification.


          xbeeNotification('activate-cookie', response.status, message);
        },
        error: function error() {
          xbeeHideOverlay(); // Response notification.

          xbeeNotification('activate-cookie', 'error', xbeeCookieAdminParams.messages.errorCookieDeactivate);
        }
      });
    }); // Connect campaign.

    $('#xbee-settings-cookie #xbee-connect-campaign').on('click', function (e) {
      var data = {
        'action': 'xbee_cookie_connect_campaign'
      }; // Response message.

      var message = '';
      $.ajax({
        url: xbeeCookieAdminParams.ajaxURL,
        method: 'post',
        data: data,
        beforeSend: function beforeSend() {
          xbeeShowOverlay();
        },
        success: function success(response) {
          xbeeHideOverlay();
          response = JSON.parse(response);

          if (response.result) {
            message = xbeeCookieAdminParams.messages.successCampaignConnect; // Reload synced options.

            if (response.html.length > 0) {
              $('#xbee-cookie-synced-options').html(response.html);
            }

            $('#xbee-settings-cookie .connect-actions').addClass('hide');
            $('#xbee-settings-cookie .disconnect-actions').removeClass('hide');
            $('#xbee-settings-cookie .xbee-connect-indicator').addClass('connected');
          } else if (response.errors.length > 0) {
            message = response.errors.join('. ');
          } else if ('error_multiple_campaigns' === response.code) {
            message = xbeeCookieAdminParams.messages.errorMultipleCampaigns;
          } else if ('info_campaign_update_not_changed' === response.code) {
            message = xbeeCookieAdminParams.messages.infoCampaignUpdateNotChanged;
          } else {
            message = xbeeCookieAdminParams.messages.errorCampaignConnect;
          } // Response notification.


          xbeeNotification('connect-campaign', response.status, message);
        },
        error: function error() {
          xbeeHideOverlay(); // Response notification.

          xbeeNotification('connect-campaign', 'error', xbeeCookieAdminParams.messages.errorCampaignConnect);
        }
      });
    }); // Disconnect campaign.

    $('#xbee-settings-cookie #xbee-disconnect-campaign').on('click', function (e) {
      var data = {
        'action': 'xbee_cookie_disconnect_campaign'
      }; // Response message.

      var message = '';
      $.ajax({
        url: xbeeCookieAdminParams.ajaxURL,
        method: 'post',
        data: data,
        beforeSend: function beforeSend() {
          xbeeShowOverlay();
        },
        success: function success(response) {
          xbeeHideOverlay();
          response = JSON.parse(response);

          if (response.result) {
            message = xbeeCookieAdminParams.messages.successCampaignDisconnect; // Reload synced options.

            if (response.html.length > 0) {
              $('#xbee-cookie-synced-options').html(response.html);
            }

            $('#xbee-settings-cookie .connect-actions').removeClass('hide');
            $('#xbee-settings-cookie .disconnect-actions').addClass('hide');
            $('#xbee-settings-cookie .xbee-connect-indicator').removeClass('connected');
          } else if (response.errors.length > 0) {
            message = response.errors.join('. ');
          } else {
            message = xbeeCookieAdminParams.messages.errorCampaignDisconnect;
          } // Response notification.


          xbeeNotification('connect-campaign', response.status, message);
        },
        error: function error() {
          xbeeHideOverlay(); // Response notification.

          xbeeNotification('connect-campaign', 'error', xbeeCookieAdminParams.messages.errorCampaignDisconnect);
        }
      });
    }); // Refresh campaign data.

    $('#xbee-settings-cookie #xbee-refresh-campaign').on('click', function (e) {
      var data = {
        'action': 'xbee_cookie_refresh_campaign',
        'campaignId': xbeeCookieAdminParams.campaignId
      }; // Response message.

      var message = '';
      $.ajax({
        url: xbeeCookieAdminParams.ajaxURL,
        method: 'post',
        data: data,
        beforeSend: function beforeSend() {
          xbeeShowOverlay();
        },
        success: function success(response) {
          xbeeHideOverlay();
          response = JSON.parse(response);

          if (response.result) {
            message = xbeeCookieAdminParams.messages.successCampaignUpdate; // Reload synced options.

            if (response.html.length > 0) {
              $('#xbee-cookie-synced-options').html(response.html);
            }
          } else if (response.errors.length > 0) {
            message = response.errors.join('. ');
          } else if ('error_multiple_campaigns' === response.code) {
            message = xbeeCookieAdminParams.messages.errorMultipleCampaigns;
          } else if ('info_campaign_update_not_changed' === response.code) {
            message = xbeeCookieAdminParams.messages.infoCampaignUpdateNotChanged;
          } else {
            message = xbeeCookieAdminParams.messages.errorCampaignUpdate;
          } // Response notification.


          xbeeNotification('connect-campaign', response.status, message);
        },
        error: function error() {
          xbeeHideOverlay(); // Response notification.

          xbeeNotification('connect-campaign', 'error', xbeeCookieAdminParams.messages.errorCampaignUpdate);
        }
      });
    }); // Update position cookie.

    $('#xbee-settings-cookie').on('click', '.cookie-position .position', function (e) {
      var position = e.target; // Disable if connected to XcooBee.

      if ($(position).closest('.section').hasClass('disabled')) {
        return;
      }

      if (!$(position).hasClass('clicked')) {
        $('#xbee-settings-cookie .cookie-position .position').each(function () {
          $(this).removeClass('clicked');
        });
        $('#xbee-settings-cookie .cookie-position [name="xbee_cookie_position"]').val($(position).data('position'));
        $(position).addClass('clicked');
      }
    });
  });
})(jQuery, window, document);