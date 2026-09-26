from gtts import gTTS
import os

# Urdu Tajweed rules
tajweed_rules = {
    "ikhfa": "اخفاء کا مطلب ہے نون ساکن یا تنوین کی آواز کو تھوڑا چھپانا۔",
    "idgham": "ادغام کا مطلب ہے نون ساکن یا تنوین کو اگلے حرف میں ملا دینا۔",
    "iqlab": "اقلاب کا مطلب ہے نون ساکن یا تنوین کو میم میں تبدیل کرنا غنہ کے ساتھ۔",
    "qalqala": "قلقلہ ان حروف ق، ط، ب، ج، د پر سکون کی حالت میں گونج دار آواز دینا ہے۔",
    "ghunna": "غنہ ایک ناک سے نکالی جانے والی آواز ہے جو دو حرکات تک کھینچی جاتی ہے۔",
    "madd": "مد کا مطلب ہے حرف علت کو دو، چار یا چھ حرکات تک کھینچنا۔",
    "waqf": "وقف کا مطلب ہے تلاوت کے دوران کسی علامت پر رکنا۔",
    "noon_saakin": "نون ساکن کے قواعد میں اخفاء، ادغام، اقلاب اور اظہار شامل ہیں۔",
    "meem_saakin": "میم ساکن کے قواعد میں اخفاء شفوی، ادغام شفوی، اور اظہار شفوی شامل ہیں۔",
    "tajweed": "تجوید قرآن کی درست ادائیگی کے اصولوں کا مجموعہ ہے۔"
}

# Output directory
output_dir = "urdu_audio"
os.makedirs(output_dir, exist_ok=True)

# Generate and save audio
for rule, text in tajweed_rules.items():
    tts = gTTS(text=text, lang='ur')
    filename = os.path.join(output_dir, f"{rule}.mp3")
    tts.save(filename)
    print(f"Saved: {filename}")
