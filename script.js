body {
    font-family: Arial, sans-serif;
    text-align: center;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
}

header {
    background-color: #008080;
    color: white;
    padding: 10px; /* ← Fixed here */
    border-radius: 10px;
}

.chat-container {
    background: white;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    margin: 20px auto;
}

.message {
    padding: 10px;
    margin: 5px;
    border-radius: 5px;
}

.bot {
    background: #d1f7d1;
    text-align: left;
}


// ── Chat Bubble Logic ──

// Send on “Enter” key
document.getElementById("user-input").addEventListener("keypress", function(e) {
  if (e.key === "Enter") sendMessage();
});

// Main sendMessage() function
function sendMessage() {
  const input = document.getElementById("user-input");
  const chatBox = document.getElementById("chat-box");
  const text = input.value.trim();
  if (!text) return;

  // 1️⃣ Add user bubble
  const userDiv = document.createElement("div");
  userDiv.className = "message user";
  userDiv.innerText = text;
  chatBox.appendChild(userDiv);

  // 2️⃣ Simulate bot reply
  const botDiv = document.createElement("div");
  botDiv.className = "message bot";
  botDiv.innerText = "Let me explain the rule of Meem Saakinah...";
  chatBox.appendChild(botDiv);

  // 3️⃣ Clear input and scroll down
  input.value = "";
  chatBox.scrollTop = chatBox.scrollHeight;
}
