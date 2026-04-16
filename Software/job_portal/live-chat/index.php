<!-- // Job Portal file: live-chat\index.php -->
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Live Chat</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<header>
  <a href="/job_portal">Return</a>
</header>

<div class="chat-wrap">
  <div class="chat">
    <div class="chat-header">
      <div class="title">Job Portal Help Bot</div>
      <button class="ghost" id="resetBtn">Reset</button>
    </div>

    <div class="chat-body" id="chatBody"></div>

    <div class="chat-actions" id="chatActions"></div>

    <div class="chat-input">
      <input id="textInput" placeholder="Type here (optional)..." />
      <button id="sendBtn">Send</button>
    </div>
  </div>
</div>

<!-- Call form modal -->
<div class="modal" id="callModal">
  <div class="modal-card">
    <div class="modal-head">
      <div class="modal-title">Request a Phone Call</div>
      <button class="ghost" id="closeModal">âœ•</button>
    </div>

    <div class="modal-body">
      <label>Name</label>
      <input id="callName" placeholder="Your name" />

      <label>Phone</label>
      <input id="callPhone" placeholder="07xxx..." />

      <label>Best time to call</label>
      <input id="callBestTime" placeholder="Tomorrow 2pm" />

      <button id="submitCall" class="primary">Create Ticket</button>
      <div class="hint">Your details will be saved to support_tickets.</div>
    </div>
  </div>
</div>

<script src="script.js"></script>
</body>
</html>

