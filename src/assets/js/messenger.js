// $(document).on('load', function() {
getConversations();
$("#search-input").val("");
get_online_users();
// });
//  ______________________
// |                     |
// | Navigation Function |
// |_____________________|

let message_container = $("#message_container");
let about_container = $("#about-container");

function hideMessageContainer() {
  message_container.addClass("hidden");
  message_container.addClass("sm:block");
}

function hideAboutContainer() {
  about_container.addClass("hidden");
  about_container.addClass("md:block");
}

function showMessageContainer(user) {
  if (!about_container.hasClass("hidden")) {
    hideAboutContainer();
  }
  if (message_container.hasClass("hidden")) {
    message_container.removeClass("hidden");
    message_container.removeClass("sm:block");
    $("#message-file").val("");
    $("#message-file").change();
  }

  messageOffset = 0;
  $("#message-box-nav").html("");
  $("#message-box").html(
    '<div class="h-full w-full flex justify-center items-center text-gray-400">\
    Loading...\
    </div>'
  );
  get_messages(user);
  $("#message-footer").removeClass("hidden");
}

function showAboutContainer() {
  if (
    about_container.hasClass("hidden") &&
    !message_container.hasClass("hidden")
  ) {
    about_container.removeClass("hidden");
    about_container.removeClass("md:block");
  } else {
    console.log("err");
  }
}

$("body").on("contextmenu", "img", function () {
  return false;
});

// ________________________________
// |                              |
// | Conversation Search Function |
// |______________________________|

$("#search-input").on("keyup", function () {
  if (!$("#search-input").val().trim() == "") {
    if ($("#search-conversations").hasClass("hidden")) {
      $("#search-conversations").removeClass("hidden");
    }
    updateSearchConversation();
  } else {
    if (!$("#search-conversations").hasClass("hidden")) {
      $("#search-conversations").addClass("hidden");
    }
  }
});

function getConversations() {
  $.ajax({
    type: "post",
    url: urlInitial + "get-conversation",
    data: {
      _token: csrf,
      user: getConversationsCount,
    },
    success: function (response) {
      if (response != 0) {
        $("#recent-users-container").html(response);
      } else {
        increaseGetConversatinCount = false;
      }
    },
  });
}

function updateSearchConversation() {
  $.ajax({
    type: "post",
    url: urlInitial + "search-conversations",
    data: {
      _token: csrf,
      limit: searchCount,
      val: $("#search-input").val(),
    },
    success: function (response) {
      if (response != 0) {
        $("#search-conversations").html(response);
      } else {
        increaseSearchCount = false;
      }
    },
  });
}

recent_conv_wait = false;
$("#search-conversations").scroll(function () {
  if (
    $(this).scrollTop() + $(this).height() >= $(this)[0].scrollHeight - 100 &&
    recent_conv_wait == false
  ) {
    if (increaseSearchCount) {
      getConversationsCount += 5;
      getConversations();
    }
    recent_conv_wait = true;
    setTimeout(function () {
      recent_conv_wait = false;
    }, 1000);
  }
});

function get_messages(user) {
  if (!$("#search-conversations").hasClass("hidden")) {
    $("#search-conversations").addClass("hidden");
    $("#search-input").val("");
  }
  noMoreMessage = false;
  $("#message-box").attr("data-id", user);

  get_nav(user);
  get_about(user);

  $.ajax({
    type: "post",
    url: urlInitial + "messages",
    data: {
      _token: csrf,
      user: user,
      offset: "0",
      tz: tz,
      limit: 20,
    },
    success: function (response) {
      $("#message-box").html(response);
      $("#message-box").scrollTop($("#message-box")[0].scrollHeight);
      getConversations();
      message_scroll_wait = true;
      setTimeout(function () {
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
      _token: csrf,
      user: user,
    },
    success: function (response) {
      $("#message-box-nav").html(response);
    },
  });
}

function get_about(user) {
  $.ajax({
    type: "post",
    url: urlInitial + "messages-about",
    data: {
      _token: csrf,
      user: user,
    },
    success: function (response) {
      $("#about-container").html(response);
    },
  });
}

$("#message-box").scroll(function () {
  if ($(this).scrollTop() <= 40 && !message_scroll_wait && !noMoreMessage) {
    messageOffset += 10;
    $.ajax({
      type: "post",
      url: urlInitial + "messages",
      data: {
        _token: csrf,
        user: $("#message-box").attr("data-id"),
        offset: messageOffset,
        tz: tz,
        limit: 20,
      },
      success: function (response) {
        if (response != 0) {
          $("#message-box").prepend(response);
          message_scroll_wait = true;
          setTimeout(function () {
            message_scroll_wait = false;
          }, 1000);
        } else {
          noMoreMessage = true;
        }
      },
    });

    message_scroll_wait = true;
    setTimeout(function () {
      message_scroll_wait = false;
    }, 1000);
  }
});

$("#message-input").keydown(function (e) {
  const key = e.which || e.keyCode;

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
  if ($("#message-input").val().trim() != "") {
    text = $("#message-input").val();
    html =
      '<div class="inline-flex mb-2 justify-end" id="new-message-' +
      newMessageCount +
      '" > <div class ="sent bg-blue-500 rounded-lg text-gray-100 px-2 text-sm py-1 max-w-65/100 cursor-pointer select-none" > \
        <span class=" whitespace-pre-wrap">' +
      text.trim() +
      '</span><div class="justify-end text-white text-dark flex items-center" style="font-size: 0.55rem; line-height: 0.55rem;">\
              <span class="text-sm">\
                <i class="bi bi-arrow-counterclockwise"></i>\
              </span>\
            </div>\
          </div>\
        </div>';

    newMessageCount++;
    if (
      $("#message-box").children("[id^=sent]").length == 0 &&
      $("#message-box").children("[id^=received]").length == 0
    ) {
      $("#message-box").html(html);
    } else {
      $("#message-box").append(html);
    }
    $("#message-box").scrollTop($("#message-box")[0].scrollHeight);
    let messagebox = $("#message-box").attr("data-id");
    $("#message-input").val("");

    $.ajax({
      type: "post",
      url: urlInitial + "send",
      data: {
        _token: csrf,
        text: text,
        to: messagebox,
      },
      success: function (response) {
        getConversations();
        $("#new-message-" + (newMessageCount - 1)).hide();
        $.ajax({
          type: "post",
          url: urlInitial + "get-last",
          data: {
            tz: tz,
            _token: csrf,
          },
          success: function (response) {
            if ($("#message-box").attr("data-id") == messagebox) {
              let atBottom =
                $("#message-box")[0].scrollHeight -
                $("#message-box").scrollTop() -
                $("#message-box").outerHeight();
              $("#message-box").append(response);
              if (atBottom < 1) {
                $("#message-box").scrollTop($("#message-box")[0].scrollHeight);
              }
            }
          },
        });
      },
    });
  }
}

function sendMessageFile() {
  files = $("#message-file")[0].files;
  let messagebox = $("#message-box").attr("data-id");

  for (let i = 0; i < files.length; i++) {
    if (files[i]["size"] <= maxFileSize) {
      let formData = new FormData();
      formData.append("files", files[i]);
      formData.append("_token", csrf);
      formData.append("to", $("#message-box").attr("data-id"));

      html =
        '<div class="inline-flex mb-2 justify-end" id="new-message-' +
        i +
        '" ><div class="sent bg-blue-500 rounded-lg text-gray-100 px-2 text-sm py-1 max-w-65/100 cursor-pointer select-none" onclick="showMessageAction()">\
                  <span>sending attachment</span>\
                  <div class="justify-end text-white text-dark flex items-center" style="font-size: 0.55rem; line-height: 0.55rem;">\
                    17:19\
                  <span class="text-sm"><i class="bi bi-arrow-counterclockwise pl-1"></i></span>\
                  </div>\
              </div></div>';

      if (
        $("#message-box").children("[id^=sent]").length == 0 &&
        $("#message-box").children("[id^=received]").length == 0
      ) {
        $("#message-box").html(html);
      } else {
        $("#message-box").append(html);
      }
      $.ajax({
        type: "post",
        url: urlInitial + "send-files",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          $.ajax({
            type: "post",
            url: urlInitial + "get-last",
            data: {
              tz: tz,
              _token: csrf,
            },
            success: function (response) {
              if ($("#message-box").attr("data-id") == messagebox) {
                let atBottom =
                  $("#message-box")[0].scrollHeight -
                  $("#message-box").scrollTop() -
                  $("#message-box").outerHeight();
                $("#message-box").append(response);
                if (atBottom < 1) {
                  $("#message-box").scrollTop(
                    $("#message-box")[0].scrollHeight
                  );
                }
              }
              getConversations();
            },
          });
        },
        error: function (response) {
          response = JSON.parse(response.responseText);
          if (response["errors"]["files"][0]) {
            new Swal("Oops", response["errors"]["files"][0], "error");
          } else {
            Swal.fire({
              toast: true,
              icon: "error",
              position: "bottom",
              text: "There was an error uploading some of your files",
              showConfirmButton: false,
              timer: 2000,
            });
          }
        },
      });
    } else {
      Swal.fire({
        toast: true,
        icon: "warning",
        position: "bottom",
        text: "Some of your files were not uploaded because they were too large",
        showConfirmButton: false,
        timer: 2000,
      });
      $("#new-message-" + i).remove();
    }
  }
  $("#message-file").val("");
  $("#message-file").change();
}

$("#message-file").change(function () {
  files = $("#message-file")[0].files;
  if (files.length > 0) {
    if ($("#files-viewer").hasClass("hidden")) {
      $("#files-viewer").removeClass("hidden");
    }
    if (files.length <= maxFileAtOnce) {
      for (let index = 0; index < files.length; index++) {
        $("#files-viewer").append(
          '<span class="p-1 flex whitespace-no-wrap flex-shrink-0 bg-blue-400 text-white rounded-lg mr-2 text-sm">\
            <i class="bi bi-file-earmark mr-2"></i>' +
            files[index].name +
            "</span>"
        );
      }
    } else {
      new Swal(
        "Oops",
        "Oops you can only select " + maxFileAtOnce + " files",
        "error"
      );
      $("#message-file").val("");
    }
  } else {
    if (!$("#files-viewer").hasClass("hidden")) {
      $("#files-viewer").addClass("hidden");
      $("#files-viewer").html("");
    }
  }
});

function get_online_users() {
  $.ajax({
    type: "post",
    url: urlInitial + "online-users",
    data: {
      _token: csrf,
    },
    success: function (response) {
      $("#online-users-cont").html(response);
    },
  });
}

function deleteConversation() {
  user = $("#message-box").attr("data-id");
  $.ajax({
    type: "delete",
    url: urlInitial + "all/" + user,
    data: {
      _token: csrf,
    },
    success: function (response) {
      get_messages(user);
    },
  });
}

$("body").on("click", "[data-zoom]", function () {
  zoomed = $(this).attr("data-zoom") == 1;
  if (zoomed) {
    $("#full-overlay").addClass("hidden");
    $(this).attr("data-zoom", 0);
    this.style.width = "250px";
    this.style.height = "250px";
    this.style.zIndex = 1;
    this.style.objectFit = "cover";
    $(this).removeClass("fixed top-0 left-0 max-h-full block");
  } else {
    $("#full-overlay").removeClass("hidden");
    $(this).attr("data-zoom", 1);
    this.style.width = "95%";
    this.style.height = "95%";
    this.style.objectFit = "contain";
    this.style.zIndex = 100;
    $(this).addClass("fixed top-0 left-0 max-h-full block");
    Swal.fire({
      toast: true,
      position: "bottom",
      text: "Click image to close",
      showConfirmButton: false,
      timer: 800,
    });
  }
});

function showSentMenu(id) {
  selectedMessageId = id;
  selectedMessageType = "sent";
  if ($("#sent-" + id).find(".sent-message-text").length < 1) {
    $("#menu-copy-btn").hide();
  } else {
    $("#menu-download-btn").hide();
  }
  $("#message-menu").removeClass("hidden");
}

function showReceivedMenu(id) {
  selectedMessageId = id;
  selectedMessageType = "received";
  $("#menu-unsend-btn").hide();
  if ($("#received-" + id).find(".received-message-text").length < 1) {
    $("#menu-copy-btn").hide();
  } else {
    $("#menu-download-btn").hide();
  }
  $("#message-menu").removeClass("hidden");
}

function copyMessage() {
  let text = $("#" + selectedMessageType + "-" + selectedMessageId)
    .find("." + selectedMessageType + "-message-text")
    .text();
  element = document.createElement("span");
  navigator.clipboard.writeText(text);
  hideMenu();
}

function deleteMessage() {
  id = selectedMessageId;
  $.ajax({
    type: "delete",
    url: urlInitial + "messages/" + id,
    data: {
      _token: csrf,
    },
    success: function (response) {
      let box = $("#" + selectedMessageType + "-" + selectedMessageId).remove();
    },
  });
  hideMenu();
  getConversations();
}

function unsendMessage() {
  id = selectedMessageId;
  $.ajax({
    type: "delete",
    url: urlInitial + "messages/unsend/" + id,
    data: {
      _token: csrf,
    },
    success: function (response) {
      let box = $("#" + selectedMessageType + "-" + selectedMessageId).remove();
    },
  });
  hideMenu();
  getConversations();
}

function hideMenu() {
  $("#menu-copy-btn").show();
  $("#menu-download-btn").show();
  $("#menu-unsend-btn").show();
  $("#message-menu").addClass("hidden");
}

function downloadAttachment() {
  src = $("#" + selectedMessageType + "-" + selectedMessageId)
    .find("[data-src]")
    .attr("data-src");
  window.open(urlInitial + "download" + src, "_blank");
  hideMenu();
}

window.Echo.private("message").listen(".messenger", (e) => {
  console.log(e);
});

window.Echo.private("message").listenForWhisper(".typing", (e) => {
  console.log(e);
});

function isTyping() {
  setTimeout(function () {
    window.Echo.private("message").whisper("typing", {
      user: userId,
      typing: true,
    });
    stoppedTyping();
  }, 300);
}

function stoppedTyping() {
  clearTimeout(typingTimer);
  typingTimer = setTimeout(function () {
    window.Echo.private("message").whisper("typing", {
      user: userId,
      typing: false,
    });
  }, 900);
}

Echo.private("message")
  .listenForWhisper("typing", (e) => {
    if (e.typing) {
      $("#conversation-text-" + e.user).addClass("hidden");
      $("#conversation-typing-" + e.user).removeClass("hidden");
      if (e.user == $("#message-box").attr("data-id")) {
        $("#message-box-online").addClass("hidden");
        $("#message-box-typing").removeClass("hidden");
      }
    } else {
      $("#conversation-text-" + e.user).removeClass("hidden");
      $("#conversation-typing-" + e.user).addClass("hidden");
      if (e.user == $("#message-box").attr("data-id")) {
        $("#message-box-online").removeClass("hidden");
        $("#message-box-typing").addClass("hidden");
      }
    }
  })
  .listen(".delivered-message", function (e) {
    if ($("#message-box").attr("data-id") == e.id) {
      markAsDelivered();
    }
  })
  .listen(".read-message", function (e) {
    if ($("#message-box").attr("data-id") == e.from && userId == e.to) {
      markAsRead();
    }
  })
  .listen(".new-message", function (e) {
    if (
      $("#message-box").attr("data-id") == e.from &&
      userId == e.to &&
      !message_container.hasClass("hidden")
    ) {
      newMessage(e.from);
    } else if (userId == e.to) {
      getConversations();
    }
  });

$("#message-input").on("keyup", function () {
  if ($(this).val().trim() != "") {
    isTyping();
  }
});

Echo.join("online")
  .joining((user) => {
    $.ajax({
      type: "PUT",
      url: urlInitial + "user/" + user.id + "/" + 1,
      data: {
        _token: csrf,
      },
      success: function (response) {
        updateMessageable(response.id);
      },
    });
  })
  .leaving((user) => {
    $.ajax({
      type: "PUT",
      url: urlInitial + "user/" + user.id + "/" + 0,
      data: {
        _token: csrf,
      },
      success: function (response) {
        if (
          $("#recent-users-container").find("#conversation-" + response.id)
            .length > 0
        ) {
          getConversations();
          get_online_users();
          get_nav(response.id);
        }
      },
    });
  });

function updateMessageable(user) {
  $.ajax({
    type: "POST",
    url: urlInitial + "friend/",
    data: {
      _token: csrf,
      id: user,
    },
    success: function (response) {
      if (response == 1) {
        getConversations();
        get_online_users();
        get_nav(user);
      }
    },
  });
}

function markAsDelivered() {
  $(".bi-check").each(function () {
    $(this).removeClass("bi-check");
    $(this).addClass("bi-check-all");
  });
}

function markAsRead() {
  $(".bi-check").each(function () {
    $(this).removeClass("bi-check");
    $(this).addClass("bi-check-all text-green-900");
  });
  $(".bi-check-all").each(function () {
    $(this).removeClass("bi-check-all");
    $(this).addClass("bi-check-all text-green-900");
  });
}

function newMessage(user) {
  $.ajax({
    type: "POST",
    url: urlInitial + "get-last-received",
    data: {
      _token: csrf,
      id: user,
      tz: tz,
    },
    success: function (response) {
      if (
        $("#message-box").scrollTop() + $("#message-box")[0].offsetHeight >=
        $("#message-box")[0].scrollHeight
      ) {
        $("#message-box").append(response);
        $("#message-box").scrollTop($("#message-box")[0].scrollHeight);
      } else {
        $("#message-box").append(response);
        Swal.fire({
          toast: true,
          position: "bottom",
          text: "New Message",
          showConfirmButton: false,
          timer: 2000,
        });
      }
    },
  });
}
