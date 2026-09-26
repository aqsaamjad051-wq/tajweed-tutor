from flask import Flask, request, jsonify
from flask_cors import CORS
import difflib
import sqlite3

app = Flask(__name__, static_folder="example_audio", static_url_path="/example_audio")
CORS(app)

DB = "progress.db"

# -------------------------------
# Initialize SQLite table
# -------------------------------
def init_db():
    conn = sqlite3.connect(DB)
    conn.execute("""
        CREATE TABLE IF NOT EXISTS attempts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
            user TEXT,
            lang TEXT,
            score INTEGER
        )
    """)
    conn.close()

init_db()

# -------------------------------
# Tajweed Rule Definitions
# -------------------------------
tajweed_rules = {
    "ikhfa":      { "example": "إِنَّ اللّٰهَ غَفُوْرٌ رَّحِیْمٌ", "highlight": "رَّحِیْمٌ" },
    "idgham":     { "example": "مِنْ لَّدُنْ حَكِيْمٍ خَبِيْرٍ", "highlight": "مِنْ لَّدُنْ" },
    "iqlab":      { "example": "يُنْبِتُ لَكُمْ بِهِ الزَّرْعَ", "highlight": "يُنْبِتُ" },
    "qalqala":    { "example": "وَالْفَجْرِ وَلَيَالٍ عَشْرٍ", "highlight": "الْفَجْرِ" },
    "ghunna":     { "example": "إِنَّا أَعْطَيْنَاكَ الْكَوْثَرَ", "highlight": "إِنَّا" },
    "madd":       { "example": "وَلَا الضَّالِّينَ", "highlight": "الضَّالِّينَ" },
    "waqf":       { "example": "وَقِفُوهُمْ ۖ إِنَّهُم مَّسْئُولُونَ", "highlight": "وَقِفُوهُمْ" },
    "noon_saakin": { "example": "فَإِن لَّمْ تَفْعَلُوا", "highlight": "فَإِن لَّمْ" },
    "meem_saakin": { "example": "وَأَنْفِقُوا فِي سَبِيلِ اللَّهِ", "highlight": "أَنْفِقُوا" },
    "tajweed":     { "example": "وَرَتِّلِ الْقُرْآنَ تَرْتِيلًا", "highlight": "تَرْتِيلًا" }
}

audio_map = { rule: f"/example_audio/{rule}.mp3" for rule in tajweed_rules }

rule_names = {
    "ikhfa": {"ur": "اخفاء", "ar": "الإخفاء"},
    "idgham": {"ur": "ادغام", "ar": "الإدغام"},
    "iqlab": {"ur": "اقلاب", "ar": "الإقلاب"},
    "qalqala": {"ur": "قلقلہ", "ar": "القلقلة"},
    "ghunna": {"ur": "غنہ", "ar": "الغنة"},
    "madd": {"ur": "مد", "ar": "المد"},
    "waqf": {"ur": "وقف", "ar": "الوقف"},
    "noon_saakin": {"ur": "نون ساکن", "ar": "النون الساكنة"},
    "meem_saakin": {"ur": "میم ساکن", "ar": "الميم الساكنة"},
    "tajweed": {"ur": "تجوید", "ar": "التجويد"}
}

def normalize(text):
    return (
        text.replace("إ", "ا").replace("أ", "ا").replace("آ", "ا")
            .replace("ى", "ي").replace("ة", "ه")
            .replace("ّ", "").replace("َ", "").replace("ُ", "").replace("ِ", "")
            .replace("ً", "").replace("ٌ", "").replace("ٍ", "").replace("ْ", "")
            .replace("ـ", "").replace("ٰ", "").replace("  ", "").replace(" ", "")
            .strip().lower()
    )

def analyze_tajweed(text, rule, lang="en"):
    user = normalize(text)
    info = tajweed_rules[rule]
    expected = normalize(info["example"])
    critical = normalize(info["highlight"])
    rule_ur = rule_names[rule]["ur"]
    rule_ar = rule_names[rule]["ar"]
    similarity = difflib.SequenceMatcher(None, user, expected).ratio()
    matched = critical in user or similarity > 0.75

    translations = {
        "en": {
            "ok":   f"✅ You correctly applied the rule <strong>{rule.replace('_',' ').title()}</strong>.",
            "miss": f"❌ You missed <span style='color:red;'>{info['highlight']}</span> — try again.",
        },
        "ur": {
            "ok":   f"✅ آپ نے {rule_ur} کا قاعدہ درست طور پر پڑھا۔",
            "miss": f"❌ آپ نے {rule_ur} کا قاعدہ درست نہیں پڑھا، دوبارہ کوشش کریں۔",
        },
        "ar": {
            "ok":   f"✅ لقد قرأت قاعدة {rule_ar} بشكل صحيح.",
            "miss": f"❌ لم تقرأ قاعدة {rule_ar} بشكل صحيح، أعد المحاولة.",
        }
    }

    return [translations.get(lang, translations["en"])["ok" if matched else "miss"]]

# -------------------------------
# Endpoint: Analyze Recitation
# -------------------------------
@app.route("/recite", methods=["POST"])
def recite():
    data = request.get_json()
    rule = data.get("rule", "").strip()
    lang = data.get("lang", "en")
    user_text = data.get("text", "").strip()

    if not rule or not user_text:
        return jsonify({"feedback": ["❌ Please select a rule and speak."], "audio_url": ""})

    feedback = analyze_tajweed(user_text, rule, lang)
    return jsonify({
        "feedback": feedback,
        "audio_url": audio_map.get(rule, "")
    })

# -------------------------------
# Endpoint: Record Progress
# -------------------------------
@app.route("/record_progress", methods=["POST"])
def record_progress():
    data = request.get_json()
    user = data.get("user", "guest")
    lang = data.get("lang")
    score = data.get("score")

    conn = sqlite3.connect(DB)
    conn.execute(
        "INSERT INTO attempts (user, lang, score) VALUES (?, ?, ?)",
        (user, lang, score)
    )
    conn.commit()
    conn.close()
    return jsonify({"status": "ok"})

# -------------------------------
# Endpoint: Fetch Progress Report
# -------------------------------
@app.route("/progress_report", methods=["GET"])
def progress_report():
    conn = sqlite3.connect(DB)
    cur = conn.execute("SELECT timestamp, user, lang, score FROM attempts ORDER BY timestamp DESC")
    rows = [{"time": r[0], "user": r[1], "lang": r[2], "score": r[3]} for r in cur]
    conn.close()
    return jsonify(rows)

# -------------------------------
# Launch App
# -------------------------------
if __name__ == "__main__":
    app.run(debug=True, port=5001)
