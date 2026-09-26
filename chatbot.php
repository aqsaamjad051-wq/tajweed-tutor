<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>AI Powered Tajweed Learning</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0fff4;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      height: 100vh;
      overflow: hidden;
    }

    header {
      background-color: #007B5E;
      padding: 20px;
      text-align: center;
      color: white;
      width: 100%;
      flex-shrink: 0;
    }

    header h1 {
      margin: 0;
      font-size: 36px;
    }

    .main-container {
      display: flex;
      width: 100%;
      max-width: 1200px;
      height: calc(100vh - 100px);
      padding: 30px;
      gap: 20px;
      box-sizing: border-box;
    }

    .chatbot-area {
      flex: 2;
      background: white;
      border-radius: 15px;
      padding: 20px;
      max-height: 100%;
      overflow-y: auto;
      box-sizing: border-box;
      display: flex;
      flex-direction: column;
      gap: 15px;
      position: relative;
    }

    .chat-bubble {
      padding: 15px 20px;
      border-radius: 20px;
      font-size: 16px;
      max-width: 600px;
      word-wrap: break-word;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .chat-bubble.tutor {
      background-color: #e2f3e4;
      justify-content: flex-start;
    }

    .chat-bubble.user {
      background-color: #f1f1f1;
      justify-content: flex-start;
    }

    .chat-bubble img.icon {
      width: 40px;
      flex-shrink: 0;
    }

    .visual-answer {
      background-color: #e2f3e4;
      border-radius: 20px;
      max-width: 600px;
      padding: 15px 20px;
      display: flex;
      gap: 20px;
      align-items: center;
    }

    .lesson-image {
      width: 150px;
      object-fit: contain;
      flex-shrink: 0;
      border-radius: 12px;
    }

    .text-card {
      flex: 1;
      text-align: left;
    }

    .example-arabic {
      font-size: 22px;
      color: #007B5E;
      font-weight: bold;
      line-height: 1.6;
      margin-bottom: 10px;
      text-align: center;
    }

    .explanation-text {
      font-weight: bold;
      font-size: 16px;
      color: #333;
    }

    .input-section {
      display: flex;
      gap: 10px;
      margin-top: 20px;
      max-width: 600px;
      align-items: center;
    }

    .input-section input {
      flex: 1;
      padding: 12px 15px;
      font-size: 16px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    .mic-btn, .send-btn {
      background-color: #007B5E;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      padding: 10px 15px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .mic-btn img {
      width: 24px;
      height: 24px;
      display: block;
    }
.lang-select {
  background-color: #007B5E;     /* Green like Send button */
  color: white;                  /* White text */
  border: none;
  border-radius: 8px;
  padding: 10px 15px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
}
.lang-select option {
  background-color: white;       /* White dropdown menu */
  color: black;                  /* Black text for contrast */
  font-weight: normal;
}
.mic-options {
      position: absolute;
      bottom: 60px;
      right: 110px;
      background: #fff;
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      font-size: 14px;
      z-index: 999;
      display: none;
    }

    .quran-image-container {
      flex: 1;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      padding-left: 10px;
      height: 100%;
    }

    .quran-image-container img {
      height: 100%;
      max-height: 100%;
      width: auto;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0,0,0,0.15);
    }

    .logout-link {
      position: absolute;
      top: 20px;
      right: 30px;
      z-index: 1000;
    }

    .logout-link a {
      background-color: #007B5E;
      color: white;
      text-decoration: none;
      padding: 10px 25px;
      border-radius: 8px;
      font-weight: bold;
      font-size: 16px;
      animation: blink 1.5s infinite;
    }

    @keyframes blink {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.3; }
    }
    .dark-toggle {
  position: absolute;
  top: 20px;
  left: 30px;
  z-index: 1000;
}

.dark-toggle button {
  background-color: #222;
  color: #fff;
  padding: 10px 20px;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  cursor: pointer;
  font-weight: bold;
}

body.dark-mode {
  background-color: #1c1c1c;
  color: #eaeaea;
}

body.dark-mode .chatbot-area {
  background-color: #2a2a2a;
}

body.dark-mode .chat-bubble.tutor {
  background-color: #3d3d3d;
}

body.dark-mode .chat-bubble.user {
  background-color: #444;
}

body.dark-mode .input-section input {
  background-color: #333;
  color: #fff;
  border: 1px solid #666;
}

body.dark-mode .lang-select,
body.dark-mode .send-btn,
body.dark-mode .mic-btn {
  background-color: #444;
  color: #fff;
}

body.dark-mode .lang-select option {
  color: black;
}

body.dark-mode .visual-answer {
  background-color: #3d3d3d;
}

body.dark-mode .logout-link a {
  background-color: #444;
}

body.dark-mode .dark-toggle button {
  background-color: #f0f0f0;
  color: #000;
}

  </style>
</head>
<body>
  <header>
    <div class="dark-toggle">
  <button onclick="toggleDarkMode()">🌙 Dark Mode</button>
</div>

    <h1>🌿 AI Powered Tajweed Learning 🌿</h1>
  </header>

  <div class="logout-link">
    <a href="logout.php">Logout</a>
  </div>

  <div class="main-container">
    <main class="chatbot-area" id="chatArea">
<div class="chat-bubble tutor">
  <p>👋 <strong>Assalamualaikum!</strong> I am your Tajweed tutor. How can I help you today?</p>
</div>
<div class="chat-bubble tutor">
        <p><strong>While using this app:</strong><br />
        1. Ask your Tajweed questions clearly<br />
        2. Use the mic or type your question<br />
        3. You will get AI-powered feedback instantly</p>
      </div>

      <div class="visual-answer">
        <img src="images/laptop.jpg" alt="Tajweed Rule" class="lesson-image" />
        <div class="text-card">
          <p class="example-arabic">اللّٰهُ غَفُوْرٌ رَّحِیْمٌ</p>
          <p class="explanation-text"> This is Ikhfa of Meem Saakinah.</p>
        </div>
      </div>

      <div class="input-section">
        <select class="lang-select" id="langSelect" onchange="saveLangPreference()">
  <option disabled>Select Language</option>
  <option value="en">🌐 English</option>
  <option value="ur">🌙 Urdu</option>
  <option value="ar">📖 Arabic</option>
</select>

<div style="position: relative; flex: 1; display: flex; flex-direction: column;">
  <input type="text" id="textInput" placeholder="Type your question..." oninput="showSuggestions()" autocomplete="off"
    style="padding: 12px 15px; font-size: 16px; border-radius: 8px; border: 1px solid #ccc; outline: none; box-shadow: none;" />
  
  <ul id="suggestionList" style="
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ccc;
    border-radius: 8px;
    margin: 5px 0 0 0;
    padding: 0;
    list-style: none;
    z-index: 999;
    max-height: 160px;
    overflow-y: auto;
  "></ul>
</div>


        <button class="mic-btn" onclick="startDictation()" title="Use Mic">
          <img src="images/mic.jpg" alt="Mic Icon" />
        </button>
        <button class="send-btn" onclick="sendMessage()">Send</button>
      </div>

      <div class="mic-options" id="micOptions">
        <p><strong>Mic Tips:</strong></p>
        <ul>
          <li>Speak slowly and clearly</li>
          <li>Make sure your mic is allowed in browser</li>
          <li>Try in a quiet environment</li>
        </ul>
      </div>
      <div style="text-align: center; margin-top: 20px;">
  <button onclick="location.href='rules.html'" style="
    background-color: #007B5E;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
  ">
    📘 Learn Tajweed Rules
  </button>
</div>

    </main>

    <div class="quran-image-container">
      <img src="images/quran.jpg" alt="Quran Image" />
    </div>
  </div>

 <script>
  function saveLangPreference() {
    const lang = document.getElementById("langSelect").value;
    localStorage.setItem("selectedLang", lang);
  }

  window.onload = function () {
    const chatArea = document.getElementById("chatArea");
    const textInput = document.getElementById("textInput");
    const micOptions = document.getElementById("micOptions");
    const langSelect = document.getElementById("langSelect");

    const savedLang = localStorage.getItem("selectedLang");
    if (savedLang) langSelect.value = savedLang;

    // ✅ Track currently playing audio and speech
    let currentAudio = null;
    let currentSpeech = null;

    function scrollToBottom() {
      chatArea.scrollTop = chatArea.scrollHeight;
    }

    function sendMessage() {
      // ✅ Stop any previous audio/speech
      if (currentAudio) {
        currentAudio.pause();
        currentAudio.currentTime = 0;
        currentAudio = null;
      }
      speechSynthesis.cancel();

      const msg = textInput.value.trim();
      const lang = langSelect.value || "en";
      if (!msg) return;

      const userMsg = document.createElement("div");
      userMsg.className = "chat-bubble user";
      userMsg.innerHTML = "<p><strong>" + msg + "</strong></p>";
      chatArea.insertBefore(userMsg, document.querySelector(".input-section"));
      textInput.value = "";
      scrollToBottom();

      const waitingMsg = document.createElement("div");
      waitingMsg.className = "chat-bubble tutor";
      waitingMsg.innerHTML = "<p><em>⏳ Waiting for reply...</em></p>";
      chatArea.insertBefore(waitingMsg, document.querySelector(".input-section"));
      scrollToBottom();

      fetch("http://127.0.0.1:5000/chat", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ message: msg, lang: lang })
      })
        .then(response => response.json())
        .then(data => {
          waitingMsg.remove();

          const botReply = document.createElement("div");
          botReply.className = "chat-bubble tutor";
          botReply.innerHTML = "<p>" + data.reply + "</p>";
          chatArea.insertBefore(botReply, document.querySelector(".input-section"));
          scrollToBottom();

          if (!data.matched && data.reply && "speechSynthesis" in window) {
            const spokenReply = data.reply
              .replace(/<[^>]*>/g, "")
              .replace(/🔹|📖/g, "")
              .replace(/\{diamond\}/g, "")
              .replace(/\s+/g, " ")
              .trim();

            const reminderUtterance = new SpeechSynthesisUtterance(spokenReply);
            reminderUtterance.lang = lang === "ar" ? "ar-SA" : (lang === "ur" ? "ur-PK" : "en-US");
            currentSpeech = reminderUtterance;
            speechSynthesis.speak(reminderUtterance);
          }

          if (data.highlight) {
            const highlightBubble = document.createElement("div");
            highlightBubble.className = "chat-bubble tutor";
            highlightBubble.innerHTML = `<p><strong> 🚨 Highlighted Portion:</strong><br><span style='font-size:20px;color:#007B5E;'>${data.highlight}</span></p>`;
            chatArea.insertBefore(highlightBubble, document.querySelector(".input-section"));
            scrollToBottom();
          }

          const knownRules = ["ikhfa", "idgham", "iqlab", "qalqala", "ghunna", "madd", "waqf", "noon saakin", "meem saakin", "tajweed"];
          const ruleKey = knownRules.find(rule => msg.toLowerCase().includes(rule));

          const replyText = data.reply;
          const exampleAudioUrl = data.example_audio;

          const ruleOnly = replyText
            .replace(/🔹/g, "")
            .replace(/📖/g, "")
            .replace(/Open Book[\s\S]*$/gi, "")
            .replace(/Example Ayah[\s\S]*$/gi, "")
            .replace(/<[^>]*>/g, "")
            .replace(/[.۔٫٬؟]/g, "")
            .replace(/\u200E|\u200F|\u202A|\u202B|\u202C|\u2066|\u2067|\u2068|\u2069/g, "")
            .replace(/[\u{1F300}-\u{1F6FF}]/gu, "")
            .replace(/\s+/g, " ")
            .trim();

          const exampleLabels = {
            "en": "Example Ayah",
            "ar": "مثال آية",
            "ur": "Example Ayah"
          };
          const label = exampleLabels[lang] || "Example Ayah";

          if (data.matched && lang === "ur") {
            const ruleAudio = new Audio("audio/" + ruleKey.replace(/ /g, "_") + ".mp3?v=" + new Date().getTime());
            currentAudio = ruleAudio;
            ruleAudio.play();

            ruleAudio.onended = () => {
              const exampleLabelSpeech = new SpeechSynthesisUtterance(label);
              exampleLabelSpeech.lang = "en-US";
              currentSpeech = exampleLabelSpeech;
              exampleLabelSpeech.onend = () => {
                setTimeout(() => {
                  if (exampleAudioUrl) {
                    const audio = new Audio(exampleAudioUrl + "?v=" + new Date().getTime());
                    currentAudio = audio;
                    audio.play();

                    audio.onended = () => {
                      setTimeout(() => {
                        if (data.rule_explanation) {
                          const speakRule = new SpeechSynthesisUtterance(data.rule_explanation);
                          speakRule.lang = "en-US";
                          currentSpeech = speakRule;
                          speechSynthesis.speak(speakRule);
                        }
                      }, 1200);
                    };
                  }
                }, 1200);
              };
              speechSynthesis.speak(exampleLabelSpeech);
            };
          } else if (data.matched && "speechSynthesis" in window) {
            const ruleSpeech = new SpeechSynthesisUtterance(ruleOnly);
            ruleSpeech.lang = lang === "ar" ? "ar-SA" : "en-US";
            currentSpeech = ruleSpeech;

            ruleSpeech.onend = () => {
              setTimeout(() => {
                const exampleLabelSpeech = new SpeechSynthesisUtterance(label);
                exampleLabelSpeech.lang = lang === "ar" ? "ar-SA" : "en-US";
                currentSpeech = exampleLabelSpeech;

                exampleLabelSpeech.onend = () => {
                  setTimeout(() => {
                    if (exampleAudioUrl) {
                      const audio = new Audio(exampleAudioUrl + "?v=" + new Date().getTime());
                      currentAudio = audio;
                      audio.play();

                      audio.onended = () => {
                        setTimeout(() => {
                          if (data.rule_explanation) {
                            const speakRule = new SpeechSynthesisUtterance(data.rule_explanation);
                            speakRule.lang = lang === "ar" ? "ar-SA" : "en-US";
                            currentSpeech = speakRule;
                            speechSynthesis.speak(speakRule);
                          }
                        }, 1200);
                      };
                    }
                  }, 1000);
                };
                speechSynthesis.speak(exampleLabelSpeech);
              }, 2000);
            };

            speechSynthesis.speak(ruleSpeech);
          }
        })
        .catch(error => {
          waitingMsg.remove();
          console.error("Error:", error);
          const errorMsg = document.createElement("div");
          errorMsg.className = "chat-bubble tutor";
          errorMsg.innerHTML = '<img src="images/sad.png" alt="Sad Emoji" class="icon"><p><strong>Sorry, something went wrong.</strong></p>';
          chatArea.insertBefore(errorMsg, document.querySelector(".input-section"));
          scrollToBottom();
        });
    }

    function startDictation() {
      micOptions.style.display = "block";
      micOptions.scrollIntoView({ behavior: "smooth", block: "center" });

      setTimeout(() => {
        micOptions.style.display = "none";
      }, 4000);

      try {
        const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        recognition.lang = "en-US";
        recognition.onresult = function (event) {
          textInput.value = event.results[0][0].transcript;
        };
        recognition.start();
      } catch (error) {
        alert("Speech recognition not supported in your browser.");
      }
    }

    document.querySelector(".send-btn").addEventListener("click", sendMessage);
    textInput.addEventListener("keydown", function (event) {
      if (event.key === "Enter") {
        event.preventDefault();
        sendMessage();
      }
    });

    window.sendMessage = sendMessage;
    window.startDictation = startDictation;
  };

  const rulesSuggestions = [
    "What is Ikhfa?",
    "Explain Idgham with example",
    "What is Qalqala?",
    "Tell me about Ghunna",
    "What is Iqlab?",
    "Define Madd in Tajweed",
    "Rules of Noon Saakin",
    "What is Meem Saakin rule?",
    "How to apply Waqf?",
    "What are Tajweed rules?"
  ];

  function showSuggestions() {
    const input = document.getElementById("textInput");
    const list = document.getElementById("suggestionList");
    const query = input.value.toLowerCase();
    list.innerHTML = "";

    if (query.length === 0) {
      list.style.display = "none";
      return;
    }

    const filtered = rulesSuggestions.filter(rule => rule.toLowerCase().includes(query));

    if (filtered.length === 0) {
      list.style.display = "none";
      return;
    }

    filtered.forEach(rule => {
      const li = document.createElement("li");
      li.textContent = rule;
      li.style.padding = "10px";
      li.style.cursor = "pointer";
      li.style.fontSize = "15px";
      li.onmouseover = () => li.style.backgroundColor = "#e2f3e4";
      li.onmouseout = () => li.style.backgroundColor = "white";
      li.onclick = () => {
        input.value = rule;
        list.innerHTML = "";
        list.style.display = "none";
      };
      list.appendChild(li);
    });

    list.style.display = "block";
  }

  document.addEventListener("click", function (e) {
    const isInput = e.target.closest("#textInput");
    if (!isInput) {
      const list = document.getElementById("suggestionList");
      if (list) {
        list.innerHTML = "";
        list.style.display = "none";
      }
    }
  });

  function toggleDarkMode() {
    const body = document.body;
    body.classList.toggle("dark-mode");
    localStorage.setItem("darkMode", body.classList.contains("dark-mode") ? "on" : "off");
  }
</script>
</body> 