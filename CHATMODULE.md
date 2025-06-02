# How to Implement Chat Module

## Features

- **Team Leader Login:**  
  Displays a list of agents under the team leader. Team leader can chat with their agents.
- **Agent Login:**  
  Displays the team leader. Agent can chat with their team leader.
- **New Message Notification:**  
  Shows notification when a new message arrives.
- **Unread Message Indicator:**  
  Displays an indicator for unread messages.

---

## 1. Create `cms_chat_conversation` Table in Database

```sql
CREATE TABLE IF NOT EXISTS `cms_chat_conversation` (
  `messageId` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `messageFrom` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `messageTo` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `messageType` enum('textWithAttachment','text','attachment') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `messageContent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `attachmentId` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `attachmentName` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_time` datetime DEFAULT NULL,
  `created_by` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_message` enum('SENT','READ') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sentAt` datetime DEFAULT NULL,
  `seenAt` datetime DEFAULT NULL,
  `conversationId` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`messageId`) USING BTREE,
  KEY `messageId` (`messageId`) USING BTREE,
  KEY `created_by` (`created_by`) USING BTREE,
  KEY `status_message` (`status_message`) USING BTREE,
  KEY `sentAt` (`sentAt`) USING BTREE,
  KEY `seenAt` (`seenAt`) USING BTREE,
  KEY `fromNumber` (`messageFrom`) USING BTREE,
  KEY `toNumber` (`messageTo`) USING BTREE,
  KEY `insert_time` (`created_time`) USING BTREE,
  KEY `inbound_message_id` (`conversationId`) USING BTREE,
  KEY `sentAt_messageFrom` (`messageFrom`,`sentAt`),
  KEY `sentAt_messageTo` (`messageTo`,`sentAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## 2. Alter `cc_user` Table in Database

```sql
ALTER TABLE `cc_user`
ADD COLUMN `socket_id` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
ADD KEY `socket_id` (`socket_id`)
```

## 3. Create Socket Server in Node.js

- Copy the `socket_server` folder to your server app.
- Configure environment variables in the `.env` file.
- Run `npm install` to install dependencies.
- Run `npm start` to start the server.

## 4. Copy the Chat Module

Copy the `Chat` folder at `/projectApp/app/Modules/Chat` to your project with the same name and folder structure.

## 5. Add Routes

Add the following routes at `/projectApp/app/Config/Routes.php` to your routes project:

```php
#Chat modules
$routes->add('Chat/conversationChatView', '\App\Modules\Chat\Controllers\Chat::conversationChatView',['filter' => 'authfilter']);
$routes->add('Chat/chatListView', '\App\Modules\Chat\Controllers\Chat::chatListView',['filter' => 'authfilter']);
$routes->add('Chat/sendMessage', '\App\Modules\Chat\Controllers\Chat::sendMessage',['filter' => 'authfilter']);
$routes->add('Chat/updateSeenAt', '\App\Modules\Chat\Controllers\Chat::updateSeenAt',['filter' => 'authfilter']);
```

## 6. Add Chat Module Loader to JS

Add this code at the end of the file `/projectApp/public/private/js/admin_main.js` to your `admin_main.js` file:

```js
function loadChatModule() {
  $("#chatCanvas")
    .html("")
    .load(
      GLOBAL_MAIN_VARS["BASE_URL"] + "Chat/chatListView",
      function (responseTxt, statusTxt, xhr) {
        if (statusTxt == "success") {
          changeTheme(GLOBAL_THEME_MODE);
          $("#chatCanvas").show();
        } else if (statusTxt == "error") {
          $("#chatCanvas").html("<i>something wrong</i>");
        }
      }
    );
}

$("#canvasLinkBackChat").click(() => {
  loadChatModule();
});
```

## 7. Initialize Chat Module on Page Load

Add the following code at the end of the `jQuery(function($){ ... })` function in `/projectApp/public/private/js/admin_main.js`, just before the closing `});` to your `admin_main.js` file:

```js
jQuery(function ($) {
  // -- your existing code --
  loadChatModule();
});
```

**Explanation:**

- Make sure to place `loadChatModule();` inside the `jQuery(function($){ ... })` block.
- Insert it right before the closing `});` at the end of the function.

## 8. Copy Socket Folder

Copy the `socket` folder at `/projectApp/public/assets/` to your project with the same name and folder structure.

## 9. Add Environment Variables for Socket Server

Add the following environment variables to your `/projectApp/.env` file:

```
HOST_SOCKET_SERVER=http://localhost
PORT_SOCKET_SERVER=3000
```

Adjust the values to match the host and port of your running socket server.

## 10. Copy JS Files

Copy the files `socket.js` and `chat.js` at `/projectApp/public/private/js/` to your project with the same name and folder structure.

## 11. Add Chat Module Scripts to View

Add the new code from `nice_admin_view.php` at `/projectApp/app/Views/nice_admin_view.php` to your project.
The new code is wrap with comments `#Chat modules`, and adjust as needed to fit your existing code.

**Example:**

```html
<!-- #Chat modules -->
<script src="<?= base_url(); ?>assets/socket/socket.io.min.js?v=<?= rand(); ?>"></script>
<script src="<?= base_url(); ?>private/js/chat.js?v=<?= rand(); ?>"></script>
<script src="<?= base_url(); ?>private/js/socket.js?v=<?= rand(); ?>"></script>
<!-- #Chat modules -->
```

make sure you have copied all the new code that is wrapped with `#Chat modules` to your `nice_admin_view.php` file.

**Done! Your chat module should now be integrated.**
