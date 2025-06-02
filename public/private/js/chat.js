let dataListChat = [];

function addChatBubble(message, sender, userLogin, userTarget, sentAt = null) {
  const idElement = `chatMessages_${userTarget}`;
  console.log(idElement, "idElement");
  const time = sentAt
    ? new Date(sentAt)
        .toLocaleString("sv-SE", { hour12: false })
        .replace(" ", " ")
    : new Date().toLocaleString("sv-SE", { hour12: false }).replace(" ", " ");
  const isUser = sender.toLowerCase() === userLogin.toLowerCase();
  const bubbleClass = isUser
    ? "bg-warning text-white ms-auto"
    : "bg-secondary text-white me-auto";
  const alignClass = isUser ? "ms-auto" : "me-auto";
  const html = `${
    userLogin == "newMessage"
      ? `<div class="d-flex mb-2 justify-content-center newMessageChat" style="font-size: 0.60em; opacity: 0.7;">- New Message -</div>`
      : `<div class="d-flex mb-2">
                <div class="${bubbleClass} rounded px-3 py-2 ${alignClass}" style="max-width: 70%;">
                    <div class="fw-bold small mb-1">${
                      isUser ? "You" : sender
                    }</div>
                    ${$("<div>").text(message).html()}
                    <div class="text-end small mt-1" style="font-size: 0.60em; opacity: 0.7;">${time}</div>
                </div>
            </div>`
  }`;
  $(`#${idElement}`).append(html);
  $(`#${idElement}`).scrollTop($(`#${idElement}`)[0].scrollHeight);
}

function generateListChat(param) {
  console.log("start generate list");
  $("#list-group-chat").html("");

  const list = param.map((val, index) => {
    return `<div class="card mb-1" style="cursor: pointer;" id="chat-${
      val.userId
    }" onclick="chatHistoryAgent('${val.userId}')">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-item-center fs-6">
                            <p style="font-weight: bold;"> ${val.userName} </p>
                            <p style="font-size: 0.60em; opacity: 0.7;"> ${
                              val.createdTime
                                ? new Date(val.createdTime)
                                    .toLocaleString("sv-SE", { hour12: false })
                                    .replace(" ", " ")
                                : "-"
                            } </p>
                        </div>
                        <div class="d-flex justify-content-between align-item-center">
                            <p class="fs-6 text-secondary m-0">${
                              val.messageFrom == AGENT_ID ? "You: " : ""
                            } ${limitChar(val.messageContent) || "-"}</p>
                            <div 
                                class="dot-pulse" 
                                id="dot-pulse-${val.userId}" 
                                style="display:${
                                  !val.seenAt &&
                                  val.messageContent &&
                                  val.messageTo == AGENT_ID
                                    ? "block"
                                    : "none"
                                };"></div>
                        </div>
                    </div>
                </div>`;
  });

  $("#list-group-chat").append(list);
  console.log("end generate list");
}

function updateChatListItem(newMessage) {
  let found = false;
  for (let i = 0; i < dataListChat.length; i++) {
    if (dataListChat[i].userId === newMessage.messageFrom) {
      dataListChat[i].messageContent = newMessage.messageContent;
      dataListChat[i].createdTime = newMessage.sentAt;
      dataListChat[i].seenAt = newMessage.seenAt;
      dataListChat[i].messageTo = newMessage.messageTo;
      dataListChat[i].messageFrom = newMessage.messageFrom;
      found = true;
      break;
    }
  }

  if (!found) {
    // Jika agent belum ada di list, tambahkan
    dataListChat.push({
      userId: newMessage.messageFrom,
      userName: newMessage.userName || newMessage.messageFrom,
      messageContent: newMessage.messageContent,
      createdTime: newMessage.sentAt,
      seenAt: newMessage.seenAt,
      messageTo: newMessage.messageTo,
      messageFrom: newMessage.messageFrom,
    });
  }
  // Urutkan dataListChat DESC by createdTime
  dataListChat.sort(
    (a, b) => new Date(b.createdTime) - new Date(a.createdTime)
  );
  console.log(newMessage, "newMessage");
  console.log(dataListChat, "dataListChat after update");
  generateListChat(dataListChat); // render ulang list chat
}

function updateStatusMessage(messageId, status = "SENT") {
  // Update seen_at di database
  $.ajax({
    url: GLOBAL_MAIN_VARS["BASE_URL"] + "Chat/updateSeenAt",
    type: "POST",
    data: { messageId: messageId, status: status },
    success: function (response) {
      console.log("Seen at updated successfully", response);
    },
    error: function (xhr, status, error) {
      console.error("Error updating seen at:", error);
    },
  });
}

function checkSeenAt() {
  const hasEmptySeenAt = dataListChat.some(
    (chat) =>
      (!chat.seenAt || chat.seenAt === "" || chat.seenAt === null) &&
      chat.messageContent &&
      chat.messageTo == AGENT_ID
  );
  console.log("Ada seenAt kosong:", hasEmptySeenAt);
  if (hasEmptySeenAt) {
    $("#iconNotifNewChat").show();
  } else {
    $("#iconNotifNewChat").hide();
  }
  return hasEmptySeenAt;
}
