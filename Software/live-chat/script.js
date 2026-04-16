// Job Portal file: live-chat\script.js
const chatBody = document.getElementById("chatBody");
const chatActions = document.getElementById("chatActions");
const textInput = document.getElementById("textInput");
const sendBtn = document.getElementById("sendBtn");
const resetBtn = document.getElementById("resetBtn");

// modal
const callModal = document.getElementById("callModal");
const closeModal = document.getElementById("closeModal");
const submitCall = document.getElementById("submitCall");
const callName = document.getElementById("callName");
const callPhone = document.getElementById("callPhone");
const callBestTime = document.getElementById("callBestTime");

// Add a chat bubble to the chat history.
function addBubble(sender, text) {
  const div = document.createElement("div");
  div.className = `bubble ${sender}`;
  div.textContent = text;
  chatBody.appendChild(div);
  chatBody.scrollTop = chatBody.scrollHeight;
}

// Render action buttons below the chat for the current conversation state.
function setActions(options = []) {
  chatActions.innerHTML = "";
  options.forEach(opt => {
    // Special case: open the callback request form modal.
    if (opt.type === "form") {
      const b = document.createElement("button");
      b.className = "btn primary";
      b.textContent = opt.label;
      b.onclick = () => openModal();
      chatActions.appendChild(b);
      return;
    }

    const b = document.createElement("button");
    b.className = "btn";
    b.textContent = opt.label;
    b.onclick = () => sendPayload(opt.value, opt.label);
    chatActions.appendChild(b);
  });
}

// Send a JSON request to the chat API and return the parsed response.
async function callApi(data) {
  const res = await fetch("api.php", {
    method: "POST",
    headers: {"Content-Type":"application/json"},
    body: JSON.stringify(data)
  });
  return await res.json();
}

// Initialize the chat interface on first page load.
async function start() {
  const data = await callApi({ action: "message", payload: "" });
  // If first load doesnâ€™t auto-start, just click Start
  addBubble("bot", "Press Start to begin.");
  setActions([{label:"Start", value:"START"}]);
}

// Send a predefined payload option to the chat backend.
async function sendPayload(value, labelForUser = null) {
  if (labelForUser) addBubble("user", labelForUser);

  const data = await callApi({ action: "message", payload: value, text: "" });
  addBubble("bot", data.message);
  setActions(data.options);
}

// Send a free-text message entered by the user.
async function sendText() {
  const t = textInput.value.trim();
  if (!t) return;
  addBubble("user", t);
  textInput.value = "";
  const data = await callApi({ action: "message", payload: "", text: t });
  addBubble("bot", data.message);
  setActions(data.options);
}

// Show and hide the callback request modal.
function openModal() {
  callModal.classList.add("show");
}
function closeModalFn() {
  callModal.classList.remove("show");
}

closeModal.addEventListener("click", closeModalFn);
callModal.addEventListener("click", (e) => {
  if (e.target === callModal) closeModalFn();
});

// Handle callback request form submission and create a support ticket.
submitCall.addEventListener("click", async () => {
  const name = callName.value.trim();
  const phone = callPhone.value.trim();
  const best_time = callBestTime.value.trim();

  const data = await callApi({
    action: "create_ticket",
    name,
    phone,
    best_time
  });

  closeModalFn();

  addBubble("bot", data.message || "Ticket submitted.");

 
  chatActions.innerHTML = "";

  const callBtn = document.createElement("a");
  callBtn.href = "tel:+447999494358";  
  callBtn.textContent = " Call Support Now";
  callBtn.className = "btn primary";
  callBtn.style.textDecoration = "none";

  const restartBtn = document.createElement("button");
  restartBtn.textContent = "Start over";
  restartBtn.className = "btn";
  restartBtn.onclick = () => sendPayload("RESTART", "Start over");

  chatActions.appendChild(callBtn);
  chatActions.appendChild(restartBtn);

  // Clear the callback form after submission.
  callName.value = "";
  callPhone.value = "";
  callBestTime.value = "";
});

// Wire up chat input and reset controls.
sendBtn.addEventListener("click", sendText);
textInput.addEventListener("keydown", (e) => {
  if (e.key === "Enter") sendText();
});

resetBtn.addEventListener("click", async () => {
  const data = await callApi({ action: "reset" });
  addBubble("bot", data.message);
  setActions(data.options);
});

// Initialize the chat widget on page load.
start();

