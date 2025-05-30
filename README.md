# CMS_CI4_v1.2

menggunakan CI versi 4.6.0 - source per 20 januari 2025 , bisa menggunakan php spark

-untuk menjalankan dev bisa menggunakan perintah
-php spark serve
-support untuk php 8.3(tested) dan 8.4

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

Copy the `Chat` folder to `/yourProject/app/Modules/Chat`.

## 5. Add Routes

Add the following routes to `/yourProject/app/Config/Routes.php`:

```php
#Chat modules
$routes->add('Chat/conversationChatView', '\App\Modules\Chat\Controllers\Chat::conversationChatView',['filter' => 'authfilter']);
$routes->add('Chat/chatListView', '\App\Modules\Chat\Controllers\Chat::chatListView',['filter' => 'authfilter']);
$routes->add('Chat/sendMessage', '\App\Modules\Chat\Controllers\Chat::sendMessage',['filter' => 'authfilter']);
$routes->add('Chat/updateSeenAt', '\App\Modules\Chat\Controllers\Chat::updateSeenAt',['filter' => 'authfilter']);
```

## 6. Add Chat Module Loader to JS

Add this code at the end of the file `/yourProject/public/private/js/admin_main.js`:

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

Add the following code at the end of the `jQuery(function($){ ... })` function in `/yourProject/public/private/js/admin_main.js`, just before the closing `});`:

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

Copy the `socket` folder to `/yourProject/public/assets/`.

## 9. Add Environment Variables for Socket Server

Add the following environment variables to your `/yourProject/.env` file:

```
HOST_SOCKET_SERVER=http://localhost
PORT_SOCKET_SERVER=3000
```

Adjust the values to match the host and port of your running socket server.

## 10. Copy JS Files

Copy the files `socket.js` and `chat.js` to `/yourProject/public/private/js/`.

## 11. Add Chat Module Scripts to View

Add the new code from `nide_admin_view.php` to `/yourProject/app/Views/nice_admin_view.php`.  
The new code is wrap with comments `#Chat modules`, and adjust as needed to fit your existing code.

**Example:**

```html
<!-- #Chat modules -->
<script src="<?= base_url(); ?>assets/socket/socket.io.min.js?v=<?= rand(); ?>"></script>
<script src="<?= base_url(); ?>private/js/chat.js?v=<?= rand(); ?>"></script>
<script src="<?= base_url(); ?>private/js/socket.js?v=<?= rand(); ?>"></script>
<!-- #Chat modules -->
```

---

**Done! Your chat module should now be integrated.**

## Getting started

To make it easy for you to get started with GitLab, here's a list of recommended next steps.

Already a pro? Just edit this README.md and make it your own. Want to make it easy? [Use the template at the bottom](#editing-this-readme)!

## Add your files

- [ ] [Create](https://docs.gitlab.com/ee/user/project/repository/web_editor.html#create-a-file) or [upload](https://docs.gitlab.com/ee/user/project/repository/web_editor.html#upload-a-file) files
- [ ] [Add files using the command line](https://docs.gitlab.com/ee/gitlab-basics/add-file.html#add-a-file-using-the-command-line) or push an existing Git repository with the following command:

```
cd existing_repo
git remote add origin https://git.ecentrix.net/helmi/cms_ci4_v1.2.git
git branch -M main
git push -uf origin main
```

## Integrate with your tools

- [ ] [Set up project integrations](https://git.ecentrix.net/helmi/cms_ci4_v1.2/-/settings/integrations)

## Collaborate with your team

- [ ] [Invite team members and collaborators](https://docs.gitlab.com/ee/user/project/members/)
- [ ] [Create a new merge request](https://docs.gitlab.com/ee/user/project/merge_requests/creating_merge_requests.html)
- [ ] [Automatically close issues from merge requests](https://docs.gitlab.com/ee/user/project/issues/managing_issues.html#closing-issues-automatically)
- [ ] [Enable merge request approvals](https://docs.gitlab.com/ee/user/project/merge_requests/approvals/)
- [ ] [Automatically merge when pipeline succeeds](https://docs.gitlab.com/ee/user/project/merge_requests/merge_when_pipeline_succeeds.html)

## Test and Deploy

Use the built-in continuous integration in GitLab.

- [ ] [Get started with GitLab CI/CD](https://docs.gitlab.com/ee/ci/quick_start/index.html)
- [ ] [Analyze your code for known vulnerabilities with Static Application Security Testing(SAST)](https://docs.gitlab.com/ee/user/application_security/sast/)
- [ ] [Deploy to Kubernetes, Amazon EC2, or Amazon ECS using Auto Deploy](https://docs.gitlab.com/ee/topics/autodevops/requirements.html)
- [ ] [Use pull-based deployments for improved Kubernetes management](https://docs.gitlab.com/ee/user/clusters/agent/)
- [ ] [Set up protected environments](https://docs.gitlab.com/ee/ci/environments/protected_environments.html)

---

# Editing this README

When you're ready to make this README your own, just edit this file and use the handy template below (or feel free to structure it however you want - this is just a starting point!). Thank you to [makeareadme.com](https://www.makeareadme.com/) for this template.

## Suggestions for a good README

Every project is different, so consider which of these sections apply to yours. The sections used in the template are suggestions for most open source projects. Also keep in mind that while a README can be too long and detailed, too long is better than too short. If you think your README is too long, consider utilizing another form of documentation rather than cutting out information.

## Name

Choose a self-explaining name for your project.

## Description

Let people know what your project can do specifically. Provide context and add a link to any reference visitors might be unfamiliar with. A list of Features or a Background subsection can also be added here. If there are alternatives to your project, this is a good place to list differentiating factors.

## Badges

On some READMEs, you may see small images that convey metadata, such as whether or not all the tests are passing for the project. You can use Shields to add some to your README. Many services also have instructions for adding a badge.

## Visuals

Depending on what you are making, it can be a good idea to include screenshots or even a video (you'll frequently see GIFs rather than actual videos). Tools like ttygif can help, but check out Asciinema for a more sophisticated method.

## Installation

Within a particular ecosystem, there may be a common way of installing things, such as using Yarn, NuGet, or Homebrew. However, consider the possibility that whoever is reading your README is a novice and would like more guidance. Listing specific steps helps remove ambiguity and gets people to using your project as quickly as possible. If it only runs in a specific context like a particular programming language version or operating system or has dependencies that have to be installed manually, also add a Requirements subsection.

## Usage

Use examples liberally, and show the expected output if you can. It's helpful to have inline the smallest example of usage that you can demonstrate, while providing links to more sophisticated examples if they are too long to reasonably include in the README.

## Support

Tell people where they can go to for help. It can be any combination of an issue tracker, a chat room, an email address, etc.

## Roadmap

If you have ideas for releases in the future, it is a good idea to list them in the README.

## Contributing

State if you are open to contributions and what your requirements are for accepting them.

For people who want to make changes to your project, it's helpful to have some documentation on how to get started. Perhaps there is a script that they should run or some environment variables that they need to set. Make these steps explicit. These instructions could also be useful to your future self.

You can also document commands to lint the code or run tests. These steps help to ensure high code quality and reduce the likelihood that the changes inadvertently break something. Having instructions for running tests is especially helpful if it requires external setup, such as starting a Selenium server for testing in a browser.

## Authors and acknowledgment

Show your appreciation to those who have contributed to the project.

## License

For open source projects, say how it is licensed.

## Project status

If you have run out of energy or time for your project, put a note at the top of the README saying that development has slowed down or stopped completely. Someone may choose to fork your project or volunteer to step in as a maintainer or owner, allowing your project to keep going. You can also make an explicit request for maintainers.

> > > > > > > README.md
