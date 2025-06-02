import { readFileSync } from "fs";
import https from "https";
import express from "express";
import mysql from "mysql2";
import { Server } from "socket.io";
import cors from "cors";
import http from "http";
import dotenv from "dotenv";
import sockerHandler from "./utils/socketHandler.js";

dotenv.config();

const PORT = process.env.APP_PORT || 3000;
const PROTOCOL = process.env.PROTOCOL || "http";
const KEY_SSL =
  process.env.KEY_SSL || "/home/ecentrix/ssl/ssl_ecentrix.net.pem";
const CERT_SSL =
  process.env.CERT_SSL || "/home/ecentrix/ssl/ssl_ecentrix.net.pem";

const app = express();
const corsOptions = {
  origin: "*",
};
app.use(cors(corsOptions));
app.use(express.json());

const dbConfig = {
  host: process.env.DB_HOST,
  database: process.env.DB_NAME,
  user: process.env.DB_USER,
  password: process.env.DB_PASSWORD,
};

const db = mysql.createPool(dbConfig);
console.log("Connected to the database!");

app.get("/", (req, res) => {
  res.send("Socket server is running");
});
if (PROTOCOL == "https") {
  const serverHttps = https.createServer({
    key: readFileSync(KEY_SSL),
    cert: readFileSync(CERT_SSL),
  });
  const io = new Server(serverHttps, {
    transports: ["websocket"],
    cors: {
      origin: "*",
      methods: ["GET", "POST"],
    },
  });

  sockerHandler(io, db);

  serverHttps.listen(PORT, () => {
    console.log(`Server https is running on port ${PORT}`);
  });
} else {
  const server = http.createServer(app);
  const io = new Server(server, {
    transports: ["websocket"],
    cors: {
      origin: "*",
      methods: ["GET", "POST"],
    },
  });

  sockerHandler(io, db);

  server.listen(PORT, () => {
    console.log(`Server http is running on port ${PORT}`);
  });
}
