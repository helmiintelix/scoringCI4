const socket = io(HOST_SOCKET_SERVER + ":" + PORT_SOCKET_SERVER, {
  transports: ["websocket"],
});

socket.on("connect", () => {
  console.log("Connected to socket server with ID:", socket.id, USER_ID);

  socket.emit(
    "updateSocket",
    {
      id: USER_ID, // Pastikan USER_ID sudah didefinisikan sebelumnya
    },
    (response) => {
      console.log("Update socket response:", response);
    }
  );
});

socket.on("chatMessage", (message) => {
  console.log("Chat message received:", message);
  const idElement = `chatMessages_${message.messageFrom}`;

  if ($(`#${idElement}`).length > 0) {
    // Elemen dengan id 'chatMessages_agent_id' ada
    if ($(`#${idElement} .newMessageChat`).length === 0) {
      addChatBubble(
        message.messageContent,
        message.messageFrom,
        "newMessage", // Pastikan AGENT_ID sudah didefinisikan sebelumnya
        message.messageFrom,
        message.sentAt
      );
    }

    addChatBubble(
      message.messageContent,
      message.messageFrom,
      AGENT_ID, // Pastikan AGENT_ID sudah didefinisikan sebelumnya
      message.messageFrom,
      message.sentAt
    );

    updateStatusMessage(message.messageId, "READ");
  } else {
    // Elemen tidak ada
    showInfo("you have a new message from " + message.messageFrom);
    updateChatListItem(message);
    updateStatusMessage(message.messageId, "SENT");
  }
  checkSeenAt();
});

socket.on("disconnect", () => {
  console.log("Disconnected from socket server", socket.id, USER_ID);
});
