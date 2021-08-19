<script>
  let csrf = "{{ csrf_token() }}";
  let increaseSearchCount = true;
  let increaseGetConversatinCount = true;
  let getConversationsCount = 10;
  let messageOffset = 0;
  let searchCount = 10;
  let message_scroll_wait = false;
  let noMoreMessage = false;
  let newMessageCount = 1;
  let maxFileSize = {{ config('messenger.max_file_size') }} * 1024;
  let urlInitial = "/messenger/"
  let maxFileAtOnce = {{ config('messenger.max_file_at_once') }};

  // $(document).on('load', function() {
  getConversations();
  $('#search-input').val('');
  // });
  //  ______________________
  // |                     |
  // | Navigation Function |
  // |_____________________|

  let message_container = $('#message_container');
  let about_container = $('#about-container');

  function hideMessageContainer() {
    message_container.addClass('hidden');
    message_container.addClass('sm:block');
  }

  function hideAboutContainer() {
    about_container.addClass('hidden');
    about_container.addClass('md:block');
  }

  function showMessageContainer(user) {
    if (!about_container.hasClass('hidden')) {
      hideAboutContainer();
    }
    if (message_container.hasClass('hidden')) {
      message_container.removeClass('hidden');
      message_container.removeClass('sm:block');
      $('#message-file').val('');
      $('#message-file').change();
    }

    messageOffset = 0;
    $('#message-box-nav').html('');
    $('#message-box').html('<div class="h-full w-full flex justify-center items-center text-gray-400">\
    Loading...\
    </div>');
    get_messages(user);
    $('#message-footer').removeClass('hidden');
  }

  function showAboutContainer() {
    if (about_container.hasClass('hidden') && !message_container.hasClass('hidden')) {
      about_container.removeClass('hidden');
      about_container.removeClass('md:block');
    } else {
      console.log('err')
    }
  }

  $('body').on('contextmenu', 'img', function() {
    return false;
  });

  // ________________________________
  // |                              |
  // | Conversation Search Function |
  // |______________________________|

  $('#search-input').on('keyup', function() {
    if (!$('#search-input').val().trim() == "") {
      if ($('#search-conversations').hasClass('hidden')) {
        $('#search-conversations').removeClass('hidden');
      }
      updateSearchConversation()
    } else {
      if (!$('#search-conversations').hasClass('hidden')) {
        $('#search-conversations').addClass('hidden');
      }
    }
  });

  function getConversations() {
    $.ajax({
      type: "post",
      url: urlInitial + "get-conversation",
      data: {
        "_token": csrf,
        "user": getConversationsCount
      },
      success: function(response) {
        if (response != 0) {
          $('#recent-users-container').html(response);
        } else {
          increaseGetConversatinCount = false;
        }
      }
    });
  }

  function updateSearchConversation() {
    $.ajax({
      type: "post",
      url: urlInitial + "search-conversations",
      data: {
        "_token": csrf,
        "limit": searchCount,
        "val": $('#search-input').val()
      },
      success: function(response) {
        if (response != 0) {
          $('#search-conversations').html(response);
        } else {
          increaseSearchCount = false;
        }
      }
    });
  }


  recent_conv_wait = false;
  $('#search-conversations').scroll(function() {
    if ($(this).scrollTop() + $(this).height() >= ($(this)[0].scrollHeight - 100) && recent_conv_wait ==
      false) {
      if (increaseSearchCount) {
        getConversationsCount += 5;
        getConversations();
      }
      recent_conv_wait = true;
      setTimeout(function() {
        recent_conv_wait = false;
      }, 1000);
    }
  })


  function get_messages(user) {
    if (!$('#search-conversations').hasClass('hidden')) {
      $('#search-conversations').addClass('hidden');
      $('#search-input').val('');
    }
    noMoreMessage = false;
    $('#message-box').attr('data-id', user);

    get_nav(user);
    get_about(user);

    $.ajax({
      type: "post",
      url: urlInitial + "messages",
      data: {
        "_token": csrf,
        "user": user,
        "offset": "0",
        "tz": new Date().getTimezoneOffset(),
        "limit": 20

      },
      success: function(response) {
        $('#message-box').html(response);
        $('#message-box').scrollTop($('#message-box')[0].scrollHeight);
        getConversations();
        message_scroll_wait = true;
        setTimeout(function() {
          message_scroll_wait = false;
        }, 500);
      },
    });
  }

  function get_nav(user) {
    $.ajax({
      type: "post",
      url: urlInitial + "messages-nav",
      data: {
        "_token": csrf,
        "user": user,
      },
      success: function(response) {
        $('#message-box-nav').html(response);
      },
    })
  }

  function get_about(user) {
    $.ajax({
      type: "post",
      url: urlInitial + "messages-about",
      data: {
        "_token": csrf,
        "user": user,
      },
      success: function(response) {
        $('#about-container').html(response);
      },
    })
  }

  $("#message-box").scroll(function() {
    if ($(this).scrollTop() <= 40 && !message_scroll_wait && !noMoreMessage) {

      messageOffset += 10
      $.ajax({
        type: "post",
        url: urlInitial + "messages",
        data: {
          "_token": csrf,
          "user": $('#message-box').attr('data-id'),
          "offset": messageOffset,
          "tz": new Date().getTimezoneOffset(),
          "limit": 20
        },
        success: function(response) {
          if (response != 0) {
            $('#message-box').prepend(response);
            message_scroll_wait = true;
            setTimeout(function() {
              message_scroll_wait = false;
            }, 1000);
          } else {
            noMoreMessage = true;
          }
        },
      });

      message_scroll_wait = true;
      setTimeout(function() {
        message_scroll_wait = false;
      }, 1000);
    }
  });

  $("#message-input").keydown(function(e) {
    const key = e.which || e.keyCode

    if (key === 13 && !e.shiftKey && window.innerWidth >= 787) {
      e.preventDefault();
      sendMessage();
    }

  });

  function sendMessage() {
    if ($("#message-file")[0].files.length <= 0) {
      sendMessageText();
    } else {
      sendMessageFile();
    }
  }

  function sendMessageText() {
    if ($('#message-input').val().trim() != "") {
      text = $('#message-input').val();
      html = '<div class="inline-flex mb-2 justify-end" id="new-message-' + newMessageCount + '" > <div class ="sent bg-blue-500 rounded-lg text-gray-100 px-2 text-sm py-1 max-w-65/100 cursor-pointer select-none" > \
        <span class=" whitespace-pre-wrap">' + text.trim() + '</span><div class="justify-end text-white text-dark flex items-center" style="font-size: 0.55rem; line-height: 0.55rem;">\
              <span class="text-sm">\
                <i class="bi bi-arrow-counterclockwise"></i>\
              </span>\
            </div>\
          </div>\
        </div>';

      newMessageCount++;
      if ($('#message-box').children("[id^=sent]").length == 0 && $('#message-box').children("[id^=received]").length ==
        0) {
        $('#message-box').html(html);
      } else {
        $('#message-box').append(html);
      }
      $('#message-box').scrollTop($('#message-box')[0].scrollHeight);
      let messagebox = $('#message-box').attr('data-id');
      $('#message-input').val('');

      $.ajax({
        type: "post",
        url: urlInitial + "/send",
        data: {
          "_token": csrf,
          "text": text,
          "to": messagebox
        },
        success: function(response) {
          getConversations();
          $('#new-message-' + (newMessageCount - 1)).hide();
          $.ajax({
            type: "post",
            url: urlInitial + "get-last",
            data: {
              "tz": new Date().getTimezoneOffset(),
              '_token': csrf,
            },
            success: function(response) {
              if ($('#message-box').attr('data-id') == messagebox) {
                let atBottom = $('#message-box')[0].scrollHeight - $('#message-box').scrollTop() - $(
                    '#message-box')
                  .outerHeight();
                $('#message-box').append(response);
                if (atBottom < 1) {
                  $('#message-box').scrollTop($('#message-box')[0].scrollHeight);
                }
              }
            }
          });
        }
      });
    }
  }

  function sendMessageFile() {
    files = $("#message-file")[0].files
    let messagebox = $('#message-box').attr('data-id');

    for (let i = 0; i < files.length; i++) {
      if (files[i]['size'] <= maxFileSize) {
        let formData = new FormData();
        formData.append('files', files[i]);
        formData.append('_token', csrf);
        formData.append('to', $("#message-box").attr("data-id"));

        html = '<div class="inline-flex mb-2 justify-end" id="new-message-' + i + '" ><div class="sent bg-blue-500 rounded-lg text-gray-100 px-2 text-sm py-1 max-w-65/100 cursor-pointer select-none" onclick="showMessageAction()">\
                  <span>sending attachment</span>\
                  <div class="justify-end text-white text-dark flex items-center" style="font-size: 0.55rem; line-height: 0.55rem;">\
                    17:19\
                  <span class="text-sm"><i class="bi bi-arrow-counterclockwise pl-1"></i></span>\
                  </div>\
              </div></div>';

        if ($('#message-box').children("[id^=sent]").length == 0 && $('#message-box').children("[id^=received]")
          .length == 0) {
          $('#message-box').html(html);
        } else {
          $('#message-box').append(html);
        }
        $.ajax({
          type: "post",
          url: urlInitial + "send-files",
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            $.ajax({
              type: "post",
              url: urlInitial + "get-last",
              data: {
                "tz": new Date().getTimezoneOffset(),
                '_token': csrf,
              },
              success: function(response) {
                if ($('#message-box').attr('data-id') == messagebox) {
                  let atBottom = $('#message-box')[0].scrollHeight - $('#message-box').scrollTop() - $(
                      '#message-box')
                    .outerHeight();
                  $('#message-box').append(response);
                  if (atBottom < 1) {
                    $('#message-box').scrollTop($('#message-box')[0].scrollHeight);
                  }
                }
                getConversations();
              }
            });
          },
          error: function(response) {
            response = JSON.parse(response.responseText);
            if (response['errors']['files'][0]) {
              new Swal('Oops', response['errors']['files'][0], 'error')
            } else {
              Swal.fire({
                toast: true,
                icon: 'error',
                position: 'bottom',
                text: 'There was an error uploading some of your files',
                showConfirmButton: false,
                timer: 2000
              });
            }
          }
        });
      } else {
        Swal.fire({
          toast: true,
          icon: 'warning',
          position: 'bottom',
          text: 'Some of your files were not uploaded because they were too large',
          showConfirmButton: false,
          timer: 2000
        });
        $('#new-message-' + i).remove();
      }
    }
    $('#message-file').val('');
    $('#message-file').change();
  }

  $('#message-file').change(function() {
    files = $('#message-file')[0].files;
    if (files.length > 0) {
      if ($('#files-viewer').hasClass('hidden')) {
        $('#files-viewer').removeClass('hidden');
      }
      if (files.length <= maxFileAtOnce) {
        for (let index = 0; index < files.length; index++) {
          $('#files-viewer').append('<span class="p-1 flex whitespace-no-wrap flex-shrink-0 bg-blue-400 text-white rounded-lg mr-2 text-sm">\
            <i class="bi bi-file-earmark mr-2"></i>' + files[index].name + '</span>');
        }
      } else {
        new Swal("Oops", 'Oops you can only select ' + maxFileAtOnce + ' files', "error");
        $('#message-file').val('');
      }
    } else {
      if (!$('#files-viewer').hasClass('hidden')) {
        $('#files-viewer').addClass('hidden');
        $('#files-viewer').html('');
      }
    }
  });

  function get_online_users() {
    $.ajax({
      type: "post",
      url: urlInitial + "online-users",
      data: {
        "_token": csrf
      },
      success: function(response) {
        $("#online-users-cont").html(response);
      }
    });
  }

  function deleteConversation() {
    user = $('#message-box').attr('data-id');
    $.ajax({
      type: "delete",
      url: urlInitial + "all/" + user,
      data: {
        '_token': csrf
      },
      success: function(response) {
        get_messages(user);
      }
    });
  }

  $("body").on("click", "[data-zoom]", function() {
    zoomed = $(this).attr('data-zoom') == 1;
    if (zoomed) {
      $('#full-overlay').addClass('hidden');
      $(this).attr('data-zoom', 0)
      this.style.width = "250px"
      this.style.height = "250px"
      this.style.zIndex = 1
      this.style.objectFit = "cover"
      $(this).removeClass('fixed top-0 left-0 max-h-full block')
    } else {
      $('#full-overlay').removeClass('hidden');
      $(this).attr('data-zoom', 1)
      this.style.width = "95%"
      this.style.height = "95%"
      this.style.objectFit = "contain"
      this.style.zIndex = 100
      $(this).addClass('fixed top-0 left-0 max-h-full block')
      Swal.fire({
        toast: true,
        position: 'bottom',
        text: 'Click image to close',
        showConfirmButton: false,
        timer: 800
      });
    }
  });

  function showSentMenu(id) {
    selectedMessageId = id
    selectedMessageType = "sent"
    if ($('#sent-' + id).find('.sent-message-text').length < 1) {
      $("#menu-copy-btn").hide();
    } else {
      $("#menu-download-btn").hide();
    }
    $('#message-menu').removeClass('hidden')
  }

  function showReceivedMenu(id) {
    selectedMessageId = id
    selectedMessageType = "received"
    $("#menu-unsend-btn").hide();
    if ($('#received-' + id).find('.received-message-text').length < 1) {
      $("#menu-copy-btn").hide();
    } else {
      $("#menu-download-btn").hide();
    }
    $('#message-menu').removeClass('hidden');
  }

  function copyMessage() {
    let text = $('#' + selectedMessageType + '-' + selectedMessageId).find('.' + selectedMessageType + '-message-text')
      .text();
    element = document.createElement('span');
    navigator.clipboard.writeText(text);
    hideMenu();
  }

  function deleteMessage() {

  }

  function unsendMessage() {

  }

  function hideMenu() {
    $("#menu-copy-btn").show();
    $("#menu-download-btn").show();
    $("#menu-unsend-btn").show();
    $('#message-menu').addClass('hidden');
  }

  function downloadAttachment() {
    src = $('#' + selectedMessageType + '-' + selectedMessageId).find('[data-src]').attr('data-src');
    window.open(urlInitial + 'download' + src, '_blank');
    hideMenu();
  }
</script>
