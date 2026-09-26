from flask import Flask, request, jsonify
from flask_cors import CORS

app = Flask(__name__)
CORS(app)

def get_diamond(lang):
    if lang == "en":
        return '<span style="color:#007B5E; display:inline-block; margin-right:6px;">🔹</span>'
    elif lang in ["ar", "ur"]:
        return '<span style="color:#007B5E; display:inline-block; margin-left:6px;">🔹</span>'
    return '<span style="color:#007B5E;">🔹</span>'


# Responses with {diamond} as a placeholder
responses = {
    "ikhfa": {
        "en": "{diamond} Ikhfa means to slightly hide the sound of Noon Saakin or Tanween.",
        "ur": "اخفاء کا مطلب ہے نون ساکن یا تنوین کی آواز کو تھوڑا چھپانا۔ {diamond}",
        "ar": "الإخفاء يعني إخفاء صوت النون الساكنة أو التنوين قليلاً {diamond}",
        "example": "إِنَّ اللّٰهَ غَفُوْرٌ رَّحِیْمٌ",
        "example_audio": "example_audio/ikhfa.mp3",
        "highlight": "غَفُوْرٌ رَّحِیْمٌ",
        "explanation": {
            "en": "The Tanween in Ghafoor was blended with the Ra of Raheem.",
            "ur": "غفور کی تنوین کو رحیم کے را سے ملایا گیا ہے۔",
            "ar": "تم إدغام التنوين في حرف الراء في كلمة رحيم."
        }
    },
    "idgham": {
        "en": "{diamond} Idgham means merging the sound of Noon Saakin or Tanween into the next letter.",
        "ur": "ادغام کا مطلب ہے نون ساکن یا تنوین کو اگلے حرف میں ملا دینا۔ {diamond}",
        "ar": "الإدغام يعني إدخال النون الساكنة أو التنوين في الحرف التالي {diamond}",
        "example": "مِنْ لَّدُنْ حَكِيْمٍ خَبِيْرٍ",
        "example_audio": "example_audio/idgham.mp3",
        "highlight": "مِنْ لَّدُنْ",
        "explanation": {
            "en": "The Noon in 'min' merged into the Laam of 'Ladun'.",
            "ur": "من کی نون کو لدن کے لام کے ساتھ ملا دیا گیا ہے۔",
            "ar": "النون في 'من' أُدخلت في اللام في 'لدن'."
        }
    },
    "iqlab": {
        "en": "{diamond} Iqlab means changing Noon Saakin or Tanween into Meem with Ghunnah.",
        "ur": "اقلاب کا مطلب ہے نون ساکن یا تنوین کو میم میں تبدیل کرنا غنہ کے ساتھ۔ {diamond}",
        "ar": "الإقلاب يعني تحويل النون الساكنة أو التنوين إلى ميم مع غنة {diamond}",
        "example": "يُنْبِتُ لَكُمْ بِهِ الزَّرْعَ",
        "example_audio": "example_audio/iqlab.mp3",
        "highlight": "يُنْبِتُ",
        "explanation": {
            "en": "The Noon sound changed into Meem with Ghunnah.",
            "ur": "نون کی آواز کو میم میں غنہ کے ساتھ تبدیل کیا گیا ہے۔",
            "ar": "تم تحويل صوت النون إلى ميم مع غنة."
        }
    },
    "qalqala": {
        "en": "{diamond} Qalqala is the echoing sound on Qaf, Ta, Ba, Jeem, Dal with sukoon.",
        "ur": "قلقلہ ان حروف ق، ط، ب، ج، د پر سکون کی حالت میں گونج دار آواز دینا ہے۔ {diamond}",
        "ar": "القلقلة هي تكرار الصوت في الحروف ق، ط، ب، ج، د عند السكون {diamond}",
        "example": "وَالْفَجْرِ وَلَيَالٍ عَشْرٍ",
        "example_audio": "example_audio/qalqala.mp3",
        "highlight": "الْفَجْرِ",
        "explanation": {
            "en": "The letter with sukoon was echoed to produce qalqala.",
            "ur": "سکون والے حرف کو گونج کے ساتھ ادا کیا گیا ہے۔",
            "ar": "الحرف الساكن تم تكرار صوته لإظهار القلقلة."
        }
    },
    "ghunna": {
        "en": "{diamond} Ghunna is the nasal sound that continues for two counts.",
        "ur": "غنہ ایک ناک سے نکالی جانے والی آواز ہے جو دو حرکات تک کھینچی جاتی ہے۔ {diamond}",
        "ar": "الغنة هي صوت أنفي يستمر بمقدار حركتين {diamond}",
        "example": "إِنَّا أَعْطَيْنَاكَ الْكَوْثَرَ",
        "example_audio": "example_audio/ghunna.mp3",
        "highlight": "إِنَّا",
        "explanation": {
            "en": "The nasal sound of Noon Mushaddad continued for 2 counts.",
            "ur": "نون مشدد کی ناک کی آواز کو دو حرکات تک کھینچا گیا۔",
            "ar": "استمر صوت النون المشددة الأنفي لمدة حركتين."
        }
    },
    "madd": {
        "en": "{diamond} Madd means to stretch a vowel sound for 2, 4, or 6 counts.",
        "ur": "مد کا مطلب ہے حرف علت کو 2، 4 یا 6 حرکات تک کھینچنا۔ {diamond}",
        "ar": "يعني إطالة الصوت بالحرف بمقدار 2 أو 4 أو 6 حركات {diamond}",
        "example": "وَلَا الضَّالِّينَ",
        "example_audio": "example_audio/madd.mp3",
        "highlight": "الضَّالِّينَ",
        "explanation": {
            "en": "The alif was stretched for 4 counts in the word 'Dhaalleen'.",
            "ur": "'ضالین' میں الف کو چار حرکات تک کھینچا گیا ہے۔",
            "ar": "تم إطالة الألف في كلمة 'الضالين' بمقدار أربع حركات."
        }
    },
    "waqf": {
        "en": "{diamond} Waqf refers to pausing at certain symbols during recitation.",
        "ur": "وقف کا مطلب ہے قراءت کے دوران مخصوص علامات پر رکنا۔ {diamond}",
        "ar": "الوقف يعني التوقف عند رموز معينة أثناء التلاوة {diamond}",
        "example": "وَقِفُوهُمْ ۖ إِنَّهُم مَّسْئُولُونَ",
        "example_audio": "example_audio/waqf.mp3",
        "highlight": "وَقِفُوهُمْ",
        "explanation": {
            "en": "Waqf is observed at the stop mark to reflect a pause in recitation.",
            "ur": "وقف کی علامت پر رک کر قراءت میں وقفہ کیا جاتا ہے۔",
            "ar": "يُوقف عند علامة الوقف لإظهار التوقف أثناء التلاوة."
        }
    },
    "noon saakin": {
        "en": "{diamond} Noon Saakin rules cover its pronunciation with letters following it.",
        "ur": "نون ساکن کے قواعد اس کی اگلے حروف کے ساتھ ادائیگی پر مشتمل ہوتے ہیں۔ {diamond}",
        "ar": "قواعد النون الساكنة تتعلق بطريقة نطقها عند مجيء حروف بعدها {diamond}",
        "example": "فَإِن لَّمْ تَفْعَلُوا",
        "example_audio": "example_audio/noon_saakin.mp3",
        "highlight": "فَإِن لَّمْ",
        "explanation": {
            "en": "Noon Saakin is pronounced with different rules based on the next letter.",
            "ur": "نون ساکن کی ادائیگی اگلے حرف کے لحاظ سے مختلف ہوتی ہے۔",
            "ar": "النون الساكنة تُنطق بطرق مختلفة حسب الحرف الذي يليها."
        }
    },
    "meem saakin": {
        "en": "{diamond} Meem Saakin rules deal with its pronunciation when followed by other letters.",
        "ur": "میم ساکن کے قواعد اس کی اگلے حروف کے ساتھ ادائیگی کو بیان کرتے ہیں۔ {diamond}",
        "ar": "قواعد الميم الساكنة تحدد كيفية نطقها عند التقاءها بحروف أخرى {diamond}",
        "example": "وَأَنْفِقُوا فِي سَبِيلِ اللَّهِ",
        "example_audio": "example_audio/meem_saakin.mp3",
        "highlight": "أَنْفِقُوا",
        "explanation": {
            "en": "Meem Saakin is pronounced clearly or merged based on the next letter.",
            "ur": "میم ساکن کو واضح یا ملا کر پڑھا جاتا ہے اگلے حرف کے مطابق۔",
            "ar": "تُنطق الميم الساكنة بوضوح أو إدغام حسب الحرف التالي."
        }
    },
    "tajweed": {
    "en": "{diamond} Tajweed is the science of correct pronunciation of Quranic words.",
    "ur": "تجوید قرآن کے الفاظ کی درست ادائیگی کا علم ہے۔ {diamond}",
    "ar": "التجويد هو علم النطق الصحيح لكلمات القرآن الكريم {diamond}",
    "example": "وَرَتِّلِ الْقُرْآنَ تَرْتِيلًا",
    "example_audio": "example_audio/tajweed.mp3",
    "highlight": "تَرْتِيلًا",
    "explanation": {
        "en": "Tajweed is reflected in reciting the Quran with clarity and proper rules.",
        "ur": "تجوید قرآن کی واضح اور اصولی قراءت کو ظاہر کرتی ہے۔",
        "ar": "التجويد يظهر في تلاوة القرآن بوضوح واتباع القواعد."
    }
},
}
def default_explanation(lang):
    msg = {
        "en": "{diamond} Please ask about a Tajweed rule like Ikhfa, Idgham, or Qalqala.",
        "ur": "براہ کرم تجوید کے اصول جیسے اخفاء، ادغام یا قلقلہ کے بارے میں پوچھیں۔ {diamond}",
        "ar": "من فضلك اسأل عن قاعدة من قواعد التجويد مثل الإخفاء، الإدغام، أو القلقلة {diamond}"
    }.get(lang, "{diamond} Please ask about a Tajweed rule.")

    return msg.replace("{diamond}", get_diamond(lang))

@app.route("/chat", methods=["POST"])
def chat():
    data = request.get_json()
    user_message = data.get("message", "").lower()
    lang = data.get("lang", "en")

    matched_rule = next((rule for rule in responses if rule in user_message), None)

    if matched_rule:
        response_data = responses[matched_rule]
        diamond = get_diamond(lang)

        raw_text = response_data.get(lang, "")
        reply = raw_text.replace("{diamond}", diamond)

        example = response_data.get("example")
        example_audio = response_data.get("example_audio")
        rule_explanation = response_data.get("explanation", {}).get(lang, "")
        highlight = response_data.get("highlight", "")

        reply += f"<br><br><strong>📖 Example Ayah:</strong><br><span style='font-size:22px;color:#007B5E;'>{example}</span>"
        reply += f"<br><br><strong>🔍 Rule Applied:</strong> <em>{rule_explanation}</em>"

        return jsonify({
    "reply": reply,
    "example_audio": example_audio,
    "rule_explanation": rule_explanation,
    "highlight": highlight,
    "matched": True
})
    else:
        # If no specific rule matched, return a default explanation
      return jsonify({
    "reply": default_explanation(lang),
    "example_audio": "",
    "rule_explanation": "",
    "highlight": "",
    "matched": False
})

    return jsonify({
        "reply": "Sorry, I didn't understand that. Please ask about a Tajweed rule.",
        "example_audio": "",
        "rule_explanation": "",
        "highlight": "",
        "matched": False
    })

if __name__ == "__main__":
    app.run(debug=True)
