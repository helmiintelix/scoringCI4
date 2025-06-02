export default function registerSocketHandlers(io, db) {
  io.on("connection", (socket) => {
    console.log("Socket connected", socket.id);

    // udpate socket_id di database saat koneksi
    socket.on("updateSocket", async (data, callback) => {
      db.query(
        "UPDATE cc_user SET socket_id = ? WHERE id = ?",
        [socket.id, data.id],
        (err, result) => {
          if (err) {
            callback({ success: false, message: err.message });
          } else {
            callback({
              success: true,
              message: `success update socket ID ${result.affectedRows}`,
            });
          }
        }
      );
    });

    socket.on("chatMessage", (message, callback) => {
      console.log("Chat message received:", message);
      // Validasi minimal
      if (
        !message ||
        !message.socketIdTarget ||
        !message.messageContent ||
        typeof message.messageContent !== "string"
      ) {
        callback({ success: false, message: "Invalid message data" });
        return;
      }

      // Cegah kirim ke diri sendiri
      if (message.socketIdTarget === socket.id) {
        callback({
          success: false,
          message: "Cannot send message to yourself",
        });
        return;
      }

      // Kirim ke user tujuan
      io.to(message.socketIdTarget).emit("chatMessage", message);

      callback({ success: true, message: "Message sent to user" });
    });

    socket.on("disconnect", () => {
      console.log("Socket disconnected", socket.id);
      db.query(
        "UPDATE cc_user SET socket_id = NULL WHERE socket_id = ?",
        [socket.id],
        (err, result) => {
          if (err) {
            console.error("Error updating socket_id on disconnect:", err);
          } else {
            console.log("Socket ID cleared from database", result.affectedRows);
          }
        }
      );
    });
  });
}
