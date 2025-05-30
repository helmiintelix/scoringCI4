import express from "express";
import mysql from "mysql2";
import { Server } from "socket.io";
import cors from "cors";
import http from "http";
import dotenv from "dotenv";
import sockerHandler from "./utils/socketHandler.js";

dotenv.config();

const PORT = process.env.APP_PORT || 3000;

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

const db = mysql.createConnection(dbConfig);
db.connect((err) => {
  if (err) {
    console.error("Database connection failed:", err);
    process.exit(1);
  } else {
    console.log("Connected to the database!");

    const server = http.createServer(app);
    const io = new Server(server, {
      transports: ["websocket"],
      cors: {
        origin: "*",
        methods: ["GET", "POST"],
      },
    });

    sockerHandler(io, db);

    app.get("/", (req, res) => {
      res.send("Socket server is running");
    });

    server.listen(PORT, () => {
      console.log(`Server is running on port ${PORT}`);
    });
  }
});
